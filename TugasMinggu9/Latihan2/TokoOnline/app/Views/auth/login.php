<?= $this->extend('layouts/shop') ?>

<?= $this->section('content') ?>
<div class="mx-auto" style="max-width: 420px">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h4 class="text-center mb-4">Masuk</h4>
            <form method="post" action="<?= site_url('login') ?>">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label">Username atau Email</label>
                    <input name="login" class="form-control" value="<?= esc(old('login')) ?>" autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control">
                </div>
                <button class="btn btn-primary w-100">Masuk</button>
            </form>
            <p class="text-center small mt-3 mb-0">Belum punya akun? <a href="<?= site_url('register') ?>">Daftar</a></p>
            <hr>
            <p class="small text-muted mb-0">
                Akun demo:<br>
                <code>admin / admin123</code> (admin)<br>
                <code>agil / agil123</code> (customer)
            </p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
