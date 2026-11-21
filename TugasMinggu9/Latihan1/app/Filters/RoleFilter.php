<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Membatasi route berdasarkan role user.
 * Dipakai di Routes.php sebagai 'role:admin' -> $arguments = ['admin'].
 */
class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $role = session()->get('role');

        if (! in_array($role, (array) $arguments, true)) {
            return redirect()->to('/admin/biodata')
                ->with('error', 'Akses ditolak: fitur ini hanya untuk ' . implode('/', (array) $arguments) . '.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
