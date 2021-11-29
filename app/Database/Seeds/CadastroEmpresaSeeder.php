<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CadastroEmpresaSeeder extends DatabaseSeeder
{
	public function run()
	{
        $this->saveOnce('empresa', [
            'razao_social'             => "SM CORRETORA",
            'nome_fantasia'            => "Sueli imóveis",
            'cnpj'                     => "09403091908",
            'endereco'                 => json_encode([
                "uf" => "PR",
                "cep" => "84010150",
                "rua" => "Rua do Rosário",
                "bairro" => "Centro",
                "cidade" => "Ponta Grossa",
                "numero" => "21551",
                "complemento" => ""
            ]),
        ]);

    }
}
