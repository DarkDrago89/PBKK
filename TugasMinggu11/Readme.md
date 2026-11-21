# Laporan Tugas Minggu 11: Authentication System CodeIgniter 4 (ShopEase)

- **Mata Kuliah:** Pemrograman Berbasis Kerangka Kerja (PBKK)
- **Nama:** Agil Lukman Hakim Muchdi
- **NRP/Angkatan:** 5025241037/2024
- **Program Studi:** Teknik Informatika, Institut Teknologi Sepuluh Nopember
- **Materi:** Hands-On Lab – *Authentication System menggunakan CodeIgniter 4* (studi kasus **ShopEase Online Store**)
- **Framework:** CodeIgniter 4.7.6 · PHP 8.2+ · SQLite / MySQL (`db_shopease`) · Bootstrap 5

---

## 1. Deskripsi Singkat

**ShopEase** adalah aplikasi toko online berbasis CodeIgniter 4 (MVC). Pada minggu ini dikerjakan **modul autentikasi**: registrasi, login, logout, session, role, dan proteksi akses dengan Filter. Modul ini menjadi pondasi seluruh fitur ShopEase pada PRD.

| Peran | Setelah login diarahkan ke | Hak akses |
|---|---|---|
| **Customer** | `/` (beranda) | Belanja, profil, keranjang & riwayat pesanan |
| **Admin** | `/admin` | Dashboard, **Kelola User (Role)**, panel operator |
| **Operator** | `/operator` | Dashboard operator (pemenuhan pesanan pada M4) |

Tampilan dibuat ulang dengan **UI/UX baru** yang mengikuti identitas ShopEase: warna ungu, logo tas belanja, font *Plus Jakarta Sans*, halaman login/registrasi dua kolom, storefront, serta panel admin/operator ber-sidebar. Semuanya dibangun di atas Bootstrap 5 dengan *design system* sendiri (`public/assets/css/shopease.css`).

### Tujuan Praktikum

- [x] Mengimplementasikan modul autentikasi (registrasi, login, logout) pada CI4.
- [x] Mengelola data pengguna dengan Model dan database.
- [x] Menerapkan session, role, dan proteksi akses (Filter).

---

## 2. Alur Proses Autentikasi

```
User ──▶ Form Registrasi / Login ──▶ AuthController ──▶ UserModel ──▶ Database (users)
  ▲                                         │
  └──────── redirect sesuai role ◀── Session (isLoggedIn, id_user, name, email, role)

Request berikutnya:  Router ──▶ AuthFilter (cek session & role) ──▶ Controller
```

1. **Registrasi:** data divalidasi, password di-hash dengan `password_hash()` (bcrypt), lalu disimpan dengan role `customer`.
2. **Login:** `password_verify()` membandingkan password dengan hash. Jika cocok, `session()->regenerate()` dijalankan dan data user disimpan di session.
3. **Redirect berdasarkan role:** admin → `/admin`, operator → `/operator`, customer → `/`.
4. **AuthFilter:** setiap halaman terlindung memeriksa `isLoggedIn`, dan bila diberi argumen (`auth:admin`), juga memeriksa role.
5. **Logout:** data login dihapus dan ID session diganti, sehingga session lama tidak dapat dipakai lagi.

---

## 3. Tahapan Praktikum

