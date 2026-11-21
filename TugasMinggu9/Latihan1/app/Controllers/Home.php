<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('home', ['title' => 'Beranda']);
    }

    /**
     * Welcome Page bawaan CodeIgniter (tanda instalasi berhasil).
     */
    public function welcome(): string
    {
        return view('welcome_message');
    }
}
