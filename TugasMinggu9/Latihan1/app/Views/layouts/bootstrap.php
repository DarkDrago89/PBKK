<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Biodata') ?> | Biodata CI4</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="d-flex flex-column min-vh-100 bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= site_url('/') ?>"><i class="bi bi-person-vcard"></i> Biodata CI4</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="<?= site_url('/') ?>">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= site_url('hello') ?>">Hello World</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= site_url('welcome') ?>">Welcome Page</a></li>
                <?php if (session()->get('isLoggedIn')): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= site_url('admin') ?>">Dashboard</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="btn btn-light btn-sm ms-lg-2 mt-1" href="<?= site_url('login') ?>">Login</a></li>
                <?php endif ?>
            </ul>
        </div>
    </div>
</nav>

<main class="flex-grow-1 py-5">
    <?= $this->renderSection('content') ?>
</main>

<footer class="bg-white border-top py-3 text-center text-muted small">
    PBKK &middot; Tugas Minggu 9 &middot; CodeIgniter <?= \CodeIgniter\CodeIgniter::CI_VERSION ?>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
