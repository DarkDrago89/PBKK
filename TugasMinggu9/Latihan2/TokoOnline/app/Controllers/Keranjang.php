<?php

namespace App\Controllers;

use App\Models\ProdukModel;

/**
 * Keranjang belanja. Logika penyimpanan ada di service('keranjang')
 * (App\Libraries\Keranjang), controller hanya memvalidasi input & stok.
 */
class Keranjang extends BaseController
{
    public function index(): string
    {
        $keranjang = service('keranjang');
        $isi       = $keranjang->isi();

        return view('shop/keranjang', [
            'title'     => 'Keranjang',
            'isi'       => $isi,
            'biaya'     => $keranjang->ringkasan($isi),
            'cfg'       => config('Toko'),
        ]);
    }

    public function tambah()
    {
        $produk = (new ProdukModel())->find((int) $this->request->getPost('produk_id'));
        $qty    = max(1, (int) $this->request->getPost('qty'));

        if ($produk === null) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }

        $keranjang = service('keranjang');
        $baru      = $keranjang->qty($produk->id) + $qty;

        if ($produk->stok == 0) {
            return redirect()->back()->with('error', "Maaf, stok {$produk->nama} sedang habis.");
        }
        if ($baru > $produk->stok) {
            return redirect()->back()->with('error', "Stok {$produk->nama} hanya {$produk->stok}, di keranjang sudah ada {$keranjang->qty($produk->id)}.");
        }

        $keranjang->set($produk->id, $baru);

        return redirect()->back()->with('success', "{$produk->nama} ditambahkan ke keranjang.");
    }

    public function ubah()
    {
        $keranjang = service('keranjang');
        $produk    = array_column((new ProdukModel())->findAll(), null, 'id');
        $pesan     = [];

        foreach ((array) $this->request->getPost('qty') as $id => $qty) {
            $id  = (int) $id;
            $qty = max(0, (int) $qty);
            if (! isset($produk[$id])) {
                continue;
            }
            if ($qty > $produk[$id]->stok) {
                $qty     = (int) $produk[$id]->stok;
                $pesan[] = "Jumlah {$produk[$id]->nama} disesuaikan dengan stok ({$qty}).";
            }
            $keranjang->set($id, $qty);
        }

        return redirect()->to('keranjang')
            ->with($pesan ? 'error' : 'success', $pesan ? implode(' ', $pesan) : 'Keranjang diperbarui.');
    }

    public function hapus(int $id)
    {
        service('keranjang')->hapus($id);

        return redirect()->to('keranjang')->with('success', 'Produk dihapus dari keranjang.');
    }
}
