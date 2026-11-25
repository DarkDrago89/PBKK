<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Tabel produk.
 * - id_category: RESTRICT -> kategori yang masih punya produk tidak bisa dihapus.
 * - created_by : siapa (admin/operator) yang menambahkan produk.
 * - deleted_at : soft delete, agar riwayat pesanan yang menunjuk produk ini tetap utuh.
 */
class CreateProducts extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_product'  => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'id_category' => ['type' => 'INT', 'unsigned' => true],
            'name'        => ['type' => 'VARCHAR', 'constraint' => 150],
            'slug'        => ['type' => 'VARCHAR', 'constraint' => 170],
            'description' => ['type' => 'TEXT', 'null' => true],
            'price'       => ['type' => 'INT', 'unsigned' => true],             // rupiah, tanpa desimal
            'stock'       => ['type' => 'INT', 'unsigned' => true, 'default' => 0],
            'image'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true], // nama file di public/uploads/products
            'is_active'   => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_by'  => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id_product', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->addKey('id_category');
        $this->forge->addForeignKey('id_category', 'categories', 'id_category', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('created_by', 'users', 'id_user', 'CASCADE', 'SET NULL');
        $this->forge->createTable('products');
    }

    public function down()
    {
        $this->forge->dropTable('products');
    }
}
