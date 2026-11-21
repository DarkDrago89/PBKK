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
                <a href="#produk" class="btn btn-accent btn-lg px-4">Belanja Sekarang <i class="bi bi-arrow-right"></i></a>
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
        <?php foreach ($kategori as [$ikon, $nama, $warna]): ?>
            <div class="col">
                <div class="se-card cat-card h-100">
                    <span class="cat-icon" style="background: <?= $warna ?>1a; color: <?= $warna ?>"><i class="bi bi-<?= $ikon ?>"></i></span>
                    <div class="fw-semibold small"><?= esc($nama) ?></div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</section>

<section id="produk">
    <div class="d-flex justify-content-between align-items-end mb-3">
        <div>
            <h4 class="se-section-title mb-0">Produk Terbaru</h4>
            <small class="text-muted">Contoh tampilan katalog. Data produk dari database dibangun pada milestone M3.</small>
        </div>
    </div>
    <div class="row row-cols-2 row-cols-md-3 row-cols-xl-4 g-3">
        <?php foreach ($produk as [$ikon, $nama, $harga, $kat, $bg]): ?>
            <div class="col">
                <div class="se-card prod-card h-100">
                    <div class="prod-thumb" style="background: <?= $bg ?>"><i class="bi bi-<?= $ikon ?>"></i></div>
                    <div class="p-3">
                        <div class="small text-muted"><?= esc($kat) ?></div>
                        <div class="fw-semibold"><?= esc($nama) ?></div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span class="fw-bold text-primary">Rp<?= number_format($harga, 0, ',', '.') ?></span>
                            <!-- /keranjang dilindungi filter auth:customer -> tamu diarahkan ke login -->
                            <a href="<?= site_url('keranjang') ?>" class="btn btn-sm btn-primary rounded-pill"><i class="bi bi-cart-plus"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</section>
<?= $this->endSection() ?>
