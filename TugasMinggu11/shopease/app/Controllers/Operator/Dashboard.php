<?php

namespace App\Controllers\Operator;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    // GET /operator  (filter auth:operator,admin)
    public function index(): string
    {
        return view('operator/dashboard', [
            'title'    => 'Dashboard Operator',
            'customer' => (new UserModel())->where('role', 'customer')->countAllResults(),
        ]);
    }
}
