<div class="app-wrapper">
    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">

            <!-- Inicio :: Titulo e Botões -->
            <div class="row g-3 align-items-center justify-content-between">

                <div class="col-auto">
                    <h1 class="app-page-title mb-0">Adicionar Imóvel</h1>
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
            <form method="POST" action="<?= base_url('imovel/store'); ?> " enctype=" multipart/form-data">

                <!-- Inicio :: Cadastro Básico -->
                <div class="card">
                    <div class="card-header fw-bold">Dados Básicos</div>
                    <div class="app-card shadow-sm p-4">
                        <div class="app-card-body">
                            <div class="row">
                                <input type="hidden" name="modal" value="0">
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label class="form-label">Imagem Destaque</label>
                                    <div class="row justify-content-between align-items-center">
                                        <div class="col">
                                            <div class="item-data">
                                                <input type="hidden" name="imagem">
                                                <input type="hidden" name="imagem_nome">
                                                <input type="file" id="imagemImovel" data-max-file-size="5MB" data-max-files="1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9 col-lg-9 col-sm-12 mb-2">
                                    <div class="row">
                                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                            <label class="form-label">Código Referência</label>
                                            <input type="text" class="form-control" name="codigo_referencia" required value="<?= old('codigo_referencia'); ?>" data-tippy-content="Informe o Código de  Referência">
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                            <label class="form-label">Categoria</label>
                                            <input type="text" class="form-control" name="codigo_categoria_imovel" required data-select="buscarCategoriaImovel" data-tippy-content="Selecione a Categoria" required value="<?= old('codigo_categoria_imovel'); ?>">
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                            <label class="form-label">Tipo Imóvel</label>
                                            <input type="text" class="form-control" name="codigo_tipo_imovel" data-select="buscarTipoImovel" data-tippy-content="Selecione o Tipo de Imóvel" required value="<?= old('codigo_tipo_imovel'); ?>">
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                            <label class="form-label">Quarto(s)</label>
                                            <input class="form-control" name="quarto" type="Number" data-tippy-content="Informe Quantos Quartos" required value="<?= old('quarto'); ?>">
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                            <label class="form-label">Banheiro(s)</label>
                                            <input class="form-control" name="banheiro" type="Number" data-tippy-content="Informe Quantos Banheiros" required value="<?= old('banheiro'); ?>">
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                            <label class="form-label">Suite(s)</label>
                                            <input class="form-control" name="suite" type="Number" data-tippy-content="Informe Quantas Suites" value="<?= old('suite'); ?>">
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                            <label class="form-label">Vagas(s)</label>
                                            <input class="form-control" name="vaga" type="Number" data-tippy-content="Informe Quantas Vagas de Garagem" value="<?= old('vaga'); ?>">
                                        </div>

                                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                            <label class="form-label">Área Construida</label>
                                            <input class="form-control" name="area_construida" type="Number" data-tippy-content="Informe a Área Construida em M²" value="<?= old('area_construida'); ?>">
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                            <label class="form-label">Área Total</label>
                                            <input class="form-control" name="area_total" type="Number" data-tippy-content="Informe a Área Útil em M²" value="<?= old('area_total'); ?>">
                                        </div>

                                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                            <label class="form-label" for="publicar">Possui Edícula?</label>
                                            <select name="edicula" id="edicula" type="text" class="form-control" data-tippy-content="Informe se Possui Edícula ou Não">
                                                <option value="f">Não</option>
                                                <option value="t">Sim</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                            <label class="form-label">Valor</label>
                                            <input type="text" class="form-control" name="valor" data-mask="dinheiro" required value="<?= old('valor'); ?>" placeholder="0,00">
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                                            <label class="form-label">Proprietário</label>
                                            <div class="input-group">
                                                <span class="input-group-text cursor" id="btnModalCadastrarProprietario" data-tippy-content="Cadastrar novo Proprietário" data-tippy-placement="bottom" data-bs-toggle="modal" data-bs-target="#modalCadastrarProprietario">
                                                    <i class="fas fa-user-plus"></i></span>

                                                <input type="text" class="form-control" name="codigo_proprietario" data-select="buscarProprietario" data-tippy-content="Selecione o Proprietário do Imóvel" value="<?= old('codigo_proprietario'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-lg-12 col-sm-12 mb-4">
                                            <label class="form-label">Descrição</label>
                                            <textarea class="form-control" name="descricao" rows="2" data-tippy-content="Informe uma Descrição Para o Imóvel"><?= old('descricao'); ?></textarea>
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
                                    <input type="text" class="form-control" name="cep" data-mask="cep" required value="<?= old('cep'); ?>">
                                </div>
                                <div class="col-md-5 col-lg-5 col-sm-12 mb-2">
                                    <label class="form-label">Rua</label>
                                    <input type="text" class="form-control" name="rua" readonly required value="<?= old('rua'); ?>">
                                </div>
                                <div class="col-md-2 col-lg-2 col-sm-12 mb-2">
                                    <label class="form-label">Número</label>
                                    <input type="text" class="form-control" name="numero" data-verificaNumero="true" required value="<?= old('numero'); ?>">
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label class="form-label">Bairro</label>
                                    <input type="text" class="form-control" name="bairro" readonly required value="<?= old('bairro'); ?>">
                                </div>
                                <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                                    <label class="form-label">Complemento</label>
                                    <input type="text" class="form-control" name="complemento" value="<?= old('complemento'); ?>">
                                </div>
                                <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                                    <label class="form-label">Cidade</label>
                                    <input type="hidden" name="cidade" required value="<?= old('cidade'); ?>">
                                    <input type="hidden" name="uf" required value="<?= old('uf'); ?>">
                                    <input type="text" class="form-control" name="cidade_completa" readonly required value="<?= old('cidade'); ?>/<?= old('uf'); ?>">
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
                                <div class="col-6"><label for="publicado">Publicar?</label><select name="publicado" id="publicado" type="text" class="form-control" data-tippy-content="Informe se Deve ser Publicado ou Não">
                                        <option value="t">Sim</option>
                                        <option value="f">Não</option>
                                    </select></div>
                                <div class="col-6"><label for="destaque">Destaque?</label><select name="destaque" id="destaque" type="text" class="form-control" data-tippy-content="Informe se Será Exibido como Destaque">
                                        <option value="f">Não</option>





                                        <option value="t">Sim</option>


                                    </select></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fim :: imovel - site -->

                <!-- Inicio :: imovel - imagem imovel -->

                <div class="card mt-2">
                    <div class="card-header fw-bold">Imagens do imóvel</div>
                    <div class="card-body">
                        <div class="form-group col-12">
                            <div class="row justify-content-between align-items-center">
                                <div class="col">
                                    <input type="file" id="imagensImovel" name="filepond[]" data-max-file-size="5MB" data-max-files="10" multiple>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fim :: imovel - imagem imovel -->

                <div class="d-flex justify-content-end mt-2">
                    <button type="submit" class="btn app-btn-primary">Salvar</button>
                </div>

            </form>
            <!-- Fim :: Formulário -->


        </div>
    </div>
</div>
