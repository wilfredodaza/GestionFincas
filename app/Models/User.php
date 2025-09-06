<?php


namespace App\Models;


use CodeIgniter\Model;

class User extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['name', 'username', 'email', 'status', 'role_id', 'photo', 'id'];
    protected $returnType       = 'object';

    public function getPassword($id_user){
        $data = $this->builder('passwords')->where(['user_id' => $id_user, 'status' => 'active'])->get()->getResult();
        return $data[0];
    }

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = ["functionBeforeFind"];
    protected $afterFind      = ["functionAfterFind"];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function functionBeforeFind(array $data){
        $this->select([
            'users.*',
            'roles.name as role_name'
        ])
        ->join('roles', 'roles.id = users.role_id');
        return $data;
    }

    protected function functionAfterFind(array $data){

        log_message("info", "Despues de  user:". json_encode($data));

        if(isset($data['data']) || isset($data['id'])){
            $password = $this->builder('passwords')->where(['user_id' => $data['data']->id, 'status' => 'active'])->get()->getResult();

            $data['data']->password = $password[0];
            $data['data']->farms = $this->builder('farms')->where(['user_id' => $data['data']->id])->get()->getResult();
            foreach ($data['data']->farms as $key => $farm) {
                $farm->lots = $this->builder('lots')->where(['farm_id' => $farm->id])->get()->getResult();
            }
        }
        return $data;
    }

}