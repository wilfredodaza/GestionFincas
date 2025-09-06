<?php


namespace App\Controllers;


use App\Models\User;
use Config\Services;
use App\Models\Plantilla;
use App\Models\Password;
use CodeIgniter\API\ResponseTrait;


class AuthController extends BaseController
{
    use ResponseTrait;
    
    public function login()
    {
        $session = session();
        $operations = ['mas', 'menos'];
        $number_a = (int) rand(1, 10);
        $number_b = (int) rand(1, 10);

        // Asegurarse de que number_a sea mayor que number_b
        if ($number_a < $number_b) {
            $temp = $number_a;
            $number_a = $number_b;
            $number_b = $temp;
        }

        $session->set('captcha', (object)[
            'number_a'  => $number_a,
            'number_b'  => $number_b,
            'operacion' => $operations[array_rand($operations)]
        ]);

        return view('auth/login');
    }

    public function validation()
    {
        $data = $this->request->getJson();
        // return $this->respond($data);
        $username = $data->email_username;
        $password = $data->password;
        $captcha = $data->captcha;
        $validationCaptcha = ValidateReCaptcha($captcha);
        if($validationCaptcha){
            $user = new User();
            $data = $user
                ->select(['users.*'])
                ->where('username', $username)
                ->orWhere('email', $username)->first();
            if ($data) {
                if ($data->status == 'active') {
                    // $data->password = $user->getPassword($data->id);
                    if((int) $data->password->attempts < 5){
                        if (password_verify($password, $data->password->password)) {
                            $session = session();
                            $session->set('user', $data);
                            // return $this->respond([
                            //     'status'    => '200',
                            //     'title'     => 'Validación de éxitosa',
                            //     'message'   => "Redirigiendo página",
                            //     'url'       => base_url(['dashboard'])
                            // ]);
                            return redirect()->to(base_url(['dashboard']));
                        } else {
                            $p_model = new Password();
                            $p_model->save([
                                'id'        => $data->password->id,
                                'attempts'  => (int) $data->password->attempts + 1
                            ]);
                            return $this->respond([
                                'status'    => '403',
                                'title'     => 'Validación de usuario',
                                'message'   => "Las credenciales no concuerdan. Numeros de intentos restantes <b>".(4 - $data->password->attempts)."</b>"
                            ]);
                            return redirect()->to(base_url(['login']))->with('errors', "Las credenciales no concuerdan. Numeros de intentos restantes <b>".(4 - $data->password->attempts)."</b>");
                        }
                    }else{
                        return $this->respond([
                            'status'    => '403',
                            'title'     => 'Validación de usuario',
                            'message'   => 'Limite de intentos superados.'
                        ]);
                        // return redirect()->to(base_url(['login']))->with('errors', 'Limite de intentos superados.');
                    }
                } else {
                    return $this->respond([
                        'status'    => '403',
                        'title'     => 'Validación de usuario',
                        'message'   => 'La cuenta no se encuentra activa.'
                    ]);
                    // return redirect()->to(base_url(['login']))->with('errors', 'La cuenta no se encuentra activa.');
                }
            } else {
                return $this->respond([
                    'status'    => '403',
                    'title'     => 'Validación de usuario',
                    'message'   => 'Las credenciales no concuerdan 1.'
                ]);
                // return redirect()->to(base_url(['login']))->with('errors', 'Las credenciales no concuerdan.');
            }
        }else {
            return $this->respond([
                'status'    => '403',
                'title'     => 'Validación de usuario',
                'message'   => 'Error al validar el Captcha'
            ]);
            // return redirect()->to(base_url(['login']))->with('errors', 'Error al validar el Captcha');
        }
    }

    public function register()
    {
        $validation = Services::validation();
        return view('auth/register', ['validation' => $validation]);
    }

