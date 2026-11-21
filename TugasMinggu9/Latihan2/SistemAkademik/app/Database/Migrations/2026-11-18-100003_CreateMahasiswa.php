<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMahasiswa extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INTEGER', 'unsigned' => true, 'auto_increment' => true],
            'user_id'    => ['type' => 'INTEGER', 'unsigned' => true],
            'nim'        => ['type' => 'VARCHAR', 'constraint' => 20],
            'nama'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'prodi'      => ['type' => 'VARCHAR', 'constraint' => 50],
            'angkatan'   => ['type' => 'INTEGER'],
            'email'      => ['type' => 'VARCHAR', 'constraint' => 100],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('nim');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('mahasiswa');
    }

    public function down()
    {
        $this->forge->dropTable('mahasiswa');
    }
}
