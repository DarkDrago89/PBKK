<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<?php
$total = array_sum($perRole);
$cards = [
    ['Total Pengguna', $total,               'people-fill',     '#6d28d9', '#ede9fe'],
    ['Customer',       $perRole['customer'], 'person-heart',    '#7c3aed', '#f5f3ff'],
    ['Operator',       $perRole['operator'], 'person-gear',     '#ea580c', '#ffedd5'],
    ['Admin',          $perRole['admin'],    'shield-lock-fill','#dc2626', '#fee2e2'],
];
?>
<div class="row g-3 mb-4">
    <?php foreach ($cards as [$label, $nilai, $ikon, $warna, $bg]): ?>
        <div class="col-sm-6 col-xl-3">
            <div class="se-card stat-card h-100">
                <span class="stat-icon" style="background: <?= $bg ?>; color: <?= $warna ?>"><i class="bi bi-<?= $ikon ?>"></i></span>
                <div>
                    <div class="text-muted small fw-semibold"><?= $label ?></div>
                    <div class="stat-value"><?= $nilai ?></div>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>

<div class="row g-3">
    <div class="col-xl-7">
        <div class="se-card p-4 h-100">
            <div class="d-flex justify-content-between mb-3">
                <div>
                    <h6 class="fw-bold mb-0">Registrasi 7 Hari Terakhir</h6>
                    <small class="text-muted">Jumlah akun baru per hari</small>
                </div>
                <span class="fw-bold text-primary"><?= array_sum($harian) ?> akun</span>
            </div>
            <canvas id="chartRegistrasi" height="140"></canvas>
        </div>
    </div>
    <div class="col-xl-5">
        <div class="se-card h-100">
            <div class="d-flex justify-content-between align-items-center p-4 pb-2">
                <h6 class="fw-bold mb-0">Pengguna Terbaru</h6>
                <a href="<?= site_url('admin/users') ?>" class="small fw-semibold">Kelola &rarr;</a>
            </div>
            <ul class="list-group list-group-flush">
                <?php foreach ($terbaru as $u): ?>
                    <?php $ini = strtoupper(implode('', array_map(static fn ($w) => mb_substr($w, 0, 1), array_slice(explode(' ', $u->name), 0, 2)))); ?>
                    <li class="list-group-item d-flex align-items-center gap-3 px-4">
                        <span class="se-avatar"><?= esc($ini) ?></span>
                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-semibold small"><?= esc($u->name) ?></div>
                            <div class="text-muted small text-truncate"><?= esc($u->email) ?></div>
                        </div>
                        <span class="role-badge role-<?= esc($u->role) ?>"><?= esc($u->role) ?></span>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
const data = <?= json_encode($harian) ?>;
new Chart(document.getElementById('chartRegistrasi'), {
    type: 'bar',
    data: {
        labels: Object.keys(data).map(d => new Date(d + 'T00:00:00').toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric' })),
        datasets: [{ data: Object.values(data), backgroundColor: '#7c3aed', borderRadius: 8, maxBarThickness: 36 }]
    },
    options: {
        animation: false,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f1eefb' } }, x: { grid: { display: false } } }
    }
});
</script>
<?= $this->endSection() ?>
