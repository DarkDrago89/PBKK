<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->get('/', 'Auth::login');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attempt');
$routes->get('logout', 'Auth::logout');

// Semua halaman di bawah ini wajib login
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('dashboard', 'Dashboard::index');

    // ---------------- ADMIN: master data ----------------
    $routes->group('admin', ['filter' => 'role:admin', 'namespace' => 'App\Controllers\Admin'], static function ($routes) {
        foreach (['mahasiswa' => 'Mahasiswa', 'dosen' => 'Dosen', 'matakuliah' => 'Matakuliah'] as $uri => $ctrl) {
            $routes->get($uri, "{$ctrl}::index");
            $routes->get("{$uri}/create", "{$ctrl}::create");
            $routes->post("{$uri}/store", "{$ctrl}::store");
            $routes->get("{$uri}/(:num)/edit", "{$ctrl}::edit/$1");
            $routes->post("{$uri}/(:num)/update", "{$ctrl}::update/$1");
            $routes->post("{$uri}/(:num)/delete", "{$ctrl}::delete/$1");
        }
    });

    // ---------------- MAHASISWA: KRS & KHS ----------------
    $routes->group('mahasiswa', ['filter' => 'role:mahasiswa', 'namespace' => 'App\Controllers\Mahasiswa'], static function ($routes) {
        $routes->get('krs', 'Krs::index');
        $routes->post('krs/ambil', 'Krs::ambil');
        $routes->post('krs/(:num)/batal', 'Krs::batal/$1');
        $routes->get('khs', 'Khs::index');
    });

    // ---------------- DOSEN: kelas & input nilai ----------------
    $routes->group('dosen', ['filter' => 'role:dosen', 'namespace' => 'App\Controllers\Dosen'], static function ($routes) {
        $routes->get('kelas', 'Kelas::index');
        $routes->get('kelas/(:num)', 'Kelas::nilai/$1');
        $routes->post('kelas/(:num)/nilai', 'Kelas::simpanNilai/$1');
    });
});
