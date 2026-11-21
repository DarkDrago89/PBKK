<?php
$uri  = uri_string();
$role = session('role');

// Menu sidebar per role: [url, ikon, label]
$menu = [
    'admin' => [
        'Master Data' => [
            ['admin/mahasiswa',  'fa-user-graduate',      'Mahasiswa'],
            ['admin/dosen',      'fa-chalkboard-teacher', 'Dosen'],
            ['admin/matakuliah', 'fa-book',               'Mata Kuliah'],
        ],
    ],
    'mahasiswa' => [
        'Akademik' => [
            ['mahasiswa/krs', 'fa-clipboard-list', 'KRS'],
            ['mahasiswa/khs', 'fa-award',          'KHS & IPK'],
        ],
    ],
    'dosen' => [
        'Pengajaran' => [
            ['dosen/kelas', 'fa-chalkboard', 'Kelas & Nilai'],
        ],
    ],
][$role] ?? [];
?>
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= site_url('dashboard') ?>">
        <div class="sidebar-brand-icon rotate-n-15"><i class="fas fa-university"></i></div>
        <div class="sidebar-brand-text mx-3">SIAKAD</div>
    </a>

    <hr class="sidebar-divider my-0">
    <li class="nav-item <?= $uri === 'dashboard' ? 'active' : '' ?>">
        <a class="nav-link" href="<?= site_url('dashboard') ?>"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a>
    </li>

    <?php foreach ($menu as $heading => $items): ?>
        <hr class="sidebar-divider">
        <div class="sidebar-heading"><?= $heading ?></div>
        <?php foreach ($items as [$url, $icon, $label]): ?>
            <li class="nav-item <?= str_starts_with($uri, $url) ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url($url) ?>"><i class="fas fa-fw <?= $icon ?>"></i><span><?= $label ?></span></a>
            </li>
        <?php endforeach ?>
    <?php endforeach ?>

    <hr class="sidebar-divider d-none d-md-block">
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
