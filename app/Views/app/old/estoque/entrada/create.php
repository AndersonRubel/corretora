<div class="app-wrapper">
    <div class="app-content pt-3 p-md-3 p-lg-4">
        <!-- Inicio :: Titulo e Botões -->
        <div class="row g-3 align-items-center justify-content-between">

            <div class="col-auto">
                <h1 class="app-page-title mb-0">Adicionar Estoque</h1>
            </div>

            <div class="col-auto">
                <div class="page-utilities">
                    <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                        <div class="col-auto">
                            <a class="btn btn-secondary" href="<?= base_url("estoque"); ?>">Voltar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="mb-4">
        <!-- Fim :: Titulo e Botões -->

        <!-- Inicio :: Formulário -->
        <form method="POST" action="<?= base_url('estoque/realizarEntrada'); ?>">

            <!-- Inicio :: Cadastro Básico -->
            <div class="card">
                <div class="card-header fw-bold">Adicionar Produto</div>
                <div class="app-card shadow-sm p-4">
                    <div class="app-card-body">
                        <div class="row">
                            <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                                <label>Empresa</label>
                                <input type="text" class="form-control" readonly data-select="buscarEmpresa" name="codigo_empresa" value="<?= $nativeSession->get('empresa')['codigo_empresa']; ?>" />
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                                <label>Estoque</label>
                                <input type="text" class="form-control" data-select="buscarEstoque" name="codigo_estoque" value="<?= old('codigo_estoque'); ?>" />
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-12 mb-2 mt-2">
                                <label>Buscar Produto</label>
                                <input type="text" class="form-control" data-select="buscarProduto" name="codigo_produto" value="<?= old('codigo_produto'); ?>" />
                            </div>
                            <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                <label class="form-label">Quantidade a ser adicionada (em unidade)</label>
                                <input type="text" class="form-control" name="nova_quantidade" data-verificanumero="true" min="0" required value="<?= old('nova_quantidade'); ?>">
                            </div>
                            <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                <label class="form-label">Quantidade de Atacado</label>
                                <input type="text" class="form-control" name="quantidade_atacado" data-verificanumero="true" value="<?= old('quantidade_atacado'); ?>">
                            </div>
                            <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                <label class="form-label">Preço de Custo</label>
                                <input type="text" class="form-control" name="valor_fabrica" data-mask="dinheiro" required value="<?= old('valor_fabrica'); ?>" placeholder="0,00">
                            </div>
                            <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                <label class="form-label">Preço de Venda</label>
                                <input type="text" class="form-control" name="valor_venda" data-mask="dinheiro" required value="<?= old('valor_venda'); ?>" placeholder="0,00">
                            </div>
                            <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                <label class="form-label">Preço de Ecommerce</label>
                                <input type="text" class="form-control" name="valor_ecommerce" data-mask="dinheiro" value="<?= old('valor_ecommerce'); ?>" placeholder="0,00">
                            </div>
                            <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                <label class="form-label">Preço de Atacado</label>
                                <input type="text" class="form-control" name="valor_atacado" data-mask="dinheiro" value="<?= old('valor_atacado'); ?>" placeholder="0,00">
                            </div>
                            <div class="col-md-12 col-lg-12 col-sm-12 mb-4">
                                <label class="form-label">Observação</label>
                                <textarea class="form-control" name="observacao" rows="2"><?= old('observacao'); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fim :: Cadastro Básico -->

            <div class="d-flex justify-content-end mt-2">
                <button type="submit" class="btn app-btn-primary">Salvar</button>
            </div>

        </form>
        <!-- Fim :: Formulário -->


    </div>
</div>
