<?php

namespace App\Database\Seeds;

class CadastroFluxoTipoSeeder extends DatabaseSeeder
{
    /**
     * Executa o seeder.
     * @return void
     */
    public function run()
    {
        $this->saveOnce('cadastro_fluxo_tipo', [
            'nome' => 'Receita',
        ]);

        $this->saveOnce('cadastro_fluxo_tipo', [
            'nome' => 'Despesa',
        ]);
    }
}
