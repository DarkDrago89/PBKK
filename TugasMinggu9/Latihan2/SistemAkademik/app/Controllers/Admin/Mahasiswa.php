<?php

namespace App\Controllers\Admin;

use App\Models\MahasiswaModel;
use CodeIgniter\Model;

class Mahasiswa extends MasterController
{
    protected string $uri           = 'mahasiswa';
    protected string $label         = 'Mahasiswa';
    protected array $fields         = ['nim', 'nama', 'prodi', 'angkatan', 'email'];
    protected ?string $role         = 'mahasiswa';
    protected string $usernameField = 'nim';

    protected function makeModel(): Model
    {
        return new MahasiswaModel();
    }

    protected function formData(): array
    {
        return ['prodiList' => config('Akademik')->prodi];
    }
}
