<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Tabel kategori produk (Product Management).
 */
class CreateCategories extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_category' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'name'        => ['type' => 'VARCHAR', 'constraint' => 60],
            'slug'        => ['type' => 'VARCHAR', 'constraint' => 80],
            'icon'        => ['type' => 'VARCHAR', 'constraint' => 40, 'default' => 'tag'],   // nama ikon bootstrap-icons
            'color'       => ['type' => 'VARCHAR', 'constraint' => 7, 'default' => '#6d28d9'],
            'description' => ['type' => 'TEXT', 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id_category', true);
        $this->forge->addUniqueKey('name');
        $this->forge->addUniqueKey('slug');
        $this->forge->createTable('categories');
    }

    public function down()
    {
        $this->forge->dropTable('categories');
    }
}
