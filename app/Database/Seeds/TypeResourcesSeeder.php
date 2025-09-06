<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

use App\Models\ResourceType;

class TypeResourcesSeeder extends Seeder
{
    public function run()
    {
        $rt_model = new ResourceType();
        $resources = [
            ['name' => 'Trabajo'],
            ['name' => 'Fertilizante'],
            ['name' => 'Fumigantes'],
            ['name' => 'Cultivo'],
            ['name' => 'Herramientas'],
        ];

        foreach ($resources as $key => $resource) {
            $rt_model->save($resource);
        }
    }
}
