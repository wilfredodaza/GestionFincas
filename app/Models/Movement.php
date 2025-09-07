<?php

namespace App\Models;

use CodeIgniter\Model;

class Movement extends Model
{
    protected $table            = 'movements';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'lot_id',
        'farm_id',
        'movement_type_id',
        'provider_id',
        'state_id',
        'resolution',
        'value',
        'date',
        'note',
        'number_bill',
        'movement_reference',
        'title',
        'support',
        'seller'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ["functionBeforeInsert"];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = ["functionAfterFind"];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function filter($data){
        $wheres = [];
        if(isset($data->tipo_de_movimiento) && !empty($data->tipo_de_movimiento)){
            $wheres["{$this->table}.movement_type_id"] = $data->tipo_de_movimiento;
        }

        if(isset($data->date_init) && !empty($data->date_init)){
            $wheres["{$this->table}.date >="] = $data->date_init;
        }

        if(isset($data->date_end) && !empty($data->date_end)){
            $wheres["{$this->table}.date <="] = $data->date_end;
        }

        if(isset($data->estado) && !empty($data->estado)){
            $wheres["{$this->table}.state_id"] = $data->estado;
        }

        if(isset($data->proveedor) && !empty($data->proveedor)){
            if($data->proveedor == "-1")   
                $wheres["{$this->table}.provider"] = null;
            else
                $wheres["{$this->table}.provider"] = $data->proveedor;
        }

        if(isset($data->pagado) && !empty($data->pagado)){
            $this->like("{$this->table}.seller", "%{$data->seller}%");
        }

        if(isset($data->referencia) && !empty($data->referencia)){
            $this->like("{$this->table}.resolution", "%{$data->referencia}%");
        }


        if(!empty($wheres))
            $this->where($wheres);
    }

    protected function functionBeforeInsert(array $data){

        $movement = $this->where([
            'farm_id'           => $data['data']['farm_id'],
            'movement_type_id'  => $data['data']['movement_type_id']
        ])->orderBy('id', 'DESC')->get()->getResult();

        $data['data']['resolution'] = empty($movement) ? 1 : (int) $movement[0]->resolution + 1;
        
        return $data;
    }

    protected function functionAfterFind(array $data){
        
        log_message("info", "Despues de encontrar movimientos: ". json_encode($data));
        if(isset($data['id'])){
            $data['data']->state = $this->builder('states')
                ->where([
                    'id' => $data['data']->state_id
                ])->get()->getResult()[0];

            $data['data']->type = $this->builder('movement_types')
                ->where([
                    'id' => $data['data']->movement_type_id
                ])->get()->getResult()[0];
            
            $provider = $this->builder('providers')
                ->where([
                    'id' => $data['data']->provider_id
                ])->get()->getResult();
            $data['data']->provider = !empty($provider) ? $provider[0] : [];

            $data['data']->farm = $this->builder('farms')
                ->where([
                    'id' => $data['data']->farm_id
                ])->get()->getResult()[0];

            $data['data']->details = $this->builder('movement_details')
                ->select([
                    'movement_details.*',
                    'r.name as name',
                    'l.name as lot_name',
                    'r.measurement_unit_id'
                ])
                ->where([
                    'movement_id' => $data['data']->id
                ])
                ->join('resources as r', 'r.id = movement_details.resource_id', 'left')
                ->join('lots as l', 'l.id = movement_details.lot_id', 'left')
                ->get()->getResult();
            
            foreach ($data['data']->details as $key => $detail) {
                $detail->presentation = $this->builder('resource_presentations')
                    ->select([
                        'resource_presentations.*',
                        // 'ms.name',
                        // 'ms.code'
                    ])
                    ->where([
                        'resource_presentations.resource_id'    => $detail->resource_id,
                        'resource_presentations.id'             => $detail->presentation_id,
                    ])
                    // ->join('measurement_units as ms' ,'ms.id = resource_presentations.measurement_unit_id', 'left')
                    ->get()->getResult()[0];
                $detail->measurement_unit = $this->builder('measurement_units')
                    ->where([
                        'id' => $detail->measurement_unit_id
                    ])
                    // ->join('measurement_units as ms' ,'ms.id = resource_presentations.measurement_unit_id', 'left')
                    ->get()->getResult()[0];
            }
            
            $file = FCPATH . "uploads\\" . $data['data']->support;

            if (is_file($file)) {
                // Lee el archivo y convierte a base64
                $fileData = file_get_contents($file);
                $base64   = base64_encode($fileData);
                $data['data']->support_base64 = $base64;
            } else {
                $data['data']->support_base64 = null; // o un mensaje de error
            }
        }else{
            if(!empty($data['data'])){
                foreach($data['data'] as $movement){
                    if(isset($movement->id)){
                        $movement->state = $this->builder('states')
                            ->where([
                                'id' => $movement->state_id
                            ])->get()->getResult()[0];
    
                        $movement->type = $this->builder('movement_types')
                            ->where([
                                'id' => $movement->movement_type_id
                            ])->get()->getResult()[0];
                        
                        $provider = $this->builder('providers')
                            ->where([
                                'id' => $movement->provider_id
                            ])->get()->getResult();
                        $movement->provider = !empty($provider) ? $provider[0] : [];
    
                        $movement->farm = $this->builder('farms')
                            ->where([
                                'id' => $movement->farm_id
                            ])->get()->getResult()[0];
    
                        $movement->details = $this->builder('movement_details')
                            ->where([
                                'movement_id' => $movement->id
                            ])->get()->getResult();
                    }
                }
            }
        }
        return $data;
    }

    protected function functionBeforeUpdate(array $data){
        
        if(isset($data['data']['state_id'])){
            if($data['data']['state_id'] == 4){
                $movement = $this->find($data['id'][0]);
                if(!empty($movement)){
                    // $data['data']['value'] = 0;
                    // foreach ($movement->details as $key => $detail) {
                    //     $this->builder('movement_details')->save([
                    //         'id'        => $detail->id,
                    //         'quantity'  => 0,
                    //         'value'     => 0,
                            
                    //     ]);
                    // }
                }
            }
        }

        
        // echo json_encode($data);
        return $data;
    }


}
