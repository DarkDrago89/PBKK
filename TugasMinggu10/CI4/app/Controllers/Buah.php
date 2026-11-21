<?php

namespace App\Controllers;

use App\Models\BuahModel;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * LANGKAH 3: controller dengan parameter dari URL.
 * Nilai placeholder di route ((:segment), (:alpha)) dikirim
 * router sebagai argumen method sesuai urutan $1, $2, ...
 */
class Buah extends BaseController
{
    private BuahModel $model;

    public function __construct()
    {
        $this->model = new BuahModel();
    }

    // GET /buah
    public function index(): string
    {
        return view('buah/index', [
            'title'    => 'Daftar Buah',
            'buah'     => $this->model->all(),
            'kategori' => $this->model->kategori(),
        ]);
    }

    // GET /buah/(:segment)  ->  $slug = "apel-malang"
    public function detail(string $slug): string
    {
        $buah = $this->model->find($slug);

        if ($buah === null) {
            throw PageNotFoundException::forPageNotFound("Buah \"{$slug}\" tidak ditemukan.");
        }

        return view('buah/detail', ['title' => $buah['nama'], 'b' => $buah]);
    }

    // GET /buah/cari?q=apel   (query string, BUKAN segmen URL -> dibaca dari $this->request)
    public function cari()
    {
        $q = trim((string) $this->request->getGet('q'));

        // Kata kunci kosong -> tampilkan semua buah
        if ($q === '') {
            return redirect()->to(url_to('buah'));
        }

        // Cocokkan nama, asal, atau kategori (tidak peka huruf besar/kecil)
        $buah = array_values(array_filter(
            $this->model->all(),
            static fn ($b) => stripos($b['nama'] . ' ' . $b['asal'] . ' ' . $b['kategori'], $q) !== false
        ));

        return view('buah/index', [
            'title'    => 'Hasil pencarian "' . $q . '"',
            'buah'     => $buah,
            'kategori' => $this->model->kategori(),
            'q'        => $q,
        ]);
    }

    // GET /buah/kategori/(:alpha)  ->  $kategori = "impor"
    public function kategori(string $kategori): string
    {
        $buah = $this->model->byKategori($kategori);

        if ($buah === []) {
            throw PageNotFoundException::forPageNotFound("Kategori \"{$kategori}\" tidak ditemukan.");
        }

        return view('buah/index', [
            'title'    => 'Buah ' . ucfirst($kategori),
            'buah'     => $buah,
            'kategori' => $this->model->kategori(),
            'aktif'    => $kategori,
        ]);
    }
}
