<style>
    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    .ttu {
        text-transform: uppercase;
    }

    .printer-ticket {
        display: table !important;
        width: 305px;
        max-width: 400px;
        font-weight: lighter;
    }

    .printer-ticket,
    .printer-ticket * {
        font-family: Tahoma, Geneva, sans-serif;
        font-size: 10px;
    }

    .printer-ticket th {
        font-weight: inherit;
        padding: 3px 0;
        border-bottom: 1px dashed #bcbcbc;
    }

    .printer-ticket tfoot .sup td {
        padding: 10px 0;
        border-top: 1px dashed #bcbcbc;
    }

    .printer-ticket tfoot .sup.p--0 td {
        padding-bottom: 0;
    }

    .printer-ticket .title {
        font-size: 1.4em;
        padding: 15px 0 5px 0;
    }
</style>

<table class="printer-ticket">
    <thead>
        <tr>
            <th class="title" colspan="4">
                <?= $empresa['nome_fantasia']; ?> <br>
                <?php if (!empty($empresa['endereco']['cep'])) : ?>
                    <small>
                        <?= !empty($empresa['endereco']['rua']) ? $empresa['endereco']['rua'] : ''; ?>,
                        <?= !empty($empresa['endereco']['numero']) ? $empresa['endereco']['numero'] : ''; ?> -
                        <?= !empty($empresa['endereco']['bairro']) ? $empresa['endereco']['bairro'] : ''; ?> <br>
                        <?= !empty($empresa['endereco']['cidade']) ? $empresa['endereco']['cidade'] : ''; ?> -
                        <?= !empty($empresa['endereco']['uf']) ? $empresa['endereco']['uf'] : ''; ?> -
                        <?= !empty($empresa['endereco']['cep']) ? intToCep($empresa['endereco']['cep']) : ''; ?> <br>
                        <?= !empty($empresa['telefone']) ? intToPhone($empresa['telefone']) : ''; ?>
                    </small>
                <?php endif; ?>
            </th>
        </tr>
        <tr>
            <th colspan="4">
                <b>Detalhe do Fluxo</b>
                <?= $fluxo['codigo_financeiro_fluxo']; ?>
            </th>
        </tr>
        <tr>
            <th colspan="4">
                <?= $fluxo['nome']; ?> <br>
                <?= $fluxo['observacao']; ?>
            </th>
        </tr>

    </thead>

    <tfoot>

        <tr class="ttu">
            <td colspan="3">Tipo</td>
            <td class="text-right"><?= $fluxo['tipo']; ?></td>
        </tr>

        <tr class="ttu">
            <td colspan="3">Método de Pagamento</td>
            <td class="text-right"><?= $fluxo['metodo_pagamento']; ?></td>
        </tr>

        <tr class="ttu">
            <td colspan="3">Situação</td>
            <td class="text-right"><?= $fluxo['situacao'] == 't' ? 'Pago' : 'Pendente'; ?></td>
        </tr>

        <tr class="ttu">
            <td colspan="3">Data de Vencimento</td>
            <td class="text-right"><?= $fluxo['data_vencimento']; ?></td>
        </tr>

        <?php if (!empty($fluxo['data_pagamento'])) : ?>
            <tr class="ttu">
                <td colspan="3">Data de Pagamento</td>
                <td class="text-right"><?= $fluxo['data_pagamento']; ?></td>
            </tr>
        <?php endif; ?>


        <?php if (!empty($fluxo['fornecedor'])) : ?>
            <tr class="ttu">
                <td colspan="3">FORNECEDOR</td>
                <td class="text-right"><?= $fluxo['fornecedor']; ?></td>
            </tr>
        <?php endif; ?>

        <?php if (!empty($fluxo['cliente'])) : ?>
            <tr class="ttu">
                <td colspan="3">CLIENTE</td>
                <td class="text-right"><?= $fluxo['cliente']; ?></td>
            </tr>
        <?php endif; ?>

        <?php if (!empty($fluxo['vendedor'])) : ?>
            <tr class="ttu">
                <td colspan="3">VENDEDOR</td>
                <td class="text-right"><?= $fluxo['vendedor']; ?></td>
            </tr>
        <?php endif; ?>

        <!-- Inicio :: Valores -->
        <tr class="ttu sup p--0">
            <td colspan="3">Valor Total</td>
            <td class="text-right">R$ <?= intToReal($fluxo['valor_bruto']); ?></td>
        </tr>
        <tr class="ttu">
            <td colspan="3">Acréscimo</td>
            <td class="text-right">R$ <?= intToReal($fluxo['valor_acrescimo']); ?></td>
        </tr>
        <tr class="ttu">
            <td colspan="3">Juros</td>
            <td class="text-right">R$ <?= intToReal($fluxo['valor_juros']); ?></td>
        </tr>
        <tr class="ttu">
            <td colspan="3">Desconto</td>
            <td class="text-right">R$ <?= intToReal($fluxo['valor_desconto']); ?></td>
        </tr>
        <tr class="ttu">
            <td colspan="3">Pago Parcial</td>
            <td class="text-right">R$ <?= intToReal($fluxo['valor_pago_parcial']); ?></td>
        </tr>
        <tr class="ttu">
            <td colspan="3">Valor a Pagar</td>
            <td class="text-right">R$ <?= intToReal($fluxo['valor_liquido']); ?></td>
        </tr>

        <!-- Fim :: Valores -->

        <tr class="sup">
            <td colspan="4" align="center">
                <b>Usuário:</b> <?= $fluxo['usuario_criacao']; ?><br>
                <b>Criado em:</b> <?= $fluxo['criado_em']; ?><br>
                www.corretora.com.br -
                <?= date('d/m/Y H:i'); ?>
            </td>
        </tr>
    </tfoot>
</table>
