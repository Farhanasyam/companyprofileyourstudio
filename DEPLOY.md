# Deploy ke Web Hosting (cara mudah)

Website ini sudah disiapkan untuk hosting: CSS, JS, dan gambar memakai **path relatif** (`/css/...`, `/storage/...`) sehingga tidak bergantung ke `APP_URL`. Cukup ikuti langkah di bawah.

## 1. Document root → folder `public`
Arahkan document root hosting ke folder **`public`** project.
- Contoh: project di `public_html/companyprofileyourstudio` → document root = `public_html/companyprofileyourstudio/public`

## 2. File .env
- Copy `.env.example` ke `.env`
- Isi `APP_URL` dengan URL asli (mis. `https://domainanda.com`)
- Isi kredensial database

## 3. Storage link (wajib agar gambar tampil)
Jalankan **sekali** di server (SSH / terminal hosting):
```bash
php artisan storage:link
```

## 4. Selesai
- Cek beranda, Produk, dan navbar (tombol Pesan + ikon sosial sejajar).
- Jika tampilan belum berubah, coba hard refresh (Ctrl+F5) atau buka mode incognito.
