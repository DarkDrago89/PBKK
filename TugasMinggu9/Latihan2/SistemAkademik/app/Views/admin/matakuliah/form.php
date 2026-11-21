<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<?php $val = static fn (string $f) => old($f, $item->{$f} ?? ''); ?>
<div class="card shadow mb-4" style="max-width: 760px">
    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary"><?= esc($title) ?></h6></div>
    <div class="card-body">
        <form action="<?= $action ?>" method="post">
            <?= csrf_field() ?>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Kode</label>
                    <input name="kode" class="form-control text-uppercase" value="<?= esc($val('kode')) ?>" maxlength="10">
                </div>
                <div class="form-group col-md-8">
                    <label>Nama Mata Kuliah</label>
                    <input name="nama" class="form-control" value="<?= esc($val('nama')) ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>SKS</label>
                    <?= form_dropdown('sks', ['' => '--', 1 => 1, 2 => 2, 3 => 3, 4 => 4, 6 => 6], (string) $val('sks'), ['class' => 'form-control']) ?>
                </div>
                <div class="form-group col-md-3">
                    <label>Semester</label>
                    <?= form_dropdown('semester', ['' => '--'] + array_combine(range(1, 8), range(1, 8)), (string) $val('semester'), ['class' => 'form-control']) ?>
                </div>
                <div class="form-group col-md-6">
                    <label>Dosen Pengampu</label>
                    <?= form_dropdown('dosen_id', ['' => '-- Belum ditentukan --'] + $dosenList, (string) $val('dosen_id'), ['class' => 'form-control']) ?>
                </div>
            </div>
            <a href="<?= site_url('admin/matakuliah') ?>" class="btn btn-secondary">Batal</a>
            <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
