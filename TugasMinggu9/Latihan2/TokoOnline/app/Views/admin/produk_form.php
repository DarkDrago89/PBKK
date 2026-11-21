<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<?php $val = static fn (string $f) => old($f, $p->{$f} ?? ''); ?>
<div class="card shadow mb-4" style="max-width: 860px">
    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary"><?= esc($title) ?></h6></div>
    <div class="card-body">
        <form action="<?= $action ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="form-row">
                <div class="form-group col-md-8">
                    <label>Nama Produk</label>
                    <input name="nama" class="form-control" value="<?= esc($val('nama')) ?>">
                </div>
                <div class="form-group col-md-4">
                    <label>Kategori</label>
                    <?= form_dropdown('kategori_id', ['' => '-- Pilih --'] + $kategori, (string) $val('kategori_id'), ['class' => 'form-control']) ?>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Harga (Rp)</label>
                    <input type="number" name="harga" min="1" class="form-control" value="<?= esc($val('harga')) ?>">
                </div>
                <div class="form-group col-md-6">
                    <label>Stok</label>
                    <input type="number" name="stok" min="0" class="form-control" value="<?= esc($val('stok')) ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" rows="4" class="form-control"><?= esc($val('deskripsi')) ?></textarea>
            </div>
            <div class="form-group">
                <label>Gambar <small class="text-muted">(jpg/png/webp, maks 2 MB<?= $p ? ', kosongkan jika tidak diganti' : '' ?>)</small></label>
                <?php if ($p): ?><div class="mb-2"><img src="<?= gambar_produk($p) ?>" class="rounded" style="height:90px"></div><?php endif ?>
                <input type="file" name="gambar" accept="image/*" class="form-control-file">
            </div>
            <a href="<?= site_url('admin/produk') ?>" class="btn btn-secondary">Batal</a>
            <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
