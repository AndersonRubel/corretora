<!-- Inicio :: Modal Cadastrar novo Cliente -->
<div class="modal fade" id="modalCadastrarCliente" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cadastrar novo Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="row">
                        <div class="row mb-2">
                            <div class="col-md-3 col-lg-3 col-sm-12">
                                <label class="form-label">Tipo de Pessoa</label>
                                <select class="form-control" name="tipo_pessoa" id="tipoPessoa" required>
                                    <option value="1" <?= old('tipo_pessoa') == 1 ? 'selected' : '' ?>>Pessoa Física
                                    </option>
                                    <option value="2" <?= old('tipo_pessoa') == 2 ? 'selected' : '' ?>>Pessoa
                                        Jurídica</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12 mb-2" id="razaoSocial">
                            <label class="form-label">Razão Social</label>
                            <input type="text" class="form-control" name="razao_social" required value="<?= old('razao_social'); ?>">
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12 mb-2" id="nomeFantasia">
                            <label class="form-label">Nome Fantasia</label>
                            <input type="text" class="form-control" name="nome_fantasia" required value="<?= old('nome_fantasia'); ?>">
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2 d-none" id="cnpj">
                            <label class="form-label">CNPJ</label>
                            <input type="text" class="form-control" name="cnpj" data-mask="cnpj" required value="<?= old('cnpj'); ?>">
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2" id="cpf">
                            <label class="form-label">CPF</label>
                            <input type="text" class="form-control" name="cpf" data-mask="cpf" required value="<?= old('cpf'); ?>">
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                            <label class="form-label">Telefone</label>
                            <input type="text" class="form-control" name="telefone" data-mask="telefoneCelular" data-tippy-content="Informe o Número de Telefone do Cliente">
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                            <label class="form-label">Celular</label>
                            <input type="text" class="form-control" name="celular" data-mask="telefoneCelular" data-tippy-content="Informe o Número de Celular do Cliente">
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" data-tippy-content="Informe o Email do Cliente">
                        </div>
                    </div>
                    <hr class="mb-4">
                    <div class="row">
                        <div class="col-md-2 col-lg-2 col-sm-12 mb-2">
                            <label class="form-label">CEP</label>
                            <input type="text" class="form-control" name="cep" data-mask="cep" data-tippy-content="Informe o Número do CEP do Cliente">
                        </div>
                        <div class="col-md-5 col-lg-5 col-sm-12 mb-2">
                            <label class="form-label">Rua</label>
                            <input type="text" class="form-control" name="rua" data-tippy-content="Informe a Rua do Cliente" readonly>
                        </div>
                        <div class="col-md-2 col-lg-2 col-sm-12 mb-2">
                            <label class="form-label">Número</label>
                            <input type="text" class="form-control" name="numero" data-verificaNumero="true" data-tippy-content="Informe o Número da Casa do Cliente">
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                            <label class="form-label">Bairro</label>
                            <input type="text" class="form-control" name="bairro" data-tippy-content="Informe o Bairro do Cliente" readonly>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                            <label class="form-label">Complemento</label>
                            <input type="text" class="form-control" name="complemento" data-tippy-content="Informe o Complemento do Endereço do Cliente">
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                            <label class="form-label">Cidade</label>
                            <input type="hidden" name="cidade">
                            <input type="hidden" name="uf">
                            <input type="text" class="form-control" name="cidade_completa" data-tippy-content="Informe a Cidade do Cliente" readonly>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary text-white" data-action="salvarClienteSimplificado">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Fim :: Modal Cadastrar novo Cliente -->
<!-- Inicio :: Modal Help -->
<div class="modal fade" id="modalHelp" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajuda </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <ul class="list-group">
                <li class="list-group-item"><b>Campos com *:</b> É obrigatórios o preenchimento</li>
                <li class="list-group-item"><b>Buscar Imóvel:</b> Selecionar o imóvel para reserva;</li>
                <li class="list-group-item"><b>Buscar Cliente :</b> Buscar um cliente ou adicionar clicando no botão ao lado do campo;</li>
                <li class="list-group-item"><b>Descrição: Alguma anotação referente a reserva;</b></li>
                <li class="list-group-item"><b>Data de Início:</b> Data início da reserva deve ser menor que a data fim;</li>
                <li class="list-group-item"><b>Data Fim:</b> Data fim da reserva deve ser maior ou igual que a data início;</li>
            </ul>

        </div>
    </div>
</div>
<!-- Fim :: Modal Help-->
