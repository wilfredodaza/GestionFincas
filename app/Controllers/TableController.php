<?php


namespace App\Controllers;


use App\Traits\Grocery;
use App\Models\Menu;
use App\Models\Farm;
use App\Models\Resource;
use App\Models\ResourcePresentation;
use CodeIgniter\Exceptions\PageNotFoundException;

class TableController extends BaseController
{
    use Grocery;

    private $crud;

    public function __construct()
    {
        $this->crud = $this->_getGroceryCrudEnterprise();
        // $this->crud->setSkin('bootstrap-v3');
        $this->crud->setLanguage('Spanish');
    }

    public function index($data)
    {
        $menu = new Menu();
        $component = $menu->where(['url' => $data, 'component' => 'table'])->get()->getResult();



        if($component) {
            $this->crud->setTable($component[0]->table);
            switch ($component[0]->url) {
                case 'usuarios':
                    $this->crud->where(['role_id > ?' => 1]);
                    $this->crud->unsetDelete();
                    $this->crud->setFieldUpload('photo', 'assets/upload/images', '/assets/upload/images');
                    $this->crud->setRelation('role_id', 'roles', 'name', ['id > ?' => 1]);
                    $this->crud->displayAs([
                        'name'  => 'Nombre',
                        'photo' => 'Foto',
                        'username'  => 'Usuario',
                        'status'    => 'Estado',
                        'role_id'   => 'Rol'
                    ]);
                    $this->crud->unsetEditFields(['role_id', 'usuario']);
                    $this->crud->uniqueFields(['email', 'username']);
                    $this->crud->setActionButton('Contraseñas', 'fa fa-lock', function ($row) {
                        return base_url(['table', 'users', $row->id]);
                    }, false);
                    break;
                case 'menus':
                    $this->crud->setTexteditor(['description']);
                    break;

                case 'farms':

                    $this->crud->setRelation('user_id', 'users', 'name', []);
                    $this->crud->displayAs([
                        'user_id'   => 'Propietario',
                        'name'      => 'Finca',
                        'location'  => 'Ubicación',
                        'status'    => 'Estado',
                        'created_at'=> 'Creado'
                    ]);

                    $columns = ['user_id', 'name', 'location', 'status'];
                    $this->crud->columns($columns);
                    $this->crud->editFields($columns);
                    if (($key = array_search('status', $columns)) !== false) {
                        unset($columns[$key]);
                    }

                    if(session('user')->role_id != 1){
                        if (($key = array_search('user_id', $columns)) !== false) {
                            unset($columns[$key]);
                        }
                    }

                    if(session('user')->role_id != 1)
                        $this->crud->where(['user_id' => session('user')->id]);

                    $this->crud->addFields($columns);

                    $this->crud->callbackBeforeInsert(function ($stateParameters) {
                        $stateParameters->data['created_at'] = date('Y-m-d h:i:s');
                        $stateParameters->data['updated_at'] = date('Y-m-d h:i:s');

                        if(session('user')->role_id != 1)
                            $stateParameters->data['user_id'] = session('user')->id;

                        return $stateParameters;
                    });

                    $this->crud->callbackBeforeUpdate(function ($stateParameters) {
                        $stateParameters->data['updated_at'] = date('Y-m-d h:i:s');
                        return $stateParameters;
                    });

                    $this->crud->callbackAfterInsert(function ($stateParameters) {

                        if(session('user')->id == $stateParameters->data['user_id']){
                            $f_model = new Farm();
                            session('user')->farms = $f_model->where(['user_id' => session('user')->id])->findAll();                        
                        }                        
                    
                        return $stateParameters;
                    });

                    $this->crud->setActionButton('Lotes', 'fa fa-bars', function ($row) {
                        return base_url(['table', 'lots', $row->id]);
                    }, false);

                    break;
                case 'crop_types':
                    $this->crud->displayAs([
                        'name'      => 'Tipo cultivo',
                        'status'    => 'Estado',
                        'created_at'=> 'Creado'
                    ]);

                    $columns = ['name', 'status', 'created_at'];
                    $this->crud->columns($columns);
                    $this->crud->editFields($columns);
                    if (($key = array_search('created_at', $columns)) !== false) {
                        unset($columns[$key]);
                    }
                    $this->crud->editFields($columns);
                    if (($key = array_search('status', $columns)) !== false) {
                        unset($columns[$key]);
                    }
                    $this->crud->addFields($columns);

                    $this->crud->callbackBeforeInsert(function ($stateParameters) {
                        $stateParameters->data['created_at'] = date('Y-m-d h:i:s');
                        $stateParameters->data['updated_at'] = date('Y-m-d h:i:s');
                        return $stateParameters;
                    });

                    $this->crud->callbackBeforeUpdate(function ($stateParameters) {
                        $stateParameters->data['updated_at'] = date('Y-m-d h:i:s');
                        return $stateParameters;
                    });
                    break;
                
                case 'resource_types':
                    $this->crud->displayAs([
                        'name'          => 'Nombre',
                        'status'        => 'Estado',
                        'created_at'    => 'Creado'
                    ]);
                    $columns = ['name', 'status', 'created_at'];
                    $this->crud->columns($columns);
                    $this->crud->editFields($columns);
                    if (($key = array_search('created_at', $columns)) !== false) {
                        unset($columns[$key]);
                    }
                    $this->crud->editFields($columns);
                    if (($key = array_search('status', $columns)) !== false) {
                        unset($columns[$key]);
                    }
                    $this->crud->addFields($columns);

                    $this->crud->callbackBeforeInsert(function ($stateParameters) {
                        $stateParameters->data['created_at'] = date('Y-m-d h:i:s');
                        $stateParameters->data['updated_at'] = date('Y-m-d h:i:s');
                        return $stateParameters;
                    });

                    $this->crud->callbackBeforeUpdate(function ($stateParameters) {
                        $stateParameters->data['updated_at'] = date('Y-m-d h:i:s');
                        return $stateParameters;
                    });


                    break;
                case 'resources':
                    $this->crud->displayAs([
                        'resource_type_id'  => 'Tipo de recurso',
                        'name'              => 'Nombre',
                        'measurement_unit_id'   => 'Unidad de medida', 
                        // 'presentation'          => 'Presentación',
                        // 'presentation_value'    => 'Valor presentación',
                        'status'            => 'Estado',
                        'created_at'        => 'Creado'
                    ]);
                    $columns = ['resource_type_id', 'measurement_unit_id', 'name', 'status', 'created_at'];
                    $this->crud->columns($columns);
                    $this->crud->editFields($columns);
                    if (($key = array_search('created_at', $columns)) !== false) {
                        unset($columns[$key]);
                    }
                    if (($key = array_search('measurement_unit_id', $columns)) !== false) {
                        unset($columns[$key]);
                    }
                    $this->crud->editFields($columns);
                    if (($key = array_search('status', $columns)) !== false) {
                        unset($columns[$key]);
                    }

                    $columns[] = "measurement_unit_id";

                    $this->crud->addFields($columns);


                    $this->crud->where(['resource_type_id > ?' => 1]);

                    $this->crud->callbackBeforeInsert(function ($stateParameters) {
                        $stateParameters->data['created_at'] = date('Y-m-d h:i:s');
                        $stateParameters->data['updated_at'] = date('Y-m-d h:i:s');
                        return $stateParameters;
                    });

                    
                    $this->crud->setRelation('resource_type_id', 'resource_types', 'name', ['id > ?' =>1]);
                    $this->crud->setRelation('measurement_unit_id', 'measurement_units', '{name} - {code}');

                    $this->crud->setActionButton('Presentaciones', 'fa fa-bars', function ($row) {
                        return base_url(['table', 'resource_presentations', $row->id]);
                    }, false);

                    $this->crud->setActionButton('Kardex', 'fa fa-list-ol', function ($row) {
                        return base_url(['dashboard', 'resource', 'kardex', $row->id]);
                    }, false);

                    $this->crud->callbackBeforeUpdate(function ($stateParameters) {
                        $stateParameters->data['updated_at'] = date('Y-m-d h:i:s');
                        return $stateParameters;
                    });
                    break;
                case 'providers':
                    $this->crud->displayAs([
                        'name'              => 'Nombre',
                        'number'            => 'Nit/Cédula',
                        'status'            => 'Estado',
                        'created_at'        => 'Creado'
                    ]);
                    $columns = ['name', 'number', 'status', 'created_at'];
                    $this->crud->columns($columns);
                    $this->crud->editFields($columns);
                    if (($key = array_search('created_at', $columns)) !== false) {
                        unset($columns[$key]);
                    }
                    $this->crud->editFields($columns);
                    if (($key = array_search('status', $columns)) !== false) {
                        unset($columns[$key]);
                    }
                    $this->crud->addFields($columns);

                    $this->crud->callbackBeforeInsert(function ($stateParameters) {
                        $stateParameters->data['created_at'] = date('Y-m-d h:i:s');
                        $stateParameters->data['updated_at'] = date('Y-m-d h:i:s');
                        return $stateParameters;
                    });

                    $this->crud->callbackBeforeUpdate(function ($stateParameters) {
                        $stateParameters->data['updated_at'] = date('Y-m-d h:i:s');
                        return $stateParameters;
                    });
                    break;
                default:
                    break;   
            }
            $output = $this->crud->render();
            if (isset($output->isJSONResponse) && $output->isJSONResponse) {
                header('Content-Type: application/json; charset=utf-8');
                echo $output->output;
                exit;
            }

            $this->viewTable($output, $component[0]->title, $component[0]->description);
        } else {
            throw PageNotFoundException::forPageNotFound();
        }
    }

