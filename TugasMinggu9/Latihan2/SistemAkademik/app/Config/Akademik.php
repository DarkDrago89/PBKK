<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Konfigurasi akademik. Diakses dengan config('Akademik').
 * Nilai bisa di-override lewat .env, contoh: akademik.maxSks = 20
 */
class Akademik extends BaseConfig
{
    /** Tahun ajaran yang sedang aktif untuk pengisian KRS & input nilai. */
    public string $tahunAjaran = '2026/2027 Ganjil';

    /** Batas maksimal SKS per semester. */
    public int $maxSks = 24;

    /** Daftar program studi. */
    public array $prodi = [
        'Teknik Informatika',
        'Sistem Informasi',
        'Teknologi Informasi',
    ];
}
