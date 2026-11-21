<?php $uri = uri_string(); ?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? '') ?> | Toko Buah (CI 4)</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="d-flex flex-column min-vh-100 bg-light">
<nav class="navbar navbar-expand-md navbar-dark bg-success">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= site_url('/') ?>">🍉 Toko Buah <small class="fw-normal opacity-75">CI 4</small></a>
        <ul class="navbar-nav ms-auto flex-row gap-3">
            <?php foreach (['' => 'Home', 'buah' => 'Buah', 'about' => 'About', 'contact' => 'Contact', 'faqs' => 'FAQs'] as $url => $label): ?>
                <?php $aktif = $url === '' ? $uri === '' : str_starts_with($uri, $url); ?>
                <li class="nav-item"><a class="nav-link <?= $aktif ? 'active fw-semibold' : '' ?>" href="<?= site_url($url) ?>"><?= $label ?></a></li>
            <?php endforeach ?>
        </ul>
    </div>
</nav>

<main class="container py-5 flex-grow-1">
    <?= $this->renderSection('content') ?>
</main>

<footer class="bg-white border-top py-3 text-center small text-muted">
    <?php $r = service('router'); ?>
    Controller: <code><?= esc($r->controllerName()) ?></code>
    &middot; Method: <code><?= esc($r->methodName()) ?>()</code>
    &middot; CodeIgniter <?= \CodeIgniter\CodeIgniter::CI_VERSION ?>
</footer>
</body>
</html>
