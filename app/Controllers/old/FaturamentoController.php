<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\RedirectResponse;
use Exception;

use App\Models\Empresa\EmpresaComissaoModel;
use App\Models\Empresa\EmpresaModel;
use App\Models\Faturamento\FaturamentoModel;
use App\Models\Faturamento\FaturamentoVendaModel;
use App\Models\Financeiro\FinanceiroFluxoModel;
use App\Models\Financeiro\FinanceiroFluxoParcialModel;

class FaturamentoController extends BaseController
{
    //////////////////////////////////
    //                              //
    //      OPERAÇÕES DE BUSCA      //
    //                              //
    //////////////////////////////////

    /**
     * Exibe a Tela de Faturamento
     * @return html
     */
    public function index()
    {
        return $this->template('faturamento', ['index', 'functions']);
    }

    /**
     * Exibe a Tela de Adicionar Registro
     * @return html
     */
    public function create()
    {
        return $this->template('faturamento', ['create', 'functions']);
    }

    /**
     * Busca os registros para o Datagrid
     */
    public function getDataGrid()
    {
        $faturamentoModel = new FaturamentoModel;
        $dadosRequest = $this->request->getVar();
        $data = $faturamentoModel->getDataGrid($dadosRequest);
        return $this->responseDataGrid($data, $dadosRequest);
    }

