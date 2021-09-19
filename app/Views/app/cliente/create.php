<div class="app-wrapper">
    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">

            <!-- Inicio :: Titulo e Botões -->
            <div class="row g-3 align-items-center justify-content-between">

                <div class="col-auto">
                    <h1 class="app-page-title mb-0">Adicionar Cliente</h1>
                </div>

                <div class="col-auto">
                    <div class="page-utilities">
                        <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                            <div class="col-auto">
                                <a class="btn btn-secondary" href="<?= base_url("cliente"); ?>">Voltar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="mb-4">
            <!-- Fim :: Titulo e Botões -->

            <!-- Inicio :: Formulário -->
            <form method="POST" action="<?= base_url('cliente/store'); ?>">

                <!-- Inicio :: Cadastro Básico -->
                <div class="card">
                    <div class="card-header fw-bold">Dados Básicos</div>
                    <div class="app-card shadow-sm p-4">
                        <div class="app-card-body">
                            <div class="row mb-2">
                                <div class="col-md-3 col-lg-3 col-sm-12">
                                    <label class="form-label">Tipo de Pessoa</label>
                                    <select class="form-control" name="tipo_pessoa" id="tipoPessoa" required>
                                        <option value="1" <?= old('tipo_pessoa') == 1 ? 'selected' : '' ?>>Pessoa Física</option>
                                        <option value="2" <?= old('tipo_pessoa') == 2 ? 'selected' : '' ?>>Pessoa Jurídica</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 col-lg-6 col-sm-12 mb-2" id="razaoSocial">
                                    <label class="form-label">Razão Social</label>
                                    <input type="text" class="form-control" name="razao_social" required value="<?= old('razao_social'); ?>">
                                </div>
                                <div class="col-md-6 col-lg-6 col-sm-12 mb-2" id="nomeFantasia">
                                    <label class="form-label">Nome Fantasia</label>
                                    <input type="text" class="form-control" name="nome_fantasia" required value="<?= old('nome_fantasia'); ?>">
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2" id="cpfCnpj">
                                    <label class="form-label">CNPJ</label>
                                    <input type="text" class="form-control" name="cpf_cnpj" data-mask="cnpjCpf" value="<?= old('cpf_cnpj'); ?>">
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2" id="dataNascimento">
                                    <label class="form-label">Data de Nascimento</label>
                                    <input type="date" class="form-control" name="data_nascimento" value="<?= old('data_nascimento'); ?>">
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label class="form-label">Telefone</label>
                                    <input type="text" class="form-control" name="telefone" data-mask="telefoneCelular" value="<?= old('telefone'); ?>">
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label class="form-label">Celular</label>
                                    <input type="text" class="form-control" name="celular" data-mask="telefoneCelular" value="<?= old('celular'); ?>">
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" value="<?= old('email'); ?>">
                                </div>

                                <div class="col-md-12 col-lg-12 col-sm-12 mb-4">
                                    <label class="form-label">Observação</label>
                                    <textarea class="form-control" name="observacao" rows="2"><?= old('observacao'); ?></textarea>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <!-- Fim :: Cadastro Básico -->

                <!-- Inicio :: Cadastro de Endereço -->
                <div class="card mt-4">
                    <div class="card-header fw-bold">Dados dos endereços</div>
                    <div class="app-card shadow-sm p-4">
                        <div class="app-card-body">

                            <div id="insertEmptyRowEndereco"></div>

                            <!-- Inicio :: Card Endereço -->
                            <div class="card mb-4 selector-row-enderecos">
                                <div class="card-header fw-bold">
                                    <div class="row justify-content-between align-items-center">
                                        <div class="col-auto"></div>
                                        <div class="col-auto">
                                            <div class="card-header-action">
                                                <button type="button" class="btn btn-primary btn-danger text-white" data-action="removerEndereco">
                                                    <span class="fas fa-times"></span>
                                                    Remover endereço
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="app-card shadow-sm p-4">
                                    <div class="app-card-body">
                                        <div class="row">
                                            <div class="col-md-2 col-lg-2 col-sm-12 mb-2">
                                                <label class="form-label">CEP</label>
                                                <input type="text" class="form-control" name="endereco[cep][]" data-mask="cep">
                                            </div>
                                            <div class="col-md-5 col-lg-5 col-sm-12 mb-2">
                                                <label class="form-label">Rua</label>
                                                <input type="text" class="form-control" name="endereco[rua][]" readonly>
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-sm-12 mb-2">
                                                <label class="form-label">Número</label>
                                                <input type="text" class="form-control" name="endereco[numero][]" data-verificaNumero="true">
                                            </div>
                                            <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                                <label class="form-label">Bairro</label>
                                                <input type="text" class="form-control" name="endereco[bairro][]" readonly>
                                            </div>
                                            <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                                                <label class="form-label">Complemento</label>
                                                <input type="text" class="form-control" name="endereco[complemento][]">
                                            </div>
                                            <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                                                <label class="form-label">Cidade</label>
                                                <input type="hidden" name="endereco[cidade][]">
                                                <input type="hidden" name="endereco[uf][]">
                                                <input type="text" class="form-control" name="endereco[cidade_completa][]" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fim :: Card Endereço -->

                            <hr class="my-4">

                            <div class="row float-end">
                                <div class="col-md-12 col-lg-12 col-sm-12 mb-2">
                                    <button type="button" class="btn btn-primary btn-success text-white mx-1" data-action="novoEndereco" data-tippy-content="Adicionar Endereço">
                                        <span class="fas fa-plus"></span>
                                        Adicionar endereço
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fim :: Cadastro de Endereço -->

                <div class="d-flex justify-content-end mt-2">
                    <button type="submit" class="btn app-btn-primary">Salvar</button>
                </div>

            </form>
            <!-- Fim :: Formulário -->


        </div>
    </div>
</div>
