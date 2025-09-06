<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MovementDetails extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment'  => TRUE],
            'movement_id'       => ['type' => 'INT', 'constraint' => '11', 'unsigned' => TRUE],
            'lot_id'            => ['type' => 'INT', 'constraint' => '11', 'unsigned' => TRUE, 'null' => TRUE],
            'resource_id'           => ['type' => 'INT', 'constraint' => '11', 'unsigned' => TRUE, 'null' => TRUE],
            // 'measurement_unit_id'   => ['type' => 'INT', 'constraint' => '11', 'unsigned' => TRUE, 'null' => TRUE],
            'productive_unit_id'    => ['type' => 'INT', 'constraint' => '11', 'unsigned' => TRUE, 'null' => TRUE],
            
            'approximate_amount'    => ['type' => 'DECIMAL(20,5)', 'default' => 0],
            'quantity'              => ['type' => 'DECIMAL(20,5)', 'default' => 0],
            'value'                 => ['type' => 'DECIMAL(20,2)', 'default' => 0],
            'note'                  => ['type' => 'TEXT', 'null' => TRUE],

            'presentation_id'       => ['type' => 'INT', 'constraint' => '11', 'unsigned' => TRUE, 'null' => TRUE],
            'presentation_value'    => ['type' => 'DECIMAL(20,2)', 'default' => 0],
        
            'created_at'        => ['type' => 'DATETIME', 'null' => TRUE],
            'updated_at'        => ['type' => 'DATETIME', 'null' => TRUE],
            'deleted_at'        => ['type' => 'DATETIME', 'null' => TRUE],
        ]);
        $this->forge->addKey('id', TRUE);
        $this->forge->addForeignKey('movement_id', 'movements', 'id');
        $this->forge->addForeignKey('lot_id', 'lots', 'id');
        $this->forge->addForeignKey('resource_id', 'resources', 'id');
        $this->forge->addForeignKey('productive_unit_id', 'productive_unit', 'id');
        $this->forge->addForeignKey('presentation_id', 'resource_presentations', 'id');
        $this->forge->createTable('movement_details');
    }

    public function down()
    {
        $this->forge->dropTable('movement_details');
    }
}
