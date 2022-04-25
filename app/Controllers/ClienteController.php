<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\RedirectResponse;
use Exception;

use App\Models\Cliente\ClienteModel;

class ClienteController extends BaseController
{
    //////////////////////////////////
    //                              //
    //      OPERAÇÕES DE BUSCA      //
    //                              //
    //////////////////////////////////

    /**
     * Exibe a Tela de cliente
     * @return html
     */
    public function index()
    {
        return $this->template('cliente', ['index', 'functions']);
    }

    /**
     * Exibe a Tela de Adicionar Registro
     * @return html
     */
    public function create()
    {
        return $this->template('cliente', ['create', 'functions', 'modal']);
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
            return redirect()->to(base_url("cliente"));
        }

        $clienteModel = new ClienteModel;
        $dados['cliente'] = $clienteModel->get([$clienteModel->uuidColumn => $uuid], [], true);

        // Desestrutura os JSONB para preencher os campos no formulário
        $dados['cliente']['endereco'] = json_decode($dados['cliente']['endereco'], true);

        return $this->template('cliente', ['edit', 'functions', 'modal'], $dados);
    }

    /**
     * Busca os registros para o Datagrid
     * @param int $status Verifica se a informação está ativa (1 ou 0)
     */
    public function getDataGrid(int $status)
    {
        $clienteModel = new ClienteModel;
        $dadosRequest = $this->request->getVar();
        $dadosRequest['status'] = $status;
        $data = $clienteModel->getDataGrid($dadosRequest);
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
        $clienteModel = new ClienteModel;
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
            $clienteValida = $clienteModel->get(['cpf_cnpj' => onlyNumber($dadosRequest['cpf_cnpj'])]);
            if (!empty($clienteValida)) {
                $this->nativeSession->setFlashData('error', 'CPF ou CNPJ Já Cadastrado.');
                return redirect()->back()->withInput();
            }
        } else {
            $this->nativeSession->setFlashData('error', 'Preencha o CPF ou CNPJ.');
            return redirect()->back()->withInput();
        }

        // JSONB de Dados do Endereço
        $clienteEndereco = [
            'cep'         => !empty($dadosRequest['cep'])         ? onlyNumber($dadosRequest['cep'])    : '',
            'rua'         => !empty($dadosRequest['rua'])         ? $dadosRequest['rua']                : '',
            'numero'      => !empty($dadosRequest['numero'])      ? onlyNumber($dadosRequest['numero']) : '',
            'bairro'      => !empty($dadosRequest['bairro'])      ? $dadosRequest['bairro']             : '',
            'complemento' => !empty($dadosRequest['complemento']) ? $dadosRequest['complemento']        : '',
            'cidade'      => !empty($dadosRequest['cidade'])      ? $dadosRequest['cidade']             : '',
            'uf'          => !empty($dadosRequest['uf'])          ? $dadosRequest['uf']                 : ''
        ];

        $cliente = [
            'codigo_empresa'  => $dadosEmpresa['codigo_empresa'],
            'tipo_pessoa'     => onlyNumber($dadosRequest['tipo_pessoa']),
            'nome_fantasia'   => $dadosRequest['nome_fantasia'],
            'razao_social'    => $dadosRequest['razao_social'],
            'cpf_cnpj'        => onlyNumber($dadosRequest['cpf_cnpj']),
            'telefone'        => onlyNumber($dadosRequest['telefone']),
            'celular'         => onlyNumber($dadosRequest['celular']),
            'email'           => $dadosRequest['email'],
            'observacao'      => $dadosRequest['observacao'],
            'data_nascimento' => !empty($dadosRequest['data_nascimento']) ? $dadosRequest['data_nascimento'] : null,
            'endereco'        => !empty($clienteEndereco) ? json_encode($clienteEndereco) : null,
        ];

        //Inicia as operações de DB
        $this->db->transStart();
        try {
            $clienteModel->save($cliente);
            $this->db->transComplete();
            $this->nativeSession->setFlashData('success', lang('Success.default.cadastrado', ['Cliente']));
        } catch (Exception $e) {
            $this->nativeSession->setFlashData('error', lang('Errors.banco.validaInsercao'));
            return redirect()->back()->withInput();
        }

        return redirect()->to(base_url("cliente"));
    }
    /**
     * Realiza o Cadastro do Registro
     * @return \CodeIgniter\HTTP\Response
     */
    public function storeSimplificado(): Response
    {
        $clienteModel = new ClienteModel;
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

        if (!empty($dadosRequest['cpf']) && strlen($dadosRequest['cnpj']) == 14) {
            $dadosRequest['cpf_cnpj'] = $dadosRequest['cpf'];
        } else if (!empty($dadosRequest['cnpj']) && strlen($dadosRequest['cnpj']) == 18) {
            $dadosRequest['cpf_cnpj'] = $dadosRequest['cnpj'];
        } else {
            return $this->response->setJSON(['mensagem' => 'CPF ou CNPJ Invalido.'], 422);
        }
        if ($dadosRequest['cpf_cnpj']) {

            $clienteValida = $clienteModel->get(['cpf_cnpj' => onlyNumber($dadosRequest['cpf_cnpj'])]);
            if (!empty($clienteValida)) {
                return $this->response->setJSON(['mensagem' => 'CPF ou CNPJ Já Cadastrado.'], 422);
            }
        }


        // JSONB de Dados do Endereço
        $clienteEndereco = [
            'cep'         => !empty($dadosRequest['cep'])         ? onlyNumber($dadosRequest['cep'])    : '',
            'rua'         => !empty($dadosRequest['rua'])         ? $dadosRequest['rua']                : '',
            'numero'      => !empty($dadosRequest['numero'])      ? onlyNumber($dadosRequest['numero']) : '',
            'bairro'      => !empty($dadosRequest['bairro'])      ? $dadosRequest['bairro']             : '',
            'complemento' => !empty($dadosRequest['complemento']) ? $dadosRequest['complemento']        : '',
            'cidade'      => !empty($dadosRequest['cidade'])      ? $dadosRequest['cidade']             : '',
            'uf'          => !empty($dadosRequest['uf'])          ? $dadosRequest['uf']                 : ''
        ];

        $cliente = [
            'codigo_empresa'  => $dadosEmpresa['codigo_empresa'],
            'tipo_pessoa'     => strlen($dadosRequest['cpf_cnpj']) == 14 ? 2 : 1,
            'nome_fantasia'   => $dadosRequest['nome_fantasia'],
            'razao_social'    => $dadosRequest['nome_fantasia'],
            'cpf_cnpj'        => onlyNumber($dadosRequest['cpf_cnpj']),
            'telefone'        => onlyNumber($dadosRequest['telefone']),
            'celular'         => onlyNumber($dadosRequest['celular']),
            'email'           => $dadosRequest['email'],
            'data_nascimento' => $dadosRequest['data_nascimento'],
            'endereco'        => !empty($clienteEndereco) ? json_encode($clienteEndereco) : null,
        ];

        //Inicia as operações de DB
        $this->db->transStart();
        try {
            $clienteModel->save($cliente);
            $clienteId = $clienteModel->insertID('cliente_codigo_cliente_seq');
            $this->db->transComplete();
        } catch (Exception $e) {
            return $this->response->setJSON(['mensagem' => lang('Errors.banco.validaInsercao')], 422);
        }

        return $this->response->setJSON(['mensagem' => lang('Success.default.cadastrado', ['Cliente']), 'cliente' => $clienteId], 200);
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
            return redirect()->to(base_url("cliente"));
        }

        $clienteModel = new ClienteModel;
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
            $clienteValida = $clienteModel->get(['cpf_cnpj' => onlyNumber($dadosRequest['cpf_cnpj']), 'uuid_cliente !=' => $uuid]);
            if (!empty($clienteValida)) {
                $this->nativeSession->setFlashData('error', 'CPF ou CNPJ Já Cadastrado.');
                return redirect()->back()->withInput();
            }
        } else {
            $this->nativeSession->setFlashData('error', 'Preencha o CPF ou CNPJ.');
            return redirect()->back()->withInput();
        }
        if ($dadosRequest['cpf_cnpj']) {
            $cliente = $clienteModel->get(['cpf_cnpj' => onlyNumber($dadosRequest['cpf_cnpj']), 'uuid_cliente !=' => $uuid]);
            if (!empty($cliente)) {
                $this->nativeSession->setFlashData('error', 'CPF ou CNPJ Já Cadastrado.');
                return redirect()->back()->withInput();
            }
        }
        // JSONB de Dados do Endereço
        $clienteEndereco = [
            'cep'         => !empty($dadosRequest['cep'])         ? onlyNumber($dadosRequest['cep'])    : '',
            'rua'         => !empty($dadosRequest['rua'])         ? $dadosRequest['rua']                : '',
            'numero'      => !empty($dadosRequest['numero'])      ? onlyNumber($dadosRequest['numero']) : '',
            'bairro'      => !empty($dadosRequest['bairro'])      ? $dadosRequest['bairro']             : '',
            'complemento' => !empty($dadosRequest['complemento']) ? $dadosRequest['complemento']        : '',
            'cidade'      => !empty($dadosRequest['cidade'])      ? $dadosRequest['cidade']             : '',
            'uf'          => !empty($dadosRequest['uf'])          ? $dadosRequest['uf']                 : ''
        ];

        $clienteUpdate = [
            'nome_fantasia'     => $dadosRequest['nome_fantasia'],
            'razao_social'      => !empty($dadosRequest['razao_social']) ? $dadosRequest['razao_social'] : $dadosRequest['nome_fantasia'],
            'cpf_cnpj'          => onlyNumber($dadosRequest['cpf_cnpj']),
            'telefone'          => onlyNumber($dadosRequest['telefone']),
            'celular'           => onlyNumber($dadosRequest['celular']),
            'email'             => $dadosRequest['email'],
            'observacao'        => $dadosRequest['observacao'],
            'data_nascimento' => !empty($dadosRequest['data_nascimento']) ? $dadosRequest['data_nascimento'] : null,
            'endereco'          => !empty($clienteEndereco) ? json_encode($clienteEndereco) : null,
        ];

        //Inicia as operações de DB
        $this->db->transStart();
        try {
            $clienteModel->where($clienteModel->uuidColumn, $uuid)->set($clienteUpdate)->update();
            $this->db->transComplete();
            $this->nativeSession->setFlashData('success', lang('Success.default.atualizado', ['Cliente']));
        } catch (Exception $e) {
            $this->nativeSession->setFlashData('error', lang('Errors.banco.validaUpdate'));
            return redirect()->back()->withInput();
        }

        return redirect()->to(base_url("cliente"));
    }

    /**
     * Ativa um Registro
     * @param string $uuid Uuid do Registro
     * @return \CodeIgniter\HTTP\Response
     */
    public function enable(string $uuid): Response
    {
        $clienteModel = new ClienteModel;
        if (!$this->verificarUuid($uuid)) {
            return $this->response->setJSON(['mensagem' => lang('Errors.geral.validaUuid')], 400);
        }

        $cliente = $clienteModel->get(['uuid_cliente' => $uuid], ['cpf_cnpj'], true, [], false, true);
        $existe = $clienteModel->get(['cpf_cnpj' => onlyNumber($cliente['cpf_cnpj']), 'uuid_cliente !=' => $uuid]);

        if (!empty($existe)) {
            return $this->response->setJSON(['mensagem' => lang('O CPF ou CNPJ está cadastrado em um usuário ativo. ')], 422);
        }
        $dadosUsuario = $this->nativeSession->get("usuario");
        $clienteModel = new ClienteModel;

        $dadosCliente = [
            'alterado_em'        => "NOW()",
            'inativado_em'       => null,
        ];

        try {
            $clienteModel->where($clienteModel->uuidColumn, $uuid)->set($dadosCliente)->update();
        } catch (Exception $e) {
            return $this->response->setJSON(['mensagem' => lang('Errors.banco.validaUpdate')], 422);
        }

        return $this->response->setJSON(['mensagem' => lang('Success.default.ativado', ['Cliente'])], 202);
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
        $clienteModel = new ClienteModel;

        try {
            $clienteModel->customSoftDelete($uuid, $dadosUsuario['codigo_usuario'], true);
        } catch (Exception $e) {
            return $this->response->setJSON(['mensagem' => lang('Errors.banco.validaUpdate')], 422);
        }

        return $this->response->setJSON(['mensagem' => lang('Success.default.inativado', ['Cliente'])], 202);
    }

    /**
     * Realiza as chamadas assincronas direto para a Model
     * @param string $function
     */
    public function backendCall(string $function)
    {
        try {
            $request = $this->request->getVar();
            return $this->response->setJSON((new ClienteModel)->$function($request));
        } catch (Exception $e) {
            var_dump($e);
        }
    }
}
