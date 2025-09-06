<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Notification extends Migration
{
	public function up()
	{
		$this->forge->addField([
		    'id'        => ['type' => 'INT', 'constraint' => '11', 'auto_increment' => TRUE, 'unsigned' => TRUE],
            'title'     => ['type' => 'VARCHAR', 'constraint' => 255],
            'body'      => ['type' => 'TEXT'],
            'icon'      => ['type' => 'VARCHAR', 'constraint' => 45,],
            'color'     => ['type' => 'ENUM("", "cyan", "amber", "orange", "purple", "red darken-1")', 'default' => 'cyan'],
            'created_at'=> ['type' => 'DATETIME', 'null' => TRUE],
            'user_id'   => ['type' => 'INT', 'constraint' => '11', 'unsigned' => TRUE, 'null' => TRUE]
        ]);
		$this->forge->addPrimaryKey('id');
		$this->forge->addForeignKey('user_id', 'users', 'id');
		$this->forge->createTable('notifications');
	}

	//--------------------------------------------------------------------

	public function down()
	{
	    $this->forge->dropTable('notifications');
	}
}
