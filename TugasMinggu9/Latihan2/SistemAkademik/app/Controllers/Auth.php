<?php

namespace App\Controllers;

use App\Models\DosenModel;
use App\Models\MahasiswaModel;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    public function attempt()
    {
        if (! $this->validate(['username' => 'required', 'password' => 'required'])) {
            return redirect()->back()->withInput()->with('error', 'Username dan password wajib diisi.');
        }

        $user = (new UserModel())->verify(
            (string) $this->request->getPost('username'),
            (string) $this->request->getPost('password'),
        );

        if ($user === null) {
            return redirect()->back()->withInput()->with('error', 'Username atau password salah.');
        }

        // refId = id di tabel dosen/mahasiswa milik user ini
        $refId = match ($user->role) {
            'dosen'     => (new DosenModel())->where('user_id', $user->id)->first()?->id,
            'mahasiswa' => (new MahasiswaModel())->where('user_id', $user->id)->first()?->id,
            default     => null,
        };

        session()->regenerate();
        session()->set([
            'isLoggedIn' => true,
            'userId'     => $user->id,
            'username'   => $user->username,
            'nama'       => $user->nama,
            'role'       => $user->role,
            'refId'      => $refId,
        ]);

        return redirect()->to('/dashboard');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login');
    }
}
