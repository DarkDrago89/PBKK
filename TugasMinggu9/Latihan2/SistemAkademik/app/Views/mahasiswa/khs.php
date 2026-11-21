<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="card shadow mb-4">
    <div class="card-body d-flex flex-wrap justify-content-between align-items-center">
        <div>
            <h5 class="mb-1 font-weight-bold text-gray-800"><?= esc($mhs->nama) ?></h5>
            <div class="text-muted small"><?= esc($mhs->nim) ?> &middot; <?= esc($mhs->prodi) ?> &middot; Angkatan <?= esc($mhs->angkatan) ?></div>
        </div>
        <div class="text-right">
            <div class="text-xs font-weight-bold text-success text-uppercase">IPK</div>
            <div class="h2 mb-0 font-weight-bold text-gray-800"><?= number_format($ipk['ip'], 2) ?></div>
            <small class="text-muted"><?= $ipk['sks'] ?> SKS dinilai</small>
        </div>
        <button onclick="window.print()" class="btn btn-sm btn-outline-secondary no-print mt-2 mt-md-0"><i class="fas fa-print"></i> Cetak</button>
    </div>
</div>

<?php if ($semester === []): ?>
    <div class="alert alert-info">Belum ada data studi.</div>
<?php endif ?>

<?php foreach ($semester as $ta => $rows): ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary"><?= esc($ta) ?></h6>
            <span>IPS: <strong><?= number_format($ips[$ta]['ip'], 2) ?></strong></span>
        </div>
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead class="thead-light">
                <tr><th class="pl-3">Kode</th><th>Mata Kuliah</th><th class="text-center">SKS</th><th class="text-center">Nilai</th><th class="text-center">Huruf</th><th class="text-center">Bobot</th><th class="text-center pr-3">SKS × Bobot</th></tr>
                </thead>
                <tbody>
                <?php foreach ($rows as $r): ?>
                    <?php $bobot = nilai_bobot($r->nilai_huruf); ?>
                    <tr>
                        <td class="pl-3"><?= esc($r->kode) ?></td>
                        <td><?= esc($r->nama) ?></td>
                        <td class="text-center"><?= $r->sks ?></td>
                        <td class="text-center"><?= $r->nilai_angka !== null ? number_format((float) $r->nilai_angka, 0) : '-' ?></td>
                        <td class="text-center"><?= $r->nilai_huruf ? '<strong>' . esc($r->nilai_huruf) . '</strong>' : '<span class="badge badge-light">Belum</span>' ?></td>
                        <td class="text-center"><?= $bobot !== null ? number_format($bobot, 1) : '-' ?></td>
                        <td class="text-center pr-3"><?= $bobot !== null ? number_format($bobot * $r->sks, 1) : '-' ?></td>
                    </tr>
                <?php endforeach ?>
                </tbody>
                <tfoot>
                <tr class="font-weight-bold">
                    <td colspan="2" class="pl-3 text-right">Total (yang sudah dinilai)</td>
                    <td class="text-center"><?= $ips[$ta]['sks'] ?></td>
                    <td colspan="3"></td>
                    <td class="text-center pr-3"><?= number_format($ips[$ta]['mutu'], 1) ?></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
<?php endforeach ?>
<?= $this->endSection() ?>
