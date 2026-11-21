<?php

namespace App\Controllers\Admin;

use App\Models\DosenModel;
use App\Models\MatakuliahModel;
use CodeIgniter\Model;

class Matakuliah extends MasterController
{
    protected string $uri   = 'matakuliah';
    protected string $label = 'Mata Kuliah';
    protected array $fields = ['kode', 'nama', 'sks', 'semester', 'dosen_id'];

    protected function makeModel(): Model
    {
        return new MatakuliahModel();
    }

    protected function formData(): array
    {
        return ['dosenList' => (new DosenModel())->dropdown()];
    }

    protected function normalize(array $data): array
    {
        $data         = parent::normalize($data);
        $data['kode'] = strtoupper($data['kode'] ?? '');
        // Dropdown "belum ditentukan" mengirim string kosong -> simpan sebagai NULL
        $data['dosen_id'] = ($data['dosen_id'] ?? '') === '' ? null : $data['dosen_id'];

        return $data;
    }
}