| No | Tahapan (Hands-On Lab) | Implementasi | Bukti |
|---|---|---|---|
| 1 | Buat database & tabel `users` | Migration `CreateUsers` (SQLite) **atau** `database/db_shopease.sql` (MySQL) | [k01](screenshots/k01-migration-users.png), [05](screenshots/05-password-tersimpan-hash.png) |
| 2 | Konfigurasi koneksi (`.env`) | `.env`: SQLite default, MySQL `db_shopease` tinggal dibuka komentarnya | §8 |
| 3 | Buat Model `UserModel` | `$table='users'`, `$primaryKey='id_user'`, `$allowedFields` sesuai lab | [k02](screenshots/k02-usermodel.png) |
| 4 | Buat `AuthController` | `register`, `store`, `login`, `authenticate`, `logout` | [k03](screenshots/k03-authcontroller-store.png), [k04](screenshots/k04-authcontroller-authenticate.png) |
| 5 | Atur Routes | 5 route autentikasi persis seperti lab + route terproteksi | [k05](screenshots/k05-routes.png), [k08](screenshots/k08-spark-routes.png) |
| 6 | Buat View Login & Registrasi | `auth/login.php`, `auth/register.php` (layout `layouts/auth.php`) | [02](screenshots/02-halaman-registrasi.png), [04](screenshots/04-registrasi-berhasil.png) |
| 7 | Buat AuthFilter & uji coba | `AuthFilter` dengan argumen role + `GuestFilter` | [k06](screenshots/k06-authfilter.png), §9 |

### Pengembangan di luar lab

| Fitur | Keterangan |
|---|---|
| **Ingat saya (30 hari)** | Token *selector + validator*: validator hanya disimpan hash SHA-256 di tabel `auth_tokens`, token **dirotasi** setiap dipakai, dan dihapus saat logout atau ganti password |
| **Anti brute force** | Maksimal 5 percobaan login per menit untuk tiap kombinasi IP + email (`Throttler` CI4) |
| **Kembali ke halaman tujuan** | Tamu yang membuka `/keranjang` diarahkan ke login, lalu dikembalikan ke `/keranjang` setelah berhasil |
| **GuestFilter** | User yang sudah login tidak bisa membuka `/login` atau `/register` |
| **Profil Saya** | Ubah nama/telepon/alamat, ganti password (wajib password lama) |
| **Kelola User (Role)** | Admin: cari & filter user, tambah akun (mis. operator), ubah role, hapus. Admin tidak bisa mengubah atau menghapus akunnya sendiri |
| **Dashboard admin** | Statistik user per role, grafik registrasi 7 hari (Chart.js), daftar user terbaru |
| **Service `auth`** | Logika login/logout terpusat di `App\Libraries\Auth`, dipanggil lewat `service('auth')` |

---

## 4. Struktur Database

```sql
CREATE TABLE users (
    id_user    INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    email      VARCHAR(100) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,                              -- hash bcrypt
    role       ENUM('customer','admin','operator') DEFAULT 'customer',
    phone      VARCHAR(20),
    address    TEXT,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
);

-- pengembangan: token "Ingat saya"
CREATE TABLE auth_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,                  -- FK -> users.id_user ON DELETE CASCADE
    selector VARCHAR(24) NOT NULL UNIQUE,
    validator_hash VARCHAR(64) NOT NULL,   -- SHA-256 dari validator, bukan nilai aslinya
    expires_at DATETIME NOT NULL,
    created_at DATETIME NULL
);
```

Di SQLite, CodeIgniter menerjemahkan `ENUM` menjadi `CHECK(role IN ('customer','admin','operator'))`. Jadi role tidak valid seperti `superadmin` ditolak oleh database itu sendiri, sama seperti `ENUM` di MySQL.

---

## 5. Struktur Folder

