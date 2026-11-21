<?= $this->extend('layouts/shop') ?>

<?= $this->section('content') ?>
<?php $biaya = ['subtotal' => (int) $pesanan->subtotal, 'ongkir' => (int) $pesanan->ongkir, 'total' => (int) $pesanan->total]; ?>
<a href="<?= site_url('akun/pesanan') ?>" class="btn btn-link px-0 mb-2">&larr; Semua pesanan</a>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-semibold"><?= esc($pesanan->kode) ?></span>
                <?= status_badge($pesanan->status) ?>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light"><tr><th>Produk</th><th>Harga</th><th>Qty</th><th class="text-end">Subtotal</th></tr></thead>
                    <tbody>
                    <?php foreach ($detail as $d): ?>
                        <tr><td><?= esc($d->nama_produk) ?></td><td><?= rupiah($d->harga) ?></td><td><?= $d->qty ?></td><td class="text-end"><?= rupiah($d->subtotal) ?></td></tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>
            <?= $this->setData(['biaya' => $biaya])->include('shop/_ringkasan') ?>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold">Pengiriman</div>
            <div class="card-body small">
                <strong><?= esc($pesanan->nama_penerima) ?></strong> &middot; <?= esc($pesanan->telepon) ?><br>
                <?= nl2br(esc($pesanan->alamat)) ?>
                <?php if ($pesanan->catatan): ?><hr class="my-2"><em>Catatan: <?= esc($pesanan->catatan) ?></em><?php endif ?>
            </div>
        </div>
        <?php if ($pesanan->status === 'menunggu'): ?>
            <div class="alert alert-warning small">
                Silakan transfer <strong><?= rupiah($pesanan->total) ?></strong> ke rekening <strong>BCA 123-456-7890 a.n. TokoKita</strong> (simulasi). Admin akan memproses setelah pembayaran diterima.
            </div>
            <form action="<?= site_url('akun/pesanan/' . $pesanan->kode . '/batal') ?>" method="post" onsubmit="return confirm('Batalkan pesanan ini?')">
                <?= csrf_field() ?>
                <button class="btn btn-outline-danger w-100">Batalkan Pesanan</button>
            </form>
        <?php endif ?>
    </div>
</div>
<?= $this->endSection() ?>
