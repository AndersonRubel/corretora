<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\RedirectResponse;
use Exception;

use App\Models\Cliente\ClienteModel;
use App\Models\Cliente\ClienteEnderecoModel;
use App\Models\Cliente\ClienteExtratoModel;

class ClienteController extends BaseController
{
    //////////////////////////////////
    //                              //
    //      OPERAÇÕES DE BUSCA      //
    //                              //
    //////////////////////////////////

    /**
     * Exibe a Tela de Cliente
     * @return html
     */
    public function index()
    {
        return $this->template('cliente', ['index', 'modal', 'functions']);
    }

    /**
     * Exibe a Tela de Adicionar Registro
     * @return html
     */
    public function create()
    {
        return $this->template('cliente', ['create', 'functions']);
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
        $clienteEnderecoModel = new ClienteEnderecoModel;
        $dados['cliente'] = $clienteModel->get([$clienteModel->uuidColumn => $uuid], [], true);
        $dados['cliente']['endereco'] = $clienteEnderecoModel->get(['codigo_cliente' => $dados['cliente']['codigo_cliente']], [], false);

        return $this->template('cliente', ['edit', 'functions'], $dados);
    }

    /**
     * Busca os registros para o Datagrid
     * @param int $status Verifica se a informação está ativa (1 ou 0)
     */
    public function getDataGrid(int $status)
    {
        try {
            $clienteModel = new ClienteModel;
            $dadosRequest = $this->request->getVar();
            $dadosRequest['status'] = $status;
            $data = $clienteModel->getDataGrid($dadosRequest);
            return $this->responseDataGrid($data, $dadosRequest);
        } catch (Exception $e) {
            var_dump($e);
        }
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
        $clienteEnderecoModel = new ClienteEnderecoModel;
        $dadosRequest = convertEmptyToNull($this->request->getVar());
        $dadosUsuario = $this->nativeSession->get("usuario");
        $dadosEmpresa = $this->nativeSession->get("empresa");

        $erros = $this->validarRequisicao($this->request, [
            'tipo_pessoa' => 'required|integer|in_list[1,2]',
            'razao_social' => 'permit_empty|string|min_length[3]|max_length[255]',
            'nome_fantasia' => 'required|string|min_length[3]|max_length[255]',
            'cpf_cnpj' => 'permit_empty|string|min_length[11]|max_length[18]',
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
            ]
        ]);

