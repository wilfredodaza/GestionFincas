<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

use App\Models\Resource;
use App\Models\ResourcePresentation;

class ResourceSeeder extends Seeder
{
    public function run()
    {
        $resources = [
            ['name' => 'Jornal', 'resource_type_id' => 1, 'measurement_unit_id' => 1, 'presentations' => [
                [
                    'presentation' => 1,
                    'base'  => 'Si',  
                ]
            ]],
            ['name' => 'Plantula Aguacate', 'resource_type_id' => 4, 'measurement_unit_id' => 1, 'presentations' => []],
            ['name' => 'Abono 16-5-18', 'resource_type_id' => 2, 'measurement_unit_id' => 2, 'presentations' => []],
            ['name' => 'Insectisidas', 'resource_type_id' => 3, 'measurement_unit_id' => 6, 'presentations' => []],
            ['name' => 'Canastas', 'resource_type_id' => 5, 'measurement_unit_id' => 1, 'presentations' => []],
        ];

        $r_model = new Resource();
        $rp_model = new ResourcePresentation();
        foreach ($resources as $key => $resource) {
            $r_model->save($resource);
            $resource_id = $r_model->insertID();
            foreach ($resource['presentations'] as $key => $presentation) {
                $presentation['resource_id'] = $resource_id;
                $rp_model->save($presentation);
            }

        }
    }
}
