<?php

namespace App\Database\Seeds;

class PermissaoSeeder extends DatabaseSeeder
{
    /**
     * Executa o seeder.
     * @return void
     */
    public function run()
    {
        // Grupo Administrador
        $this->saveOnce('usuario_grupo_menu', ['codigo_cadastro_grupo' => 2, 'codigo_cadastro_menu'  => 1]);
        $this->saveOnce('usuario_grupo_menu', ['codigo_cadastro_grupo' => 2, 'codigo_cadastro_menu'  => 2]);
        $this->saveOnce('usuario_grupo_menu', ['codigo_cadastro_grupo' => 2, 'codigo_cadastro_menu'  => 3]);
        $this->saveOnce('usuario_grupo_menu', ['codigo_cadastro_grupo' => 2, 'codigo_cadastro_menu'  => 5]);
        $this->saveOnce('usuario_grupo_menu', ['codigo_cadastro_grupo' => 2, 'codigo_cadastro_menu'  => 6]);
        $this->saveOnce('usuario_grupo_menu', ['codigo_cadastro_grupo' => 2, 'codigo_cadastro_menu'  => 7]);
        $this->saveOnce('usuario_grupo_menu', ['codigo_cadastro_grupo' => 2, 'codigo_cadastro_menu'  => 8]);
        $this->saveOnce('usuario_grupo_menu', ['codigo_cadastro_grupo' => 2, 'codigo_cadastro_menu'  => 9]);
        $this->saveOnce('usuario_grupo_menu', ['codigo_cadastro_grupo' => 2, 'codigo_cadastro_menu'  => 10]);
        $this->saveOnce('usuario_grupo_menu', ['codigo_cadastro_grupo' => 2, 'codigo_cadastro_menu'  => 11]);
        $this->saveOnce('usuario_grupo_menu', ['codigo_cadastro_grupo' => 2, 'codigo_cadastro_menu'  => 12]);
    }
}
