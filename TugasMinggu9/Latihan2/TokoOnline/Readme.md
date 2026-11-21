# Laporan Tugas Minggu 9 – Latihan 2b: Toko Online

- **Mata Kuliah:** Pemrograman Berbasis Kerangka Kerja (PBKK)
- **Nama:** Agil Lukman Hakim Muchdi
- **NRP/Angkatan:** 5025241037/2024
- **Program Studi:** Teknik Informatika, Institut Teknologi Sepuluh Nopember
- **Materi:** [PBKK-2 Pengenalan Framework Code Igniter](https://fajarbaskoro.blogspot.com/2019/02/pbkk-2-pengenalan-framework-code-igniter.html) – Latihan 2: *Membuat Toko Online*
- **Framework:** CodeIgniter 4.7 (PHP 8.2+), SQLite / MySQL, Bootstrap 5 (toko) + SB Admin 2 (admin)

---

## 1. Deskripsi Singkat

**TokoKita** adalah aplikasi toko online berbasis **CodeIgniter 4** dengan pola **MVC**. Ada dua sisi aplikasi:

| Sisi | Fitur |
|---|---|
| **Pembeli** (Bootstrap 5) | Katalog dengan filter kategori, pencarian, urutan harga/nama, dan pagination; detail produk & produk terkait; **keranjang** (bisa diisi sebelum login); registrasi & login; **checkout** dengan alamat pengiriman; riwayat & detail pesanan; batalkan pesanan yang masih menunggu |
| **Admin** (SB Admin 2) | Dashboard omzet, pesanan menunggu, dan stok menipis; CRUD **kategori**; CRUD **produk** + upload gambar; kelola **pesanan** dan ubah status (menunggu → diproses → dikirim → selesai / dibatalkan) |

Alur arsitektur: `Browser → Router → Filter (auth, role) → Controller → Service/Model → Database`, kemudian `Controller → View → Browser`.

---

## 2. Fitur Framework yang Digunakan

| Fitur CodeIgniter | Penerapan |
|---|---|
| **Routing + Route Group** | Rute publik (katalog, keranjang), grup customer (`role:customer`), grup admin (`role:admin`, namespace `Admin`) |
| **Filter** | `AuthFilter` menyimpan URL tujuan → setelah login user **kembali ke halaman semula** (misal `/checkout`); `RoleFilter` |
| **Custom Service** | `service('keranjang')` → `App\Libraries\Keranjang` didaftarkan di `Config\Services` (shared instance) |
| **Library** | `Keranjang`: menyimpan `[produk_id => qty]` di session, harga & stok selalu diambil ulang dari database |
| **Model + Query Builder** | Validasi, `withKategori()` (JOIN), `katalog()` (filter + sorting + pagination), subquery jumlah barang, `selectSum` omzet |
| **Transaksi Database** | Checkout: kurangi stok + simpan pesanan + simpan detail, rollback bila gagal; pembatalan mengembalikan stok |
| **Custom Exception** | `StokException` dilempar saat stok kurang dan ditangkap controller untuk ditampilkan ke user |
| **Migration & Seeder** | 5 tabel dengan foreign key (`CASCADE`, `SET NULL`), 12 produk dalam 4 kategori, 1 pesanan contoh |
| **Custom Config & Helper** | `Config\Toko` (nama toko, ongkir, gratis ongkir, status) + `toko_helper.php` (`rupiah()`, `gambar_produk()`, `status_badge()`) |
| **File Upload** | Gambar produk (jpg/png/webp, maks 2 MB), nama acak, file lama dihapus saat diganti/dihapus |
| **Validasi** | Rule bawaan (`is_unique`, `matches`, `regex_match` untuk no. HP, `is_not_unique`) + pesan kustom Bahasa Indonesia |
| **View Layout & Partial** | `layouts/shop.php`, `layouts/admin.php`, partial `_kartu_produk`, `_ringkasan` |

---

## 3. Struktur Database

```
users (id, username*, nama, email*, password, role, telepon, alamat)
  └──< pesanan (id, kode*, user_id → users CASCADE, nama_penerima, telepon, alamat,
                catatan, subtotal, ongkir, total, status)
          └──< pesanan_detail (id, pesanan_id → pesanan CASCADE,
                               produk_id → produk SET NULL,
                               nama_produk, harga, qty, subtotal)

kategori (id, nama, slug*)
  └──< produk (id, kategori_id → kategori SET NULL, nama, slug*, deskripsi, harga, stok, gambar)
                                                               * = unique
```

`pesanan_detail` menyimpan **salinan** (snapshot) `nama_produk` dan `harga` saat pembelian. Jadi bila admin mengubah harga atau menghapus produk, riwayat pesanan lama tidak ikut berubah.

---

## 4. Logika Bisnis Utama

### 4.1 Keranjang (`Libraries/Keranjang.php`)

- Session hanya menyimpan `[produk_id => qty]`. Harga dan stok selalu dibaca ulang dari database setiap kali keranjang ditampilkan.
- Menambah produk ditolak bila `qty di keranjang + qty baru > stok`.
- **Ongkir:** Rp15.000 flat, **gratis** bila subtotal ≥ Rp500.000 (diatur di `Config\Toko`).
- Keranjang yang diisi sebelum login tetap ada setelah login, karena `session()->regenerate()` mengganti ID session tanpa menghapus isinya.

### 4.2 Checkout dengan Transaksi (`Models/PesananModel::buat`)

```php
$this->db->transBegin();
try {
    foreach ($items as $it) {
        // cek & kurangi stok dalam SATU query -> aman dari dua pembeli bersamaan
        UPDATE produk SET stok = stok - :qty WHERE id = :id AND stok >= :qty
        if (affectedRows() === 0) throw StokException::untuk($it->nama);
    }
    insert pesanan;  insertBatch pesanan_detail;
    $this->db->transCommit();
} catch (Throwable $e) {
    $this->db->transRollback();   // stok produk lain yang sudah dikurangi ikut dikembalikan
    throw $e;
}
```

### 4.3 Status Pesanan (`PesananModel::transisiValid`)

```
menunggu ──▶ diproses ──▶ dikirim ──▶ selesai
    │            │
    └────────────┴──▶ dibatalkan   (stok dikembalikan)
```

- Admin hanya melihat tombol untuk transisi yang valid dari status saat ini.
- Request manual ke transisi yang tidak valid (misal `menunggu → selesai`) tetap ditolak di model.
- Customer hanya bisa membatalkan pesanan berstatus **menunggu**.
- Pesanan milik orang lain mengembalikan **404**, sehingga kode pesanan tidak bisa ditebak untuk mengintip data.

---

## 5. Struktur Project

```
TokoOnline
├── app
│   ├── Config
│   │   ├── Routes.php, Filters.php
│   │   ├── Services.php            # service('keranjang')
│   │   └── Toko.php                # konfigurasi toko (custom)
│   ├── Controllers
│   │   ├── Home.php                # katalog & detail produk
│   │   ├── Keranjang.php, Checkout.php, Auth.php
│   │   ├── Akun/Pesanan.php        # riwayat pesanan customer
│   │   └── Admin/                  # Dashboard, Kategori, Produk, Pesanan
│   ├── Database/Migrations, Seeds
│   ├── Exceptions/StokException.php
│   ├── Filters/                    # AuthFilter, RoleFilter
│   ├── Helpers/toko_helper.php
│   ├── Libraries/Keranjang.php
│   ├── Models/                     # User, Kategori, Produk, Pesanan, PesananDetail
│   └── Views
│       ├── layouts/                # shop.php (Bootstrap 5), admin.php (SB Admin 2)
│       ├── shop/                   # katalog, produk, keranjang, checkout, pesanan_*
│       ├── auth/                   # login, register
│       └── admin/                  # dashboard, kategori, produk_*, pesanan_*
├── public/uploads/produk           # gambar produk hasil upload
├── writable/toko.db                # database SQLite (dibuat otomatis)
├── jalankan.bat
└── .vs/tasks.vs.json
```

---

## 6. Cara Menjalankan di Visual Studio

**Prasyarat:** Visual Studio 2022/2026, PHP 8.2+ di `PATH` (extension `intl`, `mbstring`, `sqlite3`), Composer.

1. **File → Open → Folder...** → pilih `TugasMinggu9/Latihan2/TokoOnline`.
2. Klik kanan folder root di **Solution Explorer** → **1. Jalankan Aplikasi (localhost:8082)**.
   - Alternatif: buka folder induk `TugasMinggu9`, klik kanan → **Latihan 2 - Toko Online (localhost:8082)**.
   - Atau dari **View → Terminal**: `jalankan.bat`.
3. Browser terbuka ke `http://localhost:8082`.

### Akun Demo

| Role | Login | Password |
|---|---|---|
| Admin | `admin` | `admin123` |
| Customer | `agil` atau `agil@example.com` | `agil123` |

Customer baru juga bisa mendaftar lewat menu **Daftar**. Untuk **MySQL XAMPP**: buat database `toko`, aktifkan `extension=mysqli`, buka komentar `database.default.*` di `.env`, lalu jalankan task Reset Database.

---

## 7. Hasil Pengujian

Skenario 1–18 sudah diuji pada CodeIgniter 4.7.6 + PHP 8.5 + SQLite. Screenshot diambil otomatis dari aplikasi yang berjalan memakai Google Chrome *headless*; bingkai di atas setiap gambar menampilkan URL yang dibuka dan status HTTP. Klik thumbnail untuk ukuran penuh.

| No | Skenario | Langkah / Input | Hasil yang Diharapkan | Screenshot |
|----|---|---|---|---|
| 1 | Katalog | Buka `/` | 12 produk, 8 per halaman, sidebar kategori dengan jumlah produk | <a href="screenshots/01-katalog.png"><img src="screenshots/01-katalog.png" width="220"></a> |
| 2 | Filter & cari | Kategori **Buku**; cari `mouse`; urut **Harga termurah** | 3 produk; Mouse Wireless Silent; Rp25.000 paling atas | <a href="screenshots/02a-filter-kategori.png"><img src="screenshots/02a-filter-kategori.png" width="220"></a><br><a href="screenshots/02b-pencarian.png"><img src="screenshots/02b-pencarian.png" width="220"></a><br><a href="screenshots/02c-urut-termurah.png"><img src="screenshots/02c-urut-termurah.png" width="220"></a> |
| 3 | Detail produk | Klik Powerbank | Harga, stok 3, produk terkait | <a href="screenshots/03-detail-produk.png"><img src="screenshots/03-detail-produk.png" width="220"></a> |
| 4 | Tambah keranjang (tamu) | Powerbank × 2 | "Powerbank 10000mAh ditambahkan ke keranjang.", badge 2 | <a href="screenshots/04-tambah-keranjang.png"><img src="screenshots/04-tambah-keranjang.png" width="220"></a> |
| 5 | Melebihi stok | Powerbank × 2 lagi | "Stok Powerbank 10000mAh hanya 3, di keranjang sudah ada 2." | <a href="screenshots/05-melebihi-stok.png"><img src="screenshots/05-melebihi-stok.png" width="220"></a> |
| 6 | Stok habis | Buka *Clean Code* (stok 0) | Tombol berubah menjadi "Stok tidak tersedia" (nonaktif); bila dipaksa lewat POST, server menolak "Maaf, stok … sedang habis." | <a href="screenshots/06-stok-habis.png"><img src="screenshots/06-stok-habis.png" width="220"></a> |
| 7 | Gratis ongkir | Keranjang ≥ Rp500.000 | Ongkos kirim "Gratis" | <a href="screenshots/07-keranjang-gratis-ongkir.png"><img src="screenshots/07-keranjang-gratis-ongkir.png" width="220"></a> |
| 8 | Checkout → login → kembali | Klik Checkout saat belum login, login `agil` | Diarahkan ke login lalu kembali ke `/checkout`, keranjang tetap | <a href="screenshots/08a-checkout-harus-login.png"><img src="screenshots/08a-checkout-harus-login.png" width="220"></a><br><a href="screenshots/08b-kembali-ke-checkout.png"><img src="screenshots/08b-kembali-ke-checkout.png" width="220"></a> |
| 9 | Validasi checkout | No. HP `123`, alamat `pendek` | Pesan no. HP harus diawali 08/+628, alamat min. 10 karakter | <a href="screenshots/09-validasi-checkout.png"><img src="screenshots/09-validasi-checkout.png" width="220"></a> |
| 10 | Checkout berhasil | Data valid → Buat Pesanan | Halaman pesanan `INV-…` status Menunggu, stok berkurang, keranjang kosong | <a href="screenshots/10-checkout-berhasil.png"><img src="screenshots/10-checkout-berhasil.png" width="220"></a> |
| 11 | Rollback stok | Stok salah satu produk habis tepat sebelum checkout | "Stok … tidak mencukupi", tidak ada pesanan baru, stok produk lain tidak berubah | <a href="screenshots/11-rollback-stok.png"><img src="screenshots/11-rollback-stok.png" width="220"></a> |
| 12 | Customer batal | Batalkan pesanan Menunggu | Status Dibatalkan, stok kembali | <a href="screenshots/12-customer-batal.png"><img src="screenshots/12-customer-batal.png" width="220"></a> |
| 13 | Admin status | Pesanan: Diproses → Dikirim → Selesai | Tombol hanya transisi valid; omzet dashboard bertambah | <a href="screenshots/13a-admin-pesanan-menunggu.png"><img src="screenshots/13a-admin-pesanan-menunggu.png" width="220"></a><br><a href="screenshots/13b-admin-pesanan-dikirim.png"><img src="screenshots/13b-admin-pesanan-dikirim.png" width="220"></a><br><a href="screenshots/13c-admin-pesanan-selesai.png"><img src="screenshots/13c-admin-pesanan-selesai.png" width="220"></a><br><a href="screenshots/13d-dashboard-omzet.png"><img src="screenshots/13d-dashboard-omzet.png" width="220"></a> |
| 14 | Transisi tidak valid | Kirim `menunggu → selesai` atau `dikirim → dibatalkan` | Ditolak: "Status tidak bisa diubah dari …" | <a href="screenshots/14-transisi-tidak-valid.png"><img src="screenshots/14-transisi-tidak-valid.png" width="220"></a> |
| 15 | CRUD kategori | Tambah "Olahraga", tambah "buku" lagi | Slug dibuat otomatis; duplikat ditolak | <a href="screenshots/15a-kategori-tambah.png"><img src="screenshots/15a-kategori-tambah.png" width="220"></a><br><a href="screenshots/15b-kategori-duplikat.png"><img src="screenshots/15b-kategori-duplikat.png" width="220"></a> |
| 16 | CRUD produk + upload | Tambah produk dengan gambar; data kosong/harga 0 | Gambar tampil; pesan validasi muncul | <a href="screenshots/16a-form-produk.png"><img src="screenshots/16a-form-produk.png" width="220"></a><br><a href="screenshots/16b-produk-tersimpan.png"><img src="screenshots/16b-produk-tersimpan.png" width="220"></a><br><a href="screenshots/16c-produk-di-toko.png"><img src="screenshots/16c-produk-di-toko.png" width="220"></a><br><a href="screenshots/16d-validasi-produk.png"><img src="screenshots/16d-validasi-produk.png" width="220"></a> |
| 17 | Hapus produk yang pernah dibeli | Hapus Mouse | Detail pesanan lama tetap menampilkan "Mouse Wireless Silent" (produk dihapus) | <a href="screenshots/17-produk-dihapus-riwayat-tetap.png"><img src="screenshots/17-produk-dihapus-riwayat-tetap.png" width="220"></a> |
| 18 | Hak akses | Customer buka `/admin`; customer lain buka pesanan milik `agil`; admin buka `/checkout` | Diarahkan / 404 / diarahkan ke panel admin | <a href="screenshots/18a-customer-akses-admin.png"><img src="screenshots/18a-customer-akses-admin.png" width="220"></a><br><a href="screenshots/18b-pesanan-orang-lain-404.png"><img src="screenshots/18b-pesanan-orang-lain-404.png" width="220"></a><br><a href="screenshots/18c-admin-akses-checkout.png"><img src="screenshots/18c-admin-akses-checkout.png" width="220"></a> |
| 19 | Visual Studio | Task "Jalankan Aplikasi" | Server berjalan di port 8082 | 📸 *(ambil manual di Visual Studio)* |

---

## 8. Kesimpulan

Toko online berhasil dibangun dengan CodeIgniter 4 dan menunjukkan bagaimana framework memisahkan tanggung jawab: **Controller** tetap tipis, logika keranjang dikemas sebagai **Library** yang didaftarkan sebagai **Service**, dan logika pesanan (transaksi stok, transisi status) berada di **Model**. Bagian paling penting adalah checkout: pengecekan dan pengurangan stok dilakukan dalam satu query `UPDATE … WHERE stok >= qty` di dalam **transaksi database**, sehingga stok tidak pernah minus dan pesanan setengah jadi tidak mungkin tersimpan. **Filter** dengan penyimpanan URL tujuan membuat alur belanja tetap mulus walau pembeli baru login di tengah jalan, sementara **Migration**, **Seeder**, **Config**, dan **Helper** membuat aplikasi mudah dipasang ulang dan dikonfigurasi.

---

## 9. Referensi

- Fajar Baskoro – [PBKK-2 Pengenalan Framework Code Igniter](https://fajarbaskoro.blogspot.com/2019/02/pbkk-2-pengenalan-framework-code-igniter.html)
- [CodeIgniter 4 User Guide](https://codeigniter.com/user_guide/) – Services, Libraries, Database Transactions, Uploaded Files, Validation
- [Bootstrap 5](https://getbootstrap.com/docs/5.3/) · [Start Bootstrap – SB Admin 2](https://startbootstrap.com/theme/sb-admin-2)
