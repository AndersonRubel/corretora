<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\RedirectResponse;
use Exception;

use App\Models\Empresa\EmpresaModel;

class EmpresaController extends BaseController
{
    //////////////////////////////////
    //                              //
    //      OPERAÇÕES DE BUSCA      //
    //                              //
    //////////////////////////////////

    /**
     * Exibe a Tela de Empresa
     * @return html
     */
    public function index()
    {
        $dadosEmpresa = $this->nativeSession->get("empresa");
        return redirect()->to(base_url("empresa/alterar/{$dadosEmpresa['uuid_empresa']}"));
        // return $this->template('empresa', ['index', 'functions']);
    }

    /**
     * Exibe a Tela de Adicionar Registro
     * @return html
     */
    public function create()
    {
        return $this->template('empresa', ['create', 'functions']);
    }

    /**
     * Exibe a Tela de Alterar o Registro
     * @param string $uuid UUID do Registro
     * @return html
     */
    public function edit(string $uuid)
    {
        if (!$this->verificarUuid($uuid)) {
            $this->nativeSession->setFlashData('error', lang('Errors.geral.validaUuid'));
            return redirect()->to(base_url("empresa"));
        }

        $empresaModel = new EmpresaModel;
        $dados['empresa'] = $empresaModel->get([$empresaModel->uuidColumn => $uuid], [], true);

        // Desestrutura os JSONB para preencher os campos no formulário
        $dados['empresa']['endereco'] = json_decode($dados['empresa']['endereco'], true);
        $dados['empresa']['responsavel'] = json_decode($dados['empresa']['responsavel'], true);
        $dados['empresa']['configuracao_nota_fiscal'] = json_decode($dados['empresa']['configuracao_nota_fiscal'], true);

        return $this->template('empresa', ['edit', 'functions'], $dados);
    }

    /**
     * Busca os registros para o Datagrid
     * @param int $status Verifica se a informação está ativa (1 ou 0)
     */
    public function getDataGrid(int $status)
    {
        $empresaModel = new EmpresaModel;
        $dadosRequest = $this->request->getVar();
        $dadosRequest['status'] = $status;
        $data = $empresaModel->getDataGrid($dadosRequest);
        return $this->responseDataGrid($data, $dadosRequest);
    }

    //////////////////////////////////
    //                              //
    //    OPERAÇÕES DE CADASTRO     //
    //                              //
    //////////////////////////////////

