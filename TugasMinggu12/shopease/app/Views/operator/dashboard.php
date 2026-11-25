<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<div class="se-card p-4 mb-4 text-white" style="background: var(--se-gradient); border: 0">
    <h4 class="fw-bold mb-1">Halo, <?= esc(session('name')) ?> 👋</h4>
    <p class="mb-0 opacity-75">Anda login sebagai <strong><?= esc(session('role')) ?></strong>. Halaman ini dilindungi filter <code class="text-white">auth:operator,admin</code>.</p>
</div>

<?php
$kartu = [
    ['people',          'Customer Terdaftar',     $customer,                 '#ede9fe', '#6d28d9', null],
    ['hourglass-split', 'Pesanan Menunggu',       $perStatus['menunggu'],    '#fef3c7', '#d97706', 'manage/orders?status=menunggu'],
    ['box-seam',        'Sedang Diproses',        $perStatus['diproses'],    '#e0f2fe', '#0284c7', 'manage/orders?status=diproses'],
    ['exclamation-triangle', 'Stok Menipis',      count($menipis),           '#fee2e2', '#dc2626', null],
];
?>
<div class="row g-3 mb-4">
    <?php foreach ($kartu as [$ikon, $label, $nilai, $bg, $warna, $url]): ?>
        <div class="col-sm-6 col-xl-3">
            <a href="<?= $url ? site_url($url) : '#' ?>" class="se-card stat-card h-100 text-reset">
                <span class="stat-icon" style="background:<?= $bg ?>;color:<?= $warna ?>"><i class="bi bi-<?= $ikon ?>"></i></span>
                <div><div class="text-muted small fw-semibold"><?= $label ?></div><div class="stat-value"><?= $nilai ?></div></div>
            </a>
        </div>
    <?php endforeach ?>
</div>

<div class="row g-3">
    <div class="col-xl-7">
        <div class="se-card h-100">
            <div class="d-flex justify-content-between align-items-center p-4 pb-2">
                <h6 class="fw-bold mb-0">Antrian Pesanan (Menunggu)</h6>
                <a href="<?= site_url('manage/orders?status=menunggu') ?>" class="small fw-semibold">Proses &rarr;</a>
            </div>
            <ul class="list-group list-group-flush pb-2">
                <?php if ($antrian === []): ?><li class="list-group-item text-muted text-center py-4">Tidak ada antrian 🎉</li><?php endif ?>
                <?php foreach ($antrian as $o): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-4">
                        <div>
                            <div class="fw-semibold small"><?= esc($o->product_name) ?> × <?= $o->qty ?></div>
                            <div class="small text-muted"><?= esc($o->invoice) ?> &middot; <?= esc($o->customer) ?></div>
                        </div>
                        <span class="fw-semibold small">Rp<?= number_format($o->total, 0, ',', '.') ?></span>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
    </div>
    <div class="col-xl-5">
        <div class="se-card h-100">
            <div class="d-flex justify-content-between align-items-center p-4 pb-2">
                <h6 class="fw-bold mb-0">Stok Menipis (≤ <?= config('ShopEase')->batasStokMenipis ?>)</h6>
                <a href="<?= site_url('products') ?>" class="small fw-semibold">Kelola &rarr;</a>
            </div>
            <ul class="list-group list-group-flush pb-2">
                <?php if ($menipis === []): ?><li class="list-group-item text-muted text-center py-4">Semua stok aman</li><?php endif ?>
                <?php foreach ($menipis as $p): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-4">
                        <a href="<?= site_url('products/' . $p->id_product . '/edit') ?>" class="small fw-semibold text-reset"><?= esc($p->name) ?></a>
                        <span class="badge rounded-pill <?= (int) $p->stock === 0 ? 'text-bg-danger' : 'text-bg-warning' ?>"><?= (int) $p->stock === 0 ? 'Habis' : $p->stock . ' tersisa' ?></span>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
