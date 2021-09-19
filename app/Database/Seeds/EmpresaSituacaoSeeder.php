<?php

namespace App\Database\Seeds;

class EmpresaSituacaoSeeder extends DatabaseSeeder
{
    /**
     * Executa o seeder.
     * @return void
     */
    public function run()
    {
        $this->saveOnce('empresa_situacao', [
            'nome' => 'Ativa',
        ]);

        $this->saveOnce('empresa_situacao', [
            'nome' => 'Inativa',
        ]);

        $this->saveOnce('empresa_situacao', [
            'nome' => 'Congelada',
        ]);
    }
}
