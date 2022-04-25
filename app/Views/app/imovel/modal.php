<!-- Inicio :: Modal Cadastrar novo Proprietário -->
<div class="modal fade" id="modalCadastrarProprietario" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cadastrar novo Proprietário</h5>
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
                            <input type="text" class="form-control" name="telefone" data-mask="telefoneCelular" data-tippy-content="Informe o Número de Telefone do Proprietário">
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                            <label class="form-label">Celular</label>
                            <input type="text" class="form-control" name="celular" data-mask="telefoneCelular" data-tippy-content="Informe o Número de Celular do Proprietário">
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" data-tippy-content="Informe o Email do Proprietário">
                        </div>
                    </div>
                    <hr class="mb-4">
                    <div class="row">
                        <div class="col-md-2 col-lg-2 col-sm-12 mb-2">
                            <label class="form-label">CEP</label>
                            <input type="text" class="form-control" name="cep" data-mask="cep" data-tippy-content="Informe o Número do CEP do Proprietário">
                        </div>
                        <div class="col-md-5 col-lg-5 col-sm-12 mb-2">
                            <label class="form-label">Rua</label>
                            <input type="text" class="form-control" name="rua" data-tippy-content="Informe a Rua do Proprietário">
                        </div>
                        <div class="col-md-2 col-lg-2 col-sm-12 mb-2">
                            <label class="form-label">Número</label>
                            <input type="text" class="form-control" name="numero" data-verificaNumero="true" data-tippy-content="Informe o Número da Casa do Proprietário">
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                            <label class="form-label">Bairro</label>
                            <input type="text" class="form-control" name="bairro" data-tippy-content="Informe o Bairro do Proprietário">
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                            <label class="form-label">Complemento</label>
                            <input type="text" class="form-control" name="complemento" data-tippy-content="Informe o Complemento do Endereço do Proprietário">
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                            <label class="form-label">Cidade</label>
                            <input type="hidden" name="cidade">
                            <input type="hidden" name="uf">
                            <input type="text" class="form-control" name="cidade_completa" data-tippy-content="Informe a Cidade do Proprietário">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary text-white" data-action="salvarProprietarioSimplificado">Salvar</button>
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
                <li class="list-group-item"><b>Campos com *:</b> É obrigatório o preenchimento;</li>
                <li class="list-group-item"><b>Imagem destaque:</b> Se refere a imagem principal que é exibida no site;</li>
                <li class="list-group-item"><b>Código Referência:</b> Deve ser preenchido com o código que a corretora utiliza para referênciar o imóvel;</li>
                <li class="list-group-item"><b>Categoria:</b> Campo para definir se o imóvel é para venda ou locação;</li>
                <li class="list-group-item"><b>Tipo Imóvel:</b> Campo para definir o tipo do imóvel;</li>
                <li class="list-group-item"><b>Condomínio:</b> Definir se o imóvel ou terreno está localizado em condomínio ou não;</li>
                <li class="list-group-item"><b>Quarto:</b> Quantos quartos que possui o imóvel sem contar com suites;</li>
                <li class="list-group-item"><b>Banheiro(s):</b> Quantos Banheiros possui o imóvel sem contar com suites;</li>
                <li class="list-group-item"><b>Suite(s):</b> Quantas suites;</li>
                <li class="list-group-item"><b>Vagas(s):</b> Quantas vagas de estacionamento;</li>
                <li class="list-group-item"><b>Área Construida:</b> Qual é a área construida;</li>
                <li class="list-group-item"><b>Área Total:</b> Qual é a área total do terreno;</li>
                <li class="list-group-item"><b>Possui Edícula:</b> Definir se o imóvel possui edícula ou não; </li>
                <li class="list-group-item"><b>Valor Venda:</b> Qual é o valor de venda do imóvel;</li>
                <li class="list-group-item"><b>Valor Aluguel:</b> Qual é o valor de venda do imóvel;</li>
                <li class="list-group-item"><b>Proprietário:</b> Associar o imóvel ao proprietário não aparece no site;</li>
                <li class="list-group-item"><b>CEP:</b> CEP do imóvel ou terreno;</li>
                <li class="list-group-item"><b>Rua:</b> Rua do imóvel ou terreno;</li>
                <li class="list-group-item"><b>Número:</b> Número do imóvel ou terreno;</li>
                <li class="list-group-item"><b>Bairro:</b> Bairro do imóvel ou terreno;</li>
                <li class="list-group-item"><b>Complemento:</b> Complemento do imóvel ou terreno;</li>
                <li class="list-group-item"><b>Cidade:</b> CECidadeP do imóvel ou terreno;</li>
                <li class="list-group-item"><b>Mapa:</b>Indique a localizção do imóvel no mapa;</li>
                <li class="list-group-item"><b>Publicar:</b> Definir se o imóvel vai ser listado no site ou não;</li>
                <li class="list-group-item"><b>Destaque:</b> Definir se o imóvel será exibido na seção de destaque no site;</li>
                <li class="list-group-item"><b>Imagens do imóvel:</b> Imagens que vão ser exibidas quando o cliente abrir para ver mais informações;</li>
            </ul>

        </div>
    </div>
</div>
<!-- Fim :: Modal Help-->
