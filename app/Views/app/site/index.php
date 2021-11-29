<div class="slide-one-item home-slider owl-carousel">

    <div class="site-blocks-cover overlay" style="background-image: url(assets/site/images/hero_bg_1.jpg);" data-aos="fade" data-stellar-background-ratio="0.5">
    </div>

</div>


<div class="site-section site-section-sm pb-0">
    <div class="container">
        <div class="row">
            <form class="form-search col-md-12" style="margin-top: -650px; margin-bottom: 450px;" method="POST" action="<?= base_url('site/buscar'); ?>">
                <div class=" row align-items-end">

                    <div class="col-md-3">
                        <label for="tipo_imovel">Tipos de Imóvel</label>
                        <div class="select-wrap">
                            <span class="icon icon-arrow_drop_down"></span>
                            <select name="codigo_tipo_imovel" id="codigo_tipo_imovel" class="form-control d-block rounded-0">
                                <option value>Todos</option>
                                <?php foreach ($tipoImovel as $value) : ?>
                                    <option value="<?= $value['codigo_tipo_imovel'] ?>"><?= $value['nome'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="offer-types">Categorias</label>
                        <div class="select-wrap">
                            <span class="icon icon-arrow_drop_down"></span>
                            <select name="codigo_categoria_imovel" id="codigo_categoria_imovel" class="form-control d-block rounded-0">
                                <option value>Todas</option>
                                <?php foreach ($categoriaImovel as $value) : ?>
                                    <option value="<?= $value['codigo_categoria_imovel'] ?>"><?= $value['nome'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="select-city">Selecione a Cidade</label>
                        <div class="select-wrap">
                            <span class="icon icon-arrow_drop_down"></span>
                            <select name="cidade" id="cidade" class="form-control d-block rounded-0">
                                <option value>Todas</option>
                                <?php foreach ($cidades as $value) : ?>
                                    <option value="<?= $value['cidade'] ?>"><?= $value['cidade'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <input type="submit" class="btn btn-success text-white btn-block rounded-0" value="Buscar">
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

<div class="site-section site-section-sm bg-light">
    <div class="container">

        <div class="row">
            <div class="col-12">
                <div class="site-section-title mb-5">
                    <h2>Destaques</h2>
                </div>
            </div>
        </div>


        <div class="row mb-5">
            <?php foreach ($imovel['itens'] as $value) : ?>
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

<!-- <div class="site-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 text-center">
                <div class="site-section-title">
                    <h2>Why Choose Us?</h2>
                </div>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Debitis maiores quisquam saepe
                    architecto error
                    corporis aliquam. Cum ipsam a consectetur aut sunt sint animi, pariatur corporis, eaque,
                    deleniti cupiditate
                    officia.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-lg-4">
                <a href="#" class="service text-center">
                    <span class="icon flaticon-house"></span>
                    <h2 class="service-heading">Research Subburbs</h2>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Incidunt iure qui natus perspiciatis
                        ex odio
                        molestia.</p>
                    <p><span class="read-more">Read More</span></p>
                </a>
            </div>
            <div class="col-md-6 col-lg-4">
                <a href="#" class="service text-center">
                    <span class="icon flaticon-sold"></span>
                    <h2 class="service-heading">Sold Houses</h2>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Incidunt iure qui natus perspiciatis
                        ex odio
                        molestia.</p>
                    <p><span class="read-more">Read More</span></p>
                </a>
            </div>
            <div class="col-md-6 col-lg-4">
                <a href="#" class="service text-center">
                    <span class="icon flaticon-camera"></span>
                    <h2 class="service-heading">Security Priority</h2>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Incidunt iure qui natus perspiciatis
                        ex odio
                        molestia.</p>
                    <p><span class="read-more">Read More</span></p>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="site-section bg-light">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-md-7 text-center">
                <div class="site-section-title">
                    <h2>Recent Blog</h2>
                </div>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Debitis maiores quisquam saepe
                    architecto error
                    corporis aliquam. Cum ipsam a consectetur aut sunt sint animi, pariatur corporis, eaque,
                    deleniti cupiditate
                    officia.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-4 mb-5" data-aos="fade-up" data-aos-delay="100">
                <a href="#"><img src="images/img_4.jpg" alt="Image" class="img-fluid"></a>
                <div class="p-4 bg-white">
                    <span class="d-block text-secondary small text-uppercase">Jan 20th, 2019</span>
                    <h2 class="h5 text-black mb-3"><a href="#">Art Gossip by Mike Charles</a></h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias enim, ipsa exercitationem
                        veniam quae
                        sunt.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-5" data-aos="fade-up" data-aos-delay="200">
                <a href="#"><img src="images/img_2.jpg" alt="Image" class="img-fluid"></a>
                <div class="p-4 bg-white">
                    <span class="d-block text-secondary small text-uppercase">Jan 20th, 2019</span>
                    <h2 class="h5 text-black mb-3"><a href="#">Art Gossip by Mike Charles</a></h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias enim, ipsa exercitationem
                        veniam quae
                        sunt.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-5" data-aos="fade-up" data-aos-delay="300">
                <a href="#"><img src="images/img_3.jpg" alt="Image" class="img-fluid"></a>
                <div class="p-4 bg-white">
                    <span class="d-block text-secondary small text-uppercase">Jan 20th, 2019</span>
                    <h2 class="h5 text-black mb-3"><a href="#">Art Gossip by Mike Charles</a></h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias enim, ipsa exercitationem
                        veniam quae
                        sunt.</p>
                </div>
            </div>

        </div>

    </div>
</div>


<div class="site-section">
    <div class="container">
        <div class="row mb-5 justify-content-center">
            <div class="col-md-7">
                <div class="site-section-title text-center">
                    <h2>Our Agents</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vero magnam officiis ipsa eum
                        pariatur labore
                        fugit amet eaque iure vitae, repellendus laborum in modi reiciendis quis! Optio minima
                        quibusdam,
                        laboriosam.</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-4 mb-5 mb-lg-5">
                <div class="team-member">

                    <img src="images/person_1.jpg" alt="Image" class="img-fluid rounded mb-4">

                    <div class="text">

                        <h2 class="mb-2 font-weight-light text-black h4">Megan Smith</h2>
                        <span class="d-block mb-3 text-white-opacity-05">Real Estate Agent</span>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Modi dolorem totam non quis
                            facere blanditiis
                            praesentium est. Totam atque corporis nisi, veniam non. Tempore cupiditate, vitae minus
                            obcaecati
                            provident beatae!</p>
                        <p>
                            <a href="#" class="text-black p-2"><span class="icon-facebook"></span></a>
                            <a href="#" class="text-black p-2"><span class="icon-twitter"></span></a>
                            <a href="#" class="text-black p-2"><span class="icon-linkedin"></span></a>
                        </p>
                    </div>

                </div>
            </div>

            <div class="col-md-6 col-lg-4 mb-5 mb-lg-5">
                <div class="team-member">

                    <img src="images/person_2.jpg" alt="Image" class="img-fluid rounded mb-4">

                    <div class="text">

                        <h2 class="mb-2 font-weight-light text-black h4">Brooke Cagle</h2>
                        <span class="d-block mb-3 text-white-opacity-05">Real Estate Agent</span>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis, cumque vitae voluptates
                            culpa earum
                            similique corrupti itaque veniam doloribus amet perspiciatis recusandae sequi nihil
                            tenetur ad, modi
                            quos id magni!</p>
                        <p>
                            <a href="#" class="text-black p-2"><span class="icon-facebook"></span></a>
                            <a href="#" class="text-black p-2"><span class="icon-twitter"></span></a>
                            <a href="#" class="text-black p-2"><span class="icon-linkedin"></span></a>
                        </p>
                    </div>

                </div>
            </div>

            <div class="col-md-6 col-lg-4 mb-5 mb-lg-5">
                <div class="team-member">

                    <img src="images/person_3.jpg" alt="Image" class="img-fluid rounded mb-4">

                    <div class="text">

                        <h2 class="mb-2 font-weight-light text-black h4">Philip Martin</h2>
                        <span class="d-block mb-3 text-white-opacity-05">Real Estate Agent</span>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maiores illo iusto, inventore,
                            iure dolorum
                            officiis modi repellat nobis, praesentium perspiciatis, explicabo. Atque cupiditate,
                            voluptates pariatur
                            odit officia libero veniam quo.</p>
                        <p>
                            <a href="#" class="text-black p-2"><span class="icon-facebook"></span></a>
                            <a href="#" class="text-black p-2"><span class="icon-twitter"></span></a>
                            <a href="#" class="text-black p-2"><span class="icon-linkedin"></span></a>
                        </p>
                    </div>

                </div>
            </div>












































































        </div>
    </div>
</div> -->
