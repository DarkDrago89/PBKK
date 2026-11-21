<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ---------------------------------------------------------------
// Halaman publik
// ---------------------------------------------------------------
$routes->get('/', 'Home::index');

// ---------------------------------------------------------------
// Autentikasi (sesuai Hands-On Lab). Filter 'guest' = hanya untuk yang belum login.
// ---------------------------------------------------------------
$routes->get('register', 'AuthController::register', ['filter' => 'guest']);
$routes->post('register', 'AuthController::store', ['filter' => 'guest']);
$routes->get('login', 'AuthController::login', ['filter' => 'guest']);
$routes->post('login', 'AuthController::authenticate', ['filter' => 'guest']);
$routes->post('logout', 'AuthController::logout', ['filter' => 'auth']);

// ---------------------------------------------------------------
// Area yang wajib login (semua role)
// ---------------------------------------------------------------
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('profil', 'Profile::index');
    $routes->post('profil', 'Profile::update');
    $routes->post('profil/password', 'Profile::password');
});

// ---------------------------------------------------------------
// Customer
// ---------------------------------------------------------------
$routes->group('', ['filter' => 'auth:customer'], static function ($routes) {
    $routes->get('keranjang', 'Home::keranjang');
    $routes->get('pesanan', 'Home::pesanan');
});

// ---------------------------------------------------------------
// Admin
// ---------------------------------------------------------------
$routes->group('admin', ['filter' => 'auth:admin', 'namespace' => 'App\Controllers\Admin'], static function ($routes) {
    $routes->get('/', 'Dashboard::index');
    $routes->get('users', 'Users::index');
    $routes->get('users/create', 'Users::create');
    $routes->post('users', 'Users::store');
    $routes->post('users/(:num)/role', 'Users::role/$1');
    $routes->post('users/(:num)/delete', 'Users::delete/$1');
});

// ---------------------------------------------------------------
// Operator (admin juga boleh melihat) -> contoh filter dengan 2 role
// ---------------------------------------------------------------
$routes->group('operator', ['filter' => 'auth:operator,admin', 'namespace' => 'App\Controllers\Operator'], static function ($routes) {
    $routes->get('/', 'Dashboard::index');
});
