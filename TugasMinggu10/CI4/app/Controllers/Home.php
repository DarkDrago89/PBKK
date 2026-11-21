<?php

namespace App\Controllers;

class Home extends BaseController
{
    // GET /   (route: $routes->get('/', 'Home::index'))
    public function index(): string
    {
        return view('home', ['title' => 'Home']);
    }
}
