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
                        <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                            <label class="form-label">Nome</label>
                            <input type="text" class="form-control" name="nome_fantasia"
                                data-tippy-content="Informe o Nome do Cliente" required>
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                            <label class="form-label">CPF/CNPJ</label>
                            <input type="text" class="form-control" name="cpf_cnpj" data-mask="cnpjCpf"
                                data-tippy-content="Informe o CPF ou CNPJ do Cliente">
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                            <label class="form-label">Data de Nascimento</label>
                            <input type="date" class="form-control" name="data_nascimento"
                                data-tippy-content="Informe a Data de Nascimento do Cliente">
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                            <label class="form-label">Telefone</label>
                            <input type="text" class="form-control" name="telefone" data-mask="telefoneCelular"
                                data-tippy-content="Informe o Número de Telefone do Cliente">
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                            <label class="form-label">Celular</label>
                            <input type="text" class="form-control" name="celular" data-mask="telefoneCelular"
                                data-tippy-content="Informe o Número de Celular do Cliente">
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email"
                                data-tippy-content="Informe o Email do Cliente">
                        </div>
                    </div>
                    <hr class="mb-4">
                    <div class="row">
                        <div class="col-md-2 col-lg-2 col-sm-12 mb-2">
                            <label class="form-label">CEP</label>
                            <input type="text" class="form-control" name="cep" data-mask="cep"
                                data-tippy-content="Informe o Número do CEP do Cliente">
                        </div>
                        <div class="col-md-5 col-lg-5 col-sm-12 mb-2">
                            <label class="form-label">Rua</label>
                            <input type="text" class="form-control" name="rua"
                                data-tippy-content="Informe a Rua do Cliente" readonly>
                        </div>
                        <div class="col-md-2 col-lg-2 col-sm-12 mb-2">
                            <label class="form-label">Número</label>
                            <input type="text" class="form-control" name="numero" data-verificaNumero="true"
                                data-tippy-content="Informe o Número da Casa do Cliente">
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                            <label class="form-label">Bairro</label>
                            <input type="text" class="form-control" name="bairro"
                                data-tippy-content="Informe o Bairro do Cliente" readonly>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                            <label class="form-label">Complemento</label>
                            <input type="text" class="form-control" name="complemento"
                                data-tippy-content="Informe o Complemento do Endereço do Cliente">
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                            <label class="form-label">Cidade</label>
                            <input type="hidden" name="cidade">
                            <input type="hidden" name="uf">
                            <input type="text" class="form-control" name="cidade_completa" data-tippy-c ontent="Info
rme a Cidade do Cliente" readonly>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary text-white"
                        data-action="salvarClienteSimplificado">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Fim :: Modal Cadastrar novo Cliente -->