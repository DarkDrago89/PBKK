<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h1>Term of Service</h1>
<ol>
    <li>Harga dapat berubah sewaktu-waktu mengikuti musim panen.</li>
    <li>Buah yang rusak saat diterima dapat ditukar maksimal 1×24 jam.</li>
</ol>
<p class="text-muted small">
    Route manual <code>GET /tos</code> &rarr; <code>Info::getTos()</code>.
    Saat auto routing aktif, method ini juga bisa diakses via <code>/info/tos</code>.
</p>
<?= $this->endSection() ?>
