<?php
/**
 * Form produk, dipakai bersama oleh create.php dan edit.php.
 * @var object|null $p        produk yang diedit (null saat tambah)
 * @var array       $kategori [id => nama]
 * @var string      $action   URL tujuan POST
 */
$p     ??= null;
$val   = static fn (string $f, $default = '') => old($f, $p->{$f} ?? $default);
$err   = session('errors') ?? [];
$inv   = static fn (string $f) => isset($err[$f]) ? 'is-invalid' : '';
$pesan = static fn (string $f) => isset($err[$f]) ? '<div class="invalid-feedback">' . esc($err[$f]) . '</div>' : '';
$aktif = old('name') !== null ? (bool) old('is_active') : (bool) ($p->is_active ?? true);
?>
<!-- enctype multipart wajib agar file gambar ikut terkirim -->
<form action="<?= $action ?>" method="post" enctype="multipart/form-data" novalidate>
    <?= csrf_field() ?>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="se-card p-4 h-100">
                <h6 class="fw-bold mb-3">Informasi Produk</h6>
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Nama Produk</label>
                    <input name="name" class="form-control <?= $inv('name') ?>" value="<?= esc($val('name')) ?>" placeholder="Contoh: Headphone Wireless ANC">
                    <?= $pesan('name') ?>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Kategori</label>
                    <select name="id_category" class="form-select <?= $inv('id_category') ?>">
                        <option value="">— Pilih kategori —</option>
                        <?php foreach ($kategori as $id => $nama): ?>
                            <option value="<?= $id ?>" <?= (string) $val('id_category') === (string) $id ? 'selected' : '' ?>><?= esc($nama) ?></option>
                        <?php endforeach ?>
                    </select>
                    <?= $pesan('id_category') ?>
                    <div class="form-text">Kategori belum ada? <a href="<?= site_url('categories') ?>">Tambah kategori</a>.</div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Harga (Rp)</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="price" min="1" class="form-control <?= $inv('price') ?>" value="<?= esc($val('price')) ?>">
                            <?= $pesan('price') ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Stok</label>
                        <input type="number" name="stock" min="0" class="form-control <?= $inv('stock') ?>" value="<?= esc($val('stock', '0')) ?>">
                        <?= $pesan('stock') ?>
                    </div>
                </div>
                <div>
                    <label class="form-label fw-semibold small">Deskripsi</label>
                    <textarea name="description" rows="5" class="form-control <?= $inv('description') ?>" placeholder="Jelaskan keunggulan produk"><?= esc($val('description')) ?></textarea>
                    <?= $pesan('description') ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="se-card p-4 mb-4">
                <h6 class="fw-bold mb-3">Gambar Produk</h6>
                <div class="upload-preview mb-3" id="preview">
                    <?php if ($p?->image): ?>
                        <img src="<?= esc(\App\Models\ProductModel::imageUrl($p->image), 'attr') ?>" alt="">
                    <?php else: ?>
                        <span class="text-muted small"><i class="bi bi-image fs-2 d-block"></i>Belum ada gambar</span>
                    <?php endif ?>
                </div>
                <input type="file" name="image" accept="image/png,image/jpeg,image/webp" class="form-control <?= $inv('image') ?>" id="image">
                <?= $pesan('image') ?>
                <div class="form-text">JPG/PNG/WEBP, maks. 2 MB. <?= $p ? 'Kosongkan jika tidak ingin mengganti.' : 'Opsional.' ?></div>
                <?php if ($p?->image): ?>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="hapus_gambar" value="1" id="hapus_gambar">
                        <label class="form-check-label small" for="hapus_gambar">Hapus gambar saat ini</label>
                    </div>
                <?php endif ?>
            </div>
            <div class="se-card p-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" name="is_active" value="1" id="is_active" <?= $aktif ? 'checked' : '' ?>>
                    <label class="form-check-label fw-semibold" for="is_active">Tampilkan di katalog</label>
                </div>
                <div class="form-text">Produk yang disembunyikan tidak terlihat oleh customer.</div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-4">
        <a href="<?= $p ? site_url('products/' . $p->id_product) : site_url('products') ?>" class="btn btn-light">Batal</a>
        <button class="btn btn-primary px-4"><i class="bi bi-check2"></i> <?= $p ? 'Simpan Perubahan' : 'Simpan Produk' ?></button>
    </div>
</form>

<script>
// Pratinjau gambar sebelum diunggah
document.getElementById('image').addEventListener('change', (e) => {
    const f = e.target.files[0];
    if (f) document.getElementById('preview').innerHTML = `<img src="${URL.createObjectURL(f)}" alt="">`;
});
</script>