```
TugasMinggu11
├── .vs/tasks.vs.json                   # task Visual Studio
├── Readme.md
├── screenshots/                        # bukti pengujian
└── shopease/
    ├── app/
    │   ├── Config/
    │   │   ├── Routes.php              # route autentikasi + area terproteksi
    │   │   ├── Filters.php             # alias auth, guest, remember + CSRF global
    │   │   ├── Services.php            # service('auth')
    │   │   ├── Validation.php          # rule kustom password_kuat
    │   │   └── ShopEase.php            # data tampilan beranda
    │   ├── Controllers/
    │   │   ├── AuthController.php      # register, store, login, authenticate, logout
    │   │   ├── Home.php, Profile.php
    │   │   ├── Admin/                  # Dashboard, Users
    │   │   └── Operator/Dashboard.php
    │   ├── Database/Migrations/        # users, auth_tokens
    │   ├── Database/Seeds/             # akun demo
    │   ├── Filters/                    # AuthFilter, GuestFilter, RememberMeFilter
    │   ├── Libraries/Auth.php          # logika login, session, remember me
    │   ├── Models/                     # UserModel, AuthTokenModel
    │   ├── Validation/PasswordRules.php
    │   └── Views/
    │       ├── layouts/                # auth, store, panel
    │       ├── partials/               # head, logo, flash
    │       ├── auth/                   # login, register
    │       ├── store/, profile/, admin/, operator/
    ├── database/db_shopease.sql        # skrip MySQL (opsional)
    ├── public/assets/css/shopease.css  # design system ShopEase
    ├── writable/shopease.db            # database SQLite (dibuat otomatis)
    └── jalankan.bat
```

---

## 6. Potongan Kode Penting

**Migration tabel `users`**

![migration](screenshots/k01-migration-users.png)

**Model – `UserModel.php`**

![UserModel](screenshots/k02-usermodel.png)

**Controller – `AuthController.php` (registrasi)**

![store](screenshots/k03-authcontroller-store.png)

**Controller – `AuthController.php` (login)**

![authenticate](screenshots/k04-authcontroller-authenticate.png)

**Library – `Auth.php` (cek password & membuat session)**

![auth library](screenshots/k07-auth-library-login.png)

**Routes – `app/Config/Routes.php`**

![routes](screenshots/k05-routes.png)

**AuthFilter (proteksi akses)**

![AuthFilter](screenshots/k06-authfilter.png)

**`php spark routes`**: kolom *Before Filters* menunjukkan filter yang aktif di setiap route:

![spark routes](screenshots/k08-spark-routes.png)

---

## 7. Tampilan (UI/UX Baru)

| Beranda (tamu) | Login & Registrasi |
|---|---|
| <a href="screenshots/01-beranda-tamu.png"><img src="screenshots/01-beranda-tamu.png" width="420"></a> | <a href="screenshots/02-halaman-registrasi.png"><img src="screenshots/02-halaman-registrasi.png" width="420"></a> |
| **Dashboard Admin** | **Kelola User (Role)** |
| <a href="screenshots/16-login-admin-dashboard.png"><img src="screenshots/16-login-admin-dashboard.png" width="420"></a> | <a href="screenshots/17-kelola-user.png"><img src="screenshots/17-kelola-user.png" width="420"></a> |
| **Dashboard Operator** | **Profil Saya** |
| <a href="screenshots/21-login-operator-dashboard.png"><img src="screenshots/21-login-operator-dashboard.png" width="420"></a> | <a href="screenshots/13-profil-diperbarui.png"><img src="screenshots/13-profil-diperbarui.png" width="420"></a> |

Responsif di layar ponsel:

<a href="screenshots/26a-mobile-login.png"><img src="screenshots/26a-mobile-login.png" width="220"></a>
<a href="screenshots/26b-mobile-registrasi.png"><img src="screenshots/26b-mobile-registrasi.png" width="220"></a>

---

## 8. Cara Menjalankan di Visual Studio

**Prasyarat:** Visual Studio 2022/2026, PHP 8.2+ di `PATH` (extension `intl`, `mbstring`, `sqlite3`), Composer.

1. **File → Open → Folder...** → pilih `TugasMinggu11`.
2. Klik kanan folder di **Solution Explorer** → **1. Jalankan ShopEase (localhost:8080)**.
   - Pertama kali dijalankan: `vendor/`, `.env`, database SQLite, dan akun demo disiapkan otomatis.
   - Atau dari **View → Terminal**: `cd shopease` lalu `jalankan.bat`.
3. Buka `http://localhost:8080`.

Task lain: **2. Reset Database** (kembali ke akun demo) dan **3. Lihat Daftar Route**.

### Akun Demo

