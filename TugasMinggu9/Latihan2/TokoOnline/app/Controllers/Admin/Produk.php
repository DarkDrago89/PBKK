<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KategoriModel;
use App\Models\ProdukModel;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * CRUD produk + upload gambar.
 */
class Produk extends BaseController
{
    private const UPLOAD_DIR = FCPATH . 'uploads/produk';

    private ProdukModel $model;

    public function __construct()
    {
        $this->model = new ProdukModel();
    }

    public function index(): string
    {
        $q = $this->request->getGet('q');
        $this->model->withKategori();
        if ($q) {
            $this->model->groupStart()->like('produk.nama', $q)->orLike('kategori.nama', $q)->groupEnd();
        }

        return view('admin/produk_index', [
            'title'  => 'Produk',
            'produk' => $this->model->orderBy('produk.id', 'DESC')->paginate(10),
            'pager'  => $this->model->pager,
            'q'      => $q,
        ]);
    }

    public function create(): string
    {
        return $this->form(null);
    }

    public function edit(int $id): string
    {
        return $this->form($this->findOrFail($id));
    }

    public function store()
    {
        return $this->simpan(null);
    }

    public function update(int $id)
    {
        return $this->simpan($this->findOrFail($id));
    }

    public function delete(int $id)
    {
        $p = $this->findOrFail($id);
        $this->model->delete($id); // detail pesanan lama tetap ada (produk_id -> NULL, nama & harga tersimpan)
        $this->hapusGambar($p->gambar);

        return redirect()->to('admin/produk')->with('success', "Produk {$p->nama} dihapus.");
    }

    // -----------------------------------------------------------------

    private function form(?object $p): string
    {
        return view('admin/produk_form', [
            'title'    => $p ? 'Edit Produk' : 'Tambah Produk',
            'p'        => $p,
            'action'   => $p ? site_url("admin/produk/{$p->id}/update") : site_url('admin/produk/store'),
            'kategori' => array_column((new KategoriModel())->orderBy('nama')->findAll(), 'nama', 'id'),
        ]);
    }

    private function simpan(?object $lama)
    {
        $data         = $this->request->getPost(['kategori_id', 'nama', 'deskripsi', 'harga', 'stok']);
        $data['nama'] = trim((string) $data['nama']);
        $data['slug'] = url_title($data['nama'], '-', true);
        if ($lama) {
            $data['id'] = $lama->id;
        }

        $file = $this->request->getFile('gambar');
        $adaFile = $file !== null && $file->getError() !== UPLOAD_ERR_NO_FILE;

        if ($adaFile && ! $this->validate([
            'gambar' => 'is_image[gambar]|mime_in[gambar,image/jpeg,image/png,image/webp]|max_size[gambar,2048]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        if (! $this->model->validate($data)) {
            return redirect()->back()->withInput()->with('errors', $this->model->errors());
        }

        if ($adaFile && $file->isValid()) {
            $data['gambar'] = $file->getRandomName();
            $file->move(self::UPLOAD_DIR, $data['gambar']);
        }

        $this->model->skipValidation(true)->save($data);

        if ($lama && isset($data['gambar'])) {
            $this->hapusGambar($lama->gambar);
        }

        return redirect()->to('admin/produk')->with('success', "Produk {$data['nama']} disimpan.");
    }

    private function hapusGambar(?string $nama): void
    {
        if ($nama && is_file(self::UPLOAD_DIR . '/' . $nama)) {
            unlink(self::UPLOAD_DIR . '/' . $nama);
        }
    }

    private function findOrFail(int $id): object
    {
        return $this->model->find($id) ?? throw PageNotFoundException::forPageNotFound('Produk tidak ditemukan.');
    }
}
