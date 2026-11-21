<?= $this->extend('layouts/shop') ?>

<?= $this->section('content') ?>
<h4 class="mb-3"><i class="bi bi-receipt"></i> Pesanan Saya</h4>
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>Kode</th><th>Tanggal</th><th>Barang</th><th>Total</th><th>Status</th><th></th></tr></thead>
            <tbody>
            <?php if ($pesanan === []): ?>
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada pesanan.</td></tr>
            <?php endif ?>
            <?php foreach ($pesanan as $o): ?>
                <tr>
                    <td class="fw-semibold"><?= esc($o->kode) ?></td>
                    <td><?= date('d M Y H:i', strtotime($o->created_at)) ?></td>
                    <td><?= (int) $o->jumlah_barang ?></td>
                    <td><?= rupiah($o->total) ?></td>
                    <td><?= status_badge($o->status) ?></td>
                    <td class="text-end"><a href="<?= site_url('akun/pesanan/' . $o->kode) ?>" class="btn btn-sm btn-outline-primary">Detail</a></td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
