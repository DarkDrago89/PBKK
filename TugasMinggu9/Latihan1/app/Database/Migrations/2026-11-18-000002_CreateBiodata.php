<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBiodata extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INTEGER', 'unsigned' => true, 'auto_increment' => true],
            'nim'           => ['type' => 'VARCHAR', 'constraint' => 20],
            'nama'          => ['type' => 'VARCHAR', 'constraint' => 100],
            'jenis_kelamin' => ['type' => 'VARCHAR', 'constraint' => 1],   // L / P
            'tempat_lahir'  => ['type' => 'VARCHAR', 'constraint' => 50],
            'tanggal_lahir' => ['type' => 'DATE'],
            'prodi'         => ['type' => 'VARCHAR', 'constraint' => 50],
            'email'         => ['type' => 'VARCHAR', 'constraint' => 100],
            'alamat'        => ['type' => 'TEXT', 'null' => true],
            'foto'          => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('nim');
        $this->forge->createTable('biodata');
    }

    public function down()
    {
        $this->forge->dropTable('biodata');
    }
}
