<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use CodeIgniter\API\ResponseTrait;
use Config\Services;

use App\Models\Movement;
use App\Models\Resource;
use App\Models\MovementDetail;
use App\Models\MovementType;
use App\Models\MeasurementUnit;
use App\Models\ResourceType;
use App\Models\Provider;
use App\Models\User;
use App\Models\State;
use App\Models\ResourcePresentation;
use App\Models\FarmUser;
use App\Traits\JornalHelper;

use Mpdf\Mpdf;

class MovementController extends BaseController
{
    use ResponseTrait;
    use JornalHelper;
    protected $dataTable;
    protected $m_model;
    protected $r_model;
    protected $s_model;
    protected $p_model;
    protected $u_model;
    protected $md_model;
    protected $rt_model;
    protected $mt_model;
    protected $mm_model;
    protected $rp_model;
    protected $farms_ids;
    protected $fu_model;
    protected $states;
    protected array $columns = [];

    public function __construct(){
        
        $this->dataTable    = (object) [
            'draw'      => $_GET['draw'] ?? 1,
            'length'    => $length = $_GET['length'] ?? 10,
            'start'     => $start = $_GET['start'] ?? 1,
            'page'      => $_GET['page'] ?? ceil(($start - 1) / $length + 1)
        ];
        $this->columns = $_GET['columns'] ?? [];

        $this->m_model = new Movement();
        $this->r_model = new Resource();
        $this->s_model = new State();
        $this->p_model = new Provider();
        $this->u_model = new User();
        $this->md_model = new MovementDetail();
        $this->rt_model = new ResourceType();
        $this->mt_model = new MovementType();
        $this->mm_model = new MeasurementUnit();
        $this->rp_model = new ResourcePresentation();
        $this->fu_model = new FarmUser();


        $roleId = $this->u_model->where(['role_id' => session('user')->role_id]);
        if($roleId !== 1){
            $this->farms_ids = $this->fu_model
                ->where(['user_id' => session('user')->id, 'status' => 'Activo'])->findAll();
        }else{
            $this->farms_ids = $this->fu_model
                ->where(['status' => 'Activo'])->findAll();
        }
        $this->farms_ids = array_column($this->farms_ids, 'farm_id');

        // $user = $this->u_model->find(1);
        // $user->token = bin2hex(random_bytes(32));
        
        // $session = session();
        // $session->set('user', $user);



        // $this->farms_ids = array_map(fn($obj) => $obj->id, session('user')->farms);

        $s_model = new State();
        $this->states = $s_model->findAll();

        // var_dump([$this->farms_ids]); die;
        // $this->m_model->orderBy('movements.id', 'DESC');

        //$this->p_model->where(['status' => 'Activo']);
        $this->r_model->where(['resources.status' => 'Activo']);
        $this->rt_model->where(['status' => 'Activo']);
        $this->mm_model->where(['status' => 'Activo']);
        $this->rp_model->where(['status' => 'Activo']);
    }

    private function applyFarmScope(\CodeIgniter\Model $model)
    {
        $roleId = (int) session('user')->role_id;

        // ✅ Admin ve todo
        if ($roleId === 1) {
            return $model;
        }
        if (!empty($this->farms_ids)) {
        $model->whereIn('movements.farm_id', $this->farms_ids);
        } else {
            $model->where('1 = 0');
        }

    return $model;
    }

