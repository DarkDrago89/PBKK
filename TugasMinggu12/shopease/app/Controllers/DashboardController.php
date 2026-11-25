<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\ProductModel;

/**
 * Dashboard Customer (route /dashboard, filter auth:customer):
 * profil singkat + ringkasan aktivitas pesanan.
 */
class DashboardController extends BaseController
{
    // GET /dashboard
    public function index(): string
    {
        $idUser = (int) session('id_user');
        $orders = new OrderModel();

        return view('customer/dashboard', [
            'title'     => 'Dashboard Saya',
            'user'      => service('auth')->user(),
            'ringkasan' => $orders->ringkasanUser($idUser),
            'terbaru'   => (new OrderModel())->milikUser($idUser)->orderBy('id_order', 'DESC')->findAll(5),
            'rekomen'   => (new ProductModel())->terbaru(4),
        ]);
    }
}
