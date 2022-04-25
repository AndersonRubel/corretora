<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CadastroCategoriaImovelSeeder extends DatabaseSeeder
{
	public function run()
	{
        $this->saveOnce('categoria_imovel',[
            'codigo_empresa' => 1,
            'nome'           => "Aluguel",
        ]);

        $this->saveOnce('categoria_imovel',[
            'codigo_empresa' => 1,
            'nome'           => "Venda",
        ]);
        $this->saveOnce('categoria_imovel', [
            'codigo_empresa' => 1,
            'nome'           => "Venda/Aluguel",
        ]);


    }
}
