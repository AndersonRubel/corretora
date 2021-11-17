<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CadastroTipoImovelSeeder extends DatabaseSeeder
{
	public function run()
	{
        $this->saveOnce('tipo_imovel',[
            'codigo_empresa' => 1,
            'nome'           => "Casa",
        ]);

        $this->saveOnce('tipo_imovel',[
            'codigo_empresa' => 1,
            'nome'           => "Apartamento",
        ]);

        $this->saveOnce('tipo_imovel',[
            'codigo_empresa' => 1,
            'nome'           => "Terreno",
        ]);
    }
}
