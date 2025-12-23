<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FarmsUsers extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment'  => TRUE],
            'farm_id'       => ['type' => 'INT', 'constraint' => '11', 'unsigned' => TRUE],
            'user_id'       => ['type' => 'INT', 'constraint' => '11', 'unsigned' => TRUE],

            'status'        => ['type' => 'ENUM("Activo", "Inactivo")', 'default' => 'Activo'],
        
            'created_at'    => ['type' => 'DATETIME', 'null' => TRUE],
            'updated_at'    => ['type' => 'DATETIME', 'null' => TRUE],
            'deleted_at'    => ['type' => 'DATETIME', 'null' => TRUE],
        ]);
        $this->forge->addKey('id', TRUE);
        $this->forge->addForeignKey('farm_id', 'farms', 'id');
        $this->forge->addForeignKey('user_id', 'users', 'id');
        $this->forge->createTable('farms_users');
    }   

    public function down()
    {
        $this->forge->dropTable('farms_users');
    }
}
