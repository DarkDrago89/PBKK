<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PesananDetailModel;
use App\Models\PesananModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Pesanan extends BaseController
{
    public function index(): string
    {
        $status = $this->request->getGet('status');
        $model  = (new PesananModel())->withUser();

        if ($status) {
            $model->where('pesanan.status', $status);
        }

        return view('admin/pesanan_index', [
            'title'   => 'Pesanan',
            'pesanan' => $model->orderBy('pesanan.id', 'DESC')->paginate(10),
            'pager'   => $model->pager,
            'status'  => $status,
            'cfg'     => config('Toko'),
        ]);
    }

    public function show(int $id): string
    {
        $pesanan = $this->findOrFail($id);
        $cfg     = config('Toko');

        // Tombol status yang ditampilkan = transisi yang valid dari status saat ini
        $pilihan = array_filter(
            array_keys($cfg->status),
            static fn ($ke) => PesananModel::transisiValid($pesanan->status, $ke),
        );

        return view('admin/pesanan_show', [
            'title'   => 'Pesanan ' . $pesanan->kode,
            'pesanan' => $pesanan,
            'detail'  => (new PesananDetailModel())->where('pesanan_id', $id)->findAll(),
            'pilihan' => $pilihan,
            'cfg'     => $cfg,
        ]);
    }

    public function status(int $id)
    {
        $pesanan = $this->findOrFail($id);
        $ke      = (string) $this->request->getPost('status');

        if (! (new PesananModel())->ubahStatus($pesanan, $ke)) {
            return redirect()->back()->with('error', "Status tidak bisa diubah dari \"{$pesanan->status}\" ke \"{$ke}\".");
        }

        return redirect()->back()->with('success', "Status pesanan {$pesanan->kode} menjadi \"{$ke}\".");
    }

    private function findOrFail(int $id): object
    {
        return (new PesananModel())->withUser()->where('pesanan.id', $id)->first()
            ?? throw PageNotFoundException::forPageNotFound('Pesanan tidak ditemukan.');
    }
}
