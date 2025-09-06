<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Passwords extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment'  => TRUE],
            'user_id'   => ['type' => 'INT', 'constraint' => '11', 'unsigned' => TRUE, 'null' => TRUE],
            'password'  => ['type' => 'VARCHAR', 'constraint' => 100],
            'attempts'  => ['type' => 'INT', 'constraint' => 3, 'default' => 0],
            'temporary' => ['type' => 'ENUM("Si", "No")', 'default' => 'No'],
            'status'    => ['type' => 'ENUM("active", "inactive")', 'default' => 'active'],
            'created_at'=> ['type' => 'DATETIME', 'null' => TRUE],
            'updated_at'=> ['type' => 'DATETIME', 'null' => TRUE]
        ]);
		$this->forge->addKey('id', TRUE);
		$this->forge->addForeignKey('user_id', 'users', 'id');
		$this->forge->createTable('passwords');
    }

    public function down()
    {
        $this->forge->dropTable('passwords');
    }
}
