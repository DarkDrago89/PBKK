# Laporan Tugas Minggu 9 – Latihan 2a: Sistem Informasi Akademik (SIAKAD)

- **Mata Kuliah:** Pemrograman Berbasis Kerangka Kerja (PBKK)
- **Nama:** Agil Lukman Hakim Muchdi
- **NRP/Angkatan:** 5025241037/2024
- **Program Studi:** Teknik Informatika, Institut Teknologi Sepuluh Nopember
- **Materi:** [PBKK-2 Pengenalan Framework Code Igniter](https://fajarbaskoro.blogspot.com/2019/02/pbkk-2-pengenalan-framework-code-igniter.html) – Latihan 2: *Membuat sistem informasi akademik dengan menggunakan framework Code Igniter*
- **Framework:** CodeIgniter 4.7 (PHP 8.2+), SQLite / MySQL, template SB Admin 2

---

## 1. Deskripsi Singkat

**SIAKAD** adalah sistem informasi akademik berbasis web yang dibangun dengan framework **CodeIgniter 4** memakai pola **MVC**. Aplikasi memiliki tiga peran:

| Role | Yang bisa dilakukan |
|---|---|
| **Admin** | Mengelola master data mahasiswa, dosen, dan mata kuliah (CRUD + pencarian + pagination). Akun login dosen/mahasiswa dibuat otomatis. |
| **Mahasiswa** | Mengisi **KRS** semester aktif (maks. 24 SKS), membatalkan mata kuliah, melihat **KHS**, **IPS**, dan **IPK**, serta mencetak KHS. |
| **Dosen** | Melihat kelas yang diampu beserta jumlah peserta, lalu **menginput nilai** angka yang otomatis dikonversi ke nilai huruf. |

Alur arsitektur: `Browser → Router → Filter (auth, role) → Controller → Model → Database`, kemudian `Controller → View (SB Admin 2) → Browser`.

---

## 2. Fitur Framework yang Digunakan

| Fitur CodeIgniter | Penerapan |
|---|---|
| **Routing + Route Group bersarang** | Grup `''` (filter `auth`) berisi grup `admin`, `mahasiswa`, `dosen` dengan filter `role:*` dan namespace controller masing-masing |
| **Filter** | `AuthFilter` (wajib login), `RoleFilter` (pembatasan role lewat argumen `role:admin`) |
| **Controller + Namespace** | `Controllers\Admin\*`, `Controllers\Mahasiswa\*`, `Controllers\Dosen\*` |
| **Abstract Controller** | `Admin\MasterController` menampung alur CRUD bersama; `Mahasiswa`, `Dosen`, `Matakuliah` cukup mengisi properti |
| **Model** | Validasi (`is_unique` dengan `{id}`, `is_not_unique`), timestamps, Query Builder (JOIN, GROUP BY, SUM, COUNT, CASE) |
| **Migration & Seeder** | 5 tabel dengan **foreign key** (`CASCADE`, `SET NULL`), data contoh lengkap |
| **Transaksi Database** | Simpan mahasiswa/dosen + akun login dalam satu transaksi (`transStart`/`transComplete`) |
| **Custom Config** | `Config\Akademik`: tahun ajaran aktif, batas SKS, daftar prodi, dipanggil dengan `config('Akademik')` |
| **Custom Helper** | `akademik_helper.php`: `nilai_huruf()`, `nilai_bobot()`, `hitung_ip()` |
| **View Layout & Partial** | `layouts/app.php`, `partials/sidebar.php` (menu dinamis per role), `admin/_toolbar`, `_aksi`, `_footer` |
| **Form Helper, CSRF, Session Flash** | `form_dropdown()`, `csrf_field()`, `old()`, `with('success')` |
| **Pagination** | `paginate()` + template pager Bootstrap |
| **Lokalisasi** | Pesan validasi Bahasa Indonesia dari `codeigniter4/translations` |

---

## 3. Struktur Database

```
users (id, username*, nama, password, role)
  ├──< dosen (id, user_id → users CASCADE, nip*, nama, email)
  │      └──< matakuliah (id, kode*, nama, sks, semester, dosen_id → dosen SET NULL)
  └──< mahasiswa (id, user_id → users CASCADE, nim*, nama, prodi, angkatan, email)

krs (id, mahasiswa_id → mahasiswa CASCADE, matakuliah_id → matakuliah CASCADE,
     tahun_ajaran, nilai_angka, nilai_huruf)            * = unique
     UNIQUE (mahasiswa_id, matakuliah_id, tahun_ajaran)
```

Tabel `krs` berfungsi ganda: baris yang **belum dinilai** adalah KRS (rencana studi), dan setelah dosen mengisi nilai, baris yang sama menjadi bagian dari **KHS**.

**Efek foreign key:**
- Menghapus mahasiswa → akun login dan seluruh KRS-nya ikut terhapus (`CASCADE` dari `users`).
- Menghapus dosen → mata kuliah tetap ada, tetapi pengampunya menjadi kosong (`SET NULL`).

---

## 4. Logika Bisnis Utama

### 4.1 Pengisian KRS (`Controllers/Mahasiswa/Krs.php`)

Saat mahasiswa menekan **Ambil**, controller memeriksa:
1. Mata kuliah ada.
2. Belum diambil pada tahun ajaran yang sama.
3. `total SKS sekarang + SKS mata kuliah ≤ maxSks` (24).

Pembatalan hanya boleh untuk KRS **milik sendiri**, di **semester aktif**, dan **belum dinilai**.

### 4.2 Konversi Nilai & IPK (`Helpers/akademik_helper.php`)

| Nilai Angka | Huruf | Bobot |
|---|---|---|
| ≥ 86 | A | 4.0 |
| 76 – 85 | AB | 3.5 |
| 66 – 75 | B | 3.0 |
| 61 – 65 | BC | 2.5 |
| 56 – 60 | C | 2.0 |
| 41 – 55 | D | 1.0 |
| ≤ 40 | E | 0.0 |

```
IP = Σ (SKS × bobot) / Σ SKS          (hanya mata kuliah yang sudah dinilai)
IPS = IP per tahun ajaran,  IPK = IP seluruh semester
```

### 4.3 Input Nilai Dosen (`Controllers/Dosen/Kelas.php`)

- Dosen hanya bisa membuka kelas yang `dosen_id`-nya miliknya sendiri.
- ID KRS dari form dicocokkan dengan daftar peserta kelas tersebut, sehingga dosen tidak bisa mengubah nilai kelas lain walaupun memanipulasi form.
- Nilai di luar 0–100 ditolak. Nilai yang valid disimpan sekaligus dengan `updateBatch()`.

### 4.4 Transaksi Pembuatan Akun (`Controllers/Admin/MasterController.php`)

```php
$db->transStart();
$data['user_id'] = $users->buatAkun($nim, $nama, 'mahasiswa');   // 1. insert users
$this->model->skipValidation(true)->save($data);                  // 2. insert mahasiswa
$db->transComplete();                                             // gagal salah satu -> rollback
```

---

## 5. Struktur Project

```
SistemAkademik
├── app
│   ├── Config
│   │   ├── Akademik.php            # konfigurasi akademik (custom)
│   │   ├── Routes.php
│   │   └── Filters.php             # alias auth & role
│   ├── Controllers
│   │   ├── Auth.php, Dashboard.php
│   │   ├── Admin/                  # MasterController (abstract), Mahasiswa, Dosen, Matakuliah
│   │   ├── Mahasiswa/              # Krs, Khs
│   │   └── Dosen/                  # Kelas
│   ├── Database/Migrations         # users, dosen, mahasiswa, matakuliah, krs
│   ├── Database/Seeds              # DatabaseSeeder
│   ├── Filters                     # AuthFilter, RoleFilter
│   ├── Helpers/akademik_helper.php
│   ├── Models                      # UserModel, DosenModel, MahasiswaModel, MatakuliahModel, KrsModel
│   └── Views
│       ├── layouts/app.php         # SB Admin 2
│       ├── partials/               # sidebar, topbar, alerts
│       ├── auth/login.php
│       ├── dashboard/              # admin, dosen, mahasiswa
│       ├── admin/                  # mahasiswa/, dosen/, matakuliah/ (index & form)
│       ├── mahasiswa/              # krs, khs
│       └── dosen/                  # kelas, nilai
├── writable/siakad.db              # database SQLite (dibuat otomatis)
├── jalankan.bat
└── .vs/tasks.vs.json
```

---

## 6. Cara Menjalankan di Visual Studio

**Prasyarat:** Visual Studio 2022/2026, PHP 8.2+ di `PATH` (extension `intl`, `mbstring`, `sqlite3`), Composer.

1. **File → Open → Folder...** → pilih `TugasMinggu9/Latihan2/SistemAkademik`.
2. Klik kanan folder root di **Solution Explorer** → **1. Jalankan Aplikasi (localhost:8081)**.
   - Alternatif: buka folder induk `TugasMinggu9`, klik kanan → **Latihan 2 - Sistem Akademik (localhost:8081)**.
   - Atau dari **View → Terminal**: `jalankan.bat`.
3. Browser terbuka ke `http://localhost:8081`.

Task **2. Reset Database** mengembalikan semua data ke kondisi awal.

### Akun Demo

| Role | Username | Password |
|---|---|---|
| Admin | `admin` | `admin123` |
| Dosen | `198001012005011001` (Dr. Fajar Nugroho) | sama dengan NIP |
| Dosen | `198502022010012002` (Dr. Rina Kartika) | sama dengan NIP |
| Mahasiswa | `5025241037` (Agil) | sama dengan NIM |
| Mahasiswa | `5025241001` (Budi) | sama dengan NIM |

Untuk memakai **MySQL XAMPP**: buat database `siakad`, aktifkan `extension=mysqli`, buka komentar `database.default.*` di `.env`, lalu jalankan task Reset Database.

---

## 7. Hasil Pengujian

Skenario 1–15 sudah diuji pada CodeIgniter 4.7.6 + PHP 8.5 + SQLite. Screenshot diambil otomatis dari aplikasi yang berjalan memakai Google Chrome *headless*; bingkai di atas setiap gambar menampilkan URL yang dibuka dan status HTTP. Klik thumbnail untuk ukuran penuh.

| No | Skenario | Langkah / Input | Hasil yang Diharapkan | Screenshot |
|----|---|---|---|---|
| 1 | Login admin | `admin` / `admin123` | Dashboard: 4 mahasiswa, 3 dosen, 7 mata kuliah, 5 KRS semester ini | <a href="screenshots/01-dashboard-admin.png"><img src="screenshots/01-dashboard-admin.png" width="220"></a> |
| 2 | Tambah mahasiswa | NIM `5025249999`, data lengkap | "Mahasiswa berhasil ditambahkan (akun login: 5025249999 / 5025249999)" | <a href="screenshots/02-tambah-mahasiswa.png"><img src="screenshots/02-tambah-mahasiswa.png" width="220"></a> |
| 3 | Validasi | NIM yang sudah ada, nama `X`, angkatan `1990`, email `x` | Pesan: NIM sudah terdaftar, nama min. 3 karakter, angkatan > 2000, email tidak valid | <a href="screenshots/03-validasi.png"><img src="screenshots/03-validasi.png" width="220"></a> |
| 4 | Edit tanpa ubah NIM | Ubah nama saja | Tersimpan (rule `is_unique` mengabaikan record sendiri); nama di akun login ikut berubah | <a href="screenshots/04-edit-tanpa-ubah-nim.png"><img src="screenshots/04-edit-tanpa-ubah-nim.png" width="220"></a> |
| 5 | Hapus mahasiswa | Hapus mahasiswa yang punya KRS | Mahasiswa, akun, dan KRS-nya terhapus (cascade) | <a href="screenshots/05-hapus-mahasiswa.png"><img src="screenshots/05-hapus-mahasiswa.png" width="220"></a> |
| 6 | Hapus dosen | Hapus dosen pengampu | Mata kuliah tetap ada, kolom Pengampu "Belum ada" | <a href="screenshots/06-hapus-dosen-matakuliah.png"><img src="screenshots/06-hapus-dosen-matakuliah.png" width="220"></a> |
| 7 | Login mahasiswa | `5025241037` / `5025241037` | Dashboard: IPK 3.45, SKS semester ini 0/24 | <a href="screenshots/07-dashboard-mahasiswa.png"><img src="screenshots/07-dashboard-mahasiswa.png" width="220"></a> |
| 8 | Ambil KRS | Ambil 5 mata kuliah | Badge SKS bertambah (16/24) | <a href="screenshots/08-ambil-krs-16-sks.png"><img src="screenshots/08-ambil-krs-16-sks.png" width="220"></a> |
| 9 | Batas SKS | Ambil mata kuliah hingga > 24 SKS | "Gagal mengambil …: total SKS menjadi 26, melebihi batas 24 SKS." | <a href="screenshots/09-batas-sks.png"><img src="screenshots/09-batas-sks.png" width="220"></a> |
| 10 | Batal KRS | Batalkan *Kecerdasan Buatan* (belum dinilai) | "Mata kuliah dibatalkan dari KRS." Tombol Batal tidak muncul untuk mata kuliah yang sudah dinilai; bila dipaksa lewat POST, server menolak "sudah dinilai tidak bisa dibatalkan" | <a href="screenshots/10-batal-krs.png"><img src="screenshots/10-batal-krs.png" width="220"></a> |
| 11 | KHS & IPK | Menu KHS | Nilai per semester, IPS 3.45 (2025/2026 Genap), IPK, tombol Cetak | <a href="screenshots/11a-khs-sebelum-dinilai.png"><img src="screenshots/11a-khs-sebelum-dinilai.png" width="220"></a> |
| 12 | Login dosen | `198001012005011001` | Kelas PBKK (3 peserta, belum dinilai) & Pemrograman Web (0 peserta semester ini) | <a href="screenshots/12-dosen-kelas.png"><img src="screenshots/12-dosen-kelas.png" width="220"></a> |
| 13 | Input nilai | Isi 87, 62.5, 40 | Huruf otomatis A, BC, E; KHS & IPK mahasiswa langsung berubah | <a href="screenshots/13a-input-nilai.png"><img src="screenshots/13a-input-nilai.png" width="220"></a><br><a href="screenshots/13b-khs-setelah-dinilai.png"><img src="screenshots/13b-khs-setelah-dinilai.png" width="220"></a> |
| 14 | Validasi nilai | Isi 150 | "Nilai NIM … harus angka 0–100." | <a href="screenshots/14-validasi-nilai.png"><img src="screenshots/14-validasi-nilai.png" width="220"></a> |
| 15 | Hak akses | Mahasiswa buka `/admin/mahasiswa` atau `/dosen/kelas`; dosen buka kelas dosen lain | Diarahkan kembali dengan pesan akses ditolak | <a href="screenshots/15a-mahasiswa-akses-admin.png"><img src="screenshots/15a-mahasiswa-akses-admin.png" width="220"></a><br><a href="screenshots/15b-dosen-kelas-lain.png"><img src="screenshots/15b-dosen-kelas-lain.png" width="220"></a> |
| 16 | Visual Studio | Task "Jalankan Aplikasi" | Server berjalan di port 8081 | 📸 *(ambil manual di Visual Studio)* |

---

## 8. Kesimpulan

Sistem Informasi Akademik berhasil dibangun dengan CodeIgniter 4 dan memenuhi kebutuhan tiga peran pengguna. Framework sangat membantu pada bagian yang berulang: **Model** menangani validasi dan query JOIN/agregasi tanpa SQL manual, **Migration** dan **Seeder** membuat struktur database berelasi (dengan foreign key `CASCADE` dan `SET NULL`) bisa dibangun ulang dengan satu perintah, dan **Filter** di router menjamin hak akses tiap role sebelum request mencapai controller. Pewarisan controller (`MasterController`) mengurangi duplikasi kode CRUD, sedangkan **transaksi database** menjaga konsistensi saat satu aksi menyentuh dua tabel (akun login + data mahasiswa/dosen). Aturan akademik seperti tahun ajaran dan batas SKS dipisahkan ke **Config**, dan perhitungan nilai/IPK ke **Helper**, sehingga mudah diubah tanpa menyentuh controller.

---

## 9. Referensi

- Fajar Baskoro – [PBKK-2 Pengenalan Framework Code Igniter](https://fajarbaskoro.blogspot.com/2019/02/pbkk-2-pengenalan-framework-code-igniter.html)
- [CodeIgniter 4 User Guide](https://codeigniter.com/user_guide/) – Routing, Filters, Models, Database Transactions, Configuration, Helpers
- [Start Bootstrap – SB Admin 2](https://startbootstrap.com/theme/sb-admin-2)
