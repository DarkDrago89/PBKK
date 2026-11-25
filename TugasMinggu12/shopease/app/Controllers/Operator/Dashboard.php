<?php

namespace App\Controllers\Operator;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    // GET /operator  (filter auth:operator,admin)
    public function index(): string
    {
        $orders = new OrderModel();

        return view('operator/dashboard', [
            'title'     => 'Dashboard Operator',
            'customer'  => (new UserModel())->where('role', 'customer')->countAllResults(),
            'perStatus' => $orders->countByStatus(),
            'antrian'   => (new OrderModel())->search(null, 'menunggu', 5),
            'menipis'   => (new ProductModel())->stokMenipis(config('ShopEase')->batasStokMenipis),
        ]);
    }
}
