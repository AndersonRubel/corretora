<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\RedirectResponse;
use Exception;

use App\Models\Cliente\ClienteModel;

class AniversarioController extends BaseController
{
    //////////////////////////////////
    //                              //
    //      OPERAÇÕES DE BUSCA      //
    //                              //
    //////////////////////////////////

    /**
     * Exibe a Tela de Aniversários
     * @return html
     */
    public function index()
    {
        $dados['saldoDisponivel'] = 14062;
        $dados['custoSms']        = 16;
        return $this->template('aniversario', ['index', 'functions'], $dados);
    }

    /**
     * Busca os registros para o Datagrid
     */
    public function getDataGrid()
    {
        try {
            $clienteModel = new ClienteModel;
            $dadosRequest = $this->request->getVar();
            $data = $clienteModel->getDataGridAniversariantes($dadosRequest);
            return $this->responseDataGrid($data, $dadosRequest);
        } catch (Exception $e) {
            print_r($e);
            die();
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
    public function send(): RedirectResponse
    {
        $dadosRequest = convertEmptyToNull($this->request->getVar());
        $dadosEmpresa = $this->nativeSession->get("empresa");

        $erros = $this->validarRequisicao($this->request, [
            'envia_sms' => 'permit_empty|string|in_list[t,f]',
            'envia_email' => 'permit_empty|string|in_list[t,f]',
            'mensagem_sms' => 'permit_empty|string',
            'mensagem_email' => 'permit_empty|string',
        ]);

        if (!empty($erros)) {
            $this->nativeSession->setFlashData('error', formataErros($erros));
            return redirect()->back()->withInput();
        }

        print_r($dadosRequest);
        die();

        try {

            foreach ($dadosRequest['listagem'] as $key => $value) {

                // Verifica se deve enviar o SMS
                if ($dadosRequest['envia_sms'] == 't') {
                    $mensagemSms = str_replace(['#CLIENTE#', '#EMPRESA#'], [$value['nome'], $dadosEmpresa['nome_fantasia']], $dadosRequest['mensagem_sms']);
                    $enviadoSms = $this->enviarSms($value['celular'], removeAccents($mensagemSms));
                }

                // Verifica se deve enviar o EMAIL
                if ($dadosRequest['envia_email']) {
                    $mensagemEmail = str_replace(['#CLIENTE#', '#EMPRESA#'], [$value['nome'], $dadosEmpresa['nome_fantasia']], $dadosRequest['mensagem_email']);
                    $enviadoEmail = $this->enviarEmail($value['email'], "Feliz Aniversário {$value['nome']}", $mensagemEmail);
                }
            }

            $this->nativeSession->setFlashData('success', lang('Success.geral.operacao'));
        } catch (Exception $e) {
            $this->nativeSession->setFlashData('error', lang('Errors.geral.operacao'));
            return redirect()->back()->withInput();
        }

        return redirect()->to(base_url("aniversario"));
    }

    /**
     * Realiza as chamadas assincronas direto para a Model
     * @param string $function
     */
    // public function backendCall(string $function)
    // {
    //     try {
    //         $request = $this->request->getVar();
    //         return $this->response->setJSON((new FornecedorModel)->$function($request));
    //     } catch (Exception $e) {
    //         var_dump($e);
    //     }
    // }
}
