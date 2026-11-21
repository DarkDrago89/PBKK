<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukModel extends Model
{
    protected $table         = 'produk';
    protected $returnType    = 'object';
    protected $allowedFields = ['kategori_id', 'nama', 'slug', 'deskripsi', 'harga', 'stok', 'gambar'];
    protected $useTimestamps = true;

    protected $validationRules = [
        'id'          => 'permit_empty|is_natural_no_zero',
        'kategori_id' => 'required|is_not_unique[kategori.id]',
        'nama'        => 'required|min_length[3]|max_length[100]',
        'slug'        => 'required|is_unique[produk.slug,id,{id}]',
        'harga'       => 'required|is_natural_no_zero|less_than[1000000000]',
        'stok'        => 'required|is_natural',
        'deskripsi'   => 'permit_empty|max_length[2000]',
    ];

    protected $validationMessages = [
        'slug'        => ['is_unique' => 'Produk dengan nama tersebut sudah ada.'],
        'kategori_id' => ['required' => 'Kategori wajib dipilih.'],
    ];

    public function withKategori(): static
    {
        return $this->select('produk.*, kategori.nama AS kategori_nama, kategori.slug AS kategori_slug')
            ->join('kategori', 'kategori.id = produk.kategori_id', 'left');
    }

    /**
     * Katalog untuk pembeli: filter kategori, kata kunci, dan urutan.
     */
    public function katalog(?string $kategoriSlug, ?string $q, string $urut, int $perPage = 8): array
    {
        $this->withKategori();

        if ($kategoriSlug) {
            $this->where('kategori.slug', $kategoriSlug);
        }
        if ($q) {
            $this->groupStart()->like('produk.nama', $q)->orLike('produk.deskripsi', $q)->groupEnd();
        }

        match ($urut) {
            'murah' => $this->orderBy('produk.harga', 'ASC'),
            'mahal' => $this->orderBy('produk.harga', 'DESC'),
            'nama'  => $this->orderBy('produk.nama', 'ASC'),
            default => $this->orderBy('produk.id', 'DESC'), // terbaru
        };

        return $this->paginate($perPage);
    }

    public function stokMenipis(int $batas = 5): array
    {
        return $this->where('stok <=', $batas)->orderBy('stok')->findAll();
    }
}
