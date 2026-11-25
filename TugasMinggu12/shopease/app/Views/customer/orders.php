<?php /** Pesanan Saya. Route: GET /orders (filter auth:customer) — hanya pesanan milik user login. */ ?>
<?= $this->extend('layouts/store') ?>

<?= $this->section('content') ?>
<div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-3">
    <div>
        <h4 class="se-section-title mb-0">Pesanan Saya</h4>
        <small class="text-muted"><?= $pager->getTotal() ?> pesanan</small>
    </div>
    <a href="<?= site_url('dashboard') ?>" class="small fw-semibold"><i class="bi bi-grid"></i> Dashboard</a>
</div>

<div class="d-flex flex-wrap gap-2 mb-3">
    <a href="<?= site_url('orders') ?>" class="btn btn-sm rounded-pill <?= $status ? 'btn-light' : 'btn-primary' ?>">Semua</a>
    <?php foreach (\App\Models\OrderModel::BADGE as $s => [$label]): ?>
        <a href="<?= site_url('orders?status=' . $s) ?>" class="btn btn-sm rounded-pill <?= $status === $s ? 'btn-primary' : 'btn-light' ?>"><?= $label ?></a>
    <?php endforeach ?>
</div>

<?php if ($items === []): ?>
    <div class="se-card p-5 text-center text-muted">
        <i class="bi bi-bag display-6 d-block mb-2"></i>
        Belum ada pesanan<?= $status ? ' dengan status ini' : '' ?>. <a href="<?= site_url('products') ?>">Lihat katalog</a>.
    </div>
<?php else: ?>
    <div class="d-grid gap-3">
        <?php foreach ($items as $o): ?>
            <a href="<?= site_url('orders/' . $o->id_order) ?>" class="se-card p-3 px-4 d-flex flex-wrap align-items-center gap-3 text-reset">
                <span class="stat-icon" style="background: #ede9fe; color: #6d28d9"><i class="bi bi-receipt"></i></span>
                <div class="flex-grow-1">
                    <div class="fw-semibold"><?= esc($o->product_name) ?> <span class="text-muted fw-normal">× <?= $o->qty ?></span></div>
                    <div class="small text-muted"><?= esc($o->invoice) ?> &middot; <?= date('d M Y H:i', strtotime($o->created_at)) ?></div>
                </div>
                <div class="fw-bold text-primary">Rp<?= number_format($o->total, 0, ',', '.') ?></div>
                <?= view('partials/status', ['s' => $o->status]) ?>
            </a>
        <?php endforeach ?>
    </div>
    <div class="d-flex justify-content-center mt-4"><?= $pager->links('default', 'shopease') ?></div>
<?php endif ?>
<?= $this->endSection() ?>
