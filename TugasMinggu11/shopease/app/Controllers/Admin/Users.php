<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * Kelola User (Role): daftar, tambah (mis. akun operator), ubah role, hapus.
 */
class Users extends BaseController
{
    private UserModel $users;

    public function __construct()
    {
        $this->users = new UserModel();
    }

    // GET /admin/users
    public function index(): string
    {
        $q    = $this->request->getGet('q');
        $role = $this->request->getGet('role');

        return view('admin/users', [
            'title' => 'Kelola User',
            'items' => $this->users->search($q, $role, 10),
            'pager' => $this->users->pager,
            'q'     => $q,
            'role'  => $role,
        ]);
    }

    // GET /admin/users/create
    public function create(): string
    {
        return view('admin/user_form', ['title' => 'Tambah User']);
    }

    // POST /admin/users
    public function store()
    {
        $rules = [
            'name'     => ['label' => 'Nama Lengkap', 'rules' => 'required|min_length[3]|max_length[100]'],
            'email'    => ['label' => 'Email', 'rules' => 'required|valid_email|max_length[100]|is_unique[users.email]'],
            'role'     => ['label' => 'Role', 'rules' => 'required|in_list[customer,admin,operator]'],
            'password' => ['label' => 'Password Awal', 'rules' => 'required|min_length[8]|password_kuat'],
        ];
        $messages = [
            'email'    => ['is_unique' => 'Email sudah terdaftar.'],
            'password' => ['password_kuat' => 'Password harus mengandung huruf besar, huruf kecil, dan angka.'],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $d = $this->validator->getValidated();
        $this->users->insert([
            'name'     => trim($d['name']),
            'email'    => strtolower(trim($d['email'])),
            'role'     => $d['role'],
            'password' => password_hash($d['password'], PASSWORD_DEFAULT),
        ]);

        return redirect()->to('/admin/users')->with('success', "Akun {$d['role']} \"{$d['name']}\" berhasil dibuat.");
    }

    // POST /admin/users/(:num)/role
    public function role(int $id)
    {
        $user = $this->findOrFail($id);
        $role = (string) $this->request->getPost('role');

        if ($id === (int) session('id_user')) {
            return redirect()->back()->with('error', 'Anda tidak dapat mengubah role akun Anda sendiri.');
        }
        if (! in_array($role, UserModel::ROLES, true)) {
            return redirect()->back()->with('error', 'Role tidak valid.');
        }

        $this->users->update($id, ['role' => $role]);

        return redirect()->back()->with('success', "Role {$user->name} diubah dari {$user->role} menjadi {$role}.");
    }

    // POST /admin/users/(:num)/delete
    public function delete(int $id)
    {
        $user = $this->findOrFail($id);

        if ($id === (int) session('id_user')) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $this->users->delete($id); // token "Ingat saya" ikut terhapus (FK CASCADE)

        return redirect()->back()->with('success', "User {$user->name} dihapus.");
    }

    private function findOrFail(int $id): object
    {
        return $this->users->find($id) ?? throw PageNotFoundException::forPageNotFound('User tidak ditemukan.');
    }
}
