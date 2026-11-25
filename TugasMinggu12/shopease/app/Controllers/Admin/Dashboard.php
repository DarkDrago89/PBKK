<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    // GET /admin  (filter auth:admin)
    public function index(): string
    {
        $users  = new UserModel();
        $orders = new OrderModel();

        return view('admin/dashboard', [
            'title'     => 'Dashboard Admin',
            'perRole'   => $users->countByRole(),
            'harian'    => $users->registrasiHarian(7),
            'terbaru'   => $users->orderBy('id_user', 'DESC')->findAll(5),
            'produk'    => (new ProductModel())->countAllResults(),
            'kategori'  => (new CategoryModel())->countAllResults(),
            'perStatus' => $orders->countByStatus(),
            'omzet'     => (new OrderModel())->omzetHarian(7),
        ]);
    }
}
