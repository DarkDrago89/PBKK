<?php /** CREATE: form tambah produk. Route: GET /products/create -> POST /products (filter auth:admin,operator). */ ?>
<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<?= view('product/_form', ['p' => null, 'kategori' => $kategori, 'action' => site_url('products')]) ?>
<?= $this->endSection() ?>
