# 🔑 Update Kredensial Supabase Storage

## ⚠️ Error yang Terjadi

```
SignatureDoesNotMatch: The request signature we calculated does not match 
the signature you provided. Check your key and signing method.
```

Ini berarti `AWS_ACCESS_KEY_ID` atau `AWS_SECRET_ACCESS_KEY` tidak valid atau sudah expired.

---

## 📋 Cara Mendapatkan Kredensial Baru

### 1️⃣ Buka Supabase Dashboard

Klik link ini: https://supabase.com/dashboard/project/twbvqgjedeapqszljzox/settings/api

### 2️⃣ Dapatkan S3 Access Keys

1. Di dashboard, klik **Settings** (ikon gear di sidebar kiri)
2. Klik **API**
3. Scroll ke bawah sampai menemukan bagian **S3 Access Keys**
4. Jika belum ada keys, klik **Generate new keys**
5. Copy kedua keys:
   - **Access Key ID** (seperti: `b69d16c86ee7174a99b3ac205ff247cf`)
   - **Secret Access Key** (seperti: `6be2114610680bd3f68e3ffda33769bd7a986f05defde5848567144c6f4c1a49`)

### 3️⃣ Pastikan Bucket Sudah Dibuat

1. Klik **Storage** di sidebar
2. Pastikan bucket dengan nama **`produk`** sudah ada
3. Jika belum ada, klik **New bucket**:
   - Name: `produk`
   - Public bucket: **ON** (agar file bisa diakses publik)
   - Klik **Create bucket**

### 4️⃣ Update File .env

Buka file `.env` dan update baris berikut dengan kredensial baru:

```env
AWS_ACCESS_KEY_ID=PASTE_ACCESS_KEY_ID_DISINI
AWS_SECRET_ACCESS_KEY=PASTE_SECRET_ACCESS_KEY_DISINI
```

**Contoh:**
```env
AWS_ACCESS_KEY_ID=abc123def456ghi789
AWS_SECRET_ACCESS_KEY=xyz789uvw456rst123qpo456mno789lkj123hij456
```

### 5️⃣ Test Koneksi Lagi

```bash
php artisan config:clear
php artisan railway:test
```

Jika berhasil, Anda akan melihat:
```
✅ Railway MySQL Connected!
✅ Supabase Storage Connected!
   ✓ Upload test: SUCCESS
   ✓ Delete test: SUCCESS
```

---

## 🔍 Verifikasi Bucket Policy

Jika masih error setelah update kredensial, periksa bucket policy:

1. Buka **Storage** → Klik bucket **`produk`**
2. Klik tab **Policies**
3. Pastikan ada policy untuk **SELECT** (read) dan **INSERT** (write)

**Contoh Policy untuk Public Bucket:**

```sql
-- Allow public read access
CREATE POLICY "Public Access"
ON storage.objects FOR SELECT
USING ( bucket_id = 'produk' );

-- Allow authenticated users to upload
CREATE POLICY "Authenticated users can upload"
ON storage.objects FOR INSERT
WITH CHECK ( bucket_id = 'produk' AND auth.role() = 'authenticated' );
```

Atau lebih simple, set bucket menjadi **Public** di settings bucket.

---

## 📸 Screenshot Lokasi Kredensial

Kredensial S3 Access Keys ada di:
```
Supabase Dashboard
└── Settings (⚙️)
    └── API
        └── S3 Access Keys
            ├── Access Key ID
            └── Secret Access Key
```

---

## ✅ Checklist

- [ ] Buka Supabase dashboard
- [ ] Masuk ke Settings → API
- [ ] Copy Access Key ID
- [ ] Copy Secret Access Key
- [ ] Pastikan bucket `produk` sudah dibuat
- [ ] Set bucket menjadi Public
- [ ] Update `.env` dengan kredensial baru
- [ ] Jalankan `php artisan config:clear`
- [ ] Test dengan `php artisan railway:test`
- [ ] Lihat hasil: ✅ Supabase Storage Connected!

---

## 🆘 Masih Error?

Jika masih error setelah update kredensial:

1. **Regenerate keys** di Supabase dashboard
2. **Hapus dan buat ulang bucket** `produk`
3. Pastikan **region** di `.env` sesuai dengan region project Supabase
4. Cek **CORS settings** di Supabase Storage
5. Pastikan tidak ada **typo** saat copy-paste kredensial

---

## 💡 Tips

- Jangan share kredensial `AWS_SECRET_ACCESS_KEY` ke siapapun
- Jangan commit file `.env` ke Git
- Simpan kredensial di password manager
- Rotate keys secara berkala untuk keamanan
