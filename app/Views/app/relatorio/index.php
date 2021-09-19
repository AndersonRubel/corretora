<?php

use Config\Services;

?>

<div class="app-wrapper" id="produto">
    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="content-wrapper">
            <section class="content-header">

                <div class="row g-3 align-items-center justify-content-between">
                    <div class="col-auto">
                        <h1 class="app-page-title mb-0"> Relatório <?= $method != 'index' ? 'de ' . $method : 'do sistema'; ?></h1>
                    </div>
                    <?php if (!empty($instrucoes)) : ?>
                        <button data-toggle="modal" data-target="#modalRelatorioInstrucoes" type="button" class="mr-2 btn btn-secondary btn-info" data-tippy-content="Instruções para o Relatório">Instruções</button>
                    <?php endif; ?>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <select class="form-control" name="relatorioTipo">
                            <option value="">Selecione um Relatório</option>
                            <?php foreach ($relatorios as $key => $value) : ?>
                                <optgroup label="<?= $value['descricao']; ?>">
                                    <?php foreach ($value['relatorios'] as $keyRelatorios => $valueRelatorios) : ?>
                                        <option value="<?= $valueRelatorios['slug']; ?>" <?= Services::uri()->getPath() == 'relatorios/' . $valueRelatorios['slug'] ? 'selected' : '' ?>><?= $valueRelatorios['nome']; ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
        </div>

        </section>

    </div>

    <hr class="mb-4">
    <!-- Fim :: Titulo e Botões -->

    <!-- Inicio :: Filtros Disponíveis -->
    <?= view('app/relatorio/filtros'); ?>
    <!-- Fim :: Filtros Disponíveis -->

    <div class="tab-content">

        <!-- Inicio :: Todo o Conteúdo dos Relatórios -->
        <section class="content" id="fullContentReports">

            <!-- Inicio :: Conteúdo dos Relatório -->
            <div>
                <!-- Inicio :: Cards de Relatorios -->

                <!-- Inicio :: Gráfico -->
                <article class="w-100 d-none" id="grafico">
                    <div class="card">
                        <div class="card-header c-pointer" data-card-widget="collapse">
                            <h4 class="card-title">Sumário</h4>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="dataGrafico"></div>
                            <div class="text-center sem-dados">
                                <h3>Não há dados no momento</h3>
                            </div>
                        </div>
                    </div>
                </article>
                <!-- Fim :: Gráfico -->

                <!-- Inicio :: Sumário -->
                <article class="w-100 d-none" id="sumario">
                    <div class="card">
                        <div class="card-header c-pointer" data-card-widget="collapse">
                            <h4 class="card-title">Sumário</h4>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="dataSumario"></div>
                            <div class="text-center sem-dados">
                                <h3>Não há dados no momento</h3>
                            </div>
                        </div>
                    </div>
                </article>
                <!-- Fim :: Sumário -->

                <!-- Inicio :: Listagem -->
                <article class="w-100 d-none" id="listagem">
                    <div class="card">
                        <div class="card-header fw-bold">Relatório</div>
                        <div class="card-body">
                            <table class="table display table-striped table-hover" id="tableRelatorio"></table>
                            <div class="text-center sem-dados">
                                <h6>Não há dados no momento</h6>
                            </div>
                        </div>
                    </div>
                </article>
                <!-- Fim :: Listagem -->


                <!-- Fim :: Cards de Relatorios -->
            </div>
            <!-- Fim :: Conteúdo dos Relatório -->
        </section>

        <!-- Fim :: Todo o Conteúdo dos Relatórios -->

    </div>

</div>
</div>
