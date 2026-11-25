<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\OrderModel;
use App\Models\ProductModel;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * Product Management: CRUD produk (dengan upload gambar) + CRUD kategori.
 *
 * Hak akses ditentukan di Routes.php (AuthFilter):
 *   READ   (index, detail)                       -> semua user yang login
 *   CREATE / UPDATE produk & seluruh CRUD kategori -> admin, operator
 *   DELETE produk                                -> admin saja
 */
class ProductController extends BaseController
{
    private ProductModel $products;
    private CategoryModel $categories;

    public function __construct()
    {
        $this->products   = new ProductModel();
        $this->categories = new CategoryModel();
    }

    /** admin & operator mengelola produk; customer hanya berbelanja. */
    private function isStaff(): bool
    {
        return in_array(session('role'), ['admin', 'operator'], true);
    }

    // =====================================================================
    // READ
    // =====================================================================

    // GET /products
    public function index(): string
    {
        $cfg   = config('ShopEase');
        $q     = trim((string) $this->request->getGet('q'));
        $idKat = (int) $this->request->getGet('kategori') ?: null;
        $staff = $this->isStaff();

        $data = [
            'items'    => $this->products->search($q, $idKat, $staff ? $cfg->perPageKelola : $cfg->perPageKatalog, ! $staff),
            'pager'    => $this->products->pager,
            'kategori' => $this->categories->withProductCount(),
            'q'        => $q,
            'idKat'    => $idKat,
        ];

        // Satu URL, dua tampilan: tabel kelola (staff) atau katalog belanja (customer)
        return $staff
            ? view('product/index', ['title' => 'Kelola Produk'] + $data)
            : view('product/catalog', ['title' => 'Katalog Produk'] + $data);
    }

    // GET /products/(:num)
    public function detail(int $id): string
    {
        $p = $this->products->findDetail($id);

        // produk nonaktif disembunyikan dari customer
        if ($p === null || (! $p->is_active && ! $this->isStaff())) {
            throw PageNotFoundException::forPageNotFound('Produk tidak ditemukan.');
        }

        $data = ['title' => $p->name, 'p' => $p, 'staff' => $this->isStaff()];

        if ($data['staff']) {
            $data['terjual'] = (new OrderModel())->selectSum('qty', 'jumlah')
                ->where('id_product', $id)->where('status !=', 'dibatalkan')->first()->jumlah ?? 0;
        } else {
            $data['alamat'] = service('auth')->user()?->address;
            $data['maks']   = min(config('ShopEase')->maksQty, (int) $p->stock);
        }

        return view('product/detail', $data);
    }

    // =====================================================================
    // CREATE
    // =====================================================================

    // GET /products/create
    public function create(): string
    {
        return view('product/create', [
            'title'    => 'Tambah Produk',
            'kategori' => $this->categories->options(),
        ]);
    }

    // POST /products
    public function store()
    {
        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $d    = $this->validator->getValidated();
        $nama = trim($d['name']);

        $id = $this->products->insert([
            'id_category' => (int) $d['id_category'],
            'name'        => $nama,
            'slug'        => $this->slugUnik($nama),
            'description' => trim((string) ($d['description'] ?? '')),
            'price'       => (int) $d['price'],
            'stock'       => (int) $d['stock'],
            'is_active'   => $this->request->getPost('is_active') ? 1 : 0,
            'image'       => $this->simpanGambar(),
            'created_by'  => session('id_user'),
        ]);

        return redirect()->to('products/' . $id)->with('success', "Produk \"{$nama}\" berhasil ditambahkan.");
    }

    // =====================================================================
    // UPDATE
    // =====================================================================

    // GET /products/(:num)/edit
    public function edit(int $id): string
    {
        return view('product/edit', [
            'title'    => 'Edit Produk',
            'p'        => $this->findOrFail($id),
            'kategori' => $this->categories->options(),
        ]);
    }

    // POST /products/(:num)/update
    public function update(int $id)
    {
        $lama = $this->findOrFail($id);

        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $d    = $this->validator->getValidated();
        $nama = trim($d['name']);
        $data = [
            'id_category' => (int) $d['id_category'],
            'name'        => $nama,
            'description' => trim((string) ($d['description'] ?? '')),
            'price'       => (int) $d['price'],
            'stock'       => (int) $d['stock'],
            'is_active'   => $this->request->getPost('is_active') ? 1 : 0,
        ];
        if ($nama !== $lama->name) {
            $data['slug'] = $this->slugUnik($nama, $id);
        }

        // Gambar baru menggantikan yang lama; checkbox "hapus gambar" mengosongkannya
        $baru = $this->simpanGambar();
        if ($baru !== null || $this->request->getPost('hapus_gambar')) {
            $data['image'] = $baru;
            ProductModel::hapusGambar($lama->image);
        }

        $this->products->update($id, $data);

        return redirect()->to('products/' . $id)->with('success', "Produk \"{$nama}\" berhasil diperbarui.");
    }

    // =====================================================================
    // DELETE (route difilter auth:admin)
    // =====================================================================

