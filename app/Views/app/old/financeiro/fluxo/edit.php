<div class="app-wrapper">
    <div class="app-content pt-3 p-md-3 p-lg-4">
        <!-- Inicio :: Titulo e Botões -->
        <div class="row g-3 align-items-center justify-content-between">

            <div class="col-auto">
                <h1 class="app-page-title mb-0">Editar Fluxo</h1>
            </div>

            <div class="col-auto">
                <div class="page-utilities">
                    <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                        <div class="col-auto">
                            <a class="btn btn-secondary" href="<?= base_url("financeiro"); ?>">Voltar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="mb-4">
        <!-- Fim :: Titulo e Botões -->

        <!-- Inicio :: Formulário -->
        <form method="POST" action="<?= base_url("financeiro/update/{$fluxo['uuid_financeiro_fluxo']}"); ?>">

            <!-- Inicio :: Cadastro Básico -->
            <div class="card">
                <div class="card-header fw-bold">Dados Básicos</div>
                <div class="app-card shadow-sm p-4">
                    <div class="app-card-body">
                        <div class="row">
                            <input type="hidden" name="insercao_automatica" value="<?= $fluxo['insercao_automatica'] ?>" />
                            <div class="col-md-2 col-lg-2 col-sm-12 mb-2">
                                <label>Tipo</label>
                                <input type="text" class="form-control" data-select="buscarCadastroFluxoTipo" name="codigo_cadastro_fluxo_tipo" required value="<?= old('codigo_cadastro_fluxo_tipo', $fluxo['codigo_cadastro_fluxo_tipo']); ?>" />
                            </div>
                            <div class="col-md-5 col-lg-5 col-sm-12 mb-2">
                                <label>Nome</label>
                                <input type="text" class="form-control" name="nome" required value="<?= old('nome', $fluxo['nome']); ?>" />
                            </div>
                            <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                <label>Centro de Custo</label>
                                <input type="text" class="form-control" data-select="buscarEmpresaCentroCusto" name="codigo_empresa_centro_custo" value="<?= old('codigo_empresa_centro_custo', $fluxo['codigo_empresa_centro_custo']); ?>" />
                            </div>
                            <div class="col-md-2 col-lg-2 col-sm-12 mb-2" id="selectorDataVencimento">
                                <label>Data Vencimento</label>
                                <input type="date" class="form-control" name="data_vencimento" required value="<?= old('data_vencimento', $fluxo['data_vencimento']); ?>" />
                            </div>
                            <div class="col-md-2 col-lg-2 col-sm-12 mb-2">
                                <label>Valor (R$)</label>
                                <input type="text" class="form-control" name="valor_bruto" data-mask="dinheiro" required value="<?= old('valor_bruto', intToReal($fluxo['valor_bruto'])); ?>" placeholder="0,00" data-tippy-content="Informe o Valor do Fluxo" />
                            </div>
                            <div class="col-md-2 col-lg-2 col-sm-12 mb-2">
                                <label>Valor de Juros (R$)</label>
                                <input type="text" class="form-control" name="valor_juros" data-mask="dinheiro" value="<?= old('valor_juros', intToReal($fluxo['valor_juros'])); ?>" placeholder="0,00" />
                            </div>
                            <div class="col-md-2 col-lg-2 col-sm-12 mb-2">
                                <label>Valor de Acréscimo (R$)</label>
                                <input type="text" class="form-control" name="valor_acrescimo" data-mask="dinheiro" value="<?= old('valor_acrescimo', intToReal($fluxo['valor_acrescimo'])); ?>" placeholder="0,00" />
                            </div>
                            <div class="col-md-2 col-lg-2 col-sm-12 mb-2">
                                <label>Valor de Desconto (R$)</label>
                                <input type="text" class="form-control" name="valor_desconto" data-mask="dinheiro" value="<?= old('valor_desconto', intToReal($fluxo['valor_desconto'])); ?>" placeholder="0,00" />
                            </div>
                            <div class="col-md-2 col-lg-2 col-sm-12 mb-2">
                                <label>Valor Pago Parcial (R$)</label>
                                <input type="text" class="form-control" name="valor_pago_parcial" data-mask="dinheiro" readonly value="<?= old('valor_pago_parcial', intToReal($fluxo['valor_pago_parcial'])); ?>" placeholder="0,00" />
                            </div>
                            <div class="col-md-2 col-lg-2 col-sm-12 mb-2" id="selectorSituacao">
                                <label>Situação</label>
                                <select class="form-control" name="situacao" required value="<?= old('situacao', $fluxo['situacao']); ?>">
                                    <option value="f" selected>Pendente</option>
                                    <option value="t">Paga</option>
                                </select>
                            </div>
                            <div class="col-md-2 col-lg-2 col-sm-12 mb-2 d-none" id="selectorDataPagamento">
                                <label>Data Pagamento</label>
                                <input type="date" class="form-control" name="data_pagamento" value="<?= old('data_pagamento', $fluxo['data_pagamento']); ?>" />
                            </div>
                            <div class="col-md-12 col-lg-12 col-sm-12 mt-2 mb-1">
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
                        <div class="app-card shadow-sm p-4">
                            <div class="app-card-body mb-3">
                                <div class="row">
                                    <div class="col-md-2 col-lg-2 col-sm-12 mb-2">
                                        <label>Competência</label>
                                        <input type="date" class="form-control" name="data_competencia" value="<?= old('data_competencia', $fluxo['data_competencia']); ?>" />
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                        <label>Conta</label>
                                        <input type="text" class="form-control" data-select="buscarEmpresaConta" name="codigo_empresa_conta" value="<?= old('codigo_empresa_conta', $fluxo['codigo_empresa_conta']); ?>" />
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                        <label>Método de Pagamento</label>
                                        <input type="text" class="form-control" data-select="buscarCadastroMetodoPagamento" name="codigo_cadastro_metodo_pagamento" value="<?= old('codigo_cadastro_metodo_pagamento', $fluxo['codigo_cadastro_metodo_pagamento']); ?>" />
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                                        <label>Código de Barras</label>
                                        <input type="text" class="form-control" name="codigo_barras" data-verificanumero="true" value="<?= old('codigo_barras', $fluxo['codigo_barras']); ?>" />
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                                        <label>Fornecedor</label>
                                        <input type="text" class="form-control" data-select="buscarFornecedor" name="codigo_fornecedor" value="<?= old('codigo_fornecedor', $fluxo['codigo_fornecedor']); ?>" />
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                                        <label>Cliente</label>
                                        <input type="text" class="form-control" data-select="buscarCliente" name="codigo_cliente" value="<?= old('codigo_cliente', $fluxo['codigo_cliente']); ?>" />
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                                        <label>Vendedor</label>
                                        <input type="text" class="form-control" data-select="buscarVendedor" name="codigo_vendedor" value="<?= old('codigo_vendedor', $fluxo['codigo_vendedor']); ?>" />
                                    </div>

                                    <div class="col-md-12 col-lg-12 col-sm-12 mb-4" id="selectorObservacao">
                                        <label>Observação</label>
                                        <textarea class="form-control" name="observacao" rows="2"><?= old('observacao', $fluxo['observacao']); ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fim :: Outras Informações -->

            <div class="d-flex justify-content-end mt-2">
                <button type="submit" class="btn app-btn-primary" data-action="realizarSubmit">Salvar</button>
            </div>

        </form>
        <!-- Fim :: Formulário -->


    </div>
</div>