    public function detail($data, $id)
    {
        $title = '';
        $description = '';
        $this->id = $id;
        if($data) {
            $this->crud->setTable($data);
            switch ($data) {
                case 'users':
                    $this->crud->setTable('passwords');
                    $this->crud->where(['user_id' => $this->id]);
                    $this->crud->unsetDelete();
                    $this->crud->unsetEdit();
                    $this->crud->unsetColumns(['password', 'user_id', 'updated_at']);
                    $this->crud->fieldType('password', 'password');
                    $this->crud->addFields(['password']);
                    $this->crud->callbackBeforeInsert(function ($info){
                        $info->data['created_at']   = date('Y-m-d H:i:s');
                        $info->data['updated_at']   = date('Y-m-d H:i:s');
                        $info->data['user_id']      = $this->id;
                        $info->data['temporary']    = 'Si';
                        $info->data['password']     = password_hash($info->data['password'], PASSWORD_DEFAULT);
                        $p_model = new Password();
                        $passwords = $p_model->where(['user_id' => $this->id, 'status' => 'active'])->findAll();
                        foreach ($passwords as $key => $password) {
                            $p_model->save([
                                'id'        => $password->id,
                                'status'    => 'inactive'
                            ]);
                        }
                        return $info;
                    });

                    $this->crud->displayAs([
                        'attempts'      => 'N° Intentos',
                        'status'        => 'Estado',
                        'created_at'    => 'Fecha de creación',
                        'password'      => 'Contraseña',
                        'temporary'     => 'Temporal'
                    ]);
                    break;
                case 'productive_unit':

                    $this->crud->displayAs([
                        'resource_id'   => 'Recurso',
                        'code'          => 'Codigo',
                        'sowing_date'   => 'Fecha sembrado',
                        'replanted'     => 'Remplantado',
                        'status'        => 'Estado',
                        'created_at'    => 'Creado'
                    ]);
                    
                    $this->crud->where(['lot_id' => $id]);
                    $columns = ['resource_id', 'code', 'sowing_date', 'replanted', 'status', 'created_at'];
                    $this->crud->columns($columns);
                    $this->crud->editFields($columns);
                    if (($key = array_search('created_at', $columns)) !== false) {
                        unset($columns[$key]);
                    }
                    $this->crud->editFields($columns);
                    if (($key = array_search('status', $columns)) !== false) {
                        unset($columns[$key]);
                    }
                    $this->crud->addFields($columns);

                    $this->crud->callbackBeforeInsert(function ($stateParameters) use($id) {
                        $stateParameters->data['created_at']    = date('Y-m-d h:i:s');
                        $stateParameters->data['updated_at']    = date('Y-m-d h:i:s');
                        $stateParameters->data['lot_id']        = $id;
                        $stateParameters->data['replanted']     = $stateParameters->data['replanted'] == "" ? 0 : $stateParameters->data['replanted'];
                        return $stateParameters;
                    });

                    $this->crud->callbackBeforeUpdate(function ($stateParameters) {
                        $stateParameters->data['updated_at'] = date('Y-m-d h:i:s');
                        return $stateParameters;
                    });

                    $this->crud->setRelation('resource_id', 'resources', 'name', ['resource_type_id' => 2]);


                    break;
                
                case 'lots':
                    $this->crud->setRelation('crop_type_id', 'crop_types', 'name');

                    $this->crud->displayAs([
                        'crop_type_id'  => 'Cultivo',
                        'status'        => 'Estado',
                        'name'          => 'Nombre',
                        'created_at'    => 'Creado'
                    ]);

                    $columns = ['crop_type_id', 'name', 'area', 'altitud', 'densidad','status', 'created_at'];
                    $this->crud->columns($columns);
                    $this->crud->editFields($columns);
                    if (($key = array_search('created_at', $columns)) !== false) {
                        unset($columns[$key]);
                    }
                    $this->crud->editFields($columns);
                    if (($key = array_search('status', $columns)) !== false) {
                        unset($columns[$key]);
                    }
                    $this->crud->addFields($columns);

                    $this->crud->callbackBeforeInsert(function ($stateParameters) {
                        $stateParameters->data['created_at']    = date('Y-m-d h:i:s');
                        $stateParameters->data['updated_at']    = date('Y-m-d h:i:s');
                        $stateParameters->data['farm_id']       = $this->id;
                        return $stateParameters;
                    });

                    $this->crud->callbackBeforeUpdate(function ($stateParameters) {
                        $stateParameters->data['updated_at'] = date('Y-m-d h:i:s');
                        return $stateParameters;
                    });

                    
                    $this->crud->setActionButton('Unidades productivas', 'fa fa-bars', function ($row) {
                        return base_url(['table', 'productive_unit', $row->id]);
                    }, false);

                    break;
                case 'resource_presentations':

                    $this->crud->where(['resource_id' => $this->id]);

                    $r_model = new Resource();

                    $resource = $r_model->find($this->id);

                    if($resource->measurement_unit_id == 1){
                        $rp_model = new ResourcePresentation();
                        $count_rp = $rp_model->where(['resource_id' => $this->id])->countAllResults();
                        if($count_rp != 0){
                            $this->crud->unsetAdd();
                            $this->crud->unsetDelete();
                        }
                    }

                    $title = "Producto | $resource->name";

                    $this->crud->displayAs([
                        'presentation'          => 'Presentación',
                        'presentation_value'    => 'Valor presentación',
                        'status'                => 'Estado',
                        'created_at'            => 'Creado'
                    ]);

                    $columns = ['presentation', 'presentation_value', 'status', 'created_at'];
                    $this->crud->columns($columns);
                    $this->crud->editFields($columns);
                    if (($key = array_search('created_at', $columns)) !== false) {
                        unset($columns[$key]);
                    }
                    $this->crud->editFields($columns);
                    if (($key = array_search('status', $columns)) !== false) {
                        unset($columns[$key]);
                    }
                    if (($key = array_search('presentation_value', $columns)) !== false) {
                        unset($columns[$key]);
                    }
                    $this->crud->addFields($columns);

                    $this->crud->callbackBeforeInsert(function ($stateParameters) use($resource) {
                        $stateParameters->data['created_at']            = date('Y-m-d h:i:s');
                        $stateParameters->data['updated_at']            = date('Y-m-d h:i:s');
                        $stateParameters->data['resource_id']           = $this->id;

                        if(empty($resource->presentations)){
                            $stateParameters->data['base'] = "Si"; 
                        }

                        // $stateParameters->data['presentation_value']    = $stateParameters->data['presentation_value'] ?? 0;
                        return $stateParameters;
                    });

                    $this->crud->callbackBeforeUpdate(function ($stateParameters) {
                        $stateParameters->data['updated_at'] = date('Y-m-d h:i:s');
                        return $stateParameters;
                    });

                    break;
                default:
                    break;   
            }
            $output = $this->crud->render();
            if (isset($output->isJSONResponse) && $output->isJSONResponse) {
                header('Content-Type: application/json; charset=utf-8');
                echo $output->output;
                exit;
            }

            $this->viewTable($output, $title, $description);
        } else {
            throw PageNotFoundException::forPageNotFound();
        }
    }
}
