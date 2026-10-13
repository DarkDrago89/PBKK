# Laporan Praktikum: Student Management System dengan C# WPF (MVVM + SQL Server)

- **Mata Kuliah:** NET Programming dengan C#
- **Nama:** Agil Lukman Hakim Muchdi
- **NRP/Angkatan:** 5025241037/2024
- **Program Studi:** Teknik Informatika, ITS

---

## 1. Deskripsi Singkat

Praktikum ini membuat aplikasi **Student Management System** menggunakan **C# WPF (.NET 8)** dengan arsitektur **MVVM**, meliputi desain Rich UI dengan XAML, **Data Binding**, **Command** (`ICommand`), akses database **SQL Server LocalDB** menggunakan **ADO.NET** (`Microsoft.Data.SqlClient`), serta fitur CRUD, pencarian, dan statistik mahasiswa.

Alur arsitektur: `View (XAML) → Data Binding → ViewModel → Model (Student) → Repository (ADO.NET) → SQL Server (StudentDB)`.

## 2. Tampilan Aplikasi

> <img width="2560" height="1380" alt="image" src="https://github.com/user-attachments/assets/6d74afef-6286-47dd-878a-42bc6c373283" />

<br>

> <img width="2560" height="1380" alt="image" src="https://github.com/user-attachments/assets/73725b88-830d-4cdb-a9ca-5330616d966e" />

<br>

> <img width="2560" height="1380" alt="image" src="https://github.com/user-attachments/assets/2584b6e7-dd88-47d7-ac36-61076719634f" />

<br>

<br><br><br>

## 3. Hasil Pengujian

| Skenario | Input | Expected | Screenshot |
|---|---|---|---|
| READ | Jalankan aplikasi | 4 data sample muncul di DataGrid; Informasi: Total 4, Informatika 2, Sistem Informasi 2, Laki-laki 2, Perempuan 2 | 📸 *(tempel)* |
| CREATE | NIM `23005`, Nama `Dewi Lestari`, Jurusan `Informatika`, Gender `Perempuan`, Email `dewi@gmail.com`, klik **Simpan** | Data baru muncul di baris paling atas; form kembali kosong; Total 5 | 📸 *(tempel)* |
| UPDATE | Klik **Edit** pada Dewi, ubah Jurusan menjadi `Sistem Informasi`, klik **Simpan** | Perubahan tersimpan di tabel; Informatika 2, Sistem Informasi 3 | 📸 *(tempel)* |
| DELETE | Klik **Delete** pada Dewi, pilih **Yes** pada konfirmasi | Data terhapus; tabel dan statistik kembali ke Total 4 | 📸 *(tempel)* |
| SEARCH (Nama) | Ketik `Budi`, klik **Cari** (atau Enter) | Hanya data Budi Santoso yang tampil | 📸 *(tempel)* |
| SEARCH (Jurusan) | Ketik `Sistem`, klik **Cari** | Hanya Siti Aminah dan Rina Sari yang tampil | 📸 *(tempel)* |
| STATISTICS | Lakukan pencarian `Budi`, lihat panel Informasi | Angka statistik tetap menghitung seluruh data (Total 4), tidak ikut terfilter | 📸 *(tempel)* |
| RESET | Klik **Edit** pada salah satu baris, lalu klik **Reset** | Form kosong, judul kembali "INPUT MAHASISWA", pilihan baris terlepas | 📸 *(tempel)* |
| Validasi | Kosongkan NIM, klik **Simpan** | Muncul pesan "NIM wajib diisi." dan data tidak tersimpan | 📸 *(tempel)* |

## 4. Jawaban Refleksi

**1. Apa tugas masing-masing lapisan pada arsitektur MVVM di aplikasi ini?**

- **View** (`MainWindow.xaml`) hanya mengatur tampilan: form, panel informasi, kolom pencarian, dan DataGrid.
- **ViewModel** (`StudentViewModel`) menyimpan state dan logika UI: data yang tampil, baris terpilih, teks pencarian, statistik, serta command (Simpan, Reset, Cari, Edit, Delete).
- **Model** (`Student`) merepresentasikan satu data mahasiswa.
- **Repository** (`StudentRepository`) satu-satunya lapisan yang berbicara dengan SQL Server lewat ADO.NET.

Dengan pemisahan ini, tampilan bisa diubah tanpa menyentuh logika, dan logika tidak bergantung pada kontrol UI tertentu.

**2. Bagaimana Data Binding, `INotifyPropertyChanged`, dan `ObservableCollection` bekerja bersama?**

Data Binding menghubungkan properti ViewModel dengan kontrol di XAML (misalnya `Text="{Binding Form.Nama}"`). Agar tampilan ikut berubah ketika nilai di ViewModel berubah, ViewModel memanggil `PropertyChanged` lewat `OnPropertyChanged()`, contohnya pada `RefreshStatistics()` setelah data dimuat sehingga angka di panel Informasi ikut diperbarui. Untuk daftar data, `ObservableCollection<Student>` otomatis memberi tahu DataGrid ketika ada item yang ditambah atau dihapus, sedangkan `List<T>` biasa tidak melakukannya.