| Role | Email | Password |
|---|---|---|
| Admin | `admin@shopease.test` | `admin123` |
| Operator | `operator@shopease.test` | `operator123` |
| Customer | `agil@shopease.test` | `customer123` |

### Memakai MySQL (XAMPP)

Pilih **salah satu** cara berikut (keduanya membuat tabel yang sama):

- **Skrip SQL:** jalankan `shopease/database/db_shopease.sql` di phpMyAdmin.
- **Migration:** buat database kosong `db_shopease`, lalu `php spark migrate` dan `php spark db:seed DatabaseSeeder`.

Setelah itu aktifkan `extension=mysqli` di `php.ini`, dan buka komentar blok `database.default.*` di `shopease/.env`.

---

## 9. Hasil Pengujian

Semua skenario diuji pada CodeIgniter 4.7.6 + PHP 8.5 + SQLite. Screenshot diambil otomatis dari aplikasi yang berjalan memakai Google Chrome *headless*; bingkai di atas setiap gambar menampilkan URL yang dibuka dan status HTTP. Klik thumbnail untuk ukuran penuh.

### 9.1 Registrasi & Login

| No | Skenario | Langkah / Input | Hasil | Screenshot |
|---|---|---|---|---|
| 1 | Beranda tamu | Buka `/` | Tombol **Masuk/Daftar** tampil, belum ada avatar | <a href="screenshots/01-beranda-tamu.png"><img src="screenshots/01-beranda-tamu.png" width="200"></a> |
| 2 | Form registrasi | Buka `/register`, ketik password | Indikator kekuatan password berubah | <a href="screenshots/02-halaman-registrasi.png"><img src="screenshots/02-halaman-registrasi.png" width="200"></a> |
| 3 | Validasi registrasi | Nama `X`, email terdaftar, telepon `123`, password `abc`, konfirmasi beda | 5 pesan error, input lama tetap terisi | <a href="screenshots/03-registrasi-validasi.png"><img src="screenshots/03-registrasi-validasi.png" width="200"></a> |
| 4 | Registrasi berhasil | Data valid | Diarahkan ke login, pesan sukses, email terisi otomatis | <a href="screenshots/04-registrasi-berhasil.png"><img src="screenshots/04-registrasi-berhasil.png" width="200"></a> |
| 5 | Password ter-hash | `SELECT ... FROM users` | Kolom password berisi hash `$2y$12$...`, bukan teks asli; role user baru `customer` | <a href="screenshots/05-password-tersimpan-hash.png"><img src="screenshots/05-password-tersimpan-hash.png" width="200"></a> |
| 6 | Login gagal | Password salah | "Email atau password salah." (pesan umum, tidak membocorkan email terdaftar) | <a href="screenshots/06-login-gagal.png"><img src="screenshots/06-login-gagal.png" width="200"></a> |
| 7 | Anti brute force | 6× password salah untuk akun yang sama | "Terlalu banyak percobaan login. Coba lagi dalam … detik." | <a href="screenshots/07-throttle-brute-force.png"><img src="screenshots/07-throttle-brute-force.png" width="200"></a> |

### 9.2 Session, Role & Proteksi Akses

