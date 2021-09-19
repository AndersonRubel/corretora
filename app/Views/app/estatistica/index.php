<div class="app-wrapper" id="estatistica">
    <div class="app-content pt-3 p-md-3 p-lg-4">

        <!-- Inicio :: Titulo e Botões -->
        <div class="row g-3 align-items-center justify-content-between">

            <div class="col-auto">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="col-auto">
                        <h1 class="app-page-title mb-0">Estatísticas</h1>
                    </div>
                    <div class="col-auto mx-4">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button type="button" class="btn btn-secondary text-white" data-action="selecionarPeriodo">Dia</button>
                            <button type="button" class="btn btn-secondary text-white" data-action="selecionarPeriodo">Semana</button>
                            <button type="button" class="btn btn-secondary text-white" data-action="selecionarPeriodo">Mês</button>
                            <button type="button" class="btn btn-secondary text-white" data-action="selecionarPeriodo">Período</button>
                        </div>
                    </div>
                    <div class="col-5 mx-2">
                        <input type="text" class="form-control" data-select="buscarEmpresa" name="codigo_empresa" />
                    </div>
                </div>
            </div>

            <div class="col-auto">
                <div class="page-utilities">
                    <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                        <div class="col-auto">
                            <a class="btn btn-secondary text-white" href="#" data-action="btnFullScreen" data-tippy-content="Tela Cheia">
                                <i class="fas fa-expand-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="mb-4">
        <!-- Fim :: Titulo e Botões -->

        <div class="row">

            <!-- Inicio :: Vendas -->
            <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                <div class="card">
                    <div class="card-header fw-bold">Vendas</div>
                    <div class="app-card shadow-sm px-4 pb-4">
                        <div class="app-card-body">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12">

                                    <div class="text-center">
                                        <span class="title-one">Bruto</span>
                                        <span class="value-one">R$ 0,00</span>
                                    </div>
                                    <h5 class="descricao text-center" id="bar1Title">Hoje (0 Venda(s), Líquido R$ 0,00)</h5>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
                                    </div>

                                    <div class="text-center">
                                        <span class="title-two">Bruto</span>
                                        <span class="value-two">R$ 0,00</span>
                                    </div>
                                    <h5 class="descricao text-center" id="bar1Title">Ontem (0 Venda(s), Líquido R$ 0,00)</h5>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fim :: Vendas -->

            <!-- Inicio :: Melhores Vendedores (Frequência) -->
            <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                <div class="card">
                    <div class="card-header fw-bold">Melhores Vendedores (Frequência)</div>
                    <div class="app-card shadow-sm">
                        <div class="app-card-body">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-sm mb-0" id="tableMelhoresVendedoresFrequencia">
                                            <tbody class="sem-dados">
                                                <tr>
                                                    <td class="text-center">Nenhum Registro Encontrado</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fim :: Melhores Vendedores (Frequência) -->

            <!-- Inicio :: Melhores Vendedores (Valor) -->
            <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                <div class="card">
                    <div class="card-header fw-bold">Melhores Vendedores (Valor)</div>
                    <div class="app-card shadow-sm">
                        <div class="app-card-body">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-sm mb-0" id="tableMelhoresVendedoresValor">
                                            <tbody class="sem-dados">
                                                <tr>
                                                    <td class="text-center">Nenhum Registro Encontrado</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fim :: Melhores Vendedores (Valor) -->

        </div>

        <div class="row">

            <!-- Inicio :: Produtos mais Vendidos (Frequência) -->
            <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                <div class="card">
                    <div class="card-header fw-bold">Produtos mais Vendidos (Frequência)</div>
                    <div class="app-card shadow-sm">
                        <div class="app-card-body">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-sm mb-0" id="tableProdutosMaisVendidosFrequencia">
                                            <tbody class="sem-dados">
                                                <tr>
                                                    <td class="text-center">Nenhum Registro Encontrado</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fim :: Produtos mais Vendidos (Frequência) -->

            <!-- Inicio :: Produtos mais Vendidos (Valor) -->
            <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                <div class="card">
                    <div class="card-header fw-bold">Produtos mais Vendidos (Valor)</div>
                    <div class="app-card shadow-sm">
                        <div class="app-card-body">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-sm mb-0" id="tableProdutosMaisVendidosValor">
                                            <tbody class="sem-dados">
                                                <tr>
                                                    <td class="text-center">Nenhum Registro Encontrado</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fim :: Produtos mais Vendidos (Valor) -->

            <!-- Inicio :: Produtos mais Lucrativos -->
            <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                <div class="card">
                    <div class="card-header fw-bold">Produtos mais Lucrativos</div>
                    <div class="app-card shadow-sm">
                        <div class="app-card-body">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-sm mb-0" id="tableProdutosMaisLucrativos">
                                            <tbody class="sem-dados">
                                                <tr>
                                                    <td class="text-center">Nenhum Registro Encontrado</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fim :: Produtos mais Lucrativos -->

        </div>
    </div>
</div>
