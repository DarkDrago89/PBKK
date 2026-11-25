<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Kategori, produk, dan contoh pesanan. Dipanggil dari DatabaseSeeder.
 */
class CatalogSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        // [nama, ikon bootstrap-icons, warna, deskripsi]
        $kategori = [
            ['Elektronik',   'laptop',       '#6d28d9', 'Gadget, aksesoris komputer, dan perangkat audio.'],
            ['Fashion',      'bag-heart',    '#db2777', 'Tas, pakaian, dan perlengkapan gaya sehari-hari.'],
            ['Aksesoris',    'smartwatch',   '#0891b2', 'Jam tangan pintar, case HP, dan pelengkap gadget.'],
            ['Rumah Tangga', 'house-heart',  '#ea580c', 'Perabot dan peralatan rumah yang praktis.'],
            ['Kecantikan',   'droplet-half', '#c026d3', 'Perawatan kulit dan kosmetik.'],
        ];
        $idKat = [];
        foreach ($kategori as [$nama, $ikon, $warna, $desk]) {
            $this->db->table('categories')->insert([
                'name' => $nama, 'slug' => url_title($nama, '-', true), 'icon' => $ikon, 'color' => $warna,
                'description' => $desk, 'created_at' => $now, 'updated_at' => $now,
            ]);
            $idKat[$nama] = $this->db->insertID();
        }

        $admin    = $this->db->table('users')->where('role', 'admin')->get()->getRow()->id_user;
        $operator = $this->db->table('users')->where('role', 'operator')->get()->getRow()->id_user;

        // [nama, kategori, harga, stok, deskripsi]
        $produk = [
            ['Headphone Wireless ANC',  'Elektronik',   899000, 15, 'Headphone over-ear dengan Active Noise Cancelling, baterai 30 jam, dan pengisian cepat USB-C.'],
            ['Smartwatch Fit Pro',      'Aksesoris',    649000, 20, 'Pantau detak jantung, langkah, dan tidur. Tahan air 5 ATM dengan layar AMOLED 1,4 inci.'],
            ['Tas Ransel Urban',        'Fashion',      289000, 25, 'Ransel 22 liter dengan kompartemen laptop 15 inci dan bahan tahan air.'],
            ['Laptop Stand Aluminium',  'Elektronik',   199000, 4,  'Dudukan laptop lipat dengan 6 tingkat ketinggian untuk postur kerja yang lebih baik.'],
            ['Tumbler Stainless 500ml', 'Rumah Tangga', 129000, 40, 'Menjaga minuman tetap panas 12 jam dan dingin 24 jam. Bebas BPA.'],
            ['Case HP MagSafe',         'Aksesoris',     99000, 3,  'Case transparan anti-kuning dengan magnet MagSafe bawaan.'],
            ['Serum Vitamin C',         'Kecantikan',   159000, 30, 'Serum 20 ml dengan 10% vitamin C untuk mencerahkan dan meratakan warna kulit.'],
            ['Lampu Meja LED',          'Rumah Tangga', 175000, 12, 'Lampu belajar dengan 3 mode warna, pengatur kecerahan, dan port USB.'],
            ['Keyboard Mekanikal 75%',  'Elektronik',   749000, 8,  'Keyboard hot-swap dengan switch linear, koneksi Bluetooth dan kabel.'],
            ['Sneakers Kanvas Putih',   'Fashion',      349000, 18, 'Sepatu kanvas klasik dengan sol karet yang empuk untuk dipakai seharian.'],
            ['Lip Tint Matte',          'Kecantikan',    69000, 0,  'Lip tint ringan tahan lama dengan hasil akhir matte.'],
        ];
        $idProd = [];
        foreach ($produk as $i => [$nama, $kat, $harga, $stok, $desk]) {
            $waktu = date('Y-m-d H:i:s', strtotime('-' . (count($produk) - $i) . ' days'));
            $this->db->table('products')->insert([
                'id_category' => $idKat[$kat], 'name' => $nama, 'slug' => url_title($nama, '-', true),
                'description' => $desk, 'price' => $harga, 'stock' => $stok, 'is_active' => 1,
                'created_by'  => $i % 2 ? $operator : $admin, 'created_at' => $waktu, 'updated_at' => $waktu,
            ]);
            $idProd[$nama] = [$this->db->insertID(), $harga];
        }

        // Contoh pesanan: [email customer, produk, qty, status, hari yang lalu]
        $pesanan = [
            ['agil@shopease.test', 'Headphone Wireless ANC',  1, 'selesai',    6],
            ['agil@shopease.test', 'Tumbler Stainless 500ml', 2, 'dikirim',    3],
            ['agil@shopease.test', 'Lampu Meja LED',          1, 'diproses',   1],
            ['agil@shopease.test', 'Serum Vitamin C',         1, 'menunggu',   0],
            ['siti@shopease.test', 'Tas Ransel Urban',        1, 'selesai',    5],
            ['siti@shopease.test', 'Smartwatch Fit Pro',      1, 'menunggu',   0],
            ['budi@shopease.test', 'Keyboard Mekanikal 75%',  1, 'dibatalkan', 4],
            ['budi@shopease.test', 'Case HP MagSafe',         2, 'menunggu',   1],
            ['andi@shopease.test', 'Sneakers Kanvas Putih',   1, 'selesai',    2],
        ];
        foreach ($pesanan as $i => [$email, $nama, $qty, $status, $hari]) {
            $user          = $this->db->table('users')->where('email', $email)->get()->getRow();
            [$idP, $harga] = $idProd[$nama];
            $waktu         = date('Y-m-d H:i:s', strtotime("-{$hari} days") - 600 * $i);
            $this->db->table('orders')->insert([
                'invoice'          => 'INV-' . date('Ymd', strtotime($waktu)) . sprintf('-%06X', 0xA10000 + $i),
                'id_user'          => $user->id_user,
                'id_product'       => $idP,
                'product_name'     => $nama,
                'price'            => $harga,
                'qty'              => $qty,
                'total'            => $harga * $qty,
                'status'           => $status,
                'shipping_address' => $user->address ?? '-',
                'created_at'       => $waktu,
                'updated_at'       => $waktu,
            ]);
        }
    }
}
