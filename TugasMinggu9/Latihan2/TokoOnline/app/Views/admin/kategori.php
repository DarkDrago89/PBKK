<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-7 mb-4">
        <div class="card shadow">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Daftar Kategori</h6></div>
            <table class="table mb-0">
                <thead class="thead-light"><tr><th class="pl-3">Nama</th><th>Slug</th><th>Produk</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($kategori as $k): ?>
                    <tr class="<?= $edit && $edit->id == $k->id ? 'table-warning' : '' ?>">
                        <td class="pl-3 align-middle"><?= esc($k->nama) ?></td>
                        <td class="align-middle"><code><?= esc($k->slug) ?></code></td>
                        <td class="align-middle"><?= $k->jumlah ?></td>
                        <td class="text-right pr-3 text-nowrap">
                            <a href="<?= site_url("admin/kategori/{$k->id}/edit") ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                            <form action="<?= site_url("admin/kategori/{$k->id}/delete") ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus kategori ini?')">
                                <?= csrf_field() ?>
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-lg-5 mb-4">
        <div class="card shadow">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary"><?= $edit ? 'Edit Kategori' : 'Tambah Kategori' ?></h6></div>
            <div class="card-body">
                <form action="<?= site_url($edit ? "admin/kategori/{$edit->id}/simpan" : 'admin/kategori/simpan') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label>Nama Kategori</label>
                        <input name="nama" class="form-control" value="<?= esc(old('nama', $edit->nama ?? '')) ?>">
                        <small class="text-muted">Slug URL dibuat otomatis dari nama.</small>
                    </div>
                    <?php if ($edit): ?><a href="<?= site_url('admin/kategori') ?>" class="btn btn-secondary">Batal</a><?php endif ?>
                    <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
