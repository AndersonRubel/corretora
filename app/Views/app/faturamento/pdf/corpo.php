<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Faturamento_<?= $faturamento['uuid_faturamento']; ?></title>
    <style>
        body {
            font-family: sans-serif;
        }

        /* Inicio :: Header */
        .header {
            width: 100%;
            border-bottom: 4px solid #0E2D39;
            display: flex;
            margin-bottom: 4px;
        }

        /* Fim :: Header */

        /* Inicio :: Corpo */
        .corpo .label {
            font-size: 10px;
        }

        .corpo .info-fatura div span {
            font-size: 11px;
        }

        .corpo .table {
            border-collapse: collapse;
        }

        .corpo .table,
        th,
        td {
            border: 1px solid black;
        }

        .corpo .cabecalho {
            padding-top: 9px;
        }

        .corpo .listagem {
            margin-top: 5px;
        }

        .corpo .listagem-vendedor {
            margin-bottom: 15px;
        }

        .corpo .vendedor-nome {
            font-size: 12px !important;
        }

        /* Fim :: Corpo */

        /* Inicio :: Footer */

        .footer {
            margin-top: 30px;
            border-top: 1px solid #000;
            font-size: 12px;
        }

        .footer .paginacao {
            float: none;
            text-align: right;
        }

        /* Fim :: Footer */

        /* Inicio :: Utils */
        .float-left {
            float: left;
        }

        .w-100 {
            width: 100%;
        }

        .w-50 {
            width: 50%;
        }

        .page-break {
            page-break-after: always;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .font-weight-bold {
            font-weight: bold;
        }

        /* Fim :: Utils */
    </style>
</head>

<body>
    <div class="w-100 corpo">
        <div class="info-fatura">
            <?php $cont = 0; ?>
            <div class="cabecalho primeira-linha"><br><br>
                <div class="w-50 float-left">
                    <span class="label">Fatura: </span>
                    <span class="font-weight-bold"><?= $faturamento['codigo_faturamento']; ?></span>
                </div>
                <div class="w-50 float-left text-right">
                    <span class="label">Período: </span>
                    <span class="font-weight-bold"><?= date("d/m/Y", strtotime($faturamento['periodo_inicio'])); ?> - <?= date("d/m/Y", strtotime($faturamento['periodo_fim'])); ?></span>
                </div>
                <div class="w-50 float-left">
                    <span class="label">Usuário: </span>
                    <span class="text-uppercase font-weight-bold"><?= $faturamento['usuario']; ?></span>
                </div>
                <div class="w-50 float-left text-right">
                    <span class="label">Data do Faturamento: </span>
                    <span class="font-weight-bold"><?= date("d/m/Y", strtotime($faturamento['criado_em'])); ?></span>
                </div>
                <div class="w-50 float-left">
                    <span class="label">Comissão: </span>
                    <span class="text-uppercase font-weight-bold">
                        <?= !empty($faturamento['percentual_comissao']) ? ("{$faturamento['percentual_comissao']}% (R$" . intToReal($faturamento['valor_comissao']) . ")") : 0; ?>
                    </span>
                </div>
                <div class="w-50 float-left text-right">
                    <span class="label">Valor Total Líquido: </span>
                    <span class="font-weight-bold"><?= 'R$ ' . intToReal($faturamento['valor_liquido']); ?></span>
                </div>
            </div>
        </div>
        <div class="listagem">
            <?php $cont = 0; ?>
            <?php foreach ($vendedor as $keyV => $valueV) : ?>
                <?php $cont++; ?>
                <div class="listagem-vendedor">
                    <div class="w-100 float-left">
                        <span class="label">Vendedor: </span><span class="font-weight-bold vendedor-nome"><?= $valueV['vendedor']; ?></span>
                    </div>
                    <div class="vendas-listagem">
                        <table class="table w-100">
                            <tbody>
                                <tr>
                                    <th>CÓDIGO</th>
                                    <th>DATA</th>
                                    <th>CLIENTE</th>
                                    <th>VALOR</th>
                                </tr>
                                <?php $totalVendedor = 0; ?>
                                <?php foreach ($valueV['vendas'] as $keyV => $valueVenda) : ?>
                                    <?php $totalVendedor = $totalVendedor + $valueVenda['valor_liquido']; ?>
                                    <tr>
                                        <td><?= $valueVenda['codigo_venda']; ?></td>
                                        <td class="text-center"><?= $valueVenda['data_venda']; ?></td>
                                        <td><?= substr($valueVenda['cliente'], 0, 36); ?></td>
                                        <td class="text-right">R$ <?= intToReal($valueVenda['valor_liquido']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td></td>
                                    <td class="text-right" colspan="2"><b>Total do Vendedor</b></td>
                                    <td class="text-right font-weight-bold">R$ <?= intToReal($totalVendedor); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>
