<?= $this->extend('layouts/bootstrap') ?>

<?= $this->section('content') ?>
<div class="container text-center">
    <h1 class="display-3 fw-bold">Hello <?= esc($nama) ?>!</h1>
    <p class="lead text-muted">Dikirim dari <code>Hello</code> controller ke view <code>hello.php</code>.</p>

    <form class="row g-2 justify-content-center mt-4" onsubmit="location.href='<?= site_url('hello') ?>/' + encodeURIComponent(this.n.value); return false;">
        <div class="col-auto"><input name="n" class="form-control" placeholder="Ketik nama kamu" required></div>
        <div class="col-auto"><button class="btn btn-primary">Sapa</button></div>
    </form>
    <p class="small text-muted mt-3">Atau buka langsung <code><?= site_url('hello/Agil') ?></code></p>
</div>
<?= $this->endSection() ?>
