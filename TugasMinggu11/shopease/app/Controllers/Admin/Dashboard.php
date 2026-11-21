<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    // GET /admin  (filter auth:admin)
    public function index(): string
    {
        $users = new UserModel();

        return view('admin/dashboard', [
            'title'     => 'Dashboard Admin',
            'perRole'   => $users->countByRole(),
            'harian'    => $users->registrasiHarian(7),
            'terbaru'   => $users->orderBy('id_user', 'DESC')->findAll(5),
        ]);
    }
}
