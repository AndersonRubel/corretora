<div class="app-wrapper">
    <div class="app-content pt-3 p-md-3 p-lg-4">

        <!-- Inicio :: Titulo e Botões -->
        <div class="row g-3 align-items-center justify-content-between">

            <div class="col-auto">
                <h1 class="app-page-title mb-0">Faturamento</h1>
            </div>

            <div class="col-auto">
                <div class="page-utilities">
                    <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                        <div class="col-auto">
                            <a class="btn btn-primary success text-white" href="<?= base_url("faturamento/adicionar"); ?>">Adicionar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="mb-4">
        <!-- Fim :: Titulo e Botões -->

        <!-- Inicio :: Filtros -->
        <div class="row mt-2">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header fw-bold">Filtros</div>
                    <div class="app-card shadow-sm p-4">
                        <div class="app-card-body">
                            <div class="row">

                                <div class="col-sm-12 col-md-6 col-lg-2" data-filtro="filtro_periodoInicio">
                                    <label>Período Início</label>
                                    <input type="date" class="form-control" name="periodo_inicio" value="<?= date('Y-m-01'); ?>" />
                                </div>

                                <div class="col-sm-12 col-md-6 col-lg-2" data-filtro="filtro_periodoFim">
                                    <label>Período Fim</label>
                                    <input type="date" class="form-control" name="periodo_fim" value="<?= date('Y-m-t'); ?>" />
                                </div>

                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2" data-filtro="filtro_empresa">
                                    <label>Empresa</label>
                                    <input type="text" class="form-control" data-select="buscarEmpresa" name="codigo_empresa" />
                                </div>

                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2" data-filtro="filtro_vendedor">
                                    <label>Vendedor</label>
                                    <input type="text" class="form-control" data-select="buscarVendedor" name="codigo_vendedor" />
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
        </div>
        <!-- Fim :: Filtros do Fluxo -->


        <!-- Inicio :: Tabela -->
        <div class="row mt-1">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <div class="card mb-4">
                    <div class="card-header fw-bold">Faturamentos</div>
                    <div class="app-card shadow-sm p-0">
                        <div class="app-card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped table-sm" id="tableAtivos"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fim :: Tabela -->

    </div>

</div>