    // POST /products/(:num)/delete
    public function delete(int $id)
    {
        $p = $this->findOrFail($id);

        // Soft delete: baris tetap ada (deleted_at terisi) agar riwayat pesanan tetap valid.
        // Gambar tidak dihapus karena masih bisa dirujuk riwayat.
        $this->products->delete($id);

        return redirect()->to('products')->with('success', "Produk \"{$p->name}\" dihapus dari katalog.");
    }

    // =====================================================================
    // CRUD KATEGORI
    // =====================================================================

    // GET /categories   (?edit=ID membuka form edit di halaman yang sama)
    public function categories(): string
    {
        $editId = (int) $this->request->getGet('edit');

        return view('product/categories', [
            'title' => 'Kelola Kategori',
            'items' => $this->categories->withProductCount(),
            'edit'  => $editId ? $this->categories->find($editId) : null,
        ]);
    }

    // POST /categories
    public function storeCategory()
    {
        $data = $this->dataKategori();

        // Validasi dijalankan oleh CategoryModel ($validationRules)
        if ($this->categories->insert($data) === false) {
            return redirect()->back()->withInput()->with('errors', $this->categories->errors());
        }

        return redirect()->to('categories')->with('success', "Kategori \"{$data['name']}\" ditambahkan.");
    }

    // POST /categories/(:num)/update
    public function updateCategory(int $id)
    {
        $this->categories->find($id) ?? throw PageNotFoundException::forPageNotFound('Kategori tidak ditemukan.');
        $data = $this->dataKategori();

        // id_category ikut dikirim agar placeholder {id_category} pada rule is_unique terisi
        if ($this->categories->update($id, ['id_category' => $id] + $data) === false) {
            return redirect()->back()->withInput()->with('errors', $this->categories->errors());
        }

        return redirect()->to('categories')->with('success', "Kategori \"{$data['name']}\" diperbarui.");
    }

    // POST /categories/(:num)/delete
    public function deleteCategory(int $id)
    {
        $k = $this->categories->find($id) ?? throw PageNotFoundException::forPageNotFound('Kategori tidak ditemukan.');

        // FK products.id_category = RESTRICT; produk yang di-soft-delete pun masih menunjuk kategori ini
        $dipakai = $this->products->withDeleted()->where('id_category', $id)->countAllResults();
        if ($dipakai > 0) {
            return redirect()->back()->with('error', "Kategori \"{$k->name}\" tidak bisa dihapus karena masih dipakai {$dipakai} produk.");
        }

        $this->categories->delete($id);

        return redirect()->to('categories')->with('success', "Kategori \"{$k->name}\" dihapus.");
    }

    // =====================================================================
    // Helper
    // =====================================================================

    private function rules(): array
    {
        $rules = [
            'name'        => ['label' => 'Nama Produk', 'rules' => 'required|min_length[3]|max_length[150]'],
            'id_category' => ['label' => 'Kategori', 'rules' => 'required|is_not_unique[categories.id_category]'],
            'price'       => ['label' => 'Harga', 'rules' => 'required|is_natural_no_zero|less_than_equal_to[1000000000]'],
            'stock'       => ['label' => 'Stok', 'rules' => 'required|is_natural|less_than_equal_to[100000]'],
            'description' => ['label' => 'Deskripsi', 'rules' => 'permit_empty|max_length[2000]'],
        ];

        // Gambar opsional: rule file hanya dipasang jika memang ada file yang dikirim
        $file = $this->request->getFile('image');
        if ($file !== null && $file->getError() !== UPLOAD_ERR_NO_FILE) {
            $rules['image'] = ['label' => 'Gambar', 'rules' => 'uploaded[image]|is_image[image]|mime_in[image,image/jpeg,image/png,image/webp]|max_size[image,2048]|max_dims[image,3000,3000]'];
        }

        return $rules;
    }

    /** Pindahkan file upload ke public/uploads/products dengan nama acak. */
    private function simpanGambar(): ?string
    {
        $file = $this->request->getFile('image');
        if ($file === null || ! $file->isValid() || $file->hasMoved()) {
            return null;
        }

        $nama = $file->getRandomName(); // nama acak: mencegah tabrakan & nama file berbahaya
        $file->move(ProductModel::UPLOAD_DIR, $nama);

        return $nama;
    }

    private function slugUnik(string $nama, ?int $kecuali = null): string
    {
        $slug = url_title($nama, '-', true);
        $cek  = $this->products->withDeleted()->where('slug', $slug);
        if ($kecuali) {
            $cek->where('id_product !=', $kecuali);
        }

        return $cek->countAllResults() > 0 ? $slug . '-' . substr(bin2hex(random_bytes(2)), 0, 4) : $slug;
    }

    private function dataKategori(): array
    {
        $nama = trim((string) $this->request->getPost('name'));

        return [
            'name'        => $nama,
            'slug'        => url_title($nama, '-', true),
            'icon'        => trim((string) $this->request->getPost('icon')) ?: 'tag',
            'color'       => (string) $this->request->getPost('color'),
            'description' => trim((string) $this->request->getPost('description')),
        ];
    }

    private function findOrFail(int $id): object
    {
        return $this->products->find($id) ?? throw PageNotFoundException::forPageNotFound('Produk tidak ditemukan.');
    }
}
