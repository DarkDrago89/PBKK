<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<?php $persen = min(100, $totalSks / $maxSks * 100); ?>
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">KRS Tahun Ajaran <?= esc($ta) ?></h6>
        <span class="badge badge-<?= $totalSks >= $maxSks ? 'danger' : 'primary' ?> p-2"><?= $totalSks ?> / <?= $maxSks ?> SKS</span>
    </div>
    <div class="progress rounded-0" style="height:6px"><div class="progress-bar" style="width: <?= $persen ?>%"></div></div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead class="thead-light"><tr><th class="pl-3">Kode</th><th>Mata Kuliah</th><th>SKS</th><th>Dosen</th><th>Status</th><th></th></tr></thead>
            <tbody>
            <?php if ($diambil === []): ?>
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada mata kuliah yang diambil. Pilih dari daftar di bawah.</td></tr>
            <?php endif ?>
            <?php foreach ($diambil as $r): ?>
                <tr>
                    <td class="pl-3 align-middle"><?= esc($r->kode) ?></td>
                    <td class="align-middle"><?= esc($r->nama) ?></td>
                    <td class="align-middle"><?= $r->sks ?></td>
                    <td class="align-middle"><?= esc($r->dosen_nama ?? '-') ?></td>
                    <td class="align-middle">
                        <?= $r->nilai_huruf ? '<span class="badge badge-success">Dinilai: ' . esc($r->nilai_huruf) . '</span>' : '<span class="badge badge-light">Terdaftar</span>' ?>
                    </td>
                    <td class="text-right pr-3 align-middle">
                        <?php if ($r->nilai_huruf === null): ?>
                            <form method="post" action="<?= site_url("mahasiswa/krs/{$r->id}/batal") ?>" onsubmit="return confirm('Batalkan mata kuliah ini?')">
                                <?= csrf_field() ?>
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-times"></i> Batal</button>
                            </form>
                        <?php endif ?>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Mata Kuliah Tersedia</h6></div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="thead-light"><tr><th class="pl-3">Kode</th><th>Mata Kuliah</th><th>SKS</th><th>Smt</th><th>Dosen</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($tersedia as $mk): ?>
                <?php $melebihi = $totalSks + $mk->sks > $maxSks; ?>
                <tr class="<?= $melebihi ? 'text-muted' : '' ?>">
                    <td class="pl-3 align-middle"><?= esc($mk->kode) ?></td>
                    <td class="align-middle"><?= esc($mk->nama) ?></td>
                    <td class="align-middle"><?= $mk->sks ?></td>
                    <td class="align-middle"><?= $mk->semester ?></td>
                    <td class="align-middle"><?= esc($mk->dosen_nama ?? '-') ?></td>
                    <td class="text-right pr-3 align-middle">
                        <form method="post" action="<?= site_url('mahasiswa/krs/ambil') ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="matakuliah_id" value="<?= $mk->id ?>">
                            <button class="btn btn-sm btn-success" <?= $melebihi ? 'title="Melebihi batas SKS"' : '' ?>><i class="fas fa-plus"></i> Ambil</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
