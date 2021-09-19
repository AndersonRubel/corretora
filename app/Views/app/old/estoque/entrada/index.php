<div class="app-wrapper" id="entradas">
    <div class="app-content pt-3 p-md-3 p-lg-4">

        <!-- Inicio :: Titulo e Botões -->
        <div class="row g-3 align-items-center justify-content-between">

            <div class="col-auto">
                <h1 class="app-page-title mb-0">Estoque - Ver Entradas</h1>
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

        <!-- Inicio :: Filtros do Estoque -->
        <div class="card mb-4">
            <div class="card-header fw-bold">Filtros</div>
            <div class="app-card shadow-sm p-4">
                <div class="app-card-body">
                    <div class="row">

                        <div class="col-sm-12 col-md-6 col-lg-3" data-filtro="filtro_dataInicio">
                            <label>Data Início</label>
                            <input type="date" class="form-control" name="data_inicio" value="<?= date('Y-m-d', strtotime("-15 days")); ?>" />
                        </div>

                        <div class=" col-sm-12 col-md-6 col-lg-3" data-filtro="filtro_dataFim">
                            <label>Data Fim</label>
                            <input type="date" class="form-control" name="data_fim" value="<?= date('Y-m-d'); ?>" />
                        </div>

                        <div class=" col-sm-12 col-md-6 col-lg-3" data-filtro="filtro_estoque">
                            <label>Estoque</label>
                            <input type="text" class="form-control" data-select="buscarEstoque" name="codigo_estoque" value="<?= $nativeSession->get('usuario')['codigo_estoque']; ?>" />
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-3" data-filtro="filtro_usuario">
                            <label>Solicitante</label>
                            <input type="text" class="form-control" data-select="buscarUsuario" name="codigo_usuario" />
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-3" data-filtro="filtro_fornecedor">
                            <label>Fornecedor</label>
                            <input type="text" class="form-control" data-select="buscarFornecedor" name="codigo_fornecedor" />
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-3" data-filtro="filtro_empresaCategoria">
                            <label>Categoria</label>
                            <input type="text" class="form-control" data-select="buscarEmpresaCategoria" name="codigo_empresa_categoria" />
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-3" data-filtro="filtro_produto">
                            <label>Produto</label>
                            <input type="text" class="form-control" data-select="buscarProduto" name="codigo_produto" />
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
            <div class="card-header fw-bold">Entradas de Estoque</div>
            <div class="app-card shadow-sm">
                <div class="app-card-body">
                    <div class="app-card app-card-orders-table shadow-sm">
                        <div class="app-card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0 text-left" id="tableVerEntradas"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fim :: Listagem do Estoque -->

    </div>
</div>
