<div class="app-wrapper">
    <div class="app-content pt-3 p-md-3 p-lg-4">

        <!-- Inicio :: Titulo e Botões -->
        <div class="row g-3 align-items-center justify-content-between">

            <div class="col-auto">
                <h1 class="app-page-title mb-0">Aniversários</h1>
            </div>

            <div class="col-auto">
                <div class="page-utilities">
                    <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                        <div class="col-auto">
                            <a class="btn btn-primary success text-white" href="#">Recarregar</a>
                            <a class="btn btn-primary success text-white" href="#">Histórico</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="mb-4">
        <!-- Fim :: Titulo e Botões -->
        <form method="POST" action="<?= base_url('aniversario/envio'); ?>">

            <div class="row mt-2">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header fw-bold d-flex justify-content-between align-items-center">
                            <div class="col-auto">Enviar Mensagem</div>
                            <div class="col-auto">
                                <a class="btn btn-info text-white btn-sm" href="#" data-tippy-content="Esse é seu saldo disponível para enviar SMS.">
                                    Crédito Disponivel:
                                    <span id="creditoDisponivel">R$ <?= intToReal($saldoDisponivel); ?></span>
                                </a>
                            </div>
                        </div>
                        <div class="app-card shadow-sm">
                            <div class="app-card-body">
                                <div class="row px-3 py-2 mb-2">
                                    <div class="col-md-3 col-lg-3 col-sm-12 mt-2 mb-2">
                                        <label>Enviar SMS? (custo: R$ <?= intToReal($custoSms); ?> por mensagem)</label>
                                        <select class="form-control" name="envia_sms">
                                            <option value="t">Sim</option>
                                            <option value="f">Não</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-sm-12 mt-2 mb-2">
                                        <label>Enviar Email? (gratuito)</label>
                                        <select class="form-control" name="envia_email">
                                            <option value="t">Sim</option>
                                            <option value="f">Não</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row px-3 py-2 mb-2">
                                    <div class="col-md-12 col-lg-12 col-sm-12 mt-2 mb-4 d-none" id="selectorMensagemSMS">
                                        <label>Mensagem do SMS</label>
                                        <textarea class="form-control" name="mensagem_sms" rows="1">Caro #CLIENTE#, nós da #EMPRESA# desejamos à você um Feliz Aniversário!</textarea>
                                    </div>
                                    <div class="col-md-12 col-lg-12 col-sm-12 mt-2 mb-4 d-none" id="selectorMensagemEmail">
                                        <label>Mensagem do Email</label>
                                        <textarea class="form-control" name="mensagem_email" rows="1">Caro #CLIENTE#, nós da #EMPRESA# desejamos à você um Feliz Aniversário!</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-1 mb-2 mx-3">
                                <button type="submit" class="btn app-btn-primary" data-action="realizarSubmit">Enviar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="row mt-2">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header fw-bold">Aniversariantes</div>
                    <div class="app-card shadow-sm">
                        <div class="app-card-body">
                            <div class="row px-3 py-2">
                                <div class="col-md-3 col-lg-3 col-sm-12 mt-2">
                                    <label>Filtro de Data</label> <br>
                                    <button type="button" class="btn btn-secondary" data-action="btnFiltroDataRapido">Hoje</button>
                                    <button type="button" class="btn btn-secondary" data-action="btnFiltroDataRapido">Semana</button>
                                    <button type="button" class="btn btn-secondary" data-action="btnFiltroDataRapido">Mês</button>
                                    <button type="button" class="btn btn-secondary" data-action="btnFiltroDataRapido">Customizado</button>
                                </div>
                                <div class="col-md-2 col-lg-2 col-sm-12 mt-2">
                                    <label>Data de</label> <br>
                                    <input type="date" class="form-control" name="data_de" value="<?= date('Y-m-d'); ?>">
                                </div>
                                <div class="col-md-2 col-lg-2 col-sm-12 mt-2">
                                    <label>Data até</label> <br>
                                    <input type="date" class="form-control" name="data_ate" value="<?= date('Y-m-d'); ?>">
                                </div>
                                <div class="col-md-2 col-lg-2 col-sm-12 mt-3">
                                    <button type="button" class="btn btn-success text-white mt-4" data-action="btnFiltroAniversariante">Filtrar</button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0 text-left" id="tableAniversariantes"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
