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
            'descricao'   => 'Gerenciamento dos Usuários',
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
        $this->saveOnce('cadastro_menu', [
            'nome'        => 'Proprietários',
            'descricao'   => 'Gerenciamento dos Proprietários',
            'path'        => 'proprietario',
            'agrupamento' => 'gestao',
            'localizacao' => 'sidebar_gestao',
            'icone'       => 'fas fa-user'
        ]);
        /////// Inicio :: Menus Unicos ///////
        $this->saveOnce('cadastro_menu', [
            'nome'        => 'Gerenciar Imóvel',
            'descricao'   => 'Gerenciamento dos Imóveis',
            'path'        => 'imovel',
            'icone'       => 'fas fa-box'
        ]);

        $this->saveOnce('cadastro_menu', [
            'nome'        => 'Gerenciar Reserva',
            'descricao'   => 'Gerenciamento dos Reservas',
            'path'        => 'reserva',
            'icone'       => 'fas fa-box'
        ]);

        /////// Inicio :: Menus de Cadastro ///////
        $this->saveOnce('cadastro_menu', [
            'nome'        => 'Tipo Imóvel',
            'descricao'   => 'Cadastro de Tipo de Imóvel',
            'path'        => 'cadastro/tipoImovel',
            'agrupamento' => 'cadastro',
            'localizacao' => 'cadastro',
            'icone'       => 'fas fa-edit',
            'ordenacao'   => '1'
        ]);

        $this->saveOnce('cadastro_menu', [
            'nome'        => 'Categoria Imóvel',
            'descricao'   => 'Cadastro de Categoria de Imóvel',
            'path'        => 'cadastro/categoriaImovel',
            'agrupamento' => 'cadastro',
            'localizacao' => 'cadastro',
            'icone'       => 'fas fa-edit',
            'ordenacao'   => '2'
        ]);

        $this->saveOnce('cadastro_menu', [
            'nome'        => 'Grupos',
            'descricao'   => 'Cadastro de Grupos',
            'path'        => 'cadastro/grupo',
            'agrupamento' => 'cadastro',
            'localizacao' => 'cadastro',
            'icone'       => 'fas fa-edit'
        ]);

        $this->saveOnce('cadastro_menu', [
            'nome'        => 'Menus',
            'descricao'   => 'Cadastro de Menus de Acesso',
            'path'        => 'cadastro/menu',
            'agrupamento' => 'cadastro',
            'localizacao' => 'cadastro',
            'icone'       => 'fas fa-edit'
        ]);
    }
}
