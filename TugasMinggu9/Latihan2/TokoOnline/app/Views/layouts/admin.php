<?php
$sb   = 'https://cdn.jsdelivr.net/npm/startbootstrap-sb-admin-2@4.1.4/';
$uri  = uri_string();
$menu = [
    ['admin',          'fa-tachometer-alt', 'Dashboard'],
    ['admin/pesanan',  'fa-receipt',        'Pesanan'],
    ['admin/produk',   'fa-box',            'Produk'],
    ['admin/kategori', 'fa-tags',           'Kategori'],
];
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= esc($title ?? 'Admin') ?> | Admin <?= esc(config('Toko')->nama) ?></title>
    <link href="<?= $sb ?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,600,700,800" rel="stylesheet">
    <link href="<?= $sb ?>css/sb-admin-2.min.css" rel="stylesheet">
    <style>.thumb{width:48px;height:36px;object-fit:cover}</style>
</head>
<body id="page-top">
<div id="wrapper">
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= site_url('admin') ?>">
            <div class="sidebar-brand-icon rotate-n-15"><i class="fas fa-store"></i></div>
            <div class="sidebar-brand-text mx-3"><?= esc(config('Toko')->nama) ?></div>
        </a>
        <hr class="sidebar-divider my-0">
        <?php foreach ($menu as [$url, $icon, $label]): ?>
            <?php $aktif = $url === 'admin' ? $uri === 'admin' : str_starts_with($uri, $url); ?>
            <li class="nav-item <?= $aktif ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url($url) ?>"><i class="fas fa-fw <?= $icon ?>"></i><span><?= $label ?></span></a>
            </li>
        <?php endforeach ?>
        <hr class="sidebar-divider">
        <li class="nav-item">
            <a class="nav-link" href="<?= site_url('/') ?>" target="_blank"><i class="fas fa-fw fa-external-link-alt"></i><span>Lihat Toko</span></a>
        </li>
        <hr class="sidebar-divider d-none d-md-block">
        <div class="text-center d-none d-md-inline"><button class="rounded-circle border-0" id="sidebarToggle"></button></div>
    </ul>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3"><i class="fa fa-bars"></i></button>
                <h1 class="h5 mb-0 text-gray-800"><?= esc($title ?? '') ?></h1>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= esc(session('nama')) ?></span>
                            <i class="fas fa-user-circle fa-2x text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow">
                            <a class="dropdown-item" href="<?= site_url('logout') ?>"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Logout</a>
                        </div>
                    </li>
                </ul>
            </nav>

            <div class="container-fluid">
                <?php foreach (['success' => 'success', 'error' => 'danger'] as $k => $c): ?>
                    <?php if (session($k)): ?>
                        <div class="alert alert-<?= $c ?> alert-dismissible fade show"><?= esc(session($k)) ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>
                    <?php endif ?>
                <?php endforeach ?>
                <?php if (session('errors')): ?>
                    <div class="alert alert-danger"><ul class="mb-0 pl-3"><?php foreach ((array) session('errors') as $e): ?><li><?= esc($e) ?></li><?php endforeach ?></ul></div>
                <?php endif ?>

                <?= $this->renderSection('content') ?>
            </div>
        </div>
        <footer class="sticky-footer bg-white">
            <div class="container my-auto"><div class="copyright text-center my-auto"><span>PBKK &middot; Latihan 2 &middot; Toko Online</span></div></div>
        </footer>
    </div>
</div>
<script src="<?= $sb ?>vendor/jquery/jquery.min.js"></script>
<script src="<?= $sb ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= $sb ?>vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="<?= $sb ?>js/sb-admin-2.min.js"></script>
</body>
</html>
