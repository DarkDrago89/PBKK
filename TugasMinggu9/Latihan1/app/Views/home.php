<?= $this->extend('layouts/bootstrap') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="p-5 mb-4 bg-white rounded-3 shadow-sm">
        <h1 class="display-5 fw-bold">Pengenalan Framework CodeIgniter</h1>
        <p class="col-md-9 fs-5 text-muted">
            Aplikasi <strong>Biodata Mahasiswa</strong> sebagai latihan konsep <strong>MVC</strong>
            (Model, View, Controller) pada CodeIgniter 4.
        </p>
        <a href="<?= site_url('login') ?>" class="btn btn-primary btn-lg"><i class="bi bi-box-arrow-in-right"></i> Masuk Dashboard</a>
        <a href="<?= site_url('hello') ?>" class="btn btn-outline-secondary btn-lg">Hello World</a>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-primary"><i class="bi bi-database"></i> Model</h5>
                    <p class="card-text">Mengelola data &amp; database. Contoh: <code>BiodataModel</code> untuk CRUD tabel <code>biodata</code>.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-success"><i class="bi bi-window"></i> View</h5>
                    <p class="card-text">Tampilan untuk user. Halaman ini memakai <strong>Bootstrap 5</strong>, halaman admin memakai template <strong>SB Admin 2</strong>.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-danger"><i class="bi bi-diagram-3"></i> Controller</h5>
                    <p class="card-text">Penghubung View dan Model. Router di <code>app/Config/Routes.php</code> menentukan controller yang dipanggil.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
