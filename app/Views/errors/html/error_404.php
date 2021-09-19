<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
    <title><?= env('app.nomeSistema'); ?> - Página não encontrada</title>
    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/img/app-logo.svg'); ?>" />

    <link rel="stylesheet" href="<?= base_url("assets/css/portal.css"); ?>">
</head>

<body class="app app-404-page">
    <div class="container mb-5">
        <div class="row">
            <div class="col-12 col-md-11 col-lg-7 col-xl-6 mx-auto">
                <div class="app-branding text-center mb-5">
                    <a class="app-logo" href="<?= base_url() ?>">
                        <img class="logo-icon me-2" src="<?= base_url('assets/img/app-logo.svg'); ?>" alt="logo"> <br>
                        <span class="logo-text"><?= env('app.nomeSistema'); ?></span>
                    </a>
                </div>
                <div class="app-card p-5 text-center shadow-sm">
                    <h1 class="page-title mb-4">404<br><span class="font-weight-light">Página não encontrada</span></h1>
                    <div class="mb-4">
                        Desculpa! Não consigo encontrar a página que você está procurando. <br><br>
                        <?php if (!empty($message) && $message !== '(null)') : ?>
                            <strong><?= nl2br(esc($message)) ?>"</strong>
                        <?php endif; ?>
                        <br>
                        <br>
                        <a class="btn btn-primary btn-success text-white" href="<?= base_url() ?>">Ir para a tela inicial</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
