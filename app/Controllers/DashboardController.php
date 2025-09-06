<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;

use App\Models\Movement;
use App\Models\MovementType;
use App\Models\State;

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

	public function index()
	{

		$fechaEspecifica = new \DateTime(session('user')->password->created_at);
		$fechaActual = new \DateTime('now');
		$diferencia = $fechaEspecifica->diff($fechaActual);

		$kpis = [
			(object) ["name" => "Compras", "state_id" => 3, "movement_type_id" => 1, "total" => 0, "total_month" => 0, "total_week" => 0],
			(object) ["name" => "Jornales", "state_id" => 3, "movement_type_id" => 3, "total" => 0, "total_month" => 0, "total_week" => 0],
		];

		foreach ($kpis as $key => $kpi) {

			$dateInitWeek = date('Y-m-d', strtotime('monday this week'));
			$dateEndWeek    = date('Y-m-d', strtotime('sunday this week'));

			$kpi->total = $this->m_model
			->select('SUM(value) as total')
			->where([
				'state_id' => $kpi->state_id,
				'movement_type_id' => $kpi->movement_type_id
			])->first();
			
			$kpi->total_month = $this->m_model
			->select('SUM(value) as total')
			->where([
				'state_id' => $kpi->state_id,
				'movement_type_id' => $kpi->movement_type_id,
				'MONTH(date)' => $fechaActual->format('m'),
				'YEAR(date)' => $fechaActual->format('Y'),
			])->first();

			$kpi->total_week = $this->m_model
			->select('SUM(value) as total')
			->where([
				'state_id' => $kpi->state_id,
				'movement_type_id' => $kpi->movement_type_id,
				'date >= ' => "$dateInitWeek 00:00:00",
				'date <= ' => "$dateEndWeek 23:59:59",
			])->first();
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

	public function calendar(){
		try{
			$data = $this->request->getJson();

			$movements = $this->m_model
				->where([
					'date >= ' => "$data->start 00:00:00",
					'date <= ' => "$data->end 23:59:59",
				])->findAll();
			
			return $this->respond([
				'movements'	=> $movements
			]);

		}catch(\Exception $e){
			return $this->respond(['title' => 'Error en el servidor', 'error' => $e->getMessage()], 500);
		}
	}

	public function about()
  {
    return view('pages/about');
  }

}
