# Laporan Praktikum: Kalkulator Desktop dengan C# WinForms

**Mata Kuliah:** NET Programming dengan C#
**Nama:** Agil
**NRP/Angkatan:** 2024
**Program Studi:** Teknik Informatika, ITS

---

## 1. Deskripsi Singkat

Praktikum ini membuat aplikasi **Kalkulator Desktop** menggunakan **C# Windows Forms App (.NET 8)**, meliputi desain UI, penanganan event tombol, operasi aritmatika dengan `switch`, serta validasi input menggunakan `try-catch`.

## 2. Tampilan Aplikasi

<!-- screenshot form kalkulator di sini -->
<!-- Contoh: ![Tampilan Kalkulator](screenshots/tampilan-kalkulator.png) -->

<br><br><br>

## 3. Hasil Pengujian

<!-- screenshot hasil uji tiap skenario di sini -->

| Skenario | Input | Expected | Screenshot |
|---|---|---|---|
| Penjumlahan | `10 + 20 =` | `30` | <!-- ![penjumlahan](screenshots/penjumlahan.png) --> |
| Pengurangan | `30 − 12 =` | `18` | <!-- ![pengurangan](screenshots/pengurangan.png) --> |
| Perkalian | `6 × 7 =` | `42` | <!-- ![perkalian](screenshots/perkalian.png) --> |
| Pembagian | `100 ÷ 4 =` | `25` | <!-- ![pembagian](screenshots/pembagian.png) --> |
| Desimal | `2.5 × 4 =` | `10` | <!-- ![desimal](screenshots/desimal.png) --> |
| Bagi nol | `10 ÷ 0 =` | Pesan error | <!-- ![bagi-nol](screenshots/bagi-nol.png) --> |
| Clear | Tekan `C` | Display `0` | <!-- ![clear](screenshots/clear.png) --> |

## 4. Jawaban Refleksi

**1. Apa fungsi `object sender` pada event handler?**

`sender` adalah referensi ke objek yang memicu event tersebut — dalam kasus ini, tombol (`Button`) mana yang diklik. Karena tipenya `object` (generik), `sender` perlu di-*cast* dulu ke `Button` (`Button button = (Button)sender;`) sebelum diakses properti spesifiknya seperti `.Text`. Dengan begini, satu method bisa "tahu" tombol mana yang memanggilnya tanpa perlu menulis kode terpisah untuk tiap tombol.

**2. Mengapa semua tombol angka dapat memakai satu `NumberButton_Click`?**

Karena logikanya sama persis untuk semua tombol 0–9: ambil teks tombol yang diklik lalu tambahkan (atau ganti) ke `txtDisplay`. Perbedaan antar tombol hanya pada teks/angka yang ditampilkan, dan itu bisa diambil langsung dari `((Button)sender).Text`. Jadi daripada menulis 10 method identik untuk tiap angka, cukup satu handler yang di-attach ke semua tombol angka lewat properti `Click` — ini menerapkan prinsip *DRY (Don't Repeat Yourself)*.

**3. Apa perbedaan `firstNumber`, `secondNumber`, dan `result`?**

- `firstNumber` — angka yang diinput sebelum operator ditekan (disimpan saat tombol operator diklik).
- `secondNumber` — angka yang diinput setelah operator, dibaca saat tombol `=` ditekan.
- `result` — hasil akhir dari operasi aritmatika antara `firstNumber` dan `secondNumber`, yang kemudian ditampilkan kembali ke `txtDisplay`.

Ketiganya diperlukan karena kalkulator harus "mengingat" angka pertama dan operator yang dipilih selagi user mengetik angka kedua, sebelum akhirnya menghitung.

**4. Mengapa pembagian dengan nol perlu divalidasi?**

Secara matematis, pembagian dengan nol tidak terdefinisi. Di C#, `double.Parse` lalu operasi `x / 0` untuk tipe `double` sebenarnya tidak melempar exception (hasilnya `Infinity` atau `NaN`), sehingga tanpa validasi eksplisit, aplikasi akan menampilkan nilai yang membingungkan bagi user alih-alih pesan error yang jelas. Karena itu, kode secara sengaja melempar `DivideByZeroException` saat `secondNumber == 0`, agar perilaku aplikasi konsisten dan mudah dipahami user.

**5. Bagaimana `try-catch` membantu menjaga aplikasi tetap stabil?**

`try-catch` mencegah *exception* (seperti `DivideByZeroException` atau `FormatException` dari `double.Parse` jika input tidak valid) membuat aplikasi *crash*. Ketika terjadi error di dalam blok `try`, eksekusi langsung dialihkan ke blok `catch`, di mana pesan error ditampilkan lewat `MessageBox.Show(ex.Message, "Error")` — user diberi tahu apa yang salah, tapi aplikasi tetap berjalan dan bisa dipakai kembali tanpa perlu di-restart.


## 5. Kesimpulan

<!-- Kesimpulan singkat di sini -->
