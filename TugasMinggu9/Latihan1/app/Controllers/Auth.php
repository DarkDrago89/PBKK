<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/admin');
        }

        return view('auth/login', ['title' => 'Login']);
    }

    public function attempt()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Username dan password wajib diisi.');
        }

        $user = (new UserModel())->verify(
            (string) $this->request->getPost('username'),
            (string) $this->request->getPost('password'),
        );

        if ($user === null) {
            return redirect()->back()->withInput()->with('error', 'Username atau password salah.');
        }

        session()->regenerate(); // cegah session fixation
        session()->set([
            'isLoggedIn' => true,
            'userId'     => $user->id,
            'username'   => $user->username,
            'nama'       => $user->nama,
            'role'       => $user->role,
        ]);

        return redirect()->to('/admin')->with('success', 'Selamat datang, ' . $user->nama . '!');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login');
    }
}
