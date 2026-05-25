# 📦 Contoh Penggunaan Supabase Storage

## Cara Upload File ke Supabase Storage

### 1. Menggunakan StorageService (Recommended)

```php
use App\Services\StorageService;

class ProductController extends Controller
{
    protected $storage;

    public function __construct(StorageService $storage)
    {
        $this->storage = $storage;
    }

    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'name' => 'required',
            'image' => 'required|image|max:2048', // max 2MB
        ]);

        // Upload gambar produk
        $upload = $this->storage->uploadProductImage($request->file('image'));

        if ($upload['success']) {
            // Simpan ke database
            $product = Product::create([
                'name' => $request->name,
                'image_path' => $upload['path'],
                'image_url' => $upload['url'],
            ]);

            return response()->json([
                'message' => 'Product created successfully',
                'product' => $product,
            ]);
        }

        return response()->json([
            'message' => 'Failed to upload image',
            'error' => $upload['error'],
        ], 500);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Delete file dari storage
        if ($product->image_path) {
            $this->storage->delete($product->image_path);
        }

        // Delete dari database
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully',
        ]);
    }
}
```

### 2. Menggunakan Storage Facade Langsung

```php
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,doc,docx|max:5120', // max 5MB
        ]);

        $file = $request->file('document');
        
        // Generate unique filename
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = 'documents/' . $filename;

        // Upload ke Supabase Storage
        $uploaded = Storage::disk('s3')->put($path, file_get_contents($file), 'public');

        if ($uploaded) {
            $url = Storage::disk('s3')->url($path);

            return response()->json([
                'success' => true,
                'path' => $path,
                'url' => $url,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Upload failed',
        ], 500);
    }

    public function delete($path)
    {
        $deleted = Storage::disk('s3')->delete($path);

        return response()->json([
            'success' => $deleted,
            'message' => $deleted ? 'File deleted' : 'File not found',
        ]);
    }

    public function getUrl($path)
    {
        $url = Storage::disk('s3')->url($path);

        return response()->json([
            'url' => $url,
        ]);
    }
}
```

### 3. Upload Multiple Files

```php
public function uploadMultiple(Request $request)
{
    $request->validate([
        'images' => 'required|array',
        'images.*' => 'image|max:2048',
    ]);

    $uploadedFiles = [];

    foreach ($request->file('images') as $image) {
        $upload = $this->storage->uploadProductImage($image);
        
        if ($upload['success']) {
            $uploadedFiles[] = $upload;
        }
    }

    return response()->json([
        'message' => 'Files uploaded successfully',
        'files' => $uploadedFiles,
        'count' => count($uploadedFiles),
    ]);
}
```

### 4. Update File (Replace)

```php
public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);

    if ($request->hasFile('image')) {
        // Delete old image
        if ($product->image_path) {
            $this->storage->delete($product->image_path);
        }

        // Upload new image
        $upload = $this->storage->uploadProductImage($request->file('image'));

        if ($upload['success']) {
            $product->update([
                'image_path' => $upload['path'],
                'image_url' => $upload['url'],
            ]);
        }
    }

    return response()->json([
        'message' => 'Product updated successfully',
        'product' => $product,
    ]);
}
```

## Struktur Folder di Supabase Storage

```
produk/  (bucket)
├── products/
│   ├── 1234567890_abc123.jpg
│   ├── 1234567891_def456.jpg
│   └── ...
├── documents/
│   ├── 1234567890_invoice.pdf
│   ├── 1234567891_report.docx
│   └── ...
├── avatars/
│   ├── user_1.jpg
│   └── ...
└── temp/
    └── ...
```

## Validasi File

### Validasi Gambar

```php
$request->validate([
    'image' => [
        'required',
        'image',                    // harus gambar
        'mimes:jpeg,png,jpg,gif',   // format yang diizinkan
        'max:2048',                 // max 2MB
        'dimensions:min_width=100,min_height=100', // minimal 100x100px
    ],
]);
```

### Validasi Dokumen

```php
$request->validate([
    'document' => [
        'required',
        'file',
        'mimes:pdf,doc,docx,xls,xlsx',
        'max:5120', // max 5MB
    ],
]);
```

## Helper Functions

Tambahkan di `app/Helpers/StorageHelper.php`:

```php
<?php

if (!function_exists('upload_image')) {
    /**
     * Upload image to Supabase Storage
     */
    function upload_image($file, $directory = 'images')
    {
        $storage = app(\App\Services\StorageService::class);
        return $storage->upload($file, $directory);
    }
}

if (!function_exists('delete_file')) {
    /**
     * Delete file from Supabase Storage
     */
    function delete_file($path)
    {
        $storage = app(\App\Services\StorageService::class);
        return $storage->delete($path);
    }
}

if (!function_exists('storage_url')) {
    /**
     * Get public URL for file
     */
    function storage_url($path)
    {
        return Storage::disk('s3')->url($path);
    }
}
```

Daftarkan di `composer.json`:

```json
"autoload": {
    "files": [
        "app/Helpers/StorageHelper.php"
    ]
}
```

Jalankan: `composer dump-autoload`

## Blade Template

### Form Upload

```blade
<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="form-group">
        <label>Product Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Product Image</label>
        <input type="file" name="image" class="form-control" accept="image/*" required>
    </div>

    <button type="submit" class="btn btn-primary">Upload</button>
</form>
```

### Display Image

```blade
@if($product->image_url)
    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-fluid">
@else
    <img src="{{ asset('images/no-image.png') }}" alt="No image" class="img-fluid">
@endif
```

## Tips & Best Practices

1. **Selalu validasi file** sebelum upload
2. **Generate unique filename** untuk menghindari konflik
3. **Delete old file** saat update
4. **Gunakan try-catch** untuk error handling
5. **Set max file size** sesuai kebutuhan
6. **Compress image** sebelum upload jika perlu
7. **Gunakan queue** untuk upload file besar
8. **Backup file penting** secara berkala

## Troubleshooting

### Upload gagal
- Periksa kredensial AWS di `.env`
- Pastikan bucket `produk` sudah dibuat
- Cek file size tidak melebihi limit
- Periksa bucket policy (public/private)

### URL tidak bisa diakses
- Pastikan bucket policy set ke public
- Periksa `AWS_URL` di `.env`
- Cek CORS settings di Supabase dashboard

### Error "Bucket not found"
- Buat bucket `produk` di Supabase dashboard
- Atau ubah `AWS_BUCKET` di `.env` sesuai nama bucket Anda