    /**
     * Realiza o Cadastro do Registro
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function store(): RedirectResponse
    {
        $empresaModel = new EmpresaModel;
        $dadosRequest = convertEmptyToNull($this->request->getVar());
        $dadosUsuario = $this->nativeSession->get("usuario");

        $erros = $this->validarRequisicao($this->request, [
            'tipo_pessoa' => 'required|integer|in_list[1,2]',
            'razao_social' => 'permit_empty|string|min_length[3]|max_length[255]',
            'nome_fantasia' => 'required|string|min_length[3]|max_length[255]',
            'cpf_cnpj' => 'required|string|min_length[11]|max_length[18]',
            'email' => 'permit_empty|valid_email|max_length[255]',
            'email_financeiro' => 'permit_empty|valid_email|max_length[255]',
            'telefone' => [
                'rules' => 'permit_empty|checkTelefone',
                'errors' => ['checkTelefone' => 'Errors.geral.telefoneInvalido'],
            ],
            'telefone_adicional' => [
                'rules' => 'permit_empty|checkTelefone',
                'errors' => ['checkTelefone' => 'Errors.geral.telefoneInvalido'],
            ],
            'celular' => [
                'rules' => 'permit_empty|checkTelefone',
                'errors' => ['checkTelefone' => 'Errors.geral.telefoneInvalido'],
            ],
            'dia_pagamento' => [
                'rules' => 'permit_empty|checkDiasPagamento',
                'errors' => ['checkDiasPagamento' => 'Errors.empresa.diaPagamentoInvalido'],
            ],
            'cep' => [
                'rules' => 'required|checkCep',
                'errors' => ['checkCep' => 'Errors.geral.cepInvalido'],
            ],
            'rua' => 'required|string|max_length[255]',
            'numero' => 'required|integer|max_length[255]',
            'bairro' => 'required|string|max_length[255]',
            'complemento' => 'permit_empty|string|max_length[255]',
            'cidade' => 'required|string|max_length[255]',
            'uf' => 'required|string|max_length[255]',
            'responsavel_nome' => 'permit_empty|string|max_length[255]',
            'responsavel_cpf' => 'permit_empty|string|min_length[11]|max_length[18]',
            'responsavel_data_nascimento' => 'permit_empty|valid_date',
            'responsavel_rg' => 'permit_empty|string|max_length[255]',
            'responsavel_rg_orgao_emissor' => 'permit_empty|string|max_length[255]',
            'responsavel_cep' => [
                'rules' => 'permit_empty|checkCep',
                'errors' => ['checkCep' => 'Errors.geral.cepInvalido'],
            ],
            'responsavel_rua' => 'permit_empty|string|max_length[255]',
            'responsavel_numero' => 'permit_empty|integer|max_length[255]',
            'responsavel_bairro' => 'permit_empty|string|max_length[255]',
            'responsavel_complemento' => 'permit_empty|string|max_length[255]',
            'responsavel_cidade' => 'permit_empty|string|max_length[255]',
            'responsavel_uf' => 'permit_empty|string|max_length[255]',
            'possui_inscricao_estadual' => 'permit_empty|string|in_list[sim,nao,isento]',
            'inscricao_estadual' => 'permit_empty|string',
        ]);

        if (!empty($erros)) {
            $this->nativeSession->setFlashData('error', formataErros($erros));
            return redirect()->back()->withInput();
        }

        // JSONB de Dados do Endereço
        $empresaEndereco = [
            'cep'         => !empty($dadosRequest['cep'])         ? onlyNumber($dadosRequest['cep'])    : '',
            'rua'         => !empty($dadosRequest['rua'])         ? $dadosRequest['rua']                : '',
            'numero'      => !empty($dadosRequest['numero'])      ? onlyNumber($dadosRequest['numero']) : '',
            'bairro'      => !empty($dadosRequest['bairro'])      ? $dadosRequest['bairro']             : '',
            'complemento' => !empty($dadosRequest['complemento']) ? $dadosRequest['complemento']        : '',
            'cidade'      => !empty($dadosRequest['cidade'])      ? $dadosRequest['cidade']             : '',
            'uf'          => !empty($dadosRequest['uf'])          ? $dadosRequest['uf']                 : ''
        ];

        // JSONB de Dados do Responsavel
        $empresaResponsavel = [
            'nome'             => !empty($dadosRequest['responsavel_nome'])             ? $dadosRequest['responsavel_nome']               : '',
            'cpf'              => !empty($dadosRequest['responsavel_cpf'])              ? onlyNumber($dadosRequest['responsavel_cpf'])    : '',
            'data_nascimento'  => !empty($dadosRequest['responsavel_data_nascimento'])  ? $dadosRequest['responsavel_data_nascimento']    : '',
            'rg'               => !empty($dadosRequest['responsavel_rg'])               ? onlyNumber($dadosRequest['responsavel_rg'])     : '',
            'rg_orgao_emissor' => !empty($dadosRequest['responsavel_rg_orgao_emissor']) ? $dadosRequest['responsavel_rg_orgao_emissor']   : '',
            'cep'              => !empty($dadosRequest['responsavel_cep'])              ? onlyNumber($dadosRequest['responsavel_cep'])    : '',
            'rua'              => !empty($dadosRequest['responsavel_rua'])              ? $dadosRequest['responsavel_rua']                : '',
            'numero'           => !empty($dadosRequest['responsavel_numero'])           ? onlyNumber($dadosRequest['responsavel_numero']) : '',
            'bairro'           => !empty($dadosRequest['responsavel_bairro'])           ? $dadosRequest['responsavel_bairro']             : '',
            'complemento'      => !empty($dadosRequest['responsavel_complemento'])      ? $dadosRequest['responsavel_complemento']        : '',
            'cidade'           => !empty($dadosRequest['responsavel_cidade'])           ? $dadosRequest['responsavel_cidade']             : '',
            'uf'               => !empty($dadosRequest['responsavel_uf'])               ? $dadosRequest['responsavel_uf']                 : ''
        ];

        // JSONB de Dados de Nota Fiscal (NF e NFS)
        $empresaNf = [
            'possui_inscricao_estadual' => !empty($dadosRequest['possui_inscricao_estadual']) ? $dadosRequest['possui_inscricao_estadual']      : '',
            'inscricao_estadual'        => !empty($dadosRequest['inscricao_estadual'])        ? onlyNumber($dadosRequest['inscricao_estadual']) : '',
        ];

        $empresa = [
            'usuario_criacao'          => $dadosUsuario['codigo_usuario'],
            'tipo_pessoa'              => onlyNumber($dadosRequest['tipo_pessoa']),
            'nome_fantasia'            => $dadosRequest['nome_fantasia'],
            'razao_social'             => $dadosRequest['razao_social'],
            'cpf_cnpj'                 => onlyNumber($dadosRequest['cpf_cnpj']),
            'telefone'                 => onlyNumber($dadosRequest['telefone']),
            'telefone_adicional'       => onlyNumber($dadosRequest['telefone_adicional']),
            'celular'                  => onlyNumber($dadosRequest['celular']),
            'email'                    => $dadosRequest['email'],
            'email_financeiro'         => $dadosRequest['email_financeiro'],
            'dia_pagamento'            => onlyNumber($dadosRequest['dia_pagamento']),
            'endereco'                 => !empty($empresaEndereco)    ? json_encode($empresaEndereco)    : null,
            'responsavel'              => !empty($empresaResponsavel) ? json_encode($empresaResponsavel) : null,
            'configuracao_nota_fiscal' => !empty($empresaNf)          ? json_encode($empresaNf)          : null
        ];

        //Inicia as operações de DB
        $this->db->transStart();
        try {
            $empresaModel->save($empresa);
            $this->db->transComplete();
            $this->nativeSession->setFlashData('success', lang('Success.default.cadastrada', ['Empresa']));
        } catch (Exception $e) {
            $this->nativeSession->setFlashData('error', lang('Errors.banco.validaInsercao'));
            return redirect()->back()->withInput();
        }

        return redirect()->to(base_url("empresa"));
    }

    /**
     * Altera o Registro
     * @param string $uuid UUID do Registro
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update(string $uuid): RedirectResponse
    {
        if (!$this->verificarUuid($uuid)) {
            $this->nativeSession->setFlashData('error', lang('Errors.geral.validaUuid'));
            return redirect()->to(base_url("empresa"));
        }

        $empresaModel = new EmpresaModel;
        $dadosRequest = convertEmptyToNull($this->request->getVar());
        $dadosUsuario = $this->nativeSession->get("usuario");

        $erros = $this->validarRequisicao($this->request, [
            // 'tipo_pessoa' => 'required|integer|in_list[1,2]',
            'razao_social' => 'permit_empty|string|min_length[3]|max_length[255]',
            'nome_fantasia' => 'required|string|min_length[3]|max_length[255]',
            'cpf_cnpj' => 'required|string|min_length[11]|max_length[18]',
            'email' => 'permit_empty|valid_email|max_length[255]',
            'email_financeiro' => 'permit_empty|valid_email|max_length[255]',
            'telefone' => [
                'rules' => 'permit_empty|checkTelefone',
                'errors' => ['checkTelefone' => 'Errors.geral.telefoneInvalido'],
            ],
            'telefone_adicional' => [
                'rules' => 'permit_empty|checkTelefone',
                'errors' => ['checkTelefone' => 'Errors.geral.telefoneInvalido'],
            ],
            'celular' => [
                'rules' => 'permit_empty|checkTelefone',
                'errors' => ['checkTelefone' => 'Errors.geral.telefoneInvalido'],
            ],
            'dia_pagamento' => [
                'rules' => 'permit_empty|checkDiasPagamento',
                'errors' => ['checkDiasPagamento' => 'Errors.empresa.diaPagamentoInvalido'],
            ],
            'cep' => [
                'rules' => 'required|checkCep',
                'errors' => ['checkCep' => 'Errors.geral.cepInvalido'],
            ],
            'rua' => 'required|string|max_length[255]',
            'numero' => 'required|integer|max_length[255]',
            'bairro' => 'required|string|max_length[255]',
            'complemento' => 'permit_empty|string|max_length[255]',
            'cidade' => 'required|string|max_length[255]',
            'uf' => 'required|string|max_length[255]',
            'responsavel_nome' => 'permit_empty|string|max_length[255]',
            'responsavel_cpf' => 'permit_empty|string|min_length[11]|max_length[18]',
            'responsavel_data_nascimento' => 'permit_empty|valid_date',
            'responsavel_rg' => 'permit_empty|string|max_length[255]',
            'responsavel_rg_orgao_emissor' => 'permit_empty|string|max_length[255]',
            'responsavel_cep' => [
                'rules' => 'permit_empty|checkCep',
                'errors' => ['checkCep' => 'Errors.geral.cepInvalido'],
            ],
            'responsavel_rua' => 'permit_empty|string|max_length[255]',
            'responsavel_numero' => 'permit_empty|integer|max_length[255]',
            'responsavel_bairro' => 'permit_empty|string|max_length[255]',
            'responsavel_complemento' => 'permit_empty|string|max_length[255]',
            'responsavel_cidade' => 'permit_empty|string|max_length[255]',
            'responsavel_uf' => 'permit_empty|string|max_length[255]',
            'possui_inscricao_estadual' => 'permit_empty|string|in_list[sim,nao,isento]',
            'inscricao_estadual' => 'permit_empty|string',
        ]);

        if (!empty($erros)) {
            $this->nativeSession->setFlashData('error', formataErros($erros));
            return redirect()->back()->withInput();
        }


        // JSONB de Dados do Endereço
        $empresaEndereco = [
            'cep'         => onlyNumber($dadosRequest['cep']),
            'rua'         => $dadosRequest['rua'],
            'numero'      => onlyNumber($dadosRequest['numero']),
            'bairro'      => $dadosRequest['bairro'],
            'complemento' => $dadosRequest['complemento'],
            'cidade'      => $dadosRequest['cidade'],
            'uf'          => $dadosRequest['uf']
        ];

        // JSONB de Dados do Responsavel
        $empresaResponsavel = [
            'nome'             => $dadosRequest['responsavel_nome'],
            'cpf'              => onlyNumber($dadosRequest['responsavel_cpf']),
            'data_nascimento'  => $dadosRequest['responsavel_data_nascimento'],
            'rg'               => onlyNumber($dadosRequest['responsavel_rg']),
            'rg_orgao_emissor' => $dadosRequest['responsavel_rg_orgao_emissor'],
            'cep'              => onlyNumber($dadosRequest['responsavel_cep']),
            'rua'              => $dadosRequest['responsavel_rua'],
            'numero'           => onlyNumber($dadosRequest['responsavel_numero']),
            'bairro'           => $dadosRequest['responsavel_bairro'],
            'complemento'      => $dadosRequest['responsavel_complemento'],
            'cidade'           => $dadosRequest['responsavel_cidade'],
            'uf'               => $dadosRequest['responsavel_uf']
        ];


        // JSONB de Dados de Nota Fiscal (NF e NFS)
        $empresaNf = [
            'possui_inscricao_estadual' => !empty($dadosRequest['possui_inscricao_estadual']) ? $dadosRequest['possui_inscricao_estadual'] : null,
            'inscricao_estadual'        => !empty($dadosRequest['inscricao_estadual']) ? onlyNumber($dadosRequest['inscricao_estadual']) : null,
        ];

        $empresaUpdate = [
            'usuario_alteracao'        => $dadosUsuario['codigo_usuario'],
            'alterado_em'           => "NOW()",
            'tipo_pessoa'              => onlyNumber($dadosRequest['tipo_pessoa']),
            'nome_fantasia'            => $dadosRequest['nome_fantasia'],
            'razao_social'             => $dadosRequest['razao_social'],
            'cpf_cnpj'                 => onlyNumber($dadosRequest['cpf_cnpj']),
            'telefone'                 => onlyNumber($dadosRequest['telefone']),
            'telefone_adicional'       => onlyNumber($dadosRequest['telefone_adicional']),
            'celular'                  => onlyNumber($dadosRequest['celular']),
            'email'                    => $dadosRequest['email'],
            'email_financeiro'         => $dadosRequest['email_financeiro'],
            'dia_pagamento'            => onlyNumber($dadosRequest['dia_pagamento']),
            'endereco'                 => !empty($empresaEndereco) ? json_encode($empresaEndereco) : null,
            'responsavel'              => !empty($empresaResponsavel) ? json_encode($empresaResponsavel) : null,
            'configuracao_nota_fiscal' => !empty($empresaNf) ? json_encode($empresaNf) : null
        ];

        //Inicia as operações de DB
        $this->db->transStart();
        try {
            $empresaModel->where($empresaModel->uuidColumn, $uuid)->set($empresaUpdate)->update();
            $this->db->transComplete();
            $this->nativeSession->setFlashData('success', lang('Success.default.atualizada', ['Empresa']));
        } catch (Exception $e) {
            $this->nativeSession->setFlashData('error', lang('Errors.banco.validaUpdate'));
            return redirect()->back()->withInput();
        }

        return redirect()->to(base_url("empresa"));
    }

    /**
     * Ativa um Registro
     * @param string $uuid Uuid do Registro
     * @return \CodeIgniter\HTTP\Response
     */
    public function enable(string $uuid): Response
    {
        if (!$this->verificarUuid($uuid)) {
            return $this->response->setJSON(['mensagem' => lang('Errors.geral.validaUuid')], 400);
        }

        $dadosUsuario = $this->nativeSession->get("usuario");
        $empresaModel = new EmpresaModel;

        $dadosEmpresa = [
            'alterado_em'        => "NOW()",
            'usuario_alteracao'  => $dadosUsuario['codigo_usuario'],
            'inativado_em'       => null,
            'usuario_inativacao' => null
        ];

        try {
            $empresaModel->where($empresaModel->uuidColumn, $uuid)->set($dadosEmpresa)->update();
        } catch (Exception $e) {
            return $this->response->setJSON(['mensagem' => lang('Errors.banco.validaUpdate')], 422);
        }

        return $this->response->setJSON(['mensagem' => lang('Success.default.ativada', ['Empresa'])], 202);
    }

    /**
     * Desativa um Registro
     * @param string $uuid Uuid do Registro
     * @return \CodeIgniter\HTTP\Response
     */
    public function disable(string $uuid): Response
    {
        if (!$this->verificarUuid($uuid)) {
            return $this->response->setJSON(['mensagem' => lang('Errors.geral.validaUuid')], 400);
        }

        $dadosUsuario = $this->nativeSession->get("usuario");
        $empresaModel = new EmpresaModel;

        try {
            $empresaModel->customSoftDelete($uuid, $dadosUsuario['codigo_usuario'], true);
        } catch (Exception $e) {
            return $this->response->setJSON(['mensagem' => lang('Errors.banco.validaUpdate')], 422);
        }

        return $this->response->setJSON(['mensagem' => lang('Success.default.inativada', ['Empresa'])], 202);
    }

    /**
     * Realiza as chamadas assincronas direto para a Model
     * @param string $function
     */
    public function backendCall(string $function)
    {
        try {
            $request = $this->request->getVar();
            return $this->response->setJSON((new EmpresaModel)->$function($request));
        } catch (Exception $e) {
            var_dump($e);
        }
    }
}
