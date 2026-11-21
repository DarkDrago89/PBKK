<?php $sb = 'https://cdn.jsdelivr.net/npm/startbootstrap-sb-admin-2@4.1.4/'; ?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= esc($title ?? 'Admin') ?> | Biodata CI4</title>
    <link href="<?= $sb ?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,600,700,800" rel="stylesheet">
    <link href="<?= $sb ?>css/sb-admin-2.min.css" rel="stylesheet">
    <style>.foto-thumb{width:42px;height:42px;object-fit:cover}</style>
</head>
<body id="page-top">
<div id="wrapper">

    <?= $this->include('partials/sidebar') ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <?= $this->include('partials/topbar') ?>

            <div class="container-fluid">
                <?php if (session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= esc(session('success')) ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                <?php endif ?>
                <?php if (session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?= esc(session('error')) ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                <?php endif ?>

                <?= $this->renderSection('content') ?>
            </div>
        </div>

        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>PBKK &middot; Tugas Minggu 9 &middot; Template SB Admin 2</span>
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
