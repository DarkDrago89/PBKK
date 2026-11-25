<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<div class="se-card">
    <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center p-4 pb-3">
        <form class="d-flex flex-wrap gap-2" method="get">
            <div class="input-icon">
                <i class="bi bi-search"></i>
                <input name="q" class="form-control" placeholder="Cari nama / email" value="<?= esc($q ?? '') ?>">
            </div>
            <select name="role" class="form-select" style="width: auto" onchange="this.form.submit()">
                <option value="">Semua role</option>
                <?php foreach (\App\Models\UserModel::ROLES as $r): ?>
                    <option value="<?= $r ?>" <?= $role === $r ? 'selected' : '' ?>><?= ucfirst($r) ?></option>
                <?php endforeach ?>
            </select>
            <button class="btn btn-outline-primary">Cari</button>
        </form>
        <a href="<?= site_url('admin/users/create') ?>" class="btn btn-primary"><i class="bi bi-person-plus"></i> Tambah User</a>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>Pengguna</th><th>Telepon</th><th>Terdaftar</th><th style="width: 190px">Role</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
            <?php if ($items === []): ?>
                <tr><td colspan="5" class="text-center text-muted py-5">Tidak ada pengguna yang cocok.</td></tr>
            <?php endif ?>
            <?php foreach ($items as $u): ?>
                <?php
                $saya = (int) $u->id_user === (int) session('id_user');
                $ini  = strtoupper(implode('', array_map(static fn ($w) => mb_substr($w, 0, 1), array_slice(explode(' ', $u->name), 0, 2))));
                ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <span class="se-avatar"><?= esc($ini) ?></span>
                            <div>
                                <div class="fw-semibold"><?= esc($u->name) ?> <?= $saya ? '<span class="badge text-bg-light">Anda</span>' : '' ?></div>
                                <div class="small text-muted"><?= esc($u->email) ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="small"><?= esc($u->phone ?? '-') ?></td>
                    <td class="small text-muted"><?= date('d M Y', strtotime($u->created_at)) ?></td>
                    <td>
                        <?php if ($saya): ?>
                            <span class="role-badge role-<?= esc($u->role) ?>"><?= esc($u->role) ?></span>
                        <?php else: ?>
                            <form action="<?= site_url("admin/users/{$u->id_user}/role") ?>" method="post">
                                <?= csrf_field() ?>
                                <select name="role" class="form-select form-select-sm" onchange="this.form.submit()" aria-label="Ubah role">
                                    <?php foreach (\App\Models\UserModel::ROLES as $r): ?>
                                        <option value="<?= $r ?>" <?= $u->role === $r ? 'selected' : '' ?>><?= ucfirst($r) ?></option>
                                    <?php endforeach ?>
                                </select>
                            </form>
                        <?php endif ?>
                    </td>
                    <td class="text-end">
                        <?php if (! $saya): ?>
                            <form action="<?= site_url("admin/users/{$u->id_user}/delete") ?>" method="post" class="d-inline"
                                  onsubmit="return confirm(<?= esc(json_encode('Hapus akun ' . $u->name . '?'), 'attr') ?>)">
                                <?= csrf_field() ?>
                                <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                            </form>
                        <?php endif ?>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-between align-items-center p-3 px-4">
        <small class="text-muted">Total <?= $pager->getTotal() ?> pengguna</small>
        <?= $pager->links('default', 'shopease') ?>
    </div>
</div>
<?= $this->endSection() ?>
