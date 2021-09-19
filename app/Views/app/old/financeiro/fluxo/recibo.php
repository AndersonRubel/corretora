<html>

<head>
    <style>
        /* CLASSES GENÉRICAS */
        .col-md-2 {
            width: 16.66666667%;
            float: left;
        }

        .col-md-3 {
            width: 25%;
            float: left;
        }

        .col-md-4 {
            width: 33.33333333%;
            float: left;
        }

        .col-md-6 {
            width: 50%;
            float: left;
        }

        .col-md-7 {
            width: 58.33333333%;
            float: left;
        }

        .col-md-8 {
            width: 66.66666667%;
            float: left;
        }

        .col-md-10 {
            width: 83.33333333%;
            float: left;
        }

        .col-md-12 {
            width: 100%;
            float: left;
        }

        .font-size-10 {
            font-size: 10px;
        }

        .font-size-12 {
            font-size: 12px;
        }

        .font-size-14 {
            font-size: 14px;
        }

        .font-size-16 {
            font-size: 16px;
        }

        .font-size-18 {
            font-size: 18px;
        }

        .center {
            text-align: center;
        }

        .left {
            text-align: left;
        }

        .right {
            text-align: right;
        }

        .italico {
            font-style: italic;
        }

        .margin-top-10 {
            margin-top: 10px;
        }

        .margin-bottom-10 {
            margin-bottom: 10px;
        }

        .margin-left-10 {
            margin-left: 10px;
        }

        .background-cinza {
            background-color: #E4E4E4;
        }

        .line-height-20 {
            line-height: 16px;
        }

        /* FIM DAS CLASSES GENÉRICAS */

        .titulo {
            font-size: 12px;
            width: 261px;
            background-color: #E4E4E4;
            border-radius: 5px;
            padding-left: 10px;
        }

        .box {
            float: left;
            width: 100%;
            border: solid 1px #777777;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .cabecalho .logo {
            float: left;
            padding-top: 10px;
            padding-bottom: 10px;
            text-align: center;
        }

        .cabecalho .logo img {
            width: 100px;
        }

        .materiais {
            float: left;
            width: 100%;
        }

        .border-right {
            border-right: 1px solid #777777;
        }

        .border-top td {
            border-top: 1px solid #777777;
        }

        .info {
            width: 20%;
            float: left;
            text-align: right;
        }

        .assinatura {
            float: right;
            margin-top: 50px;
            border-top: 1px solid #000;
        }

        .texto {
            white-space: pre-wrap;
            text-align: justify;
            margin-left: 10px;
            margin-right: 10px;
        }

        .cabecalho .valor-recibo b {
            margin-right: 10px;
        }

        /* IMPRESSÃO TÉRMICA */
        @media (max-width: 400px) {
            .col-sm-2 {
                width: 16.66666667% !important;
                float: left !important;
            }

            .col-sm-3 {
                width: 25% !important;
                float: left !important;
            }

            .col-sm-4 {
                width: 33.33333333% !important;
                float: left !important;
            }

            .col-sm-6 {
                width: 50% !important;
                float: left !important;
            }

            .col-sm-7 {
                width: 58.33333333% !important;
                float: left !important;
            }

            .col-sm-8 {
                width: 66.66666667% !important;
                float: left !important;
            }

            .col-sm-10 {
                width: 83.33333333% !important;
                float: left !important;
            }

            .col-sm-12 {
                width: 100% !important;
                float: left !important;
            }

            .cabecalho .logo img {
                width: 100px !important;
            }

            .texto,
            .assinatura {
                font-size: 12px;
            }
        }

        /* FIM DA IMPRESSÃO TÉRMICA */
    </style>

    <meta charset="UTF-8">
    <title>Recibo</title>
</head>

<?php
setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
$agente = $fluxo['cliente'];
$documento = strlen($fluxo['cliente_cpf_cnpj']) == 11 ? intToCpf($fluxo['cliente_cpf_cnpj']) : intToCnpj($fluxo['cliente_cpf_cnpj']);
$tipoDocumento = strlen($fluxo['cliente_cpf_cnpj']) == 11 ? 'cpf' : 'cnpj';
?>

<body>
    <div class="box">
        <div class="cabecalho">
            <div class="col-sm-12 col-md-2 logo">
                <img src="<?= base_url("assets/img/logo.png"); ?>">
            </div>
            <div class="col-sm-8 col-md-7">
                <div class="font-size-12 line-height-20 margin-left-10 margin-bottom-10">
                    <?= $empresa['razao_social']; ?><br>
                    DOCUMENTO: <?= !empty($empresa['cpf_cnpj']) ? intToCnpj($empresa['cpf_cnpj']) : ''; ?><br>
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
                </div>
            </div>
            <div class="col-sm-4 col-md-3 right valor-recibo">
                <b>RECIBO</b><br>
                <b>R$ <?= intToReal($fluxo['valor_liquido']); ?></b>
            </div>
        </div>
    </div><br>

    <div class="box">
        <p class="texto">Recebemos de <b><?= $agente; ?></b><?php if (!empty($documento)) : ?>, portador do <?= $tipoDocumento; ?> número <?= $documento; ?>, <?php endif ?>a quantia de<b><?= valorExtenso(intToReal($fluxo['valor_liquido']), 1, 'alta'); ?></b> referente ao pagamento do(a) <b><?= $fluxo['nome']; ?></b>. Para clareza firmo(amos) o presente.</p>
    </div>

    <div class="rodape right">
        <br>
        <span>
            <b class="font-size-12">
                <?= !empty($empresa['endereco']['cidade']) ? $empresa['endereco']['cidade'] : ''; ?> -
                <?= !empty($empresa['endereco']['uf']) ? $empresa['endereco']['uf'] : ''; ?>,
                <?= strftime('%d de %B de %Y', strtotime('today')); ?>
            </b>
        </span>
        <br>
        <br>
        <div class="col-md-6 assinatura">
            <p class="center">Assinatura</p>
        </div>
    </div>

</body>

</html>
