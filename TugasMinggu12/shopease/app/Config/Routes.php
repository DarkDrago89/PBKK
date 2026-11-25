<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 *
 * Ringkasan hak akses (diterapkan oleh AuthFilter):
 *   publik              : /  (beranda), /login, /register
 *   auth (semua role)   : katalog & detail produk, profil
 *   auth:customer       : dashboard customer, pesan produk, pesanan saya
 *   auth:admin,operator : CRUD produk & kategori, kelola pesanan
 *   auth:admin          : hapus produk, kelola user
 */

// ---------------------------------------------------------------
// Halaman publik
// ---------------------------------------------------------------
$routes->get('/', 'Home::index');

// ---------------------------------------------------------------
// Autentikasi (Hands-On Lab minggu 11). Filter 'guest' = hanya untuk yang belum login.
// ---------------------------------------------------------------
$routes->get('register', 'AuthController::register', ['filter' => 'guest']);
$routes->post('register', 'AuthController::store', ['filter' => 'guest']);
$routes->get('login', 'AuthController::login', ['filter' => 'guest']);
$routes->post('login', 'AuthController::authenticate', ['filter' => 'guest']);
$routes->post('logout', 'AuthController::logout', ['filter' => 'auth']);

// ---------------------------------------------------------------
// Wajib login (role apa saja): READ produk + profil
// ---------------------------------------------------------------
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('products', 'ProductController::index', ['as' => 'products']);
    $routes->get('products/(:num)', 'ProductController::detail/$1', ['as' => 'product.detail']);

    $routes->get('profil', 'Profile::index');
    $routes->post('profil', 'Profile::update');
    $routes->post('profil/password', 'Profile::password');
});

// ---------------------------------------------------------------
// Customer: dashboard & pesanan
// ---------------------------------------------------------------
$routes->group('', ['filter' => 'auth:customer'], static function ($routes) {
    $routes->get('dashboard', 'DashboardController::index', ['as' => 'dashboard']);
    $routes->post('products/(:num)/order', 'OrderController::store/$1');
    $routes->get('orders', 'OrderController::index', ['as' => 'orders']);
    $routes->get('orders/(:num)', 'OrderController::show/$1');
    $routes->post('orders/(:num)/cancel', 'OrderController::cancel/$1');
});

// ---------------------------------------------------------------
// Admin & operator: Product Management (CREATE, UPDATE) + kategori + kelola pesanan
// ---------------------------------------------------------------
$routes->group('', ['filter' => 'auth:admin,operator'], static function ($routes) {
    // CRUD produk
    $routes->get('products/create', 'ProductController::create', ['as' => 'product.create']);
    $routes->post('products', 'ProductController::store');
    $routes->get('products/(:num)/edit', 'ProductController::edit/$1');
    $routes->post('products/(:num)/update', 'ProductController::update/$1');

    // CRUD kategori
    $routes->get('categories', 'ProductController::categories', ['as' => 'categories']);
    $routes->post('categories', 'ProductController::storeCategory');
    $routes->post('categories/(:num)/update', 'ProductController::updateCategory/$1');
    $routes->post('categories/(:num)/delete', 'ProductController::deleteCategory/$1');

    // Kelola pesanan semua customer
    $routes->get('manage/orders', 'OrderController::manage', ['as' => 'manage.orders']);
    $routes->post('manage/orders/(:num)/status', 'OrderController::status/$1');
});

// ---------------------------------------------------------------
// Khusus admin: DELETE produk + kelola user
// ---------------------------------------------------------------
$routes->post('products/(:num)/delete', 'ProductController::delete/$1', ['filter' => 'auth:admin']);

$routes->group('admin', ['filter' => 'auth:admin', 'namespace' => 'App\Controllers\Admin'], static function ($routes) {
    $routes->get('/', 'Dashboard::index');
    $routes->get('users', 'Users::index');
    $routes->get('users/create', 'Users::create');
    $routes->post('users', 'Users::store');
    $routes->post('users/(:num)/role', 'Users::role/$1');
    $routes->post('users/(:num)/delete', 'Users::delete/$1');
});

// ---------------------------------------------------------------
// Operator (admin juga boleh melihat)
// ---------------------------------------------------------------
$routes->group('operator', ['filter' => 'auth:operator,admin', 'namespace' => 'App\Controllers\Operator'], static function ($routes) {
    $routes->get('/', 'Dashboard::index');
});
