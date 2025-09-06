<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Users extends Migration
{
	public function up()
	{
		$this->forge->addField([
            'id'        			=> ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment'  => TRUE],
            'name'      			=> ['type' => 'VARCHAR', 'constraint' => 45],
            'email'     			=> ['type' => 'VARCHAR', 'constraint' => 100],
            'username'  			=> ['type' => 'VARCHAR', 'constraint' => 40],
            // 'password'  			=> ['type' => 'VARCHAR', 'constraint' => 100],
            'status'    			=> ['type' => 'ENUM("active", "inactive")', 'default' => 'inactive'],
            'photo'     			=> ['type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE],
            'role_id'   			=> ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE]
        ]);
		$this->forge->addKey('id', TRUE);
		$this->forge->addForeignKey('role_id', 'roles', 'id');
		$this->forge->createTable('users');
	}

	//--------------------------------------------------------------------

	public function down()
	{

		$this->forge->dropTable('users');

	}
}
