<?php

namespace App\Controllers;

use App\Libraries\CrudDinamico;
use App\Models\Cadastro\CadastroFluxoTipoModel;
use App\Models\Cadastro\CadastroMetodoPagamentoModel;
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
    public function selectCadastroFluxoTipo()
    {
        try {
            $request = $this->request->getVar();
            $cadastroFluxoTipoModel = new CadastroFluxoTipoModel;
            return $this->response->setJSON($cadastroFluxoTipoModel->selectCadastroFluxoTipo($request));
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    /**
     * Responsável por devolver os dados do Select de Metodos de Pagamento
     */
    public function selectCadastroMetodoPagamento()
    {
        try {
            $request = $this->request->getVar();
            $cadastroMetodoPagamentoModel = new CadastroMetodoPagamentoModel;
            return $this->response->setJSON($cadastroMetodoPagamentoModel->selectCadastroMetodoPagamento($request));
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
     * Crud de Configuração - cadastro_configuração
     * @param string $function Nome da Função desejada (lista, adicionar, alterar)
     * @param string $primaryKeyValue UUID do Registro
     */
    public function configuracao($function = 'lista', $primaryKeyValue = null)
    {
        ////////// NÃO MUDAR - Configurações das Váriaveis //////////
        $config['return']              = __FUNCTION__;
        $config['function']            = $function;

        ////////// Configurações das Váriaveis //////////
        $config['nomeCrud']          = 'Configurações';
        $config['table']             = 'cadastro_configuracao';
        $config['primaryKey']        = "{$config['table']}.uuid_cadastro_configuracao";
        $config['primaryKeyValue']   = $primaryKeyValue;
        $config['btnAdicionarLabel'] = 'Adicionar';
        $config['paginadorMaximo']   = 50;

        ////////// Inicio :: Formulário //////////

        $config['formFields'][0][] = [
            'grid'  => 6,
            'type'  => 'input',
            'label' => ['enabled' => true, 'text' => 'Chave'],
            'data'  => [
                'name'              => 'chave',
                'id'                => 'chave',
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
            'label' => ['enabled' => true, 'text' => 'Valor'],
            'data'  => [
                'name'              => 'valor',
                'id'                => 'valor',
                'type'              => 'text',
                'class'             => 'form-control',
                'required'          => 'required',
                'data-verify'       => 'false',
                'data-verify-clear' => 'false'
            ],
            'select2' => null
        ];

        $config['formFields'][0][] = [
            'grid'  => 12,
            'type'  => 'textarea',
            'label' => ['enabled' => true, 'text' => 'Observação'],
            'data'  => [
                'name'              => 'observacao',
                'id'                => 'observacao',
                'type'              => 'text',
                'rows'              => '5',
                'class'             => 'form-control',
                'data-verify'       => 'false',
                'data-verify-clear' => 'false'
            ],
            'select2' => null
        ];

        ////////// Fim :: Formulário //////////

        ////////// Inicio :: Listagem //////////
        $config['dataGrid'] = [
            [
                'tab_name' => 'Ativos',
                'fields'   => [
                    "{$config['table']}.uuid_cadastro_configuracao" => 'Código',
                    "{$config['table']}.chave"                      => 'Chave',
                    "{$config['table']}.valor"                      => 'Valor',
                    "{$config['table']}.observacao"                 => 'Observação',
                    "{$config['table']}.criado_em"                  => 'Criado em'
                ],
                'where'    => ["cadastro_configuracao.inativado_em IS NULL"],
                'joins'    => null,
                'order_by' => ['field' => "{$config['table']}.chave", 'method' => 'ASC'],
                'options'  => ['enabled' => true, 'edit' => true, 'desativar' => true]
            ],
            [
                'tab_name' => 'Inativos',
                'fields'   => [
                    "{$config['table']}.uuid_cadastro_configuracao" => 'Código',
                    "{$config['table']}.chave"                      => 'Chave',
                    "{$config['table']}.valor"                      => 'Valor',
                    "{$config['table']}.observacao"                 => 'Observação',
                    "{$config['table']}.criado_em"                  => 'Criado em'
                ],
                'where'    => ["cadastro_configuracao.inativado_em IS NOT NULL"],
                'joins'    => null,
                'order_by' => ['field' => "{$config['table']}.chave", 'method' => 'ASC'],
                'options'  => ['enabled' => true, 'ativar' => true]
            ]
        ];
        ////////// Fim :: Listagem //////////

        try {
            $crudDinamico = new CrudDinamico();
            $returnData = $crudDinamico->createPage($config);

            if ($this->request->isAJAX()) {
                return $this->response->setJSON($returnData);
            } else {
                return $this->template('cadastro', ['index', 'functions'], $returnData);
            }
        } catch (Exception $e) {
            var_dump($e);
            die;
        }
    }

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
    public function movimentacaoTipo($function = 'lista', $primaryKeyValue = null)
    {
        ////////// NÃO MUDAR - Configurações das Váriaveis //////////
        $config['return']              = __FUNCTION__;
        $config['function']            = $function;

        ////////// Configurações das Váriaveis //////////
        $config['nomeCrud']          = 'Tipos de Movimentação';
        $config['table']             = 'cadastro_movimentacao_tipo';
        $config['primaryKey']        = "{$config['table']}.uuid_cadastro_movimentacao_tipo";
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
                    "{$config['table']}.uuid_cadastro_movimentacao_tipo" => 'Código',
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
                    "{$config['table']}.uuid_cadastro_movimentacao_tipo" => 'Código',
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
     * Crud de  metodo pagamento - cadastro_metodo_pagamento
     * @param string $function Nome da Função desejada (lista, adicionar, alterar)
     * @param string $primaryKeyValue UUID do Registro
     */
    public function metodoPagamento($function = 'lista', $primaryKeyValue = null)
    {
        ////////// NÃO MUDAR - Configurações das Váriaveis //////////
        $config['return']              = __FUNCTION__;
        $config['function']            = $function;

        ////////// Configurações das Váriaveis //////////
        $config['nomeCrud']          = 'Metodos de Pagamento';
        $config['table']             = 'cadastro_metodo_pagamento';
        $config['primaryKey']        = "{$config['table']}.uuid_cadastro_metodo_pagamento";
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
                    "{$config['table']}.uuid_cadastro_metodo_pagamento" => 'Código',
                    "{$config['table']}.nome"                           => 'Nome',
                    "{$config['table']}.criado_em"                      => 'Criado em'
                ],
                'where'    => ["{$config['table']}.inativado_em IS NULL"],
                'joins'    => null,
                'order_by' => ['field' => "{$config['table']}.nome", 'method' => 'ASC'],
                'options'  => ['enabled' => true, 'edit' => true, 'desativar' => true]
            ],
            [
                'tab_name' => 'Inativos',
                'fields'   => [
                    "{$config['table']}.uuid_cadastro_metodo_pagamento" => 'Código',
                    "{$config['table']}.nome"                           => 'Nome',
                    "{$config['table']}.criado_em"                      => 'Criado em'
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
     * Crud de  relatório - cadastro_relatorio
     * @param string $function Nome da Função desejada (lista, adicionar, alterar)
     * @param string $primaryKeyValue UUID do Registro
     */
    public function relatorio($function = 'lista', $primaryKeyValue = null)
    {
        ////////// NÃO MUDAR - Configurações das Váriaveis //////////
        $config['return']              = __FUNCTION__;
        $config['function']            = $function;

        ////////// Configurações das Váriaveis //////////
        $config['nomeCrud']          = 'Relatórios';
        $config['table']             = 'cadastro_relatorio';
        $config['primaryKey']        = "{$config['table']}.uuid_cadastro_relatorio";
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
                    "{$config['table']}.uuid_cadastro_relatorio" => 'Código',
                    "{$config['table']}.nome"                    => 'Nome',
                    "{$config['table']}.agrupamento"             => 'Agrupamento',
                    "{$config['table']}.slug"                    => 'Slug',
                    "{$config['table']}.criado_em"               => 'Criado em'
                ],
                'where'    => ["{$config['table']}.inativado_em IS NULL"],
                'joins'    => null,
                'order_by' => ['field' => "{$config['table']}.nome", 'method' => 'ASC'],
                'options'  => ['enabled' => true, 'edit' => true, 'desativar' => true]
            ],
            [
                'tab_name' => 'Inativos',
                'fields'   => [
                    "{$config['table']}.uuid_cadastro_relatorio" => 'Código',
                    "{$config['table']}.nome"                    => 'Nome',
                    "{$config['table']}.agrupamento"             => 'Agrupamento',
                    "{$config['table']}.slug"                    => 'Slug',
                    "{$config['table']}.criado_em"               => 'Criado em'
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
     * Crud de Fluxo Tipo - cadastro_fluxo_tipo
     * @param string $function Nome da Função desejada (lista, adicionar, alterar)
     * @param string $primaryKeyValue UUID do Registro
     */
    public function fluxoTipo($function = 'lista', $primaryKeyValue = null)
    {
        ////////// NÃO MUDAR - Configurações das Váriaveis //////////
        $config['return']              = __FUNCTION__;
        $config['function']            = $function;

        ////////// Configurações das Váriaveis //////////
        $config['nomeCrud']          = 'Tipos de Fluxo do Financeiro';
        $config['table']             = 'cadastro_fluxo_tipo';
        $config['primaryKey']        = "{$config['table']}.uuid_cadastro_fluxo_tipo";
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
                    "{$config['table']}.uuid_cadastro_fluxo_tipo" => 'Código',
                    "{$config['table']}.nome"                     => 'Nome',
                    "{$config['table']}.criado_em"                => 'Criado em'
                ],
                'where'    => ["{$config['table']}.inativado_em IS NULL"],
                'joins'    => null,
                'order_by' => ['field' => "{$config['table']}.nome", 'method' => 'ASC'],
                'options'  => ['enabled' => true, 'edit' => true, 'desativar' => true]
            ],
            [
                'tab_name' => 'Inativos',
                'fields'   => [
                    "{$config['table']}.uuid_cadastro_fluxo_tipo" => 'Código',
                    "{$config['table']}.nome"                     => 'Nome',
                    "{$config['table']}.criado_em"                => 'Criado em'
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
     * Crud de Situação da Empresa - empresa_situacao
     * @param string $function Nome da Função desejada (lista, adicionar, alterar)
     * @param string $primaryKeyValue UUID do Registro
     */
    public function empresaSituacao($function = 'lista', $primaryKeyValue = null)
    {
        ////////// NÃO MUDAR - Configurações das Váriaveis //////////
        $config['return']              = __FUNCTION__;
        $config['function']            = $function;

        ////////// Configurações das Váriaveis //////////
        $config['nomeCrud']          = 'Situações da Empresa';
        $config['table']             = 'empresa_situacao';
        $config['primaryKey']        = "{$config['table']}.uuid_empresa_situacao";
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
                    "{$config['table']}.uuid_empresa_situacao" => 'Código',
                    "{$config['table']}.nome"                  => 'Nome',
                    "{$config['table']}.criado_em"             => 'Criado em'
                ],
                'where'    => ["{$config['table']}.inativado_em IS NULL"],
                'joins'    => null,
                'order_by' => ['field' => "{$config['table']}.nome", 'method' => 'ASC'],
                'options'  => ['enabled' => true, 'edit' => true, 'desativar' => true]
            ],
            [
                'tab_name' => 'Inativos',
                'fields'   => [
                    "{$config['table']}.uuid_empresa_situacao" => 'Código',
                    "{$config['table']}.nome"                  => 'Nome',
                    "{$config['table']}.criado_em"             => 'Criado em'
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
     * Crud de Centro de Custos da Empresa - empresa_centro_custo
     * @param string $function Nome da Função desejada (lista, adicionar, alterar)
     * @param string $primaryKeyValue UUID do Registro
     */
    public function empresaCentroCusto($function = 'lista', $primaryKeyValue = null)
    {
        ////////// NÃO MUDAR - Configurações das Váriaveis //////////
        $config['return']              = __FUNCTION__;
        $config['function']            = $function;

        ////////// Configurações das Váriaveis //////////
        $config['nomeCrud']          = 'Centro de Custos da Empresa';
        $config['table']             = 'empresa_centro_custo';
        $config['primaryKey']        = "{$config['table']}.uuid_empresa_centro_custo";
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
                    "{$config['table']}.uuid_empresa_centro_custo" => 'Código',
                    "{$config['table']}.nome"                      => 'Nome',
                    "{$config['table']}.criado_em"                 => 'Criado em'
                ],
                'where'    => ["{$config['table']}.inativado_em IS NULL"],
                'joins'    => null,
                'order_by' => ['field' => "{$config['table']}.nome", 'method' => 'ASC'],
                'options'  => ['enabled' => true, 'edit' => true, 'desativar' => true]
            ],
            [
                'tab_name' => 'Inativos',
                'fields'   => [
                    "{$config['table']}.uuid_empresa_centro_custo" => 'Código',
                    "{$config['table']}.nome"                      => 'Nome',
                    "{$config['table']}.criado_em"                 => 'Criado em'
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
     * Crud de Categorias do Estoque da Empresa - empresa_categoria
     * @param string $function Nome da Função desejada (lista, adicionar, alterar)
     * @param string $primaryKeyValue UUID do Registro
     */
    public function empresaCategoria($function = 'lista', $primaryKeyValue = null)
    {
        ////////// NÃO MUDAR - Configurações das Váriaveis //////////
        $config['return']              = __FUNCTION__;
        $config['function']            = $function;

        ////////// Configurações das Váriaveis //////////
        $config['nomeCrud']          = 'Categorias do Estoque da Empresa';
        $config['table']             = 'empresa_categoria';
        $config['primaryKey']        = "{$config['table']}.uuid_empresa_categoria";
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
                    "{$config['table']}.uuid_empresa_categoria" => 'Código',
                    "{$config['table']}.nome"                   => 'Nome',
                    "{$config['table']}.criado_em"              => 'Criado em'
                ],
                'where'    => ["{$config['table']}.inativado_em IS NULL"],
                'joins'    => null,
                'order_by' => ['field' => "{$config['table']}.nome", 'method' => 'ASC'],
                'options'  => ['enabled' => true, 'edit' => true, 'desativar' => true]
            ],
            [
                'tab_name' => 'Inativos',
                'fields'   => [
                    "{$config['table']}.uuid_empresa_categoria" => 'Código',
                    "{$config['table']}.nome"                   => 'Nome',
                    "{$config['table']}.criado_em"              => 'Criado em'
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
     * Crud de Comissões da Empresa - empresa_comissao
     * @param string $function Nome da Função desejada (lista, adicionar, alterar)
     * @param string $primaryKeyValue UUID do Registro
     */
    public function empresaComissao($function = 'lista', $primaryKeyValue = null)
    {
        ////////// NÃO MUDAR - Configurações das Váriaveis //////////
        $config['return']              = __FUNCTION__;
        $config['function']            = $function;

        ////////// Configurações das Váriaveis //////////
        $config['nomeCrud']          = 'Comissões da Empresa';
        $config['table']             = 'empresa_comissao';
        $config['primaryKey']        = "{$config['table']}.uuid_empresa_comissao";
        $config['primaryKeyValue']   = $primaryKeyValue;
        $config['btnAdicionarLabel'] = 'Adicionar';
        $config['paginadorMaximo']   = 50;

        ////////// Inicio :: Formulário //////////

        $config['formFields'][0][] = [
            'grid'  => 6,
            'type'  => 'input',
            'label' => ['enabled' => true, 'text' => 'Vendedor'],
            'data'  => [
                'name'              => 'codigo_vendedor',
                'id'                => 'codigo_vendedor',
                'type'              => 'text',
                'class'             => 'form-control',
            ],
            'select2' => null
        ];

        $config['formFields'][0][] = [
            'grid'  => 6,
            'type'  => 'input',
            'label' => ['enabled' => true, 'text' => 'Percentual'],
            'data'  => [
                'name'                => 'percentual',
                'id'                  => 'percentual',
                'type'                => 'text',
                'class'               => 'form-control',
                'required'            => 'required',
                'data-verificanumero' => 'true',
            ],
            'select2' => null
        ];

        $config['formFields'][0][] = [
            'grid'  => 6,
            'type'  => 'input',
            'label' => ['enabled' => true, 'text' => 'Valor Inicial'],
            'data'  => [
                'name'                => 'valor_inicial',
                'id'                  => 'valor_inicial',
                'type'                => 'text',
                'class'               => 'form-control',
                'data-verificanumero' => 'true'
            ],
            'select2' => null
        ];

        $config['formFields'][0][] = [
            'grid'  => 6,
            'type'  => 'input',
            'label' => ['enabled' => true, 'text' => 'Valor Final'],
            'data'  => [
                'name'                => 'valor_final',
                'id'                  => 'valor_final',
                'type'                => 'text',
                'class'               => 'form-control',
                'data-verificanumero' => 'true'
            ],
            'select2' => null
        ];

        ////////// Fim :: Formulário //////////

        ////////// Inicio :: Listagem //////////
        $config['dataGrid'] = [
            [
                'tab_name' => 'Ativos',
                'fields'   => [
                    "{$config['table']}.uuid_empresa_comissao"  => 'Código',
                    "{$config['table']}.percentual"             => 'Percentual',
                    "{$config['table']}.criado_em"              => 'Criado em'
                ],
                'where'    => ["{$config['table']}.inativado_em IS NULL"],
                'joins'    => null,
                'order_by' => ['field' => "{$config['table']}.criado_em", 'method' => 'ASC'],
                'options'  => ['enabled' => true, 'edit' => true, 'desativar' => true]
            ],
            [
                'tab_name' => 'Inativos',
                'fields'   => [
                    "{$config['table']}.uuid_empresa_comissao" => 'Código',
                    "{$config['table']}.percentual"            => 'Percentual',
                    "{$config['table']}.criado_em"             => 'Criado em'
                ],
                'where'    => ["{$config['table']}.inativado_em IS NOT NULL"],
                'joins'    => null,
                'order_by' => ['field' => "{$config['table']}.criado_em", 'method' => 'ASC'],
                'options'  => ['enabled' => true, 'ativar' => true]
            ]
        ];
        ////////// Fim :: Listagem //////////

        try {
            $crudDinamico = new CrudDinamico();
            $returnData = $crudDinamico->createPage($config);

            if ($this->request->isAJAX()) {
                return $this->response->setJSON($returnData);
            } else {
                return $this->template('cadastro', ['index', 'functions'], $returnData);
            }
        } catch (Exception $e) {
            var_dump($e);
            die;
        }
    }
}
