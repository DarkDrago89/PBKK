<?php

namespace App\Controllers;

use App\Models\UserModel;

/**
 * AuthController: registrasi, login, logout (Hands-On Lab ShopEase).
 */
class AuthController extends BaseController
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // GET /register
    public function register(): string
    {
        return view('auth/register', ['title' => 'Registrasi']);
    }

    // POST /register
    public function store()
    {
        $rules = [
            'name'             => ['label' => 'Nama Lengkap', 'rules' => 'required|min_length[3]|max_length[100]'],
            'email'            => ['label' => 'Email', 'rules' => 'required|valid_email|max_length[100]|is_unique[users.email]'],
            'phone'            => ['label' => 'Nomor Telepon', 'rules' => 'permit_empty|regex_match[/^(\+62|0)8[0-9]{8,12}$/]'],
            'address'          => ['label' => 'Alamat', 'rules' => 'permit_empty|max_length[500]'],
            'password'         => ['label' => 'Password', 'rules' => 'required|min_length[8]|password_kuat'],
            'password_confirm' => ['label' => 'Konfirmasi Password', 'rules' => 'required|matches[password]'],
        ];
        $messages = [
            'email'            => ['is_unique' => 'Email sudah terdaftar. Silakan login.'],
            'phone'            => ['regex_match' => 'Nomor telepon harus diawali 08 atau +628 (10–14 digit).'],
            'password'         => ['password_kuat' => 'Password harus mengandung huruf besar, huruf kecil, dan angka.'],
            'password_confirm' => ['matches' => 'Konfirmasi password tidak sama.'],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->userModel->insert([
            'name'     => trim($this->request->getPost('name')),
            'email'    => strtolower(trim($this->request->getPost('email'))),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => 'customer', // registrasi publik selalu customer
            'phone'    => $this->request->getPost('phone') ?: null,
            'address'  => $this->request->getPost('address') ?: null,
        ]);

        return redirect()->to('/login')
            ->with('success', 'Registrasi berhasil! Silakan login dengan akun baru Anda.')
            ->with('email', strtolower(trim($this->request->getPost('email'))));
    }

    // GET /login
    public function login(): string
    {
        return view('auth/login', ['title' => 'Login']);
    }

    // POST /login
    public function authenticate()
    {
        // Batasi 5 percobaan login per menit untuk kombinasi IP + email (anti brute force).
        // Kunci tidak hanya IP agar pengguna lain di jaringan yang sama (kos/kantor) tidak ikut terkunci.
        $throttler = service('throttler');
        $kunci     = 'login-' . md5($this->request->getIPAddress() . '|' . strtolower(trim((string) $this->request->getPost('email'))));
        if (! $throttler->check($kunci, 5, MINUTE)) {
            return redirect()->back()->withInput()
                ->with('error', 'Terlalu banyak percobaan login. Coba lagi dalam ' . max(1, $throttler->getTokenTime()) . ' detik.');
        }

        if (! $this->validate([
            'email'    => ['label' => 'Email', 'rules' => 'required|valid_email'],
            'password' => ['label' => 'Password', 'rules' => 'required'],
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $auth = service('auth');
        $user = $auth->attempt((string) $this->request->getPost('email'), (string) $this->request->getPost('password'));

        if ($user === null) {
            // pesan sengaja umum: tidak memberi tahu apakah email terdaftar atau tidak
            return redirect()->back()->withInput()->with('error', 'Email atau password salah.');
        }

        $auth->login($user, (bool) $this->request->getPost('remember'));

        // Kembali ke halaman yang tadi diminta (mis. /products/3), bila ada; selain itu sesuai role
        $tujuan = session('redirect_url') ?? $auth->homeFor($user->role);
        session()->remove('redirect_url');

        return redirect()->to($tujuan)
            ->with('success', 'Selamat datang, ' . $user->name . '!')
            ->withCookies(); // bawa cookie "Ingat saya" ke response redirect
    }

    // POST /logout
    public function logout()
    {
        service('auth')->logout();

        return redirect()->to('/login')
            ->with('success', 'Anda telah logout.')
            ->withCookies(); // bawa penghapusan cookie "Ingat saya"
    }
}
