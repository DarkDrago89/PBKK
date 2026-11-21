<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Jalankan: php spark db:seed DatabaseSeeder
 */
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        helper("url"); // untuk url_title()
        $now = date('Y-m-d H:i:s');
        $ts  = ['created_at' => $now, 'updated_at' => $now];

        $this->db->table('users')->insertBatch([
            ['username' => 'admin', 'nama' => 'Admin Toko', 'email' => 'admin@tokokita.test', 'password' => password_hash('admin123', PASSWORD_DEFAULT), 'role' => 'admin', 'telepon' => null, 'alamat' => null] + $ts,
            ['username' => 'agil', 'nama' => 'Agil Lukman', 'email' => 'agil@example.com', 'password' => password_hash('agil123', PASSWORD_DEFAULT), 'role' => 'customer', 'telepon' => '081234567890', 'alamat' => "Jl. Teknik Kimia, Keputih, Sukolilo\nSurabaya 60111"] + $ts,
        ]);

        $kategori = ['Elektronik', 'Buku', 'Fashion', 'Alat Tulis'];
        foreach ($kategori as $k) {
            $this->db->table('kategori')->insert(['nama' => $k, 'slug' => url_title($k, '-', true)] + $ts);
        }

        // [kategori_id, nama, harga, stok, deskripsi]
        $produk = [
            [1, 'Mouse Wireless Silent',        89000, 25, 'Mouse wireless 2.4 GHz dengan klik senyap, baterai AA tahan hingga 12 bulan.'],
            [1, 'Keyboard Mechanical 87 Key',  459000, 10, 'Keyboard mechanical TKL dengan switch brown dan lampu RGB.'],
            [1, 'Headset Gaming 7.1',          325000,  8, 'Headset dengan surround 7.1 virtual dan mikrofon noise-cancelling.'],
            [1, 'Flashdisk 64GB USB 3.2',       79000, 40, 'Flashdisk kecepatan baca hingga 150 MB/s.'],
            [1, 'Powerbank 10000mAh',          199000,  3, 'Powerbank fast charging 22.5W dengan dua port USB-A dan satu USB-C.'],
            [2, 'Belajar CodeIgniter 4',       120000, 15, 'Panduan membangun aplikasi web MVC dengan CodeIgniter 4 dari nol.'],
            [2, 'Algoritma & Struktur Data',   145000, 12, 'Buku teks algoritma dengan contoh implementasi dalam C++ dan Python.'],
            [2, 'Clean Code (Terjemahan)',     160000,  0, 'Panduan menulis kode yang mudah dibaca dan dirawat.'],
            [3, 'Kaos Informatika',             85000, 30, 'Kaos katun combed 30s dengan sablon "Hello, World!".'],
            [3, 'Hoodie Developer',            210000, 12, 'Hoodie fleece tebal, cocok untuk ngoding di ruangan ber-AC.'],
            [4, 'Buku Catatan A5 Dotted',       35000, 50, 'Notebook A5 kertas 100 gsm dengan pola titik.'],
            [4, 'Pulpen Gel 0.5 (isi 5)',       25000, 60, 'Pulpen gel tinta hitam, biru, merah, hijau, dan ungu.'],
        ];
        foreach ($produk as [$kat, $nama, $harga, $stok, $desk]) {
            $this->db->table('produk')->insert([
                'kategori_id' => $kat, 'nama' => $nama, 'slug' => url_title($nama, '-', true),
                'deskripsi' => $desk, 'harga' => $harga, 'stok' => $stok, 'gambar' => null,
            ] + $ts);
        }

        // Satu pesanan contoh yang sudah selesai milik customer 'agil'
        $this->db->table('pesanan')->insert([
            'kode' => 'INV-' . date('Ymd') . '-0001', 'user_id' => 2, 'nama_penerima' => 'Agil Lukman',
            'telepon' => '081234567890', 'alamat' => "Jl. Teknik Kimia, Keputih, Sukolilo\nSurabaya 60111",
            'catatan' => null, 'subtotal' => 209000, 'ongkir' => 15000, 'total' => 224000, 'status' => 'selesai',
        ] + $ts);
        $this->db->table('pesanan_detail')->insertBatch([
            ['pesanan_id' => 1, 'produk_id' => 6, 'nama_produk' => 'Belajar CodeIgniter 4', 'harga' => 120000, 'qty' => 1, 'subtotal' => 120000],
            ['pesanan_id' => 1, 'produk_id' => 1, 'nama_produk' => 'Mouse Wireless Silent', 'harga' => 89000, 'qty' => 1, 'subtotal' => 89000],
        ]);
    }
}
