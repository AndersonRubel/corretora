<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;
use Exception;

class ImportaVenda extends BaseCommand
{
    protected $group       = 'Importação';
    protected $name        = 'importa:venda';
    protected $usage       = 'importa:venda';
    protected $description = 'Importa as Vendas no sistema';
    protected $arguments   = [];

    /**
     * Actually execute a command.
     * @param array $params
     */
    public function run(array $params)
    {
        CLI::write('Importação de Venda Iniciada.', 'light_green');

        helper('string');

        // Instancia os Bancos
        $dbPortaJoias = Database::connect('default');
        $dbBackOffice = Database::connect('bl');

        //Inicia as operações de DB
        $dbPortaJoias->transStart();
        try {

            $vendas = $dbBackOffice->query("SELECT ve.*
                                              FROM venda as ve
                                             WHERE ve.situacao != 2
                                             ORDER BY ve.codigo_venda ASC
                                               ")->getResultArray();

            foreach ($vendas as $key => $value) {
                $vendedor = [];
                if (!empty($value['codigo_vendedor'])) {

                    $vendedor = $dbPortaJoias->query("SELECT v.codigo_vendedor
                                                        FROM vendedor as v
                                                       WHERE SPLIT_PART(v.razao_social, '#_', 1) = {$value['codigo_vendedor']}::text
                                                    ")->getResultArray();
                }

                if (!empty($value['codigo_cliente'])) {
                    $cliente = $dbPortaJoias->query("SELECT c.codigo_cliente
                                                       FROM cliente as c
                                                      WHERE SPLIT_PART(c.razao_social, '#_', 1) = {$value['codigo_cliente']}::text
                                                   ")->getResultArray();
                }

                $venda = [
                    'usuario_criacao'                  => 1,
                    'criado_em'                        => $value['criado_em'],
                    'codigo_empresa'                   => $value['codigo_cliente_iluminare'],
                    'codigo_vendedor'                  => !empty($vendedor[0]['codigo_vendedor']) ? onlyNumber($vendedor[0]['codigo_vendedor']) : onlyNumber($value['codigo_cliente_iluminare']),
                    'codigo_cliente'                   => !empty($cliente) ? $cliente[0]['codigo_cliente'] : null,
                    'codigo_cadastro_metodo_pagamento' => 1,
                    'valor_bruto'                      => onlyNumber($value['valor_total']),
                    'valor_desconto'                   => onlyNumber($value['valor_desconto']),
                    'valor_liquido'                    => onlyNumber($value['valor_final']),
                    'observacao'                       => $value['codigo_venda'],
                    'estornado_em'                     => !empty($value['estornado_em']) ? $value['estornado_em'] : null,
                ];


                //verifica se o registro não existe
                // $numRows = $dbPortaJoias->table('venda')->where($venda)->countAllResults();

                // if ($numRows === 0) {
                $insert = $dbPortaJoias->table('venda')->insert($venda);

                if ($insert) {

                    $codigoVenda = $dbPortaJoias->insertID('venda_codigo_venda_seq');

                    $vendaProdutos = $dbBackOffice->query("SELECT vp.*
                                                             FROM venda_produto as vp
                                                            WHERE vp.codigo_venda = {$value['codigo_venda']}
                                                         ORDER BY vp.codigo_produto ASC
                                                         ")->getResultArray();

                    foreach ($vendaProdutos as $key => $vp) {

                        $produto = $dbPortaJoias->query("SELECT p.codigo_produto, p.nome
                                                            FROM produto as p
                                                           WHERE SPLIT_PART(p.nome, '#_', 1) = {$vp['codigo_produto']}::text
                                                       ")->getResultArray();

                        $vendaProduto = [
                            'usuario_criacao' => 1,
                            'criado_em'       => $value['criado_em'],
                            'inativado_em'    => !empty($value['estornado_em']) ? $value['estornado_em'] : null,
                            'codigo_empresa'  => $value['codigo_cliente_iluminare'],
                            'codigo_venda'    => $codigoVenda,
                            'codigo_produto'  => $produto[0]['codigo_produto'],
                            'nome_produto'    => $produto[0]['nome'],
                            'quantidade'      => onlyNumber($vp['qtd']),
                            'valor_unitario'  => onlyNumber($vp['valor_unitario']),
                            'valor_total'     => onlyNumber($vp['valor_subtotal']),
                        ];

                        //verifica se o registro não existe
                        $numRows = $dbPortaJoias->table('venda_produto')->where($vendaProduto)->countAllResults();

                        if ($numRows === 0) {
                            $dbPortaJoias->table('venda_produto')->insert($vendaProduto);
                        }
                    }
                }
                // }
            }
            // Finaliza as operações de DB
            $dbPortaJoias->transComplete();

            CLI::write('Importação de Venda Finalizada.', 'green');
        } catch (Exception $e) {
            CLI::error($e->getMessage());
            die;
        }
    }
}
