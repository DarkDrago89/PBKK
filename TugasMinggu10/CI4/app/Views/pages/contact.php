<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h1>Contact Us</h1>
<p class="lead">Ini adalah halaman contact.</p>
<ul class="list-unstyled">
    <li>📍 Jl. Raya ITS, Sukolilo, Surabaya</li>
    <li>📞 0812-3456-7890</li>
    <li>✉️ halo@tokobuah.test</li>
</ul>
<p class="text-muted small">Route <code>GET /contact</code> &rarr; <code>Page::contact()</code> &rarr; view <code>pages/contact</code></p>
<?= $this->endSection() ?>
