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
            'nome'                  => '1#_Implantação Iluminare',
            'email'                 => 'implantacao@iluminareweb.com.br',
            'senha'                 => password_hash('iluminareweb', PASSWORD_BCRYPT),
            'codigo_empresa_padrao' => 3,
            'codigo_vendedor'       => 3,
        ]);

        // $this->saveOnce('usuario', [
        //     'nome'                  => 'Suporte Iluminare',
        //     'email'                 => 'suporte@iluminareweb.com.br',
        //     'senha'                 => password_hash('iluminareweb', PASSWORD_BCRYPT),
        //     'codigo_empresa_padrao' => 3,
        // ]);
    }
}
