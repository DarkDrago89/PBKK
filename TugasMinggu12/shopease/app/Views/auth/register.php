<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>
<?php $err = (array) (session('errors') ?? []); ?>
<div class="se-card auth-card" style="max-width: 520px">
    <div class="auth-card-head">
        <i class="bi bi-cart3"></i>
        <h4 class="fw-bold mb-0 mt-1">ShopEase</h4>
        <div class="opacity-75">Registrasi Akun Baru</div>
    </div>
    <div class="auth-card-body">
        <?= $this->include('partials/flash') ?>

        <form action="<?= site_url('register') ?>" method="post" novalidate>
            <?= csrf_field() ?>
            <?php
            $fields = [
                ['name',    'text',  'person',    'Nama Lengkap',   'name'],
                ['email',   'email', 'envelope',  'Email',          'email'],
                ['phone',   'tel',   'telephone', 'Nomor Telepon (opsional)', 'tel'],
            ];
            ?>
            <?php foreach ($fields as [$name, $type, $icon, $ph, $ac]): ?>
                <div class="input-icon mb-3">
                    <i class="bi bi-<?= $icon ?>"></i>
                    <input type="<?= $type ?>" name="<?= $name ?>" class="form-control <?= isset($err[$name]) ? 'is-invalid' : '' ?>"
                           placeholder="<?= $ph ?>" value="<?= esc(old($name)) ?>" autocomplete="<?= $ac ?>">
                </div>
            <?php endforeach ?>
            <div class="input-icon mb-3">
                <i class="bi bi-house" style="top: 1.35rem"></i>
                <textarea name="address" rows="2" class="form-control <?= isset($err['address']) ? 'is-invalid' : '' ?>" placeholder="Alamat (opsional)"><?= esc(old('address')) ?></textarea>
            </div>
            <div class="input-icon mb-2">
                <i class="bi bi-lock"></i>
                <input type="password" id="password" name="password" class="form-control <?= isset($err['password']) ? 'is-invalid' : '' ?>" placeholder="Password (min. 8 karakter)" autocomplete="new-password">
                <button type="button" class="toggle-pass" title="Tampilkan password"><i class="bi bi-eye"></i></button>
            </div>
            <div class="d-flex align-items-center gap-2 mb-3">
                <div class="pass-meter flex-grow-1"><div id="meter"></div></div>
                <small id="meter-label" class="text-muted" style="min-width: 70px">&nbsp;</small>
            </div>
            <div class="input-icon mb-4">
                <i class="bi bi-lock-fill"></i>
                <input type="password" name="password_confirm" class="form-control <?= isset($err['password_confirm']) ? 'is-invalid' : '' ?>" placeholder="Konfirmasi Password" autocomplete="new-password">
                <button type="button" class="toggle-pass" title="Tampilkan password"><i class="bi bi-eye"></i></button>
            </div>

            <button class="btn btn-primary w-100 py-2">Daftar Sekarang</button>
        </form>

        <p class="text-center mt-4 mb-0 text-muted">Sudah memiliki akun? <a href="<?= site_url('login') ?>" class="fw-semibold">Login di sini</a></p>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Indikator kekuatan password (hanya bantuan visual; validasi tetap di server)
const pass = document.getElementById('password'), bar = document.getElementById('meter'), label = document.getElementById('meter-label');
pass.addEventListener('input', () => {
    const v = pass.value;
    const skor = [v.length >= 8, /[a-z]/.test(v), /[A-Z]/.test(v), /\d/.test(v), /[^A-Za-z0-9]/.test(v)].filter(Boolean).length;
    const lvl = [['0%', '', ''], ['20%', '#ef4444', 'Lemah'], ['40%', '#f97316', 'Lemah'], ['60%', '#eab308', 'Sedang'], ['80%', '#22c55e', 'Kuat'], ['100%', '#16a34a', 'Sangat kuat']][v ? skor : 0];
    bar.style.width = lvl[0]; bar.style.backgroundColor = lvl[1]; label.textContent = lvl[2] || ' ';
});
</script>
<?= $this->endSection() ?>