    /**
     * Gera um PDF do Faturamento
     * @return pdf
     */
    public function gerarPdf(string $uuid)
    {

        if (!$this->verificarUuid($uuid)) {
            $this->nativeSession->setFlashData('error', lang('Errors.geral.validaUuid'));
            return redirect()->to(base_url("faturamento"));
        }

        $faturamentoModel = new FaturamentoModel;
        $faturamentoVendaModel = new FaturamentoVendaModel;
        $empresaModel = new EmpresaModel;

        try {
            $colunas = ["
                faturamento.*
              , obter_nome_usuario(faturamento.usuario_criacao) AS usuario
            "];
            $dados['faturamento'] = $faturamentoModel->get(['uuid_faturamento' => $uuid], $colunas, true);

            if (!empty($dados['faturamento'])) {
                $dados['empresa'] = $empresaModel->get(['codigo_empresa' => $dados['faturamento']['codigo_empresa']], [], true);
                $dados['empresa']['endereco'] = json_decode($dados['empresa']['endereco'], true);

                $dados['vendedor'] = $faturamentoVendaModel->getFaturamentoVendas($dados['faturamento']['codigo_faturamento']);

                $pdf = new \Mpdf\Mpdf([
                    'mode' => '',
                    'format' => 'A4',
                    'default_font_size' => 0,
                    'default_font' => '',
                    'margin_left' => 15,
                    'margin_right' => 15,
                    'margin_top' => 16,
                    'margin_bottom' => 16,
                    'margin_header' => 9,
                    'margin_footer' => 9,
                    'orientation' => 'P'
                ]);
                $pdf->SetWatermarkImage(base_url('assets/img/logo.png'));
                $pdf->showWatermarkImage = true;
                $pdf->watermarkImageAlpha = '0.1';
                $pdf->setHtmlHeader(view('app/faturamento/pdf/header.php', $dados));
                $pdf->setHtmlFooter(view('app/faturamento/pdf/footer.php', $dados));
                $pdf->WriteHTML(view('app/faturamento/pdf/corpo.php', $dados));
                $pdf->Output("Faturamento_{$uuid}.pdf", 'D');
            } else {
                $this->nativeSession->setFlashData('error', lang('Errors.geral.registroNaoEncontrado'));
                return redirect()->to(base_url("faturamento"));
            }
        } catch (Exception $e) {
            var_dump($e);
            die;
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
        $faturamentoModel = new FaturamentoModel;
        $faturamentoVendaModel = new FaturamentoVendaModel;
        $financeiroFluxoModel = new FinanceiroFluxoModel;
        $financeiroFluxoParcialModel = new FinanceiroFluxoParcialModel;
        $empresaComissaoModel = new EmpresaComissaoModel;

        $dadosRequest = convertEmptyToNull($this->request->getVar());
        $dadosEmpresa = $this->nativeSession->get("empresa");
        $dadosUsuario = $this->nativeSession->get("usuario");

        $erros = $this->validarRequisicao($this->request, [
            'periodo_inicio' => 'required|valid_date',
            'periodo_fim' => 'required|valid_date',
            'codigo_vendedor' => 'required|integer',
            'codigo_empresa_conta' => 'required|integer',
            'codigo_cadastro_metodo_pagamento' => 'required|integer',
            'codigo_empresa_centro_custo' => 'required|integer',
            'codigo_empresa_comissao' => 'permit_empty|integer',
            'data_vencimento' => 'required|valid_date',
            'valor_total_bruto' => 'required|string',
            'valor_comissao' => 'permit_empty|string',
            'valor_desconto' => 'permit_empty|string',
            'valor_total_liquido' => 'required|string',
            'valor_entrada' => 'permit_empty|string',
            'valor_restante' => 'required|string',
        ]);

        if (!empty($erros)) {
            $this->nativeSession->setFlashData('error', formataErros($erros));
            return redirect()->back()->withInput();
        }

        // Valida se tem dados nos arrays
        if (empty($dadosRequest['codigo_venda']) || empty($dadosRequest['valor_bruto']) || empty($dadosRequest['valor_liquido']) || empty($dadosRequest['check_faturar'])) {
            $this->nativeSession->setFlashData('error', lang('Errors.faturamento.semDados'));
            return redirect()->to(base_url("faturamento"));
        }

        // Busca a Porcentagem da Comissão
        $comissao = [];
        if (!empty($dadosRequest['codigo_empresa_comissao'])) {
            $comissao = $empresaComissaoModel->get(['codigo_empresa_comissao' => $dadosRequest['codigo_empresa_comissao']], ['percentual'], true);

            if (empty($comissao)) {
                $this->nativeSession->setFlashData('error', lang('Errors.faturamento.comissao'));
                return redirect()->to(base_url("faturamento"));
            }
        }

        //Inicia as operações de DB
        $this->db->transStart();
        try {

            $faturamento = [
                'codigo_empresa'      => $dadosEmpresa['codigo_empresa'],
                'usuario_criacao'     => $dadosUsuario['codigo_usuario'],
                'codigo_vendedor'     => $dadosRequest['codigo_vendedor'],
                'periodo_inicio'      => $dadosRequest['periodo_inicio'],
                'periodo_fim'         => $dadosRequest['periodo_fim'],
                'codigo_comissao'     => $dadosRequest['codigo_empresa_comissao'],
                'percentual_comissao' => !empty($comissao) ? $comissao['percentual'] : null,
                'valor_bruto'         => 1,
                'valor_desconto'      => onlyNumber($dadosRequest['valor_desconto']),
                'valor_entrada'       => onlyNumber($dadosRequest['valor_entrada']),
            ];

            $faturamentoModel->save($faturamento);
            $codigoFaturamento = $faturamentoModel->insertID('faturamento_codigo_faturamento_seq');

            // Percorre os menus desejados para gravar a permissão
            $valorTotalBruto = 0;
            if ($codigoFaturamento) {
                // Insere as Vendas desse Faturamento
                foreach ($dadosRequest['codigo_venda'] as $key => $value) {
                    if (isset($dadosRequest['check_faturar'][$key]) && $dadosRequest['check_faturar'][$key] == 'on') {
                        $fatVenda = [
                            'codigo_empresa'     => $dadosEmpresa['codigo_empresa'],
                            'usuario_criacao'    => $dadosUsuario['codigo_usuario'],
                            'codigo_faturamento' => $codigoFaturamento,
                            'codigo_venda'       => $value,
                            'valor_bruto'        => onlyNumber($dadosRequest['valor_bruto'][$key]),
                            'valor_liquido'      => onlyNumber($dadosRequest['valor_liquido'][$key])
                        ];

                        // Se tiver comissão
                        if (!empty($dadosRequest['codigo_empresa_comissao'])) {
                            $fatVenda['valor_comissao'] = (onlyNumber($dadosRequest['valor_liquido'][$key]) * ($comissao['percentual'] / 100));
                        }

                        $faturamentoVendaModel->save($fatVenda);

                        // Monta um Saldo Consolidado do Valor das Vendas
                        $valorTotalBruto += onlyNumber($dadosRequest['valor_liquido'][$key]);
                    }
                }

                // Atualiza o Faturamento com os valores calculados
                $faturamentoUpdate = [
                    'codigo_faturamento' => $codigoFaturamento,
                    'valor_bruto'        => $valorTotalBruto,
                ];

                // Se tiver comissão
                if (!empty($dadosRequest['codigo_empresa_comissao'])) {
                    $faturamentoUpdate['valor_comissao'] = ($valorTotalBruto * ($comissao['percentual'] / 100));
                    $faturamentoUpdate['valor_liquido'] = $faturamentoUpdate['valor_bruto'] - $faturamento['valor_desconto'] - $faturamentoUpdate['valor_comissao'];
                } else {
                    $faturamentoUpdate['valor_liquido'] = $faturamentoUpdate['valor_bruto'] - $faturamento['valor_desconto'];
                }

                $faturamentoModel->save($faturamentoUpdate);

                /////// INICIO :: Ocorrência ÚNICA - Insere o Fluxo Sem Parcelamento ///////


                // Busca o ultimo valor de lote cadastrado na empresa
                $fluxoLote = $financeiroFluxoModel->get(['codigo_empresa' => $dadosEmpresa['codigo_empresa']], ["COALESCE(MAX(fluxo_lote), 0) AS valor"], true);

                $dadosFluxo = [
                    'codigo_empresa'                   => $dadosEmpresa['codigo_empresa'],
                    'usuario_criacao'                  => $dadosUsuario['codigo_usuario'],
                    'codigo_cadastro_metodo_pagamento' => onlyNumber($dadosRequest['codigo_cadastro_metodo_pagamento']),
                    'codigo_cadastro_fluxo_tipo'       => 1, // Receita
                    'codigo_empresa_centro_custo'      => onlyNumber($dadosRequest['codigo_empresa_centro_custo']),
                    'codigo_empresa_conta'             => onlyNumber($dadosRequest['codigo_empresa_conta']),
                    'codigo_vendedor'                  => onlyNumber($dadosRequest['codigo_vendedor']),
                    'codigo_faturamento'               => $codigoFaturamento,
                    'nome'                             => "Faturamento #{$codigoFaturamento}",
                    'data_vencimento'                  => $dadosRequest['data_vencimento'],
                    'data_competencia'                 => "NOW()",
                    'valor_bruto'                      => onlyNumber($valorTotalBruto),
                    'valor_acrescimo'                  => null,
                    'valor_desconto'                   => onlyNumber($faturamento['valor_desconto']),
                    'valor_liquido'                    => onlyNumber($faturamentoUpdate['valor_liquido']),
                    'valor_pago_parcial'               => null,
                    'observacao'                       => "Período do Faturamento: " . date('d/m/Y', strtotime($dadosRequest['periodo_inicio'])) . " - " . date('d/m/Y', strtotime($dadosRequest['periodo_fim'])),
                    'fluxo_lote'                       => onlyNumber($fluxoLote['valor'] + 1),
                    'insercao_automatica'              => "t"
                ];

                // Insere o Fluxo de para esse Faturamento
                $financeiroFluxoModel->save($dadosFluxo);

                // Insere o fluxo Parcial caso tenha valor de entrada
                if (!empty($dadosRequest['valor_entrada'])) {
                    $codigoFinanceiro = $financeiroFluxoModel->insertID('financeiro_fluxo_codigo_financeiro_fluxo_seq');
                    if ($codigoFinanceiro) {
                        $dadosFluxoParcial = [
                            'codigo_empresa'                   => $dadosEmpresa['codigo_empresa'],
                            'usuario_criacao'                  => $dadosUsuario['codigo_usuario'],
                            'codigo_financeiro_fluxo'          => $codigoFinanceiro,
                            'codigo_cadastro_metodo_pagamento' => onlyNumber($dadosRequest['codigo_cadastro_metodo_pagamento']),
                            'data_pagamento'                   => "NOW()",
                            'valor'                            => onlyNumber($dadosRequest['valor_entrada'])
                        ];
                        $financeiroFluxoParcialModel->save($dadosFluxoParcial);
                    }
                }

                /////// FIM :: Ocorrência ÚNICA - Insere o Fluxo Sem Parcelamento ///////
            }
            $this->db->transComplete();
            $this->nativeSession->setFlashData('success', lang('Success.default.cadastrado', ['Faturamento']));
        } catch (Exception $e) {
            var_dump($e);
            die;
            $this->nativeSession->setFlashData('error', lang('Errors.banco.validaInsercao'));
            return redirect()->back()->withInput();
        }

        return redirect()->to(base_url("faturamento"));
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
        $faturamentoModel = new FaturamentoModel;
        $faturamentoVendaModel = new FaturamentoVendaModel;
        $financeiroFluxoModel = new FinanceiroFluxoModel;

        try {
            // Busca o código do Faturamento
            $faturamento = $faturamentoModel->get([$faturamentoModel->uuidColumn => $uuid], ['codigo_faturamento'], true);

            if (empty($faturamento)) {
                return $this->response->setJSON(['mensagem' => lang('Errors.geral.registroNaoEncontrado')], 422);
            }

            // Desativa os Fluxos desse Faturamento
            $financeiroFluxoModel
                ->where('codigo_faturamento', $faturamento['codigo_faturamento'])
                ->set(['usuario_inativacao' => $dadosUsuario['codigo_usuario'], 'inativado_em' => 'NOW()'])
                ->update();

            // Desativa as Vendas que estao dentro do Faturamento
            $faturamentoVendaModel
                ->where('codigo_faturamento', $faturamento['codigo_faturamento'])
                ->set(['usuario_inativacao' => $dadosUsuario['codigo_usuario'], 'inativado_em' => 'NOW()'])
                ->update();

            // Desativa o Faturamento
            $faturamentoModel->customSoftDelete($uuid, $dadosUsuario['codigo_usuario'], true);
        } catch (Exception $e) {
            return $this->response->setJSON(['mensagem' => lang('Errors.banco.validaUpdate')], 422);
        }

        return $this->response->setJSON(['mensagem' => lang('Success.default.excluido', ['Faturamento'])], 202);
    }

    /**
     * Realiza as chamadas assincronas direto para a Model
     * @param string $function
     */
    public function backendCall(string $function)
    {
        try {
            $request = $this->request->getVar();
            return $this->response->setJSON((new FaturamentoModel)->$function($request));
        } catch (Exception $e) {
            var_dump($e);
        }
    }
}
