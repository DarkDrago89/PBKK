<?php

namespace App\Controllers\Admin;

use App\Models\DosenModel;
use CodeIgniter\Model;

class Dosen extends MasterController
{
    protected string $uri           = 'dosen';
    protected string $label         = 'Dosen';
    protected array $fields         = ['nip', 'nama', 'email'];
    protected ?string $role         = 'dosen';
    protected string $usernameField = 'nip';

    protected function makeModel(): Model
    {
        return new DosenModel();
    }
}
