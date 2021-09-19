<div class="app-wrapper grupo">
    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">

            <!-- Inicio :: Titulo e Botões -->
            <div class="row g-3 align-items-center justify-content-between">

                <div class="col-auto">
                    <h1 class="app-page-title mb-0">Adicionar Grupo</h1>
                </div>

                <div class="col-auto">
                    <div class="page-utilities">
                        <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                            <div class="col-auto">
                                <a class="btn btn-secondary" href="<?= base_url("grupo"); ?>">Voltar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="mb-4">
            <!-- Fim :: Titulo e Botões -->

            <!-- Inicio :: Formulário -->
            <form method="POST" action="<?= base_url('grupo/store'); ?>">

                <div class="card">
                    <div class="card-header fw-bold">Dados Básicos</div>
                    <div class="app-card shadow-sm p-4">
                        <div class="app-card-body">
                            <div class="row">
                                <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                                    <label class="form-label">Empresa</label>
                                    <input type="text" class="form-control" name="codigo_empresa" data-select="buscarEmpresa" value="<?= old('codigo_empresa'); ?>">
                                </div>
                                <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                                    <label class="form-label">Nome</label>
                                    <input type="text" class="form-control" name="nome" required value="<?= old('nome'); ?>">
                                </div>
                                <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                                    <label class="form-label">Slug</label>
                                    <input type="text" class="form-control" name="slug" required value="<?= old('slug'); ?>">
                                </div>

                                <hr class="mb-4">

                                <div class="col-md-12 col-lg-12 col-sm-12 mb-2">
                                    <table class="table display table-hover">
                                        <thead>
                                            <tr>
                                                <th>Nome</th>
                                                <th class="text-center">
                                                    Consultar <br>
                                                    <span data-action="marcarTodosColuna">(Marcar Todos)</span>
                                                    <span class="d-none" data-action="desmarcaTodosColuna">(Desmarcar Todos)</span>
                                                </th>
                                                <th class="text-center">Inserir <br>
                                                    <span data-action="marcarTodosColuna">(Marcar Todos)</span>
                                                    <span class="d-none" data-action="desmarcaTodosColuna">(Desmarcar Todos)</span>
                                                </th>
                                                <th class="text-center">Modificar <br>
                                                    <span data-action="marcarTodosColuna">(Marcar Todos)</span>
                                                    <span class="d-none" data-action="desmarcaTodosColuna">(Desmarcar Todos)</span>
                                                </th>
                                                <th class="text-center">Excluir <br>
                                                    <span data-action="marcarTodosColuna">(Marcar Todos)</span>
                                                    <span class="d-none" data-action="desmarcaTodosColuna">(Desmarcar Todos)</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($menu as $value) : ?>
                                                <tr>
                                                    <td>
                                                        <i class="fas fa-info-circle" data-tippy-content="<?= $value['descricao']; ?>"></i>
                                                        <?= $value['nome']; ?>
                                                        <br>
                                                        <span data-action="selecionarTodosLinha"><b>(Marcar Todos)</b></span>
                                                        <span class="d-none" data-action="desmarcaTodosLinha"><b>(Desmarcar Todos)</b></span>
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input" type="checkbox" name="permissao[<?= $value['codigo_cadastro_menu'] ?>][consultar]" value="1">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input" type="checkbox" name="permissao[<?= $value['codigo_cadastro_menu'] ?>][inserir]" value="1">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input" type="checkbox" name="permissao[<?= $value['codigo_cadastro_menu'] ?>][modificar]" value="1">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input" type="checkbox" name="permissao[<?= $value['codigo_cadastro_menu'] ?>][deletar]" value="1">
                                                    </td>
                                                </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header fw-bold">Permissões para Relatórios</div>
                    <div class="app-card shadow-sm p-4">
                        <div class="app-card-body">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12 mb-2">
                                    <label class="form-label">Relatórios</label>
                                    <input type="text" class="form-control" name="relatorio" data-select="buscarRelatorio">
                                </div>
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
