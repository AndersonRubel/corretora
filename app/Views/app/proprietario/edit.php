<div class="app-wrapper">
    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">

            <!-- Inicio :: Titulo e Botões -->
            <div class="row g-3 align-items-center justify-content-between">

                <div class="col-auto">
                    <h1 class="app-page-title mb-0">Alterar Proprietário</h1>
                </div>

                <div class="col-auto">
                    <div class="page-utilities">
                        <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                            <div class="col-auto">
                                <a class="btn btn-secondary" href="<?= base_url("proprietario"); ?>">Voltar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="mb-4">
            <!-- Fim :: Titulo e Botões -->

            <!-- Inicio :: Formulário -->
            <form method="POST" action="<?= base_url("proprietario/update/{$proprietario['uuid_proprietario']}"); ?>">

                <!-- Inicio :: Cadastro Básico -->
                <div class="card">
                    <div class="card-header fw-bold">Dados Básicos</div>
                    <div class="app-card shadow-sm p-4">
                        <div class="app-card-body">
                            <div class="row">
                                <input type="hidden" id="tipoPessoa" value="<?= old('tipo_pessoa', $proprietario['tipo_pessoa']); ?>">

                                <div class="col-md-6 col-lg-6 col-sm-12 mb-2" id="razaoSocial">
                                    <label class="form-label">Razão Social</label>
                                    <input type="text" class="form-control" name="razao_social" required value="<?= old('razao_social', $proprietario['razao_social']); ?>">
                                </div>
                                <div class="col-md-6 col-lg-6 col-sm-12 mb-2" id="nomeFantasia">
                                    <label class="form-label">Nome Fantasia</label>
                                    <input type="text" class="form-control" name="nome_fantasia" required value="<?= old('nome_fantasia', $proprietario['nome_fantasia']); ?>">
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2" id="cpfCnpj">
                                    <label class="form-label">CNPJ</label>
                                    <input type="text" class="form-control" name="cpf_cnpj" data-mask="cnpjCpf" required value="<?= old('cpf_cnpj', $proprietario['cpf_cnpj']); ?>">
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2" id="dataNascimento">
                                    <label class="form-label">Data de Nascimento</label>
                                    <input type="date" class="form-control" name="data_nascimento" value="<?= old('data_nascimento', $proprietario['data_nascimento']); ?>">
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label class="form-label">Telefone</label>
                                    <input type="text" class="form-control" name="telefone" data-mask="telefoneCelular" value="<?= old('telefone', $proprietario['telefone']); ?>">
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label class="form-label">Celular</label>
                                    <input type="text" class="form-control" name="celular" data-mask="telefoneCelular" value="<?= old('celular', $proprietario['celular']); ?>">
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" required value="<?= old('email', $proprietario['email']); ?>">
                                </div>

                                <div class="col-md-12 col-lg-12 col-sm-12 mb-4">
                                    <label class="form-label">Observação</label>
                                    <textarea class="form-control" name="observacao" rows="2"><?= old('observacao', $proprietario['observacao']); ?></textarea>
                                </div>

                                <hr class="mb-3 mt-4">

                                <div class="col-md-2 col-lg-2 col-sm-12 mb-2">
                                    <label class="form-label">CEP</label>
                                    <input type="text" class="form-control" name="cep" data-mask="cep" value="<?= old('cep', $proprietario['endereco']['cep']); ?>">
                                </div>
                                <div class="col-md-5 col-lg-5 col-sm-12 mb-2">
                                    <label class="form-label">Rua</label>
                                    <input type="text" class="form-control" name="rua" readonly value="<?= old('rua', $proprietario['endereco']['rua']); ?>">
                                </div>
                                <div class="col-md-2 col-lg-2 col-sm-12 mb-2">
                                    <label class="form-label">Número</label>
                                    <input type="text" class="form-control" name="numero" data-verificaNumero="true" value="<?= old('numero', $proprietario['endereco']['numero']); ?>">
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label class="form-label">Bairro</label>
                                    <input type="text" class="form-control" name="bairro" readonly value="<?= old('bairro', $proprietario['endereco']['bairro']); ?>">
                                </div>
                                <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                                    <label class="form-label">Complemento</label>
                                    <input type="text" class="form-control" name="complemento" value="<?= old('complemento', $proprietario['endereco']['complemento']); ?>">
                                </div>
                                <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                                    <label class="form-label">Cidade</label>
                                    <input type="hidden" name="cidade" value="<?= old('cidade', $proprietario['endereco']['cidade']); ?>">
                                    <input type="hidden" name="uf" value="<?= old('uf', $proprietario['endereco']['uf']); ?>">
                                    <input type="text" class="form-control" name="cidade_completa" readonly value="<?= old('cidade', $proprietario['endereco']['cidade']); ?>/<?= old('uf', $proprietario['endereco']['uf']); ?>">
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
