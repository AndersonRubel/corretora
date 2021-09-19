<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\Response;
use Exception;

use App\Models\Cliente\ClienteModel;
use App\Models\Empresa\EmpresaCentroCustoModel;
use App\Models\Empresa\EmpresaContaModel;
use App\Models\Empresa\EmpresaModel;
use App\Models\Financeiro\FinanceiroFluxoModel;
use App\Models\Financeiro\FinanceiroFluxoParcialModel;

class FinanceiroController extends BaseController
{

    //////////////////////////////////
    //                              //
    //      OPERAÇÕES DE BUSCA      //
    //                              //
    //////////////////////////////////

    /**
     * Exibe a Tela de Financeiro Fluxo
     * @return html
     */
    public function index()
    {
        return $this->template('financeiro/fluxo', ['index', 'modal', 'functions']);
    }

    /**
     * Exibe a Tela de Adicionar Registro
     * @return html
     */
    public function create()
    {
        return $this->template('financeiro/fluxo', ['create', 'functions']);
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
            return redirect()->to(base_url("financeiro"));
        }

        $financeiroFluxoModel = new FinanceiroFluxoModel;
        $dados['fluxo'] = $financeiroFluxoModel->get([$financeiroFluxoModel->uuidColumn => $uuid], [], true);

        return $this->template('financeiro/fluxo', ['edit', 'functions'], $dados);
    }

    /**
     * Busca os registros para o Datagrid
     * @param int $status Verifica se a informação está ativa (1 ou 0)
     */
    public function getDataGrid(int $status)
    {
        try {
            $financeiroFluxoModel = new FinanceiroFluxoModel;
            $dadosRequest = $this->request->getVar();
            $dadosRequest['status'] = $status;
            $data = $financeiroFluxoModel->getDataGrid($dadosRequest);
            return $this->responseDataGrid($data, $dadosRequest);
        } catch (Exception $e) {
            print_r($e);
            die();
        }
    }

    /**
     * Busca os registros para o Datagrid de Resumo
     * @param int $status Verifica se a informação está ativa (1 ou 0)
     */
    public function getDataGridResumo(int $status)
    {
        try {
            $financeiroFluxoModel = new FinanceiroFluxoModel;
            $dadosRequest = $this->request->getVar();
            $dadosRequest['status'] = $status;
            $data = $financeiroFluxoModel->getDataGridResumo($dadosRequest);
            return $this->responseDataGrid($data, $dadosRequest);
        } catch (Exception $e) {
            print_r($e);
            die();
        }
    }

    /**
     * Busca os dados para o gráfico de resumo financeiro
     */
    public function getGraficoResumo(): Response
    {
        try {
            $financeiroFluxoModel = new FinanceiroFluxoModel;
            $dadosRequest = $this->request->getVar();

            $data    = [];
            $mesAno  = explode('-', $dadosRequest['data']);
            $mes     = $mesAno[1];
            $ano     = $mesAno[0];

            $fluxos = $financeiroFluxoModel->getGraficoResumo($dadosRequest);
            $numeroDias = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);

            for ($j = 1; $j <= $numeroDias; $j++) {
                $arrayQtd[] = 0;
            }

            $data['pagar'] = $data['receber'] = $arrayQtd;

            for ($i = 1; $i <= $numeroDias; $i++) {
                if ($i < 10) {
                    $num = '0' . $i;
                } else {
                    $num = $i;
                }

                $data['categorias_mes'][] = (int) $num;

                foreach ($fluxos as $key => $value) {
                    if ($value['data'] == date("{$num}/{$mes}/{$ano}")) {
                        if ($value['codigo_cadastro_fluxo_tipo'] == '1') {
                            $data['receber'][$i - 1] = (float) $value['valor'];
                        } else if ($value['codigo_cadastro_fluxo_tipo'] == '2') {
                            $data['pagar'][$i - 1] = (float) $value['valor'];
                        }
                    }
                }
            }

            return $this->response->setJSON($data);
        } catch (Exception $e) {
            print_r($e);
            die();
        }
    }

    /**
     * Monta o Comprovante do Fluxo
     * @param string $uuid UUID do Registro
     * @return html
     */
    public function comprovante(string $uuid)
    {
        $financeiroFluxoModel = new FinanceiroFluxoModel;
        $empresaModel = new EmpresaModel;

        $dados['fluxo'] = $financeiroFluxoModel->selectFluxoImpressao($uuid);

        if (!empty($dados['fluxo'])) {

            // Empresa
            if (!empty($dados['fluxo']['codigo_empresa'])) {
                $dados['empresa'] = $empresaModel->get(['codigo_empresa' => $dados['fluxo']['codigo_empresa']], [], true);
                $dados['empresa']['endereco'] = json_decode($dados['empresa']['endereco'], true);
            } else {
                $dados['empresa'] = "";
            }
        }
        echo view('app/financeiro/fluxo/comprovante', $dados);
    }

    /**
     * Monta o Recibo do Fluxo
     * @param string $uuid UUID do Registro
     * @return html
     */
    public function recibo(string $uuid)
    {
        $financeiroFluxoModel = new FinanceiroFluxoModel;
        $empresaModel = new EmpresaModel;

        $dados['fluxo'] = $financeiroFluxoModel->selectFluxoImpressao($uuid);

        if (!empty($dados['fluxo'])) {

            // Empresa
            if (!empty($dados['fluxo']['codigo_empresa'])) {
                $dados['empresa'] = $empresaModel->get(['codigo_empresa' => $dados['fluxo']['codigo_empresa']], [], true);
                $dados['empresa']['endereco'] = json_decode($dados['empresa']['endereco'], true);
            } else {
                $dados['empresa'] = "";
            }
        }
        echo view('app/financeiro/fluxo/recibo', $dados);
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
        $financeiroFluxoModel = new FinanceiroFluxoModel;
        $empresaContaModel = new EmpresaContaModel;
        $empresaCentroCustoModel = new EmpresaCentroCustoModel;
        $dadosRequest = convertEmptyToNull($this->request->getVar());
        $dadosUsuario = $this->nativeSession->get("usuario");
        $dadosEmpresa = $this->nativeSession->get("empresa");

        $erros = $this->validarRequisicao($this->request, [
            'state_submit' => 'permit_empty|string|in_list[salvar,salvar_e_continuar]',
            'insercao_automatica' => 'permit_empty|string|in_list[t,f]',
            'codigo_cadastro_fluxo_tipo' => 'required|integer',
            'nome' => 'required|string|min_length[3]|max_length[255]',
            'ocorrencia' => 'required|string|in_list[U,P]',
            'codigo_empresa_centro_custo' => 'permit_empty|integer',
            'data_vencimento' => 'permit_empty|valid_date',
            'valor_bruto' => 'required|string',
            'valor_juros' => 'permit_empty|string',
            'valor_acrescimo' => 'permit_empty|string',
            'valor_desconto' => 'permit_empty|string',
            'situacao' => 'permit_empty|string|in_list[t,f]',
            'data_pagamento' => 'permit_empty|valid_date',
            'parcelamento_tipo' => 'permit_empty|string|in_list[D,M]',
            'parcelamento_periodo' => 'permit_empty|string|in_list[M,W,Q,T,S,A]',
            'parcelamento_quantidade' => 'permit_empty|integer',
            'parcelamento_data_primeira_parcela' => 'permit_empty|valid_date',
            'data_competencia' => 'permit_empty|valid_date',
            'codigo_empresa_conta' => 'permit_empty|integer',
            'codigo_cadastro_metodo_pagamento' => 'permit_empty|integer',
            'codigo_barras' => 'permit_empty|integer',
            'codigo_fornecedor' => 'permit_empty|integer',
            'codigo_cliente' => 'permit_empty|integer',
            'codigo_vendedor' => 'permit_empty|integer',
            'observacao' => 'permit_empty|string',
        ]);

        if (!empty($erros)) {
            $this->nativeSession->setFlashData('error', formataErros($erros));
            return redirect()->back()->withInput();
        }

        //Inicia as operações de DB
        $this->db->transStart();
        try {

            // Verifica se veio a Conta da Empresa, se não seta a Padrão
            if (empty($dadosRequest['codigo_empresa_conta'])) {
                $empresaConta = $empresaContaModel->get(['codigo_empresa' => $dadosEmpresa['codigo_empresa'], 'padrao' => 't'], ['codigo_empresa_conta'], true);
                $dadosRequest['codigo_empresa_conta'] = $empresaConta['codigo_empresa_conta'];
            }

            // Verifica se veio o centro de custo da empresa, se não seta a padrão
            if (empty($dadosRequest['codigo_empresa_centro_custo'])) {
                $empresaCentroCusto = $empresaCentroCustoModel->get(['codigo_empresa' => $dadosEmpresa['codigo_empresa'], 'padrao' => 't'], ['codigo_empresa_centro_custo'], true);
                $dadosRequest['codigo_empresa_centro_custo'] = $empresaCentroCusto['codigo_empresa_centro_custo'];
            }

            // Busca o ultimo valor de lote cadastrado na empresa
            $fluxoLote = $financeiroFluxoModel->get(['codigo_empresa' => $dadosEmpresa['codigo_empresa']], ["COALESCE(MAX(fluxo_lote), 0) AS valor"], true);

            // Verifica se o fluxo é UNICO (U) ou PARCELADO/RECORRENTE (P)
            if ($dadosRequest['ocorrencia'] == 'U') {

                /////// INICIO :: Ocorrência ÚNICA - Insere o Fluxo Sem Parcelamento ///////

                // Realiza a soma dos valores
                $valorLiquido = (onlyNumber($dadosRequest['valor_bruto']) + onlyNumber($dadosRequest['valor_juros']) + onlyNumber($dadosRequest['valor_acrescimo'])) - onlyNumber($dadosRequest['valor_desconto']);

                $dadosFluxo = [
                    'codigo_empresa'                   => $dadosEmpresa['codigo_empresa'],
                    'usuario_criacao'                  => $dadosUsuario['codigo_usuario'],
                    'codigo_cadastro_metodo_pagamento' => onlyNumber($dadosRequest['codigo_cadastro_metodo_pagamento']),
                    'codigo_cadastro_fluxo_tipo'       => onlyNumber($dadosRequest['codigo_cadastro_fluxo_tipo']),
                    'codigo_empresa_centro_custo'      => onlyNumber($dadosRequest['codigo_empresa_centro_custo']),
                    'codigo_empresa_conta'             => onlyNumber($dadosRequest['codigo_empresa_conta']),
                    'codigo_fornecedor'                => onlyNumber($dadosRequest['codigo_fornecedor']),
                    'codigo_cliente'                   => onlyNumber($dadosRequest['codigo_cliente']),
                    'codigo_vendedor'                  => onlyNumber($dadosRequest['codigo_vendedor']),
                    'nome'                             => $dadosRequest['nome'],
                    'data_vencimento'                  => $dadosRequest['data_vencimento'],
                    'data_pagamento'                   => $dadosRequest['data_pagamento'],
                    'data_competencia'                 => $dadosRequest['data_competencia'],
                    'valor_bruto'                      => onlyNumber($dadosRequest['valor_bruto']),
                    'valor_juros'                      => onlyNumber($dadosRequest['valor_juros']),
                    'valor_acrescimo'                  => onlyNumber($dadosRequest['valor_acrescimo']),
                    'valor_desconto'                   => onlyNumber($dadosRequest['valor_desconto']),
                    'valor_liquido'                    => onlyNumber($valorLiquido),
                    'valor_pago_parcial'               => null,
                    'situacao'                         => "{$dadosRequest['situacao']}",
                    'observacao'                       => $dadosRequest['observacao'],
                    'fluxo_lote'                       => onlyNumber($fluxoLote['valor'] + 1),
                    'numero_parcela'                   => null,
                    'insercao_automatica'              => "{$dadosRequest['insercao_automatica']}",
                    'codigo_barras'                    => onlyNumber($dadosRequest['codigo_barras']),
                ];

                $financeiroFluxoModel->save($dadosFluxo);

                /////// FIM :: Ocorrência ÚNICA - Insere o Fluxo Sem Parcelamento ///////
            } else if ($dadosRequest['ocorrencia'] == 'P' && !empty($dadosRequest['parcelas'])) {

                /////// INICIO :: Ocorrência PARCELADA - Insere o Fluxo de Parcelamento/Recorrência ///////
                foreach ($dadosRequest['parcelas'] as $keyP => $valueP) {
                    $descricaoAdicional = "Parcela: " . ($keyP + 1) . "/" . count($dadosRequest['parcelas']);

                    // Realiza a soma dos valores
                    $valorBrutoParcelado = (onlyNumber($dadosRequest['valor_bruto']) / count($dadosRequest['parcelas']));
                    $valorJurosParcelado = (onlyNumber($dadosRequest['valor_juros']) / count($dadosRequest['parcelas']));
                    $valorAcrescimoParcelado = (onlyNumber($dadosRequest['valor_acrescimo']) / count($dadosRequest['parcelas']));
                    $valorDescontoParcelado = (onlyNumber($dadosRequest['valor_desconto']) / count($dadosRequest['parcelas']));
                    $valorLiquido = ($valorBrutoParcelado + $valorJurosParcelado + $valorAcrescimoParcelado) - $valorDescontoParcelado;

                    $dadosFluxo = [
                        'codigo_empresa'                   => $dadosEmpresa['codigo_empresa'],
                        'usuario_criacao'                  => $dadosUsuario['codigo_usuario'],
                        'codigo_cadastro_metodo_pagamento' => onlyNumber($dadosRequest['codigo_cadastro_metodo_pagamento']),
                        'codigo_cadastro_fluxo_tipo'       => onlyNumber($dadosRequest['codigo_cadastro_fluxo_tipo']),
                        'codigo_empresa_centro_custo'      => onlyNumber($dadosRequest['codigo_empresa_centro_custo']),
                        'codigo_empresa_conta'             => onlyNumber($dadosRequest['codigo_empresa_conta']),
                        'codigo_fornecedor'                => onlyNumber($dadosRequest['codigo_fornecedor']),
                        'codigo_cliente'                   => onlyNumber($dadosRequest['codigo_cliente']),
                        'codigo_vendedor'                  => onlyNumber($dadosRequest['codigo_vendedor']),
                        'nome'                             => "{$dadosRequest['nome']} - {$descricaoAdicional}",
                        'data_vencimento'                  => $valueP['data'],
                        'data_pagamento'                   => $valueP['status'] == 't' ? "NOW()" : null,
                        'data_competencia'                 => $dadosRequest['data_competencia'],
                        'valor_bruto'                      => onlyNumber($valorBrutoParcelado),
                        'valor_juros'                      => onlyNumber($valorJurosParcelado),
                        'valor_acrescimo'                  => onlyNumber($valorAcrescimoParcelado),
                        'valor_desconto'                   => onlyNumber($valorDescontoParcelado),
                        'valor_liquido'                    => onlyNumber($valorLiquido),
                        'valor_pago_parcial'               => null,
                        'situacao'                         => "{$valueP['status']}",
                        'observacao'                       => $valueP['observacao'],
                        'fluxo_lote'                       => onlyNumber($fluxoLote['valor'] + 1),
                        'numero_parcela'                   => ($keyP + 1),
                        'insercao_automatica'              => "{$dadosRequest['insercao_automatica']}",
                        'codigo_barras'                    => onlyNumber($dadosRequest['codigo_barras']),
                    ];

                    $financeiroFluxoModel->save($dadosFluxo);
                }


                /////// FIM :: Ocorrência PARCELADA - Insere o Fluxo de Parcelamento/Recorrência ///////

            }

            $this->db->transComplete();
            $this->nativeSession->setFlashData('success', lang('Success.default.cadastrado', ['Fluxo']));
        } catch (Exception $e) {
            $this->nativeSession->setFlashData('error', lang('Errors.banco.validaInsercao'));
            return redirect()->back()->withInput();
        }

        // Verifica se tem que voltar para a tela de adicionar novo fluxo
        if ($dadosRequest['state_submit'] == 'salvar_e_continuar') {
            return redirect()->to(base_url("financeiro/adicionar"));
        } else {
            return redirect()->to(base_url("financeiro"));
        }
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
            return redirect()->to(base_url("fornecedor"));
        }

        $financeiroFluxoModel = new FinanceiroFluxoModel;
        $empresaContaModel = new EmpresaContaModel;
        $empresaCentroCustoModel = new EmpresaCentroCustoModel;
        $dadosRequest = convertEmptyToNull($this->request->getVar());
        $dadosUsuario = $this->nativeSession->get("usuario");
        $dadosEmpresa = $this->nativeSession->get("empresa");

        $erros = $this->validarRequisicao($this->request, [
            'codigo_cadastro_fluxo_tipo' => 'required|integer',
            'nome' => 'required|string|min_length[3]|max_length[255]',
            'codigo_empresa_centro_custo' => 'permit_empty|integer',
            'data_vencimento' => 'permit_empty|valid_date',
            'valor_bruto' => 'required|string',
            'valor_juros' => 'permit_empty|string',
            'valor_acrescimo' => 'permit_empty|string',
            'valor_desconto' => 'permit_empty|string',
            'situacao' => 'permit_empty|string|in_list[t,f]',
            'data_pagamento' => 'permit_empty|valid_date',
            'data_competencia' => 'permit_empty|valid_date',
            'codigo_empresa_conta' => 'permit_empty|integer',
            'codigo_cadastro_metodo_pagamento' => 'permit_empty|integer',
            'codigo_barras' => 'permit_empty|integer',
            'codigo_fornecedor' => 'permit_empty|integer',
            'codigo_cliente' => 'permit_empty|integer',
            'codigo_vendedor' => 'permit_empty|integer',
            'observacao' => 'permit_empty|string',
        ]);

        if (!empty($erros)) {
            $this->nativeSession->setFlashData('error', formataErros($erros));
            return redirect()->back()->withInput();
        }

        //Inicia as operações de DB
        $this->db->transStart();
        try {

            // Verifica se veio a Conta da Empresa, se não seta a Padrão
            if (empty($dadosRequest['codigo_empresa_conta'])) {
                $empresaConta = $empresaContaModel->get(['codigo_empresa' => $dadosEmpresa['codigo_empresa'], 'padrao' => 't'], ['codigo_empresa_conta'], true);
                $dadosRequest['codigo_empresa_conta'] = $empresaConta['codigo_empresa_conta'];
            }

            // Verifica se veio o centro de custo da empresa, se não seta a padrão
            if (empty($dadosRequest['codigo_empresa_centro_custo'])) {
                $empresaCentroCusto = $empresaCentroCustoModel->get(['codigo_empresa' => $dadosEmpresa['codigo_empresa'], 'padrao' => 't'], ['codigo_empresa_centro_custo'], true);
                $dadosRequest['codigo_empresa_centro_custo'] = $empresaCentroCusto['codigo_empresa_centro_custo'];
            }

            // Realiza a soma dos valores
            $valorLiquido = (onlyNumber($dadosRequest['valor_bruto']) + onlyNumber($dadosRequest['valor_juros']) + onlyNumber($dadosRequest['valor_acrescimo'])) - onlyNumber($dadosRequest['valor_desconto']);

            $dadosFluxo = [
                'codigo_empresa'                   => $dadosEmpresa['codigo_empresa'],
                'usuario_alteracao'                => $dadosUsuario['codigo_usuario'],
                'data_alteracao'                   => "NOW()",
                'codigo_cadastro_metodo_pagamento' => onlyNumber($dadosRequest['codigo_cadastro_metodo_pagamento']),
                'codigo_cadastro_fluxo_tipo'       => onlyNumber($dadosRequest['codigo_cadastro_fluxo_tipo']),
                'codigo_empresa_centro_custo'      => onlyNumber($dadosRequest['codigo_empresa_centro_custo']),
                'codigo_empresa_conta'             => onlyNumber($dadosRequest['codigo_empresa_conta']),
                'codigo_fornecedor'                => onlyNumber($dadosRequest['codigo_fornecedor']),
                'codigo_cliente'                   => onlyNumber($dadosRequest['codigo_cliente']),
                'codigo_vendedor'                  => onlyNumber($dadosRequest['codigo_vendedor']),
                'nome'                             => $dadosRequest['nome'],
                'data_vencimento'                  => $dadosRequest['data_vencimento'],
                'data_pagamento'                   => $dadosRequest['data_pagamento'],
                'data_competencia'                 => $dadosRequest['data_competencia'],
                'valor_bruto'                      => onlyNumber($dadosRequest['valor_bruto']),
                'valor_juros'                      => onlyNumber($dadosRequest['valor_juros']),
                'valor_acrescimo'                  => onlyNumber($dadosRequest['valor_acrescimo']),
                'valor_desconto'                   => onlyNumber($dadosRequest['valor_desconto']),
                'valor_liquido'                    => onlyNumber($valorLiquido),
                'valor_pago_parcial'               => onlyNumber($dadosRequest['valor_pago_parcial']),
                'situacao'                         => "{$dadosRequest['situacao']}",
                'observacao'                       => $dadosRequest['observacao'],
                'codigo_barras'                    => onlyNumber($dadosRequest['codigo_barras']),
            ];

            $financeiroFluxoModel->where($financeiroFluxoModel->uuidColumn, $uuid)->set($dadosFluxo)->update();
            $this->db->transComplete();
            $this->nativeSession->setFlashData('success', lang('Success.default.atualizado', ['Fluxo']));
        } catch (Exception $e) {
            $this->nativeSession->setFlashData('error', lang('Errors.banco.validaUpdate'));
            return redirect()->back()->withInput();
        }

        return redirect()->to(base_url("financeiro"));
    }

    /**
     * Marca o Fluxo como PAGO
     * @param string $uuid UUID do Registro
     * @return \CodeIgniter\HTTP\Response
     */
    public function marcarPago(string $uuid): Response
    {
        if (!$this->verificarUuid($uuid)) {
            return $this->response->setJSON(['mensagem' => lang('Errors.geral.validaUuid')], 400);
        }

        $financeiroFluxoModel = new FinanceiroFluxoModel;
        $dadosUsuario = $this->nativeSession->get("usuario");

        //Inicia as operações de DB
        $this->db->transStart();
        try {
            $dadosFluxo = [
                'usuario_alteracao' => $dadosUsuario['codigo_usuario'],
                'data_alteracao'    => "NOW()",
                'data_pagamento'    => "NOW()",
                'situacao'          => "t",
            ];
            $financeiroFluxoModel->where($financeiroFluxoModel->uuidColumn, $uuid)->set($dadosFluxo)->update();
            $this->db->transComplete();
        } catch (Exception $e) {
            return $this->response->setJSON(['mensagem' => lang('Errors.banco.validaUpdate')], 422);
        }

        return $this->response->setJSON(['mensagem' => lang('Success.fluxo.pago')], 202);
    }

    /**
     * Marca o Fluxo como PENDENTE
     * @param string $uuid UUID do Registro
     * @return \CodeIgniter\HTTP\Response
     */
    public function marcarPendente(string $uuid): Response
    {
        if (!$this->verificarUuid($uuid)) {
            return $this->response->setJSON(['mensagem' => lang('Errors.geral.validaUuid')], 400);
        }

        $financeiroFluxoModel = new FinanceiroFluxoModel;
        $dadosUsuario = $this->nativeSession->get("usuario");

        //Inicia as operações de DB
        $this->db->transStart();
        try {
            $dadosFluxo = [
                'usuario_alteracao' => $dadosUsuario['codigo_usuario'],
                'data_alteracao'    => "NOW()",
                'data_pagamento'    => null,
                'situacao'          => "f",
            ];
            $financeiroFluxoModel->where($financeiroFluxoModel->uuidColumn, $uuid)->set($dadosFluxo)->update();
            $this->db->transComplete();
        } catch (Exception $e) {
            return $this->response->setJSON(['mensagem' => lang('Errors.banco.validaUpdate')], 422);
        }

        return $this->response->setJSON(['mensagem' => lang('Success.fluxo.pendente')], 202);
    }

    /**
     * Realizar um Pagamento Parcial no Fluxo
     * @param string $uuid UUID do Registro
     * @return \CodeIgniter\HTTP\Response
     */
    public function pagarParcial(string $uuid): Response
    {
        if (!$this->verificarUuid($uuid)) {
            return $this->response->setJSON(['mensagem' => lang('Errors.geral.validaUuid')], 400);
        }

        $financeiroFluxoModel = new FinanceiroFluxoModel;
        $financeiroFluxoParcialModel = new FinanceiroFluxoParcialModel;
        $dadosRequest = convertEmptyToNull($this->request->getVar());
        $dadosUsuario = $this->nativeSession->get("usuario");
        $dadosEmpresa = $this->nativeSession->get("empresa");

        // Recebe os dados
        parse_str($dadosRequest['dados'], $dados);

        //Inicia as operações de DB
        $this->db->transStart();
        try {
            // Busca o código do Fluxo
            $fluxo = $financeiroFluxoModel->get([$financeiroFluxoModel->uuidColumn => $uuid], ['codigo_financeiro_fluxo'], true);

            $dadosFluxoParcial = [
                'codigo_empresa'                   => $dadosEmpresa['codigo_empresa'],
                'usuario_criacao'                  => $dadosUsuario['codigo_usuario'],
                'codigo_financeiro_fluxo'          => $fluxo['codigo_financeiro_fluxo'],
                'codigo_cadastro_metodo_pagamento' => onlyNumber($dados['codigo_cadastro_metodo_pagamento']),
                'data_pagamento'                   => $dados['data_pagamento'],
                'valor'                            => onlyNumber($dados['valor'])
            ];

            $financeiroFluxoParcialModel->save($dadosFluxoParcial);
            $this->db->transComplete();
        } catch (Exception $e) {
            return $this->response->setJSON(['mensagem' => lang('Errors.banco.validaUpdate')], 422);
        }

        return $this->response->setJSON(['mensagem' => lang('Success.fluxo.parcial')], 202);
    }

    /**
     * Remove o Pagamento de um Fluxo Parcial
     * @param string $uuid Uuid do Registro
     * @return \CodeIgniter\HTTP\Response
     */
    public function removerPagamentoParcial(string $uuid): Response
    {
        if (!$this->verificarUuid($uuid)) {
            return $this->response->setJSON(['mensagem' => lang('Errors.geral.validaUuid')], 400);
        }

        $dadosUsuario = $this->nativeSession->get("usuario");
        $financeiroFluxoParcialModel = new FinanceiroFluxoParcialModel;

        try {
            $financeiroFluxoParcialModel->customSoftDelete($uuid, $dadosUsuario['codigo_usuario'], true);
        } catch (Exception $e) {
            return $this->response->setJSON(['mensagem' => lang('Errors.banco.validaUpdate')], 422);
        }

        return $this->response->setJSON(['mensagem' => lang('Success.default.removido', ['Fluxo Parcial'])], 202);
    }

    /**
     * Abate valores em aberto de um Cliente
     * @return \CodeIgniter\HTTP\Response
     */
    public function abaterValores(): Response
    {
        $clienteModel = new ClienteModel;
        $financeiroFluxoModel = new FinanceiroFluxoModel;
        $financeiroFluxoParcialModel = new FinanceiroFluxoParcialModel;
        $dadosRequest = convertEmptyToNull($this->request->getVar());
        $dadosUsuario = $this->nativeSession->get("usuario");
        $dadosEmpresa = $this->nativeSession->get("empresa");

        //Inicia as operações de DB
        $this->db->transStart();
        try {

            // Valida o valor em aberto do cliente
            $valor = $clienteModel->selectValorEmAberto(['clienteUuid' => $dadosRequest['uuid_cliente']]);

            if (empty($valor)) {
                $this->nativeSession->setFlashData('error', lang('Errors.fluxo.semValorAbater'));
                return redirect()->to(base_url("cliente"));
            }

            // Resgata os Fluxos em aberto do Cliente
            $fluxos = $financeiroFluxoModel->selectFluxosEmAberto(['clienteUuid' => $dadosRequest['uuid_cliente']]);

            if (empty($fluxos)) {
                $this->nativeSession->setFlashData('error', lang('Errors.fluxo.semValorAbater'));
                return redirect()->to(base_url("cliente"));
            }

            // Valor que será abatido
            $valorPagar = onlyNumber($dadosRequest['valor_pagar']);

            foreach ($fluxos as $key => $value) {

                $valorFluxoLiquido = onlyNumber($value['valor_liquido']);
                $valorFluxoParcial = onlyNumber($value['valor_pago_parcial']);
                $valorFluxoReal = ($valorFluxoLiquido - $valorFluxoParcial);

                if ($valorPagar != 0) {
                    // Valor que será descontado desse fluxo
                    if ($valorPagar >= $valorFluxoReal) {
                        $novoValorFluxoReal = $valorFluxoReal;
                        $valorPagar = ($valorPagar - $novoValorFluxoReal);
                    } else {
                        $novoValorFluxoReal = ($valorFluxoReal - $valorPagar);
                        $valorPagar = 0;
                    }

                    if (!empty($novoValorFluxoReal)) {
                        $dadosFluxoParcial = [
                            'codigo_empresa'                   => $dadosEmpresa['codigo_empresa'],
                            'usuario_criacao'                  => $dadosUsuario['codigo_usuario'],
                            'codigo_financeiro_fluxo'          => $value['codigo_financeiro_fluxo'],
                            'codigo_cadastro_metodo_pagamento' => $dadosRequest['codigo_cadastro_metodo_pagamento'],
                            'data_pagamento'                   => "NOW()",
                            'valor'                            => $novoValorFluxoReal
                        ];
                        $financeiroFluxoParcialModel->save($dadosFluxoParcial);
                    }
                }
            }

            $this->db->transComplete();
        } catch (Exception $e) {
            var_dump($e);
            die;
            $this->nativeSession->setFlashData('error', lang('Errors.banco.validaUpdate'));
            return redirect()->to(base_url("cliente"));
        }

        $this->nativeSession->setFlashData('success', lang('Success.fluxo.abatido'));
        return redirect()->to(base_url("cliente"));
    }

    /**
     * Realiza as chamadas assincronas direto para a Model
     * @param string $function
     */
    public function backendCall(string $function)
    {
        try {
            $request = $this->request->getVar();
            return $this->response->setJSON((new FinanceiroFluxoModel())->$function($request));
        } catch (Exception $e) {
            var_dump($e);
        }
    }
}
