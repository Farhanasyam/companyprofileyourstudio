# Deploy ke Web Hosting (cara mudah)

Website ini sudah disiapkan untuk hosting:

- **CSS & JS**: path relatif (`/css/...`, `/vendor/...`, `/js/...`) — tidak bergantung `APP_URL`
- **Gambar**: path relatif (`/storage/...`) di frontend dan model
- **Meta OG/SEO**: URL gambar pakai domain yang diakses (otomatis benar di hosting)
- **Critical CSS**: diload lewat `<link>`, tanpa `file_get_contents` (aman di berbagai environment)

Cukup ikuti langkah di bawah.

---

### Kenapa gambar tidak keluar di hosting?

Biasanya karena **dua hal**:

1. **Link `storage` belum ada**  
   Di folder **public** (yang berisi `index.php`) harus ada link/folder bernama **`storage`** yang mengarah ke `storage/app/public`. Tanpa ini, URL `/storage/images/...` akan 404.  
   → Lihat **Langkah 3** di bawah.

2. **File gambar tidak ikut ke server**  
   Isi folder `storage/app/public/` (logo, gambar produk, galeri, dll) **tidak di-commit ke Git**. Jadi kalau deploy hanya lewat git clone/pull, gambar tidak ikut.  
   → **Upload manual** isi `storage/app/public` ke server (FTP / File Manager / rsync), atau copy folder tersebut saat deploy. Pastikan di server ada misalnya: `storage/app/public/images/logo1.png`, `storage/app/public/images/products/`, dll.

---

## 1. Document root → folder `public`
Arahkan document root hosting ke folder **`public`** project.
- Contoh: project di `public_html/companyprofileyourstudio` → document root = `public_html/companyprofileyourstudio/public`

## 2. File .env
- Copy `.env.example` ke `.env`
- Isi `APP_URL` dengan URL asli (mis. `https://domainanda.com`)
- Isi kredensial database

## 3. Storage link (wajib agar gambar tampil)

**Di mana link harus muncul:**  
Link bernama **`storage`** (biasanya dengan ikon rantai 🔗) harus ada di **folder yang sama dengan `index.php`** (folder document root / isi folder `public`). Di folder itu juga ada folder `css`, `js`, `vendor`. Jadi buka folder yang berisi `index.php` → di situ harus ada folder/link **`storage`**.

- Jika Anda melihat folder `events`, `Images`, `products` tanpa ada folder `storage` di sampingnya, kemungkinan Anda sedang berada di **isi folder storage** (bukan di folder public). Kembali ke atas sampai ketemu folder yang berisi **index.php**, **css**, **js** → di situlah link **storage** harus ada.

**Cara buat link:**
1. **Via SSH/Terminal** (dari **root project** Laravel, bukan dari dalam `public`):
   ```bash
   php artisan storage:link
   ```
2. **Kalau di file manager tidak ada ikon rantai dan gambar tetap tidak tampil:**  
   Beberapa hosting memblokir symlink. Coba:
   - Di cPanel File Manager, masuk ke folder **public** (yang berisi index.php).
   - Cari opsi **"Create Link"** / **"Symbolic Link"** / **"Link"**.
   - Buat link: nama = **`storage`**, target = path ke **`storage/app/public`** (relatif dari folder public: `../storage/app/public`).
   - Atau tanya penyedia hosting: "Apakah symlink diizinkan? Saya butuh `public/storage` → `storage/app/public` untuk Laravel."

## 4. Upload isi folder storage (wajib agar gambar ada)

Isi `storage/app/public/` (gambar logo, produk, galeri, event, dll) **tidak ikut ke Git**. Jadi setelah deploy via Git:

- Via **FTP / cPanel File Manager**: upload seluruh isi folder **`storage/app/public`** dari komputer Anda ke path **`storage/app/public`** di server (buat folder yang sama bila belum ada).
- Pastikan di server ada file seperti: `storage/app/public/images/logo1.png`, dan folder `storage/app/public/images/products/`, `storage/app/public/images/galleries/`, dll sesuai yang Anda pakai di situs.

Tanpa langkah ini, link `storage` (langkah 3) sudah benar tapi isi folder kosong → gambar tetap tidak tampil.

## 5. Selesai
- Cek beranda, Produk, galeri, dan navbar (logo, tombol Pesan, ikon sosial). Pastikan gambar tampil.
- Jika gambar masih tidak keluar: cek lagi **langkah 3** (ada link `storage` di folder public?) dan **langkah 4** (isi `storage/app/public` sudah di-upload?).
- Jika tampilan belum berubah, coba hard refresh (Ctrl+F5) atau buka mode incognito.
