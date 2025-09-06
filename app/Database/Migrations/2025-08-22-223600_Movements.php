<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Movements extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment'  => TRUE],
            'user_id'           => ['type' => 'INT', 'constraint' => '11', 'unsigned' => TRUE],
            'farm_id'           => ['type' => 'INT', 'constraint' => '11', 'unsigned' => TRUE, 'null' => TRUE],
            'movement_type_id'  => ['type' => 'INT', 'constraint' => '11', 'unsigned' => TRUE, 'null' => TRUE],
            'provider_id'       => ['type' => 'INT', 'constraint' => '11', 'unsigned' => TRUE, 'null' => TRUE],
            'state_id'          => ['type' => 'INT', 'constraint' => '11', 'unsigned' => TRUE, 'null' => TRUE],
            
            'resolution'            => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'movement_reference'    => ['type' => 'INT', 'constraint' => '11', 'unsigned' => TRUE, 'null' => TRUE],
            'title'                 => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE],
            'support'               => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE],
            'value'                 => ['type' => 'DECIMAL(20,2)', 'default' => 0],
            'date'                  => ['type' => 'DATE', 'null' => TRUE],
            'note'                  => ['type' => 'TEXT', 'null' => TRUE],
            'number_bill'           => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE],
            'seller'                => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE],
        
            'created_at'        => ['type' => 'DATETIME', 'null' => TRUE],
            'updated_at'        => ['type' => 'DATETIME', 'null' => TRUE],
            'deleted_at'        => ['type' => 'DATETIME', 'null' => TRUE],
        ]);
        $this->forge->addKey('id', TRUE);
        $this->forge->addForeignKey('user_id', 'users', 'id');
        $this->forge->addForeignKey('farm_id', 'farms', 'id');
        $this->forge->addForeignKey('movement_type_id', 'movement_types', 'id');
        $this->forge->addForeignKey('movement_reference', 'movements', 'id');
        $this->forge->addForeignKey('provider_id', 'providers', 'id');
        $this->forge->addForeignKey('state_id', 'states', 'id');
        $this->forge->createTable('movements');
    }

    public function down()
    {
        $this->forge->dropTable('movements');
    }
}
