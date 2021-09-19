<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;
use Exception;

class ImportaFinanceiroFluxoVenda extends BaseCommand
{
    protected $group       = 'Importação';
    protected $name        = 'importa:financeirofluxovenda';
    protected $usage       = 'importa:financeirofluxovenda';
    protected $description = 'Importa o Fluxo Financeiro de Venda no sistema';
    protected $arguments   = [];

    /**
     * Actually execute a command.
     * @param array $params
     */

    public function run(array $params)
    {
        CLI::write('Importação de Fluxo Financeiro de Venda Iniciado.', 'light_green');
        helper('string');

        // Instancia os Bancos
        $dbPortaJoias = Database::connect('default');
        $dbBackOffice = Database::connect('bl');

        //Inicia as operações de DB
        $dbPortaJoias->transStart();

        try {
            $venda = $dbBackOffice->query("SELECT *
                                             FROM venda
                                            WHERE situacao !=2
                                         ORDER BY codigo_venda ASC
                                         ")->getResultArray();

            foreach ($venda as $key => $value) {
                $vendedor = [];
                if (!empty($value['codigo_vendedor'])) {

                    $vendedor =  $dbPortaJoias->query("SELECT ve.codigo_vendedor
                                                         FROM vendedor as ve
                                                        WHERE SPLIT_PART(ve.razao_social, '#_', 1) = {$value['codigo_vendedor']}::text
                                                     ")->getResultArray();
                }
                if (!empty($value['codigo_cliente'])) {

                    $cliente =  $dbPortaJoias->query("SELECT c.codigo_cliente
                                                        FROM cliente as c
                                                       WHERE SPLIT_PART(c.razao_social, '#_', 1) = {$value['codigo_cliente']}::text
                                                    ")->getResultArray();
                }
                $parcelas =  $dbBackOffice->query("SELECT *
                                                     FROM parcelamento
                                                    WHERE codigo_venda = {$value['codigo_venda']}
                                                 ORDER BY 1 ASC
                                                 ")->getResultArray();

                $vendaPJ = $dbPortaJoias->query("SELECT v.codigo_venda
                                                   FROM venda as v
                                                  WHERE v.observacao = {$value['codigo_venda']}::text
                                               ")->getResultArray();

                $financeiroFluxo = [
                    'usuario_criacao'                  => 1,
                    'criado_em'                        => !empty($value['criado_em']) ? $value['criado_em'] : 'NOW()',
                    'inativado_em'                     => !empty($value['estornado_em']) ? $value['estornado_em'] : null,
                    'codigo_empresa'                   => $value['codigo_cliente_iluminare'],
                    'codigo_cadastro_metodo_pagamento' => 1,
                    'codigo_cadastro_fluxo_tipo'       => 1,
                    'codigo_empresa_centro_custo'      => $value['codigo_cliente_iluminare'],
                    'codigo_empresa_conta'             => $value['codigo_cliente_iluminare'],
                    'codigo_vendedor'                  => !empty($vendedor) ? $vendedor[0]['codigo_vendedor'] : $value['codigo_cliente_iluminare'],
                    'codigo_cliente'                   => !empty($cliente) ? $cliente[0]['codigo_cliente'] : null,
                    'insercao_automatica'              => 't',
                    'data_competencia'                 => !empty($value['criado_em']) ? $value['criado_em'] : 'NOW()',
                    'observacao'                       => "Ref. a Venda#_{$value['codigo_venda']}",
                ];

                $fluxoLote =  $dbPortaJoias->query("SELECT COALESCE(MAX(fluxo_lote), 0) AS valor
                                                      FROM financeiro_fluxo
                                                     WHERE codigo_empresa = {$value['codigo_cliente_iluminare']}
                                                  ")->getResultArray();

                $fluxoLote = $fluxoLote[0]['valor'] + $key;

                foreach ($parcelas as $keyP => $p) {

                    $financeiroFluxo['codigo_venda']    = !empty($vendaPJ) ? $vendaPJ[0]['codigo_venda'] : null;
                    $financeiroFluxo['data_vencimento'] = $p['data_vencimento'];
                    $financeiroFluxo['data_pagamento']  = !empty($p['data_pagamento']) ? $p['data_pagamento'] : null;
                    $financeiroFluxo['situacao']        = !empty($p['data_pagamento']) ? 't' : 'f';
                    $financeiroFluxo['fluxo_lote']      = ($fluxoLote + 1);

                    if (count($parcelas) > 1) {
                        $descricaoAdicional                = "Parcela: " . ($keyP + 1) . "/" . count($parcelas);
                        $financeiroFluxo['nome']           = "{$p['codigo_parcelamento']}#_{$descricaoAdicional} Venda {$value['codigo_venda']}";
                        $financeiroFluxo['valor_bruto']    = onlyNumber($p['valor']);
                        $financeiroFluxo['valor_liquido']  = onlyNumber($p['valor']);
                        $financeiroFluxo['numero_parcela'] = $keyP + 1;
                    } else {
                        $financeiroFluxo['nome']           = "{$value['codigo_venda']}#_Venda Vendedor";
                        $financeiroFluxo['valor_bruto']    = onlyNumber($value['valor_total']);
                        $financeiroFluxo['valor_desconto'] = onlyNumber($value['valor_desconto']);
                        $financeiroFluxo['valor_liquido']  = onlyNumber($value['valor_final']);
                    }

                    $dbPortaJoias->table('financeiro_fluxo')->insert($financeiroFluxo);
                    $codigoFluxo = $dbPortaJoias->insertID('financeiro_fluxo_codigo_financeiro_fluxo_seq');

                    if (count($parcelas) > 1 && !empty($p['data_pagamento'])) {
                        $financeiroFluxoParcial = [
                            'usuario_criacao'                  => 1,
                            'codigo_empresa'                   => $value['codigo_cliente_iluminare'],
                            'codigo_financeiro_fluxo'          => $codigoFluxo,
                            'codigo_cadastro_metodo_pagamento' => 1,
                            'data_pagamento'                   => $p['data_pagamento'],
                            'valor'                            => onlyNumber($p['valor']),
                        ];
                        $dbPortaJoias->table('financeiro_fluxo_parcial')->insert($financeiroFluxoParcial);
                    }
                }
            }

            // Finaliza as operações de DB
            $dbPortaJoias->transComplete();

            CLI::write('Importação de Fluxo Financeiro Finalizado.', 'green');
        } catch (Exception $e) {
            CLI::error($e->getMessage());
            die;
        }
    }
}
