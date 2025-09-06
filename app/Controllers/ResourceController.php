<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use CodeIgniter\API\ResponseTrait;

use App\Models\Resource;
use App\Models\MovementDetail;

class ResourceController extends BaseController
{
    use ResponseTrait;
    protected $r_model;
    protected $md_model;

    public function __construct(){
        $this->dataTable    = (object) [
            'draw'      => $_GET['draw'] ?? 1,
            'length'    => $length = $_GET['length'] ?? 10,
            'start'     => $start = $_GET['start'] ?? 1,
            'page'      => $_GET['page'] ?? ceil(($start - 1) / $length + 1)
        ];
        $this->columns = $_GET['columns'] ?? [];
        
        $this->r_model = new Resource();
        $this->md_model = new MovementDetail();
    }
    public function index($id_resource)
    {
        $resource = $this->r_model
            ->select([
                'resources.*',
                'SUM(CASE WHEN m.movement_type_id = 1 THEN (md.quantity * rp.presentation) ELSE 0 END) as suma_entrada',
                'SUM(CASE WHEN m.movement_type_id = 2 THEN (md.quantity * rp.presentation) ELSE 0 END) as suma_salida',
                'SUM(CASE WHEN m.movement_type_id = 3 THEN (md.quantity * rp.presentation) ELSE 0 END) as suma_jornales',
            ])
            ->join('movement_details as md', 'md.resource_id = resources.id', 'left')
            ->join('movements as m', 'm.id = md.movement_id', 'left')
            ->join('resource_presentations as rp', 'rp.id = md.presentation_id', 'left')
            ->join('productive_unit as pu', 'pu.resource_id = resources.id', 'left')
        ->find($id_resource);



        // var_dump($resource); die;

        return view('resources/kardex', [
            'resource' => $resource
        ]);
    }

    public function data($id_resource){

        $this->md_model
        ->select([
            'movement_details.*',
            'rp.presentation',
            'mu.code',
            'm.resolution',
            'm.date',
            'm.movement_type_id',
            'mt.name as type_name',
            'l.name as name_lote'
        ])
        ->where([
            'movement_details.resource_id'   => $id_resource
        ]);

        if($id_resource == 1)
            $this->md_model->where(['m.movement_type_id' => 3]);

        $this->md_model->whereIn('m.state_id', [2, 3, 4])
        ->join('resources as r', 'r.id = movement_details.resource_id', 'left')
        ->join('resource_presentations as rp', 'rp.id = movement_details.presentation_id', 'left')
        ->join('measurement_units as mu', 'mu.id = r.measurement_unit_id', 'left')
        ->join('movements as m', 'm.id = movement_details.movement_id', 'left')
        ->join('movement_types as mt', 'mt.id = m.movement_type_id', 'left')
        ->join('lots as l', 'l.id = movement_details.lot_id', 'left')
        ->orderBy('movement_details.id', 'ASC');

        # code...
        
        // $count_data = $this->md_model->countAllResults(false);
        
        $data = $this->md_model->findAll();
        $saldo = 0;
        foreach ($data as $key => $detail) {
            $detail->quantity_detail = $detail->quantity;
            $detail->quantity = (float) $detail->presentation * (float) $detail->quantity;
            if (in_array($detail->movement_type_id, [1]) || $detail->resource_id == 1) { // Ejemplo: 1 = Entrada
                $saldo += $detail->quantity;
            } else { // 3 = Salida
                $saldo -= $detail->quantity;
            }
            $detail->saldo = $saldo;
        }

        return $this->respond([
            'data'              => array_reverse($data),
            'draw'              => $this->dataTable->draw,
            'recordsTotal'      => count($data),
            'recordsFiltered'   => count($data),
            'post'              => $this->dataTable
        ]);
    }
}
