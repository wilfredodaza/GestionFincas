<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run()
    {
        $menus = [
            ['option' => 'Sistema','url' => '','icon' => 'ri-home-gear-line','position' => '1','type' => 'primario','references' => NULL,'status' => 'inactive','component' => 'table','title' => 'Fincas','description' => NULL,'table' => 'farms'],
            ['option' => 'Fincas','url' => 'farms','icon' => NULL,'position' => '1','type' => 'secundario','references' => '10','status' => 'active','component' => 'table','title' => 'Fincas','description' => NULL,'table' => 'farms'],
            ['option' => 'Mi finca','url' => '','icon' => 'ri-building-4-line','position' => '2','type' => 'primario','references' => NULL,'status' => 'inactive','component' => 'table','title' => 'Lotes','description' => NULL,'table' => 'lots'],
            ['option' => 'Cultivos','url' => 'crop_types','icon' => NULL,'position' => '1','type' => 'secundario','references' => '10','status' => 'active','component' => 'table','title' => 'Mis cultivos','description' => NULL,'table' => 'crop_types'],
            ['option' => 'Lotes','url' => 'lots','icon' => NULL,'position' => '3','type' => 'secundario','references' => '3','status' => 'active','component' => 'table','title' => 'Mis lotes','description' => NULL,'table' => 'lots'],
            ['option' => 'Recursos','url' => '','icon' => 'ri-list-radio','position' => '3','type' => 'primario','references' => NULL,'status' => 'inactive','component' => 'table','title' => NULL,'description' => NULL,'table' => NULL],
            ['option' => 'Insumos','url' => 'resources_insumos','icon' => NULL,'position' => '1','type' => 'secundario','references' => '6','status' => 'active','component' => 'table','title' => 'Mis Insumos','description' => NULL,'table' => 'resources'],
            ['option' => 'Cultivos','url' => 'resources_cultivos','icon' => NULL,'position' => '2','type' => 'secundario','references' => '6','status' => 'active','component' => 'table','title' => 'Mis cultivos','description' => NULL,'table' => 'resources'],
            ['option' => 'Compras Y Gastos','url' => 'movements/bills','icon' => 'ri-draft-line','position' => '2','type' => 'primario','references' => NULL,'status' => 'active','component' => 'controller','title' => NULL,'description' => NULL,'table' => NULL],
            ['option' => 'Config. Sistema','url' => '','icon' => 'ri-list-settings-line','position' => '10','type' => 'primario','references' => NULL,'status' => 'active','component' => 'table','title' => NULL,'description' => NULL,'table' => NULL],
            ['option' => 'Tipos de recursos','url' => 'resource_types','icon' => NULL,'position' => '2','type' => 'secundario','references' => '10','status' => 'active','component' => 'table','title' => 'Tipos de recursos','description' => NULL,'table' => 'resource_types'],
            ['option' => 'Recursos','url' => 'resources','icon' => NULL,'position' => '3','type' => 'secundario','references' => '10','status' => 'active','component' => 'table','title' => 'Recursos','description' => NULL,'table' => 'resources'],
            ['option' => 'Proveedores','url' => 'providers','icon' => NULL,'position' => '4','type' => 'secundario','references' => '10','status' => 'active','component' => 'table','title' => 'Proveedores','description' => NULL,'table' => 'providers'],
            ['option' => 'Actividades','url' => 'movements/activities','icon' => 'ri-timer-line','position' => '3','type' => 'primario','references' => NULL,'status' => 'active','component' => 'controller','title' => NULL,'description' => NULL,'table' => NULL],
            ['option' => 'Jornales','url' => 'movements/wage','icon' => 'ri-currency-line','position' => '5','type' => 'primario','references' => NULL,'status' => 'active','component' => 'controller','title' => NULL,'description' => NULL,'table' => NULL],
        ];
        $m_model = new Menu();
        foreach ($menus as $key => $value) {
            $m_model->save($value);
        }
    }
}
