<div class="app-wrapper">
    <div class="app-content pt-3 p-md-3 p-lg-4">
        <!-- Inicio :: Titulo e Botões -->
        <div class="row g-3 align-items-center justify-content-between">

            <div class="col-auto">
                <h1 class="app-page-title mb-0">Devolução ao Fornecedor</h1>
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
        <form method="POST" action="<?= base_url('estoque/realizarDevolucao'); ?>">

            <!-- Inicio :: Cadastro Básico -->
            <div class="card">
                <div class="card-header fw-bold">Devolver Produto</div>
                <div class="app-card shadow-sm p-4">
                    <div class="app-card-body mb-4">
                        <div class="row">
                            <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                                <label>Empresa</label>
                                <input type="text" class="form-control" readonly data-select="buscarEmpresa" name="codigo_empresa" value="<?= $nativeSession->get('empresa')['codigo_empresa']; ?>" />
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                                <label>Estoque</label>
                                <input type="text" class="form-control" data-select="buscarEstoque" name="codigo_estoque" value="<?= old('codigo_estoque', $nativeSession->get('usuario')['codigo_estoque']); ?>" />
                            </div>
                            <div class="col-md-12 col-lg-12 col-sm-12 mb-4">
                                <label class="form-label">Observação</label>
                                <textarea class="form-control" name="observacao" rows="2"><?= old('observacao'); ?></textarea>
                            </div>
                        </div>

                        <div class="row mt-4 d-none" id="cardBuscarProduto">
                            <div class="col-md-12 col-lg-12 col-sm-12 mb-2">
                                <div class="card">
                                    <div class="card-header fw-bold">Buscar Produto</div>
                                    <div class="app-card shadow-sm p-2">
                                        <div class="app-card-body">
                                            <div class="row">
                                                <div class="col-md-12 col-lg-12 col-sm-12 mb-2 mt-2">
                                                    <input type="text" class="form-control" data-select="buscarProduto" name="codigo_produto" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row d-none" id="tableProdutos">
                            <div class="col-md-12 col-lg-12 col-sm-12 mb-2">
                                <div class="card">
                                    <div class="app-card shadow-sm p-4">
                                        <div class="app-card-body">
                                            <button type="button" class="btn btn-info float-end text-white" id="totalItens"></button>
                                            <table class="table table-striped table-hover" cellspacing="0">
                                                <thead>
                                                    <tr>
                                                        <th>Código</th>
                                                        <th>Nome</th>
                                                        <th>Estoque atual</th>
                                                        <th>Quantidade à Devolver</th>
                                                        <th>Opções</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
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
