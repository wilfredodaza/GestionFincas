<?php namespace App\Database\Seeds;

use App\Models\User;
use App\Models\Password;

class UserSeeder extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $user = [
            'name'              => 'Administrador',
            'email'             => 'iplanet@iplanetcolombia.com',
            'username'          => 'root',
            // 'password'          => password_hash('123456789', PASSWORD_DEFAULT),
            'status'            => 'active',
            'photo'             => '',
            'role_id'           => 1
        ];
        $u_model = new User();
        $u_model->save($user);

        $user_id = $u_model->insertID();
        $p_model = new Password();
        $p_model->save([
            'user_id'   => $user_id,
            'password'  => password_hash('123456789', PASSWORD_DEFAULT)
        ]);
        
    }
}