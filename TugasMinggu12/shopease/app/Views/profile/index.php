<?= $this->extend($layout) ?>

<?= $this->section('content') ?>
<?php $ini = strtoupper(implode('', array_map(static fn ($w) => mb_substr($w, 0, 1), array_slice(explode(' ', $user->name), 0, 2)))); ?>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="se-card p-4 text-center h-100">
            <span class="se-avatar lg mb-3"><?= esc($ini) ?></span>
            <h5 class="fw-bold mb-1"><?= esc($user->name) ?></h5>
            <div class="text-muted small mb-2"><?= esc($user->email) ?></div>
            <span class="role-badge role-<?= esc($user->role) ?>"><?= esc($user->role) ?></span>
            <hr>
            <div class="text-start small">
                <div class="d-flex justify-content-between mb-1"><span class="text-muted">Bergabung</span><span><?= date('d M Y', strtotime($user->created_at)) ?></span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">Diperbarui</span><span><?= date('d M Y H:i', strtotime($user->updated_at)) ?></span></div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="se-card p-4 mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-person-lines-fill text-primary"></i> Data Diri</h6>
            <form action="<?= site_url('profil') ?>" method="post" novalidate>
                <?= csrf_field() ?>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Nama Lengkap</label>
                        <input name="name" class="form-control" value="<?= esc(old('name', $user->name)) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Email <span class="text-muted fw-normal">(tidak dapat diubah)</span></label>
                        <input class="form-control" value="<?= esc($user->email) ?>" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Nomor Telepon</label>
                        <input name="phone" class="form-control" value="<?= esc(old('phone', $user->phone ?? '')) ?>" placeholder="08xxxxxxxxxx">
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Alamat</label>
                        <textarea name="address" rows="2" class="form-control"><?= esc(old('address', $user->address ?? '')) ?></textarea>
                    </div>
                </div>
                <button class="btn btn-primary mt-3">Simpan Perubahan</button>
            </form>
        </div>

        <div class="se-card p-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-shield-lock text-primary"></i> Ganti Password</h6>
            <form action="<?= site_url('profil/password') ?>" method="post" novalidate>
                <?= csrf_field() ?>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Password Saat Ini</label>
                        <input type="password" name="current_password" class="form-control" autocomplete="current-password">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Password Baru</label>
                        <input type="password" name="new_password" class="form-control" autocomplete="new-password">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Ulangi Password Baru</label>
                        <input type="password" name="confirm_password" class="form-control" autocomplete="new-password">
                    </div>
                </div>
                <p class="small text-muted mt-2 mb-0"><i class="bi bi-info-circle"></i> Mengganti password akan mengeluarkan sesi "Ingat saya" di perangkat lain.</p>
                <button class="btn btn-outline-primary mt-3">Ganti Password</button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
