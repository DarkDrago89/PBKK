<?php /** Dashboard Customer: profil & ringkasan aktivitas. Route: GET /dashboard (filter auth:customer). */ ?>
<?= $this->extend('layouts/store') ?>

<?= $this->section('content') ?>
<?php
$rp      = static fn ($n) => 'Rp' . number_format((int) $n, 0, ',', '.');
$inisial = strtoupper(implode('', array_map(static fn ($w) => mb_substr($w, 0, 1), array_slice(explode(' ', $user->name), 0, 2))));
$aktif   = $ringkasan['menunggu'] + $ringkasan['diproses'] + $ringkasan['dikirim'];
$kartu   = [
    ['Total Pesanan',  $ringkasan['total_pesanan'],     'receipt',        '#6d28d9', '#ede9fe', null],
    ['Sedang Berjalan', $aktif,                         'truck',          '#0284c7', '#e0f2fe', null],
    ['Selesai',        $ringkasan['selesai'],           'check2-circle',  '#059669', '#d1fae5', 'selesai'],
    ['Total Belanja',  $rp($ringkasan['total_belanja']), 'wallet2',       '#ea580c', '#ffedd5', null],
];
?>
<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="se-card p-4 h-100 text-center">
            <span class="se-avatar lg mb-3"><?= esc($inisial) ?></span>
            <h5 class="fw-bold mb-0"><?= esc($user->name) ?></h5>
            <div class="text-muted small mb-2"><?= esc($user->email) ?></div>
            <span class="role-badge role-customer">customer</span>
            <ul class="list-unstyled text-start small mt-4 mb-4">
                <li class="mb-2"><i class="bi bi-telephone text-primary me-2"></i><?= esc($user->phone ?: 'Nomor telepon belum diisi') ?></li>
                <li class="mb-2"><i class="bi bi-geo-alt text-primary me-2"></i><?= esc($user->address ?: 'Alamat belum diisi') ?></li>
                <li><i class="bi bi-calendar-check text-primary me-2"></i>Bergabung <?= date('d M Y', strtotime($user->created_at)) ?></li>
            </ul>
            <a href="<?= site_url('profil') ?>" class="btn btn-outline-primary btn-sm w-100"><i class="bi bi-pencil"></i> Edit Profil</a>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="se-card p-4 mb-4 text-white" style="background: var(--se-gradient); border: 0">
            <h4 class="fw-bold mb-1">Halo, <?= esc(strtok($user->name, ' ')) ?> 👋</h4>
            <p class="mb-0 opacity-75">Ini ringkasan aktivitas belanja Anda. Halaman ini hanya bisa dibuka customer yang sudah login (<code class="text-white">auth:customer</code>).</p>
        </div>
        <div class="row g-3">
            <?php foreach ($kartu as [$label, $nilai, $ikon, $warna, $bg, $filter]): ?>
                <div class="col-sm-6">
                    <a href="<?= site_url('orders' . ($filter ? '?status=' . $filter : '')) ?>" class="se-card stat-card h-100 text-reset">
                        <span class="stat-icon" style="background: <?= $bg ?>; color: <?= $warna ?>"><i class="bi bi-<?= $ikon ?>"></i></span>
                        <div><div class="text-muted small fw-semibold"><?= $label ?></div><div class="stat-value"><?= $nilai ?></div></div>
                    </a>
                </div>
            <?php endforeach ?>
        </div>
    </div>
</div>

<div class="se-card mb-4">
    <div class="d-flex justify-content-between align-items-center p-4 pb-2">
        <h6 class="fw-bold mb-0">Pesanan Terbaru</h6>
        <a href="<?= site_url('orders') ?>" class="small fw-semibold">Semua pesanan &rarr;</a>
    </div>
    <?php if ($terbaru === []): ?>
        <div class="text-center text-muted p-5">
            Belum ada pesanan. <a href="<?= site_url('products') ?>">Mulai belanja</a>.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead><tr><th>Invoice</th><th>Produk</th><th class="text-end">Total</th><th>Status</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($terbaru as $o): ?>
                    <tr>
                        <td><div class="fw-semibold small"><?= esc($o->invoice) ?></div><div class="small text-muted"><?= date('d M Y', strtotime($o->created_at)) ?></div></td>
                        <td class="small"><?= esc($o->product_name) ?> <span class="text-muted">× <?= $o->qty ?></span></td>
                        <td class="text-end fw-semibold small"><?= $rp($o->total) ?></td>
                        <td><?= view('partials/status', ['s' => $o->status]) ?></td>
                        <td class="text-end"><a href="<?= site_url('orders/' . $o->id_order) ?>" class="btn btn-sm btn-light">Detail</a></td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    <?php endif ?>
</div>

<h6 class="fw-bold mb-3">Produk Terbaru untuk Anda</h6>
<div class="row row-cols-2 row-cols-md-4 g-3">
    <?php foreach ($rekomen as $p): ?>
        <div class="col"><?= view('product/_card', ['p' => $p]) ?></div>
    <?php endforeach ?>
</div>
<?= $this->endSection() ?>
