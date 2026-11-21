<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<div class="se-card p-4" style="max-width: 640px">
    <p class="text-muted">Admin dapat membuat akun untuk role apa pun, misalnya akun <strong>operator</strong> gudang. Registrasi publik selalu menghasilkan role <strong>customer</strong>.</p>
    <form action="<?= site_url('admin/users') ?>" method="post" novalidate>
        <?= csrf_field() ?>
        <div class="mb-3">
            <label class="form-label fw-semibold small">Nama Lengkap</label>
            <input name="name" class="form-control" value="<?= esc(old('name')) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold small">Email</label>
            <input type="email" name="email" class="form-control" value="<?= esc(old('email')) ?>">
        </div>
        <div class="row g-3 mb-4">
            <div class="col-md-5">
                <label class="form-label fw-semibold small">Role</label>
                <select name="role" class="form-select">
                    <?php foreach (\App\Models\UserModel::ROLES as $r): ?>
                        <option value="<?= $r ?>" <?= old('role', 'operator') === $r ? 'selected' : '' ?>><?= ucfirst($r) ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="col-md-7">
                <label class="form-label fw-semibold small">Password Awal</label>
                <input type="password" name="password" class="form-control" placeholder="Min. 8 karakter">
            </div>
        </div>
        <a href="<?= site_url('admin/users') ?>" class="btn btn-light">Batal</a>
        <button class="btn btn-primary"><i class="bi bi-person-plus"></i> Buat Akun</button>
    </form>
</div>
<?= $this->endSection() ?>
