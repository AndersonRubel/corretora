<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Response;
use Exception;

use App\Models\Cliente\ClienteModel;

class EstatisticaController extends BaseController
{
    //////////////////////////////////
    //                              //
    //      OPERAÇÕES DE BUSCA      //
    //                              //
    //////////////////////////////////

    /**
     * Exibe a Tela de Estatisticas
     * @return html
     */
    public function index()
    {
        return $this->template('estatistica', ['index', 'functions']);
    }

    /**
     * Busca os registros para o Datagrid
     */
    public function getDataGrid(): Response
    {
        try {
            $clienteModel = new ClienteModel;
            $dadosRequest = $this->request->getVar();
            $data = $clienteModel->getDataGridAniversariantes($dadosRequest);

            $dados['data']             = !empty($data['data'])         ? $data['data']           : [];
            $dados['draw']             = !empty($dadosRequest['draw']) ? $dadosRequest['draw']   : 0;
            $dados['recordsTotal']     = !empty($data['count'])        ? $data['count']['total'] : 0;
            $dados['recordsFiltered']  = !empty($data['count'])        ? $data['count']['total'] : 0;

            return $this->response->setJSON($dados);
        } catch (Exception $e) {
            print_r($e);
            die();
        }
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
