<div class="app-wrapper reserva">
    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">

            <!-- Inicio :: Titulo e Botões -->
            <div class="row g-3 align-items-center justify-content-between">

                <div class="col-auto">
                    <h1 class="app-page-title mb-0">Adicionar Reserva <i class="fa fa-question-circle" id="btnHelp"></i></h1>
                </div>

                <div class="col-auto">
                    <div class="page-utilities">
                        <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                            <div class="col-auto">
                                <a class="btn btn-secondary" href="<?= base_url("reserva"); ?>">Voltar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="mb-4">
            <!-- Fim :: Titulo e Botões -->

            <!-- Inicio :: Formulário -->
            <form method="POST" action="<?= base_url('reserva/store'); ?>">

                <div class="card">
                    <div class="card-header fw-bold">Dados Básicos</div>
                    <div class="app-card shadow-sm p-4">
                        <div class="app-card-body">
                            <div class="row">
                                <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                                    <label class="form-label">Buscar Imóvel</label>
                                    <input type="text" class="form-control" name="codigo_imovel" data-select="buscarImovel" value="<?= old('codigo_imovel'); ?>" required>
                                </div>

                                <div class="col-md-6 col-lg-6 col-sm-12 mb-4">
                                    <label class="form-label">Buscar Cliente *</label>
                                    <div class="input-group">
                                        <span class="input-group-text cursor" id="btnModalCadastrarCliente" data-tippy-content="Cadastrar novo Cliente" data-tippy-placement="bottom" data-bs-toggle="modal" data-bs-target="#modalCadastrarCliente">
                                            <i class="fas fa-user-plus"></i>
                                        </span>
                                        <input type="text" class="form-control" name="codigo_cliente" data-select="buscarCliente" value="<?= old('codigo_cliente'); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12 col-sm-12 mb-5">
                                    <label class="form-label">Descrição</label>
                                    <textarea class="form-control" name="descricao" rows="2" data-tippy-content="Informe uma Descrição Para o Imóvel"><?= old('descricao'); ?></textarea>
                                </div>
                                <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                                    <label class="form-label">Data de Início</label>
                                    <input type="date" class="form-control" name="data_inicio" required value="<?= old('data_inicio'); ?>" data-tippy-content="Data de Início da Reserva">
                                </div>
                                <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                                    <label class="form-label">Data Fim</label>
                                    <input type="date" class="form-control" name="data_fim" required value="<?= old('data_fim'); ?>" data-tippy-content="Data do Fim da Reserva">
                                </div>

                                <hr class="mb-4">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-2">
                    <button type="submit" class="btn app-btn-primary">Salvar</button>
                </div>

            </form>
            <!-- Fim :: Formulário -->
































        </div>
    </div>
</div>
