<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\ProductModel;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * Pesanan.
 *   customer (auth:customer)       : store, index, show, cancel  -> hanya pesanan MILIKNYA
 *   admin/operator (auth:admin,operator): manage, status          -> semua pesanan
 */
class OrderController extends BaseController
{
    private OrderModel $orders;
    private ProductModel $products;

    public function __construct()
    {
        $this->orders   = new OrderModel();
        $this->products = new ProductModel();
    }

    // POST /products/(:num)/order   ("Beli Sekarang")
    public function store(int $idProduct)
    {
        $p = $this->products->find($idProduct);
        if ($p === null || ! $p->is_active) {
            throw PageNotFoundException::forPageNotFound('Produk tidak ditemukan.');
        }

        $maks  = config('ShopEase')->maksQty;
        $rules = [
            'qty'              => ['label' => 'Jumlah', 'rules' => "required|is_natural_no_zero|less_than_equal_to[{$maks}]"],
            'shipping_address' => ['label' => 'Alamat Pengiriman', 'rules' => 'required|min_length[10]|max_length[500]'],
            'notes'            => ['label' => 'Catatan', 'rules' => 'permit_empty|max_length[255]'],
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $d   = $this->validator->getValidated();
        $qty = (int) $d['qty'];

        // Pengurangan stok + pembuatan pesanan harus sukses bersama, atau batal bersama.
        $db = db_connect();
        $db->transStart();
        $stokCukup = $this->products->kurangiStok($idProduct, $qty);
        if ($stokCukup) {
            $id = $this->orders->insert([
                'invoice'          => OrderModel::buatInvoice(),
                'id_user'          => session('id_user'), // pemilik diambil dari SESSION, bukan dari form
                'id_product'       => $idProduct,
                'product_name'     => $p->name,
                'price'            => $p->price,
                'qty'              => $qty,
                'total'            => $p->price * $qty,
                'shipping_address' => trim($d['shipping_address']),
                'notes'            => trim((string) ($d['notes'] ?? '')) ?: null,
            ]);
        }
        $db->transComplete();

        if (! $stokCukup) {
            return redirect()->back()->withInput()->with('error', "Stok {$p->name} tidak mencukupi (tersisa {$p->stock}).");
        }

        return redirect()->to('orders/' . $id)->with('success', 'Pesanan berhasil dibuat. Silakan tunggu konfirmasi dari penjual.');
    }

    // GET /orders
    public function index(): string
    {
        $status = $this->request->getGet('status');
        $query  = $this->orders->milikUser((int) session('id_user'));
        if ($status && in_array($status, OrderModel::STATUS, true)) {
            $query->where('status', $status);
        }

        return view('customer/orders', [
            'title'  => 'Pesanan Saya',
            'items'  => $query->orderBy('id_order', 'DESC')->paginate(10),
            'pager'  => $this->orders->pager,
            'status' => $status,
        ]);
    }

    // GET /orders/(:num)
    public function show(int $id): string
    {
        $o = $this->milikSaya($id);

        return view('customer/order_detail', [
            'title'  => 'Pesanan ' . $o->invoice,
            'o'      => $o,
            'produk' => $this->products->withDeleted()->find($o->id_product),
        ]);
    }

    // POST /orders/(:num)/cancel
    public function cancel(int $id)
    {
        $o = $this->milikSaya($id);

        if (! OrderModel::bisaDibatalkan($o)) {
            return redirect()->back()->with('error', "Pesanan {$o->invoice} berstatus {$o->status} dan tidak dapat dibatalkan.");
        }

        $this->ubahStatus($o, 'dibatalkan');

        return redirect()->to('orders/' . $id)->with('success', "Pesanan {$o->invoice} dibatalkan. Stok produk dikembalikan.");
    }

    // GET /manage/orders   (admin, operator)
    public function manage(): string
    {
        $q      = trim((string) $this->request->getGet('q'));
        $status = $this->request->getGet('status');

        return view('product/orders', [
            'title'    => 'Kelola Pesanan',
            'items'    => $this->orders->search($q, $status, 10),
            'pager'    => $this->orders->pager,
            'perStatus' => (new OrderModel())->countByStatus(),
            'q'        => $q,
            'status'   => $status,
        ]);
    }

    // POST /manage/orders/(:num)/status   (admin, operator)
    public function status(int $id)
    {
        $o  = $this->orders->find($id) ?? throw PageNotFoundException::forPageNotFound('Pesanan tidak ditemukan.');
        $ke = (string) $this->request->getPost('status');

        if (! OrderModel::bolehBerubah($o->status, $ke)) {
            return redirect()->back()->with('error', "Status {$o->invoice} tidak bisa diubah dari {$o->status} ke {$ke}.");
        }

        $this->ubahStatus($o, $ke);

        return redirect()->back()->with('success', "Pesanan {$o->invoice}: {$o->status} → {$ke}.");
    }

    // ---------------------------------------------------------------------

    /**
     * Cari pesanan DENGAN syarat milik user yang login.
     * Pesanan orang lain diperlakukan "tidak ada" (404), bukan 403,
     * agar keberadaan nomor pesanan orang lain tidak bocor (mencegah IDOR).
     */
    private function milikSaya(int $id): object
    {
        return $this->orders->milikUser((int) session('id_user'))->find($id)
            ?? throw PageNotFoundException::forPageNotFound('Pesanan tidak ditemukan.');
    }

    /** Ubah status; jika dibatalkan, stok dikembalikan dalam transaksi yang sama. */
    private function ubahStatus(object $o, string $ke): void
    {
        $db = db_connect();
        $db->transStart();
        $this->orders->update($o->id_order, ['status' => $ke]);
        if ($ke === 'dibatalkan') {
            $this->products->tambahStok((int) $o->id_product, (int) $o->qty);
        }
        $db->transComplete();
    }
}
