<?php


namespace App\Traits;

use GroceryCrud\Core\GroceryCrud;

include(APPPATH . 'Libraries/GroceryCrudEnterprise/autoload.php');

Trait Grocery
{
    private function database()
    {
        $db = (new \Config\Database())->default;
        return [
            'adapter' => [
                'driver' => 'Pdo_Mysql',
                'host' => $db['hostname'],
                'database' => $db['database'],
                'username' => $db['username'],
                'password' => $db['password'],
                'charset' => 'utf8'
            ]
        ];
    }

    private function _getGroceryCrudEnterprise($bootstrap = true, $jquery = true)
    {
        $db = $this->database();
        $config = (new \Config\GroceryCrudEnterprise())->getDefaultConfig();

        $groceryCrud = new GroceryCrud($config, $db);
        return $groceryCrud;
    }

    public function viewTable($output, $title, $subtitle, $page = 'pages/table')
    {
        $output->title      = $title;
        $output->subtitle   = $subtitle;
        echo  view($page, (array)$output);
    }

    public function  viewController($controller)
    {
        redirect()->to($controller);
    }

}