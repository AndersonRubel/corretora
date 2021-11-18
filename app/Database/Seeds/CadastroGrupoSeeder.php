<?php

namespace App\Database\Seeds;

class CadastroGrupoSeeder extends DatabaseSeeder
{
    /**
     * Executa o seeder.
     * @return void
     */
    public function run()
    {
        $this->saveOnce('cadastro_grupo', [
            'codigo_empresa' => 1,
            'nome' => 'Super Administrador',
            'slug' => 'superadmin',
        ]);


        $this->saveOnce('cadastro_grupo', [
            'codigo_empresa' => 1,
            'nome' => 'Administrador',
            'slug' => 'admin',
        ]);
    }
}
