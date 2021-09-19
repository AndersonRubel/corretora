<!-- Inicio :: Modal Ver Venda -->
<div class="modal fade" id="modalVisualizarVenda" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Visualizar Venda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <!-- Inicio :: Cadastro Básico -->
                <div class="card mb-4">
                    <div class="card-header fw-bold">Dados Básicos</div>
                    <div class="app-card px-4 pt-2">
                        <div class="app-card-body mb-3">
                            <div class="row">
                                <div class="col-md-2 col-lg-2 col-sm-12 mb-2">
                                    <label>Código</label>
                                    <input type="text" class="form-control" name="codigo_venda" readonly />
                                </div>
                                <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                                    <label>Cliente</label>
                                    <input type="text" class="form-control" data-select="buscarCliente" name="codigo_cliente" readonly />
                                </div>
                                <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                                    <label>Vendedor</label>
                                    <input type="text" class="form-control" data-select="buscarVendedor" name="codigo_vendedor" readonly />
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label>Método de Pagamento</label>
                                    <input type="text" class="form-control" data-select="buscarCadastroMetodoPagamento" name="codigo_cadastro_metodo_pagamento" readonly />
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label>Valor Bruto (R$)</label>
                                    <input type="text" class="form-control" name="valor_bruto" placeholder="0,00" readonly />
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label>Valor de Desconto (R$)</label>
                                    <input type="text" class="form-control" name="valor_desconto" placeholder="0,00" readonly />
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label>Valor Líquido (R$)</label>
                                    <input type="text" class="form-control" name="valor_liquido" placeholder="0,00" readonly />
                                </div>
                                <div class="col-md-12 col-lg-12 col-sm-12 mb-4">
                                    <label>Observação</label>
                                    <textarea class="form-control" name="observacao" rows="2" readonly></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fim :: Cadastro Básico -->

                <!-- Inicio :: Produtos da Venda -->
                <div class="card">
                    <div class="card-header fw-bold">Produtos da Venda</div>
                    <div class="app-card px-4 pt-2">
                        <div class="app-card-body mb-3">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12 mb-2">
                                    <table class="table table-striped table-hover" id="tableVendaProduto" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Código Produto</th>
                                                <th>Nome</th>
                                                <th class="text-center">Quantidade</th>
                                                <th class="text-end">Valor Unit.</th>
                                                <th class="text-end">Valor Desconto</th>
                                                <th class="text-end">Valor Total</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fim :: Produtos da Venda -->

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger text-white" data-id="" data-action="btnEstornarVenda">Estornar Venda</button>
                <button type="button" class="btn btn-info text-white" data-id="" data-action="btnImprimirComprovante">Imprimir Comprovante</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
<!-- Fim :: Modal Ver Venda -->
