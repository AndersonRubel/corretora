<?php

$className = $base->getControllerName();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title><?= env('app.nomeSistema'); ?> | <?= str_replace('Controller', '', $className); ?></title>
    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/img/app-logo.svg'); ?>" />

    <meta name="application-name" content="Corretora">
    <meta name="description" content="Sistema Corretora">
    <meta name="msapplication-TileColor" content="#FFFFFF">
    <meta name="msapplication-TileImage" content="<?= base_url('assets/img/favicon/mstile-144x144.png'); ?>">
    <meta name="msapplication-square70x70logo" content="<?= base_url('assets/img/favicon/mstile-70x70.png'); ?>">
    <meta name="msapplication-square150x150logo" content="<?= base_url('assets/img/favicon/mstile-150x150.png'); ?>">
    <meta name="msapplication-wide310x150logo" content="<?= base_url('assets/img/favicon/mstile-310x150.png'); ?>">
    <meta name="msapplication-square310x310logo" content="<?= base_url('assets/img/favicon/mstile-310x310.png'); ?>">
    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="<?= base_url('assets/img/favicon/apple-touch-icon-57x57.png'); ?>">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?= base_url('assets/img/favicon/apple-touch-icon-114x114.png'); ?>">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?= base_url('assets/img/favicon/apple-touch-icon-72x72.png'); ?>">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?= base_url('assets/img/favicon/apple-touch-icon-144x144.png'); ?>">
    <link rel="apple-touch-icon-precomposed" sizes="60x60" href="<?= base_url('assets/img/favicon/apple-touch-icon-60x60.png'); ?>">
    <link rel="apple-touch-icon-precomposed" sizes="120x120" href="<?= base_url('assets/img/favicon/apple-touch-icon-120x120.png'); ?>">
    <link rel="apple-touch-icon-precomposed" sizes="76x76" href="<?= base_url('assets/img/favicon/apple-touch-icon-76x76.png'); ?>">
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="<?= base_url('assets/img/favicon/apple-touch-icon-152x152.png'); ?>">
    <link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon/apple-touch-icon-57x57.png'); ?>" sizes="196x196">
    <link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon/apple-touch-icon-57x57.png'); ?>" sizes="96x96">
    <link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon/apple-touch-icon-57x57.png'); ?>" sizes="32x32">
    <link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon/apple-touch-icon-57x57.png'); ?>" sizes="16x16">
    <link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon/apple-touch-icon-57x57.png'); ?>" sizes="128x128">

    <!-- Inicio :: Assets básicos que carregam em todas as telas -->


    <!-- JQUERY -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js?versao=<?= $base->version; ?>"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js?versao=<?= $base->version; ?>"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js?versao=<?= $base->version; ?>"></script>

    <!-- BOOTSTRAP -->
    <script type="text/javascript" src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.min.js?versao=<?= $base->version; ?>"></script>
    <!-- <script type="text/javascript" src="https://unpkg.com/tippy.js@6/dist/tippy-bundle.umd.js?versao=<?= $base->version; ?>"></script> -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tippy.js/6.3.1/tippy-bundle.umd.js?versao=<?= $base->version; ?>"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/fontawesome.min.css?versao=<?= $base->version; ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css?versao=<?= $base->version; ?>">

    <!-- SWEETALERT 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js?versao=<?= $base->version; ?>"></script>

    <!-- MOMENT -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js?versao=<?= $base->version; ?>"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js?versao=<?= $base->version; ?>"></script>

    <!-- SELECT 2-->
    <link rel="stylesheet" href="<?= base_url("assets/plugins/select2/select2.css"); ?>?versao=<?= $base->version; ?>">
    <link rel="stylesheet" href="<?= base_url("assets/plugins/select2/select2-bootstrap.css"); ?>?versao=<?= $base->version; ?>">
    <script type="text/javascript" src="<?= base_url("assets/plugins/select2/select2.js"); ?>?versao=<?= $base->version; ?>"></script>
    <script type="text/javascript" src="<?= base_url("assets/plugins/select2/select2_locale_pt-BR.js"); ?>?versao=<?= $base->version; ?>"></script>

    <!-- Filepond - Plugin de Upload de Imagens-->
    <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.js"></script>
    <script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css">
    <link rel="stylesheet" href="https://unpkg.com/filepond/dist/filepond.min.css">

    <!-- Tinymce -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.8.2/tinymce.min.js?versao=<?= $base->version; ?>"></script>

    <!-- Funções Padrão -->
    <script type="text/javascript" src="<?= base_url("assets/js/ajax-control.js"); ?>?versao=<?= $base->version; ?>"></script>
    <script type="text/javascript" src="<?= base_url("assets/js/app.js"); ?>?versao=<?= $base->version; ?>"></script>
    <script type="text/javascript" src="<?= base_url("assets/js/convert.js"); ?>?versao=<?= $base->version; ?>"></script>
    <script type="text/javascript" src="<?= base_url("assets/js/mask.js"); ?>?versao=<?= $base->version; ?>"></script>
    <script type="text/javascript" src="<?= base_url("assets/js/notification.js"); ?>?versao=<?= $base->version; ?>"></script>
    <script type="text/javascript" src="<?= base_url("assets/js/ui.js"); ?>?versao=<?= $base->version; ?>"></script>
    <script type="text/javascript" src="<?= base_url("assets/js/valid.js"); ?>?versao=<?= $base->version; ?>"></script>
    <!-- Funções Padrão -->

    <!-- Fim :: Assets básicos que carregam em todas as telas -->

    <!-- Inicio :: Arquivos CSS que carregam em todas as telas -->
    <link rel="stylesheet" href="<?= base_url("assets/css/portal.css"); ?>?versao=<?= $base->version; ?>">
    <link rel="stylesheet" href="<?= base_url("assets/css/global.css"); ?>?versao=<?= $base->version; ?>">
    <link rel="stylesheet" href="<?= base_url("assets/css/datagrid.css"); ?>?versao=<?= $base->version; ?>">
    <link rel="stylesheet" href="<?= base_url("assets/css/pages.css"); ?>?versao=<?= $base->version; ?>">
    <!-- Fim :: Arquivos CSS que carregam em todas as telas -->

    <!-- Inicio :: Arquivos CSS Carregados Dinamicamente -->

    <style>
        /*****************************/
        /******* VARIAVEIS CSS *******/
        /*****************************/
        :root {
            --primarycolor: #0E2D39;
            --secondarycolor: #0593ff;
            --tertiarycolor: #232732;
            --whitecolor: #FFFFFF;

            --primarybackgroundcolor: #eff3fa;
            --secondarybackgroundcolor: #f8f9fa;

            --primaryfont: 'Roboto', sans-serif;
            --fontsize: 14px;

            --primaryfontcolor: #756e8b;
            --secondaryfontcolor: #a9a2bf;
            --tertyaryfontcolor: #d9d3ed;

            --boxshadow: 0px 5px 10px 0px rgba(0, 0, 0, 0.1);
            --modalbackdrop: rgba(0, 0, 0, 0.5);
            --borderradius: 5px;
        }
    </style>

    <?php if ($className == 'LoginController') : ?>
        <!-- CSS Login -->

    <?php endif; ?>

    <!-- Fim :: Arquivos CSS Carregados Dinamicamente -->

    <!-- Inicio :: Plugins Carregados Dinamicamente -->

    <?php if (!in_array($className, ['LoginController', 'DashboardController', 'PdvController'])) : ?>

        <!-- DATATABLES -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" />
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/autofill/2.3.7/css/autoFill.dataTables.css" />
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css" />
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/colreorder/1.5.4/css/colReorder.dataTables.min.css" />
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/datetime/1.1.0/css/dataTables.dateTime.min.css" />
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.3.3/css/fixedColumns.dataTables.min.css" />
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedheader/3.1.9/css/fixedHeader.dataTables.min.css" />
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/keytable/2.6.2/css/keyTable.dataTables.min.css" />
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css" />
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowgroup/1.1.3/css/rowGroup.dataTables.min.css" />
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css" />
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/scroller/2.0.4/css/scroller.dataTables.min.css" />
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/searchbuilder/1.1.0/css/searchBuilder.dataTables.min.css" />
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/searchpanes/1.3.0/css/searchPanes.dataTables.min.css" />
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.3.3/css/select.dataTables.min.css" />

        <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/autofill/2.3.7/js/dataTables.autoFill.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/autofill/2.3.7/js/autoFill.dataTables.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.dataTables.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.colVis.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/colreorder/1.5.4/js/dataTables.colReorder.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/datetime/1.1.0/js/dataTables.dateTime.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.3.3/js/dataTables.fixedColumns.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/fixedheader/3.1.9/js/dataTables.fixedHeader.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/keytable/2.6.2/js/dataTables.keyTable.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.dataTables.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/rowgroup/1.1.3/js/dataTables.rowGroup.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/scroller/2.0.4/js/dataTables.scroller.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/searchbuilder/1.1.0/js/dataTables.searchBuilder.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/searchbuilder/1.1.0/js/searchBuilder.dataTables.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/searchpanes/1.3.0/js/dataTables.searchPanes.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/searchpanes/1.3.0/js/searchPanes.dataTables.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>

    <?php endif; ?>

    <?php if (in_array($className, ['FinanceiroController'])) : ?>

        <!-- ChartJS -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1/dist/chart.min.js"></script>
    <?php endif; ?>

    <!-- Fim :: Plugins Carregados Dinamicamente -->

    <script>
        // Globais JS
        const BASEURL = "<?= base_url(); ?>"; // URL do Sistema
        const CONTROLLER = "<?= $base->getControllerName(); ?>"; // Recebe o Nome do Controller
        const METODO = "<?= $base->getMethodName(); ?>"; // Recebe o Método do Controller
        const ROUTE = "<?= $base->getRouterName(); ?>"; // Recebe a rota atual
    </script>
</head>

<?php if (!in_array($className, ['LoginController', 'PdvController']) && !in_array($base->getMethodName(), ['recuperarSenha'])) : ?>

    <body class="app">
    <?php else : ?>

        <body class="app app-login p-0">
        <?php endif; ?>

        <!-- Inicio :: Loader -->
        <div class="loader">
            <div class="wrapper">
                <img src="<?= base_url("assets/img/icone-joia.png"); ?>" alt="Loader">
                <p>Carregando...</p>
            </div>
        </div>
        <!-- Fim :: Loader -->

        <script type="text/javascript">
            appFunctions.showLoader();

            // Inicio :: FlashDataToast
            let responseFlashTipo = "<?= !empty($responseFlash['tipo']) ? $responseFlash['tipo'] : ''; ?>";
            let responseFlashMensagem = "<?= !empty($responseFlash['mensagem']) ? $responseFlash['mensagem'] : '' ?>";

            if (responseFlashTipo && responseFlashMensagem) {
                notificationFunctions.toastSmall(responseFlashTipo, responseFlashMensagem);
            }
            // Fim :: FlashDataToast
        </script>
