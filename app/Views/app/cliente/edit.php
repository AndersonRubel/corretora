<div class="app-wrapper">
    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">

            <!-- Inicio :: Titulo e Botões -->
            <div class="row g-3 align-items-center justify-content-between">

                <div class="col-auto">
                    <h1 class="app-page-title mb-0">Alterar Cliente</h1>
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
            <form method="POST" action="<?= base_url("cliente/update/{$cliente['uuid_cliente']}"); ?>">

                <!-- Inicio :: Cadastro Básico -->
                <div class="card">
                    <div class="card-header fw-bold">Dados Básicos</div>
                    <div class="app-card shadow-sm p-4">
                        <div class="app-card-body">
                            <div class="row">
                                <input type="hidden" id="tipoPessoa" value="<?= old('tipo_pessoa', $cliente['tipo_pessoa']); ?>">

                                <div class="col-md-6 col-lg-6 col-sm-12 mb-2" id="razaoSocial">
                                    <label class="form-label">Razão Social</label>
                                    <input type="text" class="form-control" name="razao_social" required value="<?= old('razao_social', $cliente['razao_social']); ?>">
                                </div>
                                <div class="col-md-6 col-lg-6 col-sm-12 mb-2" id="nomeFantasia">
                                    <label class="form-label">Nome Fantasia</label>
                                    <input type="text" class="form-control" name="nome_fantasia" required value="<?= old('nome_fantasia', $cliente['nome_fantasia']); ?>">
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2" id="cpfCnpj">
                                    <label class="form-label">CNPJ</label>
                                    <input type="text" class="form-control" name="cpf_cnpj" data-mask="cnpjCpf" value="<?= old('cpf_cnpj', $cliente['cpf_cnpj']); ?>">
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2" id="dataNascimento">
                                    <label class="form-label">Data de Nascimento</label>
                                    <input type="date" class="form-control" name="data_nascimento" value="<?= old('data_nascimento', $cliente['data_nascimento']); ?>">
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label class="form-label">Telefone</label>
                                    <input type="text" class="form-control" name="telefone" data-mask="telefoneCelular" value="<?= old('telefone', $cliente['telefone']); ?>">
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label class="form-label">Celular</label>
                                    <input type="text" class="form-control" name="celular" data-mask="telefoneCelular" value="<?= old('celular', $cliente['celular']); ?>">
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" value="<?= old('email', $cliente['email']); ?>">
                                </div>

                                <div class="col-md-12 col-lg-12 col-sm-12 mb-4">
                                    <label class="form-label">Observação</label>
                                    <textarea class="form-control" name="observacao" rows="2"><?= old('observacao', $cliente['observacao']); ?></textarea>
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
                            <?php if (empty($cliente['endereco'])) : ?>
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
                            <?php else : ?>

                                <?php foreach ($cliente['endereco'] as $key => $value) : ?>
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
                                                    <input type="hidden" name="endereco[uuid_cliente_endereco][]" value="<?= $value['uuid_cliente_endereco']; ?>">
                                                    <div class="col-md-2 col-lg-2 col-sm-12 mb-2">
                                                        <label class="form-label">CEP</label>
                                                        <input type="text" class="form-control" name="endereco[cep][]" data-mask="cep" value="<?= $value['cep']; ?>">
                                                    </div>
                                                    <div class="col-md-5 col-lg-5 col-sm-12 mb-2">
                                                        <label class="form-label">Rua</label>
                                                        <input type="text" class="form-control" name="endereco[rua][]" readonly value="<?= $value['rua']; ?>">
                                                    </div>
                                                    <div class="col-md-2 col-lg-2 col-sm-12 mb-2">
                                                        <label class="form-label">Número</label>
                                                        <input type="text" class="form-control" name="endereco[numero][]" data-verificaNumero="true" value="<?= $value['numero']; ?>">
                                                    </div>
                                                    <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                                        <label class="form-label">Bairro</label>
                                                        <input type="text" class="form-control" name="endereco[bairro][]" readonly value="<?= $value['bairro']; ?>">
                                                    </div>
                                                    <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                                                        <label class="form-label">Complemento</label>
                                                        <input type="text" class="form-control" name="endereco[complemento][]" value="<?= $value['complemento']; ?>">
                                                    </div>
                                                    <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                                                        <label class="form-label">Cidade</label>
                                                        <input type="hidden" name="endereco[cidade][]" value="<?= $value['cidade']; ?>">
                                                        <input type="hidden" name="endereco[uf][]" value="<?= $value['uf']; ?>">
                                                        <input type="text" class="form-control" name="endereco[cidade_completa][]" readonly value="<?= $value['cidade']; ?>/<?= $value['uf']; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                            <?php endif; ?>
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
