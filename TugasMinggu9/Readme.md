# Tugas Minggu 9: Pengenalan Framework CodeIgniter

- **Mata Kuliah:** Pemrograman Berbasis Kerangka Kerja (PBKK)
- **Nama:** Agil Lukman Hakim Muchdi
- **NRP/Angkatan:** 5025241037/2024
- **Program Studi:** Teknik Informatika, Institut Teknologi Sepuluh Nopember
- **Materi:** [PBKK-2 Pengenalan Framework Code Igniter](https://fajarbaskoro.blogspot.com/2019/02/pbkk-2-pengenalan-framework-code-igniter.html)
- **Framework:** CodeIgniter 4.7.6 (PHP 8.2+, pola MVC)

---

## Daftar Latihan

| Latihan | Folder | Aplikasi | Port | Laporan |
|---|---|---|---|---|
| **Latihan 1** | [`Latihan1/`](Latihan1/) | Biodata Mahasiswa: instalasi & konfigurasi CI, Hello World, router & controller, Bootstrap, template SB Admin, Model & CRUD, upload file, akses user | 8080 | [Readme](Latihan1/Readme.md) |
| **Latihan 2a** | [`Latihan2/SistemAkademik/`](Latihan2/SistemAkademik/) | Sistem Informasi Akademik: role admin/dosen/mahasiswa, master data, KRS, input nilai, KHS & IPK | 8081 | [Readme](Latihan2/SistemAkademik/Readme.md) |
| **Latihan 2b** | [`Latihan2/TokoOnline/`](Latihan2/TokoOnline/) | Toko Online: katalog, keranjang, checkout dengan transaksi stok, riwayat pesanan, panel admin | 8082 | [Readme](Latihan2/TokoOnline/Readme.md) |

Ketiga aplikasi adalah project CodeIgniter 4 yang berdiri sendiri (masing-masing punya `vendor/`, database, dan port sendiri), sehingga bisa dijalankan bersamaan.

```
TugasMinggu9
├── .vs/tasks.vs.json          # task Visual Studio untuk menjalankan ketiga aplikasi
├── Readme.md                  # (file ini)
├── Latihan1/                  # Biodata Mahasiswa
└── Latihan2/
    ├── SistemAkademik/        # SIAKAD
    └── TokoOnline/            # TokoKita
```

---

## Konsep Framework yang Dipelajari

| Konsep | Latihan 1 | Latihan 2a (SIAKAD) | Latihan 2b (Toko) |
|---|:-:|:-:|:-:|
| MVC (Model, View, Controller) | ✓ | ✓ | ✓ |
| Routing, placeholder, route group | ✓ | ✓ (bersarang + namespace) | ✓ |
| Filter (auth & role) | ✓ | ✓ (3 role) | ✓ (+ redirect ke URL tujuan) |
| Model: validasi, Query Builder, pagination | ✓ | ✓ (JOIN, agregasi) | ✓ (filter, sorting, subquery) |
| Migration & Seeder | ✓ | ✓ (foreign key) | ✓ (foreign key) |
| Layout, section, partial view | ✓ | ✓ | ✓ |
| Bootstrap / SB Admin 2 | ✓ / ✓ | – / ✓ | ✓ / ✓ |
| Upload file | ✓ | – | ✓ |
| Transaksi database | – | ✓ | ✓ (rollback stok) |
| Custom Config & Helper | – | ✓ | ✓ |
| Custom Service & Library | – | – | ✓ |
| Abstract controller (pewarisan) | – | ✓ | – |
| Custom Exception | – | – | ✓ |

---

## Cara Menjalankan di Visual Studio

**Prasyarat:** Visual Studio 2022/2026, **PHP 8.2+** di `PATH` dengan extension `intl`, `mbstring`, `sqlite3` (bisa memakai PHP dari XAMPP), dan **Composer**.

1. **File → Open → Folder...** → pilih folder `TugasMinggu9`.
2. Di **Solution Explorer**, klik kanan folder `TugasMinggu9` → pilih salah satu task:
   - **Latihan 1 - Biodata (localhost:8080)**
   - **Latihan 2 - Sistem Akademik (localhost:8081)**
   - **Latihan 2 - Toko Online (localhost:8082)**
3. Saat pertama dijalankan, `jalankan.bat` otomatis menyiapkan `vendor/` (jika belum ada), `.env`, database SQLite, dan data contoh, lalu membuka browser.

Setiap aplikasi juga bisa dibuka sendiri-sendiri dengan **Open Folder** ke folder aplikasinya (punya task **Jalankan Aplikasi** dan **Reset Database**), atau lewat **View → Terminal** dengan perintah `jalankan.bat`.

### Akun Demo

| Aplikasi | Akun |
|---|---|
| Latihan 1 | `admin / admin123`, `user / user123` |
| SIAKAD | `admin / admin123`, dosen `198001012005011001`, mahasiswa `5025241037` (password = username) |
| Toko Online | `admin / admin123`, customer `agil / agil123` |
