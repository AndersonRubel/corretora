<div class="app-wrapper">
    <div class="app-content pt-3 p-md-3 p-lg-4">
        <!-- Inicio :: Titulo e Botões -->
        <div class="row g-3 align-items-center justify-content-between">

            <div class="col-auto">
                <h1 class="app-page-title mb-0">Cadastrar Fluxo Manual</h1>
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
        <form method="POST" action="<?= base_url('financeiro/store'); ?>">

            <!-- Inicio :: Cadastro Básico -->
            <div class="card">
                <div class="card-header fw-bold d-flex justify-content-between align-items-center">
                    <div class="col-auto">Dados Básicos</div>
                    <div class="col-auto">
                        <input type="button" class="btn btn-secondary" data-action="alternaCampos" value="Básico" disabled />
                        <input type="button" class="btn btn-secondary" data-action="alternaCampos" value="Avançado" />
                    </div>
                </div>
                <div class="app-card shadow-sm p-4">
                    <div class="app-card-body">
                        <div class="row">
                            <input type="hidden" name="insercao_automatica" value="f" />
                            <div class="col-md-2 col-lg-2 col-sm-12 mb-2">
                                <label>Tipo</label>
                                <input type="text" class="form-control" data-select="buscarCadastroFluxoTipo" name="codigo_cadastro_fluxo_tipo" required value="<?= old('codigo_cadastro_fluxo_tipo'); ?>" />
                            </div>
                            <div class="col-md-5 col-lg-5 col-sm-12 mb-2">
                                <label>Nome</label>
                                <input type="text" class="form-control" name="nome" required value="<?= old('nome'); ?>" />
                            </div>
                            <div class="col-md-2 col-lg-2 col-sm-12 mb-2">
                                <label>Ocorrência</label>
                                <select class="form-control" name="ocorrencia" required value="<?= old('ocorrencia'); ?>">
                                    <option value="U" selected>Única</option>
                                    <option value="P">Parcelada/Recorrente</option>
                                </select>
                            </div>
                            <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                <label>Centro de Custo</label>
                                <input type="text" class="form-control" data-select="buscarEmpresaCentroCusto" name="codigo_empresa_centro_custo" value="<?= old('codigo_empresa_centro_custo'); ?>" />
                            </div>
                            <div class="col-md-2 col-lg-2 col-sm-12 mb-2" id="selectorDataVencimento">
                                <label>Data Vencimento</label>
                                <input type="date" class="form-control" name="data_vencimento" required value="<?= old('data_vencimento'); ?>" />
                            </div>
                            <div class="col-md-2 col-lg-2 col-sm-12 mb-2">
                                <label>Valor (R$)</label>
                                <input type="text" class="form-control" name="valor_bruto" data-mask="dinheiro" required value="<?= old('valor_bruto'); ?>" placeholder="0,00" data-tippy-content="Informe o Valor do Fluxo" />
                            </div>
                            <div class="col-md-2 col-lg-2 col-sm-12 mb-2" data-tipo="avancado">
                                <label>Valor de Juros (R$)</label>
                                <input type="text" class="form-control" name="valor_juros" data-mask="dinheiro" value="<?= old('valor_juros'); ?>" placeholder="0,00" />
                            </div>
                            <div class="col-md-2 col-lg-2 col-sm-12 mb-2" data-tipo="avancado">
                                <label>Valor de Acréscimo (R$)</label>
                                <input type="text" class="form-control" name="valor_acrescimo" data-mask="dinheiro" value="<?= old('valor_acrescimo'); ?>" placeholder="0,00" />
                            </div>
                            <div class="col-md-2 col-lg-2 col-sm-12 mb-2" data-tipo="avancado">
                                <label>Valor de Desconto (R$)</label>
                                <input type="text" class="form-control" name="valor_desconto" data-mask="dinheiro" value="<?= old('valor_desconto'); ?>" placeholder="0,00" />
                            </div>
                            <div class="col-md-2 col-lg-2 col-sm-12 mb-2" id="selectorSituacao">
                                <label>Situação</label>
                                <select class="form-control" name="situacao" required value="<?= old('situacao'); ?>">
                                    <option value="f" selected>Pendente</option>
                                    <option value="t">Paga</option>
                                </select>
                            </div>
                            <div class="col-md-2 col-lg-2 col-sm-12 mb-2 d-none" id="selectorDataPagamento">
                                <label>Data Pagamento</label>
                                <input type="date" class="form-control" name="data_pagamento" value="<?= old('data_pagamento'); ?>" />
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

            <!-- Inicio :: Parcelamento/Recorrencia -->
            <div class="row mt-4 d-none" id="cardOcorrenciaParcelada">
                <div class="col-md-12 col-lg-12 col-sm-12 mb-2">
                    <div class="card">
                        <div class="card-header fw-bold">Parcelas / Recorrencia</div>
                        <div class="app-card shadow-sm p-4">
                            <div class="app-card-body mb-3">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12 col-sm-12 mb-2">
                                        <div class="table-responsive">
                                            <table class="table display table-border">
                                                <thead>
                                                    <tr class="fw-bold">
                                                        <td>Tipo de parcela *</td>
                                                        <td>Repetição *</td>
                                                        <td>Quantidade *</td>
                                                        <td>Data da 1ª Parcela *</td>
                                                        <td>Ações</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <select class="form-control" name="parcelamento_tipo" data-tippy-content="Selecione o tipo de parcela">
                                                                <option value="D" selected>Dividir o valor do lançamento entre as parcelas</option>
                                                                <option value="M">Multiplicar o valor do lançamento pelas parcelas</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select class="form-control" name="parcelamento_periodo" data-tippy-content="Informe o modo de repetição do pagamento">
                                                                <option value="M" selected="selected">Mensal</option>
                                                                <option value="W">Semanal</option>
                                                                <option value="Q">Quinzenal</option>
                                                                <option value="T">Trimestral</option>
                                                                <option value="S">Semestral</option>
                                                                <option value="A">Anual</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control" name="parcelamento_quantidade" data-verificanumero="true" data-tippy-content="Informe a quantidade">
                                                        </td>
                                                        <td>
                                                            <input type="date" class="form-control" name="parcelamento_data_primeira_parcela" data-tippy-content="Informe a data da parcela">
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-info text-white mt-1" data-action="btnGerarParcelasPag" data-tippy-content="Gerar parcelas"><i class="fas fa-cog"></i> Gerar</button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div id="divParcelas"></div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fim :: Parcelamento/Recorrencia -->

            <!-- Inicio :: Outras Informações -->
            <div class="row mt-4" data-tipo="avancado">
                <div class="col-md-12 col-lg-12 col-sm-12 mb-2">
                    <div class="card">
                        <div class="card-header fw-bold">Outras Informações</div>
                        <div class="app-card shadow-sm p-4">
                            <div class="app-card-body mb-3">
                                <div class="row">
                                    <div class="col-md-2 col-lg-2 col-sm-12 mb-2" data-tipo="avancado">
                                        <label>Competência</label>
                                        <input type="date" class="form-control" name="data_competencia" value="<?= old('data_competencia', date('Y-m-d')); ?>" />
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-sm-12 mb-2" data-tipo="avancado">
                                        <label>Conta</label>
                                        <input type="text" class="form-control" data-select="buscarEmpresaConta" name="codigo_empresa_conta" value="<?= old('codigo_empresa_conta'); ?>" />
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-sm-12 mb-2" data-tipo="avancado">
                                        <label>Método de Pagamento</label>
                                        <input type="text" class="form-control" data-select="buscarCadastroMetodoPagamento" name="codigo_cadastro_metodo_pagamento" value="<?= old('codigo_cadastro_metodo_pagamento'); ?>" />
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-12 mb-2" data-tipo="avancado">
                                        <label>Código de Barras</label>
                                        <input type="text" class="form-control" name="codigo_barras" data-verificanumero="true" value="<?= old('codigo_barras'); ?>" />
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-12 mb-2" data-tipo="avancado">
                                        <label>Fornecedor</label>
                                        <input type="text" class="form-control" data-select="buscarFornecedor" name="codigo_fornecedor" value="<?= old('codigo_fornecedor'); ?>" />
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-12 mb-2" data-tipo="avancado">
                                        <label>Cliente</label>
                                        <input type="text" class="form-control" data-select="buscarCliente" name="codigo_cliente" value="<?= old('codigo_cliente'); ?>" />
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-12 mb-2" data-tipo="avancado">
                                        <label>Vendedor</label>
                                        <input type="text" class="form-control" data-select="buscarVendedor" name="codigo_vendedor" value="<?= old('codigo_vendedor'); ?>" />
                                    </div>

                                    <div class="col-md-12 col-lg-12 col-sm-12 mb-4" id="selectorObservacao" data-tipo="avancado">
                                        <label>Observação</label>
                                        <textarea class="form-control" name="observacao" rows="2"><?= old('observacao'); ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fim :: Outras Informações -->

            <div class="d-flex justify-content-end mt-2">
                <button type="submit" class="btn app-btn-primary mx-2" data-action="realizarSubmit" data-state="salvar_e_continuar">Salvar e Cadastrar Outro</button>
                <button type="submit" class="btn app-btn-primary" data-action="realizarSubmit" data-state="salvar">Salvar</button>
                <input type="hidden" name="state_submit">
            </div>

        </form>
        <!-- Fim :: Formulário -->


    </div>
</div>
