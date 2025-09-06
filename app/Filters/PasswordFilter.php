<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\Configuration;

class PasswordFilter implements FilterInterface
{

    public function before(RequestInterface $request, $arguments = null)
    {

      $fechaEspecifica = new \DateTime(session('user')->password->created_at);
      $fechaActual = new \DateTime('now');
      $diferencia = $fechaEspecifica->diff($fechaActual);
      if($diferencia->days > 90){
        return redirect()->to(base_url(['password']));
      }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {

    }
}