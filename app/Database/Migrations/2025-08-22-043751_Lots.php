<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Lots extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment'  => TRUE],
            'farm_id'       => ['type' => 'INT', 'constraint' => '11', 'unsigned' => TRUE, 'null' => TRUE],
            'crop_type_id'  => ['type' => 'INT', 'constraint' => '11', 'unsigned' => TRUE, 'null' => TRUE],
            'name'          => ['type' => 'VARCHAR', 'constraint' => 100],
            'area'          => ['type' => 'DECIMAL(10,2)', 'default' => 0],
            'altitud'       => ['type' => 'INT', 'constraint' => '11'],
            'densidad'      => ['type' => 'INT', 'constraint' => '11'],

            'status'        => ['type' => 'ENUM("Activo", "Inactivo")', 'default' => 'Activo'],
            'created_at'    => ['type' => 'DATETIME', 'null' => TRUE],
            'updated_at'    => ['type' => 'DATETIME', 'null' => TRUE],
            'deleted_at'    => ['type' => 'DATETIME', 'null' => TRUE]
        ]);
		$this->forge->addKey('id', TRUE);
		$this->forge->addForeignKey('farm_id', 'farms', 'id');
		$this->forge->addForeignKey('crop_type_id', 'crop_types', 'id');
		$this->forge->createTable('lots');
    }

    public function down()
    {		
        $this->forge->dropTable('lots');
    }
}
