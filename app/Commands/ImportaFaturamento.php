<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;
use Exception;

class ImportaFaturamento extends BaseCommand
{
    protected $group       = 'Importação';
    protected $name        = 'importa:faturamento';
    protected $usage       = 'importa:faturamento';
    protected $description = 'Importa as faturamentos no sistema';
    protected $arguments   = [];

    /**
     * Actually execute a command.
     * @param array $params
     */
    public function run(array $params)
    {
        CLI::write('Importação de faturamento Iniciada.', 'light_green');

        helper('string');

        // Instancia os Bancos
        $dbPortaJoias = Database::connect('default');
        $dbBackOffice = Database::connect('bl');

        //Inicia as operações de DB
        $dbPortaJoias->transStart();
        try {

            $faturamentos = $dbBackOffice->query("SELECT *
                                                    FROM fechamento
                                                   WHERE periodo_inicio IS NOT NULL
                                                     AND cancelado_em IS NULL
                                                ORDER BY codigo_fechamento ASC
                                                ")->getResultArray();

            foreach ($faturamentos as $key => $value) {

                $vendedor = [];
                if (!empty($value['codigo_vendedor'])) {

                    $vendedor = $dbPortaJoias->query("SELECT codigo_vendedor
                                                        FROM vendedor as v
                                                       WHERE SPLIT_PART(razao_social, '#_', 1) = {$value['codigo_vendedor']}::text
                                                    ")->getResultArray();
                }

                $empresaComissao = [];
                if (!empty($value['codigo_comissionamento'])) {

                    $empresaComissao = $dbPortaJoias->query("SELECT percentual
                                                               FROM empresa_comissao as ec
                                                              WHERE codigo_empresa_comissao = {$value['codigo_comissionamento']}
                                                           ")->getResultArray();
                }

                $faturamento = [
                    'usuario_criacao'     => 1,
                    'criado_em'           => $value['criado_em'],
                    'codigo_empresa'      => $value['codigo_cliente_iluminare'],
                    'codigo_vendedor'     => !empty($vendedor) ? $vendedor[0]['codigo_vendedor'] : $value['codigo_cliente_iluminare'],
                    'periodo_inicio'      => date('Y-m-d', strtotime($value['periodo_inicio'])),
                    'periodo_fim'         => date('Y-m-d', strtotime($value['periodo_fim'])),
                    'valor_bruto'         => onlyNumber($value['valor_total']),
                    'percentual_comissao' => !empty($empresaComissao) ? $empresaComissao[0]['percentual'] : null,
                    'valor_comissao'      => onlyNumber($value['valor_comissao']),
                    'valor_desconto'      => onlyNumber($value['valor_desconto']),
                    'valor_entrada'       => 0,
                    'valor_liquido'       => onlyNumber($value['valor_final']),
                    'observacao'          => $value['codigo_fechamento']
                ];

                //verifica se o registro não existe
                // $numRows = $dbPortaJoias->table('venda')->where($venda)->countAllResults();

                // if ($numRows === 0) {
                $insert = $dbPortaJoias->table('faturamento')->insert($faturamento);

                if ($insert) {

                    $codigoFaturamento = $dbPortaJoias->insertID('faturamento_codigo_faturamento_seq');

                    $faturamentoVendas = $dbPortaJoias->query("SELECT codigo_venda
                                                                 FROM venda
                                                                WHERE TO_CHAR(criado_em, 'YYYY-MM-DD') > '{$faturamento['periodo_inicio']}'
                                                                  AND TO_CHAR(criado_em, 'YYYY-MM-DD') < '{$faturamento['periodo_fim']}'
                                                                  AND codigo_vendedor = {$faturamento['codigo_vendedor']}
                                                                  AND estornado_em IS NULL
                                                             ORDER BY codigo_venda ASC
                                                             ")->getResultArray();

                    foreach ($faturamentoVendas as $key => $fv) {

                        $faturamentoVenda = [
                            'usuario_criacao'    => 1,
                            'criado_em'          => $faturamento['criado_em'],
                            'codigo_empresa'     => $faturamento['codigo_empresa'],
                            'codigo_venda'       => $fv['codigo_venda'],
                            'codigo_faturamento' => $codigoFaturamento
                        ];

                        //verifica se o registro não existe
                        $numRows = $dbPortaJoias->table('faturamento_venda')->where($faturamentoVenda)->countAllResults();

                        if ($numRows === 0) {
                            $dbPortaJoias->table('faturamento_venda')->insert($faturamentoVenda);
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
