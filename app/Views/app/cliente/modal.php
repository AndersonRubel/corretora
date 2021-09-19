<!-- Inicio :: Modal Adicionar Saldo -->
<div class="modal fade" id="modalClienteAdicionarSaldo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar Saldo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?= base_url("cliente/adicionarSaldo") ?>">
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="uuid_cliente" />
                        <div class="col-md-12 col-lg-12 col-sm-12 mb-2">
                            <label>Descrição</label>
                            <input type="text" class="form-control" name="nome" required />
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12 mb-2">
                            <label>Valor (R$)</label>
                            <input type="text" class="form-control" name="valor" data-mask="dinheiro" placeholder="0,00" required />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success text-white" data-action="realizarPagamentoParcial">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Fim :: Modal Adicionar Saldo -->

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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success text-white" data-action="realizarPagamentoParcial">Salvar</button>
            </div>
        </div>
    </div>
</div>
<!-- Fim :: Modal Fluxo Parcial -->

<!-- Inicio :: Modal Abater Valores -->
<div class="modal fade" id="modalClienteAbaterValores" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Abater Valores</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?= base_url("financeiro/abaterValores") ?>">
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="uuid_cliente" />
                        <div class="col-md-12 col-lg-12 col-sm-12 mb-2">
                            <label>Valor em aberto (R$)</label>
                            <input type="text" class="form-control" name="valor_aberto" readonly />
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12 mb-2">
                            <label>Método de Pagamento</label>
                            <input type="text" class="form-control" data-select="buscarCadastroMetodoPagamento" name="codigo_cadastro_metodo_pagamento" required />
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12 mb-2">
                            <label>Valor (R$)</label>
                            <input type="text" class="form-control" name="valor_pagar" data-mask="dinheiro" placeholder="0,00" required />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success text-white" data-action="realizarPagamentoParcial">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Fim :: Modal Abater Valores -->
