<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;
use Exception;

class ImportaEstoque extends BaseCommand
{
    protected $group       = 'Importação';
    protected $name        = 'importa:estoque';
    protected $usage       = 'importa:estoque';
    protected $description = 'Importa o Estoque no sistema';
    protected $arguments   = [];

    /**
     * Actually execute a command.
     * @param array $params
     */

    public function run(array $params)
    {
        CLI::write('Cadastro de Estoque Iniciado.', 'light_green');

        helper('string');

        // Instancia os Bancos
        $dbPortaJoias = Database::connect('default');
        $dbBackOffice = Database::connect('bl');

        //Inicia as operações de DB
        $dbPortaJoias->transStart();

        try {

            // Estoque Atual da Empresa
            $estoqueAtualEmpresa = $dbBackOffice->query("SELECT produto.codigo_produto
                                                              , produto.codigo_cliente_iluminare
                                                              , COALESCE(produto.estoque_atual, 0) AS estoque_atual
                                                              , MAX(estoque.valor_compra_unitario) AS valor_fabrica
                                                              , MAX(produto.valor_venda) AS valor_venda
                                                              , MAX(estoque.criado_em) AS criado_em
                                                           FROM estoque
                                                     RIGHT JOIN produto
                                                             ON produto.codigo_produto = estoque.codigo_produto
                                                       GROUP BY produto.codigo_produto
                                                       ORDER BY produto.codigo_produto ASC
                                                       ")->getResultArray();

            // Estoque Atual do Vendedor
            $estoqueAtualVendedor = $dbBackOffice->query("SELECT estoque_vendedor.codigo_vendedor
                                                               , produto.codigo_produto
                                                               , produto.codigo_cliente_iluminare
                                                               , SUM(CAST(qtd AS signed) * IF (movimento IN (0x00, 0x03,0x04), 1, IF (movimento IN (0x01, 0x02), -1, 0))) AS estoque_atual
                                                               , null AS valor_fabrica
                                                               , MAX(produto.valor_venda) AS valor_venda
                                                               , MAX(estoque_vendedor.criado_em) AS criado_em
                                                            FROM estoque_vendedor
                                                      INNER JOIN produto
                                                              ON produto.codigo_produto = estoque_vendedor.codigo_produto
                                                        GROUP BY codigo_produto
                                                               , codigo_vendedor
                                                        ")->getResultArray();

            // Percorre o resultado montando a nova estrutura
            foreach ($estoqueAtualEmpresa as $key => $value) {

                //Busca codigo produto atual
                $codigoProduto = $dbPortaJoias->query("SELECT codigo_produto FROM produto WHERE SPLIT_PART(nome, '#_', 1) = {$value['codigo_produto']}::text")->getResultArray();

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
                    'usuario_criacao' => 1,
                    'criado_em'       => !empty($value['criado_em']) ? $value['criado_em'] : 'NOW()',
                    'codigo_empresa'  => $value['codigo_cliente_iluminare'],
                    'codigo_estoque'  => $estoque[0]['codigo_estoque'],
                    'codigo_produto'  => !empty($codigoProduto[0]['codigo_produto']) ? onlyNumber($codigoProduto[0]['codigo_produto']) : null,
                    'estoque_atual'   => !empty($value['estoque_atual']) ? onlyNumber($value['estoque_atual']) : null,
                    'valor_fabrica'   => !empty($value['valor_fabrica']) ? onlyNumber($value['valor_fabrica']) : null,
                    'valor_venda'     => !empty($value['valor_venda']) ? onlyNumber($value['valor_venda']) : null,
                ];

                // Significa que é uma Baixa que veio do estoque da do vendedor
                $dbPortaJoias->table('estoque_produto')->insert($novoEstoque);
            }

            foreach ($estoqueAtualVendedor as $key => $value) {
                $codigoProduto = $dbPortaJoias->query("SELECT codigo_produto
                                                         FROM produto
                                                        WHERE SPLIT_PART(nome, '#_', 1) = {$value['codigo_produto']}::text
                                                     ")->getResultArray();
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
                    'usuario_criacao' => 1,
                    'criado_em'       => !empty($value['criado_em']) ? $value['criado_em'] : 'NOW()',
                    'codigo_empresa'  => $value['codigo_cliente_iluminare'],
                    'codigo_estoque'  => $estoque[0]['codigo_estoque'],
                    'codigo_produto'  => !empty($codigoProduto[0]['codigo_produto']) ? onlyNumber($codigoProduto[0]['codigo_produto']) : null,
                    'estoque_atual'   => !empty($value['estoque_atual']) ? onlyNumber($value['estoque_atual']) : null,
                    'valor_fabrica'   => !empty($value['valor_fabrica']) ? onlyNumber($value['valor_fabrica']) : null,
                    'valor_venda'     => !empty($value['valor_venda']) ? onlyNumber($value['valor_venda']) : null,
                ];


                // Significa que é uma Baixa que veio do estoque da do vendedor
                $dbPortaJoias->table('estoque_produto')->insert($novoEstoque);
            }
            // Finaliza as operações de DB
            $dbPortaJoias->transComplete();

            CLI::write('Cadastro de Estoque Finalizado.', 'green');
        } catch (Exception $e) {
            CLI::error($e->getMessage());
        }
    }
}
