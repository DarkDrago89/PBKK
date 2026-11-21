<?= $this->extend('layouts/sbadmin') ?>

<?= $this->section('content') ?>
<?php
// Nilai field: prioritas input lama (setelah gagal validasi) -> data dari DB -> kosong
$val    = static fn (string $f) => old($f, $item->{$f} ?? '');
$errors = session('errors') ?? [];
$prodiList = ['Teknik Informatika', 'Sistem Informasi', 'Teknologi Informasi', 'Teknik Komputer'];
?>
<div class="card shadow mb-4" style="max-width: 860px">
    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary"><?= esc($title) ?></h6></div>
    <div class="card-body">

        <?php if ($errors): ?>
            <div class="alert alert-danger">
                <ul class="mb-0 pl-3">
                    <?php foreach ($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach ?>
                </ul>
            </div>
        <?php endif ?>

        <!-- enctype multipart wajib untuk upload file -->
        <form action="<?= $action ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>NIM</label>
                    <input name="nim" class="form-control <?= isset($errors['nim']) ? 'is-invalid' : '' ?>" value="<?= esc($val('nim')) ?>">
                </div>
                <div class="form-group col-md-8">
                    <label>Nama Lengkap</label>
                    <input name="nama" class="form-control <?= isset($errors['nama']) ? 'is-invalid' : '' ?>" value="<?= esc($val('nama')) ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control <?= isset($errors['jenis_kelamin']) ? 'is-invalid' : '' ?>">
                        <option value="">-- Pilih --</option>
                        <option value="L" <?= $val('jenis_kelamin') === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                        <option value="P" <?= $val('jenis_kelamin') === 'P' ? 'selected' : '' ?>>Perempuan</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label>Tempat Lahir</label>
                    <input name="tempat_lahir" class="form-control <?= isset($errors['tempat_lahir']) ? 'is-invalid' : '' ?>" value="<?= esc($val('tempat_lahir')) ?>">
                </div>
                <div class="form-group col-md-4">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control <?= isset($errors['tanggal_lahir']) ? 'is-invalid' : '' ?>" value="<?= esc($val('tanggal_lahir')) ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Program Studi</label>
                    <select name="prodi" class="form-control <?= isset($errors['prodi']) ? 'is-invalid' : '' ?>">
                        <option value="">-- Pilih --</option>
                        <?php foreach ($prodiList as $p): ?>
                            <option <?= $val('prodi') === $p ? 'selected' : '' ?>><?= $p ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" value="<?= esc($val('email')) ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat" rows="2" class="form-control"><?= esc($val('alamat')) ?></textarea>
            </div>
            <div class="form-group">
                <label>Foto <small class="text-muted">(jpg/png/webp, maks 2 MB<?= $item ? ', kosongkan jika tidak diganti' : '' ?>)</small></label>
                <?php if ($item && $item->foto): ?>
                    <div class="mb-2"><img src="<?= base_url('uploads/foto/' . $item->foto) ?>" class="rounded" style="height:80px"></div>
                <?php endif ?>
                <input type="file" name="foto" accept="image/*" class="form-control-file <?= isset($errors['foto']) ? 'is-invalid' : '' ?>">
            </div>

            <a href="<?= site_url('admin/biodata') ?>" class="btn btn-secondary">Batal</a>
            <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
