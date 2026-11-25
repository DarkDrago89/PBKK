<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Model tabel products.
 * useSoftDeletes: delete() hanya mengisi deleted_at, baris tetap ada
 * sehingga pesanan lama yang menunjuk produk ini tidak rusak.
 */
class ProductModel extends Model
{
    protected $table          = 'products';
    protected $primaryKey     = 'id_product';
    protected $returnType     = 'object';
    protected $useSoftDeletes = true;
    protected $useTimestamps  = true;
    protected $allowedFields  = ['id_category', 'name', 'slug', 'description', 'price', 'stock', 'image', 'is_active', 'created_by'];

    public const UPLOAD_DIR = FCPATH . 'uploads/products/';

    /** Query dasar: produk + data kategorinya. */
    private function withCategory(): static
    {
        return $this->select('products.*, categories.name AS category, categories.icon, categories.color')
            ->join('categories', 'categories.id_category = products.id_category');
    }

    /**
     * Daftar produk dengan pencarian, filter kategori, dan pagination.
     *
     * @param bool $hanyaAktif true untuk katalog customer (sembunyikan produk nonaktif)
     */
    public function search(?string $q, ?int $idCategory, int $perPage, bool $hanyaAktif = false): array
    {
        $this->withCategory();
        if ($q) {
            $this->groupStart()->like('products.name', $q)->orLike('products.description', $q)->groupEnd();
        }
        if ($idCategory) {
            $this->where('products.id_category', $idCategory);
        }
        if ($hanyaAktif) {
            $this->where('products.is_active', 1);
        }

        return $this->orderBy('products.id_product', 'DESC')->paginate($perPage);
    }

    public function findDetail(int $id): ?object
    {
        return $this->withCategory()
            ->select('users.name AS creator')
            ->join('users', 'users.id_user = products.created_by', 'left')
            ->where('products.id_product', $id)
            ->first();
    }

    public function terbaru(int $limit = 8): array
    {
        return $this->withCategory()->where('products.is_active', 1)->orderBy('products.id_product', 'DESC')->findAll($limit);
    }

    public function stokMenipis(int $batas = 5): array
    {
        return $this->withCategory()->where('products.stock <=', $batas)->orderBy('products.stock')->findAll();
    }

    /**
     * Kurangi stok secara atomik: UPDATE ... WHERE stock >= qty.
     * Mengembalikan false jika stok tidak cukup (mis. dua pembeli berebut stok terakhir).
     */
    public function kurangiStok(int $id, int $qty): bool
    {
        $this->builder()
            ->set('stock', 'stock - ' . $qty, false)
            ->where('id_product', $id)
            ->where('stock >=', $qty)
            ->where('deleted_at', null)
            ->update();

        return $this->db->affectedRows() === 1;
    }

    public function tambahStok(int $id, int $qty): void
    {
        $this->builder()->set('stock', 'stock + ' . $qty, false)->where('id_product', $id)->update();
    }

    /** URL gambar produk, atau null jika belum ada gambar. */
    public static function imageUrl(?string $file): ?string
    {
        return $file ? base_url('uploads/products/' . $file) : null;
    }

    /** Hapus file gambar lama dari disk (dipanggil saat gambar diganti). */
    public static function hapusGambar(?string $file): void
    {
        if ($file && is_file(self::UPLOAD_DIR . basename($file))) {
            unlink(self::UPLOAD_DIR . basename($file));
        }
    }
}
