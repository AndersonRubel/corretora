<div class="app-wrapper">
    <div class="app-content pt-3 p-md-3 p-lg-4">

        <!-- Inicio :: Titulo e Botões -->
        <div class="row g-3 align-items-center justify-content-between">

            <div class="col-auto">
                <h1 class="app-page-title mb-0">Visualizar Vendedor</h1>
            </div>

            <div class="col-auto">
                <div class="page-utilities">
                    <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                        <div class="col-auto">
                            <a class="btn btn-secondary" href="<?= base_url("vendedor"); ?>">Voltar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="mb-4">
        <!-- Fim :: Titulo e Botões -->

        <!-- Inicio :: Filtros -->
        <div class="card mb-4">
            <div class="card-header fw-bold">Filtros</div>
            <div class="app-card shadow-sm p-4">
                <div class="app-card-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-2 col-lg-2" data-filtro="filtro_dataInicio">
                            <label>Data Início</label>
                            <input type="date" class="form-control" name="data_inicio" value="<?= date('Y-m-01'); ?>" />
                        </div>

                        <div class="col-sm-12 col-md-2 col-lg-2" data-filtro="filtro_dataFim">
                            <label>Data Fim</label>
                            <input type="date" class="form-control" name="data_fim" value="<?= date('Y-m-t'); ?>" />
                        </div>

                        <div class="col-sm-12 col-md-3 col-lg-3" data-filtro="filtro_produto">
                            <label>Produto</label>
                            <input type="text" class="form-control" data-select="buscarProduto" name="codigo_produto" />
                        </div>

                        <div class="col-sm-12 col-md-3 col-lg-3" data-filtro="filtro_cadastroMetodoPagamento">
                            <label>Método de Pagamento</label>
                            <input type="text" class="form-control" data-select="buscarCadastroMetodoPagamento" name="codigo_cadastro_metodo_pagamento" />
                        </div>

                        <div class="col-sm-12 col-md-2 col-lg-2 d-none">
                            <label>Vendedor</label>
                            <input type="text" class="form-control" data-select="buscarVendedor" name="codigo_vendedor" value="<?= $vendedor['codigo_vendedor'] ?>" readonly />
                        </div>

                        <div class="col-sm-12 col-md-2 col-lg-2">
                            <label>Pagamento</label>
                            <select class="form-control" name="exibir_pago">
                                <option value="">Todos</option>
                                <option value="t">Apenas Pagos</option>
                                <option value="f">Apenas Pendentes</option>
                            </select>
                        </div>

                        <!-- Inicio :: Botão Filtrar e Limpar -->
                        <div class=" col mt-1 d-flex align-items-end justify-content-end">
                            <button type="button" class="btn btn-danger text-white mx-1" data-action="btnLimpar">Limpar</button>
                            <button type="submit" class="btn btn-success text-white" data-action="btnFiltrar">Filtrar</button>
                        </div>
                        <!-- Fim :: Botão Filtrar e Limpar -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Fim :: Filtros -->

        <nav class="orders-table-tab app-nav-tabs nav shadow-sm flex-column flex-sm-row mb-4">
            <a class="flex-sm-fill text-center nav-link active" id="dados-tab" data-bs-toggle="tab" href="#dados" role="tab" aria-controls="dados" aria-selected="true">
                Dados Básicos
            </a>
            <a class="flex-sm-fill text-center nav-link" id="estoque-tab" data-bs-toggle="tab" href="#estoque" role="tab" aria-controls="estoque" aria-selected="false">
                Estoque
            </a>
            <a class="flex-sm-fill text-center nav-link" id="vendas-tab" data-bs-toggle="tab" href="#vendas" role="tab" aria-controls="vendas" aria-selected="false">
                Vendas
            </a>
            <a class="flex-sm-fill text-center nav-link" id="financeiro-tab" data-bs-toggle="tab" href="#financeiro" role="tab" aria-controls="financeiro" aria-selected="false">
                Financeiro
            </a>
        </nav>

        <div class="tab-content">

            <!-- Inicio :: Dados Básico -->
            <div class="tab-pane fade show active" id="dados" role="tabpanel" aria-labelledby="dados-tab">
                <div class="card">
                    <div class="card-header fw-bold">Dados Básicos</div>
                    <div class="app-card shadow-sm p-4">
                        <div class="app-card-body">
                            <div class="row">
                                <input type="hidden" id="tipoPessoa" value="<?= old('tipo_pessoa', $vendedor['tipo_pessoa']); ?>">

                                <div class="col-md-6 col-lg-6 col-sm-12 mb-2" id="razaoSocial">
                                    <label class="form-label">Razão Social</label>
                                    <input type="text" class="form-control" readonly value="<?= old('razao_social', $vendedor['razao_social']); ?>">
                                </div>
                                <div class="col-md-6 col-lg-6 col-sm-12 mb-2" id="nomeFantasia">
                                    <label class="form-label">Nome Fantasia</label>
                                    <input type="text" class="form-control" readonly value="<?= old('nome_fantasia', $vendedor['nome_fantasia']); ?>">
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2" id="cpfCnpj">
                                    <label class="form-label">CNPJ</label>
                                    <input type="text" class="form-control" readonly data-mask="cnpjCpf" value="<?= old('cpf_cnpj', $vendedor['cpf_cnpj']); ?>">
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2" id="dataNascimento">
                                    <label class="form-label">Data de Nascimento</label>
                                    <input type="date" class="form-control" readonly value="<?= old('data_nascimento', $vendedor['data_nascimento']); ?>">
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label class="form-label">Telefone</label>
                                    <input type="text" class="form-control" readonly data-mask="telefoneCelular" value="<?= old('telefone', $vendedor['telefone']); ?>">
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label class="form-label">Celular</label>
                                    <input type="text" class="form-control" readonly data-mask="telefoneCelular" value="<?= old('celular', $vendedor['celular']); ?>">
                                </div>
                                <div class="col-md-12 col-lg-12 col-sm-12 mb-4">
                                    <label class="form-label">Observação</label>
                                    <textarea class="form-control" readonly rows="2"><?= old('observacao', $vendedor['observacao']); ?></textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- Fim :: Dados Básico -->

            <!-- Inicio :: Estoque -->
            <div class="tab-pane fade" id="estoque" role="tabpanel" aria-labelledby="estoque-tab">

                <!-- Inicio :: Indicadores -->
                <div class="row justify-content-center g-4 mb-4">
                    <?php foreach ($indicadores as $key => $value) : ?>
                        <div class="col-6 col-lg-3">
                            <div class="app-card app-card-stat shadow-sm h-100">
                                <div class="app-card-body p-3 p-lg-4">
                                    <h4 class="stats-type mb-1"><?= $value['nome'] ?></h4>
                                    <div class="stats-figure">R$ <?= intToReal($value['valor']) ?></div>
                                    <div class="stats-meta"><?= $value['descricao'] ?></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- Fim :: Indicadores -->

                <div class="row g-4 mb-4">
                    <div class="col-md-12 col-lg-12 col-sm-12 mb-2">
                        <div class="card">
                            <div class="card-header fw-bold">Estoque do Vendedor</div>
                            <div class="app-card shadow-sm">
                                <div class="app-card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped mb-0 text-left" id="tableEstoque"></table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- Fim :: Estoque -->

            <!-- Inicio :: Vendas -->
            <div class="tab-pane fade" id="vendas" role="tabpanel" aria-labelledby="vendas-tab">
                <div class="row g-4 mb-4">
                    <div class="col-md-12 col-lg-12 col-sm-12 mb-2">
                        <div class="card">
                            <div class="card-header fw-bold">Histórico de Vendas</div>
                            <div class="app-card shadow-sm">
                                <div class="app-card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped mb-0 text-left" id="tableHistoricoVendas"></table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fim :: Vendas -->

            <!-- Inicio :: Financeiro -->
            <div class="tab-pane fade" id="financeiro" role="tabpanel" aria-labelledby="financeiro-tab">
                <div class="row g-4 mb-4">
                    <div class="col-md-12 col-lg-12 col-sm-12 mb-2">
                        <div class="card">
                            <div class="card-header fw-bold">Histórico Financeiro</div>
                            <div class="app-card shadow-sm">
                                <div class="app-card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped mb-0 text-left" id="tableHistoricoFinanceiro"></table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fim :: Financeiro -->

        </div>

    </div>
</div>
