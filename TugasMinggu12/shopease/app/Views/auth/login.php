<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>
<div class="se-card auth-card">
    <div class="auth-card-head">
        <i class="bi bi-cart3"></i>
        <h4 class="fw-bold mb-0 mt-1">ShopEase</h4>
        <div class="opacity-75">Login Pengguna</div>
    </div>
    <div class="auth-card-body">
        <?= $this->include('partials/flash') ?>

        <form action="<?= site_url('login') ?>" method="post" novalidate>
            <?= csrf_field() ?>
            <label class="form-label fw-semibold small" for="email">Email</label>
            <div class="input-icon mb-3">
                <i class="bi bi-envelope"></i>
                <input type="email" id="email" name="email" class="form-control" placeholder="nama@email.com"
                       value="<?= esc(old('email', session('email') ?? '')) ?>" autocomplete="email" autofocus>
            </div>

            <label class="form-label fw-semibold small" for="password">Password</label>
            <div class="input-icon mb-3">
                <i class="bi bi-lock"></i>
                <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password" autocomplete="current-password">
                <button type="button" class="toggle-pass" title="Tampilkan password"><i class="bi bi-eye"></i></button>
            </div>

            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="remember" value="1" id="remember" <?= old('remember') ? 'checked' : '' ?>>
                <label class="form-check-label" for="remember">Ingat saya <span class="text-muted small">(30 hari)</span></label>
            </div>

            <button class="btn btn-primary w-100 py-2">Login <i class="bi bi-arrow-right"></i></button>
        </form>

        <p class="text-center mt-4 mb-0 text-muted">Belum memiliki akun? <a href="<?= site_url('register') ?>" class="fw-semibold">Registrasi</a></p>
    </div>
</div>
<?= $this->endSection() ?>
