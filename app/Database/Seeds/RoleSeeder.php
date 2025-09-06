<?php namespace App\Database\Seeds;

class RoleSeeder extends \CodeIgniter\Database\Seeder
{
        public function run()
        {

            $data = [
                ['name' =>  'Superadministrador'],
                ['name' =>  'Administrador'],
                ['name' =>  'usuario']
             ];

             foreach ($data as $key) {
                $this->db->query("INSERT INTO roles (name) VALUES(:name:)",$key);
             }
                // Simple Queries
                
                
        }
}