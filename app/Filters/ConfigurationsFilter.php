<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\Configuration;

class ConfigurationsFilter implements FilterInterface
{

    public function before(RequestInterface $request, $arguments = null)
    {
      $c_model = new Configuration();
      $config = $c_model->first();
      if(empty($config)){
        return redirect()->to(base_url(['login']));
      }else if($config->register == 'inactive'){
        return redirect()->to(base_url(['login']));
      }

      


    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {

    }
}