<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use CodeIgniter\API\ResponseTrait;

use App\Models\Resource;
use App\Models\MovementDetail;
use App\Models\Farm;
use App\Models\FarmUser;
use App\Traits\JornalHelper;

class ResourceController extends BaseController
{
    use ResponseTrait;
    use JornalHelper;
    protected $r_model;
    protected $md_model;
    protected $dataTable;
    protected $farms_ids;
    protected array $columns = [];

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

        $this->r_model  = new \App\Models\Resource();
        $this->md_model = new \App\Models\MovementDetail();

        // ✅ farms_ids scope
        $user = session('user');
        $this->farms_ids = [];
        
        if ($user) {
            // 1) fincas propias
            $farmModel = new Farm();
            $own = $farmModel->select('id')->where('user_id', $user->id)->findAll();
            $ownIds = array_map(fn($r) => (int)$r->id, $own);

            // 2) fincas asociadas
            $fuModel = new FarmUser();
            $assoc = $fuModel->select('farm_id')->where('user_id', $user->id)->findAll();
            $assocIds = array_map(fn($r) => (int)$r->farm_id, $assoc);

            $this->farms_ids = array_values(array_unique(array_merge($ownIds, $assocIds)));
        }
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

        $jornal = $this->getJornalId($this->farms_ids);
        // var_dump($resource); die;

        return view('resources/kardex', [
            'resource' => $resource,
            'jornal_id' => $jornal->id ?? null
        ]);
    }
///////////////////////////////////////////////////////////
    public function data($id_resource){
        //Scope por fincas (usuario dueño o asociado)
        if (empty($this->farms_ids)) {
            return $this->respond([
                'data' => [],
                'recordsTotal' => 0,
                'recordsFiltered' => 0
            ]);
        }

        //Detectar si existe recurso "Jornal"
        $jornal= $this->getJornalId($this->farms_ids);

        $hasJornal = !empty($jornal);


        $this->md_model
        ->select([
            'movement_details.*',
            'rp.presentation',
            'mu.code',
            'm.resolution',
            'm.date',
            'm.movement_type_id',
            'mt.name as type_name',
            'l.name as name_lote',
            'r.name as resource_name'
        ])
        ->where([
            'movement_details.resource_id'   => $id_resource
        ]);

        $this->md_model->whereIn('m.state_id', [1,2, 3, 4])
        ->join('resources as r', 'r.id = movement_details.resource_id', 'left')
        ->join('resource_presentations as rp', 'rp.id = movement_details.presentation_id', 'left')
        ->join('measurement_units as mu', 'mu.id = r.measurement_unit_id', 'left')
        ->join('movements as m', 'm.id = movement_details.movement_id', 'left')
        ->join('movement_types as mt', 'mt.id = m.movement_type_id', 'left')
        ->join('lots as l', 'l.id = movement_details.lot_id', 'left')
        ->orderBy('movement_details.id', 'ASC')
        ->whereIn('m.farm_id', $this->farms_ids)
        ->orderBy('movement_details.id', 'ASC');

        # code...
        
        //$count_data = $this->md_model->countAllResults(false);
        
        $data = $this->md_model->findAll();
        
        $saldo = 0;
        foreach ($data as $key => $detail) {
            $detail->quantity_detail = $detail->quantity;
            $detail->quantity = (float) $detail->presentation * (float) $detail->quantity;
            
            $isEntrada = in_array((int)$detail->movement_type_id, [1], true);
            $isJornalResource = isset($detail->resource_name) 
                && mb_strtolower(trim($detail->resource_name)) === 'jornal';
            
            if ($isEntrada || ($hasJornal && $isJornalResource)) {
                $saldo += $detail->quantity;
            } else {
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
