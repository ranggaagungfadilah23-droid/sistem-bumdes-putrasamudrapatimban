# 🚂 Setup Railway MySQL + Supabase Storage

## Arsitektur Aplikasi

```
┌─────────────────────────────────────────┐
│         Aplikasi Laravel                │
├─────────────────────────────────────────┤
│                                         │
│  ┌──────────────┐    ┌──────────────┐  │
│  │   Database   │    │   Storage    │  │
│  │  (Railway)   │    │  (Supabase)  │  │
│  │    MySQL     │    │  S3 Storage  │  │
│  └──────────────┘    └──────────────┘  │
│                                         │
└─────────────────────────────────────────┘
```

- **Railway**: Database MySQL (data aplikasi)
- **Supabase**: Storage S3 (gambar produk, dokumen, file upload)

---

## 📋 Langkah Setup

### 1️⃣ Dapatkan Kredensial Railway MySQL

1. Login ke Railway: https://railway.app/
2. Pilih project MySQL Anda
3. Klik tab **Variables** atau **Connect**
4. Copy kredensial berikut:
   - `MYSQLHOST` → untuk `DB_HOST`
   - `MYSQLPORT` → untuk `DB_PORT`
   - `MYSQLDATABASE` → untuk `DB_DATABASE`
   - `MYSQLUSER` → untuk `DB_USERNAME`
   - `MYSQLPASSWORD` → untuk `DB_PASSWORD`

**Contoh Connection String Railway:**
```
mysql://root:PASSWORD@roundhouse.proxy.rlwy.net:24334/railway
```

Dari string di atas:
- Host: `roundhouse.proxy.rlwy.net`
- Port: `24334`
- Database: `railway`
- Username: `root`
- Password: `PASSWORD`

### 2️⃣ Dapatkan Kredensial Supabase Storage (Opsional)

Supabase Storage sudah terkonfigurasi dengan baik di `.env` Anda:

```env
AWS_ACCESS_KEY_ID=b69d16c86ee7174a99b3ac205ff247cf
AWS_SECRET_ACCESS_KEY=6be2114610680bd3f68e3ffda33769bd7a986f05defde5848567144c6f4c1a49
AWS_ENDPOINT=https://twbvqgjedeapqszljzox.supabase.co/storage/v1/s3
```

Jika perlu update atau verifikasi:
1. Buka: https://supabase.com/dashboard/project/twbvqgjedeapqszljzox
2. Klik **Settings** → **API**
3. Scroll ke **S3 Access Keys**
4. Copy `Access Key ID` dan `Secret Access Key`

### 3️⃣ Update File .env

Buka file `.env` dan isi kredensial Railway MySQL:

```env
# Railway MySQL Database Configuration
DB_CONNECTION=mysql
DB_HOST=roundhouse.proxy.rlwy.net
DB_PORT=24334
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=your_railway_password_here

# Supabase Storage Configuration (sudah OK, tidak perlu diubah)
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=b69d16c86ee7174a99b3ac205ff247cf
AWS_SECRET_ACCESS_KEY=6be2114610680bd3f68e3ffda33769bd7a986f05defde5848567144c6f4c1a49
AWS_DEFAULT_REGION=ap-southeast-1
AWS_BUCKET=produk
AWS_USE_PATH_STYLE_ENDPOINT=true
AWS_ENDPOINT=https://twbvqgjedeapqszljzox.supabase.co/storage/v1/s3
AWS_URL=https://twbvqgjedeapqszljzox.supabase.co/storage/v1/object/public
```

### 4️⃣ Test Koneksi (Tidak Perlu Install Extension PostgreSQL)

Railway MySQL menggunakan driver MySQL yang sudah built-in di PHP/XAMPP, jadi tidak perlu install extension tambahan.

### 5️⃣ Test Koneksi

```bash
# Clear cache
php artisan config:clear

# Test koneksi database dan storage
php artisan railway:test
```

Jika berhasil, Anda akan melihat:
```
✅ Railway Database Connected!
✅ Supabase Storage Connected!
```

