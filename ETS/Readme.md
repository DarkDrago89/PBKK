# Laporan Personal ETS: Aplikasi KasKita

- **Mata Kuliah:** Pemrograman Berbasis Kerangka Kerja (PBKK)
- **Nama:** Agil Lukman Hakim Muchdi
- **NRP/Angkatan:** 5025241037/2024
- **Program Studi:** Teknik Informatika, Institut Teknologi Sepuluh Nopember
- **Nama Proyek:** KasKita
- **Repository GitHub Kelompok:** [https://github.com/Miko-a/KasKita.git](https://github.com/Miko-a/KasKita.git)

---

## 1. Deskripsi Singkat

KasKita adalah aplikasi desktop untuk membantu pengurus organisasi mahasiswa mengelola keuangan organisasi. Aplikasi ini dibuat menggunakan **C#**, **WPF**, dan **.NET 8** dengan pola **MVVM**. Data aplikasi disimpan secara lokal menggunakan **SQLite** melalui **Entity Framework Core**.

Fitur utama KasKita mencakup pencatatan transaksi masuk dan keluar, pengelolaan iuran anggota, pengajuan dan persetujuan anggaran kegiatan, dashboard keuangan, serta pembuatan laporan bulanan yang dapat diekspor ke PDF dan Excel. Aplikasi menyediakan tiga peran, yaitu Bendahara, Ketua, dan Anggota, dengan akses yang menyesuaikan peran masing-masing.

Alur umum aplikasi: `View (WPF/XAML) → ViewModel → Model → AppDbContext (EF Core) → SQLite`.

## 2. Kontribusi Personal

- **Tanggung jawab dalam kelompok:** 
- **Implementasi yang saya kerjakan:** 
- **Kendala dan penyelesaian:** 
- **Kolaborasi:**

## 3. Tampilan Aplikasi

### Login

<img width="533" height="742" alt="image" src="https://github.com/user-attachments/assets/7be947ca-6e96-465a-9149-cc02550b606f" />

### Dashboard

<img width="1633" height="967" alt="image" src="https://github.com/user-attachments/assets/283c5e64-9647-448c-9cb6-378c9259b4b2" />

> Dashboard Bendahara

<img width="1633" height="967" alt="image" src="https://github.com/user-attachments/assets/36ab43a7-eae7-441c-bb11-c3dc77d585d0" />

> Dashboard Ketua

<img width="1605" height="967" alt="image" src="https://github.com/user-attachments/assets/6629bbc9-ada0-4003-b28d-a186d05f06ce" />

> Dashboard Anggota

### Transaksi dan Iuran

<img width="1633" height="967" alt="image" src="https://github.com/user-attachments/assets/c78a26a2-a6d9-44d0-ade7-eba79ec414d7" />

> Tab Iuran sebagai Bendahara

<img width="1633" height="967" alt="image" src="https://github.com/user-attachments/assets/39c1162c-133f-41f1-83c3-c1f4ad87c43e" />

> Tab Transaksi sebagai Bendahara

### Anggaran dan Laporan

<img width="1633" height="967" alt="image" src="https://github.com/user-attachments/assets/8579d217-bda5-4f69-b466-e310b2169653" />

> Tab Anggaran Kegiatan sebagai Bendahara

<img width="1633" height="967" alt="image" src="https://github.com/user-attachments/assets/4670bded-ea0b-4224-ac62-0f4aa9814fa2" />

> Tab Laporan sebagai Bendahara

## 4. Hasil Pengujian

Pengujian berikut dapat dilakukan melalui akun contoh yang dibuat otomatis saat aplikasi pertama kali dijalankan. Gunakan akun dengan peran yang sesuai untuk memeriksa hak akses Bendahara, Ketua, dan Anggota.

| Skenario | Langkah/Input | Hasil yang diharapkan | Bukti |
|---|---|---|---|
| Login | Masuk menggunakan akun contoh untuk salah satu peran | Pengguna berhasil masuk dan tampilan aplikasi menyesuaikan perannya | <img width="1632" height="967" alt="image" src="https://github.com/user-attachments/assets/2adcb389-2954-4f72-94dd-d7efab90d364" /> |
| Dashboard | Buka halaman Dashboard | Ringkasan dan visualisasi kondisi keuangan ditampilkan | <img width="1633" height="967" alt="image" src="https://github.com/user-attachments/assets/da212d5e-18f8-4dc1-9f26-4bea287ab385" /> |
| Tambah transaksi | Sebagai Bendahara, isi tanggal, jenis kas, kategori, nominal, serta keterangan; simpan | Transaksi tersimpan dan ditampilkan pada daftar transaksi | <img width="1632" height="967" alt="image" src="https://github.com/user-attachments/assets/783e4af6-39e3-40a7-a54a-ebb9bd19a249" /> |
| Persetujuan transaksi | Tambahkan transaksi pengeluaran dengan nominal minimal Rp500.000, lalu tinjau sebagai Ketua | Transaksi berstatus menunggu persetujuan dan dapat disetujui atau ditolak oleh Ketua | <img width="1632" height="967" alt="image" src="https://github.com/user-attachments/assets/6becde6c-9d26-4140-8a75-567af682c045" /> |
| Iuran anggota | Buka halaman Iuran dan tinjau status pembayaran berdasarkan anggota dan periode | Status iuran, seperti lunas atau menunggak, ditampilkan | <img width="1633" height="967" alt="image" src="https://github.com/user-attachments/assets/bd4657c2-1511-4ca1-9f55-ddc81374653c" /> |
| Anggaran kegiatan | Ajukan anggaran kegiatan sebagai Bendahara, lalu tinjau sebagai Ketua | Pengajuan anggaran dan status persetujuannya dapat dilihat sesuai peran (sebagai ketua) | <img width="1633" height="967" alt="image" src="https://github.com/user-attachments/assets/8b7a6568-36b7-4cd7-850d-50cbb4b0104b" /> |
| Laporan bulanan | Pilih bulan dan tahun pada halaman laporan | Saldo awal, total pemasukan, total pengeluaran, saldo akhir, dan mutasi bulan tersebut ditampilkan | <img width="1633" height="967" alt="image" src="https://github.com/user-attachments/assets/d9b7628d-ed02-4471-a880-42b1dc41833b" /> |
| Ekspor laporan | Ekspor laporan yang sedang dibuka ke PDF dan Excel | Berkas laporan berhasil dibuat dan memuat ringkasan serta daftar mutasi | <img width="2560" height="1380" alt="image" src="https://github.com/user-attachments/assets/624eb63a-4a01-4925-8bad-d966b2850a01" /> |
| Pembatasan peran | Masuk sebagai Anggota dan buka fitur pengelolaan | Aksi yang hanya untuk Bendahara atau Ketua tidak tersedia bagi Anggota (tidak bisa karena tidak ada tab nya) | <img width="1633" height="967" alt="image" src="https://github.com/user-attachments/assets/bf6be586-9578-4bd1-9ea4-dd0849a65f80" /> |

## 5. Jawaban Refleksi

**1. Apa tugas masing-masing bagian pada arsitektur MVVM di KasKita?**

- **View** (`Views`) menampilkan halaman dan komponen antarmuka menggunakan WPF/XAML.
- **ViewModel** (`ViewModels`) menghubungkan tampilan dengan operasi aplikasi, mengelola state halaman, dan menyediakan perintah menggunakan CommunityToolkit.Mvvm.
- **Model** (`Models`) merepresentasikan entitas domain seperti transaksi, anggota, iuran, kegiatan, dan pengguna.
- **Data** (`Data`) mengatur konteks Entity Framework Core dan inisialisasi basis data.
- **Services** (`Services`) menangani kebutuhan lintas halaman, seperti autentikasi, laporan, pencatatan aktivitas, notifikasi, serta pencadangan data.

Pemisahan ini membuat logika aplikasi tidak perlu ditempatkan langsung di tampilan dan membantu tiap bagian dikembangkan dengan tanggung jawab yang lebih jelas.

**2. Bagaimana aplikasi berinteraksi dengan basis data?**

KasKita menggunakan SQLite sebagai penyimpanan lokal dan Entity Framework Core sebagai ORM. `AppDbContext` menyediakan akses ke entitas, sedangkan ViewModel dan layanan menggunakan konteks tersebut untuk membaca atau mengubah data. Saat pertama kali dijalankan, aplikasi menginisialisasi basis data dan menambahkan data contoh apabila belum ada pengguna.

**3. Bagaimana pembagian akses pengguna diterapkan?**

Aplikasi menyediakan peran Bendahara, Ketua, dan Anggota. Peran menentukan fitur dan tindakan yang dapat dilakukan. Sebagai contoh, Bendahara mengelola transaksi dan data keuangan, sedangkan Ketua dapat meninjau pengajuan yang memerlukan persetujuan. Anggota menggunakan aplikasi untuk melihat informasi yang berkaitan dengan keanggotaan dan status iuran.

**4. Bagaimana mekanisme persetujuan transaksi bekerja?**

Transaksi pengeluaran dengan nominal minimal **Rp500.000** disimpan dengan status menunggu persetujuan. Ketua dapat meninjau pengajuan tersebut dan memutuskan untuk menyetujui atau menolaknya. Status persetujuan menjadi bagian dari pengelolaan transaksi dan dasar untuk menampilkan data keuangan yang telah disetujui.

**5. Bagaimana laporan bulanan dihitung dan diekspor?**

Laporan bulanan menghitung saldo awal dari transaksi yang disetujui sebelum bulan terpilih, lalu menghitung total pemasukan dan pengeluaran serta daftar mutasi pada bulan tersebut. Saldo akhir dihitung dari saldo awal ditambah pemasukan dan dikurangi pengeluaran. Hasil laporan dapat diekspor ke PDF menggunakan QuestPDF dan ke Excel menggunakan ClosedXML.

## 6. Kesimpulan

KasKita menggabungkan pencatatan transaksi, pemantauan iuran, pengelolaan anggaran, persetujuan, dan pelaporan keuangan organisasi dalam satu aplikasi desktop. Melalui proyek ini, saya mempelajari penerapan WPF dan pola MVVM, penggunaan Entity Framework Core dengan SQLite, pemisahan tanggung jawab antarlapisan, serta penerapan fitur berdasarkan peran pengguna. Proyek ini juga menunjukkan bagaimana data transaksi dapat dirangkum menjadi laporan bulanan dan disediakan dalam format PDF maupun Excel.

---

## Lampiran: Struktur Proyek dan Cara Menjalankan

```text
KasKita/
├── KasKita.sln
├── README.md
└── KasKita/
    ├── KasKita.csproj
    ├── App.xaml
    ├── Data/          (konteks dan inisialisasi basis data)
    ├── Models/        (entitas aplikasi)
    ├── Resources/     (resource dan style)
    ├── Services/      (autentikasi, laporan, backup, dan layanan lain)
    ├── ViewModels/    (state dan logika presentasi)
    └── Views/         (halaman dan tampilan WPF)
```

**Persyaratan:**

- Windows 10 atau Windows 11.
- .NET 8 SDK.
- Visual Studio 2022 dengan workload **.NET desktop development**, atau .NET CLI.

**Cara menjalankan melalui terminal PowerShell dari folder yang berisi `KasKita.sln`:**

```powershell
dotnet restore .\KasKita\KasKita.csproj
dotnet run --project .\KasKita\KasKita.csproj
```

Pada saat dijalankan pertama kali, aplikasi membuat basis data dan data contoh jika belum ada pengguna. Berkas SQLite disimpan di `%LOCALAPPDATA%\KasKita\kaskita.db`, sedangkan bukti transaksi yang disalin aplikasi disimpan pada subfolder `Bukti` di direktori data KasKita.

### Akun Data Contoh

| Peran | Username | Password |
|---|---|---|
| Bendahara | `bendahara` | `bendahara123` |
| Ketua | `ketua` | `ketua123` |
| Anggota | `anggota` | `anggota123` |
