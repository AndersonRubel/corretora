<div class="app-wrapper" id="faturamento">
    <div class="app-content pt-3 p-md-3 p-lg-4">
        <!-- Inicio :: Titulo e Botões -->
        <div class="row g-3 align-items-center justify-content-between">

            <div class="col-auto">
                <h1 class="app-page-title mb-0">Cadastrar Faturamento</h1>
            </div>

            <div class="col-auto">
                <div class="page-utilities">
                    <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                        <div class="col-auto">
                            <a class="btn btn-secondary" href="<?= base_url("faturamento"); ?>">Voltar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="mb-4">
        <!-- Fim :: Titulo e Botões -->

        <!-- Inicio :: Formulário -->
        <form id="formFaturamento" method="POST" action="<?= base_url('faturamento/store'); ?>">

            <!-- Inicio :: Cadastro Básico -->
            <div class="card">
                <div class="card-header fw-bold">Dados Básicos</div>
                <div class="app-card shadow-sm p-4">
                    <div class="app-card-body">
                        <div class="row">

                            <div class="col-md-2 col-lg-2 col-sm-12 mb-2">
                                <label>Período Início</label>
                                <input type="date" class="form-control" name="periodo_inicio" required value="<?= date('Y-m-01'); ?>" />
                            </div>

                            <div class="col-md-2 col-lg-2 col-sm-12 mb-2">
                                <label>Período Fim</label>
                                <input type="date" class="form-control" name="periodo_fim" required value="<?= date('Y-m-t'); ?>" />
                            </div>

                            <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                <label>Vendedor</label>
                                <input type="text" class="form-control" name="codigo_vendedor" data-select="buscarVendedor" required value="<?= old('codigo_vendedor'); ?>" />
                            </div>

                            <div class="col mt-1 d-flex align-items-end justify-content-end">
                                <button type="button" class="btn btn-success text-white" data-action="btnBuscarDados">Buscar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fim :: Cadastro Básico -->

            <!-- Inicio :: Box de Configuração do Fluxo -->
            <div class="row pt-4 pb-2 d-none" id="blocoRecebimento">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header fw-bold">Dados do Financeiro</div>
                        <div class="app-card shadow-sm p-4">
                            <div class="app-card-body">
                                <div class="row">
                                    <div class="col-md-2 mb-2">
                                        <label>Conta</label>
                                        <input type="text" class="form-control" data-select="buscarEmpresaConta" name="codigo_empresa_conta">
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <label>Método de Pagamento</label>
                                        <input type="text" class="form-control" data-select="buscarCadastroMetodoPagamento" name="codigo_cadastro_metodo_pagamento">
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label>Centro de Custo</label>
                                        <input type="text" class="form-control" data-select="buscarEmpresaCentroCusto" name="codigo_empresa_centro_custo">
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label>Comissionamento</label>
                                        <input type="text" class="form-control" data-select="buscarEmpresaComissao" name="codigo_empresa_comissao">
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <label>Data de Vencimento</label>
                                        <input type="date" class="form-control" name="data_vencimento" value="<?= date('Y-m-d', strtotime("+30 days")); ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Valor Total Bruto</label>
                                        <input type="text" class="form-control" name="valor_total_bruto" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Valor Comissão</label>
                                        <input type="text" class="form-control" name="valor_comissao" value="0,00" data-mask="dinheiro" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Valor Desconto</label>
                                        <input type="text" class="form-control" name="valor_desconto" value="0,00" data-mask="dinheiro">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Valor Líquido</label>
                                        <input type="text" class="form-control" name="valor_total_liquido" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Valor Entrada</label>
                                        <input type="text" class="form-control" name="valor_entrada" value="0,00" data-mask="dinheiro">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Valor Restante</label>
                                        <input type="text" class="form-control" name="valor_restante" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fim :: Box de Configuração do Fluxo -->

            <!-- Inicio :: Listagem do Faturamento -->
            <div class="row py-4 d-none" id="listagemFaturamento">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header fw-bold">Vendas</div>
                        <div class="app-card shadow-sm p-0">
                            <div class="app-card-body">

                                <div class="row" id="tableFaturamentos">
                                    <div class="col-12 text-end my-2 px-4">
                                        <button type="button" class="btn btn-success text-white" onclick="faturamentoFunctions.changeCheckFaturar('marcar')">Marcar Todos</button>
                                        <button type="button" class="btn btn-danger text-white" onclick="faturamentoFunctions.changeCheckFaturar('desmarcar')">Desmarcar Todos</button>
                                        <button type="button" class="btn btn-secondary" onclick="faturamentoFunctions.changeCheckFaturar('inverter')">Inverter Seleção</button>
                                    </div>
                                    <div class="col-12">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <th class="text-center">FATURAR?</th>
                                                <th class="text-center">CLIENTE</th>
                                                <th class="text-center">VENDA</th>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="semDados">
                                <div class="col-12 text-center font-weight-bold">
                                    Nenhuma venda encontrada para este período ou vendedor.
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
            <!-- Fim :: Listagem do Faturamento -->

            <div class="d-flex justify-content-center mt-2">
                <button type="button" class="btn app-btn-primary d-none" data-action="realizarSubmit">Gerar Faturamento</button>
            </div>

        </form>
        <!-- Fim :: Formulário -->

    </div>
</div>
