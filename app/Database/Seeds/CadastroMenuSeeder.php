<?php

namespace App\Database\Seeds;

class CadastroMenuSeeder extends DatabaseSeeder
{
    /**
     * Executa o seeder.
     * @return void
     */
    public function run()
    {
        $this->saveOnce('cadastro_menu', [
            'nome'        => 'Dashboard',
            'descricao'   => 'Dashboard do Sistema',
            'path'        => 'dashboard',
            'agrupamento' => 'sidebar',
            'ordenacao'   => '1',
            'icone'       => 'fas fa-home'
        ]);

        $this->saveOnce('cadastro_menu', [
            'nome'        => 'Cadastro',
            'descricao'   => 'Cadastros do Sistema',
            'path'        => '#',
            'agrupamento' => 'cadastro',
            'ordenacao'   => '2',
            'icone'       => 'fas fa-edit'
        ]);

        /////// Inicio :: Menus de Gestão ///////
        $this->saveOnce('cadastro_menu', [
            'nome'        => 'Gestão',
            'descricao'   => 'Opções de Gestão',
            'path'        => '#',
            'agrupamento' => 'gestao',
            'icone'       => 'fas fa-sitemap'
        ]);

        $this->saveOnce('cadastro_menu', [
            'nome'        => 'Empresa',
            'descricao'   => 'Gestão de Empresas',
            'path'        => 'empresa',
            'agrupamento' => 'gestao',
            'localizacao' => 'sidebar_gestao',
            'icone'       => 'fas fa-building'
        ]);

        $this->saveOnce('cadastro_menu', [
            'nome'        => 'Usuários',
            'descricao'   => 'Gerenciamentos dos Usuários',
            'path'        => 'usuario',
            'agrupamento' => 'gestao',
            'localizacao' => 'sidebar_gestao',
            'icone'       => 'fas fa-user'
        ]);

        $this->saveOnce('cadastro_menu', [
            'nome'        => 'Clientes',
            'descricao'   => 'Gerenciamento dos Clientes',
            'path'        => 'cliente',
            'agrupamento' => 'gestao',
            'localizacao' => 'sidebar_gestao',
            'icone'       => 'fas fa-user'
        ]);

        /////// Inicio :: Menus de Cadastro ///////
        $this->saveOnce('cadastro_menu', [
            'nome'        => 'Configurações',
            'descricao'   => 'Cadastro de Configurações',
            'path'        => 'cadastro/configuracao',
            'agrupamento' => 'cadastro',
            'localizacao' => 'cadastro',
            'icone'       => 'fas fa-edit'
        ]);

        $this->saveOnce('cadastro_menu', [
            'nome'        => 'Grupos',
            'descricao'   => 'Cadastro de Grupos',
            'path'        => 'cadastro/grupo',
            'agrupamento' => 'cadastro',
            'localizacao' => 'cadastro',
            'icone'       => 'fas fa-edit'
        ]);
    }
}
