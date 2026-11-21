<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h1>About Us</h1>
<p class="lead">Ini adalah halaman about.</p>
<p>Toko Buah menjual buah segar dari petani lokal dan buah impor pilihan.</p>
<p class="text-muted small">Route <code>GET /about</code> &rarr; <code>Page::about()</code> &rarr; view <code>pages/about</code></p>
<?= $this->endSection() ?>
