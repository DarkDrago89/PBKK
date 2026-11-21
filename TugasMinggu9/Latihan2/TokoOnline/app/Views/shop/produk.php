<?= $this->extend('layouts/shop') ?>

<?= $this->section('content') ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= site_url('/') ?>">Beranda</a></li>
        <?php if ($p->kategori_slug): ?>
            <li class="breadcrumb-item"><a href="<?= site_url('/') ?>?kategori=<?= esc($p->kategori_slug, 'url') ?>"><?= esc($p->kategori_nama) ?></a></li>
        <?php endif ?>
        <li class="breadcrumb-item active"><?= esc($p->nama) ?></li>
    </ol>
</nav>

<div class="card border-0 shadow-sm mb-4">
    <div class="row g-0">
        <div class="col-md-5">
            <img src="<?= gambar_produk($p) ?>" class="img-fluid rounded-start w-100 produk-img" alt="<?= esc($p->nama) ?>">
        </div>
        <div class="col-md-7">
            <div class="card-body p-4">
                <small class="text-muted"><?= esc($p->kategori_nama ?? 'Tanpa kategori') ?></small>
                <h3 class="fw-bold"><?= esc($p->nama) ?></h3>
                <div class="fs-3 fw-bold text-primary mb-3"><?= rupiah($p->harga) ?></div>
                <p><?= nl2br(esc($p->deskripsi ?? '')) ?></p>
                <p class="mb-3">
                    Stok:
                    <?php if ($p->stok > 0): ?>
                        <strong><?= $p->stok ?></strong>
                        <?php if ($diKeranjang): ?><span class="text-muted">(<?= $diKeranjang ?> di keranjang)</span><?php endif ?>
                    <?php else: ?>
                        <span class="badge bg-secondary">Habis</span>
                    <?php endif ?>
                </p>

                <?php if ($p->stok > $diKeranjang): ?>
                    <form action="<?= site_url('keranjang/tambah') ?>" method="post" class="d-flex gap-2" style="max-width: 320px">
                        <?= csrf_field() ?>
                        <input type="hidden" name="produk_id" value="<?= $p->id ?>">
                        <input type="number" name="qty" value="1" min="1" max="<?= $p->stok - $diKeranjang ?>" class="form-control" style="width: 90px">
                        <button class="btn btn-primary flex-grow-1"><i class="bi bi-cart-plus"></i> Tambah ke Keranjang</button>
                    </form>
                <?php else: ?>
                    <button class="btn btn-secondary" disabled>Stok tidak tersedia</button>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>

<?php if ($terkait !== []): ?>
    <h5 class="mb-3">Produk Terkait</h5>
    <div class="row row-cols-2 row-cols-md-4 g-3">
        <?php foreach ($terkait as $t): ?>
            <div class="col"><?= $this->setData(['p' => $t])->include('shop/_kartu_produk') ?></div>
        <?php endforeach ?>
    </div>
<?php endif ?>
<?= $this->endSection() ?>
