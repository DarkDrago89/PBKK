<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<div class="se-card p-4 mb-4 text-white" style="background: var(--se-gradient); border: 0">
    <h4 class="fw-bold mb-1">Halo, <?= esc(session('name')) ?> 👋</h4>
    <p class="mb-0 opacity-75">Anda login sebagai <strong><?= esc(session('role')) ?></strong>. Halaman ini dilindungi filter <code class="text-white">auth:operator,admin</code>.</p>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="se-card stat-card h-100">
            <span class="stat-icon" style="background:#ede9fe;color:#6d28d9"><i class="bi bi-people"></i></span>
            <div><div class="text-muted small fw-semibold">Customer Terdaftar</div><div class="stat-value"><?= $customer ?></div></div>
        </div>
    </div>
    <?php foreach ([['clipboard-check', 'Pesanan Perlu Diproses', '#ffedd5', '#ea580c'], ['truck', 'Pengiriman Aktif', '#e0f2fe', '#0284c7']] as [$ikon, $label, $bg, $warna]): ?>
        <div class="col-md-4">
            <div class="se-card stat-card h-100">
                <span class="stat-icon" style="background:<?= $bg ?>;color:<?= $warna ?>"><i class="bi bi-<?= $ikon ?>"></i></span>
                <div><div class="text-muted small fw-semibold"><?= $label ?></div><div class="stat-value">–</div><span class="badge badge-soon">Modul pesanan M4</span></div>
            </div>
        </div>
    <?php endforeach ?>
</div>
<?= $this->endSection() ?>
