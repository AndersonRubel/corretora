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
        $this->saveOnce('vendedor', [
            'usuario_criacao' => 1,
            'codigo_empresa'  => 2,
            'nome_fantasia'   => 'Elhane Semijóias',
            'razao_social'    => 'ELHANE INEZ MOREIRA MIOLA',
            'cpf_cnpj'        => '64037606968',
            'tipo_pessoa'     => 1,
        ]);
        $this->saveOnce('vendedor', [
            'usuario_criacao' => 1,
            'codigo_empresa'  => 3,
            'nome_fantasia'   => 'TESTES',
            'razao_social'    => 'CLIENTE ILUMINARE DE TESTES',
            'cpf_cnpj'        => '28559349000187',
            'tipo_pessoa'     => 2,
        ]);
    }
}
