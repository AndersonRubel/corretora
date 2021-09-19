<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\RedirectResponse;
use Exception;

use App\Models\Cadastro\CadastroMovimentacaoTipoModel;
use App\Models\Estoque\EstoqueModel;
use App\Models\Estoque\EstoqueProdutoModel;
use App\Models\Estoque\EstoqueEntradaModel;
use App\Models\Estoque\EstoqueBaixaModel;
use App\Models\Estoque\EstoqueHistoricoModel;
use App\Models\Estoque\EstoqueHistoricoItemModel;
use App\Models\Produto\ProdutoModel;
use App\Models\Cliente\ClienteModel;
use App\Models\Empresa\EmpresaModel;
use App\Models\Vendedor\VendedorModel;


class EstoqueController extends BaseController
{
    //////////////////////////////////
    //                              //
    //      OPERAÇÕES DE BUSCA      //
    //                              //
    //////////////////////////////////

    /**
     * Exibe a Tela de Estoque
     * @return html
     */
    public function index()
    {
        return $this->template('estoque', ['index', 'functions']);
    }

    /**
     * Exibe a Tela de Baixas do Estoque
     * @return html
     */
    public function indexBaixa()
    {
        return $this->template('estoque', ['baixa/index', 'baixa/functions']);
    }

    /**
     * Exibe a Tela de Entradas do Estoque
     * @return html
     */
    public function indexEntrada()
    {
        return $this->template('estoque', ['entrada/index', 'entrada/functions']);
    }

    /**
     * Exibe a Tela de Histórico do Estoque
     * @return html
     */
    public function indexHistorico()
    {
        return $this->template('estoque', ['historico/index', 'historico/functions']);
    }

    /**
     * Exibe a tela de Histórico de um Item
     * @param string $uuid UUID do Registro
     * @param string $onlyDay Exibe o Dia todo de Movimentação
     * @return html
     */
    public function indexHistoricoItem(string $uuid, string $onlyDay = "t")
    {
        if (!$this->verificarUuid($uuid)) {
            $this->nativeSession->setFlashData('error', lang('Errors.geral.validaUuid'));
            return redirect()->to(base_url("estoque/historico"));
        }

        $dados = ['uuid_estoque_historico' => $uuid, 'estoque_historico_dia' => $onlyDay];
        return $this->template('estoque', ['historico/indexItem', 'historico/functions'], $dados);
    }

    /**
     * Exibe a Tela de Adicionar Registro
     * @return html
     */
    public function adicionar()
    {
        return $this->template('estoque', ['entrada/create', 'entrada/functions']);
    }

    /**
     * Exibe a Tela de Baixar Registro
     * @return html
     */
    public function baixar()
    {
        return $this->template('estoque', ['baixa/create', 'baixa/functions']);
    }

    /**
     * Exibe a Tela de Transferir Registro
     * @return html
     */
    public function transferir()
    {
        return $this->template('estoque', ['transferencia/index', 'transferencia/functions']);
    }

    /**
     * Exibe a Tela de Devolver Produto ao Fornecedor
     * @return html
     */
    public function devolver()
    {
        return $this->template('estoque', ['devolucao/create', 'devolucao/functions']);
    }

