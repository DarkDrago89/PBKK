<?php /** Kelola pesanan semua customer. Route: GET /manage/orders (filter auth:admin,operator). */ ?>
<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<?php use App\Models\OrderModel; ?>
<div class="d-flex flex-wrap gap-2 mb-3">
    <a href="<?= site_url('manage/orders') ?>" class="btn btn-sm rounded-pill <?= $status ? 'btn-light' : 'btn-primary' ?>">Semua (<?= array_sum($perStatus) ?>)</a>
    <?php foreach (OrderModel::BADGE as $s => [$label]): ?>
        <a href="<?= site_url('manage/orders?status=' . $s) ?>" class="btn btn-sm rounded-pill <?= $status === $s ? 'btn-primary' : 'btn-light' ?>"><?= $label ?> (<?= $perStatus[$s] ?>)</a>
    <?php endforeach ?>
</div>

<div class="se-card">
    <form class="d-flex gap-2 p-4 pb-3" method="get">
        <?php if ($status): ?><input type="hidden" name="status" value="<?= esc($status, 'attr') ?>"><?php endif ?>
        <div class="input-icon flex-grow-1" style="max-width: 360px">
            <i class="bi bi-search"></i>
            <input name="q" class="form-control" placeholder="Cari invoice / customer / produk" value="<?= esc($q) ?>">
        </div>
        <button class="btn btn-outline-primary">Cari</button>
    </form>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>Invoice</th><th>Customer</th><th>Produk</th><th class="text-end">Total</th><th>Status</th><th style="width: 190px">Ubah Status</th></tr></thead>
            <tbody>
            <?php if ($items === []): ?>
                <tr><td colspan="6" class="text-center text-muted py-5">Tidak ada pesanan.</td></tr>
            <?php endif ?>
            <?php foreach ($items as $o): ?>
                <tr>
                    <td><div class="fw-semibold small"><?= esc($o->invoice) ?></div><div class="small text-muted"><?= date('d M Y H:i', strtotime($o->created_at)) ?></div></td>
                    <td><div class="small fw-semibold"><?= esc($o->customer) ?></div><div class="small text-muted"><?= esc($o->email) ?></div></td>
                    <td class="small"><?= esc($o->product_name) ?> <span class="text-muted">× <?= $o->qty ?></span></td>
                    <td class="text-end fw-semibold small">Rp<?= number_format($o->total, 0, ',', '.') ?></td>
                    <td><?= view('partials/status', ['s' => $o->status]) ?></td>
                    <td>
                        <?php $next = OrderModel::TRANSISI[$o->status] ?? []; ?>
                        <?php if ($next === []): ?>
                            <span class="small text-muted">Final</span>
                        <?php else: ?>
                            <form action="<?= site_url('manage/orders/' . $o->id_order . '/status') ?>" method="post">
                                <?= csrf_field() ?>
                                <select name="status" class="form-select form-select-sm" onchange="if (this.value) this.form.submit()" aria-label="Ubah status">
                                    <option value="">Ubah ke…</option>
                                    <?php foreach ($next as $s): ?><option value="<?= $s ?>"><?= OrderModel::BADGE[$s][0] ?></option><?php endforeach ?>
                                </select>
                            </form>
                        <?php endif ?>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-between align-items-center p-3 px-4">
        <small class="text-muted">Total <?= $pager->getTotal() ?> pesanan</small>
        <?= $pager->links('default', 'shopease') ?>
    </div>
</div>
<?= $this->endSection() ?>
