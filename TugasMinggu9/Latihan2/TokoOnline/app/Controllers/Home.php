<?php

namespace App\Controllers;

use App\Models\KategoriModel;
use App\Models\ProdukModel;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * Katalog produk untuk pembeli.
 */
class Home extends BaseController
{
    public function index(): string
    {
        $kategori = $this->request->getGet('kategori');
        $q        = $this->request->getGet('q');
        $urut     = (string) ($this->request->getGet('urut') ?? 'terbaru');
        $model    = new ProdukModel();

        return view('shop/katalog', [
            'title'        => 'Katalog',
            'produk'       => $model->katalog($kategori, $q, $urut, 8),
            'pager'        => $model->pager,
            'kategoriList' => (new KategoriModel())->withJumlahProduk(),
            'kategori'     => $kategori,
            'q'            => $q,
            'urut'         => $urut,
        ]);
    }

    public function produk(string $slug): string
    {
        $model  = new ProdukModel();
        $produk = $model->withKategori()->where('produk.slug', $slug)->first()
            ?? throw PageNotFoundException::forPageNotFound('Produk tidak ditemukan.');

        $terkait = $model->where('kategori_id', $produk->kategori_id)
            ->where('id !=', $produk->id)
            ->orderBy('id', 'RANDOM')
            ->findAll(4);

        return view('shop/produk', [
            'title'      => $produk->nama,
            'p'          => $produk,
            'terkait'    => $terkait,
            'diKeranjang' => service('keranjang')->qty($produk->id),
        ]);
    }
}
