<div class="card card-produk h-100 border-0 shadow-sm">
    <a href="<?= site_url('produk/' . $p->slug) ?>">
        <img src="<?= gambar_produk($p) ?>" class="card-img-top produk-img" alt="<?= esc($p->nama) ?>">
    </a>
    <div class="card-body d-flex flex-column">
        <?php if (! empty($p->kategori_nama)): ?>
            <small class="text-muted"><?= esc($p->kategori_nama) ?></small>
        <?php endif ?>
        <a href="<?= site_url('produk/' . $p->slug) ?>" class="text-decoration-none text-dark fw-semibold stretched-link"><?= esc($p->nama) ?></a>
        <div class="mt-auto pt-2 d-flex justify-content-between align-items-end">
            <span class="fw-bold text-primary"><?= rupiah($p->harga) ?></span>
            <?php if ($p->stok == 0): ?>
                <span class="badge bg-secondary">Habis</span>
            <?php elseif ($p->stok <= 5): ?>
                <span class="badge bg-warning text-dark">Sisa <?= $p->stok ?></span>
            <?php endif ?>
        </div>
    </div>
</div>
