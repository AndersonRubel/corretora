<?php

namespace App\Controllers;

use App\Libraries\CrudDinamico;
use App\Models\Cadastro\CadastroCategoriaImovelModel;
use App\Models\Cadastro\CadastroTipoImovelModel;
use Exception;

class CadastroController extends BaseController
{
    public function index()
    {
        // Busca os menus que o usuario tem permissão
        $menus = $this->nativeSession->get("menus");

        $menus = array_filter($menus, function ($menu) {
            return $menu['agrupamento'] == "cadastro";
        });

        if (empty($menus)) {
            $this->nativeSession->setFlashData('error', lang('Errors.geral.acessoNaoPermitido'));
            return redirect()->to(base_url());
        }

        // Redirecionar para o primeiro cadastro que o usuario tiver permissao
        return redirect()->to(base_url("cadastro/{$menus[0]['path']}"));
    }


    //////////////////////////////////////
    //                                  //
    //       OPERAÇÕES DE SELECT2       //
    //                                  //
    //////////////////////////////////////

    /**
     * Responsável por devolver os dados do Select de Tipos de Fluxo
     */
    public function selectCategoriaImovel()
    {
        try {
            $request = $this->request->getVar();
            $cadastroCategoriaImovel = new CadastroCategoriaImovelModel;
            return $this->response->setJSON($cadastroCategoriaImovel->selectCategoriaImovel($request));
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    /**
     * Responsável por devolver os dados do Select de Tipos de Fluxo
     */
    public function selectTipoImovel()
    {
        try {
            $request = $this->request->getVar();
            $cadastroTipoImovel = new CadastroTipoImovelModel;
            return $this->response->setJSON($cadastroTipoImovel->selectTipoImovel($request));
        } catch (Exception $e) {
            var_dump($e);
        }
    }
    //////////////////////////////////////
    //                                  //
    //         OPERAÇÕES DE CRUD        //
    //                                  //
    //////////////////////////////////////

    /**
     * Insere um novo registro no Banco
     * @param string $table Nome da tabela
     * @param string $linkRetorno URL para qual será direcionado após a inserção
     * @return redirect Redireciona para o $linkRetorno
     */
    public function insert(string $table, string $linkRetorno)
    {
        try {
            $crudDinamico = new CrudDinamico();
            $this->nativeSession->setFlashData('success', lang('Success.default.cadastrado', ['Registro']));
            return $crudDinamico->insert($table, $linkRetorno);
        } catch (Exception $e) {
            $this->nativeSession->setFlashData('error', lang('Erros.geral.operacao'));
            return redirect()->to(base_url("cadastro/{$linkRetorno}"));
        }
    }

    /**
     * Altera um registro no Banco
     * @param string $table Nome da tabela
     * @param string $primaryKey Nome da primaryKey da Tabela
     * @param string $primaryKeyValue Valor da primaryKey da Tabela
     * @param string $linkRetorno URL para qual será direcionado após a inserção
     * @return redirect Redireciona para o $linkRetorno
     */
    public function update(string $table, string $primaryKey, string $primaryKeyValue, string $linkRetorno)
    {
        try {
            $crudDinamico = new CrudDinamico();
            $this->nativeSession->setFlashData('success', lang('Success.default.atualizado', ['Registro']));
            return $crudDinamico->update($table, $primaryKey, $primaryKeyValue, $linkRetorno);
        } catch (Exception $e) {
            $this->nativeSession->setFlashData('error', lang('Erros.geral.operacao'));
            return redirect()->to(base_url("cadastro/{$linkRetorno}"));
        }
    }

    /**
     * Exclui um registro no Banco
     * @param string $table Nome da tabela
     * @param string $primaryKey Nome da primaryKey da Tabela
     * @param string $primaryKeyValue Valor da primaryKey da Tabela
     * @return bool Status da Transação
     */
    public function delete(string $table, string $primaryKey, string $primaryKeyValue)
    {
        try {
            $crudDinamico = new CrudDinamico();
            $this->nativeSession->setFlashData('success', lang('Success.default.removido', ['Registro']));
            return $crudDinamico->delete($table, $primaryKey, $primaryKeyValue);
        } catch (Exception $e) {
            return $this->response->setJSON(['mensagem' => lang('Erros.geral.operacao')], 422);
        }
    }

    /**
     * Altera o Status de um registro no Banco
     * @param string $table Nome da tabela
     * @param string $primaryKey Nome da primaryKey da Tabela
     * @param string $primaryKeyValue Valor da primaryKey da Tabela
     * @return bool Status da Transação
     */
    public function toggleStatus(string $table, string $primaryKey, string $primaryKeyValue)
    {
        try {
            $crudDinamico = new CrudDinamico();
            $crudDinamico->toggleStatus($table, $primaryKey, $primaryKeyValue);
            return $this->response->setJSON(['mensagem' => lang('Success.geral.operacao')], 202);
        } catch (Exception $e) {
            return $this->response->setJSON(['mensagem' => lang('Erros.geral.operacao')], 422);
        }
    }

    /**
     * Copia um registro no Banco
     * @param string $table Nome da tabela
     * @param string $primaryKey Nome da primaryKey da Tabela
     * @param string $primaryKeyValue Valor da primaryKey da Tabela
     * @return bool Status da Transação
     */
    public function copiarRegistro(string $table, string $primaryKey, string $primaryKeyValue)
    {
        try {
            $crudDinamico = new CrudDinamico();
            $crudDinamico->copiarRegistro($table, $primaryKey, $primaryKeyValue);
            return $this->response->setJSON(['mensagem' => lang('Success.geral.operacao')], 202);
        } catch (Exception $e) {
            return $this->response->setJSON(['mensagem' => lang('Erros.geral.operacao')], 422);
        }
    }


    //////////////////////////////////////
    //                                  //
    //      TELAS DO CRUD DINÂMICO      //
    //                                  //
    //////////////////////////////////////

    /**
     * Crud de grupo - cadastro_grupo
     * @param string $function Nome da Função desejada (lista, adicionar, alterar)
     * @param string $primaryKeyValue UUID do Registro
     */
    public function grupo($function = 'lista', $primaryKeyValue = null)
    {
        ////////// NÃO MUDAR - Configurações das Váriaveis //////////
        $config['return']              = __FUNCTION__;
        $config['function']            = $function;

        ////////// Configurações das Váriaveis //////////
        $config['nomeCrud']          = 'Grupos';
        $config['table']             = 'cadastro_grupo';
        $config['primaryKey']        = "{$config['table']}.uuid_cadastro_grupo";
        $config['primaryKeyValue']   = $primaryKeyValue;
        $config['btnAdicionarLabel'] = 'Adicionar';
        $config['paginadorMaximo']   = 50;

        ////////// Inicio :: Formulário //////////

        $config['formFields'][0][] = [
            'grid'  => 6,
            'type'  => 'input',
            'label' => ['enabled' => true, 'text' => 'Nome'],
            'data'  => [
                'name'              => 'nome',
                'id'                => 'nome',
                'type'              => 'text',
                'class'             => 'form-control',
                'required'          => 'required',
                'data-verify'       => 'true',
                'data-verify-clear' => 'true'
            ],
            'select2' => null
        ];

        $config['formFields'][0][] = [
            'grid'  => 6,
            'type'  => 'input',
            'label' => ['enabled' => true, 'text' => 'Slug'],
            'data'  => [
                'name'              => 'slug',
                'id'                => 'slug',
                'type'              => 'text',
                'class'             => 'form-control',
                'required'          => 'required',
                'data-verify'       => 'true',
                'data-verify-clear' => 'true'
            ],
            'select2' => null
        ];

        ////////// Fim :: Formulário //////////

        ////////// Inicio :: Listagem //////////
        $config['dataGrid'] = [
            [
                'tab_name' => 'Ativos',
                'fields'   => [
                    "{$config['table']}.uuid_cadastro_grupo" => 'Código',
                    "{$config['table']}.nome"                => 'Nome',
                    "{$config['table']}.slug"                => 'Slug',
                    "{$config['table']}.criado_em"           => 'Criado em'
                ],
                'where'    => ["{$config['table']}.inativado_em IS NULL"],
                'joins'    => null,
                'order_by' => ['field' => "{$config['table']}.nome", 'method' => 'ASC'],
                'options'  => ['enabled' => true, 'edit' => true, 'desativar' => true]
            ],
            [
                'tab_name' => 'Inativos',
                'fields'   => [
                    "{$config['table']}.uuid_cadastro_grupo" => 'Código',
                    "{$config['table']}.nome"                => 'Nome',
                    "{$config['table']}.slug"                => 'Slug',
                    "{$config['table']}.criado_em"           => 'Criado em'
                ],
                'where'    => ["{$config['table']}.inativado_em IS NOT NULL"],
                'joins'    => null,
                'order_by' => ['field' => "{$config['table']}.nome", 'method' => 'ASC'],
                'options'  => ['enabled' => true, 'ativar' => true]
            ]
        ];
        ////////// Fim :: Listagem //////////

        $crudDinamico = new CrudDinamico();

        $returnData = $crudDinamico->createPage($config);
        if ($this->request->isAJAX()) {
            return $this->response->setJSON($returnData);
        } else {
            return $this->template('cadastro', ['index', 'functions'], $returnData);
        }
    }

    /**
     * Crud de menu - cadastro_menu
     * @param string $function Nome da Função desejada (lista, adicionar, alterar)
     * @param string $primaryKeyValue UUID do Registro
     */
    public function menu($function = 'lista', $primaryKeyValue = null)
    {
        ////////// NÃO MUDAR - Configurações das Váriaveis //////////
        $config['return']              = __FUNCTION__;
        $config['function']            = $function;

        ////////// Configurações das Váriaveis //////////
        $config['nomeCrud']          = 'Menus';
        $config['table']             = 'cadastro_menu';
        $config['primaryKey']        = "{$config['table']}.uuid_cadastro_menu";
        $config['primaryKeyValue']   = $primaryKeyValue;
        $config['btnAdicionarLabel'] = 'Adicionar';
        $config['paginadorMaximo']   = 50;

        ////////// Inicio :: Formulário //////////

        $config['formFields'][0][] = [
            'grid'  => 6,
            'type'  => 'input',
            'label' => ['enabled' => true, 'text' => 'Nome'],
            'data'  => [
                'name'              => 'nome',
                'id'                => 'nome',
                'type'              => 'text',
                'class'             => 'form-control',
                'required'          => 'required',
                'data-verify'       => 'true',
                'data-verify-clear' => 'true'
            ],
            'select2' => null
        ];

        $config['formFields'][0][] = [
            'grid'  => 6,
            'type'  => 'input',
            'label' => ['enabled' => true, 'text' => 'Descricao'],
            'data'  => [
                'name'              => 'descricao',
                'id'                => 'descricao',
                'type'              => 'text',
                'class'             => 'form-control',
                'required'          => 'required'
            ],
            'select2' => null
        ];

        $config['formFields'][0][] = [
            'grid'  => 6,
            'type'  => 'input',
            'label' => ['enabled' => true, 'text' => 'Path'],
            'data'  => [
                'name'              => 'path',
                'id'                => 'path',
                'type'              => 'text',
                'class'             => 'form-control',
                'required'          => 'required',
                'data-verify'       => 'true',
                'data-verify-clear' => 'true'
            ],
            'select2' => null
        ];

        $config['formFields'][0][] = [
            'grid'  => 6,
            'type'  => 'input',
            'label' => ['enabled' => true, 'text' => 'Agrupamento'],
            'data'  => [
                'name'              => 'agrupamento',
                'id'                => 'agrupamento',
                'type'              => 'text',
                'class'             => 'form-control',
                'required'          => 'required'
            ],
            'select2' => null
        ];

        $config['formFields'][0][] = [
            'grid'  => 6,
            'type'  => 'input',
            'label' => ['enabled' => true, 'text' => 'Ordenacao'],
            'data'  => [
                'name'              => 'ordenacao',
                'id'                => 'ordenacao',
                'type'              => 'number',
                'class'             => 'form-control'
            ],
            'select2' => null
        ];

        $config['formFields'][0][] = [
            'grid'  => 6,
            'type'  => 'input',
            'label' => ['enabled' => true, 'text' => 'Icone'],
            'data'  => [
                'name'              => 'icone',
                'id'                => 'icone',
                'type'              => 'text',
                'class'             => 'form-control'
            ],
            'select2' => null
        ];

        ////////// Fim :: Formulário //////////

        ////////// Inicio :: Listagem //////////
        $config['dataGrid'] = [
            [
                'tab_name' => 'Ativos',
                'fields'   => [
                    "{$config['table']}.uuid_cadastro_menu" => 'Código',
                    "{$config['table']}.nome"               => 'Nome',
                    "{$config['table']}.path"               => 'Path',
                    "{$config['table']}.agrupamento"        => 'Agrupamento',
                    "{$config['table']}.ordenacao"          => 'Ordenacao',
                    "{$config['table']}.icone"              => 'Icone',
                    "{$config['table']}.descricao"          => 'Descricao',
                    "{$config['table']}.criado_em"          => 'Criado em'
                ],
                'where'    => ["{$config['table']}.inativado_em IS NULL"],
                'joins'    => null,
                'order_by' => ['field' => "{$config['table']}.nome", 'method' => 'ASC'],
                'options'  => ['enabled' => true, 'edit' => true, 'desativar' => true]
            ],
            [
                'tab_name' => 'Inativos',
                'fields'   => [
                    "{$config['table']}.uuid_cadastro_menu" => 'Código',
                    "{$config['table']}.nome"               => 'Nome',
                    "{$config['table']}.path"               => 'Path',
                    "{$config['table']}.agrupamento"        => 'Agrupamento',
                    "{$config['table']}.ordenacao"          => 'Ordenacao',
                    "{$config['table']}.icone"              => 'Icone',
                    "{$config['table']}.descricao"          => 'Descricao',
                    "{$config['table']}.criado_em"          => 'Criado em'
                ],
                'where'    => ["{$config['table']}.inativado_em IS NOT NULL"],
                'joins'    => null,
                'order_by' => ['field' => "{$config['table']}.nome", 'method' => 'ASC'],
                'options'  => ['enabled' => true, 'ativar' => true]
            ]
        ];
        ////////// Fim :: Listagem //////////

        $crudDinamico = new CrudDinamico();
        $returnData = $crudDinamico->createPage($config);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($returnData);
        } else {
            return $this->template('cadastro', ['index', 'functions'], $returnData);
        }
    }

    /**
     * Crud de movimentacao tipo - cadastro_movimentacao_tipo
     * @param string $function Nome da Função desejada (lista, adicionar, alterar)
     * @param string $primaryKeyValue UUID do Registro
     */
    public function tipoImovel($function = 'lista', $primaryKeyValue = null)
    {
        ////////// NÃO MUDAR - Configurações das Váriaveis //////////
        $config['return']              = __FUNCTION__;
        $config['function']            = $function;

        ////////// Configurações das Váriaveis //////////
        $config['nomeCrud']          = 'Tipos de Imóveis';
        $config['table']             = 'tipo_imovel';
        $config['primaryKey']        = "{$config['table']}.uuid_tipo_imovel";
        $config['primaryKeyValue']   = $primaryKeyValue;
        $config['btnAdicionarLabel'] = 'Adicionar';
        $config['paginadorMaximo']   = 50;

        ////////// Inicio :: Formulário //////////

        $config['formFields'][0][] = [
            'grid'  => 6,
            'type'  => 'input',
            'label' => ['enabled' => true, 'text' => 'Nome'],
            'data'  => [
                'name'              => 'nome',
                'id'                => 'nome',
                'type'              => 'text',
                'class'             => 'form-control',
                'required'          => 'required',
                'data-verify'       => 'true',
                'data-verify-clear' => 'true'
            ],
            'select2' => null
        ];

        ////////// Fim :: Formulário //////////

        ////////// Inicio :: Listagem //////////
        $config['dataGrid'] = [
            [
                'tab_name' => 'Ativos',
                'fields'   => [
                    "{$config['table']}.uuid_tipo_imovel"                => 'Código',
                    "{$config['table']}.nome"                            => 'Nome',
                    "{$config['table']}.criado_em"                       => 'Criado em'
                ],
                'where'    => ["{$config['table']}.inativado_em IS NULL"],
                'joins'    => null,
                'order_by' => ['field' => "{$config['table']}.nome", 'method' => 'ASC'],
                'options'  => ['enabled' => true, 'edit' => true, 'desativar' => true]
            ],
            [
                'tab_name' => 'Inativos',
                'fields'   => [
                    "{$config['table']}.uuid_tipo_imovel"                => 'Código',
                    "{$config['table']}.nome"                            => 'Nome',
                    "{$config['table']}.criado_em"                       => 'Criado em'
                ],
                'where'    => ["{$config['table']}.inativado_em IS NOT NULL"],
                'joins'    => null,
                'order_by' => ['field' => "{$config['table']}.nome", 'method' => 'ASC'],
                'options'  => ['enabled' => true, 'ativar' => true]
            ]
        ];
        ////////// Fim :: Listagem //////////

        $crudDinamico = new CrudDinamico();
        $returnData = $crudDinamico->createPage($config);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($returnData);
        } else {
            return $this->template('cadastro', ['index', 'functions'], $returnData);
        }
    }

