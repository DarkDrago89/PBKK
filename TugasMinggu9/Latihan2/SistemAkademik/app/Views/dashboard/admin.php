<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<?php
$cards = [
    ['Mahasiswa',         $jumlah['mahasiswa'],  'primary', 'fa-user-graduate',      'admin/mahasiswa'],
    ['Dosen',             $jumlah['dosen'],      'success', 'fa-chalkboard-teacher', 'admin/dosen'],
    ['Mata Kuliah',       $jumlah['matakuliah'], 'info',    'fa-book',               'admin/matakuliah'],
    ['KRS Semester Ini',  $jumlah['krs'],        'warning', 'fa-clipboard-list',     null],
];
?>
<p class="text-muted">Tahun ajaran aktif: <strong><?= esc($ta) ?></strong></p>
<div class="row">
    <?php foreach ($cards as [$label, $value, $color, $icon, $url]): ?>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-<?= $color ?> shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-<?= $color ?> text-uppercase mb-1"><?= $label ?></div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $value ?></div>
                            <?php if ($url): ?><a href="<?= site_url($url) ?>" class="small">Kelola &rarr;</a><?php endif ?>
                        </div>
                        <div class="col-auto"><i class="fas <?= $icon ?> fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>
<?= $this->endSection() ?>
