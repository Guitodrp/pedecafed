<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    public function run()
    {
     $usuarioModel = new \App\Models\UsuarioModel;
     $usuario = [
         'nome' => 'Administrador',
         'email' => 'guito_flash@yahoo.com',
         'cpf' => '124.502.316-09',
         'password'=>'123456',
         'telefone' => '31-3561-0000',
         'is_admin' => true,
         'ativo' => true,
         
     ];
     $usuarioModel->skipValidation(true)->protect(false)->insert($usuario);
   
     }
}
