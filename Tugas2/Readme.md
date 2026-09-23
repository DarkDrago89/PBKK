# Laporan Praktikum: Kalkulator Desktop dengan C# WinForms

**Mata Kuliah:** NET Programming dengan C#
**Nama:** Agil Lukman Hakim Muchdi
**NRP/Angkatan:** 5025241037/2024
**Program Studi:** Teknik Informatika, ITS

---

## 1. Deskripsi Singkat

Praktikum ini membuat aplikasi **Kalkulator Desktop** menggunakan **C# Windows Forms App (.NET 8)**, meliputi desain UI, penanganan event tombol, operasi aritmatika dengan `switch`, serta validasi input menggunakan `try-catch`.

## 2. Tampilan Aplikasi

<img width="1920" height="1032" alt="image" src="https://github.com/user-attachments/assets/434caf9a-b57e-430d-a9e5-a7bc6603fd66" />

<img width="342" height="462" alt="image" src="https://github.com/user-attachments/assets/665127aa-05cd-40b7-ad80-fe94396a9dee" />

<br><br><br>

## 3. Hasil Pengujian

| Skenario | Input | Expected | Screenshot |
|---|---|---|---|
| Penjumlahan | `10 + 20 =` | `30` | <img width="342" height="482" alt="image" src="https://github.com/user-attachments/assets/15c4a5a2-b493-4943-8322-dd595833973d" /> |
| Pengurangan | `30 − 12 =` | `18` | <img width="342" height="482" alt="image" src="https://github.com/user-attachments/assets/3b791e3d-5c9b-4a44-83e0-48cf2a354530" /> |
| Perkalian | `6 × 7 =` | `42` | <img width="342" height="482" alt="image" src="https://github.com/user-attachments/assets/57588e4b-1126-46f4-b868-3943efcea9c0" /> |
| Pembagian | `100 ÷ 4 =` | `25` | <img width="342" height="482" alt="image" src="https://github.com/user-attachments/assets/309efc42-593a-46f4-b772-4db566775f6a" /> |
| Desimal | `2.5 × 4 =` | `10` | <img width="342" height="482" alt="image" src="https://github.com/user-attachments/assets/563e50c9-7b9c-4cf7-b06e-52c5d218f9d9" /> |
| Bagi nol | `10 ÷ 0 =` | Pesan error | <img width="1920" height="1032" alt="image" src="https://github.com/user-attachments/assets/0bb88314-7214-4836-b339-39b8372f4634" /> |
| Clear | Tekan `C` | Display `0` | <img width="342" height="482" alt="image" src="https://github.com/user-attachments/assets/d9102afc-ec8d-495c-aaa7-65c4ece336f6" /> |

## 4. Jawaban Refleksi

**1. Apa fungsi `object sender` pada event handler?**

`sender` adalah referensi ke objek yang memicu event tersebut dalam kasus ini, tombol (`Button`) mana yang diklik. Karena tipenya `object` (generik), `sender` perlu di-*cast* dulu ke `Button` (`Button button = (Button)sender;`) sebelum diakses properti spesifiknya seperti `.Text`. Dengan begini, satu method bisa "tahu" tombol mana yang memanggilnya tanpa perlu menulis kode terpisah untuk tiap tombol.

**2. Mengapa semua tombol angka dapat memakai satu `NumberButton_Click`?**

Karena logikanya sama persis untuk semua tombol 0–9: ambil teks tombol yang diklik lalu tambahkan (atau ganti) ke `txtDisplay`. Perbedaan antar tombol hanya pada teks/angka yang ditampilkan, dan itu bisa diambil langsung dari `((Button)sender).Text`. Jadi daripada menulis 10 method identik untuk tiap angka, cukup satu handler yang di-attach ke semua tombol angka lewat properti `Click`. Ini menerapkan prinsip *DRY (Don't Repeat Yourself)*.

**3. Apa perbedaan `firstNumber`, `secondNumber`, dan `result`?**

- `firstNumber` — angka yang diinput sebelum operator ditekan (disimpan saat tombol operator diklik).
- `secondNumber` — angka yang diinput setelah operator, dibaca saat tombol `=` ditekan.
- `result` — hasil akhir dari operasi aritmatika antara `firstNumber` dan `secondNumber`, yang kemudian ditampilkan kembali ke `txtDisplay`.

Ketiganya diperlukan karena kalkulator harus "mengingat" angka pertama dan operator yang dipilih selagi user mengetik angka kedua, sebelum akhirnya menghitung.

**4. Mengapa pembagian dengan nol perlu divalidasi?**

Secara matematis, pembagian dengan nol tidak terdefinisi. Di C#, `double.Parse` lalu operasi `x / 0` untuk tipe `double` sebenarnya tidak melempar exception (hasilnya `Infinity` atau `NaN`), sehingga tanpa validasi eksplisit, aplikasi akan menampilkan nilai yang membingungkan bagi user alih-alih pesan error yang jelas. Karena itu, kode secara sengaja melempar `DivideByZeroException` saat `secondNumber == 0`, agar perilaku aplikasi konsisten dan mudah dipahami user.

**5. Bagaimana `try-catch` membantu menjaga aplikasi tetap stabil?**

`try-catch` mencegah *exception* (seperti `DivideByZeroException` atau `FormatException` dari `double.Parse` jika input tidak valid) membuat aplikasi *crash*. Ketika terjadi error di dalam blok `try`, eksekusi langsung dialihkan ke blok `catch`, di mana pesan error ditampilkan lewat `MessageBox.Show(ex.Message, "Error")`, user diberi tahu apa yang salah, tapi aplikasi tetap berjalan dan bisa dipakai kembali tanpa perlu di-restart.


## 5. Kesimpulan

Praktikum ini berhasil mengimplementasikan kalkulator desktop sederhana menggunakan C# Windows Forms yang mencakup empat operasi aritmatika dasar (+, −, ×, ÷), penanganan input digit dan desimal, serta validasi pembagian dengan nol lewat try-catch agar aplikasi tidak crash. Konsep utama yang dipelajari adalah efisiensi event handler dengan satu method (NumberButton_Click, OperatorButton_Click) dapat melayani banyak tombol berkat parameter object sender, serta pentingnya menyimpan state (firstNumber, operation, secondNumber) di antara input pengguna. Sebagai pengembangan lanjutan, ditambahkan lblExpression untuk menampilkan ekspresi berjalan (mis. 10 +) agar pengalaman pengguna lebih mirip kalkulator fisik, tanpa mengubah logika perhitungan inti.
