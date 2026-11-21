# Laporan Tugas Minggu 10: Fungsi Router dan Controller

- **Mata Kuliah:** Pemrograman Berbasis Kerangka Kerja (PBKK)
- **Nama:** Agil Lukman Hakim Muchdi
- **NRP/Angkatan:** 5025241037/2024
- **Program Studi:** Teknik Informatika, Institut Teknologi Sepuluh Nopember
- **Materi:** PBKK-2-2 Fungsi Router dan Controller
- **Referensi contoh:** [Petani Kode – CodeIgniter MVC (CI 3)](https://www.petanikode.com/codeigniter-mvc/) · [Petani Kode – Controller CodeIgniter 4](https://www.petanikode.com/codeigniter4-controller/)

---

## 1. Deskripsi Singkat

Latihan pada materi:

1. *Buatlah controller dan router baru yang memanggil method `contact` dan `about` (CI 3).*
2. *Membuat Router dan Controller di CI 4.*

Keduanya dikerjakan sebagai dua project terpisah bertema **Toko Buah** (sesuai contoh URL `localhost/tokobuah` di modul):

| Folder | Framework | Port | Isi |
|---|---|---|---|
| [`CI3/`](CI3/) | CodeIgniter **3.1.13** | 8090 | Controller `Welcome` → `Page` dengan `about()` & `contact()`, route di `application/config/routes.php` |
| [`CI4/`](CI4/) | CodeIgniter **4.7.6** | 8080 | Controller `Page` (`about`, `contact`, `faqs`), percobaan **auto routing**, lalu pengembangan: view + layout, route group, placeholder, named route |

Semua screenshot diambil dari aplikasi yang benar-benar berjalan, memakai Google Chrome mode *headless*. Bingkai di atas setiap screenshot menampilkan **URL yang dibuka** dan **kode status HTTP** yang diterima. Screenshot kode dan terminal diambil langsung dari file sumber dan output perintah aslinya.

---

## 2. Ringkasan Materi

**Router** bertugas menentukan **controller** dan **method** yang dijalankan untuk sebuah URL. **Controller** menerima request, memanggil model bila perlu, lalu mengembalikan response (biasanya view).

```
Browser ──URL──▶ index.php ──▶ Router ──▶ Controller::method() ──▶ View ──▶ Browser
                                  ▲
                     routes.php / Routes.php
```

| | CodeIgniter 3 | CodeIgniter 4 |
|---|---|---|
| File route | `application/config/routes.php` | `app/Config/Routes.php` |
| Sintaks route | `$route['about'] = 'page/about';` | `$routes->get('/about', 'Page::about');` |
| Halaman utama | `$route['default_controller'] = 'page';` | `$routes->get('/', 'Home::index');` |
| Controller induk | `CI_Controller` | `BaseController` (namespace `App\Controllers`) |
| Memuat view | `$this->load->view('about');` | `return view('about');` |
| Pola URL otomatis | `/[controller]/[method]/[argumen]`, **selalu aktif** | Auto routing, **mati secara default**; bila aktif wajib awalan HTTP verb (`getTos()`) |
| Cek daftar route | – | `php spark routes` |

---

## 3. Bagian A – CodeIgniter 3

### Langkah 1: Tambah method `about()` dan `contact()` di controller `Welcome`

Halaman awal CI3 setelah instalasi (default controller `welcome`):

![Welcome CI3](CI3/screenshots/01-welcome-default.png)

Method baru di `application/controllers/Welcome.php`, dengan view `about.php`, `contact.php`, dan template `header`/`footer`:

![Kode Welcome](CI3/screenshots/02-kode-welcome-about-contact.png)

Tanpa menulis route, CI3 sudah bisa memanggilnya dengan pola `/[controller]/[method]`:

| `/welcome/about` | `/welcome/contact` |
|---|---|
| ![welcome/about](CI3/screenshots/03-welcome-about.png) | ![welcome/contact](CI3/screenshots/04-welcome-contact.png) |

Tetapi URL pendek `/about` masih **404**, karena belum ada route-nya:

![about 404](CI3/screenshots/05-about-404-sebelum-route.png)

### Langkah 2: Tambah route

![routes welcome](CI3/screenshots/06-kode-routes-welcome.png)

| `/about` | `/contact` |
|---|---|
| ![about](CI3/screenshots/07-about-dengan-route.png) | ![contact](CI3/screenshots/08-contact-dengan-route.png) |

### Langkah 3: Ganti `Welcome` menjadi controller `Page`

`Welcome.php` diganti nama menjadi `Page.php` dan `welcome_message.php` menjadi `home.php`. Setelah itu `default_controller` serta kedua route diarahkan ke `page`.

| Controller `Page.php` | `routes.php` |
|---|---|
| ![Page](CI3/screenshots/09-kode-page-controller.png) | ![routes page](CI3/screenshots/10-kode-routes-page.png) |

`/` sekarang menjalankan `Page::index()` (footer menampilkan controller & method yang sedang aktif):

![home](CI3/screenshots/11-home-default-controller-page.png)

| `/about` → `Page::about()` | `/contact` → `Page::contact()` |
|---|---|
| ![about](CI3/screenshots/12-about-page.png) | ![contact](CI3/screenshots/13-contact-page.png) |

Pola URI langsung `/page/about` juga tetap bekerja, sedangkan `/welcome/about` menjadi 404 karena controller `Welcome` sudah tidak ada:

| `/page/about` | `/welcome/about` |
|---|---|
| ![page/about](CI3/screenshots/14-page-about-uri-langsung.png) | ![welcome 404](CI3/screenshots/15-welcome-404-setelah-rename.png) |

> **Catatan PHP 8.2+:** CI 3.1.13 dibuat sebelum PHP 8.2, sehingga muncul banyak peringatan *"Creation of dynamic property … is deprecated"*. Peringatan ini tidak memengaruhi fungsi. Di `index.php` mode development, khusus `E_DEPRECATED` disembunyikan (error lain tetap tampil).

---

## 4. Bagian B – CodeIgniter 4

### Langkah 1: Controller `Page` + route manual (sesuai artikel)

![Welcome CI4](CI4/screenshots/01-welcome-ci4.png)

| `app/Controllers/Page.php` | `app/Config/Routes.php` |
|---|---|
| ![Page echo](CI4/screenshots/02-kode-page-echo.png) | ![Routes](CI4/screenshots/03-kode-routes.png) |

![spark routes](CI4/screenshots/04-spark-routes.png)

| `/about` | `/contact` | `/faqs` |
|---|---|---|
| ![about](CI4/screenshots/05-about.png) | ![contact](CI4/screenshots/06-contact.png) | ![faqs](CI4/screenshots/07-faqs.png) |

### Langkah 2: Percobaan Auto Routing

Artikel petanikode (2020) menambahkan method `tos()` lalu mengaktifkan `$routes->setAutoRoute(true)`, sehingga `/page/tos` bisa diakses. Di **CodeIgniter 4.7**, auto routing yang dipakai adalah **Improved Auto Routing** (`Config\Feature::$autoRoutesImproved = true`). Hasilnya berbeda dari artikel, dan percobaan berikut menunjukkan dua aturan barunya.

**2a. Mengikuti artikel persis → 404.** Router mencari `Page::getTos()`, bukan `tos()`:

| `setAutoRoute(true)` | Method `tos()` |
|---|---|
| ![autoroute true](CI4/screenshots/08-kode-setautoroute-true.png) | ![tos](CI4/screenshots/09-kode-method-tos.png) |

![404 butuh get](CI4/screenshots/10-page-tos-404-butuh-awalan-get.png)

> **Aturan 1:** method harus diawali HTTP verb. `GET /page/tos` → `getTos()`, `POST /page/tos` → `postTos()`.

**2b. Diganti `getTos()` → tetap 404**, karena controller `Page` sudah dipakai di route manual (`/about`, `/contact`, `/faqs`):

![kode page getTos](CI4/screenshots/11-kode-page-gettos.png)
![404 defined routes](CI4/screenshots/12-page-gettos-404-defined-routes.png)

> **Aturan 2:** controller yang sudah terdaftar di route manual **tidak bisa** diakses lewat auto routing. Ini fitur keamanan: kalau diizinkan, filter yang dipasang di route manual (misalnya filter login) bisa dilewati lewat URL otomatis.

**2c. Pindah ke controller baru `Info` (tanpa route manual) → berhasil.** `php spark routes` menampilkan baris `GET(auto)`:

![kode info](CI4/screenshots/13-kode-info-gettos.png)
![spark routes auto](CI4/screenshots/14-spark-routes-auto.png)

| `/info/tos` | `/index.php/info/tos` |
|---|---|
| ![info/tos](CI4/screenshots/15-info-tos-auto-route-on.png) | ![index.php](CI4/screenshots/16-index-php-info-tos.png) |

**2d. `setAutoRoute(false)` → 404 lagi.** Hanya route yang ditulis manual yang bisa diakses:

![autoroute false](CI4/screenshots/17-kode-setautoroute-false.png)
![404 autoroute off](CI4/screenshots/18-info-tos-404-auto-route-off.png)

### Langkah 3: Pengembangan – View, Route Group, Placeholder, Named Route

Method controller kini mengembalikan **view** dengan layout Bootstrap (bukan `echo`), dan ditambahkan controller `Buah` yang menerima parameter dari URL.

![routes final](CI4/screenshots/19-kode-routes-final.png)

| `Page.php` (return view) | `Buah.php` (parameter URL) |
|---|---|
| ![page view](CI4/screenshots/20-kode-page-view.png) | ![buah](CI4/screenshots/21-kode-buah-controller.png) |

![spark routes final](CI4/screenshots/22-spark-routes-final.png)

Halaman utama berisi daftar semua route:

![home](CI4/screenshots/23-home.png)

| `/about` | `/contact` |
|---|---|
| ![about](CI4/screenshots/24-about-view.png) | ![contact](CI4/screenshots/25-contact-view.png) |

| `/faqs` (data dari controller) | `/tos` (route manual ke `Info::getTos`) |
|---|---|
| ![faqs](CI4/screenshots/26-faqs-view.png) | ![tos](CI4/screenshots/31-tos-route-manual.png) |

**Route group + named route** `/buah`. Link dibuat dengan `url_to('buah.detail', $slug)`:

![buah](CI4/screenshots/27-buah-index.png)

**Placeholder `(:segment)`** `/buah/apel-malang` → `Buah::detail('apel-malang')`. Data yang tidak ada → 404:

| `/buah/apel-malang` | `/buah/durian` |
|---|---|
| ![detail](CI4/screenshots/28-buah-detail-segment.png) | ![404](CI4/screenshots/29-buah-detail-404.png) |

**Placeholder `(:alpha)`** `/buah/kategori/impor` → `Buah::kategori('impor')`:

![kategori](CI4/screenshots/30-buah-kategori-alpha.png)

---

## 5. Struktur Project

```
TugasMinggu10
├── .vs/tasks.vs.json                 # task Visual Studio (jalankan CI3 / CI4)
├── CI3/                              # CodeIgniter 3.1.13
│   ├── application/
│   │   ├── config/routes.php         # default_controller, about, contact
│   │   ├── controllers/Page.php      # index(), about(), contact()
│   │   └── views/                    # home, about, contact, templates/header & footer
│   ├── index.php                     # front controller
│   ├── .htaccess                     # hapus index.php dari URL (Apache/XAMPP)
│   ├── jalankan.bat                  # php -S localhost:8090 index.php
│   └── screenshots/
└── CI4/                              # CodeIgniter 4.7.6
    ├── app/
    │   ├── Config/Routes.php
    │   ├── Controllers/              # Home, Page, Info, Buah
    │   ├── Models/BuahModel.php      # data buah (array, tanpa database)
    │   └── Views/                    # layouts/main, home, pages/*, buah/*
    ├── jalankan.bat                  # php spark serve --port 8080
    └── screenshots/
```

---

## 6. Cara Menjalankan di Visual Studio

**Prasyarat:** Visual Studio 2022/2026, PHP 8.2+ di `PATH` (extension `intl`, `mbstring`), dan Composer.

1. **File → Open → Folder...** → pilih `TugasMinggu10`.
2. Klik kanan folder `TugasMinggu10` di **Solution Explorer**, lalu pilih:
   - **CodeIgniter 3 - Toko Buah (localhost:8090)**
   - **CodeIgniter 4 - Toko Buah (localhost:8080)**
3. Atau dari **View → Terminal**: jalankan `jalankan.bat` di folder `CI3` atau `CI4`.

**Alternatif CI3 dengan XAMPP:** salin folder `CI3` ke `C:\xampp\htdocs\tokobuah`, ubah `base_url` di `application/config/config.php` menjadi `http://localhost/tokobuah/`, lalu buka `http://localhost/tokobuah/`. File `.htaccess` sudah disiapkan agar URL tanpa `index.php` bekerja di Apache.

---

## 7. Kesimpulan

Router adalah "peta" yang menghubungkan URL dengan method controller. Di **CI3**, pola `/controller/method` selalu aktif, sehingga route di `routes.php` terutama dipakai untuk membuat URL yang lebih pendek (`/about` alih-alih `/welcome/about`) dan menentukan halaman utama lewat `default_controller`. Di **CI4**, route ditulis eksplisit dengan HTTP verb (`$routes->get()`) dan dapat diperiksa dengan `php spark routes`. Auto routing di CI4 dimatikan secara default dan, bila dinyalakan, memakai aturan yang lebih ketat dari tutorial lama: method wajib berawalan verb (`getTos()`), dan controller yang sudah punya route manual tidak bisa diakses lewat auto route. Keduanya mencegah method atau filter keamanan terlewati lewat URL yang tidak direncanakan. Fitur routing CI4 lainnya (placeholder `(:segment)`/`(:alpha)`, route group, dan named route dengan `url_to()`) membuat URL rapi, parameter langsung masuk sebagai argumen method, dan link di view tidak perlu diubah ketika pola URL berubah.

---

## 8. Referensi

- Fajar Baskoro – PBKK-2-2 Fungsi Router dan Controller
- Petani Kode – [Belajar CodeIgniter: Memahami Konsep MVC](https://www.petanikode.com/codeigniter-mvc/)
- Petani Kode – [Tutorial CodeIgniter 4: Memahami Controller dan Routing](https://www.petanikode.com/codeigniter4-controller/)
- [CodeIgniter 3 User Guide – URI Routing](https://codeigniter.com/userguide3/general/routing.html)
- [CodeIgniter 4 User Guide – URI Routing & Auto Routing (Improved)](https://codeigniter.com/user_guide/incoming/routing.html)
