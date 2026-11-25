<?php
$login = (bool) session('isLoggedIn');
$nama  = (string) session('name');
$inisial = strtoupper(implode('', array_map(static fn ($w) => mb_substr($w, 0, 1), array_slice(explode(' ', trim($nama)), 0, 2))));
?>
<!doctype html>
<html lang="id">
<head>
    <?= $this->include('partials/head') ?>
</head>
<body class="d-flex flex-column min-vh-100">
<nav class="navbar navbar-expand-lg se-navbar sticky-top py-2">
    <div class="container">
        <a class="navbar-brand" href="<?= site_url('/') ?>"><?= $this->setData(['light' => false, 'size' => 38])->include('partials/logo') ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav ms-lg-4 me-auto">
                <li class="nav-item"><a class="nav-link <?= uri_string() === '' ? 'active' : '' ?>" href="<?= site_url('/') ?>">Beranda</a></li>
                <li class="nav-item"><a class="nav-link <?= str_starts_with(uri_string(), 'products') ? 'active' : '' ?>" href="<?= site_url('products') ?>">Produk</a></li>
                <?php if ($login && session('role') === 'customer'): ?>
                    <li class="nav-item"><a class="nav-link <?= uri_string() === 'dashboard' ? 'active' : '' ?>" href="<?= site_url('dashboard') ?>">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link <?= str_starts_with(uri_string(), 'orders') ? 'active' : '' ?>" href="<?= site_url('orders') ?>">Pesanan Saya</a></li>
                <?php endif ?>
            </ul>
            <form action="<?= site_url('products') ?>" method="get" class="position-relative se-search flex-grow-1 me-lg-3 my-2 my-lg-0">
                <i class="bi bi-search position-absolute text-muted" style="left: 1rem; top: 50%; transform: translateY(-50%)"></i>
                <input name="q" class="form-control" placeholder="Cari produk…" value="<?= esc($q ?? '') ?>">
            </form>
            <div class="d-flex align-items-center gap-2">
                <?php if (! $login || session('role') === 'customer'): ?>
                    <a href="<?= site_url('orders') ?>" class="se-icon-btn" title="Pesanan Saya"><i class="bi bi-bag-check fs-5"></i></a>
                <?php endif ?>
                <?php if ($login): ?>
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center gap-2 text-dark" data-bs-toggle="dropdown">
                            <span class="se-avatar"><?= esc($inisial) ?></span>
                            <span class="d-none d-xl-inline fw-semibold small"><?= esc($nama) ?></span>
                            <i class="bi bi-chevron-down small"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                            <li><span class="dropdown-item-text small text-muted"><?= esc(session('email')) ?></span></li>
                            <li><hr class="dropdown-divider"></li>
                            <?php if (session('role') !== 'customer'): ?>
                                <li><a class="dropdown-item" href="<?= service('auth')->homeFor(session('role')) ?>"><i class="bi bi-speedometer2 me-2"></i>Dashboard <?= esc(session('role')) ?></a></li>
                            <?php endif ?>
                            <li><a class="dropdown-item" href="<?= site_url('profil') ?>"><i class="bi bi-person me-2"></i>Profil Saya</a></li>
                            <?php if (session('role') === 'customer'): ?>
                                <li><a class="dropdown-item" href="<?= site_url('dashboard') ?>"><i class="bi bi-grid me-2"></i>Dashboard Saya</a></li>
                                <li><a class="dropdown-item" href="<?= site_url('orders') ?>"><i class="bi bi-receipt me-2"></i>Pesanan Saya</a></li>
                            <?php endif ?>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="<?= site_url('logout') ?>" method="post">
                                    <?= csrf_field() ?>
                                    <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="<?= site_url('login') ?>" class="btn btn-outline-primary btn-sm px-3">Masuk</a>
                    <a href="<?= site_url('register') ?>" class="btn btn-primary btn-sm px-3">Daftar</a>
                <?php endif ?>
            </div>
        </div>
    </div>
</nav>

<main class="flex-grow-1 py-4">
    <div class="container">
        <?= $this->include('partials/flash') ?>
        <?= $this->renderSection('content') ?>
    </div>
</main>

<footer class="se-footer py-4 mt-4">
    <div class="container d-flex flex-wrap justify-content-between gap-3 small">
        <div><?= $this->setData(['light' => true, 'size' => 30])->include('partials/logo') ?></div>
        <div class="align-self-center">PBKK &middot; Tugas Minggu 12 &middot; Authentication + Product Management &middot; CodeIgniter <?= \CodeIgniter\CodeIgniter::CI_VERSION ?></div>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
