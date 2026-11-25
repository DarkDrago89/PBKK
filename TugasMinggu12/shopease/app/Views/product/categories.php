<?php /** CRUD kategori dalam satu halaman. Route: /categories (filter auth:admin,operator). */ ?>
<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<?php
$k      = $edit;   // kategori yang sedang diedit, atau null
$action = $k ? site_url('categories/' . $k->id_category . '/update') : site_url('categories');
$ikon   = ['laptop', 'bag-heart', 'smartwatch', 'house-heart', 'droplet-half', 'controller', 'book', 'basket', 'bicycle', 'gift', 'cup-straw', 'flower1'];
?>
<div class="row g-4">
    <div class="col-xl-8">
        <div class="se-card">
            <div class="p-4 pb-2 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">Daftar Kategori</h6>
                <span class="small text-muted"><?= count($items) ?> kategori</span>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead><tr><th>Kategori</th><th>Deskripsi</th><th class="text-center">Produk</th><th class="text-end">Aksi</th></tr></thead>
                    <tbody>
                    <?php foreach ($items as $c): ?>
                        <tr class="<?= $k && $k->id_category === $c->id_category ? 'table-active' : '' ?>">
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="stat-icon" style="width: 40px; height: 40px; font-size: 1.1rem; background: <?= esc($c->color, 'attr') ?>1a; color: <?= esc($c->color, 'attr') ?>"><i class="bi bi-<?= esc($c->icon, 'attr') ?>"></i></span>
                                    <div>
                                        <div class="fw-semibold"><?= esc($c->name) ?></div>
                                        <div class="small text-muted"><?= esc($c->slug) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="small text-muted" style="max-width: 260px"><?= esc($c->description ?: '-') ?></td>
                            <td class="text-center"><a href="<?= site_url('products?kategori=' . $c->id_category) ?>" class="badge rounded-pill text-bg-light"><?= $c->jumlah ?></a></td>
                            <td class="text-end text-nowrap">
                                <a href="<?= site_url('categories?edit=' . $c->id_category) ?>" class="btn btn-sm btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form action="<?= site_url('categories/' . $c->id_category . '/delete') ?>" method="post" class="d-inline"
                                      onsubmit="return confirm(<?= esc(json_encode('Hapus kategori ' . $c->name . '?'), 'attr') ?>)">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="se-card p-4">
            <h6 class="fw-bold mb-3"><?= $k ? 'Edit Kategori' : 'Tambah Kategori' ?></h6>
            <form action="<?= $action ?>" method="post" novalidate>
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Nama Kategori</label>
                    <input name="name" class="form-control" value="<?= esc(old('name', $k->name ?? '')) ?>" placeholder="Contoh: Olahraga">
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-8">
                        <label class="form-label fw-semibold small">Ikon</label>
                        <select name="icon" class="form-select">
                            <?php $pilih = old('icon', $k->icon ?? 'tag'); ?>
                            <?php foreach (array_unique(array_merge($ikon, [$pilih])) as $i): ?>
                                <option value="<?= esc($i, 'attr') ?>" <?= $pilih === $i ? 'selected' : '' ?>><?= esc($i) ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="col-4">
                        <label class="form-label fw-semibold small">Warna</label>
                        <input type="color" name="color" class="form-control form-control-color w-100" value="<?= esc(old('color', $k->color ?? '#6d28d9'), 'attr') ?>">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold small">Deskripsi</label>
                    <textarea name="description" rows="3" class="form-control"><?= esc(old('description', $k->description ?? '')) ?></textarea>
                </div>
                <?php if ($k): ?>
                    <a href="<?= site_url('categories') ?>" class="btn btn-light">Batal</a>
                <?php endif ?>
                <button class="btn btn-primary"><i class="bi bi-<?= $k ? 'check2' : 'plus-lg' ?>"></i> <?= $k ? 'Simpan Perubahan' : 'Tambah Kategori' ?></button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
