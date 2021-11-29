<div class="app-wrapper" id="imovel">
    <div class="app-content pt-3 p-md-3 p-lg-4">

        <!-- Inicio :: Titulo e Botões -->
        <div class="row g-3 align-items-center justify-content-between">

            <div class="col-auto">
                <h1 class="app-page-title mb-0">Imóvel</h1>
            </div>

            <div class="col-auto">
                <div class="page-utilities">
                    <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                        <div class="col-auto">

                            <a class="btn btn-primary success text-white" href="<?= base_url("imovel/adicionar"); ?>">Adicionar</a>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="mb-4">
        <!-- Fim :: Titulo e Botões -->

        <!-- Inicio :: Filtros de imovel -->
        <div class="card mb-4">
            <div class="card-header fw-bold change-icon" data-bs-toggle="collapse" data-bs-target="#collapseFiltros" aria-expanded="false" aria-controls="collapseFiltros">
                Filtros
            </div>
            <div class="collapse" id="collapseFiltros">
                <div class="app-card shadow-sm p-4">
                    <div class="app-card-body">
                        <div class="row">

                            <div class=" col-sm-12 col-md-3 col-lg-3" data-filtro="filtro_imovel">
                                <label>Imóvel</label>
                                <input type="text" class="form-control" data-select="buscarImovel" name="codigo_imovel" data-tippy-content="Selecione o Imóvel" />
                            </div>
                            <div class=" col-sm-12 col-md-3 col-lg-3" data-filtro="filtro_tipo_imovel">
                                <label>Tipo Imóvel</label>
                                <input type="text" class="form-control" data-select="buscarTipoImovel" name="codigo_tipo_imovel" data-tippy-content="Selecione o Tipo Imóvel" />
                            </div>
                            <div class=" col-sm-12 col-md-3 col-lg-3" data-filtro="filtro_categoria_imovel">
                                <label>Categoria Imóvel</label>
                                <input type="text" class="form-control" data-select="buscarCategoriaImovel" name="codigo_categoria_imovel" data-tippy-content="Selecione a Categoria  Imóvel" />
                            </div>

                            <div class=" col-sm-12 col-md-3 col-lg-3" data-filtro="filtro_propriteario">
                                <label>Proprietário</label>
                                <input type="text" class="form-control" data-select="buscarProprietario" name="categorias" data-tippy-content="Selecione ao cliente" />
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
        </div>
        <!-- Fim :: Filtros de imovel -->

        <nav class="orders-table-tab app-nav-tabs nav shadow-sm flex-column flex-sm-row mb-4">
            <a class="flex-sm-fill text-center nav-link active" id="ativos-tab" data-bs-toggle="tab" href="#ativos" role="tab" aria-controls="ativos" aria-selected="true">
                Ativos
                <span class="count-tableAtivos">(0)</span>
            </a>
            <a class="flex-sm-fill text-center nav-link" id="inativos-tab" data-bs-toggle="tab" href="#inativos" role="tab" aria-controls="inativos" aria-selected="false">
                Inativos
                <span class="count-tableInativos">(0)</span>
            </a>
        </nav>
        <div class="card mb-4">
            <div class="card-header fw-bold">imovels</div>
            <div class="tab-content">

                <!-- Inicio :: Registros Ativos -->
                <div class="tab-pane fade show active" id="ativos" role="tabpanel" aria-labelledby="ativos-tab">
                    <div class="app-card app-card-orders-table shadow-sm mb-5">
                        <div class="app-card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0 text-left" id="tableAtivos"></table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fim :: Registros Ativos -->

                <!-- Inicio :: Registros Inativos -->
                <div class="tab-pane fade" id="inativos" role="tabpanel" aria-labelledby="inativos-tab">
                    <div class="app-card app-card-orders-table mb-5">
                        <div class="app-card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0 text-left" id="tableInativos">
                                </table>

                            </div>




                        </div>
                    </div>
                </div>
                <!-- Fim :: Registros Inativos -->

            </div>
        </div>
    </div>
</div>
