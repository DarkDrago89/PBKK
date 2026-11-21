<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMatakuliah extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INTEGER', 'unsigned' => true, 'auto_increment' => true],
            'kode'       => ['type' => 'VARCHAR', 'constraint' => 10],
            'nama'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'sks'        => ['type' => 'INTEGER'],
            'semester'   => ['type' => 'INTEGER'],
            'dosen_id'   => ['type' => 'INTEGER', 'unsigned' => true, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('kode');
        // Jika dosen dihapus, mata kuliah tetap ada tetapi pengampunya dikosongkan
        $this->forge->addForeignKey('dosen_id', 'dosen', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('matakuliah');
    }

    public function down()
    {
        $this->forge->dropTable('matakuliah');
    }
}
