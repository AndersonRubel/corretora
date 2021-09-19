<div class="app-wrapper" id="venda">
    <div class="app-content pt-3 p-md-3 p-lg-4">

        <!-- Inicio :: Titulo e Botões -->
        <div class="row g-3 align-items-center justify-content-between">

            <div class="col-auto">
                <h1 class="app-page-title mb-0">Vendas</h1>
            </div>

            <div class="col-auto">
                <div class="page-utilities">
                    <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                        <div class="col-auto">
                            <a class="btn btn-primary success text-white" href="<?= base_url("pdv"); ?>">Nova Venda</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="mb-4">
        <!-- Fim :: Titulo e Botões -->

        <!-- Inicio :: Filtros de Venda-->
        <div class="card mb-4">
            <div class="card-header fw-bold">Filtros</div>
            <div class="app-card shadow-sm p-4">
                <div class="app-card-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-2 col-lg-2" data-filtro="filtro_dataInicio">
                            <label>Data Início</label>
                            <input type="date" class="form-control" name="data_inicio" value="<?= date('Y-m-d', strtotime("-15 days")); ?>" />
                        </div>

                        <div class="col-sm-12 col-md-2 col-lg-2" data-filtro="filtro_dataFim">
                            <label>Data Fim</label>
                            <input type="date" class="form-control" name="data_fim" value="<?= date('Y-m-d'); ?>" />
                        </div>

                        <div class="col-sm-12 col-md-2 col-lg-2" data-filtro="filtro_vendedor">
                            <label>Vendedor</label>
                            <input type="text" class="form-control" data-select="buscarVendedor" name="codigo_vendedor" />
                        </div>

                        <div class="col-sm-12 col-md-3 col-lg-3" data-filtro="filtro_cliente">
                            <label>Cliente</label>
                            <input type="text" class="form-control" data-select="buscarCliente" name="codigo_cliente" />
                        </div>

                        <div class="col-sm-12 col-md-3 col-lg-3" data-filtro="filtro_cadastroMetodoPagamento">
                            <label>Método de Pagamento</label>
                            <input type="text" class="form-control" data-select="buscarCadastroMetodoPagamento" name="codigo_cadastro_metodo_pagamento" />
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
        <!-- Fim :: Filtros de Venda-->
        <nav class="orders-table-tab app-nav-tabs nav shadow-sm flex-column flex-sm-row mb-4">
            <a class="flex-sm-fill text-center nav-link active" id="realizada-tab" data-bs-toggle="tab" href="#realizada" role="tab" aria-controls="realizada" aria-selected="true">
                Realizadas
                <span class="count-tableRealizadas">(0)</span>
            </a>
            <a class="flex-sm-fill text-center nav-link" id="estornada-tab" data-bs-toggle="tab" href="#estornada" role="tab" aria-controls="estornada" aria-selected="false">
                Estornadas
                <span class="count-tableEstornadas">(0)</span>
            </a>
        </nav>
        <div class="card mb-4">
            <div class="card-header fw-bold">Vendas</div>
            <div class="tab-content">
                <!-- Inicio :: Vendas Ativas -->
                <div class="tab-pane fade show active" id="realizada" role="tabpanel" aria-labelledby="realizada-tab">
                    <div class="app-card app-card-orders-table shadow-sm mb-5">
                        <div class="app-card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0 text-left" id="tableRealizadas"></table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fim :: Vendas Ativas -->

                <!-- Inicio :: Vendas Estornadas -->
                <div class="tab-pane fade" id="estornada" role="tabpanel" aria-labelledby="estornada-tab">
                    <div class="app-card app-card-orders-table mb-5">
                        <div class="app-card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0 text-left" id="tableEstornadas"></table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fim :: Vendas Estornadas -->

            </div>
        </div>
    </div>
</div>
