<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

use App\Models\MovementType;

class MovementTypeSeeder extends Seeder
{
    public function run()
    {
        $movement_types = [
            ['name' => 'Compra'],
            ['name' => 'Actividad'],
            ['name' => 'Gastos']
        ];

        $mt_model = new MovementType();
        foreach ($movement_types as $key => $movement_type) {
            $mt_model->save($movement_type);
        }
    }
}
