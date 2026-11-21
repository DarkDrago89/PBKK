<?= $this->extend('layouts/shop') ?>

<?= $this->section('content') ?>
<h4 class="mb-3"><i class="bi bi-cart3"></i> Keranjang Belanja</h4>

<?php if ($isi === []): ?>
    <div class="card border-0 shadow-sm"><div class="card-body text-center py-5">
        <i class="bi bi-cart-x fs-1 text-muted"></i>
        <p class="text-muted mt-2">Keranjang masih kosong.</p>
        <a href="<?= site_url('/') ?>" class="btn btn-primary">Mulai Belanja</a>
    </div></div>
<?php else: ?>
<div class="row g-4">
    <div class="col-lg-8">
        <form id="form-ubah" action="<?= site_url('keranjang/ubah') ?>" method="post"><?= csrf_field() ?></form>
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light"><tr><th>Produk</th><th>Harga</th><th style="width:110px">Jumlah</th><th class="text-end">Subtotal</th><th></th></tr></thead>
                    <tbody>
                    <?php foreach ($isi as $p): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="<?= gambar_produk($p) ?>" width="56" height="42" class="rounded object-fit-cover" alt="">
                                    <div>
                                        <a href="<?= site_url('produk/' . $p->slug) ?>" class="text-decoration-none"><?= esc($p->nama) ?></a>
                                        <div class="small text-muted">Stok <?= $p->stok ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><?= rupiah($p->harga) ?></td>
                            <td><input form="form-ubah" type="number" name="qty[<?= $p->id ?>]" value="<?= $p->qty ?>" min="0" max="<?= $p->stok ?>" class="form-control form-control-sm"></td>
                            <td class="text-end fw-semibold"><?= rupiah($p->subtotal) ?></td>
                            <td class="text-end">
                                <form action="<?= site_url('keranjang/hapus/' . $p->id) ?>" method="post">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white d-flex justify-content-between">
                <a href="<?= site_url('/') ?>" class="btn btn-link px-0">&larr; Lanjut belanja</a>
                <button form="form-ubah" class="btn btn-outline-primary btn-sm"><i class="bi bi-arrow-repeat"></i> Perbarui Keranjang</button>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Ringkasan</div>
            <?= $this->include('shop/_ringkasan') ?>
            <div class="card-body">
                <?php if ($biaya['ongkir'] > 0): ?>
                    <p class="small text-muted">Belanja <?= rupiah($cfg->gratisOngkirMin - $biaya['subtotal']) ?> lagi untuk gratis ongkir.</p>
                <?php endif ?>
                <a href="<?= site_url('checkout') ?>" class="btn btn-primary w-100">Checkout <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>
</div>
<?php endif ?>
<?= $this->endSection() ?>
