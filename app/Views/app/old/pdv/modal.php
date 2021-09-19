<!-- Inicio :: Modal Dados do Cliente -->
<div class="modal fade" id="modalDadosCliente" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dados do Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 col-lg-6 col-sm-12 mb-2" id="selectorNome">
                        <label class="form-label">Nome</label>
                        <input type="text" class="form-control" readonly>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-12 mb-2" id="selectorCpfCnpj">
                        <label class="form-label">CNPJ</label>
                        <input type="text" class="form-control" data-mask="cnpjCpf" readonly>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-12 mb-2" id="selectorNascimento">
                        <label class="form-label">Data de Nascimento</label>
                        <input type="date" class="form-control" readonly>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-12 mb-2" id="selectorTelefone">
                        <label class="form-label">Telefone</label>
                        <input type="text" class="form-control" data-mask="telefoneCelular" readonly>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-12 mb-2" id="selectorCelular">
                        <label class="form-label">Celular</label>
                        <input type="text" class="form-control" data-mask="telefoneCelular" readonly>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-12 mb-2" id="selectorEmail">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" readonly>
                    </div>

                    <div class="col-md-12 col-lg-12 col-sm-12 mb-4" id="selectorObservacao">
                        <label class="form-label">Observação</label>
                        <textarea class="form-control" name="observacao" rows="2" readonly></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
<!-- Fim :: Modal Dados do Cliente -->

<!-- Inicio :: Modal Cadastrar novo Cliente -->
<div class="modal fade" id="modalCadastrarCliente" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cadastrar novo Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?= base_url("cliente/storeSimplificado"); ?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                            <label class="form-label">Nome</label>
                            <input type="text" class="form-control" name="nome_fantasia" required>
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                            <label class="form-label">CPF/CNPJ</label>
                            <input type="text" class="form-control" name="cpf_cnpj" data-mask="cnpjCpf">
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                            <label class="form-label">Data de Nascimento</label>
                            <input type="date" class="form-control" name="data_nascimento">
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                            <label class="form-label">Telefone</label>
                            <input type="text" class="form-control" name="telefone" data-mask="telefoneCelular">
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                            <label class="form-label">Celular</label>
                            <input type="text" class="form-control" name="celular" data-mask="telefoneCelular">
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email">
                        </div>
                    </div>
                    <hr class="mb-4">
                    <div class="row">
                        <div class="col-md-2 col-lg-2 col-sm-12 mb-2">
                            <label class="form-label">CEP</label>
                            <input type="text" class="form-control" name="cep" data-mask="cep">
                        </div>
                        <div class="col-md-5 col-lg-5 col-sm-12 mb-2">
                            <label class="form-label">Rua</label>
                            <input type="text" class="form-control" name="rua" readonly>
                        </div>
                        <div class="col-md-2 col-lg-2 col-sm-12 mb-2">
                            <label class="form-label">Número</label>
                            <input type="text" class="form-control" name="numero" data-verificaNumero="true">
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                            <label class="form-label">Bairro</label>
                            <input type="text" class="form-control" name="bairro" readonly>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                            <label class="form-label">Complemento</label>
                            <input type="text" class="form-control" name="complemento">
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                            <label class="form-label">Cidade</label>
                            <input type="hidden" name="cidade">
                            <input type="hidden" name="uf">
                            <input type="text" class="form-control" name="cidade_completa" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary text-white">Salvar</button>
                </div>


            </form>
        </div>
    </div>
</div>
<!-- Fim :: Modal Cadastrar novo Cliente -->


<!-- Inicio :: Modal Painel Touch -->
<div class="modal fade" id="modalPainelTouch" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Painel Touch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
<!-- Fim :: Modal Painel Touch -->