### 6️⃣ Migrasi Database (Jika Belum)

```bash
# Lihat status migrasi
php artisan migrate:status

# Jalankan migrasi
php artisan migrate

# Atau fresh install (HATI-HATI: akan hapus semua data)
php artisan migrate:fresh
```

---

## 🧪 Testing Upload File

Untuk test upload file ke Supabase Storage:

```php
use Illuminate\Support\Facades\Storage;

// Upload file
$path = Storage::disk('s3')->put('test', $request->file('image'));

// Get URL
$url = Storage::disk('s3')->url($path);

// Delete file
Storage::disk('s3')->delete($path);
```

---

## 🔧 Troubleshooting

### ❌ Error: "could not find driver"
**Penyebab:** Extension MySQL belum aktif (jarang terjadi di XAMPP)

**Solusi:**
1. Edit `C:\xampp\php\php.ini`
2. Pastikan uncomment: `extension=mysqli` dan `extension=pdo_mysql`
3. Restart Apache
4. Verifikasi: `php -m | findstr mysql`

### ❌ Error: "SQLSTATE[HY000] [2002] Connection refused"
**Penyebab:** Kredensial Railway salah atau koneksi internet bermasalah

**Solusi:**
1. Periksa kembali `DB_HOST`, `DB_PORT`, `DB_PASSWORD` di `.env`
2. Copy ulang dari Railway dashboard
3. Test koneksi internet

### ❌ Error: "Access denied for user"
**Penyebab:** Username atau password salah

**Solusi:**
1. Periksa `DB_USERNAME` dan `DB_PASSWORD` di `.env`
2. Copy ulang dari Railway Variables
3. Pastikan tidak ada spasi di awal/akhir password

### ❌ Storage upload gagal
**Penyebab:** Kredensial Supabase salah atau bucket tidak ada

**Solusi:**
1. Periksa `AWS_ACCESS_KEY_ID` dan `AWS_SECRET_ACCESS_KEY`
2. Pastikan bucket `produk` sudah dibuat di Supabase
3. Periksa bucket policy (harus public untuk read)

### ❌ Error: "Bucket not found"
**Solusi:**
1. Buka Supabase dashboard
2. Klik **Storage**
3. Buat bucket baru dengan nama `produk`
4. Set policy:
   - Public bucket: ON (jika ingin file bisa diakses publik)
   - Atau buat policy custom

---

## 📊 Monitoring

### Railway Database
- Dashboard: https://railway.app/
- Metrics: CPU, Memory, Disk usage
- Logs: Real-time database logs
- Database: MySQL 8.x

### Supabase Storage
- Dashboard: https://supabase.com/dashboard/project/twbvqgjedeapqszljzox
- Storage usage: Settings → Billing
- File browser: Storage → produk

---

## 🔐 Keamanan

### Railway Database
- ✅ SSL/TLS encryption enabled
- ✅ Private network (tidak bisa diakses publik tanpa kredensial)
- ✅ Automatic backups (tergantung plan)
- ✅ MySQL 8.x dengan InnoDB engine

### Supabase Storage
- ✅ S3-compatible encryption
- ⚠️ Pastikan bucket policy sesuai kebutuhan:
  - Public: Untuk gambar produk yang perlu diakses publik
  - Private: Untuk dokumen sensitif

**Rekomendasi:**
1. Jangan commit file `.env` ke Git
2. Gunakan environment variables di production
3. Rotate credentials secara berkala
4. Enable Row Level Security (RLS) jika perlu

---

## 📚 Resources

- [Railway Docs](https://docs.railway.app/)
- [Supabase Storage Docs](https://supabase.com/docs/guides/storage)
- [Laravel Filesystem](https://laravel.com/docs/filesystem)
- [MySQL 8.x Docs](https://dev.mysql.com/doc/refman/8.0/en/)

---

## 🆘 Butuh Bantuan?

1. Test koneksi: `php artisan railway:test`
2. Cek logs: `storage/logs/laravel.log`
3. Railway support: https://railway.app/help
4. Supabase support: https://supabase.com/support
