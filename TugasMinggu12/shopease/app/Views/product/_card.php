<?php /** Kartu produk untuk beranda & katalog. @var object $p */ ?>
<a href="<?= site_url('products/' . $p->id_product) ?>" class="se-card prod-card h-100 d-block text-reset">
    <?= view('partials/thumb', ['p' => $p]) ?>
    <div class="p-3">
        <div class="small text-muted"><?= esc($p->category) ?></div>
        <div class="fw-semibold text-truncate"><?= esc($p->name) ?></div>
        <div class="d-flex justify-content-between align-items-center mt-2">
            <span class="fw-bold text-primary">Rp<?= number_format($p->price, 0, ',', '.') ?></span>
            <?php if ((int) $p->stock === 0): ?>
                <span class="badge text-bg-light">Habis</span>
            <?php else: ?>
                <span class="small text-muted">Stok <?= $p->stock ?></span>
            <?php endif ?>
        </div>
    </div>
</a>
