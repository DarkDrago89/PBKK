<?= $this->extend('layouts/bootstrap') ?>

<?= $this->section('content') ?>
<div class="container" style="max-width: 420px">
    <div class="card border-0 shadow">
        <div class="card-body p-4">
            <h4 class="text-center mb-4"><i class="bi bi-shield-lock text-primary"></i> Login</h4>

            <?php if (session('error')): ?>
                <div class="alert alert-danger py-2"><?= esc(session('error')) ?></div>
            <?php endif ?>

            <form method="post" action="<?= site_url('login') ?>">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input name="username" class="form-control" value="<?= esc(old('username')) ?>" autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control">
                </div>
                <button class="btn btn-primary w-100">Masuk</button>
            </form>

            <hr>
            <p class="small text-muted mb-0">
                Akun demo:<br>
                <code>admin / admin123</code> (role <strong>admin</strong>: CRUD penuh)<br>
                <code>user / user123</code> (role <strong>user</strong>: hanya lihat)
            </p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
