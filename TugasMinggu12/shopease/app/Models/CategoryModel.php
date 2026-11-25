<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Model tabel categories (kategori produk).
 */
class CategoryModel extends Model
{
    protected $table         = 'categories';
    protected $primaryKey    = 'id_category';
    protected $returnType    = 'object';
    protected $allowedFields = ['name', 'slug', 'icon', 'color', 'description'];
    protected $useTimestamps = true;

    // Validasi di model -> berlaku untuk insert() & update() dari mana pun.
    // {id_category} diganti otomatis dengan id baris yang sedang di-update (is_unique mengabaikan dirinya sendiri).
    protected $validationRules = [
        'id_category' => 'permit_empty|is_natural_no_zero',
        'name'        => 'required|min_length[3]|max_length[60]|is_unique[categories.name,id_category,{id_category}]',
        'icon'        => 'required|alpha_dash|max_length[40]',
        'color'       => 'required|regex_match[/^#[0-9a-fA-F]{6}$/]',
        'description' => 'permit_empty|max_length[500]',
    ];
    protected $validationMessages = [
        'name'  => ['is_unique' => 'Nama kategori sudah ada.', 'required' => 'Nama kategori wajib diisi.', 'min_length' => 'Nama kategori minimal 3 karakter.'],
        'icon'  => ['alpha_dash' => 'Ikon hanya boleh huruf, angka, dan tanda minus (contoh: bag-heart).'],
        'color' => ['regex_match' => 'Warna harus berformat #RRGGBB.'],
    ];

    /** Semua kategori + jumlah produk aktif di dalamnya. */
    public function withProductCount(): array
    {
        return $this->select('categories.*, COUNT(products.id_product) AS jumlah')
            ->join('products', 'products.id_category = categories.id_category AND products.deleted_at IS NULL', 'left', false)
            ->groupBy('categories.id_category')
            ->orderBy('categories.name')
            ->findAll();
    }

    /** Pilihan dropdown: [id => nama] */
    public function options(): array
    {
        return array_column($this->orderBy('name')->findAll(), 'name', 'id_category');
    }
}
