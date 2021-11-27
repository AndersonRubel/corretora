<?php

namespace App\Database\Seeds;

class UsuarioSeeder extends DatabaseSeeder
{
    /**
     * Executa o seeder.
     * @return void
     */
    public function run()
    {
        $this->saveOnce('usuario', [
            'nome'                  => '1#_Implantação corretora',
            'email'                 => 'implantacao@corretora.com.br',
            'senha'                 => password_hash('corretora', PASSWORD_BCRYPT),
            'codigo_empresa' => 1,
        ]);
    }
}
