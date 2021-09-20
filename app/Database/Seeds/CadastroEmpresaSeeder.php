<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CadastroEmpresaSeeder extends DatabaseSeeder
{
	public function run()
	{
        $this->saveOnce('empresa',[
            'usuario_criacao'          => 1,
            'tipo_pessoa'              => 1,
            'razao_social'             => "SM CORRETORA",
            'nome_fantasia'            => "Sueli imóveis",
            'cpf_cnpj'                 => "09403091908",
            'endereco'                 => json_encode([]),
        ]);

    }
}
