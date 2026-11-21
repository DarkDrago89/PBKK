<?php

namespace Config;

use App\Libraries\Auth;
use CodeIgniter\Config\BaseService;

/**
 * Services: class yang dipakai di banyak tempat, diakses lewat service('nama').
 */
class Services extends BaseService
{
    /** service('auth') -> App\Libraries\Auth (satu instance per request). */
    public static function auth(bool $getShared = true): Auth
    {
        if ($getShared) {
            return static::getSharedInstance('auth');
        }

        return new Auth(service('session'));
    }
}
