<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\State;

class StateSeeder extends Seeder
{
    public function run()
    {
        $states = [
            [
                'name'              => 'Pendiente',
                'color_background'  => 'grey lighten-5',
                'color_font'        => 'text-grey text-darken-4',
            ],
            [
                'name'              => 'Realizado',
                'color_background'  => 'light-blue lighten-5',
                'color_font'        => 'text-light-blue text-darken-4',
            ],
            [
                'name'              => 'Pagado',
                'color_background'  => 'green lighten-5',
                'color_font'        => 'text-green text-darken-4',
            ],
            [
                'name'              => 'Rechazado',
                'color_background'  => 'pink lighten-5',
                'color_font'        => 'text-pink text-darken-4',
            ]
        ];

        $s_model = new State();

        foreach ($states as $key => $state) {
            $s_model->save($state);
        }
    }
}
