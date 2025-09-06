<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Farms extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment'  => TRUE],
            'user_id'   => ['type' => 'INT', 'constraint' => '11', 'unsigned' => TRUE, 'null' => TRUE],
            'name'      => ['type' => 'VARCHAR', 'constraint' => 100],
            'location'  => ['type' => 'VARCHAR', 'constraint' => 100],
            'status'    => ['type' => 'ENUM("Activo", "Inactivo")', 'default' => 'Activo'],
            'created_at'=> ['type' => 'DATETIME', 'null' => TRUE],
            'updated_at'=> ['type' => 'DATETIME', 'null' => TRUE],
            'deleted_at'=> ['type' => 'DATETIME', 'null' => TRUE]
        ]);
		$this->forge->addKey('id', TRUE);
		$this->forge->addForeignKey('user_id', 'users', 'id');
		$this->forge->createTable('farms');
    }
    
    public function down()
    {
        $this->forge->dropTable('farms');
    }
}
