<?php

namespace App\Database\Seeds;

class CadastroRelatorioSeeder extends DatabaseSeeder
{
    /**
     * Executa o seeder.
     * @return void
     */
    public function run()
    {
        $this->saveOnce('cadastro_relatorio', [
            'usuario_criacao' => '1',
            'nome'            => 'Clientes',
            'agrupamento'     => 'clientes',
            'slug'            => 'Clientes',
        ]);

        $this->saveOnce('usuario_grupo_relatorio', [
            'usuario_criacao'           => '1',
            'codigo_cadastro_grupo'     => '1',
            'codigo_cadastro_relatorio' => '1',
        ]);
    }
}
