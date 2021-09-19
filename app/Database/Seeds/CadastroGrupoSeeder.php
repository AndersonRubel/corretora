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
            'nome' => 'Super Administrador',
            'slug' => 'superadmin',
        ]);

        $this->saveOnce('cadastro_grupo', [
            'nome' => 'Administrador',
            'slug' => 'admin',
        ]);

        $this->saveOnce('cadastro_grupo', [
            'nome' => 'Vendedor',
            'slug' => 'vendedor',
        ]);

        $this->saveOnce('cadastro_grupo', [
            'nome' => 'Gerente',
            'slug' => 'gerente',
        ]);
    }
}
