<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ResourcePresentation extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment'  => TRUE],
            'resource_id'           => ['type' => 'INT', 'constraint' => '11', 'unsigned' => TRUE, 'null' => TRUE],

            'presentation'          => ['type' => 'INT', 'constraint' => '11', 'default' => 0],
            'presentation_value'    => ['type' => 'DECIMAL(20,2)', 'default' => 0],
            'base'                  => ['type' => 'ENUM("Si", "No")', 'default' => 'No'],

            'status'    => ['type' => 'ENUM("Activo", "Inactivo")', 'default' => 'Activo'],
            'created_at'=> ['type' => 'DATETIME', 'null' => TRUE],
            'updated_at'=> ['type' => 'DATETIME', 'null' => TRUE],
            'deleted_at'=> ['type' => 'DATETIME', 'null' => TRUE]
        ]);
		$this->forge->addKey('id', TRUE);
		$this->forge->addForeignKey('resource_id', 'resources', 'id');
		// $this->forge->addForeignKey('measurement_unit_id', 'measurement_units', 'id');
		$this->forge->createTable('resource_presentations');
    }

    public function down()
    {
		$this->forge->dropTable('resource_presentations');
    }
}
