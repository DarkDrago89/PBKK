<?php

namespace App\Controllers;

use App\Models\BiodataModel;

class Dashboard extends BaseController
{
    public function index(): string
    {
        $model = new BiodataModel();

        return view('dashboard/index', [
            'title'   => 'Dashboard',
            'stat'    => $model->statistik(),
            'terbaru' => $model->orderBy('created_at', 'DESC')->orderBy('id', 'DESC')->findAll(5),
        ]);
    }
}
