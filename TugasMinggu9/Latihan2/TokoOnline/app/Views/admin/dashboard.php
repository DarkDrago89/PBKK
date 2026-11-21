<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<?php
$cards = [
    ['Omzet (pesanan selesai)', rupiah($omzet),                    'success', 'fa-money-bill-wave', null],
    ['Perlu Diproses',          $perStatus['menunggu'] ?? 0,       'warning', 'fa-hourglass-half',  'admin/pesanan?status=menunggu'],
    ['Produk',                  $jmlProduk,                        'primary', 'fa-box',             'admin/produk'],
    ['Customer',                $jmlCustomer,                      'info',    'fa-users',           null],
];
?>
<div class="row">
    <?php foreach ($cards as [$label, $value, $color, $icon, $url]): ?>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-<?= $color ?> shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-<?= $color ?> text-uppercase mb-1"><?= $label ?></div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $value ?></div>
                            <?php if ($url): ?><a href="<?= site_url($url) ?>" class="small">Lihat &rarr;</a><?php endif ?>
                        </div>
                        <div class="col-auto"><i class="fas <?= $icon ?> fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card shadow">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Pesanan Terbaru</h6></div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="thead-light"><tr><th class="pl-3">Kode</th><th>Customer</th><th>Total</th><th>Status</th></tr></thead>
                    <tbody>
                    <?php foreach ($terbaru as $o): ?>
                        <tr>
                            <td class="pl-3"><a href="<?= site_url('admin/pesanan/' . $o->id) ?>"><?= esc($o->kode) ?></a></td>
                            <td><?= esc($o->user_nama) ?></td>
                            <td><?= rupiah($o->total) ?></td>
                            <td><?= status_badge($o->status) ?></td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-danger">Stok Menipis (&le; 5)</h6></div>
            <ul class="list-group list-group-flush">
                <?php if ($menipis === []): ?><li class="list-group-item text-muted small">Semua stok aman.</li><?php endif ?>
                <?php foreach ($menipis as $p): ?>
                    <li class="list-group-item d-flex justify-content-between">
                        <a href="<?= site_url("admin/produk/{$p->id}/edit") ?>"><?= esc($p->nama) ?></a>
                        <span class="badge badge-<?= $p->stok == 0 ? 'danger' : 'warning' ?>"><?= $p->stok ?></span>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
