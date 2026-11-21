<?php $uri = uri_string(); ?>
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= site_url('admin') ?>">
        <div class="sidebar-brand-icon rotate-n-15"><i class="fas fa-id-card"></i></div>
        <div class="sidebar-brand-text mx-3">Biodata CI4</div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item <?= $uri === 'admin' ? 'active' : '' ?>">
        <a class="nav-link" href="<?= site_url('admin') ?>"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a>
    </li>

    <hr class="sidebar-divider">
    <div class="sidebar-heading">Data</div>

    <li class="nav-item <?= str_starts_with($uri, 'admin/biodata') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= site_url('admin/biodata') ?>"><i class="fas fa-fw fa-users"></i><span>Biodata Mahasiswa</span></a>
    </li>

    <?php if (session('role') === 'admin'): ?>
        <li class="nav-item">
            <a class="nav-link" href="<?= site_url('admin/biodata/create') ?>"><i class="fas fa-fw fa-user-plus"></i><span>Tambah Biodata</span></a>
        </li>
    <?php endif ?>

    <hr class="sidebar-divider">
    <div class="sidebar-heading">Latihan</div>
    <li class="nav-item">
        <a class="nav-link" href="<?= site_url('hello') ?>"><i class="fas fa-fw fa-hand-sparkles"></i><span>Hello World</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?= site_url('/') ?>"><i class="fas fa-fw fa-home"></i><span>Halaman Publik</span></a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
