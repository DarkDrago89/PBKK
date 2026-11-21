<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="p-5 mb-4 bg-white rounded-3 shadow-sm">
    <h1 class="display-5 fw-bold">Selamat Datang di Toko Buah</h1>
    <p class="lead text-muted">
        Halaman ini adalah <strong>default controller</strong>:
        <code>$route['default_controller'] = 'page';</code> sehingga URL <code>/</code> menjalankan <code>Page::index()</code>.
    </p>
    <a href="<?php echo site_url('about'); ?>" class="btn btn-success">About</a>
    <a href="<?php echo site_url('contact'); ?>" class="btn btn-outline-success">Contact</a>
</div>

<div class="row g-3">
    <?php foreach ($buah as $b): ?>
        <div class="col-6 col-md-3">
            <div class="card h-100 text-center border-0 shadow-sm">
                <div class="card-body">
                    <div style="font-size: 3rem"><?php echo $b['emoji']; ?></div>
                    <h6 class="mt-2"><?php echo html_escape($b['nama']); ?></h6>
                    <span class="text-success fw-bold">Rp<?php echo number_format($b['harga'], 0, ',', '.'); ?>/kg</span>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
