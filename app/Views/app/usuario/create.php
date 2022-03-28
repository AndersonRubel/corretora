<div class="app-wrapper">
    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">

            <!-- Inicio :: Titulo e Botões -->
            <div class="row g-3 align-items-center justify-content-between">

                <div class="col-auto">
                    <h1 class="app-page-title mb-0">Adicionar Usuário<i class="fa fa-question-circle" id="btnHelp"></i></h1>
                </div>

                <div class="col-auto">
                    <div class="page-utilities">
                        <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                            <div class="col-auto">
                                <a class="btn btn-secondary" href="<?= base_url("usuario"); ?>">Voltar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="mb-4">
            <!-- Fim :: Titulo e Botões -->

            <!-- Inicio :: Formulário -->
            <form method="POST" action="<?= base_url('usuario/store'); ?>">

                <!-- Inicio :: Cadastro Básico -->
                <div class="card">
                    <div class="card-header fw-bold">Dados Básicos</div>
                    <div class="app-card shadow-sm p-4">
                        <div class="app-card-body">
                            <div class="row">
                                <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                                    <label class="form-label">Nome</label>
                                    <input type="text" class="form-control" name="nome" required value="<?= old('nome'); ?>">
                                </div>
                                <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" required value="<?= old('email'); ?>">
                                </div>
                                <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                                    <label class="form-label">Celular</label>
                                    <input type="text" class="form-control" name="celular" data-mask="telefoneCelular" value="<?= old('celular'); ?>">
                                </div>
                                <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                                    <label class="form-label">Senha</label>
                                    <input type="password" class="form-control" name="senha" required>
                                </div>
                                <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                                    <label class="form-label">Confirmar Senha</label>
                                    <input type="password" class="form-control" name="confirmar_senha" required>
                                </div>
                                <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                                    <label class="form-label">Grupo de Acesso</label>
                                    <input type="text" class="form-control" name="codigo_cadastro_grupo" data-select="buscarGrupo" value="<?= old('codigo_cadastro_grupo'); ?>">
                                </div>
                                <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                                    <label class="form-label">Empresa Padrão</label>
                                    <input type="text" class="form-control" name="codigo_empresa" data-select="buscarEmpresa" value="<?= old('codigo_empresa'); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fim :: Cadastro Básico -->

                <div class="d-flex justify-content-end mt-2">
                    <button type="submit" class="btn app-btn-primary">Salvar</button>
                </div>

            </form>
            <!-- Fim :: Formulário -->


        </div>
    </div>
</div>
