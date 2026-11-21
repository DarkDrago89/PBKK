<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-wrap justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Produk</h6>
        <div class="d-flex">
            <form class="form-inline mr-2" method="get">
                <input name="q" class="form-control form-control-sm mr-1" placeholder="Cari produk / kategori" value="<?= esc($q ?? '') ?>">
                <button class="btn btn-sm btn-outline-primary"><i class="fas fa-search"></i></button>
            </form>
            <a href="<?= site_url('admin/produk/create') ?>" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Tambah</a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="thead-light"><tr><th class="pl-3"></th><th>Nama</th><th>Kategori</th><th class="text-right">Harga</th><th class="text-center">Stok</th><th></th></tr></thead>
            <tbody>
            <?php if ($produk === []): ?>
                <tr><td colspan="6" class="text-center text-muted py-4">Produk tidak ditemukan.</td></tr>
            <?php endif ?>
            <?php foreach ($produk as $p): ?>
                <tr>
                    <td class="pl-3 align-middle"><img src="<?= gambar_produk($p) ?>" class="rounded thumb" alt=""></td>
                    <td class="align-middle"><a href="<?= site_url('produk/' . $p->slug) ?>" target="_blank"><?= esc($p->nama) ?></a></td>
                    <td class="align-middle"><?= $p->kategori_nama ? esc($p->kategori_nama) : '<span class="badge badge-secondary">-</span>' ?></td>
                    <td class="align-middle text-right"><?= rupiah($p->harga) ?></td>
                    <td class="align-middle text-center"><span class="badge badge-<?= $p->stok == 0 ? 'danger' : ($p->stok <= 5 ? 'warning' : 'light') ?>"><?= $p->stok ?></span></td>
                    <td class="text-right pr-3 align-middle text-nowrap">
                        <a href="<?= site_url("admin/produk/{$p->id}/edit") ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        <form action="<?= site_url("admin/produk/{$p->id}/delete") ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus produk ini?')">
                            <?= csrf_field() ?>
                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
        <small class="text-muted">Total: <?= $pager->getTotal() ?> produk</small>
        <?= $pager->links('default', 'bootstrap') ?>
    </div>
</div>
<?= $this->endSection() ?>
