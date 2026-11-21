<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="card shadow mb-4">
    <?= $this->setData(['uri' => 'dosen'])->include('admin/_toolbar') ?>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="thead-light"><tr><th class="pl-3">NIP</th><th>Nama</th><th>Email</th><th></th></tr></thead>
            <tbody>
            <?php if ($items === []): ?>
                <tr><td colspan="4" class="text-center text-muted py-4">Data tidak ditemukan.</td></tr>
            <?php endif ?>
            <?php foreach ($items as $r): ?>
                <tr>
                    <td class="pl-3 align-middle"><?= esc($r->nip) ?></td>
                    <td class="align-middle"><?= esc($r->nama) ?></td>
                    <td class="align-middle"><?= esc($r->email) ?></td>
                    <?= $this->setData(['id' => $r->id, 'konfirmasi' => "Hapus {$r->nama}? Mata kuliah yang diampu menjadi tanpa pengampu."])->include('admin/_aksi') ?>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <?= $this->include('admin/_footer') ?>
</div>
<?= $this->endSection() ?>