        if (!empty($erros)) {
            $this->nativeSession->setFlashData('error', formataErros($erros));
            return redirect()->back()->withInput();
        }
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
            'data_nascimento' => $dadosRequest['data_nascimento'],
        ];

        //Inicia as operações de DB
        $this->db->transStart();
        try {
            $clienteModel->save($cliente);
            $clienteId = $clienteModel->insertID('cliente_codigo_cliente_seq');

            if (!empty($dadosRequest['endereco']) && !empty($dadosRequest['endereco']['cep'])) {
                // Percorre os endereços preenchidos e salva
                foreach ($dadosRequest['endereco']['cep'] as $key => $value) {
                    $clienteEndereco = [
                        'codigo_empresa'  => $dadosEmpresa['codigo_empresa'],
                        'codigo_cliente'  => $clienteId,
                        'cep'             => onlyNumber($dadosRequest['endereco']['cep'][$key]),
                        'rua'             => $dadosRequest['endereco']['rua'][$key],
                        'numero'          => onlyNumber($dadosRequest['endereco']['numero'][$key]),
                        'bairro'          => $dadosRequest['endereco']['bairro'][$key],
                        'complemento'     => $dadosRequest['endereco']['complemento'][$key],
                        'cidade'          => $dadosRequest['endereco']['cidade'][$key],
                        'uf'              => $dadosRequest['endereco']['uf'][$key]
                    ];

                    $clienteEnderecoModel->save($clienteEndereco);
                }
            }

            $this->db->transComplete();
            $this->nativeSession->setFlashData('success', lang('Success.default.cadastrado', ['Cliente']));
        } catch (Exception $e) {
            $this->nativeSession->setFlashData('error', lang('Errors.banco.validaInsercao'));
            return redirect()->back()->withInput();
        }

        return redirect()->to(base_url("cliente"));
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
        $clienteEnderecoModel = new ClienteEnderecoModel;
        $dadosRequest = convertEmptyToNull($this->request->getVar());
        $dadosUsuario = $this->nativeSession->get("usuario");
        $dadosEmpresa = $this->nativeSession->get("empresa");

        $erros = $this->validarRequisicao($this->request, [
            'razao_social' => 'permit_empty|string|min_length[3]|max_length[255]',
            'nome_fantasia' => 'required|string|min_length[3]|max_length[255]',
            'cpf_cnpj' => 'permit_empty|string|min_length[11]|max_length[18]',
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
            ]
        ]);

        if (!empty($erros)) {
            $this->nativeSession->setFlashData('error', formataErros($erros));
            return redirect()->back()->withInput();
        }

        $clienteUpdate = [
            'alterado_em'       => "NOW()",
            'nome_fantasia'     => $dadosRequest['nome_fantasia'],
            'razao_social'      => !empty($dadosRequest['razao_social']) ? $dadosRequest['razao_social'] : $dadosRequest['nome_fantasia'],
            'cpf_cnpj'          => onlyNumber($dadosRequest['cpf_cnpj']),
            'telefone'          => onlyNumber($dadosRequest['telefone']),
            'celular'           => onlyNumber($dadosRequest['celular']),
            'email'             => $dadosRequest['email'],
            'observacao'        => $dadosRequest['observacao'],
            'data_nascimento'   => $dadosRequest['data_nascimento'],
        ];

        $cliente = $clienteModel->get([$clienteModel->uuidColumn => $uuid], ['codigo_cliente'], true);

        //Inicia as operações de DB
        $this->db->transStart();
        try {
            $clienteModel->where($clienteModel->uuidColumn, $uuid)->set($clienteUpdate)->update();

            if (!empty($dadosRequest['endereco']) && !empty($dadosRequest['endereco']['cep'])) {
                // Percorre os endereços preenchidos e salva
                foreach ($dadosRequest['endereco']['cep'] as $key => $value) {
                    $clienteEndereco = [
                        'codigo_empresa'  => $dadosEmpresa['codigo_empresa'],
                        'codigo_cliente'  => $cliente['codigo_cliente'],
                        'cep'             => onlyNumber($dadosRequest['endereco']['cep'][$key]),
                        'rua'             => $dadosRequest['endereco']['rua'][$key],
                        'numero'          => onlyNumber($dadosRequest['endereco']['numero'][$key]),
                        'bairro'          => $dadosRequest['endereco']['bairro'][$key],
                        'complemento'     => $dadosRequest['endereco']['complemento'][$key],
                        'cidade'          => $dadosRequest['endereco']['cidade'][$key],
                        'uf'              => $dadosRequest['endereco']['uf'][$key]
                    ];

                    // Verifica se tem que criar ou editar o registro
                    if (!empty($dadosRequest['endereco']['uuid_cliente_endereco'][$key])) {
                        // Edita o registro

                        // Busca o Código
                        $endereco = $clienteEnderecoModel->get([$clienteEnderecoModel->uuidColumn => $dadosRequest['endereco']['uuid_cliente_endereco'][$key]], ['codigo_cliente_endereco'], true);
                        $clienteEndereco['codigo_cliente_endereco'] = $endereco['codigo_cliente_endereco'];
                        $clienteEndereco['alterado_em']             = "NOW()";
                        $clienteEnderecoModel->save($clienteEndereco);
                    } else {
                        // Cria o registro
                        $clienteEnderecoModel->save($clienteEndereco);
                    }
                }
            }

            $this->db->transComplete();
            $this->nativeSession->setFlashData('success', lang('Success.default.atualizado', ['Cliente']));
        } catch (Exception $e) {
            print_r($e);
            die();
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
        if (!$this->verificarUuid($uuid)) {
            return $this->response->setJSON(['mensagem' => lang('Errors.geral.validaUuid')], 400);
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
     * Desativa um Endereço do Registro
     * @param string $uuid Uuid do Registro
     * @return \CodeIgniter\HTTP\Response
     */
    public function disableEndereco(string $uuid): Response
    {
        if (!$this->verificarUuid($uuid)) {
            return $this->response->setJSON(['mensagem' => lang('Errors.geral.validaUuid')], 400);
        }

        $dadosUsuario = $this->nativeSession->get("usuario");
        $clienteEnderecoModel = new ClienteEnderecoModel;

        try {
            $clienteEnderecoModel->customSoftDelete($uuid, $dadosUsuario['codigo_usuario'], true);
        } catch (Exception $e) {
            return $this->response->setJSON(['mensagem' => lang('Errors.banco.validaUpdate')], 422);
        }

        return $this->response->setJSON(['mensagem' => lang('Success.default.inativado', ['Endereço'])], 202);
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
