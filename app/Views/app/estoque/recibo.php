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
                <b>Detalhe da Venda</b>
            </th>
        </tr>
        <tr>
            <th colspan="4">
                Consumidor <br>
                <b><?= $consumidor; ?></b>
            </th>
        </tr>
        <tr>
            <th><b>#</b></th>
            <th><b>CÓDIGO</b></th>
            <th><b>DESCRIÇÃO</b></th>
            <th class="text-right"><b>VALOR</b></th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($produtos as $key => $value) : ?>
            <tr class="top">
                <td><?= ($key + 1); ?></td>
                <td><?= $value['codigo_produto']; ?></td>
                <td><?= substr($value['nome_produto'], 0, 10); ?></td>
                <td class="text-right">R$ <?= intToReal($value['valor_total']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>

    <tfoot>
        <tr class="sup p--0">
            <td colspan="3">QTD TOTAL DE ITENS</td>
            <td class="text-right"><?= count($produtos); ?></td>
        </tr>
        <tr class="ttu">
            <td colspan="3">Valor Total</td>
            <td class="text-right">R$ <?= intToReal($venda['valor_bruto']); ?></td>
        </tr>
        <?php if (!empty($venda['valor_desconto'])) : ?>
            <tr class="ttu">
                <td colspan="3">Desconto</td>
                <td class="text-right">R$ <?= intToReal($venda['valor_desconto']); ?></td>
            </tr>
        <?php endif; ?>
        <?php if (!empty($venda['valor_troco'])) : ?>
            <tr class="ttu">
                <td colspan="3">Troco</td>
                <td class="text-right">R$ <?= intToReal($venda['valor_troco']); ?></td>
            </tr>
        <?php endif; ?>
        <?php if (!empty($venda['valor_entrada'])) : ?>
            <tr class="ttu">
                <td colspan="3">Entrada</td>
                <td class="text-right">R$ <?= intToReal($venda['valor_entrada']); ?></td>
            </tr>
        <?php endif; ?>
        <tr class="ttu">
            <td colspan="3">Valor a Pagar</td>
            <td class="text-right">R$ <?= intToReal($venda['valor_entrada'] - $venda['valor_liquido']); ?></td>
        </tr>
        <tr class="sup">
            <td colspan="4" align="center">
                <b>Venda:</b> <?= $venda['codigo_venda']; ?> <br>
                <b>Vendedor (a):</b> <?= $vendedor; ?> <br>
                www.corretora.com.br <br>
                <?= date('d/m/Y H:i'); ?>
            </td>
        </tr>
    </tfoot>
</table>
