<?php

namespace App\Database\Seeds;

class EmpresaUsuarioSeeder extends DatabaseSeeder
{
    /**
     * Executa o seeder.
     * @return void
     */
    public function run()
    {
        $this->saveOnce('empresa_usuario', [
            'codigo_empresa'        => 1,
            'codigo_usuario'        => 1,
            'codigo_cadastro_grupo' => 1
        ]);
        $this->saveOnce('empresa_usuario', [
            'codigo_empresa'        => 1,
            'codigo_usuario'        => 2,
            'codigo_cadastro_grupo' => 2
        ]);
    }
}
