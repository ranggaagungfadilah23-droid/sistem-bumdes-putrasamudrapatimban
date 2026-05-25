<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

/**
 * Storage Service untuk file upload
 * Menggunakan Laravel Storage (bisa local atau S3)
 */
class StorageService
{
    /**
     * Upload file dengan auto-generated filename
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $directory Directory dalam storage (e.g., 'products', 'documents')
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
            
            // Store file
            $stored = $file->storeAs($directory, $filename, 'public');

            if ($stored) {
                return [
                    'success' => true,
                    'path' => $stored,
                    'url' => Storage::url($stored),
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
     * @param string $path Path file dalam storage
     * @return bool
     */
    public function delete($path)
    {
        try {
            return Storage::disk('public')->delete($path);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get public URL
     * 
     * @param string $path Path file dalam storage
     * @return string
     */
    public function url($path)
    {
        return Storage::url($path);
    }

    /**
     * Check if file exists
     * 
     * @param string $path
     * @return bool
     */
    public function exists($path)
    {
        return Storage::disk('public')->exists($path);
    }
}
