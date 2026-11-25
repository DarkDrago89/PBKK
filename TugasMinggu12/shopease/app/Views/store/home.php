<?= $this->extend('layouts/store') ?>

<?= $this->section('content') ?>
<section class="hero mb-5">
    <div class="row align-items-center g-4">
        <div class="col-lg-7 position-relative" style="z-index: 1">
            <?php if (session('isLoggedIn')): ?>
                <span class="badge bg-white text-primary mb-3 px-3 py-2 rounded-pill">👋 Halo, <?= esc(session('name')) ?></span>
            <?php else: ?>
                <span class="badge bg-white text-primary mb-3 px-3 py-2 rounded-pill">✨ Gratis daftar, langsung belanja</span>
            <?php endif ?>
            <h1>Belanja Online<br>Mudah &amp; Aman</h1>
            <p class="lead opacity-75 mb-4">Berbagai produk pilihan dengan harga terbaik. Satu akun untuk belanja, bayar, dan lacak pesanan.</p>
            <?php if (session('isLoggedIn')): ?>
                <a href="<?= site_url('products') ?>" class="btn btn-accent btn-lg px-4">Belanja Sekarang <i class="bi bi-arrow-right"></i></a>
            <?php else: ?>
                <a href="<?= site_url('register') ?>" class="btn btn-accent btn-lg px-4 me-2">Daftar Gratis <i class="bi bi-arrow-right"></i></a>
                <a href="<?= site_url('login') ?>" class="btn btn-outline-light btn-lg px-4">Masuk</a>
            <?php endif ?>
        </div>
        <div class="col-lg-5 text-center d-none d-lg-block">
            <div class="hero-art">🎧👟</div>
        </div>
    </div>
</section>

<section id="kategori" class="mb-5">
    <h4 class="se-section-title mb-3">Kategori Pilihan</h4>
    <div class="row row-cols-3 row-cols-md-5 g-3">
        <?php foreach ($kategori as $k): ?>
            <div class="col">
                <!-- /products wajib login: tamu diarahkan ke /login lalu dikembalikan ke sini -->
                <a href="<?= site_url('products?kategori=' . $k->id_category) ?>" class="se-card cat-card h-100 d-block text-reset">
                    <span class="cat-icon" style="background: <?= esc($k->color, 'attr') ?>1a; color: <?= esc($k->color, 'attr') ?>"><i class="bi bi-<?= esc($k->icon, 'attr') ?>"></i></span>
                    <div class="fw-semibold small"><?= esc($k->name) ?></div>
                    <div class="text-muted" style="font-size: .72rem"><?= $k->jumlah ?> produk</div>
                </a>
            </div>
        <?php endforeach ?>
    </div>
</section>

<section id="produk">
    <div class="d-flex justify-content-between align-items-end mb-3">
        <div>
            <h4 class="se-section-title mb-0">Produk Terbaru</h4>
            <small class="text-muted"><?= session('isLoggedIn') ? 'Klik produk untuk melihat detail dan memesan.' : 'Masuk untuk melihat detail produk dan mulai memesan.' ?></small>
        </div>
        <a href="<?= site_url('products') ?>" class="fw-semibold small">Lihat semua &rarr;</a>
    </div>
    <div class="row row-cols-2 row-cols-md-3 row-cols-xl-4 g-3">
        <?php foreach ($produk as $p): ?>
            <div class="col"><?= view('product/_card', ['p' => $p]) ?></div>
        <?php endforeach ?>
    </div>
</section>
<?= $this->endSection() ?>
