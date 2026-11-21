<?= $this->extend('layouts/shop') ?>

<?= $this->section('content') ?>
<?php
$urutList = ['terbaru' => 'Terbaru', 'murah' => 'Harga termurah', 'mahal' => 'Harga termahal', 'nama' => 'Nama A–Z'];
$link = static fn (array $ubah) => site_url('/') . '?' . http_build_query(array_filter(
    array_merge(['kategori' => $kategori, 'q' => $q, 'urut' => $urut === 'terbaru' ? null : $urut], $ubah)
));
?>
<div class="row g-4">
    <aside class="col-lg-3">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Kategori</div>
            <div class="list-group list-group-flush">
                <a href="<?= $link(['kategori' => null]) ?>" class="list-group-item list-group-item-action <?= ! $kategori ? 'active' : '' ?>">Semua Produk</a>
                <?php foreach ($kategoriList as $k): ?>
                    <a href="<?= $link(['kategori' => $k->slug]) ?>" class="list-group-item list-group-item-action d-flex justify-content-between <?= $kategori === $k->slug ? 'active' : '' ?>">
                        <?= esc($k->nama) ?> <span class="badge bg-light text-dark"><?= $k->jumlah ?></span>
                    </a>
                <?php endforeach ?>
            </div>
        </div>
    </aside>

    <section class="col-lg-9">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
            <div>
                <h5 class="mb-0">
                    <?php if ($q): ?>Hasil pencarian "<?= esc($q) ?>"<?php else: ?>Katalog Produk<?php endif ?>
                </h5>
                <small class="text-muted"><?= $pager->getTotal() ?> produk</small>
            </div>
            <form method="get" class="d-flex align-items-center gap-2">
                <?php if ($kategori): ?><input type="hidden" name="kategori" value="<?= esc($kategori) ?>"><?php endif ?>
                <?php if ($q): ?><input type="hidden" name="q" value="<?= esc($q) ?>"><?php endif ?>
                <label class="small text-muted text-nowrap">Urutkan</label>
                <?= form_dropdown('urut', $urutList, $urut, ['class' => 'form-select form-select-sm', 'onchange' => 'this.form.submit()']) ?>
            </form>
        </div>

        <?php if ($produk === []): ?>
            <div class="text-center text-muted py-5"><i class="bi bi-search fs-1"></i><p class="mt-2">Produk tidak ditemukan.</p></div>
        <?php endif ?>

        <div class="row row-cols-2 row-cols-md-3 row-cols-xl-4 g-3">
            <?php foreach ($produk as $p): ?>
                <div class="col"><?= $this->setData(['p' => $p])->include('shop/_kartu_produk') ?></div>
            <?php endforeach ?>
        </div>

        <div class="d-flex justify-content-center mt-4"><?= $pager->links('default', 'bootstrap') ?></div>
    </section>
</div>
<?= $this->endSection() ?>
