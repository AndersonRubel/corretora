<!-- Inicio :: Modal Ver Fluxo -->
<div class="modal fade" id="modalVisualizarFluxo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Visualizar Fluxo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <!-- Inicio :: Cadastro Básico -->
                <div class="card">
                    <div class="card-header fw-bold">Dados Básicos</div>
                    <div class="app-card px-4 pt-2">
                        <div class="app-card-body">
                            <div class="row">
                                <div class="col-md-2 col-lg-2 col-sm-12 mb-2">
                                    <label>Tipo</label>
                                    <input type="text" class="form-control" data-select="buscarCadastroFluxoTipo" name="codigo_cadastro_fluxo_tipo" readonly />
                                </div>
                                <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                                    <label>Nome</label>
                                    <input type="text" class="form-control" name="nome" readonly />
                                </div>
                                <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                                    <label>Centro de Custo</label>
                                    <input type="text" class="form-control" data-select="buscarEmpresaCentroCusto" name="codigo_empresa_centro_custo" readonly />
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label>Valor (R$)</label>
                                    <input type="text" class="form-control" name="valor_bruto" placeholder="0,00" readonly />
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label>Valor de Juros (R$)</label>
                                    <input type="text" class="form-control" name="valor_juros" placeholder="0,00" readonly />
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label>Valor de Acréscimo (R$)</label>
                                    <input type="text" class="form-control" name="valor_acrescimo" placeholder="0,00" readonly />
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label>Valor de Desconto (R$)</label>
                                    <input type="text" class="form-control" name="valor_desconto" placeholder="0,00" readonly />
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label>Valor Pago Parcial (R$)</label>
                                    <input type="text" class="form-control" name="valor_pago_parcial" placeholder="0,00" readonly />
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label>Situação</label>
                                    <select class="form-control" name="situacao" readonly>
                                        <option value="f">Pendente</option>
                                        <option value="t">Paga</option>
                                    </select>
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label>Data Vencimento</label>
                                    <input type="date" class="form-control" name="data_vencimento" readonly />
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label>Data Pagamento</label>
                                    <input type="date" class="form-control" name="data_pagamento" readonly />
                                </div>
                                <div class="col-md-12 col-lg-12 col-sm-12 mt-3">
                                    <h3 class="text-end text-muted">
                                        Valor Total: R$
                                        <span id="calculoValorTotal">0,00</span>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fim :: Cadastro Básico -->

                <!-- Inicio :: Outras Informações -->
                <div class="row mt-4">
                    <div class="col-md-12 col-lg-12 col-sm-12 mb-2">
                        <div class="card">
                            <div class="card-header fw-bold">Outras Informações</div>
                            <div class="app-card px-4 pt-2">
                                <div class="app-card-body mb-3">
                                    <div class="row">
                                        <div class="col-md-2 col-lg-2 col-sm-12 mb-2">
                                            <label>Competência</label>
                                            <input type="date" class="form-control" name="data_competencia" readonly />
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                            <label>Conta</label>
                                            <input type="text" class="form-control" data-select="buscarEmpresaConta" name="codigo_empresa_conta" readonly />
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                            <label>Método de Pagamento</label>
                                            <input type="text" class="form-control" data-select="buscarCadastroMetodoPagamento" name="codigo_cadastro_metodo_pagamento" readonly />
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                                            <label>Código de Barras</label>
                                            <input type="text" class="form-control" name="codigo_barras" readonly />
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                                            <label>Fornecedor</label>
                                            <input type="text" class="form-control" data-select="buscarFornecedor" name="codigo_fornecedor" readonly />
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                                            <label>Cliente</label>
                                            <input type="text" class="form-control" data-select="buscarCliente" name="codigo_cliente" readonly />
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                                            <label>Vendedor</label>
                                            <input type="text" class="form-control" data-select="buscarVendedor" name="codigo_vendedor" readonly />
                                        </div>
                                        <div class="col-md-12 col-lg-12 col-sm-12 mb-4">
                                            <label>Observação</label>
                                            <textarea class="form-control" name="observacao" rows="2" readonly></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fim :: Outras Informações -->


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success text-white" data-id="" data-action="btnImprimirComprovante">Imprimir Comprovante</button>
                <button type="button" class="btn btn-info text-white" data-id="" data-action="btnImprimirRecibo">Imprimir Recibo</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
<!-- Fim :: Modal Ver Fluxo -->

<!-- Inicio :: Modal Fluxo Parcial -->
<div class="modal fade" id="modalFluxoParcial" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pagamento Parcial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-12">

                        <!-- Inicio :: Informações do Pagamento -->
                        <div class="card">
                            <div class="card-header fw-bold">Informações do Pagamento</div>
                            <div class="app-card px-4 pt-2">
                                <div class="app-card-body">
                                    <form id="formPagamentoParcial">
                                        <div class="row">
                                            <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                                <label>Data Vencimento</label>
                                                <input type="date" class="form-control" name="data_vencimento" readonly />
                                            </div>
                                            <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                                <label>Valor Total (R$)</label>
                                                <input type="text" class="form-control" name="valor_total" placeholder="0,00" readonly />
                                            </div>
                                            <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                                <label>Valor em aberto (R$)</label>
                                                <input type="text" class="form-control" name="saldo_devedor" placeholder="0,00" readonly />
                                            </div>
                                            <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                                <label>Método de Pagamento</label>
                                                <input type="text" class="form-control" data-select="buscarCadastroMetodoPagamento" name="codigo_cadastro_metodo_pagamento" required />
                                            </div>
                                            <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                                <label>Data Pagamento</label>
                                                <input type="date" class="form-control" name="data_pagamento" required />
                                            </div>
                                            <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                                <label>Valor do Pagamento (R$)</label>
                                                <input type="text" class="form-control" data-mask="dinheiro" name="valor" placeholder="0,00" required />
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Fim :: Informações do Pagamento -->

                        <!-- Inicio :: Pagamentos Realizados -->
                        <div class="row mt-4">
                            <div class="col-md-12 col-lg-12 col-sm-12 mb-2">
                                <div class="card">
                                    <div class="card-header fw-bold">Pagamentos Realizados</div>
                                    <div class="app-card px-4 pt-2">
                                        <div class="app-card-body mb-3">
                                            <p class="fw-bold d-none text-center mt-4 mb-0" id="emptyPagamentoParcial">Não foram encontrados pagamentos parciais para esse fluxo.</p>
                                            <div class="row d-none" id="notEmptyPagamentoParcial">
                                                <div class="col-md-12 col-lg-12 col-sm-12 mb-2">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped table-sm">
                                                            <thead>
                                                                <th>Data de Pagamento</th>
                                                                <th>Forma de Pagamento</th>
                                                                <th class="text-end">Valor</th>
                                                                <th class="text-center">Opções</th>
                                                            </thead>
                                                            <tbody></tbody>
                                                            <tfoot></tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fim :: Pagamentos Realizados -->

                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success text-white" data-action="realizarPagamentoParcial">Salvar</button>
            </div>
        </div>
    </div>
</div>
<!-- Fim :: Modal Fluxo Parcial -->
