# 🚀 Quick Start - Setup Database & Storage

## Setup Cepat (5 Menit)

### 1️⃣ Dapatkan Kredensial Railway MySQL

Buka Railway dashboard dan copy connection string:

```
mysql://root:PASSWORD@roundhouse.proxy.rlwy.net:24334/railway
```

Dari string di atas, ambil:
- **Host**: `roundhouse.proxy.rlwy.net`
- **Port**: `24334`
- **Database**: `railway`
- **Username**: `root`
- **Password**: `PASSWORD`

### 2️⃣ Update File .env

Buka file `.env` dan isi:

```env
DB_CONNECTION=mysql
DB_HOST=roundhouse.proxy.rlwy.net
DB_PORT=24334
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=your_password_here
```

**Supabase Storage sudah OK**, tidak perlu diubah:
```env
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=b69d16c86ee7174a99b3ac205ff247cf
AWS_SECRET_ACCESS_KEY=6be2114610680bd3f68e3ffda33769bd7a986f05defde5848567144c6f4c1a49
AWS_BUCKET=produk
AWS_ENDPOINT=https://twbvqgjedeapqszljzox.supabase.co/storage/v1/s3
```

### 3️⃣ Test Koneksi (Tidak Perlu Install Extension)

MySQL driver sudah built-in di XAMPP, langsung test saja:

```bash
php artisan config:clear
php artisan railway:test
```

Jika berhasil:
```
✅ Railway MySQL Connected!
✅ Supabase Storage Connected!
```

### 4️⃣ Migrasi Database

```bash
php artisan migrate
```

## ✅ Selesai!

Aplikasi siap digunakan dengan:
- 🚂 **Database**: Railway MySQL
- 💾 **Storage**: Supabase S3 (gambar & dokumen)

---

## 🔧 Troubleshooting Cepat

### Error: "could not find driver"
→ Extension MySQL belum aktif (jarang terjadi), edit `php.ini` dan restart Apache

### Error: "Connection refused" atau "Access denied"
→ Periksa kredensial Railway di `.env`

### Error: "Unknown database"
→ Pastikan `DB_DATABASE=railway` di `.env`

---

📚 **Dokumentasi Lengkap**: Lihat `RAILWAY_SUPABASE_SETUP.md`
