<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\RedirectResponse;
use Exception;

use App\Models\Proprietario\ProprietarioModel;

class ProprietarioController extends BaseController
{
    //////////////////////////////////
    //                              //
    //      OPERAÇÕES DE BUSCA      //
    //                              //
    //////////////////////////////////

    /**
     * Exibe a Tela de proprietario
     * @return html
     */
    public function index()
    {
        return $this->template('proprietario', ['index', 'functions']);
    }

    /**
     * Exibe a Tela de Adicionar Registro
     * @return html
     */
    public function create()
    {
        return $this->template('proprietario', ['create', 'functions', 'modal']);
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
            return redirect()->to(base_url("proprietario"));
        }

        $proprietarioModel = new ProprietarioModel;
        $dados['proprietario'] = $proprietarioModel->get([$proprietarioModel->uuidColumn => $uuid], [], true);

        // Desestrutura os JSONB para preencher os campos no formulário
        $dados['proprietario']['endereco'] = json_decode($dados['proprietario']['endereco'], true);

        return $this->template('proprietario', ['edit', 'functions', 'modal'], $dados);
    }

    /**
     * Busca os registros para o Datagrid
     * @param int $status Verifica se a informação está ativa (1 ou 0)
     */
    public function getDataGrid(int $status)
    {
        $proprietarioModel = new ProprietarioModel;
        $dadosRequest = $this->request->getVar();
        $dadosRequest['status'] = $status;
        $data = $proprietarioModel->getDataGrid($dadosRequest);
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
        $proprietarioModel = new ProprietarioModel;
        $dadosRequest = convertEmptyToNull($this->request->getVar());
        $dadosUsuario = $this->nativeSession->get("usuario");
        $dadosEmpresa = $this->nativeSession->get("empresa");

        $erros = $this->validarRequisicao($this->request, [
            'tipo_pessoa' => 'required|integer|in_list[1,2]',
            'razao_social' => 'permit_empty|string|min_length[3]|max_length[255]',
            'nome_fantasia' => 'required|string|min_length[3]|max_length[255]',
            'cpf' => 'permit_empty|string|min_length[11]|max_length[18]',
            'cnpj' => 'permit_empty|string|min_length[11]|max_length[18]',
            'email' => 'permit_empty|valid_email|max_length[255]',
            'observacao' => 'permit_empty|string',
            'data_nascimento' => 'permit_empty|valid_date',
            'telefone' => [
                'rules' => 'permit_empty|checkTelefone',
                'errors' => ['checkTelefone' => 'Errors.geral.telefoneInvalido'],
            ],
            'celular' => [
                'rules' => 'permit_empty|checkTelefone',
                'errors' => ['checkTelefone' => 'Errors.geral.telefoneInvalido'],
            ],
            'cep' => [
                'rules' => 'permit_empty|checkCep',
                'errors' => ['checkCep' => 'Errors.geral.cepInvalido'],
            ],
            'rua' => 'permit_empty|string|max_length[255]',
            'numero' => 'permit_empty|integer|max_length[255]',
            'bairro' => 'permit_empty|string|max_length[255]',
            'complemento' => 'permit_empty|string|max_length[255]',
            'cidade' => 'permit_empty|string|max_length[255]',
            'uf' => 'permit_empty|string|max_length[255]'
        ]);

        if (!empty($erros)) {
            $this->nativeSession->setFlashData('error', formataErros($erros));
            return redirect()->back()->withInput();
        }
        if (!empty($dadosRequest['cpf']) && strlen($dadosRequest['cnpj']) == 14) {
            $dadosRequest['cpf_cnpj'] = $dadosRequest['cpf'];
        } else if (!empty($dadosRequest['cnpj']) && strlen($dadosRequest['cnpj']) == 18) {
            $dadosRequest['cpf_cnpj'] = $dadosRequest['cnpj'];
        } else {
            $this->nativeSession->setFlashData('error', 'CPF ou CNPJ Invalido.');
            return redirect()->back()->withInput();
        }
        if (!empty($dadosRequest['cpf_cnpj'])) {
            $proprietarioValida = $proprietarioModel->get(['cpf_cnpj' => onlyNumber($dadosRequest['cpf_cnpj'])]);
            if (!empty($proprietarioValida)) {
                $this->nativeSession->setFlashData('error', 'CPF ou CNPJ Já Cadastrado.');
                return redirect()->back()->withInput();
            }
        } else {
            $this->nativeSession->setFlashData('error', 'Preencha o CPF ou CNPJ.');
            return redirect()->back()->withInput();
        }
        // JSONB de Dados do Endereço
        $proprietarioEndereco = [
            'cep'         => !empty($dadosRequest['cep'])         ? onlyNumber($dadosRequest['cep'])    : '',
            'rua'         => !empty($dadosRequest['rua'])         ? $dadosRequest['rua']                : '',
            'numero'      => !empty($dadosRequest['numero'])      ? onlyNumber($dadosRequest['numero']) : '',
            'bairro'      => !empty($dadosRequest['bairro'])      ? $dadosRequest['bairro']             : '',
            'complemento' => !empty($dadosRequest['complemento']) ? $dadosRequest['complemento']        : '',
            'cidade'      => !empty($dadosRequest['cidade'])      ? $dadosRequest['cidade']             : '',
            'uf'          => !empty($dadosRequest['uf'])          ? $dadosRequest['uf']                 : ''
        ];

        $proprietario = [
            'codigo_empresa'  => $dadosEmpresa['codigo_empresa'],
            'tipo_pessoa'     => onlyNumber($dadosRequest['tipo_pessoa']),
            'nome_fantasia'   => $dadosRequest['nome_fantasia'],
            'razao_social'    => $dadosRequest['razao_social'],
            'cpf_cnpj'        => onlyNumber($dadosRequest['cpf_cnpj']),
            'telefone'        => onlyNumber($dadosRequest['telefone']),
            'celular'         => onlyNumber($dadosRequest['celular']),
            'email'           => $dadosRequest['email'],
            'observacao'      => $dadosRequest['observacao'],
            'data_nascimento' => !empty($dadosRequest['data_nascimento'] ? $dadosRequest['data_nascimento'] : null),
            'endereco'        => !empty($proprietarioEndereco) ? json_encode($proprietarioEndereco) : null,
        ];

        //Inicia as operações de DB
        $this->db->transStart();
        try {
            $proprietarioModel->save($proprietario);
            $this->db->transComplete();
            $this->nativeSession->setFlashData('success', lang('Success.default.cadastrado', ['Proprietário']));
        } catch (Exception $e) {
            $this->nativeSession->setFlashData('error', lang('Errors.banco.validaInsercao'));
            return redirect()->back()->withInput();
        }

        return redirect()->to(base_url("proprietario"));
    }

    /**
     * Realiza o Cadastro do Registro
     * @return \CodeIgniter\HTTP\Response
     */
    public function storeSimplificado(): Response
    {
        $proprietarioModel = new ProprietarioModel;
        $dadosRequest = convertEmptyToNull($this->request->getVar());
        $dadosEmpresa = $this->nativeSession->get("empresa");

        $erros = $this->validarRequisicao($this->request, [
            'nome_fantasia' => 'required|string|min_length[3]|max_length[255]',
            'cpf' => 'permit_empty|string|min_length[11]|max_length[18]',
            'cnpj' => 'permit_empty|string|min_length[11]|max_length[18]',
            'email' => 'permit_empty|valid_email|max_length[255]',
            'data_nascimento' => 'permit_empty|valid_date',
            'telefone' => [
                'rules' => 'permit_empty|checkTelefone',
                'errors' => ['checkTelefone' => 'Errors.geral.telefoneInvalido'],
            ],
            'celular' => [
                'rules' => 'permit_empty|checkTelefone',
                'errors' => ['checkTelefone' => 'Errors.geral.telefoneInvalido'],
            ]
        ]);

        if (!empty($erros)) {
            return $this->response->setJSON(['mensagem' => formataErros($erros)], 422);
        }
        if (!empty($erros)) {
            return $this->response->setJSON(['mensagem' => formataErros($erros)], 422);
        }

        if (!empty($dadosRequest['cpf']) && strlen($dadosRequest['cnpj']) == 14) {
            $dadosRequest['cpf_cnpj'] = $dadosRequest['cpf'];
        } else if (!empty($dadosRequest['cnpj']) && strlen($dadosRequest['cnpj']) == 18) {
            $dadosRequest['cpf_cnpj'] = $dadosRequest['cnpj'];
        } else {
            return $this->response->setJSON(['mensagem' => 'CPF ou CNPJ Invalido.'], 422);
        }
        if ($dadosRequest['cpf_cnpj']) {

            $proprietarioValida = $proprietarioModel->get(['cpf_cnpj' => onlyNumber($dadosRequest['cpf_cnpj'])]);
            if (!empty($proprietarioValida)) {
                return $this->response->setJSON(['mensagem' => 'CPF ou CNPJ Já Cadastrado.'], 422);
            }
        }
        // JSONB de Dados do Endereço
        $proprietarioEndereco = [
            'cep'         => !empty($dadosRequest['cep'])         ? onlyNumber($dadosRequest['cep'])    : '',
            'rua'         => !empty($dadosRequest['rua'])         ? $dadosRequest['rua']                : '',
            'numero'      => !empty($dadosRequest['numero'])      ? onlyNumber($dadosRequest['numero']) : '',
            'bairro'      => !empty($dadosRequest['bairro'])      ? $dadosRequest['bairro']             : '',
            'complemento' => !empty($dadosRequest['complemento']) ? $dadosRequest['complemento']        : '',
            'cidade'      => !empty($dadosRequest['cidade'])      ? $dadosRequest['cidade']             : '',
            'uf'          => !empty($dadosRequest['uf'])          ? $dadosRequest['uf']                 : ''
        ];

        $proprietario = [
            'codigo_empresa'  => $dadosEmpresa['codigo_empresa'],
            'tipo_pessoa'     => strlen($dadosRequest['cpf_cnpj']) == 14 ? 2 : 1,
            'nome_fantasia'   => $dadosRequest['nome_fantasia'],
            'razao_social'    => $dadosRequest['nome_fantasia'],
            'cpf_cnpj'        => onlyNumber($dadosRequest['cpf_cnpj']),
            'telefone'        => onlyNumber($dadosRequest['telefone']),
            'celular'         => onlyNumber($dadosRequest['celular']),
            'email'           => $dadosRequest['email'],
            'data_nascimento' => !empty($dadosRequest['data_nascimento']) ? $dadosRequest['data_nascimento'] : null,
            'endereco'        => !empty($proprietarioEndereco) ? json_encode($proprietarioEndereco) : null,
        ];

        //Inicia as operações de DB
        $this->db->transStart();
        try {
            $proprietarioModel->save($proprietario);
            $proprietarioId = $proprietarioModel->insertID('proprietario_codigo_proprietario_seq');
            $this->db->transComplete();
        } catch (Exception $e) {
            return $this->response->setJSON(['mensagem' => lang('Errors.banco.validaInsercao')], 422);
        }

        return $this->response->setJSON(['mensagem' => lang('Success.default.cadastrado', ['Proprietário']), 'proprietario' => $proprietarioId], 200);
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
            return redirect()->to(base_url("proprietario"));
        }

        $proprietarioModel = new ProprietarioModel;
        $dadosRequest = convertEmptyToNull($this->request->getVar());
        $dadosUsuario = $this->nativeSession->get("usuario");

        $erros = $this->validarRequisicao($this->request, [
            'razao_social' => 'permit_empty|string|min_length[3]|max_length[255]',
            'nome_fantasia' => 'required|string|min_length[3]|max_length[255]',
            'cpf' => 'permit_empty|string|min_length[11]|max_length[18]',
            'cnpj' => 'permit_empty|string|min_length[11]|max_length[18]',
            'email' => 'permit_empty|valid_email|max_length[255]',
            'observacao' => 'permit_empty|string',
            'data_nascimento' => 'permit_empty|valid_date',
            'telefone' => [
                'rules' => 'permit_empty|checkTelefone',
                'errors' => ['checkTelefone' => 'Errors.geral.telefoneInvalido'],
            ],
            'celular' => [
                'rules' => 'permit_empty|checkTelefone',
                'errors' => ['checkTelefone' => 'Errors.geral.telefoneInvalido'],
            ],
            'cep' => [
                'rules' => 'permit_empty|checkCep',
                'errors' => ['checkCep' => 'Errors.geral.cepInvalido'],
            ],
            'rua' => 'permit_empty|string|max_length[255]',
            'numero' => 'permit_empty|integer|max_length[255]',
            'bairro' => 'permit_empty|string|max_length[255]',
            'complemento' => 'permit_empty|string|max_length[255]',
            'cidade' => 'permit_empty|string|max_length[255]',
            'uf' => 'permit_empty|string|max_length[255]'
        ]);

        if (!empty($erros)) {
            $this->nativeSession->setFlashData('error', formataErros($erros));
            return redirect()->back()->withInput();
        }
        if (!empty($dadosRequest['cpf']) && strlen($dadosRequest['cnpj']) == 14) {
            $dadosRequest['cpf_cnpj'] = $dadosRequest['cpf'];
        } else if (!empty($dadosRequest['cnpj']) && strlen($dadosRequest['cnpj']) == 18) {
            $dadosRequest['cpf_cnpj'] = $dadosRequest['cnpj'];
        } else {
            $this->nativeSession->setFlashData('error', 'CPF ou CNPJ Invalido.');
            return redirect()->back()->withInput();
        }

        if (!empty($dadosRequest['cpf_cnpj'])) {
            $proprietarioValida = $proprietarioModel->get(['cpf_cnpj' => onlyNumber($dadosRequest['cpf_cnpj']), 'uuid_proprietario !=' => $uuid]);
            if (!empty($proprietarioValida)) {
                $this->nativeSession->setFlashData('error', 'CPF ou CNPJ Já Cadastrado.');
                return redirect()->back()->withInput();
            }
        } else {
            $this->nativeSession->setFlashData('error', 'Preencha o CPF ou CNPJ.');
            return redirect()->back()->withInput();
        }
        // JSONB de Dados do Endereço
        $proprietarioEndereco = [
            'cep'         => !empty($dadosRequest['cep'])         ? onlyNumber($dadosRequest['cep'])    : '',
            'rua'         => !empty($dadosRequest['rua'])         ? $dadosRequest['rua']                : '',
            'numero'      => !empty($dadosRequest['numero'])      ? onlyNumber($dadosRequest['numero']) : '',
            'bairro'      => !empty($dadosRequest['bairro'])      ? $dadosRequest['bairro']             : '',
            'complemento' => !empty($dadosRequest['complemento']) ? $dadosRequest['complemento']        : '',
            'cidade'      => !empty($dadosRequest['cidade'])      ? $dadosRequest['cidade']             : '',
            'uf'          => !empty($dadosRequest['uf'])          ? $dadosRequest['uf']                 : ''
        ];

        $proprietarioUpdate = [
            'nome_fantasia'     => $dadosRequest['nome_fantasia'],
            'razao_social'      => !empty($dadosRequest['razao_social']) ? $dadosRequest['razao_social'] : $dadosRequest['nome_fantasia'],
            'cpf_cnpj'          => onlyNumber($dadosRequest['cpf_cnpj']),
            'telefone'          => onlyNumber($dadosRequest['telefone']),
            'celular'           => onlyNumber($dadosRequest['celular']),
            'email'             => $dadosRequest['email'],
            'observacao'        => $dadosRequest['observacao'],
            'data_nascimento' => !empty($dadosRequest['data_nascimento'] ? $dadosRequest['data_nascimento'] : null),
            'endereco'          => !empty($proprietarioEndereco) ? json_encode($proprietarioEndereco) : null,
        ];

        //Inicia as operações de DB
        $this->db->transStart();
        try {
            $proprietarioModel->where($proprietarioModel->uuidColumn, $uuid)->set($proprietarioUpdate)->update();
            $this->db->transComplete();
            $this->nativeSession->setFlashData('success', lang('Success.default.atualizado', ['Proprietario']));
        } catch (Exception $e) {
            $this->nativeSession->setFlashData('error', lang('Errors.banco.validaUpdate'));
            return redirect()->back()->withInput();
        }

        return redirect()->to(base_url("proprietario"));
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
        $proprietarioModel = new ProprietarioModel;

        $dadosProprietario = [
            'alterado_em'        => "NOW()",
            'inativado_em'       => null,
        ];

        try {
            $proprietarioModel->where($proprietarioModel->uuidColumn, $uuid)->set($dadosProprietario)->update();
        } catch (Exception $e) {
            return $this->response->setJSON(['mensagem' => lang('Errors.banco.validaUpdate')], 422);
        }

        return $this->response->setJSON(['mensagem' => lang('Success.default.ativado', ['Proprietário'])], 202);
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
        $proprietarioModel = new ProprietarioModel;

        try {
            $proprietarioModel->customSoftDelete($uuid, $dadosUsuario['codigo_usuario'], true);
        } catch (Exception $e) {
            return $this->response->setJSON(['mensagem' => lang('Errors.banco.validaUpdate')], 422);
        }

        return $this->response->setJSON(['mensagem' => lang('Success.default.inativado', ['Proprietário'])], 202);
    }

    /**
     * Realiza as chamadas assincronas direto para a Model
     * @param string $function
     */
    public function backendCall(string $function)
    {
        try {
            $request = $this->request->getVar();
            return $this->response->setJSON((new ProprietarioModel)->$function($request));
        } catch (Exception $e) {
            var_dump($e);
        }
    }
}
