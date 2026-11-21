<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="card shadow mb-4">
    <?= $this->setData(['uri' => 'matakuliah'])->include('admin/_toolbar') ?>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="thead-light"><tr><th class="pl-3">Kode</th><th>Mata Kuliah</th><th>SKS</th><th>Semester</th><th>Pengampu</th><th></th></tr></thead>
            <tbody>
            <?php if ($items === []): ?>
                <tr><td colspan="6" class="text-center text-muted py-4">Data tidak ditemukan.</td></tr>
            <?php endif ?>
            <?php foreach ($items as $r): ?>
                <tr>
                    <td class="pl-3 align-middle"><?= esc($r->kode) ?></td>
                    <td class="align-middle"><?= esc($r->nama) ?></td>
                    <td class="align-middle"><?= esc($r->sks) ?></td>
                    <td class="align-middle"><?= esc($r->semester) ?></td>
                    <td class="align-middle"><?= $r->dosen_nama ? esc($r->dosen_nama) : '<span class="badge badge-secondary">Belum ada</span>' ?></td>
                    <?= $this->setData(['id' => $r->id, 'konfirmasi' => "Hapus {$r->nama}? Semua KRS mata kuliah ini ikut terhapus."])->include('admin/_aksi') ?>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <?= $this->include('admin/_footer') ?>
</div>
<?= $this->endSection() ?>
