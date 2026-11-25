<?php
/**
 * READ: detail produk. Route: GET /products/{id} (filter auth).
 * Tampilan menyesuaikan role: staff -> panel + tombol edit/hapus, customer -> toko + form pesan.
 */
$rp = static fn ($n) => 'Rp' . number_format((int) $n, 0, ',', '.');
?>
<?= $this->extend($staff ? 'layouts/panel' : 'layouts/store') ?>

<?= $this->section('content') ?>
<nav class="small mb-3">
    <a href="<?= site_url('products') ?>"><i class="bi bi-arrow-left"></i> <?= $staff ? 'Kelola Produk' : 'Katalog' ?></a>
    <span class="text-muted">/ <?= esc($p->category) ?></span>
</nav>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="se-card overflow-hidden"><?= view('partials/thumb', ['p' => $p, 'cls' => 'thumb-lg']) ?></div>
    </div>

    <div class="col-lg-7">
        <div class="se-card p-4 h-100">
            <span class="badge rounded-pill mb-2" style="background: <?= esc($p->color, 'attr') ?>1a; color: <?= esc($p->color, 'attr') ?>">
                <i class="bi bi-<?= esc($p->icon, 'attr') ?>"></i> <?= esc($p->category) ?>
            </span>
            <?php if (! $p->is_active): ?><span class="badge text-bg-secondary mb-2">Disembunyikan dari katalog</span><?php endif ?>
            <h3 class="fw-bold mb-1"><?= esc($p->name) ?></h3>
            <div class="fs-3 fw-bold text-primary mb-3"><?= $rp($p->price) ?></div>
            <p class="text-muted" style="white-space: pre-line"><?= esc($p->description ?: 'Belum ada deskripsi.') ?></p>

            <?php $stok = (int) $p->stock; ?>
            <div class="d-flex gap-4 small mb-4">
                <div><span class="text-muted">Stok</span><br><strong class="<?= $stok === 0 ? 'text-danger' : '' ?>"><?= $stok === 0 ? 'Habis' : $stok . ' unit' ?></strong></div>
                <?php if ($staff): ?>
                    <div><span class="text-muted">Terjual</span><br><strong><?= (int) $terjual ?> unit</strong></div>
                    <div><span class="text-muted">Ditambahkan oleh</span><br><strong><?= esc($p->creator ?? '-') ?></strong></div>
                    <div><span class="text-muted">Dibuat</span><br><strong><?= date('d M Y', strtotime($p->created_at)) ?></strong></div>
                <?php endif ?>
            </div>

            <?php if ($staff): ?>
                <div class="d-flex flex-wrap gap-2 border-top pt-3">
                    <a href="<?= site_url('products/' . $p->id_product . '/edit') ?>" class="btn btn-primary"><i class="bi bi-pencil"></i> Edit Produk</a>
                    <?php if (session('role') === 'admin'): ?>
                        <form action="<?= site_url('products/' . $p->id_product . '/delete') ?>" method="post"
                              onsubmit="return confirm(<?= esc(json_encode('Hapus produk ' . $p->name . '?'), 'attr') ?>)">
                            <?= csrf_field() ?>
                            <button class="btn btn-outline-danger"><i class="bi bi-trash"></i> Hapus</button>
                        </form>
                    <?php endif ?>
                </div>
            <?php elseif ($stok === 0): ?>
                <div class="alert alert-info mb-0"><i class="bi bi-info-circle"></i> Stok sedang habis. Silakan cek kembali nanti.</div>
            <?php else: ?>
                <!-- Form pesan: POST /products/{id}/order, difilter auth:customer -->
                <form action="<?= site_url('products/' . $p->id_product . '/order') ?>" method="post" class="border-top pt-3" novalidate>
                    <?= csrf_field() ?>
                    <div class="row g-3 mb-3">
                        <div class="col-sm-4">
                            <label class="form-label fw-semibold small">Jumlah</label>
                            <input type="number" name="qty" min="1" max="<?= $maks ?>" value="<?= esc(old('qty', '1')) ?>" class="form-control" id="qty">
                            <div class="form-text">Maks. <?= $maks ?></div>
                        </div>
                        <div class="col-sm-8">
                            <label class="form-label fw-semibold small">Alamat Pengiriman</label>
                            <textarea name="shipping_address" rows="2" class="form-control" placeholder="Alamat lengkap"><?= esc(old('shipping_address', $alamat ?? '')) ?></textarea>
                            <?php if (! $alamat): ?><div class="form-text">Tip: isi alamat di <a href="<?= site_url('profil') ?>">Profil</a> agar terisi otomatis.</div><?php endif ?>
                        </div>
                    </div>
                    <input name="notes" class="form-control mb-3" placeholder="Catatan untuk penjual (opsional)" value="<?= esc(old('notes')) ?>">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><span class="text-muted small">Total</span><div class="fw-bold fs-5" id="total"><?= $rp($p->price) ?></div></div>
                        <button class="btn btn-primary btn-lg px-4"><i class="bi bi-bag-check"></i> Beli Sekarang</button>
                    </div>
                </form>
                <script>
                    // Hitung total saat jumlah diubah, dan sekali saat halaman dibuka (mis. jumlah lama dari old())
                    const qty = document.getElementById('qty');
                    const hitung = () => {
                        const n = Math.max(1, parseInt(qty.value || 1, 10));
                        document.getElementById('total').textContent = 'Rp' + (n * <?= (int) $p->price ?>).toLocaleString('id-ID');
                    };
                    qty.addEventListener('input', hitung);
                    hitung();
                </script>
            <?php endif ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
