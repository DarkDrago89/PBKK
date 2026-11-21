<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Membatasi route berdasarkan role. Contoh: 'role:admin' -> $arguments = ['admin'].
 */
class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! in_array(session('role'), (array) $arguments, true)) {
            $tujuan = session('role') === 'admin' ? '/admin' : '/';

            return redirect()->to($tujuan)->with('error', 'Halaman tersebut hanya untuk ' . implode('/', (array) $arguments) . '.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
