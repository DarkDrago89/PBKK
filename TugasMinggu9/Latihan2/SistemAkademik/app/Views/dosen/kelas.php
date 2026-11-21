<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="card shadow mb-4">
    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Mata Kuliah Diampu &middot; <?= esc($ta) ?></h6></div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="thead-light"><tr><th class="pl-3">Kode</th><th>Mata Kuliah</th><th>SKS</th><th>Peserta</th><th>Belum Dinilai</th><th></th></tr></thead>
            <tbody>
            <?php if ($kelas === []): ?>
                <tr><td colspan="6" class="text-center text-muted py-4">Anda belum mengampu mata kuliah.</td></tr>
            <?php endif ?>
            <?php foreach ($kelas as $k): ?>
                <tr>
                    <td class="pl-3 align-middle"><?= esc($k->kode) ?></td>
                    <td class="align-middle"><?= esc($k->nama) ?></td>
                    <td class="align-middle"><?= $k->sks ?></td>
                    <td class="align-middle"><?= (int) $k->peserta ?></td>
                    <td class="align-middle">
                        <?= (int) $k->belum_dinilai > 0 ? '<span class="badge badge-warning">' . (int) $k->belum_dinilai . '</span>' : '<span class="badge badge-success">Lengkap</span>' ?>
                    </td>
                    <td class="text-right pr-3 align-middle">
                        <a href="<?= site_url("dosen/kelas/{$k->id}") ?>" class="btn btn-sm btn-primary"><i class="fas fa-pen"></i> Input Nilai</a>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
