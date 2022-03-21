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
                        <a href="<?= base_url("site/buscar") ?>" id="todos" class="view-list px-3 border-right">Todos</a>
                        <a href="<?= base_url('site/buscar?codigo_categoria_imovel=1') ?>" id="aluguel" class="view-list px-3 border-right">Aluguel</a>
                        <a href="<?= base_url('site/buscar?codigo_categoria_imovel=2') ?>" id="venda" class="view-list px-3">Venda</a>
                    </div>
                    <form id="formCondominio" method="GET" action="">
                        <input type="hidden" id="codigo_categoria_imovel_condominio" name="codigo_categoria_imovel" value>
                        <input type="hidden" id="quarto_condominio" name="quarto" value>
                        <input type="hidden" id="ordenar_valor_condominio" name="ordenar_valor" value>
                        <div class="select-wrap">
                            <span class="icon icon-arrow_drop_down"></span>
                            <select class="form-control form-control-sm d-block rounded-0" id="selectFormCondominio" name="condominio">
                                <option value="">Condomínio?&nbsp; &nbsp; &nbsp; &nbsp;</option>
                                <option value="t">Sim &nbsp; &nbsp; &nbsp;</option>
                                <option value="f">Não &nbsp; &nbsp; &nbsp;</option>
                            </select>
                        </div>
                    </form>
                    <form id="formQuarto" method="GET" action="">
                        <input type="hidden" id="codigo_categoria_imovel_quarto" name="codigo_categoria_imovel" value>
                        <input type="hidden" id="ordenar_valor_quarto" name="ordenar_valor" value>
                        <input type="hidden" id="condominio_quarto" name="condominio" value>
                        <div class="select-wrap">
                            <span class="icon icon-arrow_drop_down"></span>
                            <select class="form-control form-control-sm d-block rounded-0" id="selectFormQuarto" name="quarto">
                                <option value="">Quartos</option>
                                <option value="1">1 Quarto</option>
                                <option value="2">2 Quarto(s)</option>
                                <option value="3">3 Quarto(s)</option>
                                <option value="4">4 Quarto(s) ou + &nbsp; &nbsp; &nbsp;</option>
                            </select>
                        </div>
                    </form>
                    <form id="formPreco" method="GET" action="">
                        <input type="hidden" id="codigo_categoria_imovel_valor" name="codigo_categoria_imovel" value>
                        <input type="hidden" id="quarto_valor" name="quarto" value>
                        <input type="hidden" id="condominio_valor" name="condominio" value>
                        <div class="select-wrap">
                            <span class="icon icon-arrow_drop_down"></span>
                            <select class="form-control form-control-sm d-block rounded-0" id="selectFormPreco" name="ordenar_valor">
                                <option value="">Valor</option>
                                <option value="maior">Maior Valor &nbsp; &nbsp; &nbsp;</option>
                                <option value="menor">Menor Valor &nbsp; &nbsp; &nbsp;</option>
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
                        <a href="<?= ("detalhes/" . $value['uuid_imovel']) ?>" class="property-thumbnail">
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
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="site-pagination">
                    <a href="#" class="active">1</a>
                </div>
            </div>
        </div>

    </div>
</div>
</div>
