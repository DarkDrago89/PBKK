<?php

namespace App\Controllers;

/**
 * LANGKAH 2c: controller untuk percobaan auto routing.
 *
 * Improved Auto Routing (CI 4.2+, default sejak 4.5) memetakan:
 *   GET  /info/tos  ->  Info::getTos()
 * Method tanpa awalan HTTP verb tidak bisa diakses lewat URL.
 *
 * LANGKAH 3: auto routing dimatikan, method ini didaftarkan manual
 * di Routes.php sebagai GET /tos.
 */
class Info extends BaseController
{
    public function getTos(): string
    {
        return view('pages/tos', ['title' => 'Term of Service']);
    }
}
