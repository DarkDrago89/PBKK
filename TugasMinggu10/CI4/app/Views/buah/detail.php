<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<a href="<?= url_to('buah') ?>" class="btn btn-link px-0">&larr; Semua buah</a>
<div class="card border-0 shadow-sm mt-2">
    <div class="card-body d-flex gap-4 align-items-center">
        <div style="font-size: 6rem"><?= $b['emoji'] ?></div>
        <div>
            <h1 class="mb-1"><?= esc($b['nama']) ?></h1>
            <span class="badge bg-<?= $b['kategori'] === 'impor' ? 'warning text-dark' : 'success' ?>"><?= $b['kategori'] ?></span>
            <p class="mt-3 mb-1"><?= esc($b['deskripsi']) ?></p>
            <p class="mb-1 text-muted">Asal: <?= esc($b['asal']) ?></p>
            <p class="fs-4 fw-bold text-success mb-0">Rp<?= number_format($b['harga'], 0, ',', '.') ?>/kg</p>
        </div>
    </div>
</div>
<p class="text-muted small mt-3">
    Route <code>GET /buah/(:segment)</code> &rarr; <code>Buah::detail('<?= esc($b['slug']) ?>')</code>:
    segmen URL dikirim router sebagai argumen <code>$slug</code>.
</p>
<?= $this->endSection() ?>
