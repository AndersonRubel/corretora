<div class="app-wrapper" id="estoque">
    <div class="app-content pt-3 p-md-3 p-lg-4">

        <!-- Inicio :: Titulo e Botões -->
        <div class="row g-3 align-items-center justify-content-between">

            <div class="col-auto">
                <h1 class="app-page-title mb-0">Estoque</h1>
            </div>

            <div class="col-auto">
                <div class="page-utilities">
                    <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                        <div class="col-auto">
                            <a class="btn btn-primary success text-white" href="<?= base_url("estoque/adicionar"); ?>">Adicionar</a>
                            <a class="btn btn-primary success text-white" href="<?= base_url("estoque/baixar"); ?>">Baixar</a>
                            <a class="btn btn-primary success text-white" href="<?= base_url("estoque/transferir"); ?>">Transferir</a>
                            <a class="btn btn-primary success text-white" href="<?= base_url("estoque/devolver"); ?>">Devolução Fornecedor</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="mb-4">
        <!-- Fim :: Titulo e Botões -->

        <!-- Inicio :: Filtros do Estoque -->
        <div class="card mb-4">
            <div class="card-header fw-bold">Filtros</div>
            <div class="app-card shadow-sm p-4">
                <div class="app-card-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-3" data-filtro="filtro_estoque">
                            <label>Estoque</label>
                            <input type="text" class="form-control" data-select="buscarEstoque" name="codigo_estoque" value="<?= $nativeSession->get('usuario')['codigo_estoque']; ?>" />
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-3" data-filtro="filtro_fornecedor">
                            <label>Fornecedor</label>
                            <input type="text" class="form-control" data-select="buscarFornecedor" name="codigo_fornecedor" />
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-3" data-filtro="filtro_produto">
                            <label>Produto</label>
                            <input type="text" class="form-control" data-select="buscarProduto" name="codigo_produto" />
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-3">
                            <label>Exibir Produtos em Estoque</label>
                            <select class="form-control" name="exibir_produtos">
                                <option value="1">Todos</option>
                                <option value="2">Negativos</option>
                                <option value="3">Positivos</option>
                                <option value="4">Zerados</option>
                            </select>
                        </div>

                        <!-- Inicio :: Botão Filtrar e Limpar -->
                        <div class="col mt-1 d-flex align-items-end justify-content-end">
                            <button type="button" class="btn btn-danger text-white mx-1" data-action="btnLimpar">Limpar</button>
                            <button type="submit" class="btn btn-success text-white" data-action="btnFiltrar">Filtrar</button>
                        </div>
                        <!-- Fim :: Botão Filtrar e Limpar -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Fim :: Filtros do Estoque -->

        <!-- Inicio :: Listagem do Estoque -->
        <div class="card mb-4">
            <div class="card-header fw-bold">Estoque</div>
            <div class="app-card shadow-sm">
                <div class="app-card-body">
                    <!-- <nav class="orders-table-tab app-nav-tabs nav shadow-sm flex-column flex-sm-row mb-4">
                        <a class="flex-sm-fill text-center nav-link active" id="ativos-tab" data-bs-toggle="tab" href="#ativos" role="tab" aria-controls="ativos" aria-selected="true">
                            Lista
                            <span class="count-tableLista">(0)</span>
                        </a>
                        <a class="flex-sm-fill text-center nav-link" id="inativos-tab" data-bs-toggle="tab" href="#inativos" role="tab" aria-controls="inativos" aria-selected="false">
                            Grade
                            <span class="count-tableGrade">(0)</span>
                        </a>
                    </nav> -->

                    <div class="tab-content">

                        <!-- Inicio :: Registros Lista -->
                        <div class="tab-pane fade show active" id="ativos" role="tabpanel" aria-labelledby="ativos-tab">
                            <div class="app-card app-card-orders-table shadow-sm">
                                <div class="app-card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped mb-0 text-left" id="tableLista"></table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fim :: Registros Lista -->

                        <!-- Inicio :: Registros Grade -->
                        <div class="tab-pane fade" id="inativos" role="tabpanel" aria-labelledby="inativos-tab">
                            <div class="app-card app-card-orders-table">
                                <div class="app-card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped mb-0 text-left" id="tableGrade"></table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fim :: Registros Grade -->

                    </div>
                </div>
            </div>
        </div>
        <!-- Fim :: Listagem do Estoque -->

    </div>
</div>
