<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><?= esc($mk->kode) ?> &middot; <?= esc($mk->nama) ?> (<?= $mk->sks ?> SKS)</h6>
        <small class="text-muted">Tahun ajaran <?= esc($ta) ?> &middot; isi nilai angka 0–100, nilai huruf dihitung otomatis</small>
    </div>
    <form method="post" action="<?= site_url("dosen/kelas/{$mk->id}/nilai") ?>">
        <?= csrf_field() ?>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="thead-light"><tr><th class="pl-3">NIM</th><th>Nama</th><th>Prodi</th><th style="width:140px">Nilai Angka</th><th class="text-center">Huruf</th></tr></thead>
                <tbody>
                <?php if ($peserta === []): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada mahasiswa yang mengambil mata kuliah ini.</td></tr>
                <?php endif ?>
                <?php foreach ($peserta as $p): ?>
                    <tr>
                        <td class="pl-3 align-middle"><?= esc($p->nim) ?></td>
                        <td class="align-middle"><?= esc($p->nama) ?></td>
                        <td class="align-middle"><?= esc($p->prodi) ?></td>
                        <td>
                            <input type="number" step="0.01" min="0" max="100" class="form-control form-control-sm"
                                   name="nilai[<?= $p->id ?>]"
                                   value="<?= esc(old("nilai.{$p->id}", $p->nilai_angka !== null ? (float) $p->nilai_angka : '')) ?>">
                        </td>
                        <td class="text-center align-middle">
                            <?= $p->nilai_huruf ? '<span class="badge badge-success p-2">' . esc($p->nilai_huruf) . '</span>' : '<span class="text-muted">-</span>' ?>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">
            <a href="<?= site_url('dosen/kelas') ?>" class="btn btn-secondary">Kembali</a>
            <?php if ($peserta !== []): ?>
                <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan Nilai</button>
            <?php endif ?>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
