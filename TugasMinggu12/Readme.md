# Laporan Tugas Minggu 12: CRUD (Create, Read, Update, Delete) CodeIgniter 4 (ShopEase)

- **Mata Kuliah:** Pemrograman Berbasis Kerangka Kerja (PBKK)
- **Nama:** Agil Lukman Hakim Muchdi
- **NRP/Angkatan:** 5025241037/2024
- **Program Studi:** Teknik Informatika, Institut Teknologi Sepuluh Nopember
- **Materi:** **CRUD** (*Create, Read, Update, Delete*) pada CodeIgniter 4, studi kasus **Product Management ShopEase**. Melanjutkan *Authentication System* dari Tugas Minggu 11, sehingga setiap operasi CRUD wajib login dan dibatasi sesuai role.
- **Framework:** CodeIgniter 4.7.6 · PHP 8.2+ · SQLite / MySQL (`db_shopease`) · Bootstrap 5

---

## 1. Deskripsi Singkat

Materi minggu ini adalah **CRUD**, yaitu empat operasi dasar untuk menyimpan data dari form ke database: membuat data baru (**Create**), menampilkannya (**Read**), mengubahnya (**Update**), dan menghapus data yang tidak terpakai (**Delete**). CRUD diterapkan pada data **produk** (lengkap dengan upload gambar) dan **kategori** ShopEase. Penjelasan setiap operasi ada di [bagian 2](#2-apa-itu-crud).

Aplikasi melanjutkan proyek **Tugas Minggu 11 (Authentication)**: registrasi, login, logout, session, role, dan `AuthFilter` tidak dibuat ulang, tetapi dipakai untuk melindungi modul CRUD. Modul yang dibuat sesuai struktur folder pada materi:

| Modul | Controller | Model | View |
|---|---|---|---|
| Authentication *(minggu 11)* | `AuthController` | `UserModel` | `auth/login`, `auth/register` |
| **Dashboard Customer** | `DashboardController` | `OrderModel` | `customer/dashboard` |
| **Product Management** | `ProductController` | `CategoryModel`, `ProductModel` | `product/index`, `create`, `edit`, `detail` |

Setiap halaman baru **wajib login**, dan apa yang boleh dilakukan ditentukan oleh **role**:

| Operasi | Tamu | Customer | Operator | Admin |
|---|:---:|:---:|:---:|:---:|
| Lihat beranda `/` | ✅ | ✅ | ✅ | ✅ |
| **Read** produk (`/products`, `/products/{id}`) | 🔒 login | ✅ katalog | ✅ tabel kelola | ✅ tabel kelola |
| **Create / Update** produk | 🔒 | ❌ | ✅ | ✅ |
| **Delete** produk | 🔒 | ❌ | ❌ | ✅ |
| CRUD kategori | 🔒 | ❌ | ✅ | ✅ |
| Dashboard customer, pesan produk, pesanan saya | 🔒 | ✅ | ❌ | ❌ |
| Kelola pesanan semua customer | 🔒 | ❌ | ✅ | ✅ |
| Kelola user (role) | 🔒 | ❌ | ❌ | ✅ |

🔒 = diarahkan ke `/login` (lalu dikembalikan ke halaman tujuan setelah login) · ❌ = diarahkan ke halaman utama role-nya dengan pesan "Akses ditolak".

### Tujuan Praktikum

- [x] Membuat **CRUD** (*Create, Read, Update, Delete*) produk dan kategori, termasuk **upload gambar**.
- [x] Menerapkan validasi form pada Create dan Update, serta konfirmasi sebelum Delete.
- [x] Membuat **Dashboard Customer** berisi profil dan ringkasan pesanan.
- [x] Melindungi setiap operasi CRUD dengan autentikasi minggu 11 (`AuthFilter` sesuai role).

---

## 2. Apa itu CRUD?

**CRUD** adalah singkatan dari **Create, Read, Update, Delete**, yaitu empat operasi dasar untuk mengelola data dari sebuah form ke database. Di ShopEase keempatnya diterapkan pada tabel `products` dan `categories`:

| Operasi | Arti | Produk (route → method) | Kategori (route → method) | SQL yang dijalankan Model |
|---|---|---|---|---|
| **C**reate | Membuat data baru dari form | `GET /products/create` → `create()`<br>`POST /products` → `store()` | `POST /categories` → `storeCategory()` | `INSERT` |
| **R**ead | Menampilkan data | `GET /products` → `index()`<br>`GET /products/{id}` → `detail()` | `GET /categories` → `categories()` | `SELECT` |
| **U**pdate | Mengubah data yang ada | `GET /products/{id}/edit` → `edit()`<br>`POST /products/{id}/update` → `update()` | `POST /categories/{id}/update` → `updateCategory()` | `UPDATE` |
| **D**elete | Menghapus data yang tidak terpakai | `POST /products/{id}/delete` → `delete()` | `POST /categories/{id}/delete` → `deleteCategory()` | `UPDATE deleted_at` (soft delete) / `DELETE` |

Semua operasi yang **mengubah data** memakai `POST` + token CSRF, sehingga tidak bisa dipicu lewat link atau `GET` biasa.

---

## 3. Alur Aplikasi (MVC + AuthFilter)

```
                         ┌──────────────── AuthFilter ────────────────┐
                         │ 1. sudah login?  tidak → /login            │
User ─▶ View ─▶ Routes ─▶│ 2. role cocok?   tidak → halaman role-nya  │─▶ Controller ─▶ Model ─▶ Database
  ▲                      └────────────────────────────────────────────┘      │
  └──────────────────────────────── View (HTML) ◀──────────────────────────────┘

Contoh: POST /products/5/delete
   Routes  : ['filter' => 'auth:admin']   → operator/customer ditolak sebelum controller dijalankan
   Control : ProductController::delete(5) → ProductModel::delete(5)
   Model   : UPDATE products SET deleted_at = NOW() WHERE id_product = 5
```

---

## 4. Tahapan Pengerjaan

| No | Tahap | Hasil | File |
|---|---|---|---|
| 1 | Lanjutkan proyek minggu 11 | Autentikasi, filter, dan desain ShopEase dipakai ulang | `TugasMinggu12/shopease` |
| 2 | Buat tabel baru | `categories`, `products`, `orders` + relasi *foreign key* | `app/Database/Migrations/2026-11-25-*` |
| 3 | Isi data awal | 5 kategori, 11 produk, 9 contoh pesanan | `app/Database/Seeds/CatalogSeeder.php` |
| 4 | Buat Model | Validasi kategori di model, soft delete produk, query ringkasan pesanan | `CategoryModel`, `ProductModel`, `OrderModel` |
| 5 | Buat Controller | CRUD produk & kategori, pemesanan, dashboard | `ProductController`, `OrderController`, `DashboardController` |
| 6 | Atur Routes + filter | Route dikelompokkan per hak akses | `app/Config/Routes.php` |
| 7 | Buat View | Katalog, tabel kelola, form create/edit (upload gambar), detail, dashboard | `app/Views/product/*`, `app/Views/customer/*` |
| 8 | Uji coba | Uji setiap role dengan browser dan `curl` | `screenshots/` |

### Pengembangan tambahan

- **Upload gambar produk** dengan pratinjau sebelum disimpan, nama file acak, validasi tipe/ukuran/dimensi, dan file lama terhapus saat gambar diganti.
- **Soft delete** produk: produk yang dihapus hilang dari katalog, tetapi riwayat pesanan yang menunjuk produk itu tetap utuh.
- **Pemesanan "Beli Sekarang"** dengan pengurangan stok yang aman dari *race condition* (`UPDATE … WHERE stock >= qty` di dalam transaksi).
- **Kelola pesanan** untuk operator/admin dengan alur status `menunggu → diproses → dikirim → selesai` (atau `dibatalkan`, stok dikembalikan).
- **Pencegahan IDOR**: customer hanya bisa membuka pesanan miliknya sendiri. Pesanan orang lain dijawab 404.
- Dashboard **admin** dan **operator** sekarang memakai data asli (jumlah produk, pesanan aktif, omzet 7 hari, stok menipis).

---

## 5. Struktur Database

Tabel `users` dan `auth_tokens` sama seperti minggu 11. Tabel baru:

```
users (minggu 11)          categories                     products                          orders
──────────────────         ─────────────────────          ──────────────────────────        ─────────────────────────
id_user (PK) ◀──┐          id_category (PK) ◀──┐          id_product (PK) ◀──────┐          id_order (PK)
name            │          name (unik)         └─ RESTRICT id_category (FK)       │          invoice (unik)
email           ├── SET NULL ──────────────────────────── created_by (FK)         │   ┌───── id_user (FK, CASCADE)
role            │          slug (unik)                    name, slug (unik)       └───┼───── id_product (FK, RESTRICT)
...             │          icon, color                    description                 │      product_name, price  ← salinan saat dipesan
                │          description                    price, stock                │      qty, total
                └─────────────────────────────────────────────────────────────────────┘      status ENUM(menunggu, diproses,
                                                          image (nama file)                         dikirim, selesai, dibatalkan)
                                                          is_active                          shipping_address, notes
                                                          deleted_at  ← soft delete          created_at, updated_at
```

| Keputusan desain | Alasan |
|---|---|
| `products.id_category` **RESTRICT** | Kategori yang masih dipakai produk tidak bisa dihapus, sehingga tidak ada produk tanpa kategori. |
| `products.deleted_at` (soft delete) | Pesanan lama tetap bisa menampilkan produknya walau produk sudah "dihapus" dari katalog. |
| `orders.product_name` & `orders.price` disalin | Perubahan harga produk di kemudian hari tidak mengubah total pesanan yang sudah dibuat. |
| `orders.status` ENUM | Di SQLite menjadi `CHECK(status IN (...))`, jadi status di luar daftar ditolak oleh database. |

---

## 6. Struktur Folder

```
TugasMinggu12/
├── Readme.md
├── .vs/tasks.vs.json                    # menu "Jalankan" di Visual Studio
├── screenshots/                         # 42 bukti pengujian
└── shopease/
    ├── jalankan.bat                     # composer install + migrate + seed + serve
    ├── database/db_shopease.sql         # skrip MySQL (XAMPP)
    ├── public/
    │   ├── assets/css/shopease.css      # design system ShopEase
    │   └── uploads/products/            # gambar produk hasil upload
    └── app/
        ├── Config/
        │   ├── Routes.php               # route dikelompokkan per hak akses
        │   ├── Filters.php              # alias auth / guest / remember
        │   └── ShopEase.php             # konfigurasi: produk per halaman, batas stok, maks. qty
        ├── Controllers/
        │   ├── AuthController.php       # (minggu 11) register, login, logout
        │   ├── DashboardController.php  # Dashboard Customer
        │   ├── ProductController.php    # CRUD produk + CRUD kategori + upload
        │   ├── OrderController.php      # pesan, pesanan saya, batal, kelola pesanan
        │   ├── Home.php                 # beranda publik
        │   ├── Admin/                   # Dashboard admin, Kelola User
        │   └── Operator/Dashboard.php
        ├── Models/
        │   ├── UserModel.php            # (minggu 11)
        │   ├── CategoryModel.php        # validasi di model
        │   ├── ProductModel.php         # soft delete, stok atomik, URL gambar
        │   └── OrderModel.php           # status, transisi, ringkasan
        ├── Filters/AuthFilter.php       # (minggu 11) cek login & role
        ├── Database/
        │   ├── Migrations/              # users, auth_tokens, categories, products, orders
        │   └── Seeds/                   # DatabaseSeeder → CatalogSeeder
        └── Views/
            ├── product/                 # index, catalog, create, edit, _form, detail, categories, orders
            ├── customer/                # dashboard, orders, order_detail
            ├── admin/ · operator/ · auth/ · profile/ · store/
            └── layouts/ · partials/
```

---

## 7. Potongan Kode Penting

### 7.1 Routes: hak akses per kelompok route

Satu tempat untuk melihat siapa boleh membuka apa. Perhatikan bahwa `products/(:num)/delete` diberi filter tersendiri `auth:admin`.

<a href="screenshots/k01-routes-hak-akses.png"><img src="screenshots/k01-routes-hak-akses.png" width="640"></a>

### 7.2 Migration tabel products

<a href="screenshots/k02-migration-products.png"><img src="screenshots/k02-migration-products.png" width="640"></a>

### 7.3 ProductModel (soft delete & stok atomik)

<a href="screenshots/k03-productmodel.png"><img src="screenshots/k03-productmodel.png" width="640"></a>

### 7.4 CREATE, UPDATE, DELETE di ProductController

| Create (`store`) | Update (`update`) | Delete + upload gambar |
|---|---|---|
| <a href="screenshots/k04-create-store.png"><img src="screenshots/k04-create-store.png" width="300"></a> | <a href="screenshots/k05-update.png"><img src="screenshots/k05-update.png" width="300"></a> | <a href="screenshots/k06-delete-dan-upload.png"><img src="screenshots/k06-delete-dan-upload.png" width="300"></a> |

### 7.5 Pemesanan dalam transaksi & pencegahan IDOR

| Transaksi stok + pesanan | Pesanan harus milik user login |
|---|---|
| <a href="screenshots/k07-order-transaksi-stok.png"><img src="screenshots/k07-order-transaksi-stok.png" width="420"></a> | <a href="screenshots/k08-cegah-idor.png"><img src="screenshots/k08-cegah-idor.png" width="420"></a> |

### 7.6 Daftar route lengkap (`php spark routes`)

<a href="screenshots/k09-spark-routes.png"><img src="screenshots/k09-spark-routes.png" width="640"></a>

---

## 8. Tampilan

| Beranda (data dari database) | Dashboard Customer |
|---|---|
| <a href="screenshots/01-beranda-tamu.png"><img src="screenshots/01-beranda-tamu.png" width="420"></a> | <a href="screenshots/07-dashboard-customer.png"><img src="screenshots/07-dashboard-customer.png" width="420"></a> |
| **Kelola Produk (operator)** | **Form Tambah Produk + upload** |
| <a href="screenshots/13-read-kelola-produk-operator.png"><img src="screenshots/13-read-kelola-produk-operator.png" width="420"></a> | <a href="screenshots/15-create-form-terisi-upload.png"><img src="screenshots/15-create-form-terisi-upload.png" width="420"></a> |
| **Kelola Kategori** | **Kelola Pesanan** |
| <a href="screenshots/21a-kategori-form-edit.png"><img src="screenshots/21a-kategori-form-edit.png" width="420"></a> | <a href="screenshots/23-kelola-pesanan-ubah-status.png"><img src="screenshots/23-kelola-pesanan-ubah-status.png" width="420"></a> |
| **Dashboard Operator** | **Dashboard Admin** |
| <a href="screenshots/12-login-operator-dashboard.png"><img src="screenshots/12-login-operator-dashboard.png" width="420"></a> | <a href="screenshots/24-dashboard-admin.png"><img src="screenshots/24-dashboard-admin.png" width="420"></a> |

Responsif di layar ponsel:

<a href="screenshots/27a-mobile-dashboard.png"><img src="screenshots/27a-mobile-dashboard.png" width="220"></a>
<a href="screenshots/27b-mobile-katalog.png"><img src="screenshots/27b-mobile-katalog.png" width="220"></a>

---

## 9. Cara Menjalankan di Visual Studio

**Prasyarat:** Visual Studio 2022/2026, PHP 8.2+ di `PATH` (extension `intl`, `mbstring`, `sqlite3`, `fileinfo`), Composer.

1. **File → Open → Folder...** → pilih `TugasMinggu12`.
2. Klik kanan folder di **Solution Explorer** → **1. Jalankan ShopEase (localhost:8080)**.
   - Pertama kali dijalankan: `vendor/`, `.env`, database SQLite, akun demo, kategori, dan produk disiapkan otomatis.
   - Atau dari **View → Terminal**: `cd shopease` lalu `jalankan.bat`.
3. Buka `http://localhost:8080`.

Task lain: **2. Reset Database** (kembali ke data awal) dan **3. Lihat Daftar Route**.

### Akun Demo

| Role | Email | Password | Halaman setelah login |
|---|---|---|---|
| Admin | `admin@shopease.test` | `admin123` | `/admin` |
| Operator | `operator@shopease.test` | `operator123` | `/operator` |
| Customer | `agil@shopease.test` | `customer123` | `/` (menu **Dashboard** di navbar) |

Customer lain: `siti@`, `budi@`, `andi@`, `rina@shopease.test` (password `customer123`).

### Memakai MySQL (XAMPP)

Pilih **salah satu**:

- **Skrip SQL:** jalankan `shopease/database/db_shopease.sql` di phpMyAdmin.
- **Migration:** buat database kosong `db_shopease`, lalu `php spark migrate --all` dan `php spark db:seed DatabaseSeeder`.

Setelah itu aktifkan `extension=mysqli` di `php.ini`, dan buka komentar blok `database.default.*` di `shopease/.env`.

---

## 10. Hasil Pengujian

Semua skenario diuji pada CodeIgniter 4.7.6 + PHP 8.5 + SQLite. Screenshot diambil otomatis dari aplikasi yang berjalan memakai Google Chrome *headless*. Bingkai di atas setiap gambar menampilkan URL yang dibuka dan status HTTP. Klik thumbnail untuk ukuran penuh.

### 10.1 Authentication pada halaman baru

| No | Skenario | Langkah / Input | Hasil | Screenshot |
|---|---|---|---|---|
| 1 | Beranda tamu | Buka `/` | Kategori & produk dibaca dari database; tombol **Masuk/Daftar** | <a href="screenshots/01-beranda-tamu.png"><img src="screenshots/01-beranda-tamu.png" width="200"></a> |
| 2 | Tamu membuka detail produk | Klik produk → `/products/1` | Diarahkan ke `/login`, "Silakan login terlebih dahulu." | <a href="screenshots/02-tamu-buka-produk-diarahkan-login.png"><img src="screenshots/02-tamu-buka-produk-diarahkan-login.png" width="200"></a> |
| 3 | Kembali ke halaman tujuan | Login sebagai customer | Langsung kembali ke `/products/1` | <a href="screenshots/03-login-kembali-ke-detail-produk.png"><img src="screenshots/03-login-kembali-ke-detail-produk.png" width="200"></a> |
| 4 | Customer membuka form CRUD | Buka `/products/create` | Ditolak, kembali ke `/`: "Akses ditolak: halaman tersebut khusus admin/operator." | <a href="screenshots/10-customer-akses-crud-ditolak.png"><img src="screenshots/10-customer-akses-crud-ditolak.png" width="200"></a> |
| 5 | Customer membuka pesanan orang lain | Buka `/orders/5` (milik Siti) | **404 Pesanan tidak ditemukan** (IDOR dicegah) | <a href="screenshots/11-customer-buka-pesanan-orang-lain-404.png"><img src="screenshots/11-customer-buka-pesanan-orang-lain-404.png" width="200"></a> |
| 6 | Operator mencoba hapus produk | Tombol hapus terkunci; `POST /products/1/delete` lewat curl | Ditolak `auth:admin`, produk tetap ada | <a href="screenshots/19-read-hasil-cari-tanpa-tombol-hapus.png"><img src="screenshots/19-read-hasil-cari-tanpa-tombol-hapus.png" width="200"></a> |
| 7 | Matriks hak akses | `curl` ke setiap URL memakai cookie session tiap role | Sesuai tabel hak akses di bagian 1 | <a href="screenshots/29-matriks-hak-akses-curl.png"><img src="screenshots/29-matriks-hak-akses-curl.png" width="200"></a> |

### 10.2 CRUD Produk (operator & admin)

| No | Operasi | Langkah / Input | Hasil | Screenshot |
|---|---|---|---|---|
| 8 | **Read** | Operator membuka `/products` | Tabel produk, filter kategori, pencarian, pagination; tombol hapus terkunci untuk operator | <a href="screenshots/13-read-kelola-produk-operator.png"><img src="screenshots/13-read-kelola-produk-operator.png" width="200"></a> |
| 9 | **Create** (gagal) | Nama `Ab`, kategori kosong, harga `0`, stok `-3` | 4 pesan error, input lama tetap terisi | <a href="screenshots/14-create-validasi-gagal.png"><img src="screenshots/14-create-validasi-gagal.png" width="200"></a> |
| 10 | **Create** (form) | Isi data + pilih gambar | Pratinjau gambar tampil sebelum diunggah | <a href="screenshots/15-create-form-terisi-upload.png"><img src="screenshots/15-create-form-terisi-upload.png" width="200"></a> |
| 11 | **Create** (berhasil) | Simpan | "Produk … berhasil ditambahkan.", gambar tampil, tercatat **Ditambahkan oleh Operator Gudang** | <a href="screenshots/16-create-berhasil-detail.png"><img src="screenshots/16-create-berhasil-detail.png" width="200"></a> |
| 12 | **Update** (form) | `/products/12/edit`, ubah nama, harga, stok | Form terisi data lama + gambar saat ini | <a href="screenshots/17-update-form-edit.png"><img src="screenshots/17-update-form-edit.png" width="200"></a> |
| 13 | **Update** (berhasil) | Simpan | "Produk … berhasil diperbarui.", slug ikut berubah | <a href="screenshots/18-update-berhasil.png"><img src="screenshots/18-update-berhasil.png" width="200"></a> |
| 14 | **Read** (cari) | `/products?q=mouse` | 1 hasil; status **Tampil**; tombol hapus terkunci (operator) | <a href="screenshots/19-read-hasil-cari-tanpa-tombol-hapus.png"><img src="screenshots/19-read-hasil-cari-tanpa-tombol-hapus.png" width="200"></a> |
| 15 | **Delete** | Admin klik hapus → konfirmasi | "Produk … dihapus dari katalog." | <a href="screenshots/25-delete-produk-oleh-admin.png"><img src="screenshots/25-delete-produk-oleh-admin.png" width="200"></a> |
| 16 | Setelah dihapus | Customer membuka `/products/12` | 404, produk tidak lagi tampil | <a href="screenshots/26-produk-terhapus-404.png"><img src="screenshots/26-produk-terhapus-404.png" width="200"></a> |
| 17 | Bukti soft delete | `SELECT … FROM products` | Baris masih ada, `deleted_at` terisi, nama file gambar tersimpan | <a href="screenshots/28-db-soft-delete-dan-gambar.png"><img src="screenshots/28-db-soft-delete-dan-gambar.png" width="200"></a> |

Diuji juga lewat `curl`: file teks yang diberi nama `.png` ditolak ("Gambar bukan berkas gambar diunggah yang valid."), produk bisa disimpan tanpa gambar, dan mengganti gambar menghapus file lama dari `public/uploads/products/`.

### 10.3 CRUD Kategori

| No | Operasi | Langkah / Input | Hasil | Screenshot |
|---|---|---|---|---|
| 18 | **Create** | Nama `Olahraga`, ikon, warna | "Kategori "Olahraga" ditambahkan." | <a href="screenshots/20-kategori-create.png"><img src="screenshots/20-kategori-create.png" width="200"></a> |
| 19 | **Update** (form) | Klik ✏️ → form berubah menjadi **Edit Kategori** | Baris yang diedit disorot | <a href="screenshots/21a-kategori-form-edit.png"><img src="screenshots/21a-kategori-form-edit.png" width="200"></a> |
| 20 | **Update** (berhasil) | Ubah nama menjadi `Olahraga & Outdoor` | Nama & slug berubah | <a href="screenshots/21b-kategori-update.png"><img src="screenshots/21b-kategori-update.png" width="200"></a> |
| 21 | **Delete** (ditolak) | Hapus `Elektronik` | "…tidak bisa dihapus karena masih dipakai 4 produk." | <a href="screenshots/22a-kategori-hapus-ditolak.png"><img src="screenshots/22a-kategori-hapus-ditolak.png" width="200"></a> |
| 22 | **Delete** (berhasil) | Hapus kategori kosong | "Kategori … dihapus." | <a href="screenshots/22b-kategori-hapus-berhasil.png"><img src="screenshots/22b-kategori-hapus-berhasil.png" width="200"></a> |

Validasi kategori berada di `CategoryModel` (`$validationRules`). Lewat `curl` sudah diuji: nama duplikat ditolak ("Nama kategori sudah ada."), warna `merah` ditolak ("Warna harus berformat #RRGGBB."), dan menyimpan kategori dengan namanya sendiri **tidak** dianggap duplikat berkat placeholder `{id_category}`.

### 10.4 Dashboard Customer & Pesanan

| No | Skenario | Langkah / Input | Hasil | Screenshot |
|---|---|---|---|---|
| 23 | Katalog customer | `/products?kategori=1` | Kartu produk sesuai kategori, filter kategori berbentuk *chip* | <a href="screenshots/04-katalog-customer-filter.png"><img src="screenshots/04-katalog-customer-filter.png" width="200"></a> |
| 24 | Form pesan | Jumlah `2`, catatan | Total dihitung otomatis; alamat terisi dari profil | <a href="screenshots/05a-form-beli-sekarang.png"><img src="screenshots/05a-form-beli-sekarang.png" width="200"></a> |
| 25 | Pesanan berhasil | Klik **Beli Sekarang** | Detail pesanan + nomor invoice, status **Menunggu**, stok berkurang 2 | <a href="screenshots/05b-pesanan-berhasil-dibuat.png"><img src="screenshots/05b-pesanan-berhasil-dibuat.png" width="200"></a> |
| 26 | Stok tidak cukup | Jumlah `5` untuk stok `3` | "Stok Case HP MagSafe tidak mencukupi (tersisa 3).", tidak ada pesanan dibuat | <a href="screenshots/06-stok-tidak-cukup.png"><img src="screenshots/06-stok-tidak-cukup.png" width="200"></a> |
| 27 | Dashboard customer | `/dashboard` | Profil, total pesanan, pesanan berjalan, selesai, total belanja, 5 pesanan terbaru | <a href="screenshots/07-dashboard-customer.png"><img src="screenshots/07-dashboard-customer.png" width="200"></a> |
| 28 | Pesanan saya | `/orders` | Hanya pesanan milik customer yang login, bisa difilter status | <a href="screenshots/08-pesanan-saya.png"><img src="screenshots/08-pesanan-saya.png" width="200"></a> |
| 29 | Batalkan pesanan | Pesanan berstatus menunggu → **Batalkan** | "Pesanan … dibatalkan. Stok produk dikembalikan." | <a href="screenshots/09-batalkan-pesanan.png"><img src="screenshots/09-batalkan-pesanan.png" width="200"></a> |
| 30 | Kelola pesanan | Operator mengubah status `menunggu → diproses` | "Pesanan INV-…: menunggu → diproses." | <a href="screenshots/23-kelola-pesanan-ubah-status.png"><img src="screenshots/23-kelola-pesanan-ubah-status.png" width="200"></a> |
| 31 | Dashboard operator | Login operator | Antrian pesanan menunggu & stok menipis dari database | <a href="screenshots/12-login-operator-dashboard.png"><img src="screenshots/12-login-operator-dashboard.png" width="200"></a> |
| 32 | Dashboard admin | Login admin | Statistik user + produk, kategori, pesanan aktif, omzet 7 hari | <a href="screenshots/24-dashboard-admin.png"><img src="screenshots/24-dashboard-admin.png" width="200"></a> |

Diuji juga lewat `curl`: lompatan status yang tidak sah (`menunggu → selesai`) ditolak, `id_user` palsu yang disisipkan ke form pesanan diabaikan (pemilik pesanan selalu diambil dari session), dan `POST /orders/6/cancel` untuk pesanan milik customer lain dijawab 404 walau token CSRF-nya valid.

---

## 11. Checkpoint Keamanan

| Ancaman | Penanganan di ShopEase |
|---|---|
| Halaman dibuka tanpa login | `AuthFilter` pada setiap route baru, tamu diarahkan ke `/login` |
| Customer memakai fitur staf | Filter role `auth:admin,operator` dan `auth:admin` di `Routes.php`, bukan hanya menyembunyikan tombol |
| IDOR (menebak ID pesanan orang lain) | Query selalu `WHERE id_user = session('id_user')`; pesanan orang lain dijawab 404 |
| Mass assignment (`id_user` palsu di form) | Pemilik pesanan diambil dari session; `$allowedFields` pada setiap Model |
| CSRF pada tambah/ubah/hapus | Semua operasi tulis memakai `POST` + `csrf_field()` (filter global `csrf`) |
| Upload file berbahaya | `is_image`, `mime_in`, `max_size`, `max_dims`; nama file diganti acak dengan `getRandomName()` |
| XSS dari nama/deskripsi produk | Semua output memakai `esc()` |
| Stok minus karena pembelian bersamaan | `UPDATE … SET stock = stock - qty WHERE stock >= qty` di dalam transaksi |
| Data riwayat rusak saat produk dihapus | Soft delete + FK `RESTRICT` + salinan nama & harga di `orders` |

---

## 12. Kesimpulan

Operasi **CRUD** pada CodeIgniter 4 dibangun dari pola MVC: form di View mengirim data, Controller memvalidasinya, lalu Model menjalankan `insert()`, `find()`/`paginate()`, `update()`, atau `delete()` ke database. Autentikasi dari minggu 11 menjadi lapisan pertama untuk modul CRUD ini. Dengan **AuthFilter** di tingkat route, setiap operasi **CRUD** produk dan kategori, Dashboard Customer, dan pesanan hanya bisa dijalankan oleh role yang berhak, bahkan ketika request dikirim langsung tanpa melalui tombol di tampilan. Fitur tambahan seperti validasi di model, soft delete, dan transaksi database menjaga data tetap konsisten.

---

## 13. Referensi

- CodeIgniter 4 User Guide: [Controller Filters](https://codeigniter.com/user_guide/incoming/filters.html), [Using CodeIgniter's Model](https://codeigniter.com/user_guide/models/model.html), [Working with Uploaded Files](https://codeigniter.com/user_guide/libraries/uploaded_files.html), [Transactions](https://codeigniter.com/user_guide/database/transactions.html)
- Materi PBKK: *Hands-On Lab Authentication System*, *Tahapan Implementasi*, dan *Struktur Folder dan Deskripsi File* ShopEase
- OWASP: [Insecure Direct Object Reference Prevention](https://cheatsheetseries.owasp.org/cheatsheets/Insecure_Direct_Object_Reference_Prevention_Cheat_Sheet.html)
