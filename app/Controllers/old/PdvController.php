<?php

namespace App\Controllers;

use App\Models\Cadastro\CadastroMovimentacaoTipoModel;
use App\Models\Estoque\EstoqueBaixaModel;
use App\Models\Estoque\EstoqueModel;
use App\Models\Estoque\EstoqueProdutoModel;
use App\Models\Produto\ProdutoModel;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\RedirectResponse;
use Exception;

use App\Models\Venda\PdvModel;
use App\Models\Venda\VendaModel;
use App\Models\Venda\VendaProdutoModel;

class PdvController extends BaseController
{
    //////////////////////////////////
    //                              //
    //      OPERAÇÕES DE BUSCA      //
    //                              //
    //////////////////////////////////

    /**
     * Exibe a Tela de PDV
     * @return html
     */
    public function index()
    {
        return $this->template('pdv', ['index', 'modal', 'functions'], [], false, false);
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
        $cadastroMovimentacaoTipoModel = new CadastroMovimentacaoTipoModel;
        $estoqueModel = new EstoqueModel;
        $estoqueBaixaModel = new EstoqueBaixaModel;
        $estoqueProdutoModel = new EstoqueProdutoModel;
        $produtoModel = new ProdutoModel;
        $vendaModel = new VendaModel;
        $vendaProdutoModel = new VendaProdutoModel;
        $dadosRequest = convertEmptyToNull($this->request->getVar());
        $dadosUsuario = $this->nativeSession->get("usuario");
        $dadosEmpresa = $this->nativeSession->get("empresa");

        // Verifica se veio o carrinho
        if (empty($dadosRequest['carrinho']) || empty($dadosRequest['carrinho']['codigo_produto'])) {
            $this->nativeSession->setFlashData('error', lang('Errors.pdv.carrinhoVazio'));
            return redirect()->to(base_url("pdv"));
        }

        // Variaveis dos totais da venda
        $valorBrutoVenda    = 0;
        $valorDescontoVenda = 0;
        $valorLiquidoVenda  = 0;

        //Inicia as operações de DB
        $this->db->transStart();
        try {

            // Verifica se os produtos desejados possuem estoque antes de prosseguir
            foreach ($dadosRequest['carrinho']['codigo_produto'] as $key => $value) {
                $possuiQuantidade = $estoqueModel->verificaEstoqueProduto($value);

                // Caso o estoque atual do produto seja menor que o solicitado, cancela a transação
                if (empty($possuiQuantidade) || ($possuiQuantidade[0]['estoque_atual'] < (int) $dadosRequest['carrinho']['quantidade'][$key])) {
                    // Busca o nome do produto, para informar no erro
                    $produto = $produtoModel->get(['codigo_produto' => $value], ['nome'], true);

                    $this->nativeSession->setFlashData('error', lang('Errors.pdv.estoqueInsuficiente', [strtoupper($produto['nome'])]));
                    return redirect()->to(base_url("pdv"));
                }
            }

            // Inicia o Processo de venda
            $vendaDados = [
                'codigo_empresa'                   => $dadosEmpresa['codigo_empresa'],
                'usuario_criacao'                  => $dadosUsuario['codigo_usuario'],
                'codigo_vendedor'                  => $dadosUsuario['codigo_vendedor'],
                'codigo_cliente'                   => !empty($dadosRequest['codigo_cliente']) ? $dadosRequest['codigo_cliente'] : null,
                'codigo_cadastro_metodo_pagamento' => onlyNumber($dadosRequest['codigo_cadastro_metodo_pagamento']),
                'valor_entrada'                    => onlyNumber($dadosRequest['valor_entrada']),
                'valor_troco'                      => onlyNumber($dadosRequest['valor_troco']),
                'parcelas'                         => onlyNumber($dadosRequest['parcelas']),
                'observacao'                       => $dadosRequest['observacao'],
            ];
            $vendaModel->save($vendaDados);

            // Resgata o ID que foi cadastrado
            $codigoVenda = $vendaModel->getInsertID("venda_codigo_venda_seq");

            // Salva os produtos da venda
            foreach ($dadosRequest['carrinho']['codigo_produto'] as $key => $value) {

                // Busca o nome do Produto e sua quantidade no estoque
                $produto = $produtoModel->get(['codigo_produto' => $value], ['nome', 'codigo_fornecedor'], true);
                $produtoEstoque = $estoqueProdutoModel->get(['codigo_estoque' => $dadosUsuario['codigo_estoque'], 'codigo_produto' => $value], ['codigo_estoque_produto', 'estoque_atual'], true);

                // Calcula o Valor do produto
                $qtdeProduto          = onlyNumber($dadosRequest['carrinho']['quantidade'][$key]);
                $valorBrutoProduto    = onlyNumber($dadosRequest['carrinho']['valor_bruto'][$key]);
                $valorDescontoProduto = onlyNumber($dadosRequest['carrinho']['valor_desconto'][$key]);
                $valorLiquidoProduto  = ($valorBrutoProduto * $qtdeProduto) - $valorDescontoProduto;

                // Incrementa o Valor Bruto Total da Venda
                $valorBrutoVenda = ($valorBrutoVenda + ($valorBrutoProduto * $qtdeProduto));

                // Incrementa o Valor de Desconto Total da Venda
                $valorDescontoVenda = ($valorDescontoVenda + $valorDescontoProduto);

                // Incrementa o Valor Liquido Total da Venda
                $valorLiquidoVenda = ($valorLiquidoVenda + $valorLiquidoProduto);

                // Salva o Produto na Venda
                $vendaProduto = [
                    'codigo_empresa'  => $dadosEmpresa['codigo_empresa'],
                    'usuario_criacao' => $dadosUsuario['codigo_usuario'],
                    'codigo_venda'    => $codigoVenda,
                    'codigo_produto'  => $value,
                    'nome_produto'    => $produto['nome'],
                    'quantidade'      => $qtdeProduto,
                    'valor_unitario'  => $valorBrutoProduto,
                    'valor_desconto'  => $valorDescontoProduto,
                    'valor_total'     => $valorLiquidoProduto
                ];
                $vendaProdutoModel->save($vendaProduto);

                // Realiza a Atualização de quantidade no estoque
                $novoEstoque = [
                    'codigo_empresa'         => $dadosEmpresa['codigo_empresa'],
                    'usuario_alteracao'      => $dadosUsuario['codigo_usuario'],
                    'alterado_em'            => 'NOW()',
                    'codigo_estoque'         => $dadosUsuario['codigo_estoque'],
                    'codigo_produto'         => $value,
                    'codigo_estoque_produto' => $produtoEstoque['codigo_estoque_produto'],
                    'estoque_atual'          => onlyNumber($produtoEstoque['estoque_atual']) - onlyNumber($qtdeProduto),
                ];
                $estoqueProdutoModel->save($novoEstoque);

                // Busca as Informações adicionais para inserir o registro dessa Movimentação de Baixa
                $movimentacaoTipo = $cadastroMovimentacaoTipoModel->get(['codigo_cadastro_movimentacao_tipo' => 2], ['codigo_cadastro_movimentacao_tipo', 'nome'], true);
                $movimentacaoBaixa = [
                    'codigo_empresa'                    => $dadosEmpresa['codigo_empresa'],
                    'usuario_criacao'                   => $dadosUsuario['codigo_usuario'],
                    'codigo_estoque'                    => $dadosUsuario['codigo_estoque'],
                    'codigo_produto'                    => $value,
                    'codigo_fornecedor'                 => $produto['codigo_fornecedor'],
                    'codigo_venda'                      => $codigoVenda,
                    'codigo_cadastro_movimentacao_tipo' => $movimentacaoTipo['codigo_cadastro_movimentacao_tipo'],
                    'nome_cadastro_movimentacao_tipo'   => $movimentacaoTipo['nome'],
                    'quantidade'                        => onlyNumber($qtdeProduto),
                    'observacao'                        => "{$dadosRequest['observacao']}"
                ];
                $estoqueBaixaModel->save($movimentacaoBaixa);
            }

            // Atualiza com o valor Liquido
            $vendaModel->save([
                'codigo_venda'   => $codigoVenda,
                'valor_bruto'    => $valorBrutoVenda,
                'valor_desconto' => $valorDescontoVenda,
                'valor_liquido'  => $valorLiquidoVenda
            ]);

            $this->db->transComplete();
            $this->nativeSession->setFlashData('success', lang('Success.pdv.realizada'));
        } catch (Exception $e) {
            print_r($e);
            die();
            $this->nativeSession->setFlashData('error', lang('Errors.pdv.naoRealizada'));
        }

        return redirect()->to(base_url("pdv"));
    }

    /**
     * Realiza as chamadas assincronas direto para a Model
     * @param string $function
     */
    public function backendCall(string $function)
    {
        try {
            $request = $this->request->getVar();
            return $this->response->setJSON((new PdvModel)->$function($request));
        } catch (Exception $e) {
            var_dump($e);
        }
    }
}
