<!-- Inicio :: Navbar -->
<header class="app-header fixed-top">
    <div class="">
        <div class="container-fluid">
            <div class="app-header-content">
                <div class="row justify-content-between align-items-center">

                    <div class="col-auto">
                        <div class="d-flex">
                            <div class="app-branding mt-2 p-0 mb-0">
                                <a class="app-logo" href="<?= base_url(); ?>">
                                    <img class="logo-icon me-2" src="<?= base_url('assets/img/app-logo.svg'); ?>" alt="logo">
                                    <span class="logo-text"><?= env('app.nomeSistema'); ?></span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-auto">
                        <div class="app-utility-item">
                            <a id="sidepanel-toggler" class="sidepanel-toggler" href="#">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" role="img">
                                    <title>Menu</title>
                                    <path stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2" d="M4 7h22M4 15h22M4 23h22"></path>
                                </svg>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- INICIO :: SIDEBAR -->
    <?= view('app/pdv/sidebar'); ?>
    <!-- FIM :: SIDEBAR -->

</header>
<!-- Fim :: Navbar -->
