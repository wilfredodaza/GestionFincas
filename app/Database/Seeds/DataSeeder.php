<?php namespace App\Database\Seeds;

class DataSeeder extends \CodeIgniter\Database\Seeder
{
    public function  run()
    {
        $this->call('RoleSeeder');
        $this->call('UserSeeder');
        $this->call('StateSeeder');
        $this->call('TypeResourcesSeeder');
        $this->call('MeasurementUnitsSeeder');
        $this->call('ResourceSeeder');
        $this->call('MenuSeeder');
        $this->call('MovementTypeSeeder');
    }
}