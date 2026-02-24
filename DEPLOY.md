# Deploy ke Web Hosting (cara mudah)

Website ini sudah disiapkan untuk hosting:

- **CSS & JS**: path relatif (`/css/...`, `/vendor/...`, `/js/...`) — tidak bergantung `APP_URL`
- **Gambar**: path relatif (`/storage/...`) di frontend dan model
- **Meta OG/SEO**: URL gambar pakai domain yang diakses (otomatis benar di hosting)
- **Critical CSS**: diload lewat `<link>`, tanpa `file_get_contents` (aman di berbagai environment)

Cukup ikuti langkah di bawah.

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
