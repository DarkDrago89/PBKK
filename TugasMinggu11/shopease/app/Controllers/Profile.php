<?php

namespace App\Controllers;

use App\Models\AuthTokenModel;
use App\Models\UserModel;

/**
 * Profil Saya: ubah data diri & ganti password (semua role).
 */
class Profile extends BaseController
{
    private UserModel $users;

    public function __construct()
    {
        $this->users = new UserModel();
    }

    private function saya(): object
    {
        return $this->users->find(session('id_user'));
    }

    // GET /profil
    public function index(): string
    {
        $layout = session('role') === 'customer' ? 'layouts/store' : 'layouts/panel';

        return view('profile/index', ['title' => 'Profil Saya', 'user' => $this->saya(), 'layout' => $layout]);
    }

    // POST /profil
    public function update()
    {
        $rules = [
            'name'    => ['label' => 'Nama Lengkap', 'rules' => 'required|min_length[3]|max_length[100]'],
            'phone'   => ['label' => 'Nomor Telepon', 'rules' => 'permit_empty|regex_match[/^(\+62|0)8[0-9]{8,12}$/]'],
            'address' => ['label' => 'Alamat', 'rules' => 'permit_empty|max_length[500]'],
        ];
        $messages = ['phone' => ['regex_match' => 'Nomor telepon harus diawali 08 atau +628 (10–14 digit).']];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->validator->getValidated();
        $this->users->update(session('id_user'), [
            'name'    => trim($data['name']),
            'phone'   => ($data['phone'] ?? '') ?: null,
            'address' => ($data['address'] ?? '') ?: null,
        ]);
        service('auth')->refresh($this->saya());

        return redirect()->to('/profil')->with('success', 'Profil berhasil diperbarui.');
    }

    // POST /profil/password
    public function password()
    {
        $rules = [
            'current_password' => ['label' => 'Password Saat Ini', 'rules' => 'required'],
            'new_password'     => ['label' => 'Password Baru', 'rules' => 'required|min_length[8]|password_kuat'],
            'confirm_password' => ['label' => 'Konfirmasi Password Baru', 'rules' => 'required|matches[new_password]'],
        ];
        $messages = [
            'new_password'     => ['password_kuat' => 'Password harus mengandung huruf besar, huruf kecil, dan angka.'],
            'confirm_password' => ['matches' => 'Konfirmasi password baru tidak sama.'],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $user = $this->saya();
        if (! password_verify((string) $this->request->getPost('current_password'), $user->password)) {
            return redirect()->back()->with('error', 'Password saat ini salah.');
        }

        $this->users->update($user->id_user, [
            'password' => password_hash((string) $this->request->getPost('new_password'), PASSWORD_DEFAULT),
        ]);
        // Cabut semua token "Ingat saya" -> perangkat lain harus login ulang
        (new AuthTokenModel())->where('id_user', $user->id_user)->delete();
        session()->regenerate(true);

        return redirect()->to('/profil')->with('success', 'Password berhasil diganti.');
    }
}
