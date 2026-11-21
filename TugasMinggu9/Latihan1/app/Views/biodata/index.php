<?= $this->extend('layouts/sbadmin') ?>

<?= $this->section('content') ?>
<?php $isAdmin = session('role') === 'admin'; ?>
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-wrap align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Biodata Mahasiswa</h6>
        <div class="d-flex mt-2 mt-md-0">
            <form class="form-inline mr-2" method="get">
                <input name="q" class="form-control form-control-sm mr-1" placeholder="Cari NIM / nama / prodi" value="<?= esc($keyword ?? '') ?>">
                <button class="btn btn-sm btn-outline-primary"><i class="fas fa-search"></i></button>
            </form>
            <?php if ($isAdmin): ?>
                <a href="<?= site_url('admin/biodata/create') ?>" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Tambah</a>
            <?php endif ?>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                <tr>
                    <th class="pl-3">Foto</th><th>NIM</th><th>Nama</th><th>L/P</th><th>Prodi</th><th>Email</th>
                    <th class="text-right pr-3">Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php if (empty($items)): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">Data tidak ditemukan.</td></tr>
                <?php endif ?>
                <?php foreach ($items as $row): ?>
                    <tr>
                        <td class="pl-3 align-middle"><?= $this->setData(['foto' => $row->foto, 'nama' => $row->nama, 'size' => 'foto-thumb'])->include('biodata/_foto') ?></td>
                        <td class="align-middle"><?= esc($row->nim) ?></td>
                        <td class="align-middle"><?= esc($row->nama) ?></td>
                        <td class="align-middle"><?= esc($row->jenis_kelamin) ?></td>
                        <td class="align-middle"><?= esc($row->prodi) ?></td>
                        <td class="align-middle"><?= esc($row->email) ?></td>
                        <td class="align-middle text-right pr-3 text-nowrap">
                            <a href="<?= site_url('admin/biodata/' . $row->id) ?>" class="btn btn-sm btn-info" title="Detail"><i class="fas fa-eye"></i></a>
                            <?php if ($isAdmin): ?>
                                <a href="<?= site_url('admin/biodata/' . $row->id . '/edit') ?>" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                <form action="<?= site_url('admin/biodata/' . $row->id . '/delete') ?>" method="post" class="d-inline"
                                      onsubmit="return confirm('Hapus biodata <?= esc($row->nama, 'js') ?>?')">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></button>
                                </form>
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
        <small class="text-muted">Total: <?= $pager->getTotal() ?> data</small>
        <?= $pager->links('default', 'bootstrap') ?>
    </div>
</div>
<?= $this->endSection() ?>
