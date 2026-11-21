<?php $sb = 'https://cdn.jsdelivr.net/npm/startbootstrap-sb-admin-2@4.1.4/'; ?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= esc($title ?? 'SIAKAD') ?> | SIAKAD CI4</title>
    <link href="<?= $sb ?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,600,700,800" rel="stylesheet">
    <link href="<?= $sb ?>css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        @media print { #accordionSidebar, .topbar, .no-print, footer { display: none !important; } }
    </style>
</head>
<body id="page-top">
<div id="wrapper">
    <?= $this->include('partials/sidebar') ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?= $this->include('partials/topbar') ?>

            <div class="container-fluid">
                <?= $this->include('partials/alerts') ?>
                <?= $this->renderSection('content') ?>
            </div>
        </div>

        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>PBKK &middot; Latihan 2 &middot; Sistem Informasi Akademik &middot; Tahun Ajaran <?= esc(config('Akademik')->tahunAjaran) ?></span>
                </div>
            </div>
        </footer>
    </div>
</div>

<script src="<?= $sb ?>vendor/jquery/jquery.min.js"></script>
<script src="<?= $sb ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= $sb ?>vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="<?= $sb ?>js/sb-admin-2.min.js"></script>
</body>
</html>
