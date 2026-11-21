<?php

use CodeIgniter\Router\RouteCollection;

/**
 * Router: memetakan URL -> Controller::method
 *
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// LANGKAH 1: route ke controller Page
$routes->get('/about', 'Page::about');
$routes->get('/contact', 'Page::contact');
$routes->get('/faqs', 'Page::faqs');

// LANGKAH 2d: auto routing dimatikan -> /info/tos kembali 404
// (true = setiap method public bisa diakses lewat URL, false = hanya route di file ini)
$routes->setAutoRoute(false);

// LANGKAH 3: halaman Term of Service kini didaftarkan manual
$routes->get('/tos', 'Info::getTos');

// LANGKAH 3: route group + placeholder + named route
$routes->group('buah', static function ($routes) {
    $routes->get('/', 'Buah::index', ['as' => 'buah']);                               // /buah
    $routes->get('kategori/(:alpha)', 'Buah::kategori/$1', ['as' => 'buah.kategori']); // /buah/kategori/impor
    $routes->get('cari', 'Buah::cari', ['as' => 'buah.cari']);                         // /buah/cari?q=apel
    // (:segment) harus PALING BAWAH: kalau di atas, "cari" ikut tertangkap sebagai slug
    $routes->get('(:segment)', 'Buah::detail/$1', ['as' => 'buah.detail']);            // /buah/apel-malang
});
