<?php

use CodeIgniter\Router\RouteCollection;

/**
 * Router: memetakan URL ke Controller::method.
 *
 * @var RouteCollection $routes
 */

// --- Halaman publik (Bootstrap 5) ---
$routes->get('/', 'Home::index');
$routes->get('welcome', 'Home::welcome');           // Welcome Page bawaan CodeIgniter

// --- Hello World + contoh placeholder route ---
$routes->get('hello', 'Hello::index');               // /hello
$routes->get('hello/(:segment)', 'Hello::nama/$1');  // /hello/Agil  -> $nama = "Agil"

// --- Autentikasi ---
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attempt');
$routes->get('logout', 'Auth::logout');

// --- Area admin (template SB Admin 2), wajib login ---
$routes->group('admin', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'Dashboard::index');

    // READ: boleh diakses semua user yang sudah login (admin & user)
    $routes->get('biodata', 'Biodata::index');
    $routes->get('biodata/(:num)', 'Biodata::show/$1');

    // CREATE / UPDATE / DELETE: hanya role admin
    $routes->group('biodata', ['filter' => 'role:admin'], static function ($routes) {
        $routes->get('create', 'Biodata::create');
        $routes->post('store', 'Biodata::store');
        $routes->get('(:num)/edit', 'Biodata::edit/$1');
        $routes->post('(:num)/update', 'Biodata::update/$1');
        $routes->post('(:num)/delete', 'Biodata::delete/$1');
    });
});
