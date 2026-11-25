<?php
/**
 * Gambar produk, atau placeholder ikon kategori jika belum ada gambar.
 * @var object $p  (butuh: image, icon, color, name)
 * @var string $cls kelas tambahan (opsional)
 */
$url = \App\Models\ProductModel::imageUrl($p->image ?? null);
?>
<?php if ($url): ?>
    <div class="prod-thumb <?= $cls ?? '' ?>"><img src="<?= esc($url, 'attr') ?>" alt="<?= esc($p->name, 'attr') ?>"></div>
<?php else: ?>
    <div class="prod-thumb <?= $cls ?? '' ?>" style="background: <?= esc($p->color ?? '#6d28d9', 'attr') ?>14; color: <?= esc($p->color ?? '#6d28d9', 'attr') ?>">
        <i class="bi bi-<?= esc($p->icon ?? 'box', 'attr') ?>"></i>
    </div>
<?php endif ?>
