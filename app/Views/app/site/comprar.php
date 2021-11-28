<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="view-options bg-white py-3 px-3 d-md-flex align-items-center">
                <div class="mr-auto">
                    <a href="index.html" class="icon-view view-module active"><span class="icon-view_module"></span></a>
                    <a href="view-list.html" class="icon-view view-list"><span class="icon-view_list"></span></a>

                </div>
                <div class="ml-auto d-flex align-items-center">
                    <div>
                        <a href="<?= base_url("site/buscar") ?>" class="view-list px-3 border-right active">Todos</a>
                        <a href="<?= base_url('site/buscar?codigo_categoria_imovel=1') ?>" class="view-list px-3 border-right">Aluguel</a>
                        <a href="<?= base_url('site/buscar?codigo_categoria_imovel=2') ?>" class="view-list px-3">Venda</a>
                    </div>

                    <form id="formPreco" method="GET" action="<?= base_url("site/buscar") ?>">
                        <input type="hidden" id="codigo_categoria_imovel" name="codigo_categoria_imovel">
                        <div class="select-wrap">
                            <span class="icon icon-arrow_drop_down"></span>
                            <select class="form-control form-control-sm d-block rounded-0" id="selectFormPreco" name="ordenarValor">
                                <option value="">Ordenar</option>
                                <option value="maior">Maior Preço</option>
                                <option value="menor">Menor Preço</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="site-section site-section-sm bg-light">
    <div class="container">

        <div class="row mb-5">
            <?php foreach ($imovel['itens'] as $value) : ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="property-entry h-100">
                        <a href="site/detalhes/<?= $value['uuid_imovel'] ?>" class="property-thumbnail">
                            <div class="offer-type-wrap">
                                <span class="offer-type bg-danger"><?= $value['categoria_imovel'] ?></span>
                            </div>
                            <div class="offer-type-wrap-2">
                                <span class="offer-type-2">Ref: <?= $value['codigo_referencia'] ?></span>
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
                            </ul>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="site-pagination">
                    <a href="#" class="active">1</a>
                    <a href="#">2</a>
                    <a href="#">3</a>
                    <a href="#">4</a>
                    <a href="#">5</a>
                    <span>...</span>
                    <a href="#">10</a>
                </div>
            </div>
        </div>

    </div>
</div>
</div>
