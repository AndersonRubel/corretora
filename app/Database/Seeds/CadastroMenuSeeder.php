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
            'nome'        => 'Fornecedores',
            'descricao'   => 'Gerenciamento dos Fornecedores',
            'path'        => 'fornecedor',
            'agrupamento' => 'gestao',
            'localizacao' => 'sidebar_gestao',
            'icone'       => 'fas fa-user'
        ]);

        $this->saveOnce('cadastro_menu', [
            'nome'        => 'Vendedores',
            'descricao'   => 'Gerenciamento dos Vendedores',
            'path'        => 'vendedor',
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

        /////// Inicio :: Menus de Estoque ///////
        $this->saveOnce('cadastro_menu', [
            'nome'        => 'Estoque',
            'descricao'   => 'Opções de Estoque',
            'path'        => '#',
            'agrupamento' => 'estoque',
            'icone'       => 'fas fa-warehouse'
        ]);

        $this->saveOnce('cadastro_menu', [
            'nome'        => 'Gerenciar Estoque',
            'descricao'   => 'Gerenciamento do Estoque',
            'path'        => 'estoque',
            'agrupamento' => 'estoque',
            'localizacao' => 'sidebar_estoque',
            'icone'       => 'fas fa-warehouse'
        ]);

        $this->saveOnce('cadastro_menu', [
            'nome'        => 'Gerenciar Produtos',
            'descricao'   => 'Gerenciamento dos Produtos',
            'path'        => 'produto',
            'agrupamento' => 'estoque',
            'localizacao' => 'sidebar_estoque',
            'icone'       => 'fas fa-box'
        ]);

        $this->saveOnce('cadastro_menu', [
            'nome'        => 'Histórico',
            'descricao'   => 'Histórico do Estoque',
            'path'        => 'estoque/historico',
            'agrupamento' => 'estoque',
            'localizacao' => 'sidebar_estoque',
            'icone'       => 'fas fa-history'
        ]);

        $this->saveOnce('cadastro_menu', [
            'nome'        => 'Ver Baixas',
            'descricao'   => 'Baixas no Estoque',
            'path'        => 'estoque/baixas',
            'agrupamento' => 'estoque',
            'localizacao' => 'sidebar_estoque',
            'icone'       => 'fas fa-level-down-alt'
        ]);

        $this->saveOnce('cadastro_menu', [
            'nome'        => 'Ver Entradas',
            'descricao'   => 'Entradas no Estoque',
            'path'        => 'estoque/entradas',
            'agrupamento' => 'estoque',
            'localizacao' => 'sidebar_estoque',
            'icone'       => 'fas fa-level-up-alt'
        ]);

        /////// Inicio :: Menus de Finanças ///////
        $this->saveOnce('cadastro_menu', [
            'nome'        => 'Finanças',
            'descricao'   => 'Opções de Finanças',
            'path'        => '#',
            'agrupamento' => 'financas',
            'icone'       => 'fas fa-dollar-sign'
        ]);

        $this->saveOnce('cadastro_menu', [
            'nome'        => 'Fluxo de Caixa',
            'descricao'   => 'Gerenciamento do Financeiro',
            'path'        => 'financeiro',
            'agrupamento' => 'financas',
            'localizacao' => 'financas',
            'icone'       => 'fas fa-dollar-sign'
        ]);

        $this->saveOnce('cadastro_menu', [
            'nome'        => 'Faturamento',
            'descricao'   => 'Faturamento das Vendas',
            'path'        => 'faturamento',
            'agrupamento' => 'financas',
            'localizacao' => 'financas',
            'icone'       => 'fas fa-dollar-sign'
        ]);

        /////// Inicio :: Menus de Vendas ///////
        $this->saveOnce('cadastro_menu', [
            'nome'        => 'Vendas',
            'descricao'   => 'Gerenciamento das Vendas',
            'path'        => '#',
            'agrupamento' => 'vendas',
            'icone'       => 'fas fa-cash-register'
        ]);

        $this->saveOnce('cadastro_menu', [
            'nome'        => 'Vendas',
            'descricao'   => 'Gerenciamento das Vendas',
            'path'        => 'venda',
            'agrupamento' => 'vendas',
            'localizacao' => 'vendas',
            'icone'       => 'fas fa-dollar-sign'
        ]);

        /////// Inicio :: Menus de APPS ///////
        $this->saveOnce('cadastro_menu', [
            'nome'        => 'Aniversários',
            'descricao'   => 'Visualizar os aniversariantes',
            'path'        => 'aniversario',
            'agrupamento' => 'apps',
            'localizacao' => 'apps',
            'icone'       => 'fas fa-gift'
        ]);

        $this->saveOnce('cadastro_menu', [
            'nome'        => 'Estatísticas',
            'descricao'   => 'Visualizar Indicadores da Empresa',
            'path'        => 'estatistica',
            'agrupamento' => 'apps',
            'localizacao' => 'apps',
            'icone'       => 'fas fa-chart-bar'
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
