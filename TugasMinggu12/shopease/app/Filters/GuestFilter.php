<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Halaman login & registrasi hanya untuk tamu.
 * User yang sudah login diarahkan ke halaman utama role-nya.
 */
class GuestFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to(service('auth')->homeFor(session()->get('role')))
                ->with('info', 'Anda sudah login sebagai ' . session()->get('name') . '.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
