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
                    <label>NIM <small class="text-muted">(10 digit)</small></label>
                    <input name="nim" class="form-control" value="<?= esc($val('nim')) ?>" maxlength="10">
                </div>
                <div class="form-group col-md-8">
                    <label>Nama Lengkap</label>
                    <input name="nama" class="form-control" value="<?= esc($val('nama')) ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Program Studi</label>
                    <?= form_dropdown('prodi', ['' => '-- Pilih --'] + array_combine($prodiList, $prodiList), $val('prodi'), ['class' => 'form-control']) ?>
                </div>
                <div class="form-group col-md-6">
                    <label>Angkatan</label>
                    <input type="number" name="angkatan" class="form-control" value="<?= esc($val('angkatan') ?: date('Y')) ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?= esc($val('email')) ?>">
            </div>
            <?php if ($item === null): ?>
                <p class="small text-muted"><i class="fas fa-info-circle"></i> Akun login dibuat otomatis: username &amp; password = NIM.</p>
            <?php endif ?>
            <a href="<?= site_url('admin/mahasiswa') ?>" class="btn btn-secondary">Batal</a>
            <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
