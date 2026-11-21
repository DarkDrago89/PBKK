<div class="card-header py-3 d-flex flex-wrap align-items-center justify-content-between">
    <h6 class="m-0 font-weight-bold text-primary"><?= esc($title) ?></h6>
    <div class="d-flex mt-2 mt-md-0">
        <form class="form-inline mr-2" method="get">
            <input name="q" class="form-control form-control-sm mr-1" placeholder="Cari..." value="<?= esc($q ?? '') ?>">
            <button class="btn btn-sm btn-outline-primary"><i class="fas fa-search"></i></button>
        </form>
        <a href="<?= site_url("admin/{$uri}/create") ?>" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Tambah</a>
    </div>
</div>
