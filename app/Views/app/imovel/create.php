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
                                    <label class="form-label">Imagem Capa</label>
                                    <div class="row justify-content-between align-items-center">
                                        <div class="col">
                                            <div class="item-data">
                                                <input type="hidden" name="imagem">
                                                <input type="hidden" name="imagem_nome">
                                                <input type="file" id="imagem_imovel" data-max-file-size="5MB" data-max-files="1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9 col-lg-9 col-sm-12 mb-2">
                                    <div class="row">
                                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                            <label class="form-label">Código Referência</label>
                                            <input type="text" class="form-control" name="codigo_referencia" value="<?= old('codigo_referencia'); ?>" data-tippy-content="Informe o Código de  Referência" >
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                                            <label class="form-label">Proprietário</label>
                                            <input type="text" class="form-control" name="codigo_proprietario" data-select="buscarProprietario" data-tippy-content="Selecione o Proprietário do Imóvel" required value="<?= old('codigo_proprietario'); ?>">
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                            <label class="form-label">Categoria</label>
                                            <input type="text" class="form-control" name="codigo_empresa_categoria" data-select="buscarCategoriaImovel" data-tippy-content="Selecione a Categoria" required value="<?= old('codigo_empresa_categoria'); ?>">
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                            <label class="form-label">Tipo Imóvel</label>
                                            <input type="text" class="form-control" name="codigo_tipo_imovel" data-select="buscarTipoImovel" data-tippy-content="Selecione o Tipo de Imóvel" required value="<?= old('codigo_tipo_imóvel'); ?>">
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

                <!-- Inicio :: imovel - imagem imovel -->

                <div class="card">
                    <div class="card-header fw-bold">Imagens do imovel</div>
                    <div class="card-body">
                        <div class="form-group col-12">
                            <div class="row justify-content-between align-items-center">
                                <div class="col">
                                    <input type="file" id="imagens_imovel" name="filepond[]" data-max-file-size="5MB" data-max-files="10" multiple>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fim :: imovel - imagem imovel -->


                <!-- Inicio :: Cadastro Imagem -->
                <!-- <div class="card mt-4">
                    <div class="card-header fw-bold">Imagens do imovel</div>
                    <div class="app-card shadow-sm p-4">
                        <div class="app-card-body">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12 mb-2">
                                    <div class="row justify-content-between align-items-center">
                                        <div class="col">
                                            <div class="item-data">
                                                <input type="hidden" name="imagem">
                                                <input type="hidden" name="imagem_nome">
                                                <input type="file" id="imagemimovel" data-max-file-size="5MB" data-max-files="1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
                <!-- Fim :: Cadastro  Imagem -->

                <div class="d-flex justify-content-end mt-2">
                    <button type="submit" class="btn app-btn-primary">Salvar</button>
                </div>

            </form>
            <!-- Fim :: Formulário -->


        </div>
    </div>
</div>
