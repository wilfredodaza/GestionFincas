<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UnitMeasurement extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment'  => TRUE],
            'name'      => ['type' => 'VARCHAR', 'constraint' => 100],
            'code'      => ['type' => 'VARCHAR', 'constraint' => 100],
            'status'    => ['type' => 'ENUM("Activo", "Inactivo")', 'default' => 'Activo'],
            'created_at'=> ['type' => 'DATETIME', 'null' => TRUE],
            'updated_at'=> ['type' => 'DATETIME', 'null' => TRUE],
            'deleted_at'=> ['type' => 'DATETIME', 'null' => TRUE]
        ]);
		$this->forge->addKey('id', TRUE);
		$this->forge->createTable('measurement_units');
    }

    public function down()
    {
		$this->forge->dropTable('measurement_units');
    }
}
