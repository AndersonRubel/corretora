<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;
use Exception;

class ImportaEstoqueMovimentacao extends BaseCommand
{
    protected $group       = 'Importação';
    protected $name        = 'importa:estoquemovimentacao';
    protected $usage       = 'importa:estoquemovimentacao';
    protected $description = 'Importa a Movimentacao do Estoque no sistema';
    protected $arguments   = [];

    /**
     * Actually execute a command.
     * @param array $params
     */

    public function run(array $params)
    {
        CLI::write('Cadastro de Estoque Movimentacao Iniciado.', 'light_green');

        helper('string');

        // Instancia os Bancos
        $dbPortaJoias = Database::connect('default');
        $dbBackOffice = Database::connect('bl');

        //Inicia as operações de DB
        $dbPortaJoias->transStart();

        try {

            /////// INICIO :: BUSCANDO TODOS OS TIPOS DE ENTRADAS ///////

            // Estoque Empresa - Movimento 0 (Entrada no Estoque)
            $entradaEstoqueEmpresa = $dbBackOffice->query("SELECT e.codigo_cliente_iluminare
                                                                , e.criado_em
                                                                , e.valor_venda_unitario
                                                                , e.valor_compra_total
                                                                , e.qtd AS quantidade
                                                                , p.codigo_produto
                                                                , p.nome AS nome_produto
                                                                , p.valor_venda
                                                                , 1 AS codigo_cadastro_movimentacao_tipo
                                                                , 'Entrada de Estoque' AS nome_cadastro_movimentacao_tipo
                                                                , 0 AS codigo_vendedor
                                                                , 1 AS movimentacao_lote
                                                             FROM estoque e
                                                             LEFT JOIN produto p
                                                               ON p.codigo_produto = e.codigo_produto
                                                            WHERE e.movimento = 0
                                                            ORDER BY e.codigo_estoque ASC
                                                        ")->getResultArray();

            // Estoque Empresa - Movimento 3 (Devolução Para a Empresa)
            $entradaEstoqueEmpresaDevolucaoEmpresa = $dbBackOffice->query("SELECT e.codigo_cliente_iluminare
                                                                                , e.criado_em
                                                                                , e.valor_venda_unitario
                                                                                , e.valor_compra_total
                                                                                , e.qtd AS quantidade
                                                                                , p.codigo_produto
                                                                                , p.nome AS nome_produto
                                                                                , p.valor_venda
                                                                                , 4 AS codigo_cadastro_movimentacao_tipo
                                                                                , 'Entrada de Estoque por Devolução' AS nome_cadastro_movimentacao_tipo
                                                                                , 0 AS codigo_vendedor
                                                                                , (SELECT COALESCE(pt.codigo_transacao, 1) AS codigo_transacao
                                                                                     FROM produto_transacao pt
                                                                                    INNER JOIN transacao t
                                                                                       ON t.codigo_transacao = pt.codigo_transacao
                                                                                    WHERE pt.codigo_produto = p.codigo_produto
                                                                                      AND t.codigo_cliente_iluminare = p.codigo_cliente_iluminare
                                                                                      AND t.cancelado_em IS NULL
                                                                                      AND t.tipo_transacao = 3
                                                                                    ORDER BY 1 DESC
                                                                                    LIMIT 1
                                                                                  ) AS movimentacao_lote
                                                                             FROM estoque e
                                                                             LEFT JOIN produto p
                                                                               ON p.codigo_produto = e.codigo_produto
                                                                            WHERE e.movimento = 3
                                                                            ORDER BY e.codigo_estoque ASC
                                                                        ")->getResultArray();

            // Estoque Empresa - Movimento 5 (Estorno)
            $entradaEstoqueEmpresaEstorno = $dbBackOffice->query("SELECT e.codigo_cliente_iluminare
                                                                       , e.criado_em
                                                                       , e.valor_venda_unitario
                                                                       , e.valor_compra_total
                                                                       , e.qtd AS quantidade
                                                                       , p.codigo_produto
                                                                       , p.nome AS nome_produto
                                                                       , p.valor_venda
                                                                       , 6 AS codigo_cadastro_movimentacao_tipo
                                                                       , 'Estorno Pedido' AS nome_cadastro_movimentacao_tipo
                                                                       , 0 AS codigo_vendedor
                                                                       , 1 AS movimentacao_lote
                                                                    FROM estoque e
                                                                    LEFT JOIN produto p
                                                                      ON p.codigo_produto = e.codigo_produto
                                                                   WHERE e.movimento = 5
                                                                   ORDER BY e.codigo_estoque ASC
                                                                ")->getResultArray();

            // Estoque Vendedor - Movimento 0
            // As entradas do vendedor é apenas por transferencia, mas o sistema entende que é uma movimentacao de Entrada (Tipo 0)
            $entradaEstoqueVendedor = $dbBackOffice->query("SELECT ev.codigo_vendedor
                                                                 , ev.criado_em
                                                                 , ev.qtd AS quantidade
                                                                 , ps.codigo_cliente_iluminare
                                                                 , p.codigo_produto
                                                                 , p.nome AS nome_produto
                                                                 , p.valor_venda
                                                                 , 1 AS codigo_cadastro_movimentacao_tipo
                                                                 , 'Entrada de Estoque' AS nome_cadastro_movimentacao_tipo
                                                                 , (SELECT COALESCE(pt.codigo_transacao, 1) AS codigo_transacao
                                                                      FROM produto_transacao pt
                                                                     INNER JOIN transacao t
                                                                        ON t.codigo_transacao = pt.codigo_transacao
                                                                     WHERE pt.codigo_produto = p.codigo_produto
                                                                       AND t.codigo_cliente_iluminare = p.codigo_cliente_iluminare
                                                                       AND t.cancelado_em IS NULL
                                                                       AND t.tipo_transacao = 3
                                                                     ORDER BY 1 DESC
                                                                     LIMIT 1
                                                                   ) AS movimentacao_lote
                                                              FROM estoque_vendedor ev
                                                             INNER JOIN produto p
                                                                ON p.codigo_produto = ev.codigo_produto
                                                             INNER JOIN vendedor v
                                                                ON v.codigo_vendedor = ev.codigo_vendedor
                                                             INNER JOIN pessoa ps
                                                                ON ps.codigo_pessoa = v.codigo_pessoa
                                                             WHERE ev.movimento = 0
                                                             ORDER BY ev.codigo_estoque_vendedor ASC
                                                        ")->getResultArray();

            // Estoque Vendedor - Movimento 4 Estornando Pedido
            $entradaEstoqueVendedorEstorno = $dbBackOffice->query("SELECT ev.codigo_vendedor
                                                                        , ev.criado_em
                                                                        , ev.qtd AS quantidade
                                                                        , ps.codigo_cliente_iluminare
                                                                        , p.codigo_produto
                                                                        , p.nome AS nome_produto
                                                                        , p.valor_venda
                                                                        , 1 AS codigo_cadastro_movimentacao_tipo
                                                                        , 'Entrada de Estoque por Estorno' AS nome_cadastro_movimentacao_tipo
                                                                        , 1 AS movimentacao_lote
                                                                     FROM estoque_vendedor ev
                                                                    INNER JOIN produto p
                                                                       ON p.codigo_produto = ev.codigo_produto
                                                                    INNER JOIN vendedor v
                                                                       ON v.codigo_vendedor = ev.codigo_vendedor
                                                                    INNER JOIN pessoa ps
                                                                       ON ps.codigo_pessoa = v.codigo_pessoa
                                                                    WHERE ev.movimento = 4
                                                                    ORDER BY ev.codigo_estoque_vendedor ASC
                                                                ")->getResultArray();


            /////// FIM :: BUSCANDO TODOS OS TIPOS DE ENTRADAS ///////

            /////// INICIO :: BUSCANDO TODOS OS TIPOS DE BAIXAS ///////

            // Estoque Empresa - Movimento 1 (Venda)
            $baixaEstoqueEmpresa = $dbBackOffice->query("SELECT e.codigo_cliente_iluminare
                                                                , e.criado_em
                                                                , e.valor_venda_unitario
                                                                , e.valor_compra_total
                                                                , e.qtd AS quantidade
                                                                , p.codigo_produto
                                                                , p.nome AS nome_produto
                                                                , p.valor_venda
                                                                , 2 AS codigo_cadastro_movimentacao_tipo
                                                                , 'Baixa de Estoque' AS nome_cadastro_movimentacao_tipo
                                                                , 0 AS codigo_vendedor
                                                                , (SELECT COALESCE(pt.codigo_transacao, 1) AS codigo_transacao
                                                                     FROM produto_transacao pt
                                                                    INNER JOIN transacao t
                                                                       ON t.codigo_transacao = pt.codigo_transacao
                                                                    WHERE pt.codigo_produto = p.codigo_produto
                                                                      AND t.codigo_cliente_iluminare = p.codigo_cliente_iluminare
                                                                      AND t.cancelado_em IS NULL
                                                                      AND t.tipo_transacao = 2
                                                                    ORDER BY 1 DESC
                                                                    LIMIT 1
                                                                  ) AS movimentacao_lote
                                                             FROM estoque e
                                                             LEFT JOIN produto p
                                                               ON p.codigo_produto = e.codigo_produto
                                                            WHERE e.movimento = 1
                                                            ORDER BY e.codigo_estoque ASC
                                                        ")->getResultArray();

            // Estoque Empresa - Movimento 2 (Transferencia para Vendedor)
            $baixaEstoqueEmpresaTransferenciaVendedor = $dbBackOffice->query("SELECT e.codigo_cliente_iluminare
                                                                                   , e.criado_em
                                                                                   , e.valor_venda_unitario
                                                                                   , e.valor_compra_total
                                                                                   , e.qtd AS quantidade
                                                                                   , p.codigo_produto
                                                                                   , p.nome AS nome_produto
                                                                                   , p.valor_venda
                                                                                   , 3 AS codigo_cadastro_movimentacao_tipo
                                                                                   , 'Transferência para vendedor' AS nome_cadastro_movimentacao_tipo
                                                                                   , 0 AS codigo_vendedor
                                                                                   , (SELECT COALESCE(pt.codigo_transacao, 1) AS codigo_transacao
                                                                                     FROM produto_transacao pt
                                                                                    INNER JOIN transacao t
                                                                                       ON t.codigo_transacao = pt.codigo_transacao
                                                                                    WHERE pt.codigo_produto = p.codigo_produto
                                                                                      AND t.codigo_cliente_iluminare = p.codigo_cliente_iluminare
                                                                                      AND t.cancelado_em IS NULL
                                                                                      AND t.tipo_transacao = 0
                                                                                    ORDER BY 1 DESC
                                                                                    LIMIT 1
                                                                                  ) AS movimentacao_lote
                                                                                FROM estoque e
                                                                                LEFT JOIN produto p
                                                                                  ON p.codigo_produto = e.codigo_produto
                                                                               WHERE e.movimento = 2
                                                                               ORDER BY e.codigo_estoque ASC
                                                                            ")->getResultArray();

            // Estoque Empresa - Movimento 4 (Devolucao Fornecedor)
            $baixaEstoqueEmpresaDevolucaoFornecedor = $dbBackOffice->query("SELECT e.codigo_cliente_iluminare
                                                                                 , e.criado_em
                                                                                 , e.valor_venda_unitario
                                                                                 , e.valor_compra_total
                                                                                 , e.qtd AS quantidade
                                                                                 , p.codigo_produto
                                                                                 , p.nome AS nome_produto
                                                                                 , p.valor_venda
                                                                                 , 5 AS codigo_cadastro_movimentacao_tipo
                                                                                 , 'Baixa de Estoque por Devolução para o fornecedor' AS nome_cadastro_movimentacao_tipo
                                                                                 , 0 AS codigo_vendedor
                                                                                 , (SELECT COALESCE(pt.codigo_transacao, 1) AS codigo_transacao
                                                                                     FROM produto_transacao pt
                                                                                    INNER JOIN transacao t
                                                                                       ON t.codigo_transacao = pt.codigo_transacao
                                                                                    WHERE pt.codigo_produto = p.codigo_produto
                                                                                      AND t.codigo_cliente_iluminare = p.codigo_cliente_iluminare
                                                                                      AND t.cancelado_em IS NULL
                                                                                      AND t.tipo_transacao = 3
                                                                                    ORDER BY 1 DESC
                                                                                    LIMIT 1
                                                                                  ) AS movimentacao_lote
                                                                              FROM estoque e
                                                                              LEFT JOIN produto p
                                                                                ON p.codigo_produto = e.codigo_produto
                                                                             WHERE e.movimento = 4
                                                                             ORDER BY e.codigo_estoque ASC
                                                                        ")->getResultArray();

            // Estoque Vendedor - Movimento 1 (Venda)
            $baixaEstoqueVendedor = $dbBackOffice->query("SELECT ev.codigo_vendedor
                                                                 , ev.criado_em
                                                                 , ev.qtd AS quantidade
                                                                 , ps.codigo_cliente_iluminare
                                                                 , p.codigo_produto
                                                                 , p.nome AS nome_produto
                                                                 , p.valor_venda
                                                                 , 2 AS codigo_cadastro_movimentacao_tipo
                                                                 , 'Baixa de Estoque' AS nome_cadastro_movimentacao_tipo
                                                                 , (SELECT COALESCE(pt.codigo_transacao, 1) AS codigo_transacao
                                                                      FROM produto_transacao pt
                                                                     INNER JOIN transacao t
                                                                        ON t.codigo_transacao = pt.codigo_transacao
                                                                     WHERE pt.codigo_produto = p.codigo_produto
                                                                       AND t.codigo_cliente_iluminare = p.codigo_cliente_iluminare
                                                                       AND t.cancelado_em IS NULL
                                                                       AND t.tipo_transacao = 2
                                                                     ORDER BY 1 DESC
                                                                     LIMIT 1
                                                                   ) AS movimentacao_lote
                                                              FROM estoque_vendedor ev
                                                             INNER JOIN produto p
                                                                ON p.codigo_produto = ev.codigo_produto
                                                             INNER JOIN vendedor v
                                                                ON v.codigo_vendedor = ev.codigo_vendedor
                                                             INNER JOIN pessoa ps
                                                                ON ps.codigo_pessoa = v.codigo_pessoa
                                                             WHERE ev.movimento = 1
                                                             ORDER BY ev.codigo_estoque_vendedor ASC
                                                        ")->getResultArray();

            // Estoque Vendedor - Movimento 3 (Devolução Para a Empresa)
            $baixaEstoqueVendedor = $dbBackOffice->query("SELECT ev.codigo_vendedor
                                                                 , ev.criado_em
                                                                 , ev.qtd AS quantidade
                                                                 , ps.codigo_cliente_iluminare
                                                                 , p.codigo_produto
                                                                 , p.nome AS nome_produto
                                                                 , p.valor_venda
                                                                 , 4 AS codigo_cadastro_movimentacao_tipo
                                                                 , 'Devolução para empresa' AS nome_cadastro_movimentacao_tipo
                                                                 , (SELECT COALESCE(pt.codigo_transacao, 1) AS codigo_transacao
                                                                      FROM produto_transacao pt
                                                                     INNER JOIN transacao t
                                                                        ON t.codigo_transacao = pt.codigo_transacao
                                                                     WHERE pt.codigo_produto = p.codigo_produto
                                                                       AND t.codigo_cliente_iluminare = p.codigo_cliente_iluminare
                                                                       AND t.cancelado_em IS NULL
                                                                       AND t.tipo_transacao = 3
                                                                     ORDER BY 1 DESC
                                                                     LIMIT 1
                                                                   ) AS movimentacao_lote
                                                              FROM estoque_vendedor ev
                                                             INNER JOIN produto p
                                                                ON p.codigo_produto = ev.codigo_produto
                                                             INNER JOIN vendedor v
                                                                ON v.codigo_vendedor = ev.codigo_vendedor
                                                             INNER JOIN pessoa ps
                                                                ON ps.codigo_pessoa = v.codigo_pessoa
                                                             WHERE ev.movimento = 2
                                                             ORDER BY ev.codigo_estoque_vendedor ASC
                                                        ")->getResultArray();

            /////// FIM :: BUSCANDO TODOS OS TIPOS DE BAIXAS ///////

            // Junta as duas consultas e ordena por data dde criação
            $movimentacoes = array_merge(
                $entradaEstoqueEmpresa,
                $entradaEstoqueVendedor,
                $entradaEstoqueEmpresaDevolucaoEmpresa,
                $entradaEstoqueEmpresaEstorno,
                $entradaEstoqueVendedorEstorno,
                $baixaEstoqueEmpresa,
                $baixaEstoqueVendedor,
                $baixaEstoqueEmpresaTransferenciaVendedor,
                $baixaEstoqueEmpresaDevolucaoFornecedor,
            );

            array_multisort(array_column($movimentacoes, 'criado_em'), SORT_ASC, $movimentacoes);

            // Percorre o resultado montando a nova estrutura
            foreach ($movimentacoes as $key => $value) {

                // Busca o novo Estoque que receberá os registros
                if (!empty($value['codigo_vendedor'])) {
                    $estoque = $dbPortaJoias->query("SELECT e.codigo_estoque
                                                       FROM estoque e
                                                 INNER JOIN vendedor v
                                                         ON v.codigo_vendedor = e.codigo_vendedor
                                                      WHERE SPLIT_PART(v.razao_social, '#_', 1) = {$value['codigo_vendedor']}::text
                                                   ORDER BY e.codigo_estoque ASC
                                                      LIMIT 1
                                                   ")->getResultArray();
                } else {
                    $estoque = $dbPortaJoias->query("SELECT codigo_estoque
                                                       FROM estoque
                                                      WHERE codigo_empresa = {$value['codigo_cliente_iluminare']}
                                                        AND codigo_vendedor IS NULL
                                                   ")->getResultArray();
                }

                $novoEstoque = [
                    'usuario_criacao'                   => 1,
                    'criado_em'                         => $value['criado_em'],
                    'codigo_empresa'                    => $value['codigo_cliente_iluminare'],
                    'codigo_cadastro_movimentacao_tipo' => $value['codigo_cadastro_movimentacao_tipo'],
                    'nome_cadastro_movimentacao_tipo'   => $value['nome_cadastro_movimentacao_tipo'],
                    'codigo_estoque'                    => $estoque[0]['codigo_estoque'],
                    'codigo_produto'                    => onlyNumber($value['codigo_produto']),
                    'quantidade'                        => onlyNumber($value['quantidade']),
                    'movimentacao_lote'                 => onlyNumber($value['movimentacao_lote'])
                ];

                if (in_array($novoEstoque['codigo_cadastro_movimentacao_tipo'], [1, 4, 6]) && empty($value['codigo_vendedor'])) {
                    // Significa que é uma Entrada que veio do estoque da empresa
                    $dbPortaJoias->table('estoque_entrada')->insert($novoEstoque);
                } elseif ($novoEstoque['codigo_cadastro_movimentacao_tipo'] == 1 && !empty($value['codigo_vendedor'])) {
                    // Significa que é uma Entrada que veio do estoque do vendedor
                    $novoEstoque['transferencia_de_codigo_estoque'] = $this->getCodigoEstoque($value['codigo_vendedor'], 'vendedor');
                    $novoEstoque['transferencia_para_codigo_estoque'] = $this->getCodigoEstoque($value['codigo_cliente_iluminare'], 'empresa');
                    $dbPortaJoias->table('estoque_entrada')->insert($novoEstoque);
                } elseif (in_array($novoEstoque['codigo_cadastro_movimentacao_tipo'], [3, 5]) && empty($value['codigo_vendedor'])) {
                    // Significa que é uma Baixa que veio do estoque da empresa
                    $dbPortaJoias->table('estoque_baixa')->insert($novoEstoque);
                } elseif (in_array($novoEstoque['codigo_cadastro_movimentacao_tipo'], [2, 4]) && !empty($value['codigo_vendedor'])) {
                    // Significa que é uma Baixa que veio do estoque da do vendedor
                    $novoEstoque['transferencia_de_codigo_estoque'] = $this->getCodigoEstoque($value['codigo_cliente_iluminare'], 'empresa');
                    $novoEstoque['transferencia_para_codigo_estoque'] = $this->getCodigoEstoque($value['codigo_vendedor'], 'vendedor');
                    $dbPortaJoias->table('estoque_baixa')->insert($novoEstoque);
                }
            }

            // Finaliza as operações de DB
            $dbPortaJoias->transComplete();

            CLI::write('Cadastro de Estoque Movimentacao Finalizado.', 'green');
        } catch (Exception $e) {
            CLI::error($e->getMessage());
            die;
        }
    }

    /**
     * Busca o Código do Estoque do Vendedor ou Empresa
     */
    public function getCodigoEstoque($codigo, string $tipo)
    {

        // Instancia o Banco
        $dbPortaJoias = Database::connect('default');

        if ($tipo == 'empresa') {
            $estoque = $dbPortaJoias->query("SELECT codigo_estoque
                                               FROM estoque
                                              WHERE codigo_empresa = {$codigo}
                                                AND codigo_vendedor IS NULL
                                            ")->getResultArray();
        } elseif ($tipo == 'vendedor') {
            $estoque = $dbPortaJoias->query("SELECT e.codigo_estoque
                                               FROM estoque e
                                              INNER JOIN vendedor v
                                                 ON v.codigo_vendedor = e.codigo_vendedor
                                              WHERE SPLIT_PART(v.razao_social, '#_', 1) = {$codigo}::text
                                              ORDER BY e.codigo_estoque ASC
                                              LIMIT 1
                                            ")->getResultArray();
        }

        return !empty($estoque) ? $estoque[0]['codigo_estoque'] : null;
    }
}
