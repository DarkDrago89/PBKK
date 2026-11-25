<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Konfigurasi aplikasi ShopEase: config('ShopEase').
 * Kategori & produk sekarang disimpan di database (lihat CatalogSeeder).
 */
class ShopEase extends BaseConfig
{
    public string $nama    = 'ShopEase';
    public string $tagline = 'Online Store';

    /** Produk per halaman: katalog customer & tabel kelola produk. */
    public int $perPageKatalog = 12;
    public int $perPageKelola  = 10;

    /** Stok <= nilai ini ditandai "menipis" di dashboard operator. */
    public int $batasStokMenipis = 5;

    /** Batas jumlah barang per pesanan. */
    public int $maksQty = 10;
}