    public function create()
    {
        $validation = Services::validation();
        if ($this->validate([
            'name'              => 'required|max_length[45]',
            'username'          => 'required|is_unique[users.username]|max_length[40]',
            'email'             => 'required|valid_email|is_unique[users.email]|max_length[100]',
            'password'          => 'required|min_length[6]',
            'password_confirm'  => 'required|matches[password]',
        ], [
            'name' => [
                'required' => 'El campo Nombres y Apellidos es obrigatorio.',
                'max_length' => 'El campo Nombres Y Apellidos no debe tener mas de 45 caracteres.'
            ],
            'username' => [
                'required' => 'El campo Nombre de Usuario es obligatorio',
                'is_unique' => 'Lo sentimos. El nombre de usuario ya se encuentra registrado.',
                'max_length' => 'El campo Nombre de Usuario no puede superar mas de 20 caracteres.'
            ],
            'email' => [
                'required' => 'El campo Correo Electronico es obrigatorio.',
                'is_unique' => 'Lo sentimos. El correo ya se encuentra registrado.'
            ],
            'password' => [
                'required' => 'El campo Contraseña es obligatorio.',
                'min_length' => 'El campo Contraseña debe tener minimo 6 caracteres.'
            ],
            'password_confirm' => [
                'required'      => 'La confirmacion de la contraseña es obligatoria.',
                'matches'       => 'Las contraseñas no coinciden.'
            ]

        ])) {
            $info = $this->request->getJson();
            $data = [
                'name' => $info->name,
                'username' => $info->username,
                'email' => $info->email,
                'status' => 'inactive',
                'role_id' => 3
            ];

            $u_model = new User();
            $u_model->save($data);

            $user_id = $u_model->insertID();
            $p_model = new Password();
            $p_model->save([
                'user_id'   => $user_id,
                'password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT)
            ]);
            return $this->respond([
                'status'    => '200',
                'title'     => 'Creación de éxitosa',
                'message'   => "Esperando a activar la cuenta."
            ]);
        } else {
            $errors = implode("<br>", $validation->getErrors());
            return $this->respond([
                'status'    => '403',
                'title'     => 'Error en los datos de creación.',
                'message'   => $errors
            ]);
        }


    }

    public function resetPassword()
    {
        return view('auth\reset_password');
    }

    public function forgotPassword()
    {
        $request = Services::request();
        $user = new User();
        $info = $this->request->getJson();
        $data = $user->where('email', $info->email)->first();
        if (!empty($data) > 0) {
            $email = new EmailController();
            $password = $this->encript();
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $response = $email->send('wabox324@gmail.com', 'wabox', $request->getPost('email'), 'Recuperacion de contraseña', password($password));
            if($response->status){
                $p_model = new Password();
                if($p_model->set(['status' => 'inactive'])->where(['user_id' => $data->id, 'status' => 'active'])->update()){
                    if($p_model->save(['user_id' => $data->id, 'password' => $password_hash, 'temporary' => 'Si'])){
                        return $this->respond([
                            'status'    => '200',
                            'title'     => 'Contraseña actualizada con éxito',
                            'message'   => $response->message
                        ]);
                    }else
                        return $this->respond([
                            'status'    => '403',
                            'title'     => 'Error al recuperar la contraseña',
                            'message'   => 'Error al actualizar la contraseña.'
                        ]);
                }else{
                    return $this->respond([
                        'status'    => '403',
                        'title'     => 'Error al recuperar la contraseña',
                        'message'   => 'Error al actualizar la contraseña.'
                    ]);
                }
            }else{
                return $this->respond([
                    'status'    => '403',
                    'title'     => 'Error al recuperar la contraseña',
                    'message'   => $response->message
                ]);
            }
        } else {
            return $this->respond([
                'status'    => '403',
                'title'     => 'Error al recuperar la contraseña',
                'message'   => 'No se encontró el correo electrónico.'
            ]);
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url(['login']));
    }

    public function encript($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    protected function validatePassword(){

    }
}
