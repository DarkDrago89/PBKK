<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<a href="<?= site_url('admin/pesanan') ?>" class="btn btn-link px-0 mb-2">&larr; Semua pesanan</a>
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><?= esc($pesanan->kode) ?></h6>
                <?= status_badge($pesanan->status) ?>
            </div>
            <table class="table mb-0">
                <thead class="thead-light"><tr><th class="pl-3">Produk</th><th class="text-right">Harga</th><th class="text-center">Qty</th><th class="text-right pr-3">Subtotal</th></tr></thead>
                <tbody>
                <?php foreach ($detail as $d): ?>
                    <tr>
                        <td class="pl-3"><?= esc($d->nama_produk) ?><?= $d->produk_id === null ? ' <span class="badge badge-secondary">produk dihapus</span>' : '' ?></td>
                        <td class="text-right"><?= rupiah($d->harga) ?></td>
                        <td class="text-center"><?= $d->qty ?></td>
                        <td class="text-right pr-3"><?= rupiah($d->subtotal) ?></td>
                    </tr>
                <?php endforeach ?>
                </tbody>
                <tfoot>
                <tr><td colspan="3" class="text-right">Subtotal</td><td class="text-right pr-3"><?= rupiah($pesanan->subtotal) ?></td></tr>
                <tr><td colspan="3" class="text-right">Ongkir</td><td class="text-right pr-3"><?= rupiah($pesanan->ongkir) ?></td></tr>
                <tr class="font-weight-bold"><td colspan="3" class="text-right">Total</td><td class="text-right pr-3"><?= rupiah($pesanan->total) ?></td></tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="col-lg-4 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Pengiriman</h6></div>
            <div class="card-body small">
                <strong><?= esc($pesanan->nama_penerima) ?></strong> &middot; <?= esc($pesanan->telepon) ?><br>
                <?= nl2br(esc($pesanan->alamat)) ?>
                <?php if ($pesanan->catatan): ?><hr class="my-2"><em>Catatan: <?= esc($pesanan->catatan) ?></em><?php endif ?>
                <hr class="my-2">
                Akun: <?= esc($pesanan->user_nama) ?> (<?= esc($pesanan->user_email) ?>)<br>
                Dibuat: <?= date('d/m/Y H:i', strtotime($pesanan->created_at)) ?>
            </div>
        </div>
        <div class="card shadow">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Ubah Status</h6></div>
            <div class="card-body">
                <?php if ($pilihan === []): ?>
                    <p class="text-muted small mb-0">Pesanan sudah final, status tidak dapat diubah lagi.</p>
                <?php endif ?>
                <?php foreach ($pilihan as $ke): ?>
                    <?php [$label, $warna] = $cfg->status[$ke]; ?>
                    <form action="<?= site_url("admin/pesanan/{$pesanan->id}/status") ?>" method="post" class="mb-2"
                          <?= $ke === 'dibatalkan' ? 'onsubmit="return confirm(\'Batalkan pesanan? Stok akan dikembalikan.\')"' : '' ?>>
                        <?= csrf_field() ?>
                        <input type="hidden" name="status" value="<?= $ke ?>">
                        <button class="btn btn-<?= $warna ?> btn-block">&rarr; <?= $label ?></button>
                    </form>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
