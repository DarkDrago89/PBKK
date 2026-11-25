<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Tabel pesanan (satu produk per pesanan, fitur "Beli Sekarang").
 * Nama & harga produk disalin (snapshot) saat pesanan dibuat, sehingga
 * perubahan harga produk di kemudian hari tidak mengubah riwayat pesanan.
 */
class CreateOrders extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_order'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'invoice'          => ['type' => 'VARCHAR', 'constraint' => 30],
            'id_user'          => ['type' => 'INT', 'unsigned' => true],
            'id_product'       => ['type' => 'INT', 'unsigned' => true],
            'product_name'     => ['type' => 'VARCHAR', 'constraint' => 150],
            'price'            => ['type' => 'INT', 'unsigned' => true],
            'qty'              => ['type' => 'INT', 'unsigned' => true],
            'total'            => ['type' => 'INT', 'unsigned' => true],
            'status'           => ['type' => 'ENUM', 'constraint' => ['menunggu', 'diproses', 'dikirim', 'selesai', 'dibatalkan'], 'default' => 'menunggu'],
            'shipping_address' => ['type' => 'TEXT'],
            'notes'            => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id_order', true);
        $this->forge->addUniqueKey('invoice');
        $this->forge->addKey('id_user');
        $this->forge->addForeignKey('id_user', 'users', 'id_user', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_product', 'products', 'id_product', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('orders');
    }

    public function down()
    {
        $this->forge->dropTable('orders');
    }
}
