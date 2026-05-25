<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Supabase Storage Service menggunakan REST API
 * Alternatif untuk S3 API yang lebih mudah digunakan
 */
class SupabaseStorageService
{
    protected $url;
    protected $bucket;
    protected $headers;

    public function __construct()
    {
        $this->url = 'https://twbvqgjedeapqszljzox.supabase.co';
        $this->bucket = 'produk';
        
        // Gunakan service_role key untuk backend operations
        // Atau anon key jika bucket public
        $apiKey = env('SUPABASE_SERVICE_KEY', env('SUPABASE_KEY'));
        
        $this->headers = [
            'apikey' => $apiKey,
            'Authorization' => 'Bearer ' . $apiKey,
        ];
    }

    /**
     * Upload file ke Supabase Storage
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $path Path dalam bucket (e.g., 'products/image.jpg')
     * @return array
     */
    public function upload($file, $path)
    {
        try {
            $response = Http::withHeaders($this->headers)
                ->attach('file', file_get_contents($file), $file->getClientOriginalName())
                ->post("{$this->url}/storage/v1/object/{$this->bucket}/{$path}");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'path' => $path,
                    'url' => $this->getPublicUrl($path),
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
     * Upload dengan auto-generated filename
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $directory
     * @return array
     */
    public function uploadAuto($file, $directory = 'uploads')
    {
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '_' . uniqid() . '.' . $extension;
        $path = $directory . '/' . $filename;

        return $this->upload($file, $path);
    }

    /**
     * Delete file
     * 
     * @param string $path
     * @return array
     */
    public function delete($path)
    {
        try {
            $response = Http::withHeaders($this->headers)
                ->delete("{$this->url}/storage/v1/object/{$this->bucket}/{$path}");

            return [
                'success' => $response->successful(),
                'message' => $response->successful() ? 'File deleted' : 'Delete failed',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get public URL
     * 
     * @param string $path
     * @return string
     */
    public function getPublicUrl($path)
    {
        return "{$this->url}/storage/v1/object/public/{$this->bucket}/{$path}";
    }

    /**
     * List files
     * 
     * @param string $folder
     * @return array
     */
    public function listFiles($folder = '')
    {
        try {
            $response = Http::withHeaders($this->headers)
                ->post("{$this->url}/storage/v1/object/list/{$this->bucket}", [
                    'prefix' => $folder,
                ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'files' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'error' => 'Failed to list files',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Test connection
     * 
     * @return bool
     */
    public function testConnection()
    {
        try {
            $response = Http::withHeaders($this->headers)
                ->get("{$this->url}/storage/v1/bucket/{$this->bucket}");

            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}
