<?php
$toko   = config('Toko');
$jmlKer = service('keranjang')->jumlah();
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? '') ?> | <?= esc($toko->nama) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .produk-img { aspect-ratio: 4 / 3; object-fit: cover; }
        .card-produk { transition: transform .15s, box-shadow .15s; }
        .card-produk:hover { transform: translateY(-3px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.12) !important; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100 bg-body-tertiary">

<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="<?= site_url('/') ?>"><i class="bi bi-bag-heart-fill"></i> <?= esc($toko->nama) ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="nav">
            <form class="d-flex mx-lg-auto my-2 my-lg-0 flex-grow-1" style="max-width: 480px" action="<?= site_url('/') ?>" method="get">
                <input class="form-control" type="search" name="q" placeholder="Cari produk..." value="<?= esc(service('request')->getGet('q') ?? '') ?>">
                <button class="btn btn-primary ms-1"><i class="bi bi-search"></i></button>
            </form>
            <ul class="navbar-nav ms-lg-3 align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link position-relative" href="<?= site_url('keranjang') ?>">
                        <i class="bi bi-cart3 fs-5"></i> Keranjang
                        <?php if ($jmlKer > 0): ?>
                            <span class="badge rounded-pill bg-danger"><?= $jmlKer ?></span>
                        <?php endif ?>
                    </a>
                </li>
                <?php if (session('isLoggedIn')): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"><i class="bi bi-person-circle"></i> <?= esc(session('nama')) ?></a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php if (session('role') === 'admin'): ?>
                                <li><a class="dropdown-item" href="<?= site_url('admin') ?>"><i class="bi bi-speedometer2"></i> Panel Admin</a></li>
                            <?php else: ?>
                                <li><a class="dropdown-item" href="<?= site_url('akun/pesanan') ?>"><i class="bi bi-receipt"></i> Pesanan Saya</a></li>
                            <?php endif ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= site_url('logout') ?>"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="btn btn-outline-primary btn-sm ms-lg-2" href="<?= site_url('login') ?>">Masuk</a></li>
                    <li class="nav-item"><a class="btn btn-primary btn-sm ms-lg-2 mt-1 mt-lg-0" href="<?= site_url('register') ?>">Daftar</a></li>
                <?php endif ?>
            </ul>
        </div>
    </div>
</nav>

<main class="flex-grow-1 py-4">
    <div class="container">
        <?php foreach (['success' => 'success', 'error' => 'danger'] as $k => $c): ?>
            <?php if (session($k)): ?>
                <div class="alert alert-<?= $c ?> alert-dismissible fade show"><?= esc(session($k)) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            <?php endif ?>
        <?php endforeach ?>
        <?php if (session('errors')): ?>
            <div class="alert alert-danger"><ul class="mb-0"><?php foreach ((array) session('errors') as $e): ?><li><?= esc($e) ?></li><?php endforeach ?></ul></div>
        <?php endif ?>

        <?= $this->renderSection('content') ?>
    </div>
</main>

<footer class="bg-white border-top py-3 text-center text-muted small">
    PBKK &middot; Latihan 2 &middot; Toko Online dengan CodeIgniter <?= \CodeIgniter\CodeIgniter::CI_VERSION ?>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
