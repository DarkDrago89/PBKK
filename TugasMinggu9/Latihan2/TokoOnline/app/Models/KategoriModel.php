<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriModel extends Model
{
    protected $table         = 'kategori';
    protected $returnType    = 'object';
    protected $allowedFields = ['nama', 'slug'];
    protected $useTimestamps = true;

    protected $validationRules = [
        'id'   => 'permit_empty|is_natural_no_zero',
        'nama' => 'required|min_length[3]|max_length[50]',
        'slug' => 'required|is_unique[kategori.slug,id,{id}]',
    ];

    protected $validationMessages = [
        'slug' => ['is_unique' => 'Kategori dengan nama tersebut sudah ada.'],
    ];

    public function withJumlahProduk(): array
    {
        return $this->select('kategori.*, COUNT(produk.id) AS jumlah')
            ->join('produk', 'produk.kategori_id = kategori.id', 'left')
            ->groupBy('kategori.id')
            ->orderBy('kategori.nama')
            ->findAll();
    }
}
