<!-- INICIO :: NAVBAR -->
<?= view('app/pdv/navbar'); ?>
<!-- FIM :: NAVBAR -->

<form class="h-100" method="POST" id="formCarrinho" action="<?= base_url('pdv/store'); ?>">
    <div class="row" style="padding-top: 56px;height: inherit;">

        <!-- Inicio :: Grid do PDV -->
        <div class="col-md-7 col-lg-7 col-sm-12 pe-0 mb-2">
            <div class="app-card bg-transparent">
                <div class="app-card-body">
                    <div class="row py-2 px-4">

                        <!-- Inicio :: Cliente -->
                        <div class="col-md-12 col-lg-12 col-sm-12 mt-2 mb-2" id="sectionCliente">
                            <h6>Cliente</h6>
                            <hr class="mt-1 mb-2">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12 mb-2">
                                    <label>Cliente</label>
                                    <div class="input-group">

                                        <span class="input-group-text cursor" id="btnModalCadastrarCliente" data-tippy-content="Cadastrar novo Cliente" data-tippy-placement="bottom" data-bs-toggle="modal" data-bs-target="#modalCadastrarCliente">
                                            <i class="fas fa-user-plus"></i>
                                        </span>

                                        <span class="input-group-text cursor" data-tippy-content="Buscar Cliente" data-tippy-placement="bottom" data-action="buscarCliente">
                                            <i class="fas fa-search"></i>
                                        </span>

                                        <input type="text" class="form-control" name="codigo_cliente" data-select="buscarCliente" />

                                        <!-- <span class="input-group-text cursor d-none" data-tippy-content="Trocar de Cliente" data-tippy-placement="bottom" data-action="trocarCliente">
                                        <i class="fas fa-user-edit"></i>
                                    </span> -->

                                        <span class="input-group-text cursor" data-tippy-content="Não identificar o Cliente" data-tippy-placement="bottom" data-action="clienteNaoIdentificado">
                                            <i class="fas fa-user-times"></i>
                                        </span>

                                        <span class="input-group-text cursor d-none" data-tippy-content="Identificar o Cliente" data-tippy-placement="bottom" data-action="identificarCliente">
                                            <i class="fas fa-user-plus"></i>
                                        </span>

                                        <span class="input-group-text cursor d-none" id="btnModalDadosCliente" data-tippy-content="Dados do Cliente" data-tippy-placement="bottom" data-bs-toggle="modal" data-bs-target="#modalDadosCliente">
                                            <i class="fas fa-user"></i>
                                        </span>

                                    </div>
                                </div>

                                <!-- <div class="col-md-5 col-lg-5 col-sm-12 mb-2 d-md-block d-lg-block d-none d-lg-block">
                                    <label>CPF</label>
                                    <div class="input-group">
                                        <span class="input-group-text cursor" data-tippy-content="Mudar para CNPJ" data-tippy-placement="bottom" data-action="toggleCpfCnpj">
                                            <i class="fas fa-exchange-alt"></i>
                                        </span>
                                        <input type="text" class="form-control" name="cpf_cnpj" data-mask="cnpjCpf" readonly>
                                        <span class="input-group-text cursor d-none" data-tippy-content="Remover CPF" data-tippy-placement="bottom" data-action="removerCpfCnpj">
                                            <i class="fas fa-times"></i>
                                        </span>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                        <!-- Fim :: Cliente -->

                        <!-- Inicio :: Produto -->
                        <div class="col-md-12 col-lg-12 col-sm-12 mt-4 mb-2 d-none" id="sectionProduto">
                            <h6>Produto</h6>
                            <hr class="mt-1 mb-2">
                            <div class="row">

                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label>Quantidade</label>
                                    <input type="tel" class="form-control" name="produto_quantidade" readonly>
                                </div>

                                <div class="col-md-9 col-lg-9 col-sm-12 mb-2">
                                    <label>Código do Produto (F8)</label>
                                    <div class="input-group">

                                        <span class="input-group-text cursor" data-tippy-content="Buscar Produto" data-tippy-placement="bottom" data-action="buscarProduto">
                                            <i class="fas fa-search"></i>
                                        </span>

                                        <span class="input-group-text cursor" data-bs-toggle="modal" data-bs-target="#modalConsultarProduto" data-tippy-content="Consultar Produto" data-tippy-placement="bottom">
                                            <i class="fas fa-info-circle"></i>
                                        </span>

                                        <input type="text" class="form-control" name="codigo_produto" data-select="buscarProduto" />

                                        <span class="input-group-text cursor" data-tippy-content="Painel Touch" data-tippy-placement="bottom" data-bs-toggle="modal" data-bs-target="#modalPainelTouch">
                                            <i class="fas fa-th"></i>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label>Em Estoque</label>
                                    <input type="text" class="form-control" name="produto_em_estoque" readonly>
                                </div>

                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label>Valor Unitário (R$)</label>
                                    <input type="text" class="form-control" name="produto_valor_unitario" readonly>
                                </div>

                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label>Desconto (R$)</label>
                                    <input type="text" class="form-control" name="produto_desconto" data-mask="dinheiro" readonly>
                                </div>

                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label>Valor Final (R$)</label>
                                    <input type="text" class="form-control" name="produto_valor_final" readonly>
                                </div>

                            </div>
                        </div>
                        <!-- Fim :: Produto -->

                        <!-- Inicio :: Pagamento -->
                        <div class="col-md-12 col-lg-12 col-sm-12 mt-4 mb-2 d-none" id="sectionPagamento">
                            <h6>Pagamento</h6>
                            <hr class="mt-1 mb-2">

                            <div class="row">
                                <div class="col-md-9 col-lg-9 col-sm-12 mb-2">
                                    <label>
                                        Método de Pagamento
                                        <i class="fas fa-info-circle cursor" data-tippy-content="Clique para adicionar uma observação para o fluxo" data-tippy-placement="bottom" data-bs-toggle="modal" data-bs-target="#modalObservacao"></i>
                                    </label>
                                    <input type="hidden" name="observacao">
                                    <input type="text" class="form-control" data-select="buscarCadastroMetodoPagamento" name="codigo_cadastro_metodo_pagamento" value="1">
                                </div>

                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label>Parcelas</label>
                                    <input type="text" class="form-control" value="1" name="parcelas">
                                </div>

                                <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                                    <label>Valor Total (R$)</label>
                                    <input type="text" class="form-control" name="valor_total" readonly>
                                </div>

                                <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                                    <label>Valor de Entrada (R$)</label>
                                    <input type="text" class="form-control" data-mask="dinheiro" name="valor_entrada">
                                </div>

                                <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                                    <label>Troco (R$)</label>
                                    <input type="text" class="form-control" name="valor_troco" readonly>
                                </div>
                            </div>
                        </div>
                        <!-- Fim :: Pagamento -->

                        <!-- Inicio :: Ações -->
                        <div class="col-md-12 col-lg-12 col-sm-12 mt-2 mb-2">
                            <div class="d-flex justify-content-center">
                                <button type="button" class="btn btn-danger text-white mx-1 px-3 d-none" data-tippy-content="Cancelar Venda" data-tippy-placement="bottom" data-action="cancelarVenda">
                                    <i class="fas fa-2x fa-ban mb-1"></i>
                                    <br>
                                    Cancelar
                                </button>
                                <button type="button" class="btn btn-info text-white mx-1 d-none" data-tippy-content="Adicionar no Carrinho" data-tippy-placement="bottom" data-action="adicionarCarrinho">
                                    <i class="fas fa-2x fa-check-circle mb-1"></i>
                                    <br>
                                    Adicionar no Carrinho
                                </button>
                                <button type="button" class="btn btn-warning text-white mx-1 d-none" data-tippy-content="Ir ao Pagamento" data-tippy-placement="bottom" data-action="irPagamento">
                                    <i class="fas fa-2x fa-dollar-sign mb-1"></i>
                                    <br>
                                    Pagamento
                                </button>
                                <button type="button" class="btn btn-primary text-white mx-1 px-4 d-none" data-tippy-content="Finalizar a Venda" data-tippy-placement="bottom" data-action="finalizarVenda">
                                    <i class="fas fa-2x fa-check-circle mb-1"></i>
                                    <br>
                                    Finalizar Venda
                                </button>
                            </div>
                        </div>
                        <!-- Fim :: Ações -->

                    </div>
                </div>
            </div>
        </div>
        <!-- Fim :: Grid do PDV -->

        <!-- Inicio :: Grid do Carrinho -->
        <div class="col-md-5 col-lg-5 col-sm-12 ps-0 mb-2">
            <div class="app-card h-100 shadow-sm mx-lg-0 mx-md-0 mx-sm-4">
                <div class="app-card-header p-3">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-auto">
                            <i class="fas fa-trash cursor ms-3 d-none" data-tippy-content="Esvaziar carrinho" data-tippy-placement="bottom" data-action="esvaziarCarrinho"></i>
                        </div>
                        <div class="col-auto">
                            <h4 class="app-card-title">Carrinho</h4>
                        </div>
                        <div class="col-auto">
                            <!-- <div class="card-header-action">
                                <i class="fas fa-ellipsis-h cursor me-3" data-tippy-content="Opções" data-tippy-placement="bottom"></i>
                            </div> -->
                        </div>
                    </div>
                </div>
                <div class="app-card-body" id="bodyCarrinho">

                    <div class="d-flex p-4" style="height: calc(100% - 56px);" id="carrinhoVazio">
                        <div class="col align-self-center text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="128" height="128" fill="currentColor" class="bi bi-cart4 align-self-center" viewBox="0 0 16 16">
                                <path d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5zM3.14 5l.5 2H5V5H3.14zM6 5v2h2V5H6zm3 0v2h2V5H9zm3 0v2h1.36l.5-2H12zm1.11 3H12v2h.61l.5-2zM11 8H9v2h2V8zM8 8H6v2h2V8zM5 8H3.89l.5 2H5V8zm0 5a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0z" />
                            </svg>
                            <p>O carrinho está vazio.</p>
                        </div>
                    </div>

                    <div class="d-none" id="carrinhoComProdutos">

                        <!-- Inicio :: Itens do Carrinho -->
                        <div class="row" style="height: calc(100% - 279px);overflow: scroll;">
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <div class="app-card h-100 ">
                                    <div class="app-card-body p-4">
                                        <div class="pt-2 px-2 pb-0 border rounded">

                                            <!-- Inicio :: Titulo dos Itens do Carrinho -->
                                            <div class="item">
                                                <div class="row align-items-center pt-2 px-2 pb-0">
                                                    <div class="col">
                                                        <h6>Descrição</h6>
                                                    </div>
                                                    <div class="col-auto">
                                                        <h6>Qtde</h6>
                                                    </div>
                                                    <div class="col-auto mx-3">
                                                        <h6>Valor Un.</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr class="mt-1 mb-2">
                                            <!-- Fim :: Titulo dos Itens do Carrinho -->

                                            <div id="listagemItens"></div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fim :: Itens do Carrinho -->

                        <!-- Inicio :: Totais -->
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12 ">
                                <div class="app-card h-100 ">
                                    <div class="app-card-body p-4">
                                        <div class="pt-2 px-2 pb-0">
                                            <hr class="mt-1 mb-2">
                                            <div class="item">
                                                <div class="row align-items-between px-2 pb-0">
                                                    <div class="col">
                                                        <h6 class="text-muted">Subtotal <small id="subTotalItens"></small></h6>
                                                    </div>
                                                    <div class="col-auto">
                                                        <h6 id="subTotalValor"></h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr class="mt-0 mb-1">
                                            <div class="item">
                                                <div class="row align-items-between px-2 pb-0">
                                                    <div class="col">
                                                        <h6 class="text-muted">Descontos</h6>
                                                    </div>
                                                    <div class="col-auto">
                                                        <h6 id="desconto"></h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr class="mt-0 mb-1">
                                            <div class="item">
                                                <div class="row align-items-between px-2 pb-0">
                                                    <div class="col">
                                                        <h6 class="text-muted">Total</h6>
                                                    </div>
                                                    <div class="col-auto">
                                                        <h6 id="total"></h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="item d-none" id="selectorPago">
                                                <div class="row align-items-between px-2 pb-0">
                                                    <div class="col">
                                                        <h6 class="text-muted">Pago</h6>
                                                    </div>
                                                    <div class="col-auto">
                                                        <h6>R$ 90,00</h6>
                                                    </div>
                                                </div>
                                                <hr class="mt-0 mb-1">
                                            </div>
                                            <div class="item d-none" id="selectorTroco">
                                                <div class="row align-items-between px-2 pb-0">
                                                    <div class="col">
                                                        <h6 class="text-muted">Troco</h6>
                                                    </div>
                                                    <div class="col-auto">
                                                        <h6>R$ 0,00</h6>
                                                    </div>
                                                </div>
                                                <hr class="mt-0 mb-1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fim :: Totais -->

                    </div>

                </div>
            </div>
        </div>
        <!-- Fim :: Grid do Carrinho -->

    </div>
</form>
