<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductModel;

class Home extends BaseController
{
    // GET /  (publik) — etalase. Detail produk & pemesanan wajib login.
    public function index(): string
    {
        return view('store/home', [
            'title'    => 'Belanja Online Mudah & Aman',
            'kategori' => (new CategoryModel())->withProductCount(),
            'produk'   => (new ProductModel())->terbaru(8),
        ]);
    }
}
