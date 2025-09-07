<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use PhpOffice\PhpSpreadsheet\IOFactory;
use CodeIgniter\API\ResponseTrait;



class LoadsController extends BaseController
{
    use ResponseTrait;

    public function env(){
        try {
            // Usar cURL para obtener los commits desde la API de GitHub (con autenticación si es necesario)
            $ch = curl_init();

            // Configuración de cURL para obtener los commits
            curl_setopt($ch, CURLOPT_URL, "https://api.github.com/repos/wilfredodaza/GestionFincas/commits");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0");  // GitHub requiere un User-Agent
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Accept: application/vnd.github.v3+json',
                // Si es necesario, puedes incluir un token de autenticación aquí
                // 'Authorization: token YOUR_GITHUB_TOKEN'
            ]);

            $response = curl_exec($ch);
            curl_close($ch);

            // Decodificar la respuesta JSON de GitHub
            $commits = json_decode($response);

            // Crear un objeto con el último commit
            $commit = $commits[0]->sha;

            // Retornar la respuesta
            // return $this->respond($commit);

            $envFile = env('DEPLOYPATH_ENV', APPPATH . '../.env') ;
            $envContent = file_get_contents($envFile);
            $key = 'GIT_COMMIT_HASH';
            $pattern = "/^" . preg_quote($key, '/') . "=.*/m";

            if (preg_match($pattern, $envContent)) {
                // Si la clave existe, la reemplazamos
                $envContent = preg_replace($pattern, "{$key}={$commit}", $envContent);
            } else {
                // Si no existe, la agregamos al final
                $envContent .="\n{$key}={$commit}";
            }
            file_put_contents($envFile, $envContent);
            log_message('info', "Env: ".$envContent);
            return $this->respond([$commit]);
        } catch (\Exception $th) {
            echo $envFile;
            die('Error: ' . $th->getMessage());
        }
    }

}
