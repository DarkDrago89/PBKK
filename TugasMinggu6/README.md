# Laporan Hands-On Lab 2 – Aplikasi WPF + C# Terhubung SQL Server dengan ADO.NET

**Nama:** Agil Lukman Hakim Muchdi
**NIM:** 5025241037
**Program Studi:** IF
**Mata Kuliah:** .NET Programming with C#
**Studi Kasus:** Student Registration App

---

## 1. Tujuan Praktikum

Setelah menyelesaikan Hands-On Lab 2, mahasiswa mampu:

- Membuat project WPF menggunakan C#.
- Menghubungkan aplikasi WPF dengan SQL Server.
- Menggunakan ADO.NET (`SqlConnection`, `SqlCommand`, `SqlDataReader`) untuk akses database.
- Membuat Model `Student` dan `Program`.
- Membuat `StudentRepository` sebagai lapisan akses data.
- Menampilkan data mahasiswa pada `DataGrid`.

---

## 2. Ringkasan Materi

**WPF (Windows Presentation Foundation)** adalah framework UI dari .NET untuk membangun aplikasi desktop Windows. Tampilan didefinisikan dengan **XAML**, sedangkan logika ditulis dengan C# pada *code-behind*.

**ADO.NET** adalah teknologi akses data pada .NET. Tiga class utama yang dipakai pada lab ini:

| Class | Fungsi |
|-------|--------|
| `SqlConnection` | Membuka koneksi ke SQL Server |
| `SqlCommand` | Menjalankan perintah SQL |
| `SqlDataReader` | Membaca hasil query baris demi baris |

**Repository Pattern** memisahkan kode akses data dari UI, sehingga `MainWindow` tidak perlu mengetahui detail query SQL.

---

## 3. Deskripsi Program

**Student Registration App** adalah aplikasi desktop WPF yang mengambil data mahasiswa dari database `StudentRegistrationDB` di SQL Server, lalu menampilkannya dalam `DataGrid`.

### Fitur

| No | Fitur | Keterangan |
|----|-------|------------|
| 1 | Tampil data mahasiswa | Data dari tabel `Students` di-JOIN dengan `Programs` |
| 2 | Tombol Refresh | Mengambil ulang data dari database |
| 3 | Error handling | Menampilkan `MessageBox` jika koneksi gagal |
| 4 | Kolom Alamat *(Challenge 1)* | Menampilkan `Address` |
| 5 | Kolom Dibuat *(Challenge 2)* | Menampilkan `CreatedAt` |
| 6 | Total Mahasiswa *(Challenge 3)* | Menggunakan `SELECT COUNT(*) FROM Students` |

---

## 4. Arsitektur dan Struktur Project

```
┌───────────────────────┐
│      MainWindow       │
│        WPF UI         │
└───────────┬───────────┘
            ▼
┌───────────────────────┐
│   StudentRepository   │
│      Data Access      │
└───────────┬───────────┘
            ▼
┌───────────────────────┐
│        ADO.NET        │
└───────────┬───────────┘
            ▼
┌───────────────────────┐
│      SQL Server       │
│ StudentRegistrationDB │
└───────────────────────┘
```

```
StudentRegistration
├── Database
│   └── StudentRegistrationDB.sql
├── Models
│   ├── Student.cs
│   └── Program.cs
├── Repositories
│   └── StudentRepository.cs
├── screenshots
├── App.xaml
├── App.xaml.cs
├── MainWindow.xaml
├── MainWindow.xaml.cs
└── StudentRegistration.csproj
```

| File | Tanggung Jawab |
|------|----------------|
| `Student.cs` | Model data mahasiswa |
| `Program.cs` | Model program studi (bukan entry point) |
| `StudentRepository.cs` | Data access ke SQL Server |
| `MainWindow.xaml` | User interface WPF |
| `MainWindow.xaml.cs` | Event dan UI logic |

---

## 5. Alur Data: dari SQL Server ke DataGrid

1. Aplikasi WPF dijalankan.
2. `MainWindow` membuat objek `StudentRepository`.
3. Repository membuat `SqlConnection` menggunakan connection string.
4. `SqlCommand` menjalankan query `SELECT` (JOIN `Students` dan `Programs`).
5. `SqlDataReader` membaca hasil dari SQL Server.
6. Setiap baris dipetakan menjadi objek `Student`.
7. Objek dimasukkan ke `List<Student>`.
8. `List<Student>` di-set sebagai `ItemsSource` pada `DataGrid`.
9. `DataGrid` menampilkan data melalui data binding.

### Potongan Kode Penting

**Connection string** (`StudentRepository.cs`):

```csharp
private readonly string _connectionString =
    @"Server=.\SQLEXPRESS;Database=StudentRegistrationDB;Trusted_Connection=True;TrustServerCertificate=True;";
```

**Membaca data dengan ADO.NET:**

```csharp
using var connection = new SqlConnection(_connectionString);
using var command = new SqlCommand(sql, connection);
connection.Open();
using var reader = command.ExecuteReader();

while (reader.Read())
{
    students.Add(new Student { /* mapping kolom ke property */ });
}
```

**Menghubungkan ke DataGrid** (`MainWindow.xaml.cs`):

```csharp
var students = _studentRepository.GetAll();
StudentDataGrid.ItemsSource = students;
```

---

## 6. Cara Menjalankan Aplikasi

### Prasyarat

- Visual Studio 2022 dengan workload **.NET desktop development**
- .NET 8 SDK
- SQL Server / SQL Server Express + SSMS

### Langkah

1. **Siapkan database.** Buka SSMS, lalu jalankan `Database/StudentRegistrationDB.sql` (lewati jika database dari Lab 1 sudah ada).
2. **Buka project.** Buka `StudentRegistration.csproj` di Visual Studio.
3. **Restore package.** Package `Microsoft.Data.SqlClient` akan ter-restore otomatis (atau install via *Tools → NuGet Package Manager*).
4. **Sesuaikan connection string** di `Repositories/StudentRepository.cs` jika nama instance berbeda (contoh: `(localdb)\MSSQLLocalDB`).
5. **Jalankan** dengan menekan **F5**.

### Troubleshooting (jika DataGrid kosong)

1. Jalankan `SELECT * FROM Students` di SSMS dan pastikan ada data.
2. Pastikan `Server` pada connection string sesuai instance SQL Server.
3. Pastikan nama database `StudentRegistrationDB`.
4. Pastikan package `Microsoft.Data.SqlClient` terinstal.
5. Jalankan query JOIN langsung di SSMS.

---

## 7. Hasil Pengujian (Screenshot)

> **Cara memasukkan screenshot:** simpan gambar di folder `screenshots/` lalu ganti nama file pada baris `![...](...)` di bawah. Jika memakai GitHub, kamu juga bisa drag & drop gambar langsung ke editor README di GitHub.

### 7.1 Database di SSMS

Hasil `SELECT * FROM Students` di SQL Server Management Studio.

![Database SSMS](screenshots/01-ssms-students.png)

### 7.2 Struktur Project di Visual Studio

Solution Explorer menampilkan folder `Models`, `Repositories`, dan file utama.

![Solution Explorer](screenshots/02-solution-explorer.png)

### 7.3 Package Microsoft.Data.SqlClient Terinstal

![NuGet Package](screenshots/03-nuget-sqlclient.png)

### 7.4 Aplikasi Berjalan – Data Tampil di DataGrid

Data mahasiswa dari SQL Server berhasil tampil pada `DataGrid`.

![DataGrid](screenshots/04-datagrid.png)

### 7.5 Tombol Refresh

Klik **Refresh** untuk mengambil ulang data dari database.

![Refresh](screenshots/05-refresh.png)

### 7.6 Mini Challenge (Alamat, Dibuat, Total Mahasiswa)

![Mini Challenge](screenshots/06-mini-challenge.png)

### 7.7 *(Opsional)* Error Handling

Pesan error ketika koneksi database gagal (misal nama server salah).

![Error Handling](screenshots/07-error-handling.png)

---

## 8. Checkpoint

**Database**
- [x] StudentRegistrationDB
- [x] Programs
- [x] Students

**C#**
- [x] Student.cs
- [x] Program.cs
- [x] StudentRepository.cs

**ADO.NET**
- [x] Microsoft.Data.SqlClient
- [x] SqlConnection
- [x] SqlCommand
- [x] SqlDataReader

**WPF**
- [x] MainWindow.xaml
- [x] DataGrid
- [x] Refresh Button
- [x] Data berhasil ditampilkan

---

## 9. Kesimpulan

Aplikasi **Student Registration** berhasil menghubungkan WPF dengan SQL Server menggunakan ADO.NET. Data dari tabel `Students` dan `Programs` diambil melalui `SqlConnection`, `SqlCommand`, dan `SqlDataReader`, dipetakan ke objek `Student`, lalu ditampilkan pada `DataGrid` melalui `ItemsSource` dan data binding. Pemisahan tanggung jawab antara Model, Repository, dan UI membuat kode lebih terstruktur dan siap dikembangkan ke tahap selanjutnya (CRUD, MVVM, dan UI profesional).

---

## 10. Referensi

- Modul Hands-On Lab 2 – *Membuat Aplikasi WPF + C# Terhubung SQL Server dengan ADO.NET*, Mata Kuliah .NET Programming with C#.
- Microsoft Docs – [ADO.NET Overview](https://learn.microsoft.com/dotnet/framework/data/adonet/ado-net-overview)
- Microsoft Docs – [WPF Documentation](https://learn.microsoft.com/dotnet/desktop/wpf/)
