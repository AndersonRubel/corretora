<div class="app-wrapper">
    <div class="app-content pt-3 p-md-3 p-lg-4">

        <!-- Inicio :: Titulo e Botões -->
        <div class="row g-3 align-items-center justify-content-between">

            <div class="col-auto">
                <h1 class="app-page-title mb-0">Estoque - Histórico</h1>
            </div>

            <div class="col-auto">
                <div class="page-utilities">
                    <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                        <div class="col-auto">
                            <a class="btn btn-secondary" href="<?= base_url("estoque/historico"); ?>">Voltar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="mb-4">
        <!-- Fim :: Titulo e Botões -->

        <!-- Inicio :: Variaveis de Controle para parametro -->
        <input type="hidden" name="uuid_estoque_historico" value="<?= $uuid_estoque_historico ?>">
        <input type="hidden" name="estoque_historico_dia" value="<?= $estoque_historico_dia ?>">
        <!-- Fim :: Variaveis de Controle para parametro -->

        <!-- Inicio :: Listagem do Estoque -->
        <div class="card mb-4">
            <div class="card-header fw-bold">Histórico de Estoque</div>
            <div class="app-card shadow-sm">
                <div class="app-card-body">
                    <div class="app-card app-card-orders-table shadow-sm">
                        <div class="app-card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0 text-left" id="tableHistoricoItem"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fim :: Listagem do Estoque -->

    </div>
</div>
