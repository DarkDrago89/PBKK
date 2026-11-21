<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Konfigurasi tampilan ShopEase: config('ShopEase').
 * Data katalog di sini masih statis untuk halaman beranda;
 * modul produk berbasis database dibangun pada milestone M3.
 */
class ShopEase extends BaseConfig
{
    public string $nama    = 'ShopEase';
    public string $tagline = 'Online Store';

    /** [ikon bootstrap-icons, nama, warna] */
    public array $kategori = [
        ['laptop',        'Elektronik',    '#6d28d9'],
        ['bag-heart',     'Fashion',       '#db2777'],
        ['smartwatch',    'Aksesoris',     '#0891b2'],
        ['house-heart',   'Rumah Tangga',  '#ea580c'],
        ['droplet-half',  'Kecantikan',    '#c026d3'],
    ];

    /** [ikon, nama, harga, kategori, warna latar] */
    public array $produk = [
        ['headphones',  'Headphone Wireless ANC', 899000, 'Elektronik', '#ede9fe'],
        ['smartwatch',  'Smartwatch Fit Pro',     649000, 'Aksesoris',  '#e0f2fe'],
        ['backpack2',   'Tas Ransel Urban',       289000, 'Fashion',    '#fce7f3'],
        ['laptop',      'Laptop Stand Aluminium', 199000, 'Elektronik', '#ede9fe'],
        ['cup-hot',     'Tumbler Stainless 500ml', 129000, 'Rumah Tangga', '#ffedd5'],
        ['phone',       'Case HP MagSafe',         99000, 'Aksesoris',  '#e0f2fe'],
        ['droplet',     'Serum Vitamin C',        159000, 'Kecantikan', '#fae8ff'],
        ['lamp',        'Lampu Meja LED',         175000, 'Rumah Tangga', '#ffedd5'],
    ];
}
