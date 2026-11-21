<?php
$uri  = uri_string();
$role = session('role');
$nama = (string) session('name');
$inisial = strtoupper(implode('', array_map(static fn ($w) => mb_substr($w, 0, 1), array_slice(explode(' ', trim($nama)), 0, 2))));

// Menu per role: [url, ikon, label, sudah dibuat?]
$menu = [
    'admin' => [
        'Utama'      => [['admin', 'speedometer2', 'Dashboard', true], ['admin/users', 'people', 'Kelola User (Role)', true], ['operator', 'box-seam', 'Panel Operator', true]],
        'Toko (M3–M4)' => [['#', 'tags', 'Kelola Kategori', false], ['#', 'bag', 'Kelola Produk', false], ['#', 'receipt', 'Kelola Pesanan', false], ['#', 'credit-card', 'Verifikasi Pembayaran', false], ['#', 'graph-up', 'Laporan Penjualan', false]],
    ],
    'operator' => [
        'Utama'        => [['operator', 'speedometer2', 'Dashboard', true]],
        'Pesanan (M4)' => [['#', 'clipboard-check', 'Kelola Pesanan', false], ['#', 'truck', 'Kelola Pengiriman', false]],
    ],
][$role] ?? [];
?>
<!doctype html>
<html lang="id">
<head>
    <?= $this->include('partials/head') ?>
</head>
<body>
<div class="panel">
    <aside class="panel-side">
        <a href="<?= service('auth')->homeFor($role) ?>" class="px-2 mb-2"><?= $this->setData(['light' => true, 'size' => 36])->include('partials/logo') ?></a>
        <nav class="flex-grow-1">
            <?php foreach ($menu as $judul => $items): ?>
                <div class="side-label"><?= $judul ?></div>
                <?php foreach ($items as [$url, $ikon, $label, $siap]): ?>
                    <?php $aktif = $siap && ($uri === $url || ($url !== 'admin' && $url !== 'operator' && str_starts_with($uri, $url))); ?>
                    <a href="<?= $siap ? site_url($url) : '#' ?>" class="side-link <?= $aktif ? 'active' : '' ?> <?= $siap ? '' : 'disabled' ?>">
                        <i class="bi bi-<?= $ikon ?>"></i> <?= $label ?>
                        <?php if (! $siap): ?><span class="badge badge-soon">segera</span><?php endif ?>
                    </a>
                <?php endforeach ?>
            <?php endforeach ?>
            <div class="side-label">Akun</div>
            <a href="<?= site_url('profil') ?>" class="side-link <?= $uri === 'profil' ? 'active' : '' ?>"><i class="bi bi-person"></i> Profil Saya</a>
            <a href="<?= site_url('/') ?>" class="side-link"><i class="bi bi-shop"></i> Lihat Toko</a>
        </nav>
        <form action="<?= site_url('logout') ?>" method="post">
            <?= csrf_field() ?>
            <button class="side-link w-100 border-0 bg-transparent"><i class="bi bi-box-arrow-right"></i> Logout</button>
        </form>
    </aside>

    <div class="panel-main">
        <header class="panel-top">
            <div>
                <div class="small text-muted"><?= ucfirst(esc($role)) ?> Panel</div>
                <h5 class="fw-bold mb-0"><?= esc($title ?? '') ?></h5>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="text-end d-none d-sm-block">
                    <div class="fw-semibold small"><?= esc($nama) ?></div>
                    <span class="role-badge role-<?= esc($role) ?>"><?= esc($role) ?></span>
                </div>
                <span class="se-avatar"><?= esc($inisial) ?></span>
            </div>
        </header>
        <div class="panel-body">
            <?= $this->include('partials/flash') ?>
            <?= $this->renderSection('content') ?>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