    /**
     * Busca os registros para o Datagrid
     * @param int $status Verifica se a informação está ativa (1 ou 0)
     */
    public function getDataGrid(int $status)
    {
        try {
            $estoqueModel = new EstoqueModel;
            $dadosRequest = $this->request->getVar();
            $dadosRequest['status'] = $status;
            $data = $estoqueModel->getDataGrid($dadosRequest);
            return $this->responseDataGrid($data, $dadosRequest);
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    /**
     * Busca os registros para o Datagrid de Ver Entradas
     */
    public function getDataGridEntrada()
    {
        try {
            $estoqueEntradaModel = new EstoqueEntradaModel;
            $dadosRequest = $this->request->getVar();
            $data = $estoqueEntradaModel->getDataGrid($dadosRequest);
            return $this->responseDataGrid($data, $dadosRequest);
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    /**
     * Busca os registros para o Datagrid de Ver Baixas
     */
    public function getDataGridBaixa()
    {
        try {
            $estoqueBaixaModel = new EstoqueBaixaModel;
            $dadosRequest = $this->request->getVar();
            $data = $estoqueBaixaModel->getDataGrid($dadosRequest);
            return $this->responseDataGrid($data, $dadosRequest);
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    /**
     * Busca os registros para o Datagrid de Histórico
     */
    public function getDataGridHistorico()
    {
        try {
            $estoqueHistoricoModel = new EstoqueHistoricoModel;
            $dadosRequest = $this->request->getVar();
            $data = $estoqueHistoricoModel->getDataGrid($dadosRequest);
            return $this->responseDataGrid($data, $dadosRequest);
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    /**
     * Busca os registros para o Datagrid de Histórico de Item
     * @param string $uuid UUID do Registro
     * @param string $onlyDay Exibe o Dia todo de Movimentação
     */
    public function getDataGridHistoricoItem(string $uuid, string $onlyDay = "t")
    {
        try {
            $estoqueHistoricoItemModel = new EstoqueHistoricoItemModel;
            $dadosRequest = $this->request->getVar();
            $dadosRequest['uuid'] = $uuid;
            $dadosRequest['onlyDay'] = $onlyDay;
            $data = $estoqueHistoricoItemModel->getDataGrid($dadosRequest);
            return $this->responseDataGrid($data, $dadosRequest);
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    /**
     * Monta o Recibo da Venda
     * @param string $tipo Tipo do Recibo
     * @param string $uuid UUID do Registro
     * @return html
     */
    public function recibo(string $tipo, string $uuid)
    {
        $dados = [];
        $estoqueBaixaModel = new EstoqueBaixaModel;
        $estoqueEntradaModel = new EstoqueEntradaModel;
        $clienteModel = new ClienteModel;
        $empresaModel = new EmpresaModel;
        $vendedorModel = new VendedorModel;
        $produtoModel = new ProdutoModel;

        switch ($tipo) {
            case 'entrada':
                $dados['movimentacao'] = $estoqueEntradaModel->get(['uuid_estoque_entrada' => $uuid], [], true);
                break;
            case 'baixa':
                $dados['movimentacao'] = $estoqueBaixaModel->get(['uuid_estoque_baixa' => $uuid], [], true);
                break;
            default:
                # code...
                break;
        }

        if (!empty($dados['movimentacao']['codigo_empresa'])) {
            $dados['empresa'] = $empresaModel->get(['codigo_empresa' => $dados['movimentacao']['codigo_empresa']], [], true);
            $dados['empresa']['endereco'] = json_decode($dados['empresa']['endereco'], true);
        } else {
            $dados['empresa'] = "";
        }

        $dados['produto']   = $produtoModel->get(['codigo_produto' => $dados['movimentacao']['codigo_produto']], [], true);
        $dados['produto']['quantidade'] =  $dados['movimentacao']['quantidade'];
        echo view('app/estoque/recibo', $dados);
    }

    //////////////////////////////////
    //                              //
    //    OPERAÇÕES DE CADASTRO     //
    //                              //
    //////////////////////////////////

    /**
     * Realiza a entrada de produto no Estoque
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function realizarEntrada(): RedirectResponse
    {
        $estoqueProdutoModel = new EstoqueProdutoModel;
        $estoqueEntradaModel = new EstoqueEntradaModel;
        $cadastroMovimentacaoTipoModel = new CadastroMovimentacaoTipoModel;
        $produtoModel = new ProdutoModel;
        $dadosRequest = convertEmptyToNull($this->request->getVar());
        $dadosUsuario = $this->nativeSession->get("usuario");
        $dadosEmpresa = $this->nativeSession->get("empresa");

        $erros = $this->validarRequisicao($this->request, [
            'codigo_empresa' => 'required|integer',
            'codigo_estoque' => 'required|integer',
            'codigo_produto' => 'required|integer',
            'valor_fabrica' => 'required|string',
            'valor_venda' => 'required|string',
            'valor_ecommerce' => 'permit_empty|string',
            'valor_atacado' => 'permit_empty|string',
            'quantidade_atacado' => 'permit_empty|integer',
            'nova_quantidade' => 'required|integer',
            'observacao' => 'permit_empty|string'
        ]);

        if (!empty($erros)) {
            $this->nativeSession->setFlashData('error', formataErros($erros));
            return redirect()->back()->withInput();
        }

        //Inicia as operações de DB
        $this->db->transStart();
        try {

            // Busca o ultimo valor de lote cadastrado na empresa
            $movimentacaoLote = $estoqueEntradaModel->get(['codigo_empresa' => $dadosEmpresa['codigo_empresa']], ["COALESCE(MAX(movimentacao_lote), 0) AS valor"], true);

            // Prepara os dados para atualizar o estoque atual
            $novoEstoque = [
                'valor_fabrica'      => onlyNumber($dadosRequest['valor_fabrica']),
                'valor_venda'        => onlyNumber($dadosRequest['valor_venda']),
                'valor_ecommerce'    => onlyNumber($dadosRequest['valor_ecommerce']),
                'valor_atacado'      => onlyNumber($dadosRequest['valor_atacado']),
                'quantidade_atacado' => onlyNumber($dadosRequest['quantidade_atacado']),
                'ultima_compra'      => "NOW()"
            ];

            // Busca esse Produto, nesse estoque
            $produtoEstoque = $estoqueProdutoModel->get(['codigo_estoque' => $dadosRequest['codigo_estoque'], 'codigo_produto' => $dadosRequest['codigo_produto']], [], true);

            // Se não encontrar registro adiciona um novo
            if (empty($produtoEstoque)) {
                $novoEstoque['codigo_empresa']  = $dadosEmpresa['codigo_empresa'];
                $novoEstoque['codigo_estoque']  = $dadosRequest['codigo_estoque'];
                $novoEstoque['codigo_produto']  = $dadosRequest['codigo_produto'];
                $novoEstoque['usuario_criacao'] = $dadosUsuario['codigo_usuario'];
                $novoEstoque['estoque_atual']   = onlyNumber($dadosRequest['nova_quantidade']);
            } else {
                $novoEstoque['codigo_estoque_produto'] = $produtoEstoque['codigo_estoque_produto'];
                $novoEstoque['usuario_alteracao'] = $dadosUsuario['codigo_usuario'];
                $novoEstoque['alterado_em'] = 'NOW()';

                // Pega o estoque atual e adiciona a nova quantidade
                $novoEstoque['estoque_atual'] = onlyNumber($produtoEstoque['estoque_atual']) + onlyNumber($dadosRequest['nova_quantidade']);
            }

            $estoqueProdutoModel->save($novoEstoque);

            // Busca as Informações adicionais para inserir o registro dessa Movimentação de Entrada
            $movimentacaoTipo = $cadastroMovimentacaoTipoModel->get(['codigo_cadastro_movimentacao_tipo' => 1], ['codigo_cadastro_movimentacao_tipo', 'nome'], true);
            $produto = $produtoModel->get(['codigo_produto' => $dadosRequest['codigo_produto']], ['codigo_fornecedor'], true);
            $movimentacaoEntrada = [
                'codigo_empresa'                    => $dadosEmpresa['codigo_empresa'],
                'usuario_criacao'                   => $dadosUsuario['codigo_usuario'],
                'codigo_estoque'                    => $dadosRequest['codigo_estoque'],
                'codigo_produto'                    => $dadosRequest['codigo_produto'],
                'codigo_fornecedor'                 => $produto['codigo_fornecedor'],
                'codigo_cadastro_movimentacao_tipo' => $movimentacaoTipo['codigo_cadastro_movimentacao_tipo'],
                'nome_cadastro_movimentacao_tipo'   => $movimentacaoTipo['nome'],
                'quantidade'                        => onlyNumber($dadosRequest['nova_quantidade']),
                'observacao'                        => $dadosRequest['observacao'],
                'movimentacao_lote'                 => onlyNumber($movimentacaoLote['valor'])
            ];

            // Insere um registro de Movimentação do tipo Entrada
            $estoqueEntradaModel->save($movimentacaoEntrada);

            $this->db->transComplete();
            $this->nativeSession->setFlashData('success', lang('Success.estoque.entrada'));
        } catch (Exception $e) {
            $this->nativeSession->setFlashData('error', lang('Errors.banco.validaInsercao'));
            return redirect()->back()->withInput();
        }

        return redirect()->to(base_url("estoque"));
    }

    /**
     * Realiza a baixa de produto no Estoque
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function realizarBaixa(): RedirectResponse
    {
        $estoqueProdutoModel = new EstoqueProdutoModel;
        $estoqueBaixaModel = new EstoqueBaixaModel;
        $cadastroMovimentacaoTipoModel = new CadastroMovimentacaoTipoModel;
        $produtoModel = new ProdutoModel;
        $dadosRequest = convertEmptyToNull($this->request->getVar());
        $dadosUsuario = $this->nativeSession->get("usuario");
        $dadosEmpresa = $this->nativeSession->get("empresa");

        $erros = $this->validarRequisicao($this->request, [
            'codigo_empresa' => 'required|integer',
            'codigo_estoque' => 'required|integer',
            'codigo_produto' => 'required|integer',
            'nova_quantidade' => 'required|integer',
            'observacao' => 'permit_empty|string',
            'observacao_rapida' => 'permit_empty|string'
        ]);

        if (!empty($erros)) {
            $this->nativeSession->setFlashData('error', formataErros($erros));
            return redirect()->back()->withInput();
        }

        //Inicia as operações de DB
        $this->db->transStart();
        try {

            // Busca o ultimo valor de lote cadastrado na empresa
            $movimentacaoLote = $estoqueBaixaModel->get(['codigo_empresa' => $dadosEmpresa['codigo_empresa']], ["COALESCE(MAX(movimentacao_lote), 0) AS valor"], true);

            // Busca esse Produto, nesse estoque
            $produtoEstoque = $estoqueProdutoModel->get(['codigo_estoque' => $dadosRequest['codigo_estoque'], 'codigo_produto' => $dadosRequest['codigo_produto']], [], true);

            // Valida se a quantidade não é superior a que tem no estoque
            if (onlyNumber($dadosRequest['nova_quantidade']) > onlyNumber($produtoEstoque['estoque_atual'])) {
                $this->nativeSession->setFlashData('error', lang('Errors.estoque.estoqueQuantidadeInvalida'));
                return redirect()->back()->withInput();
            }

            // Prepara os dados para atualizar o estoque atual
            $novoEstoque = [
                'codigo_empresa'         => $dadosEmpresa['codigo_empresa'],
                'usuario_alteracao'      => $dadosUsuario['codigo_usuario'],
                'alterado_em'            => 'NOW()',
                'codigo_estoque'         => $dadosRequest['codigo_estoque'],
                'codigo_produto'         => $dadosRequest['codigo_produto'],
                'codigo_estoque_produto' => $produtoEstoque['codigo_estoque_produto'],
                'estoque_atual'          => onlyNumber($produtoEstoque['estoque_atual']) - onlyNumber($dadosRequest['nova_quantidade']),
            ];
            $estoqueProdutoModel->save($novoEstoque);

            // Busca as Informações adicionais para inserir o registro dessa Movimentação de Baixa
            $movimentacaoTipo = $cadastroMovimentacaoTipoModel->get(['codigo_cadastro_movimentacao_tipo' => 2], ['codigo_cadastro_movimentacao_tipo', 'nome'], true);
            $produto = $produtoModel->get(['codigo_produto' => $dadosRequest['codigo_produto']], ['codigo_fornecedor'], true);
            $movimentacaoBaixa = [
                'codigo_empresa'                    => $dadosEmpresa['codigo_empresa'],
                'usuario_criacao'                   => $dadosUsuario['codigo_usuario'],
                'codigo_estoque'                    => $dadosRequest['codigo_estoque'],
                'codigo_produto'                    => $dadosRequest['codigo_produto'],
                'codigo_fornecedor'                 => $produto['codigo_fornecedor'],
                'codigo_cadastro_movimentacao_tipo' => $movimentacaoTipo['codigo_cadastro_movimentacao_tipo'],
                'nome_cadastro_movimentacao_tipo'   => $movimentacaoTipo['nome'],
                'quantidade'                        => onlyNumber($dadosRequest['nova_quantidade']),
                'observacao'                        => "[{$dadosRequest['observacao_rapida']}] - {$dadosRequest['observacao']}",
                'movimentacao_lote'                 => onlyNumber($movimentacaoLote['valor'])
            ];
            $estoqueBaixaModel->save($movimentacaoBaixa);

            $this->db->transComplete();
            $this->nativeSession->setFlashData('success', lang('Success.estoque.baixa'));
        } catch (Exception $e) {
            $this->nativeSession->setFlashData('error', lang('Errors.banco.validaInsercao'));
            return redirect()->back()->withInput();
        }

        return redirect()->to(base_url("estoque"));
    }

    /**
     * Realiza a Transferencia de Produtos entre vendedores
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function realizarTransferencia(): RedirectResponse
    {
        $estoqueProdutoModel = new EstoqueProdutoModel;
        $estoqueBaixaModel = new EstoqueBaixaModel;
        $estoqueEntradaModel = new EstoqueEntradaModel;
        $cadastroMovimentacaoTipoModel = new CadastroMovimentacaoTipoModel;
        $produtoModel = new ProdutoModel;
        $dadosRequest = convertEmptyToNull($this->request->getVar());
        $dadosEmpresa = $this->nativeSession->get("empresa");
        $dadosUsuario = $this->nativeSession->get("usuario");

        $erros = $this->validarRequisicao($this->request, [
            'de_codigo_empresa' => 'required|integer',
            'para_codigo_empresa' => 'required|integer',
            'transferencia_de_codigo_estoque' => 'required|integer',
            'transferencia_para_codigo_estoque' => 'required|integer'
        ]);

        if (!empty($erros)) {
            $this->nativeSession->setFlashData('error', formataErros($erros));
            return redirect()->to(base_url("estoque"));
        }

        // Valida se veio os produtos na lista
        if (empty($dadosRequest['transferencia_produto_codigo']) || empty($dadosRequest['transferencia_produto_quantidade'])) {
            $this->nativeSession->setFlashData('error', lang('Errors.estoque.transferenciaSemProdutos'));
            return redirect()->to(base_url("estoque"));
        }

        //Inicia as operações de DB
        $this->db->transStart();
        try {

            // Busca o ultimo valor de lote cadastrado na empresa
            $movimentacaoLoteBaixa = $estoqueBaixaModel->get(['codigo_empresa' => $dadosEmpresa['codigo_empresa']], ["COALESCE(MAX(movimentacao_lote), 0) AS valor"], true);
            $movimentacaoLoteEntrada = $estoqueEntradaModel->get(['codigo_empresa' => $dadosEmpresa['codigo_empresa']], ["COALESCE(MAX(movimentacao_lote), 0) AS valor"], true);

            // Percorre os produtos
            foreach ($dadosRequest['transferencia_produto_codigo'] as $key => $value) {

                $quantidadeASerTransferida = $dadosRequest['transferencia_produto_quantidade'][$key];

                /////// INICIO :: TRANSFERENCIA DE - BAIXA ///////

                // Busca esse Produto, nesse estoque
                $produtoEstoqueDe = $estoqueProdutoModel->get(['codigo_estoque' => $dadosRequest['transferencia_de_codigo_estoque'], 'codigo_produto' => $value], [], true);

                // Valida se a quantidade não é superior a que tem no estoque
                if (onlyNumber($quantidadeASerTransferida) > onlyNumber($produtoEstoqueDe['estoque_atual'])) {
                    $this->nativeSession->setFlashData('error', lang('Errors.estoque.estoqueQuantidadeInvalida'));
                    return redirect()->to(base_url("estoque"));
                }

                // Prepara os dados para atualizar o estoque de quem esta transferindo
                $novoEstoqueDe = [
                    'codigo_empresa'         => $dadosRequest['de_codigo_empresa'],
                    'usuario_alteracao'      => $dadosUsuario['codigo_usuario'],
                    'alterado_em'            => 'NOW()',
                    'codigo_estoque'         => $dadosRequest['transferencia_de_codigo_estoque'],
                    'codigo_produto'         => $value,
                    'codigo_estoque_produto' => $produtoEstoqueDe['codigo_estoque_produto'],
                    'estoque_atual'          => onlyNumber($produtoEstoqueDe['estoque_atual']) - onlyNumber($quantidadeASerTransferida),
                ];
                $estoqueProdutoModel->save($novoEstoqueDe);

                // Busca as Informações adicionais para inserir o registro dessa Movimentação de Transferencia
                $movimentacaoTipo = $cadastroMovimentacaoTipoModel->get(['codigo_cadastro_movimentacao_tipo' => 3], ['codigo_cadastro_movimentacao_tipo', 'nome'], true);
                $produto = $produtoModel->get(['codigo_produto' => $value], ['codigo_fornecedor'], true);
                $movimentacaoBaixa = [
                    'codigo_empresa'                    => $dadosRequest['de_codigo_empresa'],
                    'usuario_criacao'                   => $dadosUsuario['codigo_usuario'],
                    'codigo_estoque'                    => $dadosRequest['transferencia_de_codigo_estoque'],
                    'codigo_produto'                    => $value,
                    'codigo_fornecedor'                 => $produto['codigo_fornecedor'],
                    'codigo_cadastro_movimentacao_tipo' => $movimentacaoTipo['codigo_cadastro_movimentacao_tipo'],
                    'nome_cadastro_movimentacao_tipo'   => $movimentacaoTipo['nome'],
                    'quantidade'                        => onlyNumber($quantidadeASerTransferida),
                    'movimentacao_lote'                 => onlyNumber($movimentacaoLoteBaixa['valor'])
                ];
                $estoqueBaixaModel->save($movimentacaoBaixa);
                /////// FIM :: TRANSFERENCIA DE - BAIXA ///////


                /////// INICIO :: TRANSFERENCIA PARA - ENTRADA ///////

                // Busca esse Produto, nesse estoque
                $produtoEstoquePara = $estoqueProdutoModel->get(['codigo_estoque' => $dadosRequest['transferencia_para_codigo_estoque'], 'codigo_produto' => $value], [], true);

                // Prepara os dados para atualizar o estoque de quem recebera a transferencia
                $novoEstoquePara = [
                    'codigo_empresa'         => $dadosRequest['para_codigo_empresa'],
                    'usuario_alteracao'      => $dadosUsuario['codigo_usuario'],
                    'alterado_em'            => 'NOW()',
                    'codigo_estoque'         => $dadosRequest['transferencia_para_codigo_estoque'],
                    'codigo_produto'         => $value,
                ];

                // Se o Produto existir no estoque de destino
                if (!empty($produtoEstoquePara['codigo_estoque_produto'])) {
                    $novoEstoquePara['codigo_estoque_produto'] = $produtoEstoquePara['codigo_estoque_produto'];
                    $novoEstoquePara['estoque_atual']          = onlyNumber($produtoEstoquePara['estoque_atual']) + onlyNumber($quantidadeASerTransferida);
                } else {
                    $novoEstoquePara['estoque_atual']          = onlyNumber($quantidadeASerTransferida);
                }

                $estoqueProdutoModel->save($novoEstoquePara);

                // Busca as Informações adicionais para inserir o registro dessa Movimentação de Baixa
                $movimentacaoTipo = $cadastroMovimentacaoTipoModel->get(['codigo_cadastro_movimentacao_tipo' => 1], ['codigo_cadastro_movimentacao_tipo', 'nome'], true);
                $produto = $produtoModel->get(['codigo_produto' => $value], ['codigo_fornecedor'], true);
                $movimentacaoEntrada = [
                    'codigo_empresa'                    => $dadosRequest['para_codigo_empresa'],
                    'usuario_criacao'                   => $dadosUsuario['codigo_usuario'],
                    'codigo_estoque'                    => $dadosRequest['transferencia_para_codigo_estoque'],
                    'codigo_produto'                    => $value,
                    'codigo_fornecedor'                 => $produto['codigo_fornecedor'],
                    'codigo_cadastro_movimentacao_tipo' => $movimentacaoTipo['codigo_cadastro_movimentacao_tipo'],
                    'nome_cadastro_movimentacao_tipo'   => $movimentacaoTipo['nome'],
                    'quantidade'                        => onlyNumber($quantidadeASerTransferida),
                    'observacao'                        => "Transferência do Estoque ",
                    'movimentacao_lote'                 => onlyNumber($movimentacaoLoteEntrada['valor'])
                ];
                $estoqueEntradaModel->save($movimentacaoEntrada);

                /////// FIM :: TRANSFERENCIA PARA - ENTRADA ///////
            }

            $this->db->transComplete();
            $this->nativeSession->setFlashData('success', lang('Success.estoque.transferencia'));
        } catch (Exception $e) {
            var_dump($e);
            die;
            $this->nativeSession->setFlashData('error', lang('Errors.estoque.transferencia'));
        }

        return redirect()->to(base_url("estoque"));
    }

    /**
     * Realiza a Devolução de Produtos para o Fornecedor
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function realizarDevolucao(): RedirectResponse
    {
        $estoqueProdutoModel = new EstoqueProdutoModel;
        $estoqueBaixaModel = new EstoqueBaixaModel;
        $cadastroMovimentacaoTipoModel = new CadastroMovimentacaoTipoModel;
        $produtoModel = new ProdutoModel;
        $dadosRequest = convertEmptyToNull($this->request->getVar());
        $dadosEmpresa = $this->nativeSession->get("empresa");
        $dadosUsuario = $this->nativeSession->get("usuario");

        $erros = $this->validarRequisicao($this->request, [
            'codigo_empresa' => 'required|integer',
            'codigo_estoque' => 'required|integer',
        ]);

        if (!empty($erros)) {
            $this->nativeSession->setFlashData('error', formataErros($erros));
            return redirect()->to(base_url("estoque"));
        }

        // Valida se veio os produtos na lista
        if (empty($dadosRequest['produto_codigo']) || empty($dadosRequest['produto_quantidade'])) {
            $this->nativeSession->setFlashData('error', lang('Errors.estoque.devolucaoSemProdutos'));
            return redirect()->to(base_url("estoque"));
        }

        //Inicia as operações de DB
        $this->db->transStart();
        try {

            // Busca o ultimo valor de lote cadastrado na empresa
            $movimentacaoLote = $estoqueBaixaModel->get(['codigo_empresa' => $dadosEmpresa['codigo_empresa']], ["COALESCE(MAX(movimentacao_lote), 0) AS valor"], true);

            // Percorre os produtos
            foreach ($dadosRequest['produto_codigo'] as $key => $value) {

                $quantidadeASerDevolvido = $dadosRequest['produto_quantidade'][$key];

                /////// INICIO :: DEVOLUÇÃO - BAIXA ///////

                // Busca esse Produto, nesse estoque
                $produtoEstoque = $estoqueProdutoModel->get(['codigo_estoque' => $dadosRequest['codigo_estoque'], 'codigo_produto' => $value], [], true);

                // Valida se a quantidade não é superior a que tem no estoque
                if (onlyNumber($quantidadeASerDevolvido) > onlyNumber($produtoEstoque['estoque_atual'])) {
                    $this->nativeSession->setFlashData('error', lang('Errors.estoque.estoqueQuantidadeInvalida'));
                    return redirect()->to(base_url("estoque"));
                }

                // Prepara os dados para atualizar o estoque de quem esta devolvendo
                $novoEstoque = [
                    'codigo_empresa'         => $dadosRequest['codigo_empresa'],
                    'usuario_alteracao'      => $dadosUsuario['codigo_usuario'],
                    'alterado_em'            => 'NOW()',
                    'codigo_estoque'         => $dadosRequest['codigo_estoque'],
                    'codigo_produto'         => $value,
                    'codigo_estoque_produto' => $produtoEstoque['codigo_estoque_produto'],
                    'estoque_atual'          => onlyNumber($produtoEstoque['estoque_atual']) - onlyNumber($quantidadeASerDevolvido),
                ];
                $estoqueProdutoModel->save($novoEstoque);

                // Busca as Informações adicionais para inserir o registro dessa Movimentação de Baixa por Devolução
                $movimentacaoTipo = $cadastroMovimentacaoTipoModel->get(['codigo_cadastro_movimentacao_tipo' => 5], ['codigo_cadastro_movimentacao_tipo', 'nome'], true);
                $produto = $produtoModel->get(['codigo_produto' => $value], ['codigo_fornecedor'], true);
                $movimentacaoBaixa = [
                    'codigo_empresa'                    => $dadosRequest['codigo_empresa'],
                    'usuario_criacao'                   => $dadosUsuario['codigo_usuario'],
                    'codigo_estoque'                    => $dadosRequest['codigo_estoque'],
                    'codigo_produto'                    => $value,
                    'codigo_fornecedor'                 => $produto['codigo_fornecedor'],
                    'codigo_cadastro_movimentacao_tipo' => $movimentacaoTipo['codigo_cadastro_movimentacao_tipo'],
                    'nome_cadastro_movimentacao_tipo'   => $movimentacaoTipo['nome'],
                    'quantidade'                        => onlyNumber($quantidadeASerDevolvido),
                    'movimentacao_lote'                 => onlyNumber($movimentacaoLote['valor'])
                ];
                $estoqueBaixaModel->save($movimentacaoBaixa);
                /////// FIM :: DEVOLUÇÃO - BAIXA ///////

            }

            $this->db->transComplete();
            $this->nativeSession->setFlashData('success', lang('Success.estoque.devolucao'));
        } catch (Exception $e) {
            var_dump($e);
            die;
            $this->nativeSession->setFlashData('error', lang('Errors.estoque.devolucao'));
        }

        return redirect()->to(base_url("estoque"));
    }

    /**
     * Realiza as chamadas assincronas direto para a Model
     * @param string $function
     */
    public function backendCall(string $function)
    {
        try {
            $request = $this->request->getVar();
            return $this->response->setJSON((new EstoqueModel)->$function($request));
        } catch (Exception $e) {
            var_dump($e);
        }
    }
}