| No | Skenario | Langkah / Input | Hasil | Screenshot |
|---|---|---|---|---|
| 8 | Tamu buka halaman terlindung | Buka `/keranjang` | Diarahkan ke `/login`, "Silakan login terlebih dahulu." | <a href="screenshots/08-tamu-akses-keranjang.png"><img src="screenshots/08-tamu-akses-keranjang.png" width="200"></a> |
| 9 | Kembali ke halaman tujuan | Login sebagai customer | Langsung kembali ke `/keranjang` | <a href="screenshots/09-login-kembali-ke-keranjang.png"><img src="screenshots/09-login-kembali-ke-keranjang.png" width="200"></a> |
| 10 | Menu customer | Klik avatar | Profil Saya, Riwayat Pesanan, Logout | <a href="screenshots/10-customer-beranda-menu.png"><img src="screenshots/10-customer-beranda-menu.png" width="200"></a> |
| 11 | Customer → `/admin` | Buka `/admin` | Kembali ke `/` dengan pesan akses ditolak | <a href="screenshots/11-customer-akses-admin-ditolak.png"><img src="screenshots/11-customer-akses-admin-ditolak.png" width="200"></a> |
| 12 | Sudah login → `/login` | Buka `/login` | `GuestFilter` mengarahkan ke beranda | <a href="screenshots/12-sudah-login-buka-login.png"><img src="screenshots/12-sudah-login-buka-login.png" width="200"></a> |
| 13 | Ubah profil | Nama, telepon, alamat | "Profil berhasil diperbarui.", nama di navbar ikut berubah | <a href="screenshots/13-profil-diperbarui.png"><img src="screenshots/13-profil-diperbarui.png" width="200"></a> |
| 14 | Ganti password | Password saat ini salah | "Password saat ini salah." | <a href="screenshots/14-ganti-password-salah.png"><img src="screenshots/14-ganti-password-salah.png" width="200"></a> |
| 15 | Logout | Menu → Logout (POST + CSRF) | Kembali ke login, "Anda telah logout." | <a href="screenshots/15-logout.png"><img src="screenshots/15-logout.png" width="200"></a> |
| 16 | Login admin | `admin@shopease.test` | Diarahkan ke `/admin` | <a href="screenshots/16-login-admin-dashboard.png"><img src="screenshots/16-login-admin-dashboard.png" width="200"></a> |
| 17 | Kelola user | `/admin/users` | Daftar user + role; akun sendiri bertanda **Anda** tanpa tombol ubah/hapus | <a href="screenshots/17-kelola-user.png"><img src="screenshots/17-kelola-user.png" width="200"></a> |
| 18 | Tambah operator | Form Tambah User, role operator | "Akun operator … berhasil dibuat." | <a href="screenshots/18a-form-tambah-operator.png"><img src="screenshots/18a-form-tambah-operator.png" width="200"></a><br><a href="screenshots/18b-operator-dibuat.png"><img src="screenshots/18b-operator-dibuat.png" width="200"></a> |
| 19 | Ubah role | Siti: customer → operator | "Role Siti Rahma diubah dari customer menjadi operator." | <a href="screenshots/19-ubah-role.png"><img src="screenshots/19-ubah-role.png" width="200"></a> |
| 20 | Filter multi-role | Admin buka `/operator` (`auth:operator,admin`) | Diizinkan | <a href="screenshots/20-admin-akses-operator.png"><img src="screenshots/20-admin-akses-operator.png" width="200"></a> |
| 21 | Login operator | `operator@shopease.test` | Diarahkan ke `/operator` | <a href="screenshots/21-login-operator-dashboard.png"><img src="screenshots/21-login-operator-dashboard.png" width="200"></a> |
| 22 | Operator → `/admin` | Buka `/admin` | Kembali ke `/operator` dengan pesan akses ditolak | <a href="screenshots/22-operator-akses-admin-ditolak.png"><img src="screenshots/22-operator-akses-admin-ditolak.png" width="200"></a> |

### 9.3 Keamanan

