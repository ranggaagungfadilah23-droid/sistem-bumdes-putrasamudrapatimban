<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

/**
 * Storage Service untuk Supabase Storage
 * 
 * Helper class untuk upload, delete, dan manage files di Supabase Storage
 */
class StorageService
{
    protected $disk;

    public function __construct()
    {
        $this->disk = Storage::disk('s3');
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
            
            $uploaded = $this->disk->put($path, file_get_contents($file), 'public');

            if ($uploaded) {
                return [
                    'success' => true,
                    'path' => $path,
                    'url' => $this->disk->url($path),
                    'filename' => $filename,
                ];
            }

            return [
                'success' => false,
                'error' => 'Failed to upload file',
            ];
        } catch (\Exception $e) {
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
            return $this->disk->delete($path);
        } catch (\Exception $e) {
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
        return $this->disk->url($path);
    }

    /**
     * Check if file exists
     * 
     * @param string $path
     * @return bool
     */
    public function exists($path)
    {
        return $this->disk->exists($path);
    }
}
