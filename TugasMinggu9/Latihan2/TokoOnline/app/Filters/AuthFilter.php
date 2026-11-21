<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Wajib login. URL yang dituju disimpan agar setelah login
 * user dikembalikan ke halaman tersebut (misal: checkout).
 */
class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! session('isLoggedIn')) {
            if ($request->getMethod() === 'GET') {
                session()->set('redirect_url', (string) current_url(true));
            }

            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
