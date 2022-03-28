<div class="app-wrapper">
    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">

            <!-- Inicio :: Titulo e Botões -->
            <div class="row g-3 align-items-center justify-content-between">

                <div class="col-auto">
                    <h1 class="app-page-title mb-0">Alterar Imóvel<i class="fa fa-question-circle" id="btnHelp"></i></h1>
                </div>

                <div class="col-auto">
                    <div class="page-utilities">
                        <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                            <div class="col-auto">
                                <a class="btn btn-secondary" href="<?= base_url("imovel"); ?>">Voltar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="mb-4">
            <!-- Fim :: Titulo e Botões -->

            <!-- Inicio :: Formulário -->
            <form id="form-imovel" method="POST" action="<?= base_url("imovel/update/{$imovel['uuid_imovel']}"); ?> " enctype=" multipart/form-data">

                <!-- Inicio :: Cadastro Básico -->
                <div class="card">
                    <div class="card-header fw-bold">Dados Básicos</div>
                    <div class="app-card shadow-sm p-4">
                        <div class="app-card-body">
                            <div class="row">
                                <input type="hidden" name="modal" value="0">
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label class="form-label">Imagem Destaque</label>
                                    <div class="row justify-content-between align-items-center d-none" id="containerPluginImagem">
                                        <div class="col">
                                            <div class="item-data">
                                                <input type="hidden" name="imagem">
                                                <input type="hidden" name="imagem_nome">
                                                <input type="file" id="imagemImovel" data-max-file-size="5MB" data-max-files="1">
                                            </div>
                                        </div>
                                    </div>
                                    <img src="<?= $imovel['imagem_destaque'] ?>" class="w-100" id="imagemEdicao" alt="Imagem de Destaque" height="290" style="object-fit:contain;">
                                    <div class="d-flex justify-content-center">
                                        <button type="button" class="btn-sm app-btn-secondary mt-3" data-action="alterarImagemDestaque">Alterar Imagem</button>
                                        <button type="button" class="btn-sm app-btn-secondary mt-3 d-none" data-action="cancelarAlterarImagemDestaque">Cancelar</button>
                                    </div>
                                </div>
                                <div class="col-md-9 col-lg-9 col-sm-12 mb-2">
                                    <div class="row">
                                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                            <label class="form-label">Código Referência</label>
                                            <input type="text" class="form-control" name="codigo_referencia" required value="<?= old('codigo_referencia', $imovel['codigo_referencia']); ?>" data-tippy-content="Informe o Código de  Referência">
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                            <label class="form-label">Categoria</label>
                                            <input type="text" class="form-control" name="codigo_categoria_imovel" required data-select="buscarCategoriaImovel" data-tippy-content="Selecione a Categoria" required value="<?= old('codigo_categoria_imovel', $imovel['codigo_categoria_imovel']); ?>">
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                            <label class="form-label">Tipo Imóvel</label>
                                            <input type="text" class="form-control" name="codigo_tipo_imovel" data-select="buscarTipoImovel" readonly data-tippy-content="Selecione o Tipo de Imóvel" required value="<?= old('codigo_tipo_imovel', $imovel['codigo_tipo_imovel']); ?>">
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                            <label class="form-label" for="condominio">Condomínio?</label>
                                            <select name="condominio" id="selectFormCondominio" type="text" class="form-control" data-tippy-content="Informe se é Condomínio" required value="<?= old('condominio', $imovel['condominio'] == true ? 't' : 'f'); ?>">
                                                <option value="f">Não</option>
                                                <option value="t">Sim</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2" id="quarto">
                                            <label class="form-label">Quarto</label>
                                            <input class="form-control" name="quarto" type="Number" data-tippy-content="Informe Quantos Quartos" required value="<?= old('quarto', $imovel['quarto']); ?>">
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2" id="banheiro">
                                            <label class="form-label">Banheiro</label>
                                            <input class="form-control" name="banheiro" type="Number" data-tippy-content="Informe Quantos Banheiros" required value="<?= old('banheiro', $imovel['banheiro']); ?>">
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2" id="suite">
                                            <label class="form-label">Suite</label>
                                            <input class="form-control" name="suite" type="Number" data-tippy-content="Informe Quantas Suites" value="<?= old('suite', $imovel['suite']); ?>">
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2" id="vaga">
                                            <label class="form-label">Vagas(s)</label>
                                            <input class="form-control" name="vaga" type="Number" data-tippy-content="Informe Quantas Vagas de Garagem" value="<?= old('vaga', $imovel['vaga']); ?>">
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2" id="area_construida">
                                            <label class="form-label">Área Construida</label>
                                            <input class="form-control" name="area_construida" type="Number" data-tippy-content="Informe a Área Construida em M²" value="<?= old('area_construida', $imovel['area_construida']); ?>">
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                            <label class="form-label">Área Total</label>
                                            <input class="form-control" name="area_total" type="Number" data-tippy-content="Informe a Área Útil em M²" required value="<?= old('area_total', $imovel['area_total']); ?>">
                                        </div>

                                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2" id="edicula_campo">
                                            <label class="form-label" for="edicula">Possui Edícula?</label>
                                            <select name="edicula" id="selectFormEdicula" type="text" class="form-control" data-tippy-content="Informe se Possui Edícula ou Não" <?= old('edicula'); ?>>
                                                <option value="f">Não</option>
                                                <option value="t">Sim</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2" id="valor_venda">
                                            <label class="form-label">Valor Venda</label>
                                            <input type="text" class="form-control" name="valor_venda" data-mask="dinheiro" required value="<?= old('valor_venda', intToReal($imovel['valor_venda'])); ?>" placeholder="0,00">
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2" id="valor_aluguel">
                                            <label class="form-label">Valor Aluguel</label>
                                            <input type="text" class="form-control" name="valor_aluguel" data-mask="dinheiro" required value="<?= old('valor_aluguel', intToReal($imovel['valor_aluguel'])); ?>" placeholder="0,00">
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                            <label class="form-label">Proprietário</label>
                                            <div class="input-group">
                                                <span class="input-group-text cursor" id="btnModalCadastrarProprietario" data-tippy-content="Cadastrar novo Proprietário" data-tippy-placement="bottom" data-bs-toggle="modal" data-bs-target="#modalCadastrarProprietario">
                                                    <i class="fas fa-user-plus"></i></span>

                                                <input type="text" class="form-control" name="codigo_proprietario" data-select="buscarProprietario" data-tippy-content="Selecione o Proprietário do Imóvel" value="<?= old('codigo_proprietario', $imovel['codigo_proprietario']); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-lg-12 col-sm-12 mb-4">
                                            <label class="form-label">Descrição</label>
                                            <textarea class="form-control" name="descricao" rows="2" data-tippy-content="Informe uma Descrição Para o Imóvel"><?= old('descricao', $imovel['descricao']); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fim :: Cadastro Básico -->

                <!-- Início :: imovel - endereço -->
                <div class="card mt-2">
                    <div class="card-header fw-bold">Endereço</div>
                    <div class="card-body">
                        <div class="form-group col-12">
                            <div class="row">
                                <div class="col-md-2 col-lg-2 col-sm-12 mb-2">
                                    <label class="form-label">CEP</label>
                                    <input type="text" class="form-control" name="cep" data-mask="cep" required value="<?= old('cep', $endereco['cep']); ?>">
                                </div>
                                <div class="col-md-5 col-lg-5 col-sm-12 mb-2">
                                    <label class="form-label">Rua</label>
                                    <input type="text" class="form-control" name="rua" required value="<?= old('rua', $endereco['rua']); ?>">
                                </div>
                                <div class="col-md-2 col-lg-2 col-sm-12 mb-2">
                                    <label class="form-label">Número</label>
                                    <input type="text" class="form-control" name="numero" data-verificaNumero="true" required value="<?= old('numero', $endereco['numero']); ?>">
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label class="form-label">Bairro</label>
                                    <input type="text" class="form-control" name="bairro" required value="<?= old('bairro', $endereco['bairro']); ?>">
                                </div>
                                <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                                    <label class="form-label">Complemento</label>
                                    <input type="text" class="form-control" name="complemento" value="<?= old('complemento', $endereco['complemento']); ?>">
                                </div>
                                <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                                    <label class="form-label">Cidade</label>
                                    <input type="hidden" name="cidade" required value="<?= old('cidade', $endereco['cidade']); ?>">
                                    <input type="hidden" name="uf" required value="<?= old('uf', $endereco['uf']); ?>">
                                    <input type="text" class="form-control" name="cidade_completa" readonly required value="<?= old('cidade', $endereco['cidade']); ?>/<?= old('uf', $endereco['uf']); ?>">
                                </div>
                                <div class="col-md-12 col-lg-12 col-sm-12 mb-4">
                                    <label class="form-label">Latitude</label>
                                    <input type="hidden" class="form-control" name="lat" value="<?= $endereco['lat'] ?>">
                                </div>
                                <div class="col-md-12 col-lg-12 col-sm-12 mb-4">
                                    <label class="form-label">Longitude</label>
                                    <input type="hidden" class="form-control" name="lng" value="<?= $endereco['lng'] ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fim :: imovel - endereço -->

                <!-- Inicio :: imovel - site -->
                <div class="card mt-2">
                    <div class="card-header fw-bold">Opções do site</div>
                    <div class="card-body">
                        <div class="form-group col-12">
                            <div class="row">
                                <div class="col-6"><label for="publicado">Publicar?</label><select name="publicado" id="selectFormPublicado" type="text" class="form-control" data-tippy-content="Informe se Deve ser Publicado ou Não" <?= old('publicado'); ?>>
                                        <option value="t">Sim</option>
                                        <option value="f">Não</option>
                                    </select></div>
                                <div class="col-6"><label for="destaque">Destaque ?</label><select name="destaque" id="selectFormDestaque" type="text" class="form-control" data-tippy-content="Informe se Será Exibido como Destaque" <?= old('destaque'); ?>>

                                        <option value="f">Não</option>
                                        <option value="t">Sim</option>
                                    </select></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fim :: imovel - site -->

                <!-- Inicio :: Cadastro Imagem -->
                <div class="card mt-4">
                    <div class="card-header fw-bold">Imagens do Imóvel</div>
                    <div class="app-card shadow-sm p-4">
                        <div class="app-card-body">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12 mb-2">
                                    <div class="row justify-content-between align-items-center">
                                        <div class="col" id="file-itens">
                                            <input type="file" id="imagensImovel" name="filepond[]" data-max-file-size="5MB" data-max-files="10" multiple>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <?php foreach ($imagemImovel as $key => $value) : ?>
                                        <div class="col-md-3 col-lg-3 col-sm-12 mt-3 offset-md-1 offset-lg-1 card justify-content-center" data-image="<?= $value['uuid_imagem_imovel'] ?>">
                                            <div class="row">
                                                <img src="<?= $value['diretorio_imagem'] ?>" class="mt-2" style="object-fit:contain;" width="223" height="291" id="<?= $value['uuid_imagem_imovel'] ?>" alt="Imagem do Imóvel">
                                            </div>
                                            <div class="d-flex justify-content-center mb-3">
                                                <button data-url="/imovel/desativarImagem/<?= $value['uuid_imagem_imovel'] ?>" type="button" class="btn-sm app-btn-secondary mt-3" data-tippy-content="Desativar" data-action="removerImagem" data-id="<?= $value['uuid_imagem_imovel'] ?>">Remover Imagem</button>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fim :: Cadastro  Imagem -->
                <div id="googleMap" style="width:100%;height:400px;">
                </div>
            </form>
            </form>
            <div class="d-flex justify-content-end mt-2">
                <button data-action='form-imovel-submit' class="btn app-btn-primary" style="z-index:1">Salvar</button>
            </div>
        </div>
    </div>
</div>


<!-- Fim :: Formulário -->

<script>
    function myMap() {
        const lat = '<?= !empty($endereco['lat']) ? $endereco['lat'] : '-25.0927465'; ?>';
        const lng = '<?= !empty($endereco['lng']) ? $endereco['lng'] : '-50.1707468'; ?>';

        var mapProp = {
            center: new google.maps.LatLng(lat, lng),
            zoom: 10,
        };

        var map = new google.maps.Map(document.getElementById("googleMap"), mapProp);
        // The marker, positioned at Uluru
        const marker = new google.maps.Marker({
            position: {
                lat: parseFloat(lat),
                lng: parseFloat(lng)
            },
            map: map,
            draggable: true,
        });

        marker.addListener("dragend", () => {
            localizacao = marker.getPosition().toJSON();
            $("[name='lat']").val(`${localizacao.lat}`);
            $("[name='lng']").val(`${localizacao.lng}`);
            console.log(localizacao);

        });
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA03uqmKd3hJg9KIfS3d8MH1pkW6TY-WH0&callback=myMap"></script>
