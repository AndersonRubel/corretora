<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;
use Exception;

class ImportaFinanceiroFluxoFechamento extends BaseCommand
{
    protected $group       = 'Importação';
    protected $name        = 'importa:financeirofluxofechamento';
    protected $usage       = 'importa:financeirofluxofechamento';
    protected $description = 'Importa Fluxo Financeiro de fechamento no sistema';
    protected $arguments   = [];

    /**
     * Actually execute a command.
     * @param array $params
     */

    public function run(array $params)
    {
        CLI::write('Importação de Fluxo Financeiro de fechamento Iniciado.', 'light_green');
        helper('string');

        // Instancia os Bancos
        $dbPortaJoias = Database::connect('default');
        $dbBackOffice = Database::connect('bl');

        //Inicia as operações de DB
        $dbPortaJoias->transStart();

        try {
            $fechamento = $dbBackOffice->query("SELECT * FROM fechamento ORDER BY 1 ASC")->getResultArray();

            foreach ($fechamento as $key => $value) {

                $vendedor =  $dbPortaJoias->query("SELECT codigo_vendedor
                                                     FROM vendedor
                                                    WHERE SPLIT_PART(razao_social, '#_', 1) = {$value['codigo_vendedor']}::text
                                                 ")->getResultArray();

                $faturamento =  $dbPortaJoias->query("SELECT observacao AS codigo_faturamento
                                                        FROM faturamento
                                                       WHERE observacao = {$value['codigo_fechamento']}::text
                                                    ")->getResultArray();

                $parcelas =  $dbBackOffice->query("SELECT *
                                                     FROM parcelamento_fechamento
                                                    WHERE codigo_fechamento = {$value['codigo_fechamento']}
                                                    ORDER BY 1 ASC
                                                 ")->getResultArray();

                $financeiroFluxo = [
                    'usuario_criacao'                  => 1,
                    'criado_em'                        => !empty($value['criado_em'])   ? $value['criado_em']   : 'NOW()',
                    'alterado_em'                      => !empty($value['alterado_em']) ? $value['alterado_em'] : null,
                    'inativado_em'                     => !empty($value['excluido_em']) ? $value['excluido_em'] : null,
                    'codigo_empresa'                   => $value['codigo_cliente_iluminare'],
                    'codigo_faturamento'               => !empty($faturamento) ? $faturamento[0]['codigo_faturamento'] : null,
                    'codigo_cadastro_metodo_pagamento' => 1,
                    'codigo_cadastro_fluxo_tipo'       => 1,
                    'codigo_empresa_centro_custo'      => $value['codigo_cliente_iluminare'],
                    'codigo_empresa_conta'             => $value['codigo_cliente_iluminare'],
                    'codigo_vendedor'                  => $vendedor[0]['codigo_vendedor'],
                    'insercao_automatica'              => 't',
                    'data_competencia'                 => !empty($value['criado_em']) ? $value['criado_em'] : 'NOW()',
                    'observacao'                       => "Período Início: " . date("d/m/Y", strtotime($value['periodo_inicio'])) . " - Período Fim: " . date("d/m/Y", strtotime($value['periodo_fim'])),
                ];

                $fluxoLote =  $dbPortaJoias->query("SELECT COALESCE(MAX(fluxo_lote), 0) AS valor
                                                      FROM financeiro_fluxo
                                                     WHERE codigo_empresa = {$value['codigo_cliente_iluminare']}
                                                  ")->getResultArray();

                $fluxoLote = $fluxoLote[0]['valor'] + $key;

                foreach ($parcelas as $keyP => $p) {

                    $financeiroFluxo['codigo_venda']    = null;
                    $financeiroFluxo['data_vencimento'] = $p['data_vencimento'];
                    $financeiroFluxo['data_pagamento']  = !empty($p['data_pagamento']) ? $p['data_pagamento'] : null;
                    $financeiroFluxo['situacao']        = !empty($p['data_pagamento']) ? 't' : 'f';
                    $financeiroFluxo['fluxo_lote']      = ($fluxoLote + 1);

                    if (count($parcelas) > 1) {

                        $descricaoAdicional                = "Parcela: " . ($keyP + 1) . "/" . count($parcelas);
                        $financeiroFluxo['nome']           = "{$p['codigo_parcelamento']}#_{$descricaoAdicional} Fechamento {$value['codigo_fechamento']}";
                        $financeiroFluxo['valor_bruto']    = onlyNumber($p['valor']);
                        $financeiroFluxo['valor_liquido']  = onlyNumber($p['valor']);
                        $financeiroFluxo['numero_parcela'] = $keyP + 1;
                    } else {
                        $financeiroFluxo['nome']           = "{$value['codigo_fechamento']}#_Fechamento Vendedor";
                        $financeiroFluxo['valor_bruto']    = onlyNumber($value['valor_total']);
                        $financeiroFluxo['valor_desconto'] = onlyNumber($value['valor_comissao'] + $value['valor_desconto']);
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

            CLI::write('Importação de Fluxo Financeiro de fechamento Finalizado.', 'green');
        } catch (Exception $e) {
            CLI::error($e->getMessage());
            die;
        }
    }
}
