<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\Model;

/**
 * Controller dasar CRUD master data (Mahasiswa, Dosen, Mata Kuliah).
 * Subclass cukup menentukan model, nama URI, dan field form.
 *
 * Bila $role diisi, setiap record punya akun login di tabel users:
 * akun dibuat/diubah/dihapus bersamaan dalam SATU transaksi database.
 */
abstract class MasterController extends BaseController
{
    protected Model $model;

    /** Segmen URL & folder view, contoh: 'mahasiswa'. */
    protected string $uri;

    /** Label untuk judul & pesan, contoh: 'Mahasiswa'. */
    protected string $label;

    /** Field yang diambil dari form. */
    protected array $fields;

    /** Role akun login (dosen/mahasiswa), null = tanpa akun. */
    protected ?string $role = null;

    /** Field yang dipakai sebagai username akun (nim/nip). */
    protected string $usernameField = '';

    abstract protected function makeModel(): Model;

    public function __construct()
    {
        $this->model = $this->makeModel();
    }

    /** Data tambahan untuk form (misal daftar dropdown). */
    protected function formData(): array
    {
        return [];
    }

    /** Hook untuk merapikan input sebelum validasi. */
    protected function normalize(array $data): array
    {
        return array_map(static fn ($v) => is_string($v) ? trim($v) : $v, $data);
    }

    // -----------------------------------------------------------------

    public function index(): string
    {
        $q = $this->request->getGet('q');

        return view("admin/{$this->uri}/index", [
            'title' => 'Data ' . $this->label,
            'items' => $this->model->search($q, 10),
            'pager' => $this->model->pager,
            'q'     => $q,
        ]);
    }

    public function create(): string
    {
        return view("admin/{$this->uri}/form", [
            'title'  => 'Tambah ' . $this->label,
            'item'   => null,
            'action' => site_url("admin/{$this->uri}/store"),
        ] + $this->formData());
    }

    public function store()
    {
        return $this->simpan(null);
    }

    public function edit(int $id): string
    {
        return view("admin/{$this->uri}/form", [
            'title'  => 'Edit ' . $this->label,
            'item'   => $this->findOrFail($id),
            'action' => site_url("admin/{$this->uri}/{$id}/update"),
        ] + $this->formData());
    }

    public function update(int $id)
    {
        return $this->simpan($id);
    }

    public function delete(int $id)
    {
        $item = $this->findOrFail($id);

        if ($this->role !== null) {
            // Hapus akun -> FK ON DELETE CASCADE ikut menghapus record & KRS-nya
            (new UserModel())->delete($item->user_id);
        } else {
            $this->model->delete($id);
        }

        return redirect()->to("admin/{$this->uri}")->with('success', "{$this->label} berhasil dihapus.");
    }

    // -----------------------------------------------------------------

    protected function simpan(?int $id)
    {
        $data = $this->normalize($this->request->getPost($this->fields));

        if ($id !== null) {
            $lama       = $this->findOrFail($id);
            $data['id'] = $id; // agar rule is_unique[...,id,{id}] mengabaikan record ini
        }

        if (! $this->model->validate($data)) {
            return redirect()->back()->withInput()->with('errors', $this->model->errors());
        }

        $db = db_connect();
        $db->transStart();

        if ($this->role !== null) {
            $users    = new UserModel();
            $username = $data[$this->usernameField];

            if ($id === null) {
                $data['user_id'] = $users->buatAkun($username, $data['nama'], $this->role);
            } else {
                $users->update($lama->user_id, ['username' => $username, 'nama' => $data['nama']]);
            }
        }

        $this->model->skipValidation(true)->save($data);

        $db->transComplete();

        if (! $db->transStatus()) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data, transaksi dibatalkan.');
        }

        $pesan = $id === null ? 'ditambahkan' : 'diperbarui';
        if ($id === null && $this->role !== null) {
            $pesan .= " (akun login: {$username} / {$username})";
        }

        return redirect()->to("admin/{$this->uri}")->with('success', "{$this->label} berhasil {$pesan}.");
    }

    protected function findOrFail(int $id): object
    {
        return $this->model->find($id) ?? throw PageNotFoundException::forPageNotFound("{$this->label} tidak ditemukan.");
    }
}
