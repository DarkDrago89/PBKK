<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<ul class="nav nav-pills mb-3">
    <li class="nav-item"><a class="nav-link <?= ! $status ? 'active' : '' ?>" href="<?= site_url('admin/pesanan') ?>">Semua</a></li>
    <?php foreach ($cfg->status as $kode => [$label]): ?>
        <li class="nav-item"><a class="nav-link <?= $status === $kode ? 'active' : '' ?>" href="<?= site_url('admin/pesanan') ?>?status=<?= $kode ?>"><?= $label ?></a></li>
    <?php endforeach ?>
</ul>
<div class="card shadow mb-4">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="thead-light"><tr><th class="pl-3">Kode</th><th>Tanggal</th><th>Customer</th><th class="text-right">Total</th><th>Status</th><th></th></tr></thead>
            <tbody>
            <?php if ($pesanan === []): ?>
                <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada pesanan.</td></tr>
            <?php endif ?>
            <?php foreach ($pesanan as $o): ?>
                <tr>
                    <td class="pl-3 align-middle font-weight-bold"><?= esc($o->kode) ?></td>
                    <td class="align-middle"><?= date('d/m/Y H:i', strtotime($o->created_at)) ?></td>
                    <td class="align-middle"><?= esc($o->user_nama) ?></td>
                    <td class="align-middle text-right"><?= rupiah($o->total) ?></td>
                    <td class="align-middle"><?= status_badge($o->status) ?></td>
                    <td class="text-right pr-3"><a href="<?= site_url('admin/pesanan/' . $o->id) ?>" class="btn btn-sm btn-primary">Kelola</a></td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white d-flex justify-content-end"><?= $pager->links('default', 'bootstrap') ?></div>
</div>
<?= $this->endSection() ?>
