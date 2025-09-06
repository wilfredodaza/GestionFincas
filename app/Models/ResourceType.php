<?php

namespace App\Models;

use CodeIgniter\Model;

class ResourceType extends Model
{
    protected $table            = 'resource_types';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name', 'state'
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
        if(isset($data['id'])){
            // $data['data']->farms = $this->builder('farms')->where(['user_id' => $data['data']->id])->get()->getResult();
        }else{
            foreach($data['data'] as $resource_type){
                $resource_type->resources = $this->builder('resources')->where(['resource_type_id' => $resource_type->id, 'status' => 'Activo'])->get()->getResult();
            }
        }
        return $data;
    }
}
