<div class="app-wrapper" id="financeiro-fluxo">
    <div class="app-content pt-3 p-md-3 p-lg-4">

        <!-- Inicio :: Titulo e Botões -->
        <div class="row g-3 align-items-center justify-content-between">

            <div class="col-auto">
                <h1 class="app-page-title mb-0">Financeiro</h1>
            </div>

            <div class="col-auto">
                <div class="page-utilities">
                    <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                        <div class="col-auto">
                            <a class="btn btn-primary success text-white" href="<?= base_url("financeiro/adicionar"); ?>">Cadastrar Fluxo Manual</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="mb-4">
        <!-- Fim :: Titulo e Botões -->

        <!-- Inicio :: Cabeçalho -->
        <div class="row">

            <div class="col-md-8 col-lg-8 col-sm-12 align-self-end">
                <nav class="orders-table-tab app-nav-tabs nav shadow-sm flex-column flex-sm-row mb-4">
                    <a class="flex-sm-fill text-center nav-link active" id="resumo-tab" data-bs-toggle="tab" href="#resumo" role="tab" aria-controls="resumo" aria-selected="true">
                        Resumo Financeiro
                    </a>
                    <a class="flex-sm-fill text-center nav-link" id="fluxo-tab" data-bs-toggle="tab" href="#fluxo" role="tab" aria-controls="fluxo" aria-selected="false">
                        Fluxo de Caixa
                    </a>
                </nav>
            </div>

            <!-- Inicio :: Sumario do Fluxo -->
            <div class="col-sm-12 col-md-4 col-lg-4" id="sumario">
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="d-flex mb-1" id="selectorReceitaPaga" data-tippy-content="Total de Receitas Pagas (Utiliza os filtros para somar)">
                            <div class="col text-end">
                                <label><small>Receitas</small></label>
                            </div>
                            <div class="col">
                                <input class="form-control mx-1" placeholder="R$ 0,00" disabled>
                            </div>
                        </div>
                        <div class="d-flex mb-1" id="selectorDespesaPaga" data-tippy-content="Total de Despesas Pagas (Utiliza os filtros para somar)">
                            <div class="col text-end">
                                <label><small>Despesas</small></label>
                            </div>
                            <div class="col">
                                <input class="form-control mx-1" placeholder="R$ 0,00" disabled>
                            </div>
                        </div>
                        <div class="d-flex mb-1" id="selectorBalancoReceitaDespesa" data-tippy-content="Balanço entre Receitas e Despesas (Utiliza os filtros para somar)">
                            <div class="col text-end">
                                <label><small>Balanço</small></label>
                            </div>
                            <div class="col">
                                <td><input class="form-control mx-1" placeholder="R$ 0,00" disabled></td>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="d-flex mb-1" id="selectorReceitaPendente" data-tippy-content="Total de Receitas Pendentes (Utiliza os filtros para somar)">
                            <div class="col text-end">
                                <label><small>A Receber</small></label>
                            </div>
                            <div class="col">
                                <input class="form-control mx-1" placeholder="R$ 0,00" disabled>
                            </div>
                        </div>
                        <div class="d-flex mb-1" id="selectorDespesaPendente" data-tippy-content="Total de Despesas Pendentes (Utiliza os filtros para somar)">
                            <div class="col text-end">
                                <label><small>A Pagar</small></label>
                            </div>
                            <div class="col">
                                <input class="form-control mx-1" placeholder="R$ 0,00" disabled>
                            </div>
                        </div>
                        <div class="d-flex mb-1" id="selectorBtnVerSaldo" data-tippy-content="Clique para ver seu saldo">
                            <div class="col text-end">
                                <button type="button" class="btn btn-primary success text-white" data-action="btnVerSaldo">Ver Saldo</button>
                            </div>
                        </div>
                        <div class="d-flex mb-1 d-none" id="selectorSaldo">
                            <div class="col text-end">
                                <small>Saldo</small>
                                <i class="fas fa-times text-danger text-end cursor" data-action="btnOcultarSaldo" data-tippy-content="Ocultar Saldo"></i>
                            </div>
                            <div class="col" data-tippy-content="Saldo da Conta (Utiliza o filtro de conta para calcular)">
                                <input class="form-control fw-bold mx-1" placeholder="R$ 0,00" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fim :: Sumario do Fluxo -->

        </div>
        <!-- Fim :: Cabeçalho -->

        <div class="row">
            <div class="col-12">
                <div class="tab-content">

                    <!-- Inicio :: Resumo -->
                    <div class="tab-pane fade show active" id="resumo" role="tabpanel" aria-labelledby="resumo-tab">

                        <!-- Inicio :: Gráfico -->
                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="card mb-4">
                                    <div class="card-header fw-bold">Gráfico</div>
                                    <div class="app-card shadow-sm p-4">
                                        <div class="app-card-body">
                                            <div class="row">
                                                <div class="col-md-2 col-lg-2 col-sm-12">
                                                    <input type="month" class="form-control" id="selectorDataGrafico" value="<?php echo date('Y-m'); ?>">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 col-lg-12 col-sm-12">
                                                    <canvas id="graficoMes"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fim :: Gráfico -->

                        <!-- Inicio :: Tabelas de Contas a Receber/Pagar -->
                        <div class="row mt-2">


                            <!-- Inicio :: Contas a Receber -->
                            <div class="col-md-6 col-lg-6 col-sm-12 mb-2 order-1">

                                <!-- Inicio :: Contas a Receber Hoje -->
                                <div class="card mb-4">
                                    <div class="card-header fw-bold">Contas a Receber - Hoje</div>
                                    <div class="app-card shadow-sm p-0">
                                        <div class="app-card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-striped table-sm" id="tableReceberHoje"></table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Fim :: Contas a Receber Hoje -->

                                <!-- Inicio :: Contas a Receber Vencidas -->
                                <div class="card mb-4">
                                    <div class="card-header fw-bold">Contas a Receber - Vencidas</div>
                                    <div class="app-card shadow-sm p-0">
                                        <div class="app-card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-striped table-sm" id="tableReceberVencida"></table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Fim :: Contas a Receber Vencidas -->

                                <!-- Inicio :: Contas a Receber Futuras -->
                                <div class="card mb-4">
                                    <div class="card-header fw-bold">Contas a Receber - Futuras</div>
                                    <div class="app-card shadow-sm p-0">
                                        <div class="app-card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-striped table-sm" id="tableReceberFutura"></table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Fim :: Contas a Receber Futuras -->

                            </div>
                            <!-- Fim :: Contas a Receber -->

                            <!-- Inicio :: Contas a Pagar -->
                            <div class="col-md-6 col-lg-6 col-sm-12 mb-2 order-2">

                                <!-- Inicio :: Contas a Pagar Hoje -->
                                <div class="card mb-4">
                                    <div class="card-header fw-bold">Contas a Pagar - Hoje</div>
                                    <div class="app-card shadow-sm p-0">
                                        <div class="app-card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-striped table-sm" id="tablePagarHoje"></table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Fim :: Contas a Pagar Hoje -->

                                <!-- Inicio :: Contas a Pagar Vencidas -->
                                <div class="card mb-4">
                                    <div class="card-header fw-bold">Contas a Pagar - Vencidas</div>
                                    <div class="app-card shadow-sm p-0">
                                        <div class="app-card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-striped table-sm" id="tablePagarVencida"></table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Fim :: Contas a Pagar Vencidas -->

                                <!-- Inicio :: Contas a Pagar Futuras -->
                                <div class="card mb-4">
                                    <div class="card-header fw-bold">Contas a Pagar - Futuras</div>
                                    <div class="app-card shadow-sm p-0">
                                        <div class="app-card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-striped table-sm" id="tablePagarFutura"></table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Fim :: Contas a Pagar Futuras -->

                            </div>
                            <!-- Fim :: Contas a Pagar -->

                        </div>
                    </div>
                    <!-- Fim :: Resumo -->

                    <!-- Inicio :: Fluxo -->
                    <div class="tab-pane fade" id="fluxo" role="tabpanel" aria-labelledby="fluxo-tab">

                        <!-- Inicio :: Filtros do Fluxo -->
                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="card mb-4">
                                    <div class="card-header fw-bold d-flex justify-content-between align-items-center">
                                        <div class="col-auto">Filtros</div>
                                        <div class="col-auto">
                                            <input type="button" class="btn btn-secondary" data-action="alternaCampos" value="Básico" disabled />
                                            <input type="button" class="btn btn-secondary" data-action="alternaCampos" value="Avançado" />
                                        </div>
                                    </div>
                                    <div class="app-card shadow-sm p-4">
                                        <div class="app-card-body">
                                            <div class="row">

                                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2" data-filtro="filtro_empresa" data-tipo="avancado">
                                                    <label>Empresa</label>
                                                    <input type="text" class="form-control" data-select="buscarEmpresa" name="codigo_empresa" />
                                                </div>

                                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2" data-filtro="filtro_tipoData">
                                                    <label>Tipo da Data</label>
                                                    <select class="form-control" name="tipo_data">
                                                        <option value="pagamento">Pagamento</option>
                                                        <option value="vencimento" selected>Vencimento</option>
                                                        <option value="lancamento">Lançamento no sistema</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2" data-filtro="filtro_dataInicio">
                                                    <label>Data Início</label>
                                                    <input type="date" class="form-control" name="data_inicio" value="<?= date('Y-m-d', strtotime("-15 days")); ?>" />
                                                </div>

                                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2" data-filtro="filtro_dataFim">
                                                    <label>Data Fim</label>
                                                    <input type="date" class="form-control" name="data_fim" value="<?= date('Y-m-d'); ?>" />
                                                </div>

                                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2" data-filtro="filtro_dataCompetencia" data-tipo="avancado">
                                                    <label>Competência</label>
                                                    <input type="month" class="form-control" name="data_competencia" />
                                                </div>

                                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2" data-filtro="filtro_conta">
                                                    <label>Conta</label>
                                                    <input type="text" class="form-control" data-select="buscarEmpresaConta" name="codigo_empresa_conta" />
                                                </div>

                                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2" data-filtro="filtro_centroCusto">
                                                    <label>Centro de Custo</label>
                                                    <input type="text" class="form-control" data-select="buscarEmpresaCentroCusto" name="codigo_empresa_centro_custo" />
                                                </div>

                                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2" data-filtro="filtro_tipo">
                                                    <label>Tipo</label>
                                                    <input type="text" class="form-control" data-select="buscarCadastroFluxoTipo" name="codigo_cadastro_fluxo_tipo" />
                                                </div>

                                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2" data-filtro="filtro_situacao">
                                                    <label>Situação</label>
                                                    <select class="form-control" name="situacao">
                                                        <option value="" selected>Todos</option>
                                                        <option value="f">Pendente</option>
                                                        <option value="t">Paga</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2" data-filtro="filtro_metodoPagamento" data-tipo="avancado">
                                                    <label>Método de Pagamento</label>
                                                    <input type="text" class="form-control" data-select="buscarCadastroMetodoPagamento" name="codigo_cadastro_metodo_pagamento" />
                                                </div>

                                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2" data-filtro="filtro_fornecedor" data-tipo="avancado">
                                                    <label>Fornecedor</label>
                                                    <input type="text" class="form-control" data-select="buscarFornecedor" name="codigo_fornecedor" />
                                                </div>

                                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2" data-filtro="filtro_cliente" data-tipo="avancado">
                                                    <label>Cliente</label>
                                                    <input type="text" class="form-control" data-select="buscarCliente" name="codigo_cliente" />
                                                </div>

                                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2" data-filtro="filtro_usuario" data-tipo="avancado">
                                                    <label>Usuário</label>
                                                    <input type="text" class="form-control" data-select="buscarUsuario" name="codigo_usuario" />
                                                </div>

                                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2" data-filtro="filtro_tipoRegistro" data-tipo="avancado">
                                                    <label>Tipo do registro</label>
                                                    <select class="form-control" name="insercao_automatica">
                                                        <option value="" selected>Todos</option>
                                                        <option value="t">Automático</option>
                                                        <option value="f">Manual</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2" data-filtro="filtro_estorno" data-tipo="avancado">
                                                    <label>Mostrar estornos</label>
                                                    <select class="form-control" name="incluir_estorno">
                                                        <option value="t">Sim</option>
                                                        <option value="f">Não</option>
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
                            </div>
                        </div>
                        <!-- Fim :: Filtros do Fluxo -->

                        <div class="row mt-1">
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <div class="card mb-4">
                                    <div class="card-header fw-bold">Fluxo</div>
                                    <div class="app-card shadow-sm p-0">
                                        <div class="app-card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-striped table-sm" id="tableFluxo"></table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                    <!-- Fim :: Fluxo -->

                </div>
            </div>
        </div>

    </div>
</div>
