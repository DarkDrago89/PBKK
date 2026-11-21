# Laporan Tugas Minggu 9 – Latihan 1: Pengenalan Framework CodeIgniter

- **Mata Kuliah:** Pemrograman Berbasis Kerangka Kerja (PBKK)
- **Nama:** Agil Lukman Hakim Muchdi
- **NRP/Angkatan:** 5025241037/2024
- **Program Studi:** Teknik Informatika, Institut Teknologi Sepuluh Nopember
- **Materi:** [PBKK-2 Pengenalan Framework Code Igniter](https://fajarbaskoro.blogspot.com/2019/02/pbkk-2-pengenalan-framework-code-igniter.html)
- **Studi Kasus:** Aplikasi Biodata Mahasiswa (CodeIgniter 4.7)

---

## 1. Deskripsi Singkat

Tugas ini membuat aplikasi web **Biodata Mahasiswa** dengan framework **CodeIgniter 4** yang menerapkan konsep **MVC** (Model, View, Controller). Aplikasi mencakup seluruh poin **Latihan 1** pada modul: instalasi dan konfigurasi CI, Hello World, router dan controller, tampilan Bootstrap, template **SB Admin 2**, Model dan CRUD, upload file, serta pengaturan akses user.

Aplikasi dijalankan dari **Visual Studio** (mode *Open Folder*) dengan web server bawaan CodeIgniter (`php spark serve`). Database default memakai **SQLite** sehingga bisa langsung berjalan tanpa XAMPP, dan dapat diganti ke **MySQL (XAMPP)** cukup lewat file `.env`.

Alur arsitektur: `Browser → Router (Routes.php) → Filter (auth/role) → Controller → Model → Database`, lalu `Controller → View → Browser`.

---

## 2. Ringkasan Materi

**CodeIgniter** adalah framework PHP *open source* dan gratis yang memakai pola **MVC**, dibuat agar developer tidak perlu membangun aplikasi web dari nol.

| Komponen | Fungsi | Contoh di project ini |
|---|---|---|
| **Model** | Pengolahan/manipulasi database (ambil, simpan, ubah, hapus data) | `BiodataModel`, `UserModel` |
| **View** | Tampilan antarmuka untuk user | `app/Views/biodata/*.php`, layout Bootstrap & SB Admin 2 |
| **Controller** | Penghubung View dan Model; user tidak berhubungan langsung dengan Model | `Biodata`, `Auth`, `Dashboard`, `Hello` |

Kelebihan CodeIgniter yang dipakai pada tugas ini:

- **Sintaks terstruktur**: setiap bagian punya foldernya sendiri (`Controllers/`, `Models/`, `Views/`, `Config/`).
- **Mudah digunakan**: CRUD cukup memanggil `insert()`, `save()`, `find()`, `delete()` pada Model.
- **Helper & library bawaan**: pagination (`paginate()`), session, validasi, upload file, URL helper (`site_url()`, `base_url()`).
- **Keamanan**: user tidak mengakses database secara langsung, ditambah proteksi **CSRF**, escaping output (`esc()`), *password hashing*, dan *query binding* otomatis dari Query Builder.

### Perbedaan dengan langkah di modul (CI 3 + XAMPP)

Modul memakai CodeIgniter 3 yang diekstrak ke `htdocs` XAMPP. Tugas ini memakai **CodeIgniter 4** (versi aktif saat ini), sehingga:

| Modul (CI 3) | Tugas ini (CI 4) |
|---|---|
| Ekstrak ZIP ke `C:/xampp/htdocs/biodata` | `composer create-project codeigniter4/appstarter` |
| Akses `http://localhost/biodata` lewat Apache | `php spark serve` → `http://localhost:8080` |
| Konfigurasi `application/config/*.php` (array) | Konfigurasi `app/Config/*.php` (class) + `.env` |
| Routing otomatis `controller/method` | Routing eksplisit di `app/Config/Routes.php` |
| Database MySQL via phpMyAdmin | Migration + Seeder (`php spark migrate`, `db:seed`), SQLite/MySQL |

Welcome Page bawaan CodeIgniter tetap tersedia di `/welcome` sebagai tanda instalasi berhasil.

---

## 3. Fitur (Pemetaan ke Latihan 1)

| No | Latihan | Implementasi | URL |
|----|---------|--------------|-----|
| 1 | Instalasi & Konfigurasi CI | CI 4.7 via Composer; `App.php` (tanpa `index.php`, zona waktu Asia/Jakarta, locale `id`), `Database.php`, `.env` | `/welcome` |
| 2 | Hello World | Controller `Hello` + view `hello.php` | `/hello` |
| 3 | Router & Controller | Route statis, placeholder `(:segment)` / `(:num)`, route group `admin`, filter per route | `/hello/Agil` |
| 4 | Bootstrap untuk View | Layout `layouts/bootstrap.php` (Bootstrap 5) untuk halaman publik & login | `/`, `/login` |
| 5 | Template SB Admin | Layout `layouts/sbadmin.php` (SB Admin 2) + `partials/sidebar` & `topbar`, dashboard statistik | `/admin` |
| 6 | Model & CRUD | `BiodataModel` (validasi, timestamps, pencarian, pagination) + controller `Biodata` | `/admin/biodata` |
| 7 | File Upload | Upload foto (jpg/png/webp, maks 2 MB), nama file acak, foto lama dihapus saat diganti/dihapus | form tambah/edit |
| 8 | Mengatur Akses User | Login dengan `password_hash`, filter `auth` (wajib login) dan `role:admin` (CRUD hanya admin) | `/login`, `/logout` |

### Akun Demo

| Username | Password | Role | Hak Akses |
|---|---|---|---|
| `admin` | `admin123` | admin | Lihat, tambah, edit, hapus biodata |
| `user` | `user123` | user | Hanya lihat daftar & detail biodata |

---

## 4. Arsitektur dan Struktur Project

```
┌──────────────┐   request   ┌──────────────────────┐
│   Browser    │ ──────────▶ │ Router (Routes.php)  │
└──────▲───────┘             └──────────┬───────────┘
       │                                ▼
       │                     ┌──────────────────────┐
       │                     │ Filter: csrf, auth,  │
       │                     │ role:admin           │
       │                     └──────────┬───────────┘
       │                                ▼
       │   HTML     ┌────────┐   ┌──────────────┐   ┌──────────────┐
       └─────────── │  View  │ ◀─│  Controller  │ ─▶│    Model     │
                    └────────┘   └──────────────┘   └──────┬───────┘
                                                           ▼
                                                  ┌──────────────────┐
                                                  │ SQLite / MySQL   │
                                                  └──────────────────┘
```

```
TugasMinggu9/Latihan1
├── .vs/tasks.vs.json          # Task Visual Studio (Jalankan, Reset DB, Routes)
├── app
│   ├── Config
│   │   ├── App.php            # baseURL, indexPage, timezone, locale
│   │   ├── Database.php       # koneksi default SQLite3
│   │   ├── Filters.php        # alias filter auth & role, CSRF global
│   │   ├── Pager.php          # template pagination 'bootstrap'
│   │   └── Routes.php         # semua route aplikasi
│   ├── Controllers
│   │   ├── Home.php           # beranda & welcome page
│   │   ├── Hello.php          # Hello World
│   │   ├── Auth.php           # login / logout
│   │   ├── Dashboard.php      # statistik
│   │   └── Biodata.php        # CRUD + upload foto
│   ├── Database
│   │   ├── Migrations         # tabel users & biodata
│   │   └── Seeds              # akun demo & 4 data contoh
│   ├── Filters
│   │   ├── AuthFilter.php     # wajib login
│   │   └── RoleFilter.php     # batasi berdasarkan role
│   ├── Models
│   │   ├── BiodataModel.php
│   │   └── UserModel.php
│   └── Views
│       ├── layouts            # bootstrap.php (publik), sbadmin.php (admin)
│       ├── partials           # sidebar.php, topbar.php
│       ├── auth/login.php
│       ├── biodata            # index, show, form, _foto
│       ├── dashboard/index.php
│       ├── pager/bootstrap.php
│       ├── home.php
│       └── hello.php
├── public
│   ├── index.php              # front controller
│   └── uploads/foto           # hasil upload foto
├── writable/biodata.db        # database SQLite (dibuat otomatis)
├── jalankan.bat               # setup otomatis + php spark serve
├── .env                       # konfigurasi environment
└── composer.json
```

### Tabel Database

**users**: `id`, `username` (unique), `nama`, `password` (hash), `role` (`admin`/`user`), `created_at`, `updated_at`

**biodata**: `id`, `nim` (unique), `nama`, `jenis_kelamin` (`L`/`P`), `tempat_lahir`, `tanggal_lahir`, `prodi`, `email`, `alamat`, `foto`, `created_at`, `updated_at`

---

## 5. Alur Data dan Potongan Kode Penting

### 5.1 Router → Controller (Hello World)

```php
// app/Config/Routes.php
$routes->get('hello', 'Hello::index');
$routes->get('hello/(:segment)', 'Hello::nama/$1');   // /hello/Agil -> $nama = "Agil"
```

```php
// app/Controllers/Hello.php
public function nama(string $nama): string
{
    return view('hello', ['title' => 'Hello ' . $nama, 'nama' => urldecode($nama)]);
}
```

### 5.2 Hak Akses dengan Route Group + Filter

```php
$routes->group('admin', ['filter' => 'auth'], static function ($routes) {
    $routes->get('biodata', 'Biodata::index');                      // semua user login
    $routes->group('biodata', ['filter' => 'role:admin'], static function ($routes) {
        $routes->get('create', 'Biodata::create');                  // hanya admin
        $routes->post('(:num)/delete', 'Biodata::delete/$1');
        // ...
    });
});
```

`role:admin` mengirim `['admin']` sebagai `$arguments` ke `RoleFilter::before()`. Karena dicek di router, user biasa tidak bisa menembus fitur admin meskipun mengetik URL atau mengirim POST secara manual.

### 5.3 Model: Validasi dan CRUD

```php
// app/Models/BiodataModel.php
protected $validationRules = [
    'nim'   => 'required|numeric|min_length[5]|max_length[20]|is_unique[biodata.nim,id,{id}]',
    'email' => 'required|valid_email|max_length[100]',
    // ...
];
```

Controller cukup memanggil `$this->model->insert($data)` atau `$this->model->save($data)`. Jika validasi gagal, `$this->model->errors()` dikirim kembali ke form beserta input lama (`withInput()` + `old()`).

### 5.4 Upload File

```php
$file = $this->request->getFile('foto');
if ($file !== null && $file->isValid() && ! $file->hasMoved()) {
    $namaBaru = $file->getRandomName();             // cegah nama bentrok & path traversal
    $file->move(FCPATH . 'uploads/foto', $namaBaru);
}
```

Rule `is_image`, `mime_in`, dan `max_size` hanya dijalankan bila user memilih file, sehingga foto bersifat opsional. Form wajib memakai `enctype="multipart/form-data"`.

---

## 6. Cara Menjalankan di Visual Studio

### Prasyarat

- **Visual Studio 2022/2026** (Community cukup).
- **PHP 8.2+** terdaftar di `PATH` dengan extension `intl`, `mbstring`, dan `sqlite3` aktif. Bisa memakai PHP dari XAMPP (`C:\xampp\php`) atau PHP mandiri.
- **Composer** (hanya jika folder `vendor/` belum ada).
- *(Opsional)* XAMPP + MySQL bila ingin memakai MySQL alih-alih SQLite.

Cek di terminal:

```bash
php -v
php -m | findstr /i "intl mbstring sqlite3"
```

### Langkah

1. Buka **Visual Studio** → **File → Open → Folder...** → pilih folder `TugasMinggu9/Latihan1`.
2. Di **Solution Explorer**, klik kanan **folder paling atas** (`Latihan1`) → pilih **1. Jalankan Aplikasi (localhost:8080)**.
   - Alternatif: buka folder `TugasMinggu9` (induk), lalu klik kanan → **Latihan 1 - Biodata (localhost:8080)**.
   - Saat pertama kali, task otomatis menjalankan `composer install` (jika perlu), membuat `.env`, menjalankan migration, dan mengisi data contoh.
   - Browser terbuka ke `http://localhost:8080`. Log server tampil di jendela **Output**.
3. Login dengan akun demo (`admin / admin123` atau `user / user123`).
4. Untuk berhenti, tekan **Ctrl+C** pada jendela server, atau akhiri proses `php.exe` lewat Task Manager.

Task lain di menu klik kanan yang sama:

| Task | Fungsi |
|---|---|
| **2. Reset Database** | `php spark migrate:refresh --all -f` + `db:seed`, kembali ke data awal |
| **3. Lihat Daftar Route** | `php spark routes`, menampilkan route beserta filternya |

> **Alternatif:** buka **View → Terminal** di Visual Studio lalu jalankan `jalankan.bat`, atau `php spark serve`. File `jalankan.bat` juga bisa di-*double-click* dari File Explorer.

### Memakai MySQL (XAMPP)

1. Jalankan **Apache** dan **MySQL** dari XAMPP Control Panel, buat database `biodata` di phpMyAdmin.
2. Pastikan `extension=mysqli` aktif di `php.ini`.
3. Buka komentar bagian `database.default.*` di file `.env` (DBDriver `MySQLi`, user `root`, password kosong).
4. Jalankan task **2. Reset Database**, lalu **1. Jalankan Aplikasi**.

### Troubleshooting

| Masalah | Solusi |
|---|---|
| `'php' is not recognized` | Tambahkan folder PHP (misal `C:\xampp\php`) ke *Environment Variables → Path*, lalu restart Visual Studio |
| `Class "Locale" not found` / error `intl` | Aktifkan `extension=intl` di `php.ini` |
| `Unable to load SQLite3` | Aktifkan `extension=sqlite3` di `php.ini` |
| Port 8080 sudah dipakai | Jalankan `php spark serve --port 8081` dan sesuaikan `app.baseURL` di `.env` |
| Menu task tidak muncul | Pastikan membuka lewat **Open Folder** (bukan file tunggal) dan klik kanan folder root |

---

## 7. Hasil Pengujian

Skenario 1–15 sudah diuji pada CodeIgniter 4.7.6 + PHP 8.5 + SQLite. Screenshot diambil otomatis dari aplikasi yang berjalan memakai Google Chrome *headless*; bingkai di atas setiap gambar menampilkan URL yang dibuka dan status HTTP. Klik thumbnail untuk ukuran penuh.

| No | Skenario | Langkah / Input | Hasil yang Diharapkan | Screenshot |
|----|---|---|---|---|
| 1 | Instalasi berhasil | Buka `/welcome` | Welcome Page CodeIgniter tampil | <a href="screenshots/01-welcome-page.png"><img src="screenshots/01-welcome-page.png" width="220"></a> |
| 2 | Hello World | Buka `/hello`, lalu `/hello/Agil` | Tampil "Hello World!" dan "Hello Agil!" | <a href="screenshots/02a-hello-world.png"><img src="screenshots/02a-hello-world.png" width="220"></a><br><a href="screenshots/02b-hello-agil.png"><img src="screenshots/02b-hello-agil.png" width="220"></a> |
| 3 | Halaman Bootstrap | Buka `/` | Beranda Bootstrap 5 dengan penjelasan MVC | <a href="screenshots/03-beranda-bootstrap.png"><img src="screenshots/03-beranda-bootstrap.png" width="220"></a> |
| 4 | Proteksi login | Buka `/admin` tanpa login | Diarahkan ke `/login` dengan pesan "Silakan login terlebih dahulu." | <a href="screenshots/04-proteksi-login.png"><img src="screenshots/04-proteksi-login.png" width="220"></a> |
| 5 | Login gagal | `admin` / `salah` | Pesan "Username atau password salah." | <a href="screenshots/05-login-gagal.png"><img src="screenshots/05-login-gagal.png" width="220"></a> |
| 6 | Dashboard SB Admin | Login `admin` / `admin123` | Kartu statistik: Total 4, Laki-laki 2, Perempuan 2, Prodi 2 | <a href="screenshots/06-dashboard-sb-admin.png"><img src="screenshots/06-dashboard-sb-admin.png" width="220"></a> |
| 7 | READ + pencarian | Menu Biodata, cari `Sistem` | Hanya Siti Aminah & Rina Sari yang tampil | <a href="screenshots/07-read-pencarian.png"><img src="screenshots/07-read-pencarian.png" width="220"></a> |
| 8 | CREATE + upload | Tambah NIM `5025241037`, isi semua field, pilih foto | Pesan "Biodata berhasil ditambahkan.", foto tampil di tabel | <a href="screenshots/08a-form-create.png"><img src="screenshots/08a-form-create.png" width="220"></a><br><a href="screenshots/08b-create-berhasil.png"><img src="screenshots/08b-create-berhasil.png" width="220"></a> |
| 9 | Validasi | Tambah dengan NIM `5025241001`, nama `X`, email `bad` | Pesan: NIM sudah terdaftar, nama min. 3 karakter, email tidak valid; input lama tetap terisi | <a href="screenshots/09-validasi.png"><img src="screenshots/09-validasi.png" width="220"></a> |
| 10 | Validasi file | Upload file bukan gambar | Pesan "Foto bukan berkas gambar diunggah yang valid." | <a href="screenshots/10-validasi-file.png"><img src="screenshots/10-validasi-file.png" width="220"></a> |
| 11 | UPDATE | Edit nama & ganti foto | Data berubah, foto lama terhapus dari `public/uploads/foto` | <a href="screenshots/11a-form-edit.png"><img src="screenshots/11a-form-edit.png" width="220"></a><br><a href="screenshots/11b-update-berhasil.png"><img src="screenshots/11b-update-berhasil.png" width="220"></a> |
| 12 | DELETE | Klik hapus → konfirmasi OK | Pesan "berhasil dihapus", foto ikut terhapus | <a href="screenshots/12-delete.png"><img src="screenshots/12-delete.png" width="220"></a> |
| 13 | Akses user biasa | Login `user` / `user123` | Tombol Tambah/Edit/Hapus tidak tampil | <a href="screenshots/13-role-user-tanpa-tombol.png"><img src="screenshots/13-role-user-tanpa-tombol.png" width="220"></a> |
| 14 | Bypass URL | Sebagai `user`, buka `/admin/biodata/create` | Ditolak: "Akses ditolak: fitur ini hanya untuk admin." | <a href="screenshots/14-bypass-url-ditolak.png"><img src="screenshots/14-bypass-url-ditolak.png" width="220"></a> |
| 15 | Logout | Klik nama user → Logout, lalu buka `/admin` | Kembali ke halaman login | <a href="screenshots/15-logout.png"><img src="screenshots/15-logout.png" width="220"></a> |
| 16 | Visual Studio | Solution Explorer + task "Jalankan Aplikasi" | Struktur folder MVC tampil dan server berjalan | 📸 *(ambil manual di Visual Studio)* |

---

## 8. Checkpoint

**Instalasi & Konfigurasi**
- [x] CodeIgniter 4 terpasang via Composer
- [x] Welcome Page tampil
- [x] `App.php`, `Database.php`, `.env` dikonfigurasi

**MVC**
- [x] Controller: `Home`, `Hello`, `Auth`, `Dashboard`, `Biodata`
- [x] Model: `BiodataModel`, `UserModel`
- [x] View: layout Bootstrap 5 & SB Admin 2

**Fitur**
- [x] Hello World
- [x] Router dengan placeholder & group
- [x] CRUD Biodata + pencarian + pagination
- [x] Upload foto dengan validasi
- [x] Login, logout, dan hak akses berbasis role

---

## 9. Kesimpulan

Aplikasi **Biodata Mahasiswa** berhasil dibangun dengan CodeIgniter 4 dan memenuhi seluruh poin Latihan 1. Pola **MVC** membuat tanggung jawab setiap bagian jelas: **Model** menangani database beserta validasinya, **View** hanya berisi tampilan (Bootstrap 5 untuk halaman publik dan SB Admin 2 untuk halaman admin), sedangkan **Controller** menghubungkan keduanya tanpa menulis SQL. **Router** menentukan controller mana yang dipanggil, dan **Filter** (`auth`, `role:admin`) menjaga hak akses sebelum request sampai ke controller, sehingga keamanan tidak bergantung pada tombol yang disembunyikan di tampilan. Fitur bawaan framework seperti Query Builder, validasi, session, CSRF, pagination, dan upload file mempercepat pengembangan dibanding menulis PHP dari awal. Penggunaan migration, seeder, dan SQLite juga membuat project dapat dijalankan ulang di komputer lain hanya dengan satu task di Visual Studio.

---

## 10. Referensi

- Fajar Baskoro – [PBKK-2 Pengenalan Framework Code Igniter](https://fajarbaskoro.blogspot.com/2019/02/pbkk-2-pengenalan-framework-code-igniter.html)
- [CodeIgniter 4 User Guide](https://codeigniter.com/user_guide/)
- [XAMPP Web Server](https://www.apachefriends.org/)
- [Start Bootstrap – SB Admin 2](https://startbootstrap.com/theme/sb-admin-2)
- [Bootstrap 5 Documentation](https://getbootstrap.com/docs/5.3/)
- Microsoft Learn – [Open Folder projects & tasks.vs.json di Visual Studio](https://learn.microsoft.com/visualstudio/ide/customize-build-and-debug-tasks-in-visual-studio)