**3. Apa perbedaan Command pada MVVM dengan event `Click` pada WinForms?**

Pada WinForms, tombol memicu event `Click` yang ditangani di *code-behind*, sehingga logika dekat dengan tampilan. Pada WPF + MVVM, tombol diikat ke `ICommand` di ViewModel (`Command="{Binding SaveCommand}"`). `RelayCommand` membungkus sebuah method agar bisa dipanggil lewat binding, jadi `MainWindow.xaml.cs` tidak berisi logika aplikasi. Tombol Edit dan Delete di DataGrid memakai `CommandParameter="{Binding}"` untuk mengirim objek `Student` pada baris yang diklik.

**4. Mengapa akses database dipisahkan ke `StudentRepository` dan memakai parameter SQL (`@NIM`, `@Keyword`, dst.)?**

Memisahkan Repository membuat ViewModel tidak perlu tahu detail SQL, sehingga database bisa diganti (misalnya ke Entity Framework) tanpa mengubah ViewModel. Parameter SQL mencegah **SQL Injection**: input pengguna dikirim sebagai nilai, bukan digabung ke string perintah SQL. Jika NIM atau kata kunci pencarian dirangkai langsung ke query, input berbahaya seperti `'; DROP TABLE Students; --` bisa dieksekusi sebagai perintah.

**5. Bagaimana alur data saat tombol Simpan ditekan, dan mengapa form mengedit salinan (`Clone`) data?**

Alurnya: tombol Simpan → `SaveCommand` → `Save()` di ViewModel → validasi → `Insert` (jika `Id == 0`) atau `Update` → `LoadData()` memuat ulang dari SQL Server → `Reset()` mengosongkan form. Saat baris DataGrid dipilih, form diisi dengan salinan data (`Clone()`), bukan objek aslinya. Jika form terikat langsung ke objek di DataGrid, setiap ketikan langsung mengubah tampilan tabel padahal belum disimpan, dan perubahan tidak bisa dibatalkan dengan Reset.

## 5. Kesimpulan

Praktikum ini berhasil mengimplementasikan Student Management System berbasis WPF dengan arsitektur MVVM yang mencakup CRUD, pencarian berdasarkan NIM/Nama/Jurusan, dan statistik mahasiswa yang tersambung ke SQL Server LocalDB melalui ADO.NET. Konsep utama yang dipelajari adalah Data Binding dua arah, `ICommand` lewat `RelayCommand`, serta `ObservableCollection` dan `INotifyPropertyChanged` agar UI selalu sinkron dengan data, ditambah pemisahan tanggung jawab antara View, ViewModel, Model, dan Repository. Sebagai pengembangan di luar studi kasus awal, ditambahkan validasi input, konfirmasi sebelum hapus, penanganan error database, pembuatan database otomatis saat aplikasi pertama dijalankan, dan statistik yang dihitung dari seluruh data agar tidak berubah saat pencarian.

---

## Lampiran: Struktur Project dan Cara Menjalankan

```
StudentManager/                      <- folder solution
├── StudentManager.sln
├── Database/StudentDB.sql           (opsional, script manual)
└── StudentManager/                  <- folder project
    ├── StudentManager.csproj
    ├── App.xaml / App.xaml.cs       (menyiapkan database saat startup)
    ├── MainWindow.xaml / .xaml.cs   (View + DataContext)
    ├── Models/Student.cs
    ├── ViewModels/
    │   ├── StudentViewModel.cs
    │   └── RelayCommand.cs
    └── Data/
        ├── DbConfig.cs              (connection string)
        ├── DatabaseInitializer.cs   (auto-create DB + sample data)
        └── StudentRepository.cs     (ADO.NET: GetAll, Search, Insert, Update, Delete)
```

**Cara menjalankan di Visual Studio:**

1. Buka `StudentManager.sln` dengan **Visual Studio 2022** (workload **.NET desktop development**, SDK **.NET 8**).
2. Pastikan komponen **SQL Server Express LocalDB** terpasang (Visual Studio Installer > Individual components). Cek di Command Prompt dengan `sqllocaldb info`, harus muncul `MSSQLLocalDB`.
   Jika memakai SQL Server lain, ganti `Server` di `Data/DbConfig.cs` (misalnya `.\SQLEXPRESS` atau `localhost`).
3. Tunggu NuGet restore (`Microsoft.Data.SqlClient`) selesai, lalu tekan **F5**.
4. Database `StudentDB`, tabel `Students`, dan 4 data sample dibuat otomatis saat aplikasi pertama dijalankan.