    public function index($type)
    {
        // Color();
        $u_model = new User();
        $user = $u_model->find(session('user')->id);
        $session = session();
        $session->set('user', $user);
        $roleId = (int)session('user')->role_id;

        $data = (object)[
            'id'            => "",
            'title'         => "",
            'button'        => "",
            'form_filter'   => []
        ];

        switch ($type) {
            case 'bills':
                $data->id       = 1;
                $data->title    = 'Compras y Gastos';
                $data->button   = 'Añadir compra';
                $type_movements = $this->mt_model->whereIn('id', [1, 3])->findAll();
                $sellers        = $this->m_model->select('seller')->orderBy('seller', 'ASC')->findAll();
                $providers      = $this->p_model->findAll();

                $states         = $this->s_model->whereIn('id', [3, 4])->findAll();
                $farms          = $this->fu_model
                    ->select([
                        'farms.*'
                    ])
                    ->where(['farms_users.user_id' => session('user')->id, 'farms_users.status' => 'Activo'])
                    ->join('farms', 'farms.id = farms_users.farm_id')
                    ->findAll();

                array_unshift($providers, (object)["id" => -1, "name" => "Sin proveedores"]);

                foreach ($sellers as $key => $seller) {
                    $seller->id = $seller->seller;
                    $seller->name = $seller->seller;
                }

                $data->form_filter = [
                    (object) ["name" => "fecha_mov", "required" => false, "allow_new" => false, "title" => "Fecha de compra", "value" => "", "type" => "date_range"],
                    (object) ["name" => "tipo_de_movimiento", "required" => false, "allow_new" => false, "title" => "Tipo de movimiento", "value" => "", "type" => "select", "options" => $type_movements],
                    (object) ["name" => "farm_id", "required" => false, "allow_new" => false, "title" => "Finca", "value" => "", "type" => "select", "options" => $farms, "multiple" => true],
                    (object) ["name" => "pagado", "required" => false, "allow_new" => true, "title" => "Pagado por", "value" => "", "type" => "select", "options" => $sellers],
                    (object) ["name" => "referencia", "required" => false, "allow_new" => false, "title" => "# Referencia", "value" => "", "type" => "text"],
                    (object) ["name" => "proveedor", "required" => false, "allow_new" => false, "title" => "Proveedor", "value" => "", "type" => "select", "options" => $providers],
                    (object) ["name" => "estado", "required" => false, "allow_new" => false, "title" => "Estado", "value" => "", "type" => "select", "options" => $states],
                ];
                break;
                
            case 'activities':
                $data->id       = 2;
                $data->title    = 'Actividades';
                $data->button   = 'Añadir actividad';

                break;
            case 'wage':
                $data->id       = 3;
                $data->title    = 'Jornales';
                    
                break;
                
            default:
                # code...
                break;
            }

            $db = \Config\Database::connect();
            $jornal = $db->table('resources')
                ->select('id');
                if($roleId !== 1){
                    $jornal->whereIn('farm_id', $this->farms_ids ?: [-1]);  
                }
                //->whereIn('farm_id', $this->farms_ids ?: [-1])
                $row = $jornal
                ->where("LOWER(TRIM(name))", 'jornal')
                ->limit(1)
                ->get()
                ->getRow();


            return view('movements/index', [
                'data'  => $data,
                'jornal_id' => $row->id ?? null
            ]);

    }
/////////////////////////////////////////////////////////
    public function data($type){

        $filters = (object) $this->request->getGet();

    // -----------------------------
    // 1) Aplica reglas por tipo (solo reglas de negocio)
    // -----------------------------

        switch ($type) {
            case '3':
                $this->m_model
                    ->join('movement_details as md', 'md.movement_id = movements.id', 'inner')
                    ->join('resources as r', 'r.id = md.resource_id', 'inner')
                    ->where("LOWER(TRIM(r.name))", 'jornal')
                    ->groupStart()
                        ->where('movements.movement_type_id', 2)
                        ->whereIn('movements.state_id', [1,2, 3])   // si también quieres 4, agrégalo aquí
                    ->groupEnd()
                    ->groupBy('movements.id'); // evita duplicados por join

                    $this->mt_model->where(['id' => 2]);
                break;

            case '1':
                $this->m_model
                    ->select(['m.resolution as custom_number_bill'])
                    ->whereIn('movements.movement_type_id', [1, 3])
                    ->whereIn('movements.state_id', [2, 3, 4])
                    ->join('movements as m', 'm.id = movements.movement_reference', 'left');
                $this->mt_model->whereIn('id', [1, 3]);
                break;

            default:
                $this->m_model->where(['movement_type_id' => $type]);
                $this->mt_model->where(['id' => $type]);
                break;
        }

        // -----------------------------
        // 2) KPIs (indicadores) con el mismo scope
        // -----------------------------
        $movement_types = $this->mt_model->findAll();

            foreach ($movement_types as $mt) {

                $mt->states = [];

                foreach ($this->states as $state) {

                    $stateCopy = clone $state;
                    $movements = [];

                    $q = new \App\Models\Movement();
                    $q->filter($filters);
                    $this->applyFarmScope($q);

                    if ($type == 3) {
                        if (in_array($state->id, [1,2, 3])) {
                            $movements = $q
                                ->select('movements.*')
                                ->join('movement_details as md', 'md.movement_id = movements.id', 'left')
                                ->join('resources as r', 'r.id = md.resource_id', 'inner')
                                ->where("LOWER(TRIM(r.name))", 'jornal')
                                ->where([
                                    'movements.movement_type_id' => $mt->id,
                                    'movements.state_id'         => $state->id
                                ])
                                ->groupBy('movements.id') // evita duplicados por join
                                ->findAll();
                        }
                    } else {
                        $movements = $q->where([
                            'movements.movement_type_id' => $mt->id,
                            'movements.state_id'         => $state->id
                        ])->findAll();
                    }

                    if (!empty($movements)) {
                        $stateCopy->movements = $movements;
                        $mt->states[] = $stateCopy;
                    }
                }
            }
        
        

        // -----------------------------
        // 3) Listado principal (data + count) con el mismo scope
        // -----------------------------
        $this->m_model->filter($filters);
        $this->applyFarmScope($this->m_model);
        

        $this->m_model
            ->select([
                'movements.*',
                'mt.name as movement_type_name',
                'p.name as provider_name'
            ])
            ->join('movement_types as mt', 'mt.id = movements.movement_type_id', 'left')
            ->join('providers as p', 'p.id = movements.provider_id', 'left')
            ->orderBy('movements.id', 'DESC');

        // count con los filtros ya aplicados (correcto)
        $count_data = $this->m_model->countAllResults(false);
        //log_message('info', 'roleId='.(int)session('user')->role_id.' farms_ids='.json_encode($this->farms_ids));

        $data = $this->dataTable->length == -1
            ? $this->m_model->findAll()
            : $this->m_model->paginate($this->dataTable->length, 'dataTable', $this->dataTable->page);

        return $this->respond([
            'data'              => $data,
            'draw'              => $this->dataTable->draw,
            'recordsTotal'      => $count_data,
            'recordsFiltered'   => $count_data,
            'post'              => $this->dataTable,
            'indicadores'       => $movement_types,
            'filters'           => $filters
        ]);

        
    }
////////////////////////////////////////////////////////////
    public function created($type){
        $userId = (int) session('user')->id;
        $movement = [];
        if (strpos($type, "_") !== false) {
            [$type, $movement_id] = explode("_", $type, 2);
            $movement = $this->m_model->find($movement_id);
        } else {
            $movement_id = null; // o 0, o "" según tu necesidad
        }
        $resources          = [];
        
        $db = $this->r_model->db;
        $farmIds = array_merge(
            array_map(
                fn($f) => (int)$f->id,
                $db->table('farms')->select('id')->where('user_id', $userId)->get()->getResult()
            ),
            array_map(
                fn($f) => (int)$f->farm_id,
                $db->table('farms_users')->select('farm_id')->where([
                    'user_id' => $userId,
                    'status'  => 'Activo'
                ])->get()->getResult()
            )
        );
        $farmIds = array_values(array_unique($farmIds));
        $resourcesQuery = $this->r_model->whereIn('farm_id', $farmIds);

        $providers = $this->p_model
            ->select(['providers.*'])
            ->join('farms f', 'f.id = providers.farm_id')
            ->join('farms_users fu', 'f.id = fu.farm_id')
            ->where([
                'fu.user_id' => session('user')->id,
                'fu.status'  => 'Activo'
            ])
            ->groupBy('providers.id')
            ->findAll();

        $movement_type      = $this->mt_model->find($type);

        $farms               = $this->fu_model
            ->select([
                'farms.*'
            ])
            ->where(['farms_users.user_id' => session('user')->id, 'farms_users.status' => 'Activo'])
            ->join('farms', 'farms.id = farms_users.farm_id')
            ->findAll();

        switch ($type) {
            case '1': $resourcesQuery->where('LOWER(TRIM(name)) !=', 'jornal'); break;
            case '3': $resourcesQuery->where('LOWER(TRIM(name))', 'jornal'); break;
        }
        $resourcesQuery->where("EXISTS (SELECT 1 FROM resource_presentations rp WHERE rp.resource_id = resources.id)");

        $resources = $resourcesQuery->findAll();
        
        $jornal = $this->getJornalId($farmIds);

        return view('movements/new', [
            'resources'         => $resources,
            'providers'         => $providers,
            'movement_type'     => $movement_type,
            'movement'          => $movement,
            'farms'             => $farms,
            'jornal_id'         => $jornal
            // 'measurement_units' => $measurement_units
        ]);
    }
/////////////////////////////////////////////////
    public function store(){
        try{
            $data = $this->request->getJson();

            if(isset($data->api)){
                $errors = $this->validarData($data);
                if(!empty($errors)){
                    return $this->respond(['title' => 'Error de validación', 'error' => $errors], 400);
                }
            }
            

            if(!empty($data->support_file)){
                $fileData = base64_decode($data->support_file);

                $uploadPath = FCPATH . 'uploads/'; // => public/uploads/
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $newName = uniqid() . '_' . $data->support_name;

                $filePath = $uploadPath . $newName;
                file_put_contents($filePath, $fileData);

            }

            $movement = [
                'user_id'           => isset($data->usuario) ? $data->usuario : session('user')->id,
                'farm_id'           => $data->farm_id,
                'movement_type_id'  => $data->movement_type_id,
                'provider_id'       => isset($data->provider_id) && !empty($data->provider_id) ? $data->provider_id : null,
                'state_id'          => $data->state_id,
                'value'             => 0,
                'date'              => $data->movement_date,
                'note'              => $data->notes,
                'number_bill'       => isset($data->number_bill) ? $data->number_bill : null,
                'title'             => isset($data->title) ? $data->title : null,
                'movement_reference'=> isset($data->movement_reference) ? $data->movement_reference : null,
                'support'           => isset($newName) ? $newName : null,
                'seller'            => isset($data->seller) && !empty($data->seller) ? $data->seller : null,
            ];


            if($this->m_model->save($movement)){

                $movement_id = $this->m_model->insertID();

                $total = 0;
                $resources = [];

                foreach ($data->resources as $key => $resource) {

                    if($data->movement_type_id == 2){
                        $resource->value = (int) $resource->presentation->presentation * (float) ($resource->resource_type_id != 1 ? $resource->presentation->presentation_value : $resource->value);
                    }

                    if(!isset($resource->presentation->id)){
                        $this->rp_model->save([
                            'resource_id'   => $resource->id,
                            'presentation'  => $resource->presentation->presentation
                        ]);
                        $resource->presentation->id = $this->rp_model->insertID();
                    }

                    $data_resource = [
                        'movement_id'   => $movement_id,
                        'lot_id'        => isset($resource->lot_id) ? $resource->lot_id : null,
                        'resource_id'   => $resource->id ?? null,
                        'quantity'      => $resource->quantity,
                        'value'         => $resource->value,
                        'note'          => $resource->note ?? null,

                        // 'measurement_unit_id'   => $resource->measurement_unit_id,
                        'presentation_id'          => $resource->presentation->id ?? null,
                        'presentation_value'    => $resource->value / (int) $resource->presentation->presentation,
                    ];

                    $total = $total + ($resource->quantity * $resource->value);
                    if($data->movement_type_id == 1 || $data->movement_type_id == 3){
                        $this->rp_model->save([
                            'id'                    => $resource->presentation->id,
                            'presentation_value'    => $resource->value / (int) ($resource->presentation->presentation)
                        ]);
                    }

                    $this->md_model->save($data_resource);
                }

                $this->m_model->save([
                    'id'    => $movement_id,
                    'value' => $total
                ]);

                switch ($data->movement_type_id) {
                    case '1':
                        if(isset($data->api)){
                            return $this->respond([
                                'title' => 'Movimiento creado con exito.'
                            ]);
                        }
                        return redirect()->to(base_url(['dashboard/movements/bills']));
                        break;
                    case '2':
                        if(isset($data->api)){
                            return $this->respond([
                                'title' => 'Movimiento creado con exito.'
                            ]);
                        }
                        return redirect()->to(base_url(['dashboard/movements/activities']));
                        break;
                    case '3':
                        $this->m_model->save([
                            'id'        => $data->movement_reference,
                            'state_id'  => 3
                        ]);
                        if(isset($data->api)){
                            return $this->respond([
                                'title' => 'Movimiento creado con exito.'
                            ]);
                        }
                        return redirect()->to(base_url(['dashboard/movements/wage']));
                        break;
                    
                    default:
                        break;
                }
            }

            return $this->respond([
                'data' => $data,
            ]);
        }catch(\Exception $e){
			$line = null;
			if (method_exists($e, 'getLine')) {
				$line = $e->getLine();
			}
			return $this->respond(['title' => 'Error en el servidor', 'error' => $e->getMessage(), 'line' => $line], 500);
		}
    }
///////////////////////////////////////////////////////////////////////////
    public function edit($id_movement){
        $userId = (int) session('user')->id;
        $resources          = [];

        $db = $this->r_model->db;
        $farmIds = array_merge(
            array_map(
                fn($f) => (int)$f->id,
                $db->table('farms')->select('id')->where('user_id', $userId)->get()->getResult()
            ),
            array_map(
                fn($f) => (int)$f->farm_id,
                $db->table('farms_users')->select('farm_id')->where([
                    'user_id' => $userId,
                    'status'  => 'Activo'
                ])->get()->getResult()
            )
        );
        $farmIds = array_values(array_unique($farmIds));
        $resourcesQuery = $this->r_model->whereIn('farm_id', $farmIds);

        //$providers          = $this->p_model->findAll();
        $providers = $this->p_model
            ->select(['providers.*'])
            ->join('farms f', 'f.id = providers.farm_id')
            ->join('farms_users fu', 'f.id = fu.farm_id')
            ->where([
                'fu.user_id' => session('user')->id,
                'fu.status'  => 'Activo'
            ])
            ->groupBy('providers.id')
            ->findAll();

        $movement           = $this->m_model->find($id_movement);
        $farms               = $this->fu_model
            ->select([
                'farms.*'
            ])
            ->where(['farms_users.user_id' => session('user')->id, 'farms_users.status' => 'Activo'])
            ->join('farms', 'farms.id = farms_users.farm_id')
            ->findAll();

        switch ($id_movement) {
            case '1': $resourcesQuery->where('LOWER(TRIM(name)) !=', 'jornal'); break;
            case '3': $resourcesQuery->where('LOWER(TRIM(name))', 'jornal'); break;
        }
        $resourcesQuery->where("EXISTS (SELECT 1 FROM resource_presentations rp WHERE rp.resource_id = resources.id)");

        $resources = $resourcesQuery->findAll();
        
        $jornal= $this->getJornalId($farmIds);
        // var_dump($movement); die;
        // var_dump($resources); die;
        
                    echo '<pre>';
                    //print_r(session('user'));
                    print_r ($id_movement);
                    echo '</pre>';
                    

        return view('movements/edit', [
            'resources'         => $resources,
            'providers'         => $providers,
            'movement'          => $movement,
            'farms'             => $farms,
            'jornal_id'         => $jornal
        ]);
    }
//////////////////////////////////////////////////
    public function updated(){
        try {
            $data = $this->request->getJson();
            $movement = $this->m_model->find($data->id);
            $movementTypeId = (int) $data->movement_type_id;
            $value_total = 0;
            $resources = [];
            
            foreach ($data->details as $key => $resource) {

                if(!isset($resource->presentation->id)){
                    $this->rp_model->save([
                        'resource_id'   => $resource->id,
                        'presentation'  => $resource->presentation->presentation
                    ]);
                    $resource->presentation->id = $this->rp_model->insertID();
                }

                if($data->movement_type_id == 2 || $data->movement_type_id == 3){
                    $resource->value = (int) $resource->presentation->presentation * (float) ($resource->resource_type_id == 1 ? $resource->value : $resource->presentation->presentation_value);
                }

                $value_total = $this->updatedDetail($resource, $value_total, $movementTypeId, $data->id);
            }

            if(!empty($data->support_file)){
                if($movement->support != $data->support_name){
                    $fileData = base64_decode($data->support_file);
    
                    $uploadPath = FCPATH . 'uploads/'; // => public/uploads/
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }
    
                    $newName = uniqid() . '_' . $data->support_name;
    
                    $filePath = $uploadPath . $newName;
                    file_put_contents($filePath, $fileData);
                }

            }

            $movement = [
                'id'                => $data->id,
                'user_id'           => session('user')->id,
                'farm_id'           => $data->farm_id,
                'movement_type_id'  => $data->movement_type_id,
                'provider_id'       => isset($data->provider_id) && !empty($data->provider_id) ? $data->provider_id : null,
                'state_id'          => $data->state_id,
                'value'             => $value_total,
                'date'              => $data->date,
                'note'              => $data->note,
                'number_bill'       => isset($data->number_bill) ? $data->number_bill : null,
                'title'             => isset($data->title) ? $data->title : null,
                'movement_reference'=> isset($data->movement_reference) ? $data->movement_reference : null,
                'support'           => isset($newName) ? $newName : null,
                'seller'            => isset($data->seller) && !empty($data->seller) ? $data->seller : null,
            ];

            $this->m_model->save($movement);

            switch ($movementTypeId) {
                case '1':
                    return redirect()->to(base_url(['dashboard/movements/bills']));
                    break;
                case '2':
                    // return $this->respond([$movement, $data]);
                    return redirect()->to(base_url(['dashboard/movements/activities']));
                    break;
                case '3':
                    // return $this->respond([$movement, $data]);
                    return redirect()->to(base_url(['dashboard/movements/bills']));
                    break;
                
                default:
                    # code...
                    break;
            }

        }catch(\Exception $e){
			return $this->respond(['title' => 'Error en el servidor', 'error' => $e->getMessage()], 500);
		}
    }

    public function download($id_movement){
        $this->response->setHeader('Content-Type', 'application/pdf');
        $mpdf = new Mpdf([
			'mode'          => 'utf-8',
			'format'        => 'Letter',
			"margin_left"   => 5,
			"margin_right"  => 5,
			"margin_top"    => 5,
			"margin_bottom" => 17,
			"margin_header" => 0
		]);
        $mpdf->SetHTMLFooter('
        	<hr>
			<table width="100%">
				<tr>
					<td width="50%" align="left">Software elaborado por IPlanet Colombia SAS</td>
					<td width="50%" align="right">Pagina {PAGENO}/{nbpg}</td>
				</tr>
			</table>
		');

        $movement = $this->m_model
            ->select([
                'movements.*',
                'p.name as provider_name'
            ])
            ->join('providers as p', 'p.id = movements.provider_id', 'left')
            ->find($id_movement);
        
        
        $page = view('pdf/movement', [
            'movement' => $movement
        ]);
        // print(FCPATH); die;
        $css = file_get_contents(FCPATH . 'pdf/movement.css');
        $inter = file_get_contents(FCPATH . 'pdf/inter.css');
        $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($inter, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($page);
        $mpdf->Output("{$movement->type->name}_{$movement->resolution}.pdf", 'I');
    }
////////////////////////////////////////////////////////
    protected function updatedDetail($resource, $value, $movement_type_id, $movement_id){
        if(!$resource->productNew && $resource->isDelete){
            $this->md_model->delete($resource->movement_detail_id);
        }else if($resource->productNew && !$resource->isDelete){
            $data_resource = [
                'movement_id'   => $movement_id,
                'lot_id'        => isset($resource->lot_id) ? $resource->lot_id : null,
                'resource_id'   => $resource->id,
                'quantity'      => $resource->quantity,
                'value'         => $resource->value,
                'note'          => $resource->note,

                'presentation_id'       => $resource->presentation->id,
                'presentation_value'    => $resource->value / (int) $resource->presentation->presentation,
            ];
            
            
            if($movement_type_id == 1 || $movement_type_id == 3){
                $value = $value + ($resource->quantity * $resource->value);
                $this->rp_model->save([
                    'id'                    => $resource->presentation->id,
                    'presentation_value'    => $resource->value / (int) $resource->presentation->presentation
                ]);
            }else if($movement_type_id == 2){
                $value = $value + (((int) $resource->presentation->presentation * $resource->quantity) * ($resource->resource_type_id == 1 ? $resource->value : $resource->presentation->presentation_value));
                $this->rp_model->save([
                    'id'                    => $resource->presentation->id,
                    'presentation_value'    => ($resource->resource_type_id == 1 ? $resource->value : $resource->presentation->presentation_value) / (int) $resource->presentation->presentation
                ]);
            }

            $this->md_model->save($data_resource);
        }else if(!$resource->productNew && !$resource->isDelete){
            $data_resource = [
                'id'            => $resource->movement_detail_id,
                'lot_id'        => isset($resource->lot_id) ? $resource->lot_id : null,
                'resource_id'   => $resource->id,
                'quantity'      => $resource->quantity,
                'value'         => $resource->value,
                'note'          => $resource->note,
                'presentation_value'    => ($resource->resource_type_id == 1 ? $resource->value : $resource->presentation->presentation_value) / (int) $resource->presentation->presentation,
            ];

            
            if($movement_type_id == 1 || $movement_type_id == 3){
                $value = $value + ($resource->quantity * $resource->value);
                $this->rp_model->save([
                    'id'                    => $resource->presentation->id,
                    'presentation_value'    => $resource->value / (int) $resource->presentation->presentation
                ]);
            }else if($movement_type_id == 2){
                $value = $value + (((int) $resource->presentation->presentation * $resource->quantity) * ($resource->resource_type_id == 1 ? $resource->value : $resource->presentation->presentation_value));
                $this->rp_model->save([
                    'id'                    => $resource->presentation->id,
                    'presentation_value'    => ($resource->resource_type_id == 1 ? $resource->value : $resource->presentation->presentation_value) / (int) $resource->presentation->presentation
                ]);
            }

            $this->md_model->save($data_resource);
        }

        return $value;
    }

    public function state(){
        try{
            $data = $this->request->getJson();
            $this->m_model->save([
                'id'        => $data->movement_id,
                'state_id'  => $data->state_id
            ]);

            // return $this->respond($data);
            
            switch ($data->movement_type_id) {
                case '1':
                    return redirect()->to(base_url(['dashboard/movements/bills']));
                    break;
                case '2':
                    if($data->state_id == 2){
                        $value = 0;
                        foreach ($data->resources as $key => $resource) {
                            $detail = $this->md_model->find($resource->movement_detail_id);
                            $this->md_model->save([
                                'id'                    => $resource->movement_detail_id,
                                'approximate_amount'    => $detail->quantity
                            ]);
                            $value = $this->updatedDetail($resource, $value, $data->movement_type_id, $data->movement_id);
                        }
                        $this->m_model->save([
                            'id'        => $data->movement_id,
                            'value'     => $value
                        ]);
                    }
                    return redirect()->to(base_url(['dashboard/movements/activities']));
                    break;
                case '3':
                    return redirect()->to(base_url(['dashboard/movements/wage']));
                    break;
                
                default:
                    # code...
                    break;
            }

        }catch(\Exception $e){
			return $this->respond(['title' => 'Error en el servidor', 'error' => $e->getMessage()], 500);
		}
    }

    protected function validarData($data){
        $errors = [];
        
        if(isset($data->provider) && empty($data->provider)){
            $errors[] = "El proovedor no fue mensionado.";
        }else{
            $provider_aux = $this->p_model->like('name', "%{$data->provider}%")->first();
            if(!$provider_aux){
                $errors[] = "El proovedor no existe.";
            }else{
                $data->provider_id = $provider_aux->id;
            }
        }
        
        if(empty($data->resources)){    
            $errors[] = "Debe agregar al menos un producto o recurso.";
        }

        foreach($data->resources as $resource){
            if(empty($resource->name)){
                $errors[] = "El nombre del producto o recurso es obligatorio.";
            }else{
                $resource->name = ucfirst($resource->name);
                $resouces_aux = $this->r_model->like('name', "%{$resource->name}%")->first();
                if(!$resouces_aux){
                    $errors[] = "El producto o recurso no existe.";
                }else{
                    $resource->id = $resouces_aux->id;
                    foreach($resouces_aux->presentations as $presentation){
                        if(isset($presentation->base) && $presentation->base == 'Si'){
                            $resource->presentation = $presentation;
                            break;
                        }
                    }
                    if(!isset($resource->presentation) || !empty($resource->presentation)){
                        $resource->presentation = $resouces_aux->presentations[0];
                    }
                }
            }
            if(empty($resource->quantity)){
                $errors[] = "La cantidad de *{$resource->name}* es obligatoria.";
            }else if($resource->quantity <= 0){
                $errors[] = "La cantidad de *{$resource->name}* debe ser mayor a 0.";
            }
            if(empty($resource->value)){
                $errors[] = "El valor de *{$resource->name}* es obligatorio.";
            }else if($resource->value <= 0){
                $errors[] = "El valor de *{$resource->name}* debe ser mayor a 0.";
            }
        }


        return $errors;
    }

}
