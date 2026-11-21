<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<p class="text-muted">Tahun ajaran aktif: <strong><?= esc($ta) ?></strong></p>
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Mata Kuliah Diampu</div>
                <div class="h4 mb-0 font-weight-bold text-gray-800"><?= count($kelas) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Peserta</div>
                <div class="h4 mb-0 font-weight-bold text-gray-800"><?= array_sum(array_column($kelas, 'peserta')) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Belum Dinilai</div>
                <div class="h4 mb-0 font-weight-bold text-gray-800"><?= array_sum(array_column($kelas, 'belum_dinilai')) ?></div>
            </div>
        </div>
    </div>
</div>
<a href="<?= site_url('dosen/kelas') ?>" class="btn btn-primary"><i class="fas fa-chalkboard"></i> Kelola Kelas & Nilai</a>
<?= $this->endSection() ?>
