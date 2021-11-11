<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\RedirectResponse;
use Exception;

use App\Models\TipoImovel\TipoImovelModel;

class TipoImovelController extends BaseController
{
    //////////////////////////////////
    //                              //
    //      OPERAÇÕES DE BUSCA      //
    //                              //
    //////////////////////////////////

    /**
     * Exibe a Tela de Grupo
     * @return html
     */
    public function index()
    {
        return $this->template('tipo-imovel', ['index', 'functions']);
    }

    /**
     * Exibe a Tela de Adicionar Registro
     * @return html
     */
    public function create()
    {
        return $this->template('tipo-imovel', ['create', 'functions']);
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
            return redirect()->to(base_url("grupo"));
        }

        $tipoImovelModel = new TipoImovelModel;

        $dados['tipoImovel'] = $tipoImovelModel->get([$tipoImovelModel->uuidColumn => $uuid],[],true);

        return $this->template('tipo-imovel', ['edit', 'functions'],$dados);
    }

    /**
     * Busca os registros para o Datagrid
     * @param int $status Verifica se a informação está ativa (1 ou 0)
     */
    public function getDataGrid(int $status)
    {
        $tipoImoveloModel = new TipoImovelModel;
        $dadosRequest = $this->request->getVar();
        $dadosRequest['status'] = $status;
        $data = $tipoImoveloModel->getDataGrid($dadosRequest);
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
        $tipoImoveloModel = new TipoImovelModel;
        $dadosRequest = convertEmptyToNull($this->request->getVar());
        $dadosEmpresa= $this->nativeSession->get("empresa");

        $erros = $this->validarRequisicao($this->request, [
            'nome' => 'required|string|min_length[3]|max_length[255]|is_unique[cadastro_grupo.nome]',
        ]);

        if (!empty($erros)) {
            $this->nativeSession->setFlashData('error', formataErros($erros));
            return redirect()->back()->withInput();
        }

        $tipoImovel = [
            'codigo_empresa'  => $dadosEmpresa['codigo_empresa'],
            'nome'            => $dadosRequest['nome'],
        ];

        //Inicia as operações de DB
        $this->db->transStart();
        try {
            $tipoImoveloModel->save($tipoImovel);

            $this->db->transComplete();
            $this->nativeSession->setFlashData('success', lang('Success.default.cadastrado', ['TipoImovel']));
        } catch (Exception $e) {
            $this->nativeSession->setFlashData('error', lang('Errors.banco.validaInsercao'));
            return redirect()->back()->withInput();
        }

        return redirect()->to(base_url("tipoImovel"));
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
            return redirect()->to(base_url("grupo"));
        }

        $tipoImoveloModel = new TipoImovelModel;
        $dadosRequest = convertEmptyToNull($this->request->getVar());
        $dadosEmpresa= $this->nativeSession->get("empresa");

        $erros = $this->validarRequisicao($this->request, [
            'nome' => 'required|string|min_length[3]|max_length[255]',
        ]);

        if (!empty($erros)) {
            $this->nativeSession->setFlashData('error', formataErros($erros));
            return redirect()->back()->withInput();
        }

        $tipoImovelUpdate = [
            'nome'              => $dadosRequest['nome'],
        ];

        //Inicia as operações de DB
        $this->db->transStart();
        try {

            $tipoImoveloModel->where($tipoImoveloModel->uuidColumn, $uuid)->set($tipoImovelUpdate)->update();

            $this->db->transComplete();
            $this->nativeSession->setFlashData('success', lang('Success.default.atualizado', ['TipoImovel']));
        } catch (Exception $e) {
            $this->nativeSession->setFlashData('error', lang('Errors.banco.validaUpdate'));
            return redirect()->back()->withInput();
        }

        return redirect()->to(base_url("tipoImovel"));
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

        $tipoImovelModel = new TipoImovelModel;

        $dadosGrupo = [
            'alterado_em'        => "NOW()",
            'inativado_em'       => null,
        ];

        try {
            $tipoImovelModel->where($tipoImovelModel->uuidColumn, $uuid)->set($dadosGrupo)->update();
        } catch (Exception $e) {
            return $this->response->setJSON(['mensagem' => lang('Errors.banco.validaUpdate')], 422);
        }

        return $this->response->setJSON(['mensagem' => lang('Success.default.ativado', ['TipoImovel'])], 202);
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
        $tipoImovelModel = new TipoImovelModel;

        try {
            $tipoImovelModel->customSoftDelete($uuid,true);
        } catch (Exception $e) {
            return $this->response->setJSON(['mensagem' => lang('Errors.banco.validaUpdate')], 422);
        }

        return $this->response->setJSON(['mensagem' => lang('Success.default.desativado', ['Grupo'])], 202);
    }

    /**
     * Realiza as chamadas assincronas direto para a Model
     * @param string $function
     */
    public function backendCall(string $function)
    {
        try {
            $request = $this->request->getVar();
            return $this->response->setJSON((new TipoImovelModel)->$function($request));
        } catch (Exception $e) {
            var_dump($e);
        }
    }
}
