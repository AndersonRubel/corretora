<div class="breadcrumb-wrap mt-5">
    <div class="container">
        <a class="btn text-light" style="background-color: #364e68;" href="<?= previous_url(true) ?>">Voltar</a>

    </div>
</div>
<div class="container mt-5">
    <h2>
        <?= $imovel['tipo_imovel'] . " à " . strtolower($imovel['categoria_imovel']) . " - " . $imovel['endereco'] ?>
    </h2>

</div>
<div class="site-section site-section-sm">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div>
                    <div class="slide-one-item home-slider owl-carousel">

                        <?php foreach ($imagemImovel as $value) : ?>
                            <div>
                                <img src="<?= $value['diretorio_imagem'] ?>" alt="Image" style="object-fit:cover; width:100%;height:400px !important">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="bg-white property-body border-bottom border-left border-right">
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <strong class="text-success h1 mb-3">R$<?= intToreal($imovel['valor']) ?></strong>
                        </div>
                        <!-- <div class="col-md-6">
                            <ul class="property-specs-wrap mb-3 mb-lg-0  float-lg-right">
                                <li>
                                    <span class="property-specs">Quarto(s)</span>
                                    <span class="property-specs-number"></span>
                                </li>
                                <li>
                                    <span class="property-specs">Suite(s)</span>
                                    <span class="property-specs-number"></span>
                                </li>
                                <li>
                                    <span class="property-specs">Banheiro(s)</span>
                                    <span class="property-specs-number">></span>

                                </li>
                            </ul>
                        </div> -->
                    </div>
                    <div class="row mb-5">
                        <div class="col-md-6 col-lg-4 text-center border-bottom border-top py-3">
                            <span class="d-inline-block text-black mb-0 caption-text">Tipo</span>
                            <strong class="d-block"><?= $imovel['tipo_imovel'] ?></strong>
                        </div>
                        <?php if ($imovel['tipo_imovel'] != 'Terreno') : ?>
                            <div class="col-md-6 col-lg-4 text-center border-bottom border-top py-3">
                                <span class="d-inline-block text-black mb-0 caption-text">Quarto(s)</span>
                                <strong class="d-block"><?= $imovel['quarto'] ?></strong>
                            </div>
                            <div class="col-md-6 col-lg-4 text-center border-bottom border-top py-3">
                                <span class="d-inline-block text-black mb-0 caption-text">Suite(s)</span>
                                <strong class="d-block"><?= $imovel['suite'] ?></strong>
                            </div>
                            <div class="col-md-6 col-lg-4 text-center border-bottom border-top py-3">
                                <span class="d-inline-block text-black mb-0 caption-text">Banheiro(s)</span>
                                <strong class="d-block"><?= $imovel['banheiro'] ?></strong>
                            </div>
                            <div class="col-md-6 col-lg-4 text-center border-bottom border-top py-3">
                                <span class="d-inline-block text-black mb-0 caption-text">Vagas(s)</span>
                                <strong class="d-block"><?= $imovel['vaga'] ?></strong>
                            </div>
                            <div class="col-md-6 col-lg-4 text-center border-bottom border-top py-3">
                                <span class="d-inline-block text-black mb-0 caption-text">Área Construida</span>
                                <strong class="d-block"><?= $imovel['area_construida'] ?></strong>
                            </div>
                            <div class="col-md-6 col-lg-4 text-center border-bottom border-top py-3">
                                <span class="d-inline-block text-black mb-0 caption-text">Edícula</span>
                                <strong class="d-block"><?= $imovel['edicula'] == true ? 'Sim' : 'Não' ?></strong>
                            </div>
                        <?php endif; ?>
                        <div class="col-md-6 col-lg-4 text-center border-bottom border-top py-3">
                            <span class="d-inline-block text-black mb-0 caption-text">Área Total</span>
                            <strong class="d-block"><?= $imovel['area_total'] ?></strong>
                        </div>
                    </div>
                    <h2 class="h4 text-black">Mais Informações</h2>
                    <p><?= $imovel['descricao'] ?></p>

                    <div class="row no-gutters mt-5">
                        <div class="col-12">
                            <h2 class="h4 text-black mb-3">Galeria</h2>
                        </div>
                        <?php foreach ($imagemImovel as $value) : ?>
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <a href="<?= $value['diretorio_imagem'] ?>" class="image-popup gal-item"><img src="<?= $value['diretorio_imagem'] ?>" alt="Image" class="img-fluid"></a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">

                <div class="bg-white widget border rounded">

                    <h3 class="h4 text-black widget-title mb-3">Contato</h3>
                    <form action="" class="form-contact-agent">
                        <div class="form-group">
                            <label for="name">Nome</label>
                            <input type="text" id="name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="phone">Telefone</label>
                            <input type="text" id="phone" class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="submit" id="phone" class="btn btn-primary" value="Enviar Mensagem">
                        </div>
                    </form>
                </div>

                <div class="bg-white widget border rounded">
                    <h3 class="h4 text-black widget-title mb-3">Contato whats</h3>
                    <a class="btn btn-success" href="https://web.whatsapp.com/send?phone=5542998230013"> 55 42 9 9823-0013</a>
                </div>

            </div>
            <div class="col-md-12 col-lg-12 mt-3 col-sm-12">
                <div class="border-map">
                    <?= $imovel['mapa'] ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="site-section site-section-sm bg-light">
    <div class="container">

        <div class="row">
            <div class="col-12">
                <div class="site-section-title mb-5">
                    <h2>Outros Imóveis</h2>
                </div>
            </div>
        </div>


        <div class="row mb-5">
            <?php foreach ($itens as $value) : ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="property-entry h-100">
                        <a href="<?= base_url("site/detalhes/" . $value['uuid_imovel']) ?>" class="property-thumbnail">
                            <div class="offer-type-wrap">
                                <span class="offer-type bg-danger"><?= $value['categoria_imovel'] ?></span>
                            </div>
                            <div class="offer-type-wrap-2">
                                <span class=" offer-type-2">Ref: <?= $value['codigo_referencia'] ?></span>
                            </div>
                            <div class="row ">
                                <img src="<?= $value['imagem_destaque'] ?>" alt="Image" style="object-fit:cover; width:400px;height:200px !important">
                            </div>
                        </a>
                        <div class="p-4 property-body">
                            <a href="#" class="property-favorite"><span class="icon-heart-o"></span></a>
                            <h2 class="property-title"><a href="property-details.html"><?= $value['tipo_imovel'] ?></a></h2>
                            <span class="property-location d-block mb-3"><span class="property-icon icon-room"></span><?= $value['endereco'] ?></span>
                            <strong class="property-price text-primary mb-3 d-block text-success" data-mask="dinheiro">R$<?= intToreal($value['valor']) ?></strong>
                            <ul class="property-specs-wrap mb-3 mb-lg-0">
                                <?php if ($value['tipo_imovel'] != 'Terreno') : ?>
                                    <li>
                                        <span class="property-specs">Quarto(s)</span>
                                        <span class="property-specs-number"><?= $value['quarto'] ?></span>

                                    </li>
                                    <li>
                                        <span class="property-specs">Suite(s)</span>
                                        <span class="property-specs-number"><?= $value['suite'] ?></span>

                                    </li>
                                    <li>
                                        <span class="property-specs">banheiro(s)</span>
                                        <span class="property-specs-number"><?= $value['banheiro'] ?></span>

                                    </li>
                                    <li>
                                        <span class="property-specs">Área Construida</span>
                                        <span class="property-specs-number"><?= $value['area_construida'] ?> M²</span>

                                    </li>
                                <?php endif; ?>
                                <li>
                                    <span class="property-specs">Área Total</span>
                                    <span class="property-specs-number"><?= $value['area_total'] ?> M²</span>

                                </li>
                            </ul>

                        </div>





                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<style>
    iframe {
        height: 300px !important;
        width: 100% !important;
    }

    .border-map {
        border: 1px solid #dee2e6 !important;
    }
</style>
