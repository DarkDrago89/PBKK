<?php $sb = 'https://cdn.jsdelivr.net/npm/startbootstrap-sb-admin-2@4.1.4/'; ?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | SIAKAD CI4</title>
    <link href="<?= $sb ?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,600,700,800" rel="stylesheet">
    <link href="<?= $sb ?>css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-primary">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-9 col-lg-11">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="row no-gutters">
                    <div class="col-lg-6 d-none d-lg-flex bg-gradient-info text-white p-5 flex-column justify-content-center">
                        <i class="fas fa-university fa-4x mb-4"></i>
                        <h2 class="font-weight-bold">SIAKAD</h2>
                        <p class="mb-4">Sistem Informasi Akademik berbasis CodeIgniter 4.</p>
                        <table class="small text-white">
                            <tr><td class="pr-3">Admin</td><td><code class="text-white">admin / admin123</code></td></tr>
                            <tr><td class="pr-3">Dosen</td><td><code class="text-white">198001012005011001</code> (password = NIP)</td></tr>
                            <tr><td class="pr-3">Mahasiswa</td><td><code class="text-white">5025241037</code> (password = NIM)</td></tr>
                        </table>
                    </div>
                    <div class="col-lg-6 p-5">
                        <h1 class="h4 text-gray-900 mb-4 text-center">Selamat Datang</h1>

                        <?php if (session('error')): ?>
                            <div class="alert alert-danger py-2 small"><?= esc(session('error')) ?></div>
                        <?php endif ?>

                        <form class="user" method="post" action="<?= site_url('login') ?>">
                            <?= csrf_field() ?>
                            <div class="form-group">
                                <input name="username" class="form-control form-control-user" placeholder="Username / NIM / NIP" value="<?= esc(old('username')) ?>" autofocus>
                            </div>
                            <div class="form-group">
                                <input type="password" name="password" class="form-control form-control-user" placeholder="Password">
                            </div>
                            <button class="btn btn-primary btn-user btn-block">Login</button>
                        </form>
                        <div class="d-lg-none small text-muted mt-4">
                            Demo: <code>admin/admin123</code>, dosen <code>198001012005011001</code>, mahasiswa <code>5025241037</code> (password = username).
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
