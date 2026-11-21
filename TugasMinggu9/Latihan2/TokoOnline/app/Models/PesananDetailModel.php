<?php

namespace App\Models;

use CodeIgniter\Model;

class PesananDetailModel extends Model
{
    protected $table         = 'pesanan_detail';
    protected $returnType    = 'object';
    protected $allowedFields = ['pesanan_id', 'produk_id', 'nama_produk', 'harga', 'qty', 'subtotal'];
}
