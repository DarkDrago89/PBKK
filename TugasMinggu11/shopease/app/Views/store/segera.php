<?= $this->extend('layouts/store') ?>

<?= $this->section('content') ?>
<div class="se-card p-5 text-center mx-auto" style="max-width: 640px">
    <span class="stat-icon bg-primary-subtle text-primary mx-auto mb-3" style="width: 72px; height: 72px; font-size: 2rem"><i class="bi bi-<?= $ikon ?>"></i></span>
    <h3 class="fw-bold"><?= esc($title) ?></h3>
    <p class="text-muted mb-1">Halaman ini <strong>hanya bisa dibuka oleh customer yang sudah login</strong> (filter <code>auth:customer</code>).</p>
    <p class="text-muted">Modul <strong><?= esc($modul) ?></strong> dibangun pada milestone <span class="badge badge-soon"><?= esc($m) ?></span> sesuai PRD.</p>
    <a href="<?= site_url('/') ?>" class="btn btn-primary mt-2">Kembali ke Beranda</a>
</div>
<?= $this->endSection() ?>
