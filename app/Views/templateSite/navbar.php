<body>
    <div>
        <div class="site-loader"></div>

        <div class="site-wrap">

            <div class="site-mobile-menu">
                <div class="site-mobile-menu-header">
                    <div class="site-mobile-menu-close mt-3">
                        <span class="icon-close2 js-menu-toggle"></span>
                    </div>
                </div>
                <div class="site-mobile-menu-body"></div>
            </div> <!-- .site-mobile-menu -->

            <div class="site-navbar" style="background-color: #364e68;position:initial">
                <div class="container py-1">
                    <div class="row align-items-center">
                        <div class="col-8 col-md-8 col-lg-4">
                            <h1 class="mb-0"><a href="<?= base_url('site') ?>" class="text-white h2 mb-0"><strong>Corretora<span class="text-danger">.</span></strong></a></h1>
                        </div>
                        <div class="col-4 col-md-4 col-lg-8">
                            <nav class="site-navigation text-right text-md-right" role="navigation">

                                <div class="d-inline-block d-lg-none ml-md-0 mr-auto py-3"><a href="#" class="site-menu-toggle js-menu-toggle text-white"><span class="icon-menu h3"></span></a></div>

                                <ul class="site-menu js-clone-nav d-none d-lg-block">
                                    <li class="active">
                                        <a href="<?= base_url('site') ?>">Home</a>
                                    </li>
                                    <li><a href="<?= base_url('site/buscar?codigo_categoria_imovel=2') ?>">Comprar</a></li>
                                    <li><a href="<?= base_url('site/buscar?codigo_categoria_imovel=1') ?>">Alugar</a></li>
                                    <!-- <li class="has-children">
                                    <a href="properties.html">Propriedades</a>
                                    <ul class="dropdown arrow-top ">
                                        <li><a href="#">Condominio</a></li>
                                        <li><a href="#">Property Land</a></li>
                                        <li><a href="#">Commercial Building</a></li>
                                        <li class="has-children">
                                            <a href="#">Sub Menu</a>
                                            <ul class="dropdown">
                                                <li><a href="#">Menu One</a></li>
                                                <li><a href="#">Menu Two</a></li>
                                                <li><a href="#">Menu Three</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li> -->
                                    <li><a href="<?= base_url('sobre') ?>">Sobre</a></li>
                                    <li><a href="<?= base_url('contato') ?>">Contato</a></li>
                                </ul>
                            </nav>
                        </div>






                    </div>
                </div>
            </div>
        </div>
    </div>