    /**
     * Crud de movimentacao tipo - cadastro_movimentacao_tipo
     * @param string $function Nome da Função desejada (lista, adicionar, alterar)
     * @param string $primaryKeyValue UUID do Registro
     */
    public function categoriaImovel($function = 'lista', $primaryKeyValue = null)
    {
        ////////// NÃO MUDAR - Configurações das Váriaveis //////////
        $config['return']              = __FUNCTION__;
        $config['function']            = $function;

        ////////// Configurações das Váriaveis //////////
        $config['nomeCrud']          = 'Categorias de Imóvel';
        $config['table']             = 'categoria_imovel';
        $config['primaryKey']        = "{$config['table']}.uuid_categoria_imovel";
        $config['primaryKeyValue']   = $primaryKeyValue;
        $config['btnAdicionarLabel'] = 'Adicionar';
        $config['paginadorMaximo']   = 50;

        ////////// Inicio :: Formulário //////////

        $config['formFields'][0][] = [
            'grid'  => 6,
            'type'  => 'input',
            'label' => ['enabled' => true, 'text' => 'Nome'],
            'data'  => [
                'name'              => 'nome',
                'id'                => 'nome',
                'type'              => 'text',
                'class'             => 'form-control',
                'required'          => 'required',
                'data-verify'       => 'true',
                'data-verify-clear' => 'true'
            ],
            'select2' => null
        ];

        ////////// Fim :: Formulário //////////

        ////////// Inicio :: Listagem //////////
        $config['dataGrid'] = [
            [
                'tab_name' => 'Ativos',
                'fields'   => [
                    "{$config['table']}.uuid_categoria_imovel"           => 'Código',
                    "{$config['table']}.nome"                            => 'Nome',
                    "{$config['table']}.criado_em"                       => 'Criado em'
                ],
                'where'    => ["{$config['table']}.inativado_em IS NULL"],
                'joins'    => null,
                'order_by' => ['field' => "{$config['table']}.nome", 'method' => 'ASC'],
                'options'  => ['enabled' => true, 'edit' => true, 'desativar' => true]
            ],
            [
                'tab_name' => 'Inativos',
                'fields'   => [
                    "{$config['table']}.uuid_categoria_imovel"           => 'Código',
                    "{$config['table']}.nome"                            => 'Nome',
                    "{$config['table']}.criado_em"                       => 'Criado em'
                ],
                'where'    => ["{$config['table']}.inativado_em IS NOT NULL"],
                'joins'    => null,
                'order_by' => ['field' => "{$config['table']}.nome", 'method' => 'ASC'],
                'options'  => ['enabled' => true, 'ativar' => true]
            ]
        ];
        ////////// Fim :: Listagem //////////

        $crudDinamico = new CrudDinamico();
        $returnData = $crudDinamico->createPage($config);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($returnData);
        } else {
            return $this->template('cadastro', ['index', 'functions'], $returnData);
        }
    }
}