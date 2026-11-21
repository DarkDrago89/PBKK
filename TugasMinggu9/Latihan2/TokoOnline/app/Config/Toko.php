<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Konfigurasi toko. Diakses dengan config('Toko').
 */
class Toko extends BaseConfig
{
    public string $nama = 'TokoKita';

    /** Ongkos kirim flat per pesanan (Rupiah). */
    public int $ongkir = 15000;

    /** Gratis ongkir bila subtotal >= nilai ini. */
    public int $gratisOngkirMin = 500000;

    /** Status pesanan => [label, warna badge]. */
    public array $status = [
        'menunggu'   => ['Menunggu Pembayaran', 'warning'],
        'diproses'   => ['Diproses',            'info'],
        'dikirim'    => ['Dikirim',             'primary'],
        'selesai'    => ['Selesai',             'success'],
        'dibatalkan' => ['Dibatalkan',          'danger'],
    ];
}
