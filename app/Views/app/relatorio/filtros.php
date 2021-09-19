<div class="card mb-4" id="cardFiltros">
    <div class="card-header fw-bold">Filtros</div>
    <div class="app-card shadow-sm p-4">
        <div class="app-card-body">
            <div class="row">
                <form class="row" id="formRelatorio">

                    <div class="col-sm-12 col-md-6 col-lg-3 d-none" data-filtro="filtro_empresas">
                        <label>Empresa</label>
                        <input type="text" class="form-control" data-select="buscarEmpresas" name="codigo_empresas" />
                    </div>

                    <div class="col-sm-12 col-md-6 col-lg-3 d-none" data-filtro="filtro_tipoDataFinanceiro">
                        <label>Tipo de Data</label>
                        <select class="form-control" name="tipo_datafinanceiro" data-selectTwo="true">
                            <option value="vencimento">Por Vencimento</option>
                            <option value="realizado">Por Realizado</option>
                            <option value="competencia">Por Competência</option>
                        </select>
                    </div>

                    <div class="col-sm-12 col-md-6 col-lg-3 d-none" data-filtro="filtro_dataInicio">
                        <label>Data Início</label>
                        <input type="date" class="form-control" name="data_inicio" />
                    </div>

                    <div class="col-sm-12 col-md-6 col-lg-3 d-none" data-filtro="filtro_dataFim">
                        <label>Data Fim</label>
                        <input type="date" class="form-control" name="data_fim" />
                    </div>

                    <div class="col-sm-12 col-md-6 col-lg-3 d-none" data-filtro="filtro_dataHoraInicio">
                        <label>Data/Hora Início</label>
                        <input type="datetime-local" class="form-control" name="data_hora_inicio" />
                    </div>

                    <div class="col-sm-12 col-md-6 col-lg-3 d-none" data-filtro="filtro_dataHoraFim">
                        <label>Data/Hora Fim</label>
                        <input type="datetime-local" class="form-control" name="data_hora_fim" />
                    </div>

                    <div class="col-sm-12 col-md-6 col-lg-3 d-none" data-filtro="filtro_vendedor">
                        <label>Vendedor</label>
                        <input type="text" class="form-control" data-select="buscarVendedor" name="codigo_vendedor">
                    </div>

                    <div class="col-sm-12 col-md-6 col-lg-3 d-none" data-filtro="filtro_cliente">
                        <label>Cliente</label>
                        <input type="text" class="form-control" data-select="buscarCliente" name="codigo_cliente" />
                    </div>

                    <div class="col-sm-12 col-md-6 col-lg-3 d-none" data-filtro="filtro_usuario">
                        <label>Usuário</label>
                        <input type="text" class="form-control" data-select="buscarUsuario" name="usuario_id">
                    </div>

                    <div class="col-sm-12 col-md-6 col-lg-3 d-none" data-filtro="filtro_dataCompetencia">
                        <label>Data de Competência</label>
                        <input type="date" class="form-control" name="data_competencia" />
                    </div>

                    <div class="col-sm-12 col-md-6 col-lg-3 d-none" data-filtro="filtro_statusPagamento">
                        <label>Status</label>
                        <select class="form-control" name="status_fluxo" data-selectTwo="true">
                            <option value="">Selecione</option>
                            <option value="t">Pago</option>
                            <option value="f">Pendente</option>
                        </select>
                    </div>

                    <div class="col-sm-12 col-md-6 col-lg-3 d-none" data-filtro="filtro_fluxoTipo">
                        <label>Tipo do Fluxo</label>
                        <input type="text" class="form-control" data-select="buscarFluxoTipo" name="fluxo_tipo_id" />
                    </div>

                    <div class="col-sm-12 col-md-6 col-lg-3 d-none" data-filtro="filtro_estorno">
                        <label>Mostrar Estorno</label>
                        <select class="form-control" name="mostrar_estorno" data-selectTwo="true">
                            <option value="sim">Sim</option>
                            <option value="nao" selected>Não</option>
                            <option value="apenas">Apenas os Estornos</option>
                        </select>
                    </div>

                    <!-- Inicio :: Botão Filtrar e Limpar -->
                    <div class="col mt-1 d-flex align-items-end justify-content-end">
                        <button type="button" class="btn btn-danger text-white mx-1" data-action="btnLimpar">Limpar</button>
                        <button type="submit" class="btn btn-success text-white" data-action="btnFiltrar">Filtrar</button>
                    </div>
                    <!-- Fim :: Botão Filtrar e Limpar -->
                </form>
            </div>
        </div>
    </div>
</div>
