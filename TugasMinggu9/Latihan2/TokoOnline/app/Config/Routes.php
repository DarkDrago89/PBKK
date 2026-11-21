<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ---------------- Publik: katalog & keranjang ----------------
$routes->get('/', 'Home::index');
$routes->get('produk/(:segment)', 'Home::produk/$1');

$routes->get('keranjang', 'Keranjang::index');
$routes->post('keranjang/tambah', 'Keranjang::tambah');
$routes->post('keranjang/ubah', 'Keranjang::ubah');
$routes->post('keranjang/hapus/(:num)', 'Keranjang::hapus/$1');

// ---------------- Autentikasi ----------------
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attempt');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::simpanRegister');
$routes->get('logout', 'Auth::logout');

$routes->group('', ['filter' => 'auth'], static function ($routes) {
    // ---------------- Customer ----------------
    $routes->group('', ['filter' => 'role:customer'], static function ($routes) {
        $routes->get('checkout', 'Checkout::index');
        $routes->post('checkout', 'Checkout::proses');

        $routes->get('akun/pesanan', 'Akun\Pesanan::index');
        $routes->get('akun/pesanan/(:segment)', 'Akun\Pesanan::show/$1');
        $routes->post('akun/pesanan/(:segment)/batal', 'Akun\Pesanan::batal/$1');
    });

    // ---------------- Admin ----------------
    $routes->group('admin', ['filter' => 'role:admin', 'namespace' => 'App\Controllers\Admin'], static function ($routes) {
        $routes->get('/', 'Dashboard::index');

        $routes->get('kategori', 'Kategori::index');
        $routes->get('kategori/(:num)/edit', 'Kategori::index/$1');
        $routes->post('kategori/simpan', 'Kategori::simpan');
        $routes->post('kategori/(:num)/simpan', 'Kategori::simpan/$1');
        $routes->post('kategori/(:num)/delete', 'Kategori::delete/$1');

        $routes->get('produk', 'Produk::index');
        $routes->get('produk/create', 'Produk::create');
        $routes->post('produk/store', 'Produk::store');
        $routes->get('produk/(:num)/edit', 'Produk::edit/$1');
        $routes->post('produk/(:num)/update', 'Produk::update/$1');
        $routes->post('produk/(:num)/delete', 'Produk::delete/$1');

        $routes->get('pesanan', 'Pesanan::index');
        $routes->get('pesanan/(:num)', 'Pesanan::show/$1');
        $routes->post('pesanan/(:num)/status', 'Pesanan::status/$1');
    });
});
