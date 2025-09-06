<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use CodeIgniter\API\ResponseTrait;

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

use Mpdf\Mpdf;

class MovementController extends BaseController
{
    use ResponseTrait;

    protected $dataTable;
    protected $m_model;
    protected $r_model;
    protected $p_model;
    protected $md_model;
    protected $rt_model;
    protected $mt_model;
    protected $mm_model;
    protected $rp_model;
    protected $farms_ids;

    protected $states;

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
        $this->p_model = new Provider();
        $this->md_model = new MovementDetail();
        $this->rt_model = new ResourceType();
        $this->mt_model = new MovementType();
        $this->mm_model = new MeasurementUnit();
        $this->rp_model = new ResourcePresentation();

        $this->farms_ids = array_map(fn($obj) => $obj->id, session('user')->farms);

        $s_model = new State();
        $this->states = $s_model->findAll();

        // var_dump([ session('user')]); die;

        if(!empty($this->farms_ids)){
            $this->m_model->whereIn('movements.farm_id', $this->farms_ids);
        }

        // $this->m_model->orderBy('movements.id', 'DESC');

        $this->p_model->where(['status' => 'Activo']);
        $this->r_model->where(['resources.status' => 'Activo']);
        $this->rt_model->where(['status' => 'Activo']);
        $this->mm_model->where(['status' => 'Activo']);
        $this->rp_model->where(['status' => 'Activo']);
    }

    public function index($type)
    {
        // Color();
        $u_model = new User();
        $user = $u_model->find(session('user')->id);
        $session = session();
        $session->set('user', $user);

        $data = (object)[
            'id'        => "",
            'title'     => "",
            'button'    => "",
        ];

        switch ($type) {
            case 'bills':
                $data->id       = 1;
                $data->title    = 'Compras y Gastos';
                $data->button   = 'Añadir compra';
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
            return view('movements/index', [
                'data'  => $data
            ]);

    }

    public function data($type){
        switch ($type) {
            case '3':
                $this->m_model
                    ->join('movement_details as md', 'md.movement_id = movements.id')
                    ->where('md.resource_id', 1)
                    ->groupStart() // ( 
                        ->groupStart()
                            ->where('movements.movement_type_id', 2)
                            ->whereIn('movements.state_id', [2, 3])
                        ->groupEnd()
                        // ->orGroupStart()
                        //     ->where('movements.movement_type_id', 3)
                        //     ->where('movements.state_id', 3)
                        // ->groupEnd()
                    ->groupEnd();

                    
                $this->mt_model->where(['id' => 2]);

                break;
            case '1':
                $this->m_model
                    ->select([
                        'm.resolution as custom_number_bill'
                    ])
                    ->whereIn('movements.movement_type_id', [1, 3])
                    ->whereIn('movements.state_id', [2, 3])
                    ->join('movements as m', 'm.id = movements.movement_reference', 'left');
                $this->mt_model->whereIn('id', [1, 3]);
                
                break;
            
            default:
                $this->m_model
                    ->where([
                        'movement_type_id'  => $type,
                    ]);
                $this->mt_model->where(['id' => $type]);
                break;
        }

        $movement_types = [];

        if($this->dataTable->length != -1){
            // if($type == 3)
            //     $movement_types = $this->mt_model->where(['resource_id' => 1])->findAll();
            // else
            $movement_types = $this->mt_model->findAll();
            $m_model = new Movement();
            foreach ($movement_types as $key => $mt) {
                $mt->states = [];
                foreach ($this->states as $key => $state) {
                    $stateCopy = clone $state;
                    $movements = [];
                    if($type == 3){
                        if(in_array($state->id, [2, 3])){
                            $movements = $m_model
                            ->select([
                                'movements.*'
                            ])
                            ->where([
                                'movement_type_id'  => $mt->id,
                                'state_id'          => $state->id,
                                'md.resource_id'    => 1
                            ])
                            ->join('movement_details as md', 'md.movement_id = movements.id', 'left')
                            ->findAll();
                        }                    
                    }else if($type == 2){
                        // if(in_array($mt->id, [1,2,3]))
                        $movements = $m_model->where([
                            'movement_type_id'  => $mt->id,
                            'state_id'          => $state->id
                        ])->findAll();

                        if($state->id == 1){
                            $m_model = new Movement();
                            $nearest = $m_model
                            ->select("date")
                            ->whereIn('farm_id', $this->farms_ids)
                            ->where([
                                'state_id'          => 1,
                                'movement_type_id'  => 2,
                                'date >='           => date("Y-m-d")
                            ])
                            ->orderBy('date', 'ASC')
                            ->first();

                            $m_model = new Movement();
                            $stateCopy->movements_pend = 
                                $m_model
                                ->where([
                                    'state_id'          => 1,
                                    'movement_type_id'  => 2,
                                    'date'              => $nearest->date
                                ])
                            ->findAll();
                        }

                    }else if($type == 1)
                        $movements = $m_model->where([
                            'movement_type_id'  => $mt->id,
                            'state_id'          => $state->id
                        ])->findAll();

                    if(!empty($movements)){
                        $stateCopy->movements = $movements;
                        $mt->states[] = $stateCopy;
                    }
                }
            }
        }



        $this->m_model->select([
            'movements.*',
            'mt.name as movement_type_name',
            'p.name as provider_name'
        ])
        ->join('movement_types as mt', 'mt.id = movements.movement_type_id', 'left')
        ->join('providers as p', 'p.id = movements.provider_id', 'left')
        ->orderBy('movements.id', 'DESC');

        $count_data = $this->m_model->countAllResults(false);

        $data = $this->dataTable->length == -1 ? $this->m_model->findAll() : $this->m_model->paginate($this->dataTable->length, 'dataTable', $this->dataTable->page);

        return $this->respond([
            'data'              => $data,
            'draw'              => $this->dataTable->draw,
            'recordsTotal'      => $count_data,
            'recordsFiltered'   => $count_data,
            'post'              => $this->dataTable,
            'indicadores'       => $movement_types
        ]);

    }

    public function created($type){
        $movement = [];
        if (strpos($type, "_") !== false) {
            [$type, $movement_id] = explode("_", $type, 2);
            $movement = $this->m_model->find($movement_id);
        } else {
            $movement_id = null; // o 0, o "" según tu necesidad
        }
        $resources          = [];
        $providers          = $this->p_model->findAll();
        $movement_type      = $this->mt_model->find($type);

        // var_dump([$type, $movement_type]); die;

        // $measurement_units  = $this->mm_model->findAll();
        switch ($type) {
            case '1':
                $resources = $this->r_model
                    ->where([
                        'resource_type_id !=' => 1
                    ])
                ->findAll();
                break;
            case '3':
                $resources = $this->r_model
                    ->where([
                        'resource_type_id' => 1
                    ])
                ->findAll();
                break;
            
            default:
                $resources = $this->r_model->findAll();
                break;
        }

        $resources = array_filter($resources, function($resource) {
            return isset($resource->presentations) && !empty($resource->presentations);
        });

        $resources = array_values($resources);

        return view('movements/new', [
            'resources'         => $resources,
            'providers'         => $providers,
            'movement_type'     => $movement_type,
            'movement'          => $movement
            // 'measurement_units' => $measurement_units
        ]);
    }

    public function store(){
        try{
            $data = $this->request->getJson();

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
                'user_id'           => session('user')->id,
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
                        $resource->value = (int) $resource->presentation->presentation * (float) $resource->presentation->presentation_value;
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
                        'resource_id'   => $resource->id,
                        'quantity'      => $resource->quantity,
                        'value'         => $resource->value,
                        'note'          => $resource->note,

                        // 'measurement_unit_id'   => $resource->measurement_unit_id,
                        'presentation_id'          => $resource->presentation->id,
                        'presentation_value'    => $resource->value / (int) $resource->presentation->presentation,
                    ];

                    $total = $total + ($resource->quantity * $resource->value);
                    if($data->movement_type_id == 1 || $data->movement_type_id == 3){
                        $this->rp_model->save([
                            'id'                    => $resource->presentation->id,
                            'presentation_value'    => $resource->value / (int) $resource->presentation->presentation
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
                        return redirect()->to(base_url(['dashboard/movements/bills']));
                        break;
                    case '2':
                        return redirect()->to(base_url(['dashboard/movements/activities']));
                        break;
                    case '3':
                        $this->m_model->save([
                            'id'        => $data->movement_reference,
                            'state_id'  => 3
                        ]);
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
			return $this->respond(['title' => 'Error en el servidor', 'error' => $e->getMessage()], 500);
		}
    }

    public function edit($id_movement){
        $resources          = [];
        $providers          = $this->p_model->findAll();
        $movement           = $this->m_model->find($id_movement);
        switch ($movement->movement_type_id) {
            case '1':
                $resources = $this->r_model
                    ->where([
                        'resource_type_id !=' => 1
                    ])
                ->findAll();
                break;
            case '3':
                $resources = $this->r_model
                    ->where([
                        'resource_type_id' => 1
                    ])
                ->findAll();
                break;
            
            default:
                $resources = $this->r_model->findAll();
                break;
        }

        $resources = array_filter($resources, function($resource) {
            return isset($resource->presentations) && !empty($resource->presentations);
        });

        $resources = array_values($resources);

        // var_dump($movement); die;
        // var_dump($resources); die;

        return view('movements/edit', [
            'resources'         => $resources,
            'providers'         => $providers,
            'movement'          => $movement,
        ]);
    }

    public function updated(){
        try {
            $data = $this->request->getJson();
            $movement = $this->m_model->find($data->id);
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

                if($data->movement_type_id == 2){
                    $resource->value = (int) $resource->presentation->presentation * (float) $resource->presentation_value;
                }

                $value_total = $this->updatedDetail($resource, $value_total, $data->movement_type_id);
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

            switch ($data->movement_type_id) {
                case '1':
                    return redirect()->to(base_url(['dashboard/movements/bills']));
                    break;
                case '2':
                    // return $this->respond([$movement, $data]);
                    return redirect()->to(base_url(['dashboard/movements/activities']));
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

    protected function updatedDetail($resource, $value, $movement_type_id){
        if(!$resource->productNew && $resource->isDelete){
            $this->md_model->delete($resource->movement_detail_id);
        }else if($resource->productNew && !$resource->isDelete){
            $data_resource = [
                'movement_id'   => $data->id,
                'lot_id'        => isset($resource->lot_id) ? $resource->lot_id : null,
                'resource_id'   => $resource->id,
                'quantity'      => $resource->quantity,
                'value'         => $resource->value,
                'note'          => $resource->note,

                'presentation_id'       => $resource->presentation->id,
                'presentation_value'    => $resource->value / (int) $resource->presentation->presentation,
            ];
            
            
            if($movement_type_id == 1){
                $value = $value + ($resource->quantity * $resource->value);
                $this->rp_model->save([
                    'id'                    => $resource->presentation->id,
                    'presentation_value'    => $resource->value / (int) $resource->presentation->presentation
                ]);
            }else if($movement_type_id == 2){
                $value = $value + (((int) $resource->presentation->presentation * $resource->quantity) * $resource->presentation_value);
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
                'presentation_value'    => $resource->value / (int) $resource->presentation->presentation,
            ];

            
            if($movement_type_id == 1){
                $value = $value + ($resource->quantity * $resource->value);
                $this->rp_model->save([
                    'id'                    => $resource->presentation->id,
                    'presentation_value'    => $resource->value / (int) $resource->presentation->presentation
                ]);
            }else if($movement_type_id == 2){
                $value = $value + (((int) $resource->presentation->presentation * $resource->quantity) * $resource->presentation_value);
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
                            $value = $this->updatedDetail($resource, $value, $data->movement_type_id);
                        }
                    }
                    // return $this->respond([$movement, $data]);
                    $this->m_model->save([
                        'id'        => $data->movement_id,
                        'state_id'  => $data->state_id,
                        'value'     => $value
                    ]);
                    return redirect()->to(base_url(['dashboard/movements/activities']));
                    break;
                
                default:
                    # code...
                    break;
            }

        }catch(\Exception $e){
			return $this->respond(['title' => 'Error en el servidor', 'error' => $e->getMessage()], 500);
		}
    }

}
