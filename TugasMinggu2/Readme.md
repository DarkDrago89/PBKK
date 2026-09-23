# Laporan Tugas PBKK – Sistem Data Mahasiswa (.NET Framework)

**Nama:** Agil Lukman Hakim Muchdi
**NIM:** 5025241037
**Program Studi:** IF
**Mata Kuliah:** PBKK – .NET Framework

---

## 1. Ringkasan Materi: .NET Framework

.NET Framework adalah kerangka kerja perangkat lunak buatan Microsoft yang berjalan utamanya di sistem operasi Windows, dan sejak Windows Server 2003 sudah terintegrasi langsung dalam distribusi standar Windows. Kerangka kerja ini menyediakan pustaka pemrograman yang luas serta mendukung banyak bahasa pemrograman yang dapat saling berinteroperasi satu sama lain dalam satu proyek.

Berbeda dari aplikasi konvensional yang berjalan langsung di atas perangkat keras, program yang dibangun dengan .NET Framework dieksekusi di atas **Common Language Runtime (CLR)** yakni semacam mesin virtual yang menjembatani program dengan perangkat keras. Karena itu, aplikasi .NET secara teoritis dapat berjalan di berbagai perangkat keras selama didukung oleh .NET Framework.

Dua komponen utama .NET Framework adalah:

1. **Common Language Runtime (CLR)** = lingkungan eksekusi yang mengatur kebutuhan runtime program, seperti manajemen memori, garbage collection, exception handling, dan keamanan. CLR merupakan implementasi dari standar **CLI (Common Language Infrastructure)** yang ditetapkan oleh ECMA.
2. **.NET Framework Class Library** = kumpulan pustaka kelas yang mencakup kebutuhan pengembangan seperti user interface, akses data, koneksi basis data, kriptografi, aplikasi web, algoritma numerik, dan komunikasi jaringan.

Dengan adanya CLR dan class library ini, .NET Framework juga sering disebut sebagai sebuah **platform** pengembangan terpadu, yang mempermudah proses pembuatan, distribusi, pengelolaan, dan integrasi aplikasi berbasis Windows dengan sistem jaringan lain. Microsoft mulai mengembangkan .NET Framework pada akhir 1990-an dengan nama awal *Next Generation Windows Services (NGWS)*, dan versi beta pertamanya (.NET 1.0) dirilis pada akhir tahun 2000.

---

## 2. Deskripsi Program

**Sistem Data Mahasiswa** adalah aplikasi console berbasis C#/.NET yang mendemonstrasikan konsep dasar .NET Framework: penggunaan CLR untuk menjalankan kode C#, serta pustaka standar (`System.Collections.Generic`, `System.Globalization`, dll.) dari .NET Class Library.

Program ini mengelola data mahasiswa (NIM, Nama, Program Studi, IPK) secara in-memory selama aplikasi berjalan, melalui menu interaktif berbasis teks dengan tampilan berwarna di terminal.

### Fitur

| No | Fitur | Keterangan |
|----|-------|------------|
| 1 | Tambah mahasiswa | Input NIM, nama, prodi, IPK dengan validasi |
| 2 | Tampilkan semua data | Menampilkan data dalam format tabel |
| 3 | Cari berdasarkan NIM | Pencarian data spesifik |
| 4 | Hapus data mahasiswa | Dengan konfirmasi (y/n) |
| 5 | Keluar | Mengakhiri aplikasi |

### Validasi yang Diterapkan

- Input tidak boleh kosong (`BacaInputWajib`)
- NIM tidak boleh duplikat
- IPK harus berupa angka antara 0.00–4.00 (`BacaIpk`)

---

## 3. Struktur Kode

### a. Model Data berupa `Mahasiswa`

```csharp
internal sealed class Mahasiswa
{
    public string Nim { get; init; } = string.Empty;
    public string Nama { get; init; } = string.Empty;
    public string Prodi { get; init; } = string.Empty;
    public double Ipk { get; init; }
}
```

Kelas ini merepresentasikan satu entitas mahasiswa menggunakan properti `init` agar data bersifat *immutable* setelah objek dibuat.

### b. Penyimpanan Data

