<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KategoriModel;

/**
 * CRUD kategori dalam satu halaman (daftar + form di samping).
 */
class Kategori extends BaseController
{
    private KategoriModel $model;

    public function __construct()
    {
        $this->model = new KategoriModel();
    }

    /** GET /admin/kategori atau /admin/kategori/{id}/edit */
    public function index(?int $id = null): string
    {
        return view('admin/kategori', [
            'title'    => 'Kategori',
            'kategori' => $this->model->withJumlahProduk(),
            'edit'     => $id ? $this->model->find($id) : null,
        ]);
    }

    public function simpan(?int $id = null)
    {
        $nama = trim((string) $this->request->getPost('nama'));
        $data = ['nama' => $nama, 'slug' => url_title($nama, '-', true)];

        if ($id !== null) {
            $data['id'] = $id;
        }

        if (! $this->model->save($data)) {
            return redirect()->back()->withInput()->with('errors', $this->model->errors());
        }

        return redirect()->to('admin/kategori')->with('success', "Kategori \"{$nama}\" disimpan.");
    }

    public function delete(int $id)
    {
        $this->model->delete($id); // produk di kategori ini -> kategori_id = NULL (FK SET NULL)

        return redirect()->to('admin/kategori')->with('success', 'Kategori dihapus. Produk di dalamnya menjadi tanpa kategori.');
    }
}
