<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * AuthFilter (proteksi akses) sesuai Hands-On Lab.
 *
 *   ['filter' => 'auth']                 -> wajib login (role apa saja)
 *   ['filter' => 'auth:admin']           -> wajib login sebagai admin
 *   ['filter' => 'auth:admin,operator']  -> admin ATAU operator
 */
class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! session()->get('isLoggedIn')) {
            // simpan URL tujuan agar setelah login user dikembalikan ke sini
            if ($request->getMethod() === 'GET') {
                session()->set('redirect_url', current_url(true)->__toString());
            }

            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (! empty($arguments) && ! in_array(session()->get('role'), $arguments, true)) {
            return redirect()->to(service('auth')->homeFor(session()->get('role')))
                ->with('error', 'Akses ditolak: halaman tersebut khusus ' . implode('/', $arguments) . '.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
