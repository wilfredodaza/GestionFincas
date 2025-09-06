<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

use App\Models\MeasurementUnit;

class MeasurementUnitsSeeder extends Seeder
{
    public function run()
    {
        $measurements = [
            ['name' => 'Unidades', 'code' => 'Und'],
            ['name' => 'Kilogramo', 'code' => 'Kg'],
            ['name' => 'Libra', 'code' => 'Lb'],
            ['name' => 'Gramo', 'code' => 'g'],
            ['name' => 'Onza', 'code' => 'Oz'],
            ['name' => 'Litro', 'code' => 'L'],
            ['name' => 'Militro', 'code' => 'mL'],
            ['name' => 'Galon', 'code' => 'Gal'],
        ];

        $mm_model = new MeasurementUnit();

        foreach ($measurements as $key => $value) {
            $mm_model->save($value);
        }
    }
}
