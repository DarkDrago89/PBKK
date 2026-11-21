<?= $this->extend('layouts/shop') ?>

<?= $this->section('content') ?>
<h4 class="mb-3"><i class="bi bi-truck"></i> Checkout</h4>
<form action="<?= site_url('checkout') ?>" method="post" class="row g-4">
    <?= csrf_field() ?>
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Alamat Pengiriman</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Penerima</label>
                        <input name="nama_penerima" class="form-control" value="<?= esc(old('nama_penerima', $user->nama)) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">No. HP</label>
                        <input name="telepon" class="form-control" placeholder="08xxxxxxxxxx" value="<?= esc(old('telepon', $user->telepon ?? '')) ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea name="alamat" rows="3" class="form-control"><?= esc(old('alamat', $user->alamat ?? '')) ?></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Catatan <small class="text-muted">(opsional)</small></label>
                        <input name="catatan" class="form-control" value="<?= esc(old('catatan')) ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Pesanan (<?= array_sum(array_column($isi, 'qty')) ?> barang)</div>
            <ul class="list-group list-group-flush">
                <?php foreach ($isi as $p): ?>
                    <li class="list-group-item d-flex justify-content-between small">
                        <span><?= esc($p->nama) ?> <span class="text-muted">× <?= $p->qty ?></span></span>
                        <span><?= rupiah($p->subtotal) ?></span>
                    </li>
                <?php endforeach ?>
            </ul>
            <?= $this->include('shop/_ringkasan') ?>
            <div class="card-body">
                <p class="small text-muted mb-2"><i class="bi bi-info-circle"></i> Pembayaran: transfer bank (simulasi). Stok dikunci saat pesanan dibuat.</p>
                <button class="btn btn-success w-100 btn-lg">Buat Pesanan</button>
            </div>
        </div>
    </div>
</form>
<?= $this->endSection() ?>
