<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

/**
 * Supabase Storage Service
 * 
 * Service untuk mengelola file storage di Supabase
 * Database menggunakan Railway PostgreSQL
 */
class SupabaseService
{
    protected $disk;
    protected $bucket;

    public function __construct()
    {
        $this->disk = Storage::disk('s3');
        $this->bucket = config('supabase.storage.bucket', 'produk');
    }

    /**
     * Upload file to Supabase Storage
     * 
     * @param string $path File path in bucket (e.g., 'produk/image.jpg')
     * @param mixed $file File content, file path, or UploadedFile
     * @param array $options Additional options
     * @return array
     */
    public function upload($path, $file, $options = [])
    {
        try {
            if (is_string($file) && file_exists($file)) {
                // If file is a local path
                $result = $this->disk->put($path, file_get_contents($file));
            } else {
                // If file is UploadedFile or content
                $result = $this->disk->put($path, $file);
            }

            if ($result) {
                return [
                    'success' => true,
                    'path' => $path,
                    'url' => $this->disk->url($path),
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
     * Upload file dengan nama otomatis
     * 
     * @param string $directory Directory path (e.g., 'produk', 'jasa')
     * @param mixed $file UploadedFile
     * @param string|null $customName Custom filename (optional)
     * @return array
     */
    public function uploadWithAutoName($directory, $file, $customName = null)
    {
        try {
            if (!$file) {
                return [
                    'success' => false,
                    'error' => 'No file provided',
                ];
            }

            // Generate filename
            if ($customName) {
                $filename = $customName;
            } else {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            }

            $path = $directory . '/' . $filename;

            return $this->upload($path, $file);
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Delete file from Supabase Storage
     * 
     * @param string $path File path in bucket
     * @return array
     */
    public function delete($path)
    {
        try {
            $result = $this->disk->delete($path);

            return [
                'success' => $result,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Delete multiple files
     * 
     * @param array $paths Array of file paths
     * @return array
     */
    public function deleteMultiple(array $paths)
    {
        try {
            $result = $this->disk->delete($paths);

            return [
                'success' => $result,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get public URL for a file
     * 
     * @param string $path File path in bucket
     * @return string
     */
    public function getUrl($path)
    {
        return $this->disk->url($path);
    }

    /**
     * Check if file exists
     * 
     * @param string $path File path in bucket
     * @return bool
     */
    public function exists($path)
    {
        return $this->disk->exists($path);
    }

    /**
     * List files in a directory
     * 
     * @param string $directory Directory path (optional)
     * @return array
     */
    public function listFiles($directory = '')
    {
        try {
            $files = $this->disk->files($directory);

            return [
                'success' => true,
                'files' => $files,
                'count' => count($files),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * List directories
     * 
     * @param string $directory Directory path (optional)
     * @return array
     */
    public function listDirectories($directory = '')
    {
        try {
            $directories = $this->disk->directories($directory);

            return [
                'success' => true,
                'directories' => $directories,
                'count' => count($directories),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get file size
     * 
     * @param string $path File path in bucket
     * @return int|false File size in bytes or false
     */
    public function getSize($path)
    {
        try {
            return $this->disk->size($path);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get file last modified time
     * 
     * @param string $path File path in bucket
     * @return int|false Unix timestamp or false
     */
    public function getLastModified($path)
    {
        try {
            return $this->disk->lastModified($path);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Copy file
     * 
     * @param string $from Source path
     * @param string $to Destination path
     * @return array
     */
    public function copy($from, $to)
    {
        try {
            $result = $this->disk->copy($from, $to);

            return [
                'success' => $result,
                'url' => $this->disk->url($to),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Move file
     * 
     * @param string $from Source path
     * @param string $to Destination path
     * @return array
     */
    public function move($from, $to)
    {
        try {
            $result = $this->disk->move($from, $to);

            return [
                'success' => $result,
                'url' => $this->disk->url($to),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get storage info
     * 
     * @return array
     */
    public function getStorageInfo()
    {
        return [
            'bucket' => $this->bucket,
            'endpoint' => config('supabase.storage.endpoint'),
            'url' => config('supabase.storage.url'),
            'region' => config('supabase.storage.region'),
        ];
    }
}
