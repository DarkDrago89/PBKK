<?= $this->extend('layouts/sbadmin') ?>

<?= $this->section('content') ?>
<div class="card shadow mb-4" style="max-width: 760px">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 text-center mb-3">
                <?= $this->setData(['foto' => $item->foto, 'nama' => $item->nama, 'size' => 'img-fluid shadow'])->include('biodata/_foto') ?>
                <h5 class="mt-3 mb-0"><?= esc($item->nama) ?></h5>
                <small class="text-muted"><?= esc($item->nim) ?></small>
            </div>
            <div class="col-md-8">
                <table class="table table-borderless table-sm">
                    <tr><th style="width:150px">NIM</th><td><?= esc($item->nim) ?></td></tr>
                    <tr><th>Nama</th><td><?= esc($item->nama) ?></td></tr>
                    <tr><th>Jenis Kelamin</th><td><?= $item->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' ?></td></tr>
                    <tr><th>Tempat, Tgl Lahir</th><td><?= esc($item->tempat_lahir) ?>, <?= date('d-m-Y', strtotime($item->tanggal_lahir)) ?></td></tr>
                    <tr><th>Program Studi</th><td><?= esc($item->prodi) ?></td></tr>
                    <tr><th>Email</th><td><?= esc($item->email) ?></td></tr>
                    <tr><th>Alamat</th><td><?= nl2br(esc($item->alamat ?? '-')) ?></td></tr>
                </table>
                <a href="<?= site_url('admin/biodata') ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
                <?php if (session('role') === 'admin'): ?>
                    <a href="<?= site_url('admin/biodata/' . $item->id . '/edit') ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
