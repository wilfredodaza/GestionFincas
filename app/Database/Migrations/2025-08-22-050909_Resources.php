<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Resources extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment'  => TRUE],
            'resource_type_id'      => ['type' => 'INT', 'constraint' => '11', 'unsigned' => TRUE, 'null' => TRUE],
            'measurement_unit_id'   => ['type' => 'INT', 'constraint' => '11', 'unsigned' => TRUE, 'null' => TRUE],
            'name'                  => ['type' => 'VARCHAR', 'constraint' => 100],

            // 'presentation'          => ['type' => 'INT', 'constraint' => '11', 'default' => 0],
            // 'presentation_value'    => ['type' => 'DECIMAL(20,2)', 'default' => 0],

            'status'    => ['type' => 'ENUM("Activo", "Inactivo")', 'default' => 'Activo'],
            'created_at'=> ['type' => 'DATETIME', 'null' => TRUE],
            'updated_at'=> ['type' => 'DATETIME', 'null' => TRUE],
            'deleted_at'=> ['type' => 'DATETIME', 'null' => TRUE]
        ]);
		$this->forge->addKey('id', TRUE);
		$this->forge->addForeignKey('resource_type_id', 'resource_types', 'id');
		$this->forge->addForeignKey('measurement_unit_id', 'measurement_units', 'id');
		$this->forge->createTable('resources');
    }
    
    public function down()
    {
        $this->forge->dropTable('resources');
    }
}
