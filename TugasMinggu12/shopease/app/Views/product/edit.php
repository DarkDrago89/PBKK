<?php /** UPDATE: form ubah produk. Route: GET /products/{id}/edit -> POST /products/{id}/update (filter auth:admin,operator). */ ?>
<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<div class="small text-muted mb-3">
    Terakhir diubah <?= date('d M Y H:i', strtotime($p->updated_at)) ?> &middot;
    <a href="<?= site_url('products/' . $p->id_product) ?>">Lihat detail</a>
</div>
<?= view('product/_form', ['p' => $p, 'kategori' => $kategori, 'action' => site_url('products/' . $p->id_product . '/update')]) ?>
<?= $this->endSection() ?>
