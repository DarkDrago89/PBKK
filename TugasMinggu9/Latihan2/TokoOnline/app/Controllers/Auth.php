<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session('isLoggedIn')) {
            return redirect()->to(session('role') === 'admin' ? '/admin' : '/');
        }

        return view('auth/login', ['title' => 'Login']);
    }

    public function attempt()
    {
        if (! $this->validate(['login' => 'required', 'password' => 'required'])) {
            return redirect()->back()->withInput()->with('error', 'Username/email dan password wajib diisi.');
        }

        $user = (new UserModel())->verify((string) $this->request->getPost('login'), (string) $this->request->getPost('password'));

        if ($user === null) {
            return redirect()->back()->withInput()->with('error', 'Username/email atau password salah.');
        }

        $this->masuk($user);

        // Kembali ke halaman yang tadi diminta (misal /checkout), bila ada
        $tujuan = session('redirect_url') ?? ($user->role === 'admin' ? site_url('admin') : site_url('/'));
        session()->remove('redirect_url');

        return redirect()->to($tujuan)->with('success', 'Selamat datang, ' . $user->nama . '!');
    }

    public function register()
    {
        return view('auth/register', ['title' => 'Daftar Akun']);
    }

    public function simpanRegister()
    {
        $rules = [
            'nama'       => 'required|min_length[3]|max_length[100]',
            'username'   => 'required|alpha_numeric|min_length[4]|max_length[30]|is_unique[users.username]',
            'email'      => 'required|valid_email|is_unique[users.email]',
            'password'   => 'required|min_length[6]',
            'konfirmasi' => 'required|matches[password]',
        ];
        $messages = [
            'username'   => ['is_unique' => 'Username sudah dipakai.'],
            'email'      => ['is_unique' => 'Email sudah terdaftar.'],
            'konfirmasi' => ['matches' => 'Konfirmasi password tidak sama.'],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data  = $this->validator->getValidated();
        $model = new UserModel();
        $id    = $model->insert([
            'nama'     => $data['nama'],
            'username' => $data['username'],
            'email'    => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role'     => 'customer',
        ]);

        $this->masuk($model->find($id));

        $tujuan = session('redirect_url') ?? site_url('/');
        session()->remove('redirect_url');

        return redirect()->to($tujuan)->with('success', 'Akun berhasil dibuat. Selamat berbelanja!');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/');
    }

    private function masuk(object $user): void
    {
        // regenerate() mengganti ID session (cegah session fixation),
        // tetapi isinya tetap -> keranjang yang diisi saat belum login tidak hilang
        session()->regenerate();
        session()->set([
            'isLoggedIn' => true,
            'userId'     => $user->id,
            'username'   => $user->username,
            'nama'       => $user->nama,
            'role'       => $user->role,
        ]);
    }
}
