<?= $this->extend('layouts/shop') ?>

<?= $this->section('content') ?>
<div class="mx-auto" style="max-width: 480px">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h4 class="text-center mb-4">Daftar Akun</h4>
            <form method="post" action="<?= site_url('register') ?>">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input name="nama" class="form-control" value="<?= esc(old('nama')) ?>">
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Username</label>
                        <input name="username" class="form-control" value="<?= esc(old('username')) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= esc(old('email')) ?>">
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ulangi Password</label>
                        <input type="password" name="konfirmasi" class="form-control">
                    </div>
                </div>
                <button class="btn btn-primary w-100">Daftar</button>
            </form>
            <p class="text-center small mt-3 mb-0">Sudah punya akun? <a href="<?= site_url('login') ?>">Masuk</a></p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
