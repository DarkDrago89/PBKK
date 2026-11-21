<?= $this->extend('layouts/sbadmin') ?>

<?= $this->section('content') ?>
<?php
$cards = [
    ['Total Mahasiswa', $stat['total'],     'primary', 'fa-users'],
    ['Laki-laki',       $stat['laki'],      'info',    'fa-male'],
    ['Perempuan',       $stat['perempuan'], 'danger',  'fa-female'],
    ['Program Studi',   count($stat['prodi']), 'success', 'fa-graduation-cap'],
];
?>
<div class="row">
    <?php foreach ($cards as [$label, $value, $color, $icon]): ?>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-<?= $color ?> shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-<?= $color ?> text-uppercase mb-1"><?= $label ?></div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $value ?></div>
                        </div>
                        <div class="col-auto"><i class="fas <?= $icon ?> fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>

<div class="row">
    <div class="col-lg-7 mb-4">
        <div class="card shadow">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Biodata Terbaru</h6></div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="thead-light"><tr><th class="pl-3">NIM</th><th>Nama</th><th>Prodi</th></tr></thead>
                    <tbody>
                    <?php foreach ($terbaru as $row): ?>
                        <tr>
                            <td class="pl-3"><?= esc($row->nim) ?></td>
                            <td><a href="<?= site_url('admin/biodata/' . $row->id) ?>"><?= esc($row->nama) ?></a></td>
                            <td><?= esc($row->prodi) ?></td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5 mb-4">
        <div class="card shadow">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Mahasiswa per Prodi</h6></div>
            <div class="card-body">
                <?php foreach ($stat['prodi'] as $p): ?>
                    <?php $persen = $stat['total'] > 0 ? round($p->jumlah / $stat['total'] * 100) : 0; ?>
                    <h4 class="small font-weight-bold"><?= esc($p->prodi) ?> <span class="float-right"><?= $p->jumlah ?> (<?= $persen ?>%)</span></h4>
                    <div class="progress mb-3">
                        <div class="progress-bar bg-primary" style="width: <?= $persen ?>%"></div>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
