<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\RedirectResponse;
use Exception;

use App\Models\Reserva\ReservaModel;

class ReservaController extends BaseController
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
        $reservaModel = new ReservaModel;
        $dados['reserva'] = $reservaModel->get();
        foreach ($dados['reserva'] as $key => $value) {

            if (strtotime($value['data_fim']) < strtotime(date('Y-m-d'))) {
                $reservaModel->customSoftDelete($value['uuid_reserva'], true);
            }
        }
        return $this->template('reserva', ['index', 'functions']);
    }


    /**
     * Exibe a Tela de Adicionar Registro
     * @return html
     */
    public function create()
    {
        return $this->template('reserva', ['create', 'modal', 'functions']);
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
            return redirect()->to(base_url("reserva"));
        }

        $reservaModel = new ReservaModel;
        $dados['reserva'] = $reservaModel->get([$reservaModel->uuidColumn => $uuid], [], true);

        return $this->template('reserva', ['edit', 'functions', 'modal'], $dados);
    }

    /**
     * Busca os registros para o Datagrid
     * @param int $status Verifica se a informação está ativa (1 ou 0)
     */
    public function getDataGrid(int $status)
    {
        $reservaModel = new ReservaModel;
        $dadosRequest = $this->request->getVar();
        $dadosRequest['status'] = $status;
        $data = $reservaModel->getDataGrid($dadosRequest);
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
        $reservaModel = new ReservaModel;
        $dadosRequest = convertEmptyToNull($this->request->getVar());
        $dadosEmpresa = $this->nativeSession->get("empresa");

        $erros = $this->validarRequisicao($this->request, [
            'codigo_imovel' => 'permit_empty|integer',
            'codigo_cliente' => 'permit_empty|integer',
            'data_inicio' => 'required|valid_date',
            'data_fim' => 'required|valid_date',
            'data_fim' => 'required|valid_date',
            'descricao' => 'permit_empty|string',
        ]);

        if (!empty($erros)) {
            $this->nativeSession->setFlashData('error', formataErros($erros));
            return redirect()->back()->withInput();
        }

        $reserva = [
            'codigo_empresa' => $dadosEmpresa['codigo_empresa'],
            'codigo_imovel'  => $dadosRequest['codigo_imovel'],
            'codigo_cliente' => $dadosRequest['codigo_cliente'],
            'data_inicio'    => $dadosRequest['data_inicio'],
            'data_fim'       => $dadosRequest['data_fim'],
            'descricao'      => $dadosRequest['descricao'],
        ];

        //Inicia as operações de DB
        $this->db->transStart();
        try {
            $reservaModel->save($reserva);
            $this->db->transComplete();
            $this->nativeSession->setFlashData('success', lang('Success.default.cadastrado', ['Reserva']));
        } catch (Exception $e) {

            $error = $e->getMessage();
            $error = explode('|',  $error);

            $this->nativeSession->setFlashData('error', lang($error[1], ['Reserva']));
            return redirect()->back()->withInput();
        }

        return redirect()->to(base_url("reserva"));
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
            return redirect()->to(base_url("reserva"));
        }

        $reservaModel = new ReservaModel;
        $dadosRequest = convertEmptyToNull($this->request->getVar());
        $dadosEmpresa = $this->nativeSession->get("empresa");

        $erros = $this->validarRequisicao($this->request, [
            'codigo_imovel' => 'permit_empty|integer',
            'codigo_cliente' => 'permit_empty|integer',
            'data_inicio' => 'required|valid_date',
            'data_fim' => 'required|valid_date',
            'data_fim' => 'required|valid_date',
            'descricao' => 'permit_empty|string',
        ]);

        if (!empty($erros)) {
            $this->nativeSession->setFlashData('error', formataErros($erros));
            return redirect()->back()->withInput();
        }

        $reservaUpdate = [
            'codigo_empresa' => $dadosEmpresa['codigo_empresa'],
            'codigo_imovel'  => $dadosRequest['codigo_imovel'],
            'codigo_cliente' => $dadosRequest['codigo_cliente'],
            'data_inicio'    => $dadosRequest['data_inicio'],
            'data_fim'       => $dadosRequest['data_fim'],
            'descricao'      => $dadosRequest['descricao'],
        ];

        //Inicia as operações de DB
        $this->db->transStart();
        try {
            $reservaModel->where($reservaModel->uuidColumn, $uuid)->set($reservaUpdate)->update();
            $this->db->transComplete();
            $this->nativeSession->setFlashData('success', lang('Success.default.atualizado', ['Reserva']));
        } catch (Exception $e) {
            $error = $e->getMessage();
            $error = explode('|',  $error);
            $this->nativeSession->setFlashData('error', lang($error[1], ['Reserva']));
            return redirect()->back()->withInput();
        }
        return redirect()->to(base_url("reserva"));
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

        $reservaModel = new ReservaModel;
        $dadosReserva = [
            'alterado_em'        => "NOW()",
            'inativado_em'       => null,
        ];

        try {
            $reservaModel->where($reservaModel->uuidColumn, $uuid)->set($dadosReserva)->update();
        } catch (Exception $e) {
            return $this->response->setJSON(['mensagem' => lang('Errors.banco.validaUpdate')], 422);
        }

        return $this->response->setJSON(['mensagem' => lang('Success.default.ativado', ['Reserva'])], 202);
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

        $reservaModel = new ReservaModel;

        try {
            $reservaModel->customSoftDelete($uuid, true);
        } catch (Exception $e) {
            return $this->response->setJSON(['mensagem' => lang('Errors.banco.validaUpdate')], 422);
        }

        return $this->response->setJSON(['mensagem' => lang('Success.default.desativado', ['Reserva'])], 202);
    }

    /**
     * Realiza as chamadas assincronas direto para a Model
     * @param string $function
     */
    public function backendCall(string $function)
    {
        try {
            $request = $this->request->getVar();
            return $this->response->setJSON((new ReservaModel)->$function($request));
        } catch (Exception $e) {
            var_dump($e);
        }
    }
}
