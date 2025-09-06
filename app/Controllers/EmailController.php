<?php


namespace App\Controllers;

use Config\Services;

class EmailController extends BaseController
{
    protected $email;

    public function __construct()
    {
        $this->email = Services::email();
    }

    public function send($from, $name, $to, $subject, $message)
    {
        $email = Services::email();
        $email->setFrom(!empty(configInfo()['email']) ? configInfo()['email'] : 'iplanet@iplanetcolombia.com', !empty(configInfo()['name_app']) ? configInfo()['name_app'] : 'IPlanet Colombia S.A.S');
        $email->setTo($to);
        $email->setSubject($subject);
        $email->setMessage($message);
        if ($email->send()) 
		{
            return (object)[
                'status'    => true,
                'message'   => 'Valida el correo te enviamos una nueva contraseña'
            ];
        } 
		else 
		{
            $data = $email->printDebugger(['headers']);
            return (object)[
                'status'    => false,
                'message'   => 'Error al enviar el correo'
            ];
            print_r($data);
        }

    }
}