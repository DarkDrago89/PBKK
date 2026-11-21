<?php

namespace App\Controllers\Akun;

use App\Controllers\BaseController;
use App\Models\PesananDetailModel;
use App\Models\PesananModel;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * Riwayat pesanan milik customer yang sedang login.
 */
class Pesanan extends BaseController
{
    public function index(): string
    {
        return view('shop/pesanan_index', [
            'title'   => 'Pesanan Saya',
            'pesanan' => (new PesananModel())->milikUser((int) session('userId')),
        ]);
    }

    public function show(string $kode): string
    {
        $pesanan = $this->milikSaya($kode);

        return view('shop/pesanan_show', [
            'title'   => 'Pesanan ' . $kode,
            'pesanan' => $pesanan,
            'detail'  => (new PesananDetailModel())->where('pesanan_id', $pesanan->id)->findAll(),
        ]);
    }

    public function batal(string $kode)
    {
        $pesanan = $this->milikSaya($kode);

        // Customer hanya boleh membatalkan pesanan yang belum diproses
        if ($pesanan->status !== 'menunggu' || ! (new PesananModel())->ubahStatus($pesanan, 'dibatalkan')) {
            return redirect()->back()->with('error', "Pesanan berstatus \"{$pesanan->status}\" tidak dapat dibatalkan.");
        }

        return redirect()->back()->with('success', 'Pesanan dibatalkan, stok produk dikembalikan.');
    }

    /** Pesanan orang lain dianggap tidak ada (404), bukan 403, agar kode pesanan tidak bisa ditebak. */
    private function milikSaya(string $kode): object
    {
        return (new PesananModel())->where(['kode' => $kode, 'user_id' => session('userId')])->first()
            ?? throw PageNotFoundException::forPageNotFound('Pesanan tidak ditemukan.');
    }
}
