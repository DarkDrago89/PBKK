<?php /** READ (admin/operator): tabel produk dengan opsi edit & hapus. Route: GET /products. */ ?>
<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<?php $isAdmin = session('role') === 'admin'; ?>
<div class="se-card">
    <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center p-4 pb-3">
        <form class="d-flex flex-wrap gap-2" method="get">
            <div class="input-icon">
                <i class="bi bi-search"></i>
                <input name="q" class="form-control" placeholder="Cari nama / deskripsi" value="<?= esc($q) ?>">
            </div>
            <select name="kategori" class="form-select" style="width: auto" onchange="this.form.submit()">
                <option value="">Semua kategori</option>
                <?php foreach ($kategori as $k): ?>
                    <option value="<?= $k->id_category ?>" <?= (int) $idKat === (int) $k->id_category ? 'selected' : '' ?>><?= esc($k->name) ?> (<?= $k->jumlah ?>)</option>
                <?php endforeach ?>
            </select>
            <button class="btn btn-outline-primary">Cari</button>
        </form>
        <div class="d-flex gap-2">
            <a href="<?= site_url('categories') ?>" class="btn btn-light"><i class="bi bi-tags"></i> Kategori</a>
            <a href="<?= site_url('products/create') ?>" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Produk</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>Produk</th><th>Kategori</th><th class="text-end">Harga</th><th class="text-center">Stok</th><th>Status</th><th class="text-end" style="width: 150px">Aksi</th></tr></thead>
            <tbody>
            <?php if ($items === []): ?>
                <tr><td colspan="6" class="text-center text-muted py-5">Tidak ada produk yang cocok.</td></tr>
            <?php endif ?>
            <?php foreach ($items as $p): ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <?= view('partials/thumb', ['p' => $p, 'cls' => 'thumb-sm']) ?>
                            <div class="min-w-0">
                                <a href="<?= site_url('products/' . $p->id_product) ?>" class="fw-semibold text-reset"><?= esc($p->name) ?></a>
                                <div class="small text-muted">#<?= $p->id_product ?> &middot; <?= esc($p->slug) ?></div>
                            </div>
                        </div>
                    </td>
                    <td><span class="small fw-semibold" style="color: <?= esc($p->color, 'attr') ?>"><i class="bi bi-<?= esc($p->icon, 'attr') ?>"></i> <?= esc($p->category) ?></span></td>
                    <td class="text-end fw-semibold">Rp<?= number_format($p->price, 0, ',', '.') ?></td>
                    <td class="text-center">
                        <?php $stok = (int) $p->stock; ?>
                        <span class="badge rounded-pill <?= $stok === 0 ? 'text-bg-danger' : ($stok <= config('ShopEase')->batasStokMenipis ? 'text-bg-warning' : 'text-bg-light') ?>"><?= $stok ?></span>
                    </td>
                    <td><?= $p->is_active ? '<span class="badge bg-success-subtle text-success-emphasis">Tampil</span>' : '<span class="badge text-bg-secondary">Disembunyikan</span>' ?></td>
                    <td class="text-end text-nowrap">
                        <a href="<?= site_url('products/' . $p->id_product) ?>" class="btn btn-sm btn-light" title="Detail"><i class="bi bi-eye"></i></a>
                        <a href="<?= site_url('products/' . $p->id_product . '/edit') ?>" class="btn btn-sm btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                        <?php if ($isAdmin): ?>
                            <form action="<?= site_url('products/' . $p->id_product . '/delete') ?>" method="post" class="d-inline"
                                  onsubmit="return confirm(<?= esc(json_encode('Hapus produk ' . $p->name . '?'), 'attr') ?>)">
                                <?= csrf_field() ?>
                                <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                            </form>
                        <?php else: ?>
                            <!-- operator: tombol hapus disembunyikan; route DELETE juga difilter auth:admin -->
                            <button class="btn btn-sm btn-outline-secondary" disabled title="Hapus produk hanya untuk admin"><i class="bi bi-lock"></i></button>
                        <?php endif ?>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-between align-items-center p-3 px-4">
        <small class="text-muted">Total <?= $pager->getTotal() ?> produk</small>
        <?= $pager->links('default', 'shopease') ?>
    </div>
</div>
<?= $this->endSection() ?>
