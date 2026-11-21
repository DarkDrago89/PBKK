<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-5 mb-4">
        <div class="card shadow h-100">
            <div class="card-body">
                <h5 class="font-weight-bold text-primary mb-3"><i class="fas fa-id-card"></i> Profil Mahasiswa</h5>
                <table class="table table-sm table-borderless mb-0">
                    <tr><th style="width:110px">NIM</th><td><?= esc($mhs->nim) ?></td></tr>
                    <tr><th>Nama</th><td><?= esc($mhs->nama) ?></td></tr>
                    <tr><th>Prodi</th><td><?= esc($mhs->prodi) ?></td></tr>
                    <tr><th>Angkatan</th><td><?= esc($mhs->angkatan) ?></td></tr>
                    <tr><th>Email</th><td><?= esc($mhs->email) ?></td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">IPK</div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800"><?= number_format($ipk['ip'], 2) ?></div>
                        <small class="text-muted"><?= $ipk['sks'] ?> SKS lulus dinilai</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">SKS <?= esc($ta) ?></div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800"><?= $sksAktif ?> <small class="text-muted h6">/ <?= $maxSks ?></small></div>
                        <div class="progress progress-sm mt-2">
                            <div class="progress-bar bg-info" style="width: <?= min(100, $sksAktif / $maxSks * 100) ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a href="<?= site_url('mahasiswa/krs') ?>" class="btn btn-primary"><i class="fas fa-clipboard-list"></i> Isi KRS</a>
        <a href="<?= site_url('mahasiswa/khs') ?>" class="btn btn-outline-primary"><i class="fas fa-award"></i> Lihat KHS</a>
    </div>
</div>
<?= $this->endSection() ?>
