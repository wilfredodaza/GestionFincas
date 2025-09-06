<?php

namespace App\Models;

use CodeIgniter\Model;

class Resource extends Model
{
    protected $table            = 'resources';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'resource_type_id',
        'measurement_unit_id',
        'presentation',
        'presentation_value',
        'name',
        'status',
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
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = ["functionAfterFind"];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function functionAfterFind(array $data){
        $resources = is_array($data['data']) ? $data['data'] : [$data['data']];
    
        foreach($resources as $resource){
            if(isset($resource->id)){
                $resource->presentations = $this->builder('resource_presentations')
                    ->where('resource_id', $resource->id)
                    ->get()->getResult();
    
                $resource->measurement_unit = $this->builder('measurement_units')
                    ->where('id', $resource->measurement_unit_id)
                    ->get()->getRow(); // 👈 mejor getRow() para obtener solo un objeto
            }
        }
    
        if (!is_array($data['data'])) {
            $data['data'] = $resources[0];
        }else{
            $data['data'] = $resources;
        }
        return $data;
    }

}
