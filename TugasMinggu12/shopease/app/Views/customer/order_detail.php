<?php /** Detail pesanan milik customer. Route: GET /orders/{id} (filter auth:customer + cek kepemilikan). */ ?>
<?= $this->extend('layouts/store') ?>

<?= $this->section('content') ?>
<?php
use App\Models\OrderModel;

$rp    = static fn ($n) => 'Rp' . number_format((int) $n, 0, ',', '.');
$alur  = ['menunggu', 'diproses', 'dikirim', 'selesai'];
$posisi = array_search($o->status, $alur, true);
?>
<nav class="small mb-3"><a href="<?= site_url('orders') ?>"><i class="bi bi-arrow-left"></i> Pesanan Saya</a></nav>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="se-card p-4 mb-4">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-4">
                <div>
                    <div class="small text-muted">Nomor Invoice</div>
                    <h5 class="fw-bold mb-0"><?= esc($o->invoice) ?></h5>
                    <div class="small text-muted"><?= date('d M Y, H:i', strtotime($o->created_at)) ?></div>
                </div>
                <?= view('partials/status', ['s' => $o->status]) ?>
            </div>

            <?php if ($o->status === 'dibatalkan'): ?>
                <div class="alert alert-danger mb-0"><i class="bi bi-x-circle"></i> Pesanan ini telah dibatalkan.</div>
            <?php else: ?>
                <div class="order-steps">
                    <?php foreach ($alur as $i => $s): ?>
                        <?php [$label, , $ikon] = OrderModel::BADGE[$s]; ?>
                        <div class="step <?= $i <= $posisi ? 'done' : '' ?>">
                            <span class="dot"><i class="bi bi-<?= $ikon ?>"></i></span>
                            <span class="small fw-semibold"><?= $label ?></span>
                        </div>
                    <?php endforeach ?>
                </div>
            <?php endif ?>
        </div>

        <div class="se-card p-4">
            <h6 class="fw-bold mb-3">Rincian Produk</h6>
            <div class="d-flex gap-3 align-items-center">
                <?php if ($produk): ?>
                    <?= view('partials/thumb', ['p' => (object) ['image' => $produk->image, 'name' => $o->product_name, 'icon' => 'box-seam', 'color' => '#6d28d9'], 'cls' => 'thumb-sm']) ?>
                <?php endif ?>
                <div class="flex-grow-1">
                    <div class="fw-semibold"><?= esc($o->product_name) ?></div>
                    <div class="small text-muted"><?= $o->qty ?> × <?= $rp($o->price) ?> <span class="ms-1">(harga saat dipesan)</span></div>
                    <?php if ($produk && $produk->deleted_at === null && $produk->is_active): ?>
                        <a href="<?= site_url('products/' . $o->id_product) ?>" class="small">Lihat produk</a>
                    <?php endif ?>
                </div>
                <div class="fw-bold fs-5"><?= $rp($o->total) ?></div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="se-card p-4 mb-4">
            <h6 class="fw-bold mb-3">Pengiriman</h6>
            <div class="small text-muted">Penerima</div>
            <div class="fw-semibold mb-2"><?= esc(session('name')) ?></div>
            <div class="small text-muted">Alamat</div>
            <div class="small mb-2" style="white-space: pre-line"><?= esc($o->shipping_address) ?></div>
            <?php if ($o->notes): ?>
                <div class="small text-muted">Catatan</div>
                <div class="small"><?= esc($o->notes) ?></div>
            <?php endif ?>
        </div>

        <?php if (OrderModel::bisaDibatalkan($o)): ?>
            <form action="<?= site_url('orders/' . $o->id_order . '/cancel') ?>" method="post" onsubmit="return confirm('Batalkan pesanan ini?')">
                <?= csrf_field() ?>
                <button class="btn btn-outline-danger w-100"><i class="bi bi-x-circle"></i> Batalkan Pesanan</button>
            </form>
        <?php endif ?>
    </div>
</div>
<?= $this->endSection() ?>
