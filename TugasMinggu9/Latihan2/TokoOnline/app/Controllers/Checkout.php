<?php

namespace App\Controllers;

use App\Exceptions\StokException;
use App\Models\PesananModel;
use App\Models\UserModel;

class Checkout extends BaseController
{
    public function index()
    {
        $keranjang = service('keranjang');
        $isi       = $keranjang->isi();

        if ($isi === []) {
            return redirect()->to('keranjang')->with('error', 'Keranjang masih kosong.');
        }

        return view('shop/checkout', [
            'title' => 'Checkout',
            'isi'   => $isi,
            'biaya' => $keranjang->ringkasan($isi),
            'user'  => (new UserModel())->find(session('userId')),
        ]);
    }

    public function proses()
    {
        $rules = [
            'nama_penerima' => 'required|min_length[3]|max_length[100]',
            'telepon'       => 'required|regex_match[/^(\+62|0)8[0-9]{8,12}$/]',
            'alamat'        => 'required|min_length[10]|max_length[500]',
            'catatan'       => 'permit_empty|max_length[255]',
        ];
        $messages = ['telepon' => ['regex_match' => 'Nomor HP harus diawali 08 atau +628 dan berisi 10–14 digit.']];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $keranjang = service('keranjang');
        $isi       = $keranjang->isi();

        if ($isi === []) {
            return redirect()->to('keranjang')->with('error', 'Keranjang masih kosong.');
        }

        try {
            $kode = (new PesananModel())->buat(
                (int) session('userId'),
                $this->validator->getValidated(),
                $isi,
                $keranjang->ringkasan($isi),
            );
        } catch (StokException $e) {
            return redirect()->to('keranjang')->with('error', $e->getMessage());
        }

        $keranjang->kosongkan();

        return redirect()->to("akun/pesanan/{$kode}")->with('success', "Pesanan {$kode} berhasil dibuat. Terima kasih!");
    }
}
