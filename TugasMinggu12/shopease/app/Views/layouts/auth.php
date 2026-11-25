<!doctype html>
<html lang="id">
<head>
    <?= $this->include('partials/head') ?>
</head>
<body>
<div class="auth-wrap">
    <aside class="auth-side">
        <a href="<?= site_url('/') ?>"><?= $this->setData(['light' => true, 'size' => 44])->include('partials/logo') ?></a>

        <div class="my-5">
            <div class="auth-hero-title mb-3">Belanja Online<br>Mudah &amp; Aman</div>
            <p class="opacity-75 mb-4" style="max-width: 420px">Satu akun ShopEase untuk berbelanja, melacak pesanan, dan mengelola toko sesuai peran Anda.</p>
            <div class="auth-feature"><i class="bi bi-shield-lock"></i><div><strong>Password terenkripsi</strong><div class="small opacity-75">Disimpan sebagai hash bcrypt, tidak pernah dalam bentuk asli.</div></div></div>
            <div class="auth-feature"><i class="bi bi-person-badge"></i><div><strong>Akses sesuai peran</strong><div class="small opacity-75">Customer, Operator, dan Admin diarahkan ke halaman masing-masing.</div></div></div>
            <div class="auth-feature"><i class="bi bi-clock-history"></i><div><strong>Ingat saya 30 hari</strong><div class="small opacity-75">Token aman yang otomatis diganti setiap dipakai.</div></div></div>
        </div>

        <div class="auth-demo">
            <div class="fw-bold mb-1"><i class="bi bi-key"></i> Akun demo</div>
            <div>Admin: <code>admin@shopease.test</code> / <code>admin123</code></div>
            <div>Operator: <code>operator@shopease.test</code> / <code>operator123</code></div>
            <div>Customer: <code>agil@shopease.test</code> / <code>customer123</code></div>
        </div>
    </aside>

    <main class="auth-main">
        <?= $this->renderSection('content') ?>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Tampilkan / sembunyikan password
document.querySelectorAll('.toggle-pass').forEach(btn => btn.addEventListener('click', () => {
    const input = btn.parentElement.querySelector('input');
    const show = input.type === 'password';
    input.type = show ? 'text' : 'password';
    btn.innerHTML = show ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
}));
</script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
