<div class="app-wrapper">
    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">

            <!-- Inicio :: Titulo e Botões -->
            <div class="row g-3 align-items-center justify-content-between">

                <div class="col-auto">
                    <h1 class="app-page-title mb-0">Alterar Empresa</h1>
                </div>

                <div class="col-auto">
                    <div class="page-utilities">
                        <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                            <div class="col-auto">
                                <a class="btn btn-secondary" href="<?= base_url("empresa"); ?>">Voltar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="mb-4">
            <!-- Fim :: Titulo e Botões -->

            <!-- Inicio :: Formulário -->
            <form method="POST" action="<?= base_url("empresa/update/{$empresa['uuid_empresa']}"); ?>">

                <!-- Inicio :: Cadastro Básico -->
                <div class="card">
                    <div class="card-header fw-bold">Dados Básicos</div>
                    <div class="app-card shadow-sm p-4">
                        <div class="app-card-body">

                            <input type="hidden" id="tipoPessoa" value="<?= old('tipo_pessoa', $empresa['tipo_pessoa']); ?>">

                            <div class="row">
                                <div class="col-md-6 col-lg-6 col-sm-12 mb-2" id="razaoSocial">
                                    <label class="form-label">Razão Social</label>
                                    <input type="text" class="form-control" name="razao_social" required value="<?= old('razao_social', $empresa['razao_social']); ?>">
                                </div>
                                <div class="col-md-6 col-lg-6 col-sm-12 mb-2" id="nomeFantasia">
                                    <label class="form-label">Nome Fantasia</label>
                                    <input type="text" class="form-control" name="nome_fantasia" required value="<?= old('nome_fantasia', $empresa['nome_fantasia']); ?>">
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2" id="cpfCnpj">
                                    <label class="form-label">CNPJ</label>
                                    <input type="text" class="form-control" name="cpf_cnpj" data-mask="cnpjCpf" required value="<?= old('cpf_cnpj', $empresa['cpf_cnpj']); ?>">
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label class="form-label">Telefone</label>
                                    <input type="text" class="form-control" name="telefone" data-mask="telefoneCelular" value="<?= old('telefone', $empresa['telefone']); ?>">
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label class="form-label">Telefone Adicional</label>
                                    <input type="text" class="form-control" name="telefone_adicional" data-mask="telefoneCelular" value="<?= old('telefone_adicional', $empresa['telefone_adicional']); ?>">
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label class="form-label">Celular</label>
                                    <input type="text" class="form-control" name="celular" data-mask="telefoneCelular" value="<?= old('celular', $empresa['celular']); ?>">
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" required value="<?= old('email', $empresa['email']); ?>">
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label class="form-label">Email do Financeiro</label>
                                    <input type="email" class="form-control" name="email_financeiro" value="<?= old('email_financeiro', $empresa['email_financeiro']); ?>">
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12">
                                    <label class="form-label">Dia do Pagamento</label>
                                    <select class="form-control" name="dia_pagamento" required>
                                        <option value="5" selected>5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                    </select>
                                </div>

                                <hr class="mb-3 mt-3">

                                <div class="col-md-2 col-lg-2 col-sm-12 mb-2">
                                    <label class="form-label">CEP</label>
                                    <input type="text" class="form-control" name="cep" data-mask="cep" value="<?= old('cep', !empty($empresa['endereco']['cep']) ? $empresa['endereco']['cep'] : ''); ?>">
                                </div>
                                <div class="col-md-5 col-lg-5 col-sm-12 mb-2">
                                    <label class="form-label">Rua</label>
                                    <input type="text" class="form-control" name="rua" readonly value="<?= old('rua',!empty($empresa['endereco']['rua']) ? $empresa['endereco']['rua'] : ''); ?>">
                                </div>
                                <div class="col-md-2 col-lg-2 col-sm-12 mb-2">
                                    <label class="form-label">Número</label>
                                    <input type="text" class="form-control" name="numero" data-verificaNumero="true" value="<?= old('numero', !empty($empresa['endereco']['numero']) ? $empresa['endereco']['numero'] : ''); ?>">
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label class="form-label">Bairro</label>
                                    <input type="text" class="form-control" name="bairro" readonly value="<?= old('bairro', !empty($empresa['endereco']['bairro']) ? $empresa['endereco']['bairro'] : ''); ?>">
                                </div>
                                <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                                    <label class="form-label">Complemento</label>
                                    <input type="text" class="form-control" name="complemento" value="<?= old('complemento', !empty($empresa['endereco']['complemento']) ? $empresa['endereco']['complemento'] : ''); ?>">
                                </div>
                                <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                                    <label class="form-label">Cidade</label>
                                    <input type="hidden" name="cidade" value="<?= old('cidade', !empty($empresa['endereco']['cidade']) ? $empresa['endereco']['cidade'] : ''); ?>">
                                    <input type="hidden" name="uf" value="<?= old('uf', !empty($empresa['endereco']['uf']) ? $empresa['endereco']['uf'] : ''); ?>">
                                    <input type="text" class="form-control" name="cidade_completa" readonly value="<?= old('cidade', !empty($empresa['endereco']['cidade']) ? $empresa['endereco']['cidade'] : ''); ?>/<?= old('uf', !empty($empresa['endereco']['uf']) ? $empresa['endereco']['uf'] : ''); ?>">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- Fim :: Cadastro Básico -->

                <?php if ($empresa['tipo_pessoa'] == 2) : ?>
                    <!-- Inicio :: Dados Fiscais -->
                    <div class="card mt-4" id="blocoFiscal">
                        <div class="card-header fw-bold">Dados Fiscais</div>
                        <div class="app-card shadow-sm p-4">
                            <div class="app-card-body">
                                <div class="row mb-2">
                                    <div class="col-md-3 col-lg-3 col-sm-12">
                                        <label class="form-label">Possui Inscrição Estadual?</label>
                                        <select class="form-control" name="possui_inscricao_estadual">
                                            <option <?= !empty($empresa['configuracao_nota_fiscal']) ? ($empresa['configuracao_nota_fiscal']['possui_inscricao_estadual'] == '' ? 'selected' : '') : ''; ?> value="">Selecione</option>
                                            <option <?= !empty($empresa['configuracao_nota_fiscal']) ? ($empresa['configuracao_nota_fiscal']['possui_inscricao_estadual'] == 'sim' ? 'selected' : '') : ''; ?> value="sim">Sim</option>
                                            <option <?= !empty($empresa['configuracao_nota_fiscal']) ? ($empresa['configuracao_nota_fiscal']['possui_inscricao_estadual'] == 'nao' ? 'selected' : '') : ''; ?> value="nao">Não</option>
                                            <option <?= !empty($empresa['configuracao_nota_fiscal']) ? ($empresa['configuracao_nota_fiscal']['possui_inscricao_estadual'] == 'isento' ? 'selected' : '') : ''; ?> value="isento">Isento</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-sm-12 mb-2 d-none" id="fieldInscricaoEstadual">
                                        <label class="form-label">Inscrição Estadual</label>
                                        <input type="text" class="form-control" name="inscricao_estadual" value="<?= old('inscricao_estadual', !empty($empresa['configuracao_nota_fiscal']) ? $empresa['configuracao_nota_fiscal']['inscricao_estadual'] : ''); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Fim :: Dados Fiscais -->
                <?php endif; ?>

                <!-- Inicio :: Cadastro do Responsável -->
                <?php if ($empresa['tipo_pessoa'] == 2) : ?>
                    <div class="card mt-4">
                        <div class="card-header fw-bold">Dados do Responsável</div>
                        <div class="app-card shadow-sm p-4">
                            <div class="app-card-body">

                                <div class="row">
                                    <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                        <label class="form-label">Nome</label>
                                        <input type="text" class="form-control" name="responsavel_nome" value="<?= old('responsavel_nome', !empty($empresa['responsavel']) ? $empresa['responsavel']['nome'] : ''); ?>">
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                        <label class="form-label">CPF</label>
                                        <input type="text" class="form-control" name="responsavel_cpf" data-mask="cpf" value="<?= old('responsavel_cpf', !empty($empresa['responsavel']) ? $empresa['responsavel']['cpf'] : ''); ?>">
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                        <label class="form-label">Data de Nascimento</label>
                                        <input type="date" class="form-control" name="responsavel_data_nascimento" value="<?= old('responsavel_data_nascimento', !empty($empresa['responsavel']) ? $empresa['responsavel']['data_nascimento'] : ''); ?>">
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                        <label class="form-label">RG</label>
                                        <input type="text" class="form-control" name="responsavel_rg" data-verificaNumero="true" value="<?= old('responsavel_rg', !empty($empresa['responsavel']) ? $empresa['responsavel']['rg'] : ''); ?>">
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                        <label class="form-label">Orgão Emissor</label>
                                        <input type="text" class="form-control" name="responsavel_rg_orgao_emissor" value="<?= old('responsavel_rg_orgao_emissor', !empty($empresa['responsavel']) ? $empresa['responsavel']['rg_orgao_emissor'] : ''); ?>">
                                    </div>

                                    <hr class="mb-3 mt-3">

                                    <div class="col-md-2 col-lg-2 col-sm-12 mb-2">
                                        <label class="form-label">CEP</label>
                                        <input type="text" class="form-control" name="responsavel_cep" data-mask="cep" required value="<?= old('responsavel_cep', !empty($empresa['responsavel']) ? $empresa['responsavel']['cep'] : ''); ?>">
                                    </div>
                                    <div class="col-md-5 col-lg-5 col-sm-12 mb-2">
                                        <label class="form-label">Rua</label>
                                        <input type="text" class="form-control" name="responsavel_rua" readonly required value="<?= old('responsavel_rua', !empty($empresa['responsavel']) ?  $empresa['responsavel']['rua'] : ''); ?>">
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-12 mb-2">
                                        <label class="form-label">Número</label>
                                        <input type="text" class="form-control" name="responsavel_numero" required data-verificaNumero="true" value="<?= old('responsavel_numero', !empty($empresa['responsavel']) ? $empresa['responsavel']['numero'] : ''); ?>">
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                        <label class="form-label">Bairro</label>
                                        <input type="text" class="form-control" name="responsavel_bairro" readonly required value="<?= old('responsavel_bairro', !empty($empresa['responsavel']) ? $empresa['responsavel']['bairro'] : ''); ?>">
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                                        <label class="form-label">Complemento</label>
                                        <input type="text" class="form-control" name="responsavel_complemento" required value="<?= old('responsavel_complemento', !empty($empresa['responsavel']) ? $empresa['responsavel']['complemento'] : ''); ?>">
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                                        <label class="form-label">Cidade</label>
                                        <input type="hidden" name="responsavel_cidade" required value="<?= old('responsavel_cidade', !empty($empresa['responsavel']) ? $empresa['responsavel']['cidade'] : ''); ?>">
                                        <input type="hidden" name="responsavel_uf" required value="<?= old('responsavel_uf', !empty($empresa['responsavel']) ? $empresa['responsavel']['uf'] : ''); ?>">
                                        <input type="text" class="form-control" name="responsavel_cidade_completa" readonly required value="<?= old('responsavel_cidade', !empty($empresa['responsavel']) ? $empresa['responsavel']['cidade'] : ''); ?>/<?= old('responsavel_uf', !empty($empresa['responsavel']) ? $empresa['responsavel']['uf'] : ''); ?>">
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <!-- Fim :: Cadastro do Responsável -->

                <div class="d-flex justify-content-end mt-2">
                    <button type="submit" class="btn app-btn-primary">Salvar</button>
                </div>

            </form>
            <!-- Fim :: Formulário -->


        </div>
    </div>
</div>
