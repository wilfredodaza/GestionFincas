<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;

use App\Models\Movement;
use App\Models\MovementType;
use App\Models\State;
use SebastianBergmann\Environment\Console;

class DashboardController extends BaseController
{
    use ResponseTrait;

	protected $m_model;
	protected $s_model;
	protected $mt_model;

	public function __construct(){
		$this->m_model = new Movement();
		$this->s_model = new State();
		$this->mt_model = new MovementType();
	}

	private function kpiBaseQuery(int $roleId, array $farmIds)
	{
		$m = new \App\Models\Movement(); //

		if ($roleId !== 1) {
			if (empty($farmIds)) {
				// devolvemos el model con where imposible para que no traiga nada
				$m->where('1 = 0');
				return $m;
			}
			$m->whereIn('farm_id', $farmIds);
		}

		return $m;
	}


	public function index()
	{

		$fechaEspecifica = new \DateTime(session('user')->password->created_at);
		$fechaActual = new \DateTime('now');

		$userId = (int) session('user')->id;
		$roleId = (int) session('user')->role_id;

		$db = $this->m_model->db; // o $this->r_model->db
		if($roleId === 1){
			$farmIds = [];
		}else{
			$farmIds = array_merge(
				array_map(
					fn($f) => (int)$f->id,
					$db->table('farms')
					->select('id')
					->where('user_id', $userId)
					->get()
					->getResult()
				),
				array_map(
					fn($f) => (int)$f->farm_id,
					$db->table('farms_users')->select('farm_id')->where([
						'user_id' => $userId,
						'status'  => 'Activo'
					])->get()->getResult()
				)
			);
		}
		
		$farmIds = array_values(array_unique($farmIds));

		$diferencia = $fechaEspecifica->diff($fechaActual);

		$kpis = [
			(object) ["name" => "Compras", "state_id" => 3, "movement_type_id" => 1, "total" => 0, "total_month" => 0, "total_week" => 0],
			(object) ["name" => "Jornales", "state_id" => 3, "movement_type_id" => 3, "total" => 0, "total_month" => 0, "total_week" => 0],
		];

		if (empty($farmIds) && $roleId !== 1) {
			foreach ($kpis as $kpi) {
				$kpi->total = (object)['total' => 0];
				$kpi->total_month = (object)['total' => 0];
				$kpi->total_week = (object)['total' => 0];
			}
		} else {
			foreach ($kpis as $kpi) {

				$dateInitWeek = date('Y-m-d', strtotime('monday this week'));
				$dateEndWeek  = date('Y-m-d', strtotime('sunday this week'));

				$kpi->total = $this->kpiBaseQuery($roleId,$farmIds)
					->select('SUM(value) as total')
					//->whereIn('farm_id', $farmIds)
					->where([
						'state_id' => $kpi->state_id,
						'movement_type_id' => $kpi->movement_type_id
					])->first();

				$kpi->total_month = $this->kpiBaseQuery($roleId,$farmIds)
					->select('SUM(value) as total')
					//->whereIn('farm_id', $farmIds)
					->where([
						'state_id' => $kpi->state_id,
						'movement_type_id' => $kpi->movement_type_id,
					])
					->where('MONTH(date)' ,$fechaActual->format('m'),false)
					->where('YEAR(date)',  $fechaActual->format('Y'), false)
					->first();

				$kpi->total_week = $this->kpiBaseQuery($roleId,$farmIds)
					->select('SUM(value) as total')
					//->whereIn('farm_id', $farmIds)
					->where([
						'state_id' => $kpi->state_id,
						'movement_type_id' => $kpi->movement_type_id,
						'date >=' => "$dateInitWeek 00:00:00",
						'date <=' => "$dateEndWeek 23:59:59",
					])->first();
			}
		}

		$movement_types = $this->mt_model->findAll();
		$states = $this->s_model->findAll();

	  	return  view('pages/home', [
			'day' 				=> (90 - $diferencia->days),
			'kpis' 				=> $kpis,
			'movement_types'	=> $movement_types,
			'states'			=> $states
		]);
	}

	public function calendar()
	{
		try {
			$data   = $this->request->getJson();
			$userId = (int) session('user')->id;
			$roleId = (int) session('user')->role_id;

			$db = $this->m_model->db;

			// 1) Fincas propias + asignadas
			if($roleId === 1){
				$farmIds = [];
				
			}else{
				$farmIds = array_merge(
					array_map(
						fn($f) => (int)$f->id,
						$db->table('farms')
							->select('id')
							->where('user_id', $userId)
							->get()->getResult()
					),
					array_map(
						fn($f) => (int)$f->farm_id,
						$db->table('farms_users')
							->select('farm_id')
							->where([
								'user_id' => $userId,
								'status'  => 'Activo'
							])
							->get()->getResult()
					)
				);
			}

			$farmIds = array_values(array_unique($farmIds));
			
			// 2) Si no tiene fincas, no tiene eventos
			

			// 3) Movimientos SOLO de sus fincas y rango de fechas
			$movements = $this->m_model;
				if($roleId !== 1){
					if (empty($farmIds)) {
						return $this->respond(['movements' => []]);
					}
					$movements->whereIn('farm_id', $farmIds);
				}
				$row = $movements
				->where('date >=', "{$data->start} 00:00:00")
				->where('date <=', "{$data->end} 23:59:59")
				->findAll();

			return $this->respond([
				'movements' => $row
			]);

		} catch (\Exception $e) {
			return $this->respond([
				'title' => 'Error en el servidor',
				'error' => $e->getMessage()
			], 500);
		}
	}


	public function about()
  {
    return view('pages/about');
  }

}