```csharp
private static readonly List<Mahasiswa> DaftarMahasiswa = new();
```

Data disimpan sementara di dalam `List<Mahasiswa>` (in-memory), sehingga akan hilang saat aplikasi ditutup sesuai catatan pada README.

### c. Alur Program Utama (`Main`)

Program menggunakan struktur `do-while` dengan `switch` untuk menangani navigasi menu (1–5), dan memanggil method terpisah untuk setiap operasi CRUD:

- `TambahMahasiswa()`
- `TampilkanMahasiswa()`
- `CariMahasiswa()`
- `HapusMahasiswa()`

### d. Fungsi Pendukung

- `BacaInput`, `BacaInputWajib`, `BacaIpk` = menangani pembacaan & validasi input pengguna
- `TampilkanMenu`, `TampilkanJudul`, `TampilkanPesan` = menangani tampilan antarmuka console dengan ANSI escape code untuk warna
- `Potong` = memotong teks yang terlalu panjang agar rapi saat ditampilkan dalam tabel

---

## 4. Cara Menjalankan Aplikasi

Pastikan .NET SDK 8 atau lebih baru telah terpasang, lalu jalankan dari folder proyek:

```bash
dotnet run
```

Untuk build tanpa menjalankan:

```bash
dotnet build
```

---

## 5. Hasil Pengujian (Screenshot)

Berikut alur pengujian yang telah dilakukan terhadap aplikasi:

1. **Tambah Mahasiswa** = Data dengan NIM `5025241037`, Nama `Agil Lukman Hakim Muchdi`, Prodi `IF`, IPK `3.6` berhasil ditambahkan.

<img width="851" height="668" alt="Screenshot From 2026-09-23 14-17-28" src="https://github.com/user-attachments/assets/d08ca81b-4ca8-4773-8ebc-5497d3959b2d" />

2. **Tampilkan Semua Data** = Data yang ditambahkan tampil dalam format tabel dengan kolom NIM, Nama, Program Studi, dan IPK.

<img width="851" height="668" alt="Screenshot From 2026-09-23 14-17-36" src="https://github.com/user-attachments/assets/1f942270-5672-4ade-9677-ee6d54bc1549" />

3. **Cari Berdasarkan NIM** = Pencarian dengan NIM `5025241037` berhasil menemukan data yang sesuai.

<img width="851" height="668" alt="Screenshot From 2026-09-23 14-17-45" src="https://github.com/user-attachments/assets/74a644a4-b564-4ae2-bbbd-eb2386047ac6" />

4. **Hapus Data Mahasiswa** = Data dengan NIM `5025241037` berhasil dihapus setelah konfirmasi `y`.

<img width="851" height="668" alt="Screenshot From 2026-09-23 14-17-55" src="https://github.com/user-attachments/assets/306e2e2a-cc0b-470e-84ca-6622d2c1e096" />

5. **Keluar** = Aplikasi menampilkan pesan penutup dan berhenti berjalan.

<img width="851" height="668" alt="Screenshot From 2026-09-23 14-17-59" src="https://github.com/user-attachments/assets/a17c8876-97f7-406d-bf17-6170e7f07ea0" />


Seluruh alur menu (tambah, tampilkan, cari, hapus, keluar) telah diuji dan berjalan sesuai fungsinya masing-masing.

---

## 6. Kesimpulan

Program **Sistem Data Mahasiswa** ini berhasil mengimplementasikan konsep dasar .NET Framework melalui aplikasi console sederhana. Program memanfaatkan CLR untuk mengeksekusi kode C# serta memanfaatkan pustaka bawaan .NET Class Library (`System`, `System.Collections.Generic`, `System.Globalization`) untuk pengelolaan data, validasi input, dan format tampilan. Struktur kode yang modular (pemisahan model data dan method per-operasi menu) sejalan dengan prinsip pengembangan aplikasi yang mudah dikelola sebagaimana ditekankan dalam konsep .NET sebagai *platform* pengembangan terpadu.

---

## 7. Referensi

- Baskoro, Fajar. *PBKK-2-NET Framework*. [fajarbaskoro.blogspot.com](https://fajarbaskoro.blogspot.com/2021/03/net-framework.html)
