<?php

namespace Config;

use App\Libraries\Keranjang;
use CodeIgniter\Config\BaseService;

/**
 * Services: tempat mendaftarkan class yang dipakai di banyak tempat.
 * Pemanggilan: service('keranjang') atau \Config\Services::keranjang()
 */
class Services extends BaseService
{
    /**
     * Keranjang belanja berbasis session (shared = satu instance per request).
     */
    public static function keranjang(bool $getShared = true): Keranjang
    {
        if ($getShared) {
            return static::getSharedInstance('keranjang');
        }

        return new Keranjang(service('session'));
    }
}
