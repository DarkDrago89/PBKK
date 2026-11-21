<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <h1 class="mb-0"><?= esc($title) ?></h1>
    <form action="<?= url_to('buah.cari') ?>" method="get" class="d-flex gap-1">
        <input name="q" class="form-control form-control-sm" placeholder="Cari buah..." value="<?= esc($q ?? '') ?>">
        <button class="btn btn-sm btn-success">Cari</button>
    </form>
    <div class="btn-group">
        <a href="<?= url_to('buah') ?>" class="btn btn-sm btn-outline-success <?= empty($aktif) ? 'active' : '' ?>">Semua</a>
        <?php foreach ($kategori as $k): ?>
            <a href="<?= url_to('buah.kategori', $k) ?>" class="btn btn-sm btn-outline-success <?= ($aktif ?? '') === $k ? 'active' : '' ?>"><?= ucfirst($k) ?></a>
        <?php endforeach ?>
    </div>
</div>

<div class="row g-3">
    <?php foreach ($buah as $b): ?>
        <div class="col-6 col-md-4 col-lg-2">
            <a href="<?= url_to('buah.detail', $b['slug']) ?>" class="card h-100 text-center text-decoration-none text-dark border-0 shadow-sm">
                <div class="card-body">
                    <div style="font-size: 3rem"><?= $b['emoji'] ?></div>
                    <h6 class="mt-2"><?= esc($b['nama']) ?></h6>
                    <span class="badge bg-<?= $b['kategori'] === 'impor' ? 'warning text-dark' : 'success' ?>"><?= $b['kategori'] ?></span>
                </div>
            </a>
        </div>
    <?php endforeach ?>
</div>
<p class="text-muted small mt-4">
    Link dibuat dengan <em>named route</em>: <code>url_to('buah.detail', $slug)</code> dan <code>url_to('buah.kategori', $k)</code>.
</p>
<?= $this->endSection() ?>
