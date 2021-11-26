<div class="slide-one-item home-slider owl-carousel">

    <div class="site-blocks-cover overlay" style="background-image: url(assets/site/images/hero_bg_1.jpg);"
        data-aos="fade" data-stellar-background-ratio="0.5">
    </div>

</div>


<div class="site-section site-section-sm pb-0">
    <div class="container">
        <div class="row">
            <form class="form-search col-md-12" style="margin-top: -650px; margin-bottom: 450px;">
                <div class="row  align-items-end">

                    <div class="col-md-3">
                        <label for="list-types">Tipos de Imóvel</label>
                        <div class="select-wrap">
                            <span class="icon icon-arrow_drop_down"></span>
                            <select name="list-types" id="list-types" class="form-control d-block rounded-0">
                                <option value="">Condo</option>
                                <option value="">Commercial Building</option>
                                <option value="">Land Property</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="offer-types">Tipos Ofertados</label>
                        <div class="select-wrap">
                            <span class="icon icon-arrow_drop_down"></span>
                            <select name="offer-types" id="offer-types" class="form-control d-block rounded-0">
                                <option value="">For Sale</option>
                                <option value="">For Rent</option>
                                <option value="">For Lease</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="select-city">Selecione a Cidade</label>
                        <div class="select-wrap">
                            <span class="icon icon-arrow_drop_down"></span>
                            <select name="select-city" id="select-city" class="form-control d-block rounded-0">
                                <option value="">New York</option>
                                <option value="">Brooklyn</option>
                                <option value="">London</option>
                                <option value="">Japan</option>
                                <option value="">Philippines</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <input type="submit" class="btn btn-success text-white btn-block rounded-0" value="Search">
                    </div>
                </div>
            </form>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="view-options bg-white py-3 px-3 d-md-flex align-items-center">
                    <div class="mr-auto">
                        <a href="index.html" class="icon-view view-module active"><span
                                class="icon-view_module"></span></a>
                        <a href="view-list.html" class="icon-view view-list"><span class="icon-view_list"></span></a>

                    </div>
                    <div class="ml-auto d-flex align-items-center">
                        <div>
                            <a href="#" class="view-list px-3 border-right active">All</a>
                            <a href="#" class="view-list px-3 border-right">Rent</a>
                            <a href="#" class="view-list px-3">Sale</a>
                        </div>


                        <div class="select-wrap">
                            <span class="icon icon-arrow_drop_down"></span>
                            <select class="form-control form-control-sm d-block rounded-0">
                                <option value="">Sort by</option>
                                <option value="">Price Ascending</option>
                                <option value="">Price Descending</option>
                            </select>
                        </div>
                    </div>
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
                        <div class="row ">
                            <img src="<?= $value['imagem_destaque'] ?>" alt="Image"
                                style="object-fit:cover; width:400px;height:200px !important">
                        </div>
                    </a>
                    <div class="p-4 property-body">
                        <a href="#" class="property-favorite"><span class="icon-heart-o"></span></a>
                        <h2 class="property-title"><a href="property-details.html"><?= $value['tipo_imovel'] ?></a></h2>
                        <span class="property-location d-block mb-3"><span
                                class="property-icon icon-room"></span><?= $value['endereco'] ?></span>
                        <strong class="property-price text-primary mb-3 d-block text-success"
                            data-mask="dinheiro">R$<?= intToreal($value['valor']) ?></strong>
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

<div class="site-section">
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
</div>