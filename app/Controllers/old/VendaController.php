<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Response;
use Exception;

use App\Models\Cadastro\CadastroMovimentacaoTipoModel;
use App\Models\Cliente\ClienteModel;
use App\Models\Empresa\EmpresaModel;
use App\Models\Estoque\EstoqueBaixaModel;
use App\Models\Estoque\EstoqueEntradaModel;
use App\Models\Estoque\EstoqueProdutoModel;
use App\Models\Faturamento\FaturamentoVendaModel;
use App\Models\Venda\VendaModel;
use App\Models\Venda\VendaProdutoModel;
use App\Models\Vendedor\VendedorModel;

class VendaController extends BaseController
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
        return $this->template('venda', ['index', 'modal', 'functions']);
    }

    /**
     * Monta o Comrovante da Venda
     * @param string $uuid UUID do Registro
     * @return html
     */
    public function comprovante(string $uuid)
    {
        $vendaModel = new VendaModel;
        $clienteModel = new ClienteModel;
        $empresaModel = new EmpresaModel;
        $vendedorModel = new VendedorModel;
        $vendaProdutoModel = new VendaProdutoModel;

        $dados['venda'] = $vendaModel->get(['uuid_venda' => $uuid], [], true);

        // Empresa
        if (!empty($dados['venda']['codigo_empresa'])) {
            $dados['empresa'] = $empresaModel->get(['codigo_empresa' => $dados['venda']['codigo_empresa']], [], true);
            $dados['empresa']['endereco'] = json_decode($dados['empresa']['endereco'], true);
        } else {
            $dados['empresa'] = "";
        }

        // Consumidor
        if (!empty($dados['venda']['cpf_cnpj'])) {
            $dados['consumidor'] = $clienteModel->get(['cpf_cnpj' => $dados['venda']['cpf_cnpj']], ['nome_fantasia'], true);
            $dados['consumidor'] = "{$dados['consumidor']['nome_fantasia']} ({$dados['venda']['cpf_cnpj']})";
        } else {
            $dados['consumidor'] = "Não Identificado";
        }

        // Vendedor
        if (!empty($dados['venda']['codigo_vendedor'])) {
            $dados['vendedor'] = $vendedorModel->get(['codigo_vendedor' => $dados['venda']['codigo_vendedor']], ['nome_fantasia'], true);
            $dados['vendedor'] = "{$dados['vendedor']['nome_fantasia']}";
        } else {
            $dados['vendedor'] = "Não Identificado";
        }

        $dados['produtos']   = $vendaProdutoModel->get(['codigo_venda' => $dados['venda']['codigo_venda']], []);
        echo view('app/venda/comprovante', $dados);
    }

    //////////////////////////////////
    //                              //
    //    OPERAÇÕES DE CADASTRO     //
    //                              //
    //////////////////////////////////

    /**
     * Busca os registros para o Datagrid
     * @param int $status Verifica se a informação está ativa (1 ou 0)
     */
    public function getDataGrid(int $status)
    {
        $vendaModel = new VendaModel;
        $dadosRequest = $this->request->getVar();
        $dadosRequest['status'] = $status;
        $data = $vendaModel->getDataGrid($dadosRequest);
        return $this->responseDataGrid($data, $dadosRequest);
    }

    /**
     * Realiza as chamadas assincronas direto para a Model
     * @param string $function
     */
    public function backendCall(string $function)
    {
        try {
            $request = $this->request->getVar();
            return $this->response->setJSON((new VendaModel)->$function($request));
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    /**
     * Realiza o Estorno de uma Venda
     * @param string $uuid Uuid do Registro
     * @return \CodeIgniter\HTTP\Response
     */
    public function estornarVenda(string $uuid): Response
    {
        if (!$this->verificarUuid($uuid)) {
            return $this->response->setJSON(['mensagem' => lang('Errors.geral.validaUuid')], 400);
        }

        $dadosEmpresa = $this->nativeSession->get("empresa");
        $dadosUsuario = $this->nativeSession->get("usuario");
        $vendaModel = new VendaModel;
        $estoqueBaixaModel = new EstoqueBaixaModel;
        $estoqueEntradaModel = new EstoqueEntradaModel;
        $estoqueProdutoModel = new EstoqueProdutoModel;
        $cadastroMovimentacaoTipoModel = new CadastroMovimentacaoTipoModel;
        $faturamentoVendaModel = new FaturamentoVendaModel;

        /////// INICIO :: VALIDA SE O VENDEDOR DO ESTORNO É O MESMO DA VENDA ///////
        $venda = $vendaModel->get(['uuid_venda' => $uuid, 'codigo_vendedor' => $dadosUsuario['codigo_vendedor']], ['codigo_venda'], true);

        if (empty($venda)) {
            return $this->response->setJSON(['mensagem' => lang('Errors.venda.vendedor')], 400);
        }

        /////// FIM :: VALIDA SE O VENDEDOR DO ESTORNO É O MESMO DA VENDA ///////

        /////// INICIO :: VALIDA SE A VENDA NÃO ESTA EM UM FATURAMENTO ///////

        $faturamento = $faturamentoVendaModel->get(['codigo_venda' => $venda['codigo_venda']], []);

        if (!empty($faturamento)) {
            return $this->response->setJSON(['mensagem' => lang('Errors.venda.vendaFaturamento')], 400);
        }

        /////// FIM :: VALIDA SE A VENDA NÃO ESTA EM UM FATURAMENTO ///////

        try {
            /////// INICIO :: DEVOLVE OS PRODUTOS DA VENDA AO ESTOQUE DE ORIGEM ///////

            // Busca os produtos que foram baixados nessa venda
            $produtosBaixadosNaVenda = $estoqueBaixaModel->get(['codigo_venda' => $venda['codigo_venda']], []);

            if (empty($produtosBaixadosNaVenda)) {
                return $this->response->setJSON(['mensagem' => lang('Errors.venda.produto')], 400);
            }

            foreach ($produtosBaixadosNaVenda as $key => $value) {
                // Busca esse Produto, nesse estoque
                $produtoEstoque = $estoqueProdutoModel->get(['codigo_estoque' => $value['codigo_estoque'], 'codigo_produto' => $value['codigo_produto']], [], true);

                $novoEstoque['codigo_estoque_produto'] = $produtoEstoque['codigo_estoque_produto'];
                $novoEstoque['usuario_alteracao']      = $dadosUsuario['codigo_usuario'];
                $novoEstoque['alterado_em']            = 'NOW()';
                $novoEstoque['estoque_atual']          = onlyNumber($produtoEstoque['estoque_atual']) + onlyNumber($value['quantidade']);
                $estoqueProdutoModel->save($novoEstoque);

                // Busca as Informações adicionais para inserir o registro dessa Movimentação de Entrada
                $movimentacaoTipo = $cadastroMovimentacaoTipoModel->get(['codigo_cadastro_movimentacao_tipo' => 6], ['codigo_cadastro_movimentacao_tipo', 'nome'], true);
                $movimentacaoEntrada = [
                    'codigo_empresa'                    => $dadosEmpresa['codigo_empresa'],
                    'usuario_criacao'                   => $dadosUsuario['codigo_usuario'],
                    'codigo_estoque'                    => $value['codigo_estoque'],
                    'codigo_produto'                    => $value['codigo_produto'],
                    'codigo_fornecedor'                 => $value['codigo_fornecedor'],
                    'codigo_cadastro_movimentacao_tipo' => $movimentacaoTipo['codigo_cadastro_movimentacao_tipo'],
                    'nome_cadastro_movimentacao_tipo'   => $movimentacaoTipo['nome'],
                    'quantidade'                        => onlyNumber($value['quantidade']),
                    'observacao'                        => "Venda: {$venda['codigo_venda']}"
                ];

                // Insere um registro de Movimentação do tipo Entrada
                $estoqueEntradaModel->save($movimentacaoEntrada);
            }
            /////// FIM :: DEVOLVE OS PRODUTOS DA VENDA AO ESTOQUE DE ORIGEM ///////

            // Realiza o Estorno
            $vendaUpdate = [
                'alterado_em'       => "NOW()",
                'usuario_alteracao' => $dadosUsuario['codigo_usuario'],
                'estornado_em'      => "NOW()"
            ];
            $vendaModel->where($vendaModel->uuidColumn, $uuid)->set($vendaUpdate)->update();
        } catch (Exception $e) {
            return $this->response->setJSON(['mensagem' => lang('Errors.venda.estorno')], 422);
        }

        return $this->response->setJSON(['mensagem' => lang('Success.venda.estornada')], 202);
    }
}
