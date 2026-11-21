<?php

namespace App\Controllers;

class Home extends BaseController
{
    // GET /  (publik)
    public function index(): string
    {
        $cfg = config('ShopEase');

        return view('store/home', [
            'title'    => 'Belanja Online Mudah & Aman',
            'kategori' => $cfg->kategori,
            'produk'   => $cfg->produk,
        ]);
    }

    // GET /keranjang  (filter auth:customer)
    public function keranjang(): string
    {
        return view('store/segera', [
            'title' => 'Keranjang Belanja',
            'ikon'  => 'cart3',
            'modul' => 'Keranjang & Checkout',
            'm'     => 'M4',
        ]);
    }

    // GET /pesanan  (filter auth:customer)
    public function pesanan(): string
    {
        return view('store/segera', [
            'title' => 'Riwayat Pesanan',
            'ikon'  => 'receipt',
            'modul' => 'Transaksi & Riwayat Pesanan',
            'm'     => 'M4',
        ]);
    }
}
