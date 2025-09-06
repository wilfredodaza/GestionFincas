<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use CodeIgniter\API\ResponseTrait;

use App\Models\Lot;
use App\Models\Resource;
use App\Models\ProductiveUnit;

class ProductiveUnitController extends BaseController
{
    use ResponseTrait;

    protected $dataTable;
    private $l_model;
    private $r_model;
    private $pu_model;

    public function __construct(){
        $this->dataTable    = (object) [
            'draw'      => $_GET['draw'] ?? 1,
            'length'    => $length = $_GET['length'] ?? 10,
            'start'     => $start = $_GET['start'] ?? 1,
            'page'      => $_GET['page'] ?? ceil(($start - 1) / $length + 1)
        ];
        $this->columns = $_GET['columns'] ?? [];

        $this->l_model = new Lot();
        $this->r_model = new Resource();
        $this->pu_model = new ProductiveUnit();
    }

    public function index($lot_id)
    {
        $lot = $this->l_model->find($lot_id);

        $recursos = $this->r_model->where(['resource_type_id' => 4])->findAll();

        $data = (object)[
            'title'     => "Unidades Productiva",
            'form'      => [
                (object) ["name" => "lote", "required" => false, "title" => "", "value" => $lot->id, "type" => "hidden"],
                (object) ["name" => "pu_id", "required" => false, "title" => "", "value" => "", "type" => "hidden"],
                (object) ["name" => "recurso", "required" => true, "title" => "Recurso", "value" => "", "type" => "select", "options" => $recursos],
                (object) ["name" => "codigo", "required" => true, "title" => "Codigo", "value" => "", "type" => "text"],
                (object) ["name" => "siembra", "required" => true, "title" => "Fecha de siembra", "value" => "", "type" => "date"],
                (object) ["name" => "replan", "required" => false, "title" => "Replantados", "value" => "", "type" => "number"],
                (object) ["name" => "estado", "required" => true, "title" => "Estado", "value" => "Activo", "type" => "select", "options" => [
                    (object) ["id" => "Activo", "name" => "Activo"],
                    (object) ["id" => "Inactivo", "name" => "Inactivo"],
                ]],
            ]
        ];

        return view("productive_units/index", [
            'lot'   => $lot,
            'data'  => $data
        ]);
    }

    public function data($lot_id){

        $this->pu_model->where(['lot_id' => $lot_id]);

        $count_data = $this->pu_model->countAllResults(false);

        $this->pu_model
            ->select([
                'productive_unit.*',
                'r.name as product_name'
            ])
            ->join('resources r', 'r.id = productive_unit.resource_id', 'left')
            ->orderBy('id', 'DESC');

        $data = $this->dataTable->length == -1 ? $this->m_model->findAll() : $this->pu_model->paginate($this->dataTable->length, 'dataTable', $this->dataTable->page);


        return $this->respond([
            'data'              => $data,
            'draw'              => $this->dataTable->draw,
            'recordsTotal'      => $count_data,
            'recordsFiltered'   => $count_data,
            'post'              => $this->dataTable
        ]);
    }

    public function save(){
        try{
            $data = $this->request->getJson();

            foreach ($data->productive_units as $key => $productive_unit) {
                $this->saveProductive($productive_unit);
            }

            return $this->respond([
                'data'  => $data
            ]);

        }catch(\Exception $e){
			return $this->respond(['title' => 'Error en el servidor', 'error' => $e->getMessage()], 500);
		}
    }

    protected function saveProductive($data){
        $productive_unit = [
            'lot_id'        => $data->lote,
            'resource_id'   => $data->recurso,
            'code'          => $data->codigo,
            'sowing_date'   => $data->siembra,
            'replanted'     => $data->replan,
            'status'        => $data->estado,
        ];

        if(!empty($data->pu_id))
            $productive_unit['id'] = $data->pu_id;

        $this->pu_model->save($productive_unit);

        return $productive_unit;
    }
}
