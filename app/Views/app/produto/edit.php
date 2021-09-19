<div class="app-wrapper">
    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">

            <!-- Inicio :: Titulo e Botões -->
            <div class="row g-3 align-items-center justify-content-between">

                <div class="col-auto">
                    <h1 class="app-page-title mb-0">Alterar Produto</h1>
                </div>
                <div class="col-auto">
                    <div class="page-utilities">
                        <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                            <div class="col-auto">
                                <a class="btn btn-secondary" href="<?= base_url("produto"); ?>">Voltar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="mb-4">
            <!-- Fim :: Titulo e Botões -->

            <!-- Inicio :: Formulário -->
            <form method="POST" action="<?= base_url("produto/update/{$produto['uuid_produto']}"); ?>">

                <!-- Inicio :: Cadastro Básico -->
                <div class="card">
                    <div class="card-header fw-bold">Dados Básicos</div>
                    <div class="app-card shadow-sm p-4">
                        <div class="app-card-body">
                            <div class="row">
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label class="form-label">Código Interno/Barras</label>
                                    <div class="input-group flex-nowrap">
                                        <input type="text" class="form-control" name="codigo_barras" data-verificanumero="true" required value="<?= old('codigo_barras', $produto['codigo_barras']); ?>">
                                        <button class="btn btn-secondary" type="button" id="dropdownMenuCodigo" data-bs-toggle="dropdown" aria-expanded="false" data-tippy-content="Gere um código automaticamente">
                                            <i class="fas fa-sync"></i>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuCodigo">
                                            <li class="dropdown-item" data-action="gerarCodigo" data-tipo="EAN8"><small class="cursor">Gerar Código EAN9</small></li>
                                            <li class="dropdown-item" data-action="gerarCodigo" data-tipo="aleatorio"><small class="cursor">Gerar Código aleatório</small></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label class="form-label">Referência Fornecedor</label>
                                    <input type="text" class="form-control" name="referencia_fornecedor" value="<?= old('referencia_fornecedor', $produto['referencia_fornecedor']); ?>">
                                </div>
                                <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                                    <label class="form-label">Nome</label>
                                    <input type="text" class="form-control" name="nome" required value="<?= old('nome', $produto['nome']); ?>">
                                </div>
                                <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                                    <label class="form-label">Categorias</label>
                                    <input type="text" class="form-control" name="categorias" data-select="buscarCategoria" required value="<?= old('categorias', $produto['categorias']); ?>">
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label class="form-label">Fornecedor</label>
                                    <input type="text" class="form-control" name="codigo_fornecedor" data-select="buscarFornecedor" required value="<?= old('codigo_fornecedor', $produto['codigo_fornecedor']); ?>">
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label class="form-label">SKU</label>
                                    <input type="text" class="form-control" name="sku" value="<?= old('sku', $produto['sku']); ?>">
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label class="form-label">NCM</label>
                                    <input type="text" class="form-control" name="ncm" value="<?= old('ncm', $produto['ncm']); ?>">
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label class="form-label">CEST</label>
                                    <input type="text" class="form-control" name="cest" value="<?= old('cest', $produto['cest']); ?>">
                                </div>
                                <div class="col-md-12 col-lg-12 col-sm-12 mb-4">
                                    <label class="form-label">Descrição</label>
                                    <textarea class="form-control" name="descricao" rows="2"><?= old('descricao', $produto['descricao']); ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fim :: Cadastro Básico -->

                <!-- Inicio :: Cadastro Imagem -->
                <div class="card mt-4">
                    <div class="card-header fw-bold">Imagem do Produto</div>
                    <div class="app-card shadow-sm p-4">
                        <div class="app-card-body">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12 mb-2">
                                    <div class="row justify-content-between align-items-center">
                                        <div class="col">
                                            <div class="item-data">
                                                <input type="hidden" name="imagem">
                                                <input type="hidden" name="imagem_nome">
                                                <input type="file" id="imagemProduto" data-max-file-size="5MB" data-max-files="1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fim :: Cadastro  Imagem -->

                <div class="d-flex justify-content-end mt-2">
                    <button type="submit" class="btn app-btn-primary">Salvar</button>
                </div>

            </form>
            <!-- Fim :: Formulário -->


        </div>
    </div>
</div>