| No | Skenario | Langkah / Input | Hasil | Screenshot |
|---|---|---|---|---|
| 23 | Ingat saya | Login dengan centang, hapus cookie `shopease_session`, buka `/profil` | Tetap login; token lama dihapus dan diganti token baru (rotasi) | <a href="screenshots/23a-remember-me-cookie.png"><img src="screenshots/23a-remember-me-cookie.png" width="200"></a><br><a href="screenshots/23b-remember-me-tetap-login.png"><img src="screenshots/23b-remember-me-tetap-login.png" width="200"></a> |
| 24 | CSRF | Kirim form login dengan token palsu | HTTP 403 *SecurityException* (mode development menampilkan detail; di production halaman generik) | <a href="screenshots/24-csrf-token-palsu.png"><img src="screenshots/24-csrf-token-palsu.png" width="200"></a> |
| 25 | Logout via GET | Buka `/logout` langsung | 404, logout hanya lewat POST (link jahat tidak bisa me-logout user) | <a href="screenshots/25-logout-via-get-ditolak.png"><img src="screenshots/25-logout-via-get-ditolak.png" width="200"></a> |
| 26 | Responsif | Lebar layar 390 px | Form login & registrasi menyesuaikan layar | <a href="screenshots/26a-mobile-login.png"><img src="screenshots/26a-mobile-login.png" width="120"></a> <a href="screenshots/26b-mobile-registrasi.png"><img src="screenshots/26b-mobile-registrasi.png" width="120"></a> |
| 27 | Visual Studio | Task "Jalankan ShopEase" | Server berjalan di port 8080 | 📸 *(ambil manual di Visual Studio)* |

Pengujian tambahan lewat HTTP (tanpa screenshot): token "Ingat saya" lama yang dipakai ulang ditolak; logout menghapus token di database; setelah ganti password, password lama tidak bisa dipakai login; percobaan login akun lain dari IP yang sama tidak ikut terblokir; role di luar ENUM ditolak.

---

## 10. Checkpoint Keamanan

- [x] Password disimpan sebagai hash (`password_hash` / `password_verify`)
- [x] `session()->regenerate()` saat login (cegah *session fixation*)
- [x] CSRF aktif global untuk semua form POST
- [x] Logout hanya lewat POST
- [x] Pesan login gagal umum (tidak membocorkan email terdaftar) + `password_verify` tetap dijalankan walau email tidak ada (cegah *timing attack*)
- [x] Pembatasan percobaan login (*throttling*)
- [x] Proteksi akses berbasis role di router (`auth:admin`), bukan sekadar menyembunyikan menu
- [x] Registrasi publik selalu `customer`; role lain hanya bisa diberikan admin
- [x] Token "Ingat saya" disimpan sebagai hash, dirotasi, dan dicabut saat logout/ganti password
- [x] Output di-escape dengan `esc()` (cegah XSS)

---

## 11. Kesimpulan

Modul autentikasi ShopEase berhasil diimplementasikan sesuai tahapan Hands-On Lab: tabel `users` dengan role ENUM, `UserModel`, `AuthController` (registrasi, login, logout), route autentikasi, view login/registrasi, dan `AuthFilter` yang memeriksa login sekaligus role. Fitur bawaan CodeIgniter 4 dimanfaatkan penuh: **Validation** untuk input, **Session** untuk status login, **Filter** untuk proteksi akses di level router, **CSRF** untuk melindungi form, **Throttler** untuk menahan brute force, dan **Services** untuk memusatkan logika autentikasi di satu library. Redirect berdasarkan role membuat customer, admin, dan operator langsung masuk ke halaman yang sesuai, sementara pengembangan seperti "Ingat saya" dengan rotasi token, Kelola User (Role), dan Profil Saya membuat modul ini siap menjadi pondasi milestone berikutnya (katalog, keranjang, transaksi). Tampilan baru berbasis *design system* ShopEase menjaga konsistensi visual di seluruh halaman dan tetap responsif di ponsel.

---

## 12. Referensi

- Hands-On Lab: *Authentication System menggunakan CodeIgniter 4* (ShopEase), PRD & Arsitektur Aplikasi ShopEase
- [CodeIgniter 4 User Guide](https://codeigniter.com/user_guide/) – Sessions, Controller Filters, Validation, Security (CSRF), Throttler, Services
- [PHP Manual – password_hash](https://www.php.net/manual/en/function.password-hash.php)
- [Bootstrap 5](https://getbootstrap.com/docs/5.3/) · [Bootstrap Icons](https://icons.getbootstrap.com/) · [Chart.js](https://www.chartjs.org/)
