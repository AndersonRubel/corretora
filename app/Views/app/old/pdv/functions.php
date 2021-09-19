<script>
    const select2PdvFunctions = {
        init: () => {
            select2PdvFunctions.buscarCliente();
            select2PdvFunctions.buscarProduto();
            select2PdvFunctions.buscarMetodoPagamento();
        },
        buscarCliente: () => {
            let elementSelect2 = $("[data-select='buscarCliente']");
            let url = `${BASEURL}/${ROUTE}/backendCall/selectCliente`;
            elementSelect2.select2({
                placeholder: "Pesquise o cliente aqui",
                allowClear: false,
                multiple: false,
                quietMillis: 2000,
                minimumInputLength: 3,
                initSelection: function(element, callback) {
                    $.ajax({
                        url: url,
                        dataType: "json",
                        type: 'POST',
                        params: {
                            contentType: "application/json; charset=utf-8",
                        },
                        data: {
                            termo: $(element).val(),
                            page: 1
                        },
                        success: (data) => callback(data.itens[0])
                    })
                },
                ajax: {
                    url: url,
                    dataType: 'json',
                    type: 'POST',
                    data: (term, page) => {
                        return {
                            termo: term,
                            page: page,
                        };
                    },
                    results: (data, page) => {
                        if (page == 1) {
                            $(elementSelect2).data('count', data.count);
                        }
                        return {
                            results: data.itens,
                            more: (page * 30) < $(elementSelect2).data('count')
                        };
                    }
                },
                formatResult: (data) => data.text,
                formatSelection: (data) => {
                    $("input[name='cpf_cnpj']").val('');

                    if (data.cpf_cnpj) {
                        if (data.cpf_cnpj.length == 11) {
                            //
                        } else if (data.cpf_cnpj.length == 14) {
                            $("[data-action='toggleCpfCnpj']").click();
                        }
                    }

                    $("input[name='cpf_cnpj']").prop('readonly', false).val(data.cpf_cnpj).keydown();
                    $("[data-action='removerCpfCnpj']").removeClass('d-none');
                    $("#btnModalDadosCliente").removeClass('d-none');

                    // Exibe a sessão de produtos
                    $("#sectionProduto").removeClass('d-none');

                    return data.text;
                }
            });
        },
        buscarProduto: () => {
            let elementSelect2 = $("[data-select='buscarProduto']");
            let url = `${BASEURL}/${ROUTE}/backendCall/selectProduto`;
            elementSelect2.select2({
                placeholder: "Pesquise o produto aqui",
                allowClear: false,
                multiple: false,
                quietMillis: 2000,
                minimumInputLength: 3,
                initSelection: function(element, callback) {
                    $.ajax({
                        url: url,
                        dataType: "json",
                        type: 'POST',
                        params: {
                            contentType: "application/json; charset=utf-8",
                        },
                        data: {
                            termo: $(element).val(),
                            page: 1
                        },
                        success: (data) => callback(data.itens[0])
                    })
                },
                ajax: {
                    url: url,
                    dataType: 'json',
                    type: 'POST',
                    data: (term, page) => {
                        return {
                            termo: term,
                            page: page,
                        };
                    },
                    results: (data, page) => {
                        if (page == 1) {
                            $(elementSelect2).data('count', data.count);
                        }
                        return {
                            results: data.itens,
                            more: (page * 30) < $(elementSelect2).data('count')
                        };
                    }
                },
                formatResult: (data) => data.text,
                formatSelection: (data) => {
                    $("#sectionProduto input").val('');
                    $("#sectionProduto input[name='produto_quantidade']").prop('readonly', false).val(1);
                    $("#sectionProduto input[name='produto_em_estoque']").val(data.estoque_atual);
                    $("#sectionProduto input[name='produto_valor_unitario']").val(convertFunctions.intToReal(data.valor));
                    $("#sectionProduto input[name='produto_desconto']").prop('readonly', false).val(convertFunctions.intToReal(0));
                    $("#sectionProduto input[name='produto_valor_final']").val(convertFunctions.intToReal(data.valor));
                    $("[data-action='adicionarCarrinho']").removeClass('d-none')
                    return data.text;
                }
            });
        },
        buscarMetodoPagamento: () => {
            let elementSelect2 = $("[data-select='buscarCadastroMetodoPagamento']");
            let url = `${BASEURL}/${ROUTE}/backendCall/selectCadastroMetodoPagamento`;
            elementSelect2.select2({
                placeholder: "Selecione...",
                allowClear: false,
                multiple: false,
                quietMillis: 2000,
                initSelection: function(element, callback) {
                    $.ajax({
                        url: url,
                        dataType: "json",
                        type: 'POST',
                        params: {
                            contentType: "application/json; charset=utf-8",
                        },
                        data: {
                            termo: $(element).val(),
                            page: 1
                        },
                        success: (data) => callback(data.itens[0])
                    })
                },
                ajax: {
                    url: url,
                    dataType: 'json',
                    type: 'POST',
                    data: (term, page) => {
                        return {
                            termo: term,
                            page: page,
                        };
                    },
                    results: (data, page) => {
                        if (page == 1) {
                            $(elementSelect2).data('count', data.count);
                        }
                        return {
                            results: data.itens,
                            more: (page * 30) < $(elementSelect2).data('count')
                        };
                    }
                },
                formatResult: (data) => data.text,
                formatSelection: (data) => data.text
            });
        },
    }

    const pdvFunctions = {
        init: () => {
            pdvFunctions.hideSidebar();
            pdvFunctions.listenerEvents();
            pdvFunctions.listenerCliente();
            pdvFunctions.listenerProduto();
            pdvFunctions.listenerCarrinho();
            pdvFunctions.listenerVenda();
            pdvFunctions.listenerModalPainelTouch();
        },
        hideSidebar: () => {
            setTimeout(() => {
                $("#app-sidepanel").addClass('sidepanel-hidden').removeClass('sidepanel-visible');
            }, 50);
        },
        listenerEvents: () => {
            // Bloqueia o click direito
            document.addEventListener('contextmenu', e => e.preventDefault());

            // Bloqueia a seleção de texto
            document.addEventListener('selectstart', e => e.preventDefault());
        },
        listenerCliente: () => {
            // Ao abrir a Modal
            $(document).on('shown.bs.modal', '#modalDadosCliente', async function() {
                if ($("input[name='codigo_cliente']").val() !== '') {
                    await appFunctions.backendCall('POST', `${ROUTE}/backendCall/selectCliente`, {
                        termo: $("input[name='codigo_cliente']").val(),
                        page: 1,
                        modo: 'completo'
                    }).then(res => {
                        if (res) {
                            el = res.itens[0];
                            $("#modalDadosCliente #selectorNome input").val(el.text);
                            $("#modalDadosCliente #selectorCpfCnpj input").val(el.cpf_cnpj).keydown();
                            $("#modalDadosCliente #selectorNascimento input").val(el.data_nascimento);
                            $("#modalDadosCliente #selectorTelefone input").val(el.telefone).keydown();
                            $("#modalDadosCliente #selectorCelular input").val(el.celular).keydown();
                            $("#modalDadosCliente #selectorEmail input").val(el.email);
                            $("#modalDadosCliente #selectorObservacao textarea").val(el.observacao);
                        }
                    }).catch(err => notificationFunctions.toastSmall(err.textStatus, err.mensagem));
                } else {
                    notificationFunctions.toastSmall('warning', 'É necessário selecionar um cliente.');
                    $(this).modal('hide');
                    $("[data-action='buscarCliente']").click();
                }
            });

            // Abre o Campo de Pesquisa de Cliente ao clicar no icone
            $(document).on('click', "[data-action='buscarCliente']", function() {
                $("[data-select='buscarCliente']").select2('open');
            });

            // Se não quiser identificar o Cliente, segue o fluxo
            $(document).on('click', "[data-action='identificarCliente']", function() {
                // Habilita o Campo de cliente
                $("input[name='codigo_cliente']").prop('disabled', false).select2('val', '');
                $("input[name='cpf_cnpj']").prop('readonly', false).val('');

                // Oculta esse botão, e mostra o de não identificar cliente
                $(this).addClass('d-none');
                $("[data-action='clienteNaoIdentificado']").removeClass('d-none');
            });

            // Se quiser identificar o Cliente, habilita o campo novamente
            $(document).on('click', "[data-action='clienteNaoIdentificado']", function() {
                // Desabilita o Campo de cliente
                $("input[name='codigo_cliente']").prop('disabled', true).select2('val', '');
                $("input[name='cpf_cnpj']").prop('readonly', true).val('');

                // Oculta esse botão, e mostra o de identificar cliente
                $(this).addClass('d-none');
                $("[data-action='identificarCliente']").removeClass('d-none');
                $("#btnModalDadosCliente").addClass('d-none');

                // Exibe a sessao de produtos, que é o proximo fluxo
                $("#sectionProduto").removeClass('d-none');
            });

            // Limpa o Campo CPF/CNPJ
            $(document).on('click', "[data-action='removerCpfCnpj']", function() {
                $("input[name='cpf_cnpj']").val('');
                $(this).addClass('d-none');
            });

            // Alterna o Campo CPF/CNPJ
            $(document).on('click', "[data-action='toggleCpfCnpj']", function() {
                $("[data-action='removerCpfCnpj']").click();
                let label = $(this).parent().parent().find('label');

                if ($(this).attr('data-tippy-content').includes('CPF')) {
                    label.text('CNPJ');
                    $(this).attr('data-tippy-content', 'Mudar para CNPJ');
                } else {
                    label.text('CPF');
                    $(this).attr('data-tippy-content', 'Mudar para CPF');
                }

                appFunctions.tooltip();
            });

            // Ao fechar a Modal limpa os campos
            $(document).on('hide.bs.modal', '#modalCadastrarCliente', async function() {
                $("#modalCadastrarCliente input").val('');
            });

            $(document).on('keyup', "#modalCadastrarCliente [data-mask='cep']", function() {
                const cep = $(this).val();
                if (cep.length >= 9) {
                    appFunctions.buscarCep(cep).then(
                        (retorno) => {
                            if (retorno) {
                                $("#modalCadastrarCliente input[name='rua']").val(retorno.street);
                                $("#modalCadastrarCliente input[name='bairro']").val(retorno.neighborhood);
                                $("#modalCadastrarCliente input[name='cidade']").val(retorno.city);
                                $("#modalCadastrarCliente input[name='cidade_completa']").val(`${retorno.city}/${retorno.state}`);
                                $("#modalCadastrarCliente input[name='uf']").val(retorno.state);
                                $("#modalCadastrarCliente input[name='numero']").val('');
                                $("#modalCadastrarCliente input[name='numero']").focus();
                            }
                        }
                    )
                }
            });

        },
        listenerProduto: () => {

            // Abre o Campo de Pesquisa de Produto ao clicar no icone
            $(document).on('click', "[data-action='buscarProduto']", function() {
                $("[data-select='buscarProduto']").select2('open');
            });

            // Calcula o valor total do produto
            $(document).on('keyup', "input[name='produto_valor_unitario'], input[name='produto_quantidade'], input[name='produto_desconto']", function(e) {
                valorTotal = 0;

                quantidade = $("input[name='produto_quantidade']").val() || 1;
                valorProduto = convertFunctions.onlyNumber($("input[name='produto_valor_unitario']").val()) || 0;
                valorDesconto = convertFunctions.onlyNumber($("input[name='produto_desconto']").val()) || 0;

                valorTotal = (valorProduto * quantidade) - valorDesconto;

                $("input[name='produto_valor_final']").val(convertFunctions.intToReal(valorTotal));
            });
        },
        listenerModalPainelTouch: () => {},
        listenerCarrinho: () => {

            // Cancelar compra
            $(document).on('click', "[data-action='cancelarVenda']", function(e) {
                notificationFunctions.popupConfirm('Atenção', 'Deseja realmente cancelar a venda?', 'warning').then(
                    (result) => {
                        if (result.value) {
                            window.location.reload();
                        }
                    }
                );
            });

            // Ir ao Pagamento
            $(document).on('click', "[data-action='irPagamento']", function(e) {
                // Mostra a sessao de pagamento
                $("#sectionPagamento").removeClass('d-none');
                $(this).addClass('d-none');
                $("[data-action='finalizarVenda']").removeClass('d-none');
            });

            // Esvaziar o carrinho
            $(document).on('click', "[data-action='esvaziarCarrinho']", function(e) {
                notificationFunctions.popupConfirm('Atenção', 'Deseja realmente esvaziar o carrinho?', 'warning').then(
                    (result) => {
                        if (result.value) {
                            $("#bodyCarrinho #carrinhoComProdutos").addClass('d-none');
                            $("#bodyCarrinho #carrinhoVazio").removeClass('d-none');
                            $("#bodyCarrinho #listagemItens").html('');
                            $(this).addClass('d-none');
                            pdvFunctions.recalculaCarrinho();
                        }
                    }
                );
            });

            // Adicionar produto no Carrinho
            $(document).on('click', "[data-action='adicionarCarrinho']", function(e) {
                // Valida a Quantidade
                if ($("input[name='produto_quantidade']").val() < "1") {
                    notificationFunctions.toastSmall('error', 'A quantidade deve ser igual ou maior que 1.');
                    $("input[name='produto_quantidade']").focus();
                    return;
                }

                // Valida se a quantidade não é maior que o estoque disponível no momento
                if (parseInt($("input[name='produto_quantidade']").val()) > parseInt($("input[name='produto_em_estoque']").val())) {
                    notificationFunctions.toastSmall('error', 'Esse produto não possui estoque suficiente.');
                    $("input[name='produto_quantidade']").focus();
                    return;
                }

                // Realiza a validação do Desconto
                if ($("input[name='produto_valor_final']").val() < "0") {
                    notificationFunctions.toastSmall('error', 'O valor de desconto não pode ser superior ao valor total.');
                    $("input[name='produto_desconto']").focus();
                    return;
                }

                /////// Inicio :: Adiciona no Carrinho ///////

                // Oculta o aviso de carrinho vazio e mostra os itens
                $("#bodyCarrinho #carrinhoVazio").addClass('d-none');
                $("#bodyCarrinho #carrinhoComProdutos").removeClass('d-none');

                // Coleta os dados
                let dadosProduto = $("[data-select='buscarProduto']").select2('data');
                let desconto = $("#sectionProduto input[name='produto_desconto']").val();

                $("#bodyCarrinho #listagemItens").append(`
                    <div class="item">
                        <input type="hidden" name="carrinho[codigo_produto][]" value="${dadosProduto.id}" />
                        <input type="hidden" name="carrinho[quantidade][]" value="${$("#sectionProduto input[name='produto_quantidade']").val()}" />
                        <input type="hidden" name="carrinho[valor_bruto][]" value="${dadosProduto.valor}" />
                        <input type="hidden" name="carrinho[valor_final][]" value="${convertFunctions.onlyNumber($("#sectionProduto input[name='produto_valor_final']").val())}" />
                        <input type="hidden" name="carrinho[valor_desconto][]" value="${convertFunctions.onlyNumber(desconto)}" />
                        <div class="row align-items-center pt-2 px-2 pb-0">
                            <div class="col">
                                <span class="lh-1">${dadosProduto.produto} <small>(Cód. Barras: ${dadosProduto.codigo_barras})</small></span>
                                <br>
                                <small>Fornecedor: ${dadosProduto.fornecedor} — ${dadosProduto.referencia_fornecedor}</small>
                            </div>
                            <div class="col-auto">
                                <h6 class="text-start">${$("#sectionProduto input[name='produto_quantidade']").val()}</h6>
                            </div>
                            <div class="col-auto">
                                <h6 class="lh-1 mb-0 text-end">R$ ${convertFunctions.intToReal(dadosProduto.valor)}</h6>
                                <small class="text-end ${desconto !== '0,00' ? '' : 'd-none'}">Desconto: R$ ${desconto}</small>
                            </div>
                        </div>
                    </div>
                    <hr class="mt-1 mb-2">
                `);

                // Esvazia os campos do Produto
                $("#sectionProduto input").prop('readonly', true).val('');
                $("[data-select='buscarProduto']").prop('readonly', false).select2('val', '');
                notificationFunctions.toastSmall('success', 'Produto adicionado no carrinho.');

                pdvFunctions.recalculaCarrinho();

                // Oculta esse botão
                $(this).addClass('d-none');

                // Exibe o botao de Pagamento, e cancelamento
                $("[data-action='irPagamento']").removeClass('d-none');
                $("[data-action='cancelarVenda']").removeClass('d-none');

                // Mostra o botão de esvaziar Carrinho
                $("[data-action='esvaziarCarrinho']").removeClass('d-none');

                /////// Fim :: Adiciona no Carrinho ///////

            });

            // Calcula o troco
            $(document).on('keyup', "input[name='valor_entrada']", function(e) {
                valorTotal = convertFunctions.onlyNumber($("input[name='valor_total']").val());
                valorEntrada = convertFunctions.onlyNumber($(this).val());

                if (valorEntrada > valorTotal) {
                    valorTroco = valorEntrada - valorTotal;
                } else {
                    valorTroco = 0;
                }

                $("input[name='valor_troco']").val(convertFunctions.intToReal(valorTroco));
            });

        },
        recalculaCarrinho: () => {
            // Calcula a quantidade de Itens no Carrinho
            let qtde = 0,
                subTotal = 0,
                desconto = 0,
                total = 0;

            $.each($("input[name='carrinho[quantidade][]']"), (i, el) => {
                qtde = qtde + parseInt($(el).val())
            });
            $("#subTotalItens").text(`(${qtde} ${qtde > 1 ? 'Itens' : 'Item'})`);

            // Calcula o subTotal
            $.each($("input[name='carrinho[valor_bruto][]']"), (i, el) => {
                let quantidade = $("input[name='carrinho[quantidade][]']")[i];
                subTotal = subTotal + (convertFunctions.onlyNumber($(el).val()) * $(quantidade).val())
            });
            $("#subTotalValor").text(`R$ ${convertFunctions.intToReal(subTotal)}`);

            // Calcula os Descontos
            $.each($("input[name='carrinho[valor_desconto][]']"), (i, el) => {
                desconto = desconto + convertFunctions.onlyNumber($(el).val())
            });
            $("#desconto").text(`R$ ${convertFunctions.intToReal(desconto)}`);

            // Calcula o Total
            total = subTotal - desconto;
            $("#total").text(`R$ ${convertFunctions.intToReal(total)}`);
            $("#sectionPagamento input[name='valor_total']").val(convertFunctions.intToReal(total));

        },
        listenerVenda: () => {
            $(document).on('click', "[data-action='finalizarVenda']", async function(e) {
                // Confirma se deve continuar
                const res = await notificationFunctions.popupConfirm('Atenção', 'Deseja realmente finalizar a venda?', 'warning').then(result => result);
                if (res.isDismissed == true) return;

                // Caso nao seja identificado o cliente
                if ($("input[name='codigo_cliente']").val() == "") {
                    valorTotal = convertFunctions.onlyNumber($("input[name='valor_total']").val());
                    valorEntrada = convertFunctions.onlyNumber($("input[name='valor_entrada']").val());
                    parcelas = convertFunctions.onlyNumber($("input[name='parcelas']").val());

                    // É obrigatório pagar todo o valor
                    if (valorEntrada == "" || valorEntrada == 0 || (valorEntrada < valorTotal)) {
                        notificationFunctions.alertPopup('warning', 'Se não for informado o cliente, é necessario pagar o valor total da venda.', 'Atenção');
                        $("input[name='valor_entrada']").focus();
                        return;
                    }

                    // Só pode ser pago a vista
                    if (parcelas != 1) {
                        notificationFunctions.alertPopup('warning', 'Se não for informado o cliente, só é possível realizar o pagamento em uma parcela.', 'Atenção');
                        $("input[name='parcelas']").focus();
                        return;
                    }
                }

                // Envia o Formulario
                $("#formCarrinho").submit();
            });
        },
    };

    document.addEventListener("DOMContentLoaded", () => {
        select2PdvFunctions.init();
        pdvFunctions.init();
    });
</script>
