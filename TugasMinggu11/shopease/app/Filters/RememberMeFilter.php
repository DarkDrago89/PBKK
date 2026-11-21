<?php

namespace App\Filters;

use App\Libraries\Auth;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Filter global: jika session sudah habis tetapi browser masih menyimpan
 * cookie "Ingat saya" yang valid, user otomatis login kembali.
 */
class RememberMeFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session()->get('isLoggedIn')) {
            return;
        }

        service('auth')->loginDariCookie($request->getCookie(Auth::REMEMBER_COOKIE));
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
