<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Storage Service untuk Supabase Storage menggunakan REST API
 */
class StorageService
{
    protected $url;
    protected $bucket;
    protected $apiKey;

    public function __construct()
    {
        $this->url = config('supabase.url', 'https://twbvqgjedeapqszljzox.supabase.co');
        $this->bucket = config('supabase.storage.bucket', 'produk');
        $this->apiKey = config('supabase.service_key');
    }

    /**
     * Upload file dengan auto-generated filename
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $directory Directory dalam bucket (e.g., 'products', 'documents')
     * @param string|null $customName Custom filename (optional)
     * @return array ['success' => bool, 'path' => string, 'url' => string]
     */
    public function upload($file, $directory = '', $customName = null)
    {
        try {
            if ($customName) {
                $filename = $customName;
            } else {
                // Generate unique filename
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '_' . uniqid() . '.' . $extension;
            }

            $path = $directory ? $directory . '/' . $filename : $filename;
            
            // Upload menggunakan Supabase REST API
            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => $file->getMimeType(),
            ])->withBody(file_get_contents($file->getRealPath()), $file->getMimeType())
              ->post("{$this->url}/storage/v1/object/{$this->bucket}/{$path}");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'path' => $path,
                    'url' => $this->url($path),
                    'filename' => $filename,
                ];
            }

            return [
                'success' => false,
                'error' => $response->json()['message'] ?? 'Upload failed',
            ];
        } catch (\Exception $e) {
            Log::error('Supabase upload error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Upload gambar produk
     * 
     * @param \Illuminate\Http\UploadedFile $image
     * @return array
     */
    public function uploadProductImage($image)
    {
        return $this->upload($image, 'products');
    }

    /**
     * Upload dokumen
     * 
     * @param \Illuminate\Http\UploadedFile $document
     * @return array
     */
    public function uploadDocument($document)
    {
        return $this->upload($document, 'documents');
    }

    /**
     * Delete file
     * 
     * @param string $path Path file dalam bucket
     * @return bool
     */
    public function delete($path)
    {
        try {
            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->delete("{$this->url}/storage/v1/object/{$this->bucket}/{$path}");

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Supabase delete error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get public URL
     * 
     * @param string $path Path file dalam bucket
     * @return string
     */
    public function url($path)
    {
        return "{$this->url}/storage/v1/object/public/{$this->bucket}/{$path}";
    }

    /**
     * Check if file exists
     * 
     * @param string $path
     * @return bool
     */
    public function exists($path)
    {
        try {
            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->head("{$this->url}/storage/v1/object/public/{$this->bucket}/{$path}");

            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}
