<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PesananModel;
use App\Models\ProdukModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    public function index(): string
    {
        $pesanan = new PesananModel();

        $perStatus = array_column(
            $pesanan->select('status, COUNT(*) AS jumlah')->groupBy('status')->findAll(),
            'jumlah',
            'status',
        );

        return view('admin/dashboard', [
            'title'      => 'Dashboard',
            'omzet'      => (int) ($pesanan->selectSum('total')->where('status', 'selesai')->first()->total ?? 0),
            'perStatus'  => $perStatus,
            'jmlProduk'  => (new ProdukModel())->countAllResults(),
            'jmlCustomer' => (new UserModel())->where('role', 'customer')->countAllResults(),
            'menipis'    => (new ProdukModel())->stokMenipis(5),
            'terbaru'    => (new PesananModel())->withUser()->orderBy('pesanan.id', 'DESC')->findAll(5),
        ]);
    }
}
