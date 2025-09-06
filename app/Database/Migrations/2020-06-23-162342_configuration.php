<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Configuration extends Migration
{
	public function up()
	{
		$this->forge->addField([
		    'id'                        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE],
            'name_app'                  => ['type' => 'VARCHAR', 'constraint' => 45],
            'icon_app'                  => ['type' => 'VARCHAR', 'constraint' => 30],
            'email'                     => ['type' => 'VARCHAR', 'constraint' => 100],
            'intro'                     => ['type' => 'TEXT', 'null' => TRUE],
            'footer'                    => ['type' => 'TEXT', 'null' => TRUE],
            'register'                  => ['type' => 'ENUM("active", "inactive")', 'default' => 'active'],
            'meta_description'          => ['type' => 'TEXT', 'null' => TRUE],
            'meta_keywords'             => ['type' => 'TEXT', 'null' => TRUE],
            'background_image'          => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE],
            'favicon'                   => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE],
            'background_img_vertical'   => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE],
            'primary_color'             => ['type' => 'VARCHAR', 'constraint' => 100],
            'secundary_color'           => ['type' => 'VARCHAR', 'constraint' => 100],
            'captcha'                   => ['type' => 'ENUM("active", "inactive")', 'default' => 'active'],
        ]);
		$this->forge->addPrimaryKey('id');
		$this->forge->createTable('configurations');
	}

	//--------------------------------------------------------------------

	public function down()
	{
        $this->forge->dropTable('configurations');
    }
}
