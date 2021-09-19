<?php

namespace App\Database\Seeds;

class VendedorSeeder extends DatabaseSeeder
{
    /**
     * Executa o seeder.
     * @return void
     */
    public function run()
    {
        $this->saveOnce('vendedor', [
            'usuario_criacao' => 1,
            'codigo_empresa'  => 1,
            'nome_fantasia'   => 'VERA FAGA SEMIJOIAS',
            'razao_social'    => 'VERA LUCIA FUSSIGER FAGA 51863936904',
            'cpf_cnpj'        => '27778972000168',
            'tipo_pessoa'     => 2,
        ]);
    }
}
