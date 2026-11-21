<?php

namespace App\Controllers;

use App\Models\BiodataModel;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * Controller CRUD Biodata Mahasiswa + upload foto.
 * Controller hanya menghubungkan View <-> Model (tidak ada SQL di sini).
 */
class Biodata extends BaseController
{
    /** Folder upload (di dalam public/ agar bisa diakses browser). */
    private const UPLOAD_DIR = FCPATH . 'uploads/foto';

    private const FOTO_RULES = [
        'foto' => [
            'label' => 'Foto',
            'rules' => 'is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png,image/webp]|max_size[foto,2048]',
        ],
    ];

    private BiodataModel $model;

    public function __construct()
    {
        $this->model = new BiodataModel();
    }

    // READ (list + search + pagination)
    public function index(): string
    {
        $keyword = $this->request->getGet('q');

        return view('biodata/index', [
            'title'   => 'Data Biodata',
            'items'   => $this->model->search($keyword, 5),
            'pager'   => $this->model->pager,
            'keyword' => $keyword,
        ]);
    }

    // READ (detail)
    public function show(int $id): string
    {
        return view('biodata/show', [
            'title' => 'Detail Biodata',
            'item'  => $this->findOrFail($id),
        ]);
    }

    // CREATE (form)
    public function create(): string
    {
        return view('biodata/form', [
            'title' => 'Tambah Biodata',
            'item'  => null,
            'action' => site_url('admin/biodata/store'),
        ]);
    }

    // CREATE (simpan)
    public function store()
    {
        if (! $this->validFoto()) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data         = $this->formData();
        $data['foto'] = $this->uploadFoto();

        if (! $this->model->insert($data)) {
            $this->hapusFoto($data['foto']);

            return redirect()->back()->withInput()->with('errors', $this->model->errors());
        }

        return redirect()->to('/admin/biodata')->with('success', 'Biodata berhasil ditambahkan.');
    }

    // UPDATE (form)
    public function edit(int $id): string
    {
        return view('biodata/form', [
            'title'  => 'Edit Biodata',
            'item'   => $this->findOrFail($id),
            'action' => site_url("admin/biodata/{$id}/update"),
        ]);
    }

    // UPDATE (simpan)
    public function update(int $id)
    {
        $lama = $this->findOrFail($id);

        if (! $this->validFoto()) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data       = $this->formData();
        $data['id'] = $id; // dibutuhkan rule is_unique[biodata.nim,id,{id}]
        $fotoBaru   = $this->uploadFoto();

        if ($fotoBaru !== null) {
            $data['foto'] = $fotoBaru;
        }

        if (! $this->model->save($data)) {
            $this->hapusFoto($fotoBaru);

            return redirect()->back()->withInput()->with('errors', $this->model->errors());
        }

        if ($fotoBaru !== null) {
            $this->hapusFoto($lama->foto); // foto lama diganti
        }

        return redirect()->to('/admin/biodata')->with('success', 'Biodata berhasil diperbarui.');
    }

    // DELETE
    public function delete(int $id)
    {
        $item = $this->findOrFail($id);
        $this->model->delete($id);
        $this->hapusFoto($item->foto);

        return redirect()->to('/admin/biodata')->with('success', 'Biodata ' . $item->nama . ' berhasil dihapus.');
    }

    // ---------------------------------------------------------------------

    private function findOrFail(int $id): object
    {
        $item = $this->model->find($id);

        if ($item === null) {
            throw PageNotFoundException::forPageNotFound('Biodata tidak ditemukan.');
        }

        return $item;
    }

    /**
     * Validasi foto hanya bila user memilih file (foto bersifat opsional).
     */
    private function validFoto(): bool
    {
        $file = $this->request->getFile('foto');

        if ($file === null || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return true;
        }

        return $this->validate(self::FOTO_RULES);
    }

    private function formData(): array
    {
        return $this->request->getPost([
            'nim', 'nama', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'prodi', 'email', 'alamat',
        ]);
    }

    /**
     * Memindahkan file upload ke public/uploads/foto dengan nama acak.
     * Mengembalikan nama file, atau null bila tidak ada file yang diupload.
     */
    private function uploadFoto(): ?string
    {
        $file = $this->request->getFile('foto');

        if ($file === null || ! $file->isValid() || $file->hasMoved()) {
            return null;
        }

        $namaBaru = $file->getRandomName();
        $file->move(self::UPLOAD_DIR, $namaBaru);

        return $namaBaru;
    }

    private function hapusFoto(?string $nama): void
    {
        if ($nama !== null && $nama !== '' && is_file(self::UPLOAD_DIR . '/' . $nama)) {
            unlink(self::UPLOAD_DIR . '/' . $nama);
        }
    }
}
