<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ProductiveUnit extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment'  => TRUE],
            'lot_id'        => ['type' => 'INT', 'constraint' => '11', 'unsigned' => TRUE, 'null' => TRUE],
            'resource_id'   => ['type' => 'INT', 'constraint' => '11', 'unsigned' => TRUE, 'null' => TRUE],
            'code'          => ['type' => 'VARCHAR', 'constraint' => 100],
            'sowing_date'   => ['type' => 'DATE'],
            'replanted'     => ['type' => 'INT', 'constraint' => '11', 'default' => 0],
            

            'status'    => ['type' => 'ENUM("Activo", "Inactivo")', 'default' => 'Activo'],
            'created_at'=> ['type' => 'DATETIME', 'null' => TRUE],
            'updated_at'=> ['type' => 'DATETIME', 'null' => TRUE],
            'deleted_at'=> ['type' => 'DATETIME', 'null' => TRUE]
        ]);
		$this->forge->addKey('id', TRUE);
		$this->forge->addForeignKey('lot_id', 'lots', 'id');
		$this->forge->addForeignKey('resource_id', 'resources', 'id');
		$this->forge->createTable('productive_unit');
    }
    
    public function down()
    {
        $this->forge->dropTable('productive_unit');
    }
}
