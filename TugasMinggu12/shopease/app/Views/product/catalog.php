<?php /** READ (customer): katalog produk aktif. Route: GET /products (filter auth). */ ?>
<?= $this->extend('layouts/store') ?>

<?= $this->section('content') ?>
<div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-3">
    <div>
        <h4 class="se-section-title mb-0">Katalog Produk</h4>
        <small class="text-muted">
            <?= $pager->getTotal() ?> produk<?= $q !== '' ? ' untuk "' . esc($q) . '"' : '' ?>
        </small>
    </div>
    <?php if ($q !== '' || $idKat): ?>
        <a href="<?= site_url('products') ?>" class="small fw-semibold"><i class="bi bi-x-circle"></i> Hapus filter</a>
    <?php endif ?>
</div>

<div class="d-flex flex-wrap gap-2 mb-4">
    <a href="<?= site_url('products' . ($q !== '' ? '?q=' . urlencode($q) : '')) ?>" class="btn btn-sm rounded-pill <?= $idKat ? 'btn-light' : 'btn-primary' ?>">Semua</a>
    <?php foreach ($kategori as $k): ?>
        <a href="<?= site_url('products?' . http_build_query(array_filter(['q' => $q, 'kategori' => $k->id_category]))) ?>"
           class="btn btn-sm rounded-pill <?= (int) $idKat === (int) $k->id_category ? 'btn-primary' : 'btn-light' ?>">
            <i class="bi bi-<?= esc($k->icon, 'attr') ?>"></i> <?= esc($k->name) ?>
        </a>
    <?php endforeach ?>
</div>

<?php if ($items === []): ?>
    <div class="se-card p-5 text-center text-muted">
        <i class="bi bi-search display-6 d-block mb-2"></i>
        Tidak ada produk yang cocok.
    </div>
<?php else: ?>
    <div class="row row-cols-2 row-cols-md-3 row-cols-xl-4 g-3">
        <?php foreach ($items as $p): ?>
            <div class="col"><?= view('product/_card', ['p' => $p]) ?></div>
        <?php endforeach ?>
    </div>
    <div class="d-flex justify-content-center mt-4"><?= $pager->links('default', 'shopease') ?></div>
<?php endif ?>
<?= $this->endSection() ?>
