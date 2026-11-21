<?php

namespace App\Controllers;

/**
 * LANGKAH 3: method Page kini mengembalikan View (bukan echo),
 * sehingga tampilan terpisah dari controller (konsep MVC).
 */
class Page extends BaseController
{
    public function about(): string
    {
        return view('pages/about', ['title' => 'About']);
    }

    public function contact(): string
    {
        return view('pages/contact', ['title' => 'Contact']);
    }

    public function faqs(): string
    {
        $faqs = [
            'Apa itu Router?'       => 'Bagian framework yang menentukan Controller dan method mana yang dijalankan berdasarkan URL.',
            'Di mana route ditulis?' => 'Di CodeIgniter 4: app/Config/Routes.php. Di CodeIgniter 3: application/config/routes.php.',
            'Apa itu Auto Routing?'  => 'Pemetaan otomatis /controller/method tanpa menulis route. Di CI 4 default-nya mati demi keamanan.',
            'Kenapa method harus getXxx()?' => 'Improved Auto Routing mewajibkan awalan HTTP verb agar method hanya bisa diakses dengan verb yang sesuai.',
        ];

        return view('pages/faqs', ['title' => 'FAQs', 'faqs' => $faqs]);
    }
}
