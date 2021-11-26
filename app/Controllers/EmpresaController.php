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
     * Exibe a Tela de empresa
     * @return html
     */
    public function index()
    {
        return $this->template('empresa', ['index', 'functions']);
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
        $dadosEmpresa = $this->nativeSession->get("empresa");

        $erros = $this->validarRequisicao($this->request, [
            'tipo_pessoa' => 'required|integer|in_list[1,2]',
            'razao_social' => 'permit_empty|string|min_length[3]|max_length[255]',
            'nome_fantasia' => 'required|string|min_length[3]|max_length[255]',
            'cpf_cnpj' => 'required|string|min_length[11]|max_length[18]',
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

        $empresa = [
            'tipo_pessoa'     => onlyNumber($dadosRequest['tipo_pessoa']),
            'nome_fantasia'   => $dadosRequest['nome_fantasia'],
            'razao_social'    => $dadosRequest['razao_social'],
            'cpf_cnpj'        => onlyNumber($dadosRequest['cpf_cnpj']),
            'telefone'        => onlyNumber($dadosRequest['telefone']),
            'celular'         => onlyNumber($dadosRequest['celular']),
            'email'           => $dadosRequest['email'],
            'observacao'      => $dadosRequest['observacao'],
            'data_nascimento' => $dadosRequest['data_nascimento'],
            'endereco'        => !empty($empresaEndereco) ? json_encode($empresaEndereco) : null,
        ];
        $empresaModel->save($empresa);
        //Inicia as operações de DB
        $this->db->transStart();
        try {

            $this->db->transComplete();
            $this->nativeSession->setFlashData('success', lang('Success.default.cadastrado', ['Empresa']));
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
            'razao_social' => 'permit_empty|string|min_length[3]|max_length[255]',
            'nome_fantasia' => 'required|string|min_length[3]|max_length[255]',
            'cpf_cnpj' => 'required|string|min_length[11]|max_length[18]',
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

        $empresaUpdate = [
            'nome_fantasia'     => $dadosRequest['nome_fantasia'],
            'razao_social'      => !empty($dadosRequest['razao_social']) ? $dadosRequest['razao_social'] : $dadosRequest['nome_fantasia'],
            'cpf_cnpj'          => onlyNumber($dadosRequest['cpf_cnpj']),
            'telefone'          => onlyNumber($dadosRequest['telefone']),
            'celular'           => onlyNumber($dadosRequest['celular']),
            'email'             => $dadosRequest['email'],
            'observacao'        => $dadosRequest['observacao'],
            'data_nascimento'   => $dadosRequest['data_nascimento'],
            'endereco'          => !empty($empresaEndereco) ? json_encode($empresaEndereco) : null,
        ];

        //Inicia as operações de DB
        $this->db->transStart();
        try {
            $empresaModel->where($empresaModel->uuidColumn, $uuid)->set($empresaUpdate)->update();
            $this->db->transComplete();
            $this->nativeSession->setFlashData('success', lang('Success.default.atualizado', ['Empresa']));
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
        $empresaModel = new EmpresaModel;

        $dadosEmpresa = [
            'alterado_em'        => "NOW()",
            'inativado_em'       => null,
        ];

        try {
            $empresaModel->where($empresaModel->uuidColumn, $uuid)->set($dadosEmpresa)->update();
        } catch (Exception $e) {
            return $this->response->setJSON(['mensagem' => lang('Errors.banco.validaUpdate')], 422);
        }

        return $this->response->setJSON(['mensagem' => lang('Success.default.ativado', ['Empresa'])], 202);
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

        $empresaModel = new EmpresaModel;

        try {
            $empresaModel->customSoftDelete($uuid, true);
        } catch (Exception $e) {
            return $this->response->setJSON(['mensagem' => lang('Errors.banco.validaUpdate')], 422);
        }

        return $this->response->setJSON(['mensagem' => lang('Success.default.inativado', ['Empresa'])], 202);
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
