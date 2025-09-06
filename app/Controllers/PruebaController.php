<?php

namespace App\Controllers;
use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;

use App\Traits\Grocery;

class PruebaController extends BaseController
{
    use Grocery;

    private $crud;

    use ResponseTrait;

    public function __construct()
    {
        $this->crud = $this->_getGroceryCrudEnterprise();
        $this->crud->setSkin('bootstrap-v3');
        $this->crud->setLanguage('Spanish');
    }

    public function chat(){
        return view('landings/chat');
    }

    public function datatable($type){
        switch ($type) {
            case 'extends':
                return view('landings/datatable-extends');
                break;

            case 'advanced':
                return view('landings/datatable-advanced');
                break;
            
            default:
                return view('landings/datatable');
                break;
        }
    }

    public function table(){
        
        $this->crud->setTable('users');
        $this->crud->setActionButton('Avatar', 'fa fa-bars', function ($row) {
            return base_url(['table', 'info_creditos']);
        }, false);
        $output = $this->crud->render();
        if (isset($output->isJSONResponse) && $output->isJSONResponse) {
            header('Content-Type: application/json; charset=utf-8');
            echo $output->output;
            exit;
        }

        $this->viewTable($output,'Prueba titulo', 'Prueba description', 'landings/grocery');
    }
}
