<script>
    const select2ClienteFunctions = {
        init: () => {
            select2ClienteFunctions.buscarCadastroMetodoPagamento();
            select2ClienteFunctions.buscarCliente();
            select2ClienteFunctions.buscarProduto();
        },
        buscarCadastroMetodoPagamento: () => {
            let elementSelect2 = $("[data-select='buscarCadastroMetodoPagamento']");
            let url = `${BASEURL}/cadastro/selectCadastroMetodoPagamento`;
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
        buscarCliente: () => {
            let elementSelect2 = $("[data-select='buscarCliente']");
            let url = `${BASEURL}/cliente/backendCall/selectCliente`;
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
        buscarProduto: () => {
            let elementSelect2 = $("[data-select='buscarProduto']");
            let url = `${BASEURL}/produto/backendCall/selectProduto`;
            elementSelect2.select2({
                placeholder: "Selecione...",
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
                formatSelection: (data) => data.text
            });
        },
    };

    const clienteFunctions = {
        init: () => {
            clienteFunctions.listenerTipoPessoa();
            clienteFunctions.listenerBuscarCep();
            clienteFunctions.listenerCloneEndereco();
            clienteFunctions.listenerFiltros();
            clienteFunctions.listenerAdicionarSaldo();
            clienteFunctions.listenerPagamentoParcial();
            clienteFunctions.listenerAbaterValores();

            // Atualiza os campos conforme a o tipo de pessoa
            $("#tipoPessoa").trigger('change');
        },
        listenerFiltros: () => {
            $(document).on('click', "[data-action='btnLimpar']", () => {
                // Busca todos os elementos que possuem o atributo 'data-filtro' e que iniciam com 'filtro_'
                $("[data-filtro^='filtro_']").find("input,select,textarea").val('');
                $("[data-filtro^='filtro_']").find("input").select2('val', '');
                $('#tableExtrato').DataTable(dataGridGlobalFunctions.getSettings(2));
            });

            $(document).on('click', "[data-action='btnFiltrar']", (e) => {
                e.preventDefault();

                dataGridOptionsFunctions.destroyTable("#tableExtrato");
                dataGridOptionsFunctions.destroyTable("#tableHistoricoProduto");
                dataGridOptionsFunctions.destroyTable("#tableHistoricoFinanceiro");
                dataGridOptionsFunctions.destroyTable("#tableHistoricoSaldo");

                let filtros = [{
                    exibir_pago: $("select[name='exibir_pago']").val(),
                    codigo_cliente: $("input[name='codigo_cliente']").val(),
                    codigo_produto: $("input[name='codigo_produto']").val(),
                    codigo_cadastro_metodo_pagamento: $("input[name='codigo_cadastro_metodo_pagamento']").val(),
                    data_inicio: $("input[name='data_inicio']").val(),
                    data_fim: $("input[name='data_fim']").val(),
                }];

                mapeamento[2][ROUTE]['custom_data'] = filtros;
                mapeamento[3][ROUTE]['custom_data'] = filtros;
                mapeamento[4][ROUTE]['custom_data'] = filtros;
                mapeamento[5][ROUTE]['custom_data'] = filtros;

                $('#tableExtrato').DataTable(dataGridGlobalFunctions.getSettings(2));
                $('#tableHistoricoProduto').DataTable(dataGridGlobalFunctions.getSettings(3));
                $('#tableHistoricoFinanceiro').DataTable(dataGridGlobalFunctions.getSettings(4));
                $('#tableHistoricoSaldo').DataTable(dataGridGlobalFunctions.getSettings(5));
            })
        },
        listenerTipoPessoa: () => {
            $(document).on('change', "#tipoPessoa", function() {

                if ($(this).val() !== $('#tipoPessoa').val()) {
                    // Zera o valor dos campos
                    $("input[name='razao_social'], input[name='nome_fantasia'], input[name='cpf_cnpj']").val('');
                }

                if ($(this).val() == 1) {
                    // PESSOA FÍSICA

                    // Esconde o Campo de Razão Social, remove a obrigatoriedade
                    $("#razaoSocial").addClass('d-none').find('input').removeAttr('required');

                    // Troca a Label de Nome Fantasia para Nome
                    $("#nomeFantasia").find('label').text('Nome *');

                    // Troca a Label de CNPJ para CPF, e atualiza o tipo de mascara
                    $("#cpfCnpj").find('label').text('CPF');

                    // Exibe a Data de Nascimento
                    $("#dataNascimento").removeClass('d-none');
                } else {
                    // PESSOA JURÍDICA

                    // Exibe o Campo de Razão Social, e adiciona a obrigatoriedade
                    $("#razaoSocial").removeClass('d-none').find('input').attr('required');
                    $("#nomeFantasia").find('label').text('Nome Fantasia *');

                    // Troca a Label de CPF para CNPJ, e atualiza o tipo de mascara
                    $("#cpfCnpj").find('label').text('CNPJ *');

                    // Oculta a Data de Nascimento
                    $("#dataNascimento").addClass('d-none');
                }

                appFunctions.addInputLabelRequired();
                maskFunctions.init();
            });
        },
        listenerBuscarCep: () => {
            $(document).on('keyup', "input[name='endereco[cep][]']", function() {
                const cep = $(this).val();
                if (cep.length >= 9) {
                    appFunctions.buscarCep(cep).then(
                        (retorno) => {
                            if (retorno) {
                                let row = $(this).parent().parent();
                                $(row).find("input[name='endereco[rua][]']").val(retorno.street);
                                $(row).find("input[name='endereco[bairro][]']").val(retorno.neighborhood);
                                $(row).find("input[name='endereco[cidade][]']").val(retorno.city);
                                $(row).find("input[name='endereco[cidade_completa][]']").val(`${retorno.city}/${retorno.state}`);
                                $(row).find("input[name='endereco[uf][]']").val(retorno.state);
                                $(row).find("input[name='endereco[complemento][]']").val('');
                                $(row).find("input[name='endereco[numero][]']").val('').focus();
                            }
                        }
                    )
                }
            });
        },
        listenerCloneEndereco: () => {
            // Adiciona um Bloco de Endereço
            $(document).on('click', "[data-action='novoEndereco']", function() {
                let bloco = $('.selector-row-enderecos:last').clone();
                $('.selector-row-enderecos:last').after(bloco);
                $('.selector-row-enderecos:last input').val('');
                appFunctions.tooltip();
                maskFunctions.init();
            });

            // Remove um Bloco de Endereço
            $(document).on('click', "[data-action='removerEndereco']", async function() {
                // Valida se é para Excluir ou apenas remover
                let codigoEndereco = $(this).parents('.selector-row-enderecos').find('input:hidden').val();
                if (codigoEndereco !== '') {
                    await notificationFunctions.popupConfirm('Atenção', 'Tem certeza que deseja remover esse endereço?', 'warning').then(
                        (result) => {
                            if (result.value) {
                                appFunctions.backendCall('POST', `cliente/desativarEndereco/${codigoEndereco}`).then(
                                    (res) => {
                                        if (res) {
                                            clienteFunctions.excluiEndereco($(this));
                                        }
                                        notificationFunctions.toastSmall(res.textStatus, res.mensagem);
                                    }
                                );
                            }
                        }
                    );
                } else {
                    clienteFunctions.excluiEndereco($(this));
                }
            });
        },
        excluiEndereco: (_this) => {
            // Verifica se só tem um endereço ou mais
            if ($("[data-action='removerEndereco']").length == 1) {
                let bloco = $('.selector-row-enderecos:last').clone(); // Clona o ultimo Card
                $(_this).parents('.selector-row-enderecos').remove(); // Remove o card ativo
                $('#insertEmptyRowEndereco').append(bloco); // Insere o card clonado
                $('.selector-row-enderecos').find('input').val(''); // Zera os valores dos inputs
            } else {
                // Remove o CARD inteiro de Endereço
                $(_this).parents('.selector-row-enderecos').remove();
            }

            appFunctions.tooltip();
            maskFunctions.init();
        },
        listenerAdicionarSaldo: () => {
            $(document).on('click', "[data-action='adicionarSaldo']", function() {
                $("#modalClienteAdicionarSaldo input").val('');
                $("#modalClienteAdicionarSaldo input[name='uuid_cliente']").val($(this).data('id'));
                $("#modalClienteAdicionarSaldo").modal('show');
            });
        },
        listenerPagamentoParcial: () => {

            // Listener da Modal de Pagamento Parcial
            $(document).on('click', "[data-action='fluxoPagarParcial']", async function(e) {
                let fluxoUuid = $(this).data('id');

                // Limpa os campos
                $("#modalFluxoParcial input").val('');
                $("#modalFluxoParcial input").select2('val', '');
                $("#modalFluxoParcial #emptyPagamentoParcial").removeClass('d-none');

                // Busca os dados do Fluxo
                await appFunctions.backendCall('POST', `financeiro/backendCall/selectFluxo`, {
                    codUuid: fluxoUuid,
                    page: 1
                }).then((res) => {
                    if (res && res.itens.length > 0) {
                        res = res.itens[0];
                        // Atribui os valores nos campos
                        $("#modalFluxoParcial input[name='data_vencimento']").val(res.data_vencimento);
                        $("#modalFluxoParcial input[name='valor_total']").val(convertFunctions.intToReal(res.valor_liquido));
                        $("#modalFluxoParcial input[name='saldo_devedor']").val(convertFunctions.intToReal(res.saldo_devedor));
                        $("#modalFluxoParcial input[name='codigo_cadastro_metodo_pagamento']").select2('val', res.codigo_cadastro_metodo_pagamento);
                        $("#modalFluxoParcial [data-action='realizarPagamentoParcial']").attr('id', fluxoUuid)
                    } else {
                        $("#modalFluxoParcial #emptyPagamentoParcial").removeClass('d-none');
                        $("#modalFluxoParcial #notEmptyPagamentoParcial").addClass('d-none');
                        notificationFunctions.toastSmall('error', 'Fluxo não encontrado.')
                    }
                }).catch(err => notificationFunctions.toastSmall('error', 'Fluxo não encontrado.'));

                // Busca os Pagamentos parciais desse fluxo
                await appFunctions.backendCall('POST', `financeiro/backendCall/selectFluxoParcial`, {
                    codUuid: fluxoUuid,
                    page: 1
                }).then((res) => {
                    let valorTotal = "0";

                    $("#modalFluxoParcial tbody").html('');
                    $("#modalFluxoParcial tfoot").html('');
                    if (res && res.itens.length > 0) {
                        res.itens.forEach((el) => {
                            valorTotal = (convertFunctions.onlyNumber(valorTotal) + convertFunctions.onlyNumber(el.valor));
                            $("#modalFluxoParcial tbody").append(`
                                <tr>
                                    <td>${moment(el.data_pagamento).format('DD/MM/YYYY')}</td>
                                    <td>${el.metodo_pagamento || 'Não Informado'}</td>
                                    <td class="text-end">R$ ${convertFunctions.intToReal(el.valor)}</td>
                                    <td class="text-center"><i class="fas fa-trash text-danger cursor" data-id="${el.uuid_financeiro_fluxo_parcial}" data-action="removerFluxoParcial" data-tippy-content="Remover pagamento parcial"></i></td>
                                </tr>
                            `);
                        });

                        $("#modalFluxoParcial tfoot").append(`
                            <tr>
                                <td></td>
                                <td></td>
                                <td class="text-end"><b>Total: </b>R$ ${convertFunctions.intToReal(valorTotal)}</td>
                                <td></td>
                            </tr>
                        `);

                        appFunctions.tooltip();
                        $("#modalFluxoParcial #emptyPagamentoParcial").addClass('d-none');
                        $("#modalFluxoParcial #notEmptyPagamentoParcial").removeClass('d-none');
                    } else {
                        $("#modalFluxoParcial #emptyPagamentoParcial").removeClass('d-none');
                        $("#modalFluxoParcial #notEmptyPagamentoParcial").addClass('d-none');
                    }

                }).catch(err => notificationFunctions.toastSmall('error', 'Fluxo não encontrado.'));

                $("#modalFluxoParcial").modal('show');
            });

            // Listener para realizar o Pagamento parcial
            $(document).on('click', "[data-action='realizarPagamentoParcial']", async function(e) {
                if (!$("#formPagamentoParcial")[0].reportValidity()) return false;

                if (convertFunctions.onlyNumber($("#formPagamentoParcial input[name='valor']").val()) > convertFunctions.onlyNumber($("#formPagamentoParcial input[name='saldo_devedor']").val())) {
                    notificationFunctions.alertPopup('error', 'O valor do pagamento não pode ser superior ao valor do saldo devedor', 'Atenção');
                    return false;
                }

                let fluxoUuid = $(this).attr('id');
                await appFunctions.backendCall('POST', `financeiro/pagarParcial/${fluxoUuid}`, {
                    dados: $("#modalFluxoParcial #formPagamentoParcial").serialize()
                }).then((res) => {
                    $("#modalFluxoParcial").modal('hide');
                    $("[data-action='btnFiltrar']").trigger('click');
                    notificationFunctions.toastSmall(res.textStatus, res.mensagem);
                }).catch(err => notificationFunctions.toastSmall(err.textStatus, err.mensagem));
            });

            // Remove um Pagamento de Fluxo Parcial
            $(document).on('click', "[data-action='removerFluxoParcial']", function(e) {
                let fluxoUuidParcial = $(this).data('id');
                notificationFunctions.popupConfirm('Atenção', 'Deseja realmente remover esse pagamento parcial?', 'warning').then(
                    async (result) => {
                        if (result.value) {
                            await appFunctions.backendCall('POST', `financeiro/removerPagamentoParcial/${fluxoUuidParcial}`).then(res => {
                                $("#modalFluxoParcial").modal('hide');
                                $("[data-action='btnFiltrar']").trigger('click');
                                notificationFunctions.toastSmall(res.textStatus, res.mensagem);
                            }).catch(err => notificationFunctions.toastSmall(err.textStatus, err.mensagem));
                        }
                    }
                );
            });

        },
        listenerAbaterValores: () => {
            // Listener de Abater Valores em aberto
            $(document).on('click', "[data-action='abaterValores']", async function(e) {
                let clienteUuid = $(this).data('id');

                await appFunctions.backendCall('POST', `cliente/backendCall/selectValorEmAberto`, {
                    clienteUuid: clienteUuid
                }).then((res) => {
                    if (res && res.length > 0) {
                        res = res[0];
                        // Atribui os valores nos campos
                        $("#modalClienteAbaterValores input").val('');
                        $("#modalClienteAbaterValores input[name='uuid_cliente']").val(clienteUuid);
                        $("#modalClienteAbaterValores input[name='valor_aberto']").val(convertFunctions.intToReal(res.valor));
                        $("#modalClienteAbaterValores input[name='valor_pagar']").val('');
                        $("#modalClienteAbaterValores").modal('show');
                    }
                }).catch(err => notificationFunctions.toastSmall('error', 'Fluxo não encontrado.'));
            });

            // Listener de Abater Valores em aberto - Validação do Valor digitado
            $(document).on('keyup', "#modalClienteAbaterValores input[name='valor_pagar']", function() {
                let valor = convertFunctions.onlyNumber($(this).val());
                let valorAberto = convertFunctions.onlyNumber($("#modalClienteAbaterValores input[name='valor_aberto']").val());

                if (valor > valorAberto) {
                    notificationFunctions.toastSmall('error', 'O Valor a pagar não pode ser maior que o valor em aberto.')
                }
            });
        }
    };

    const dataGridClienteFunctions = {
        init: () => {
            dataGridClienteFunctions.mapeamentoCliente();
            dataGridClienteFunctions.mapeamentoClienteVisualizar();

            if (METODO == 'index') {
                $('#tableAtivos').DataTable(dataGridGlobalFunctions.getSettings(0));
                $('#tableInativos').DataTable(dataGridGlobalFunctions.getSettings(1));
            }

            if (METODO == 'view') {
                $("[data-action='btnFiltrar']").click();
            }
        },
        mapeamentoCliente: () => {
            // Ativos
            mapeamento[0] = [];
            mapeamento[0][ROUTE] = [];
            mapeamento[0][ROUTE]['id_column'] = `uuid_cliente`;
            mapeamento[0][ROUTE]['ajax_url'] = `${BASEURL}/cliente/getDataGrid/1`;
            mapeamento[0][ROUTE]['order_by'] = [{
                "coluna": 0,
                "metodo": "ASC"
            }];
            mapeamento[0][ROUTE]['columns'] = [{
                    "data": "nome",
                    "title": "Nome"
                },
                {
                    "data": "email",
                    "title": "Email"
                },
                {
                    "data": "celular",
                    "title": "Celular",
                    "isreplace": true,
                    "render": (data) => convertFunctions.intToPhone(data)
                },
                {
                    "data": "saldo",
                    "title": "Saldo",
                    "className": "text-end",
                    "render": (data) => `R$ ${convertFunctions.intToReal(data)}`
                },
                {
                    "data": "criado_em",
                    "visible": false,
                    "title": "Criado em"
                },
                {
                    "data": "usuario_criacao",
                    "visible": false,
                    "title": "Criado por"
                },
                {
                    "data": "alterado_em",
                    "visible": false,
                    "title": "Alterado em"
                },
                {
                    "data": "usuario_alteracao",
                    "visible": false,
                    "title": "Alterado por"
                },
                {
                    "data": "uuid_cliente",
                    "title": "Ações",
                    "className": "text-center"
                }
            ];
            mapeamento[0][ROUTE]['btn_montar'] = true;
            mapeamento[0][ROUTE]['btn'] = [{
                    "funcao": "visualizar",
                    "metodo": "visualizar",
                    "compare": null
                }, {
                    "funcao": "editar",
                    "metodo": "alterar",
                    "compare": null
                },
                {
                    "funcao": "clienteAdicionarSaldo",
                    "metodo": "",
                    "compare": null
                },
                {
                    "funcao": "desativar",
                    "metodo": "",
                    "compare": null
                },
            ];

            // Inativos
            mapeamento[1] = [];
            mapeamento[1][ROUTE] = [];
            mapeamento[1][ROUTE]['id_column'] = `uuid_cliente`;
            mapeamento[1][ROUTE]['ajax_url'] = `${BASEURL}/cliente/getDataGrid/0`;
            mapeamento[1][ROUTE]['order_by'] = [{
                "coluna": 0,
                "metodo": "ASC"
            }];
            mapeamento[1][ROUTE]['columns'] = [{
                    "data": "nome",
                    "title": "Nome"
                },
                {
                    "data": "email",
                    "title": "Email"
                },
                {
                    "data": "celular",
                    "title": "Celular",
                    "isreplace": true,
                    "render": (data) => convertFunctions.intToPhone(data)
                },
                {
                    "data": "saldo",
                    "title": "Saldo",
                    "className": "text-end",
                    "render": (data) => `R$ ${convertFunctions.intToReal(data)}`
                },
                {
                    "data": "criado_em",
                    "visible": false,
                    "title": "Criado em"
                },
                {
                    "data": "usuario_criacao",
                    "visible": false,
                    "title": "Criado por"
                },
                {
                    "data": "alterado_em",
                    "visible": false,
                    "title": "Alterado em"
                },
                {
                    "data": "usuario_alteracao",
                    "visible": false,
                    "title": "Alterado por"
                },
                {
                    "data": "inativado_em",
                    "visible": false,
                    "title": "Inativado em"
                },
                {
                    "data": "usuario_inativacao",
                    "visible": false,
                    "title": "Inativado por"
                },
                {
                    "data": "uuid_cliente",
                    "title": "Ações",
                    "className": "text-center"
                }
            ];
            mapeamento[1][ROUTE]['btn_montar'] = true;
            mapeamento[1][ROUTE]['btn'] = [{
                "funcao": "ativar",
                "metodo": "",
                "compare": null
            }, ];



        },
        mapeamentoClienteVisualizar: () => {
            // Extrato do Cliente
            mapeamento[2] = [];
            mapeamento[2][ROUTE] = [];
            mapeamento[2][ROUTE]['id_column'] = `uuid_financeiro_fluxo`;
            mapeamento[2][ROUTE]['ajax_url'] = `${BASEURL}/cliente/getDataGridExtrato/0`;
            mapeamento[2][ROUTE]['order_by'] = [{
                "coluna": 0,
                "metodo": "ASC"
            }];
            mapeamento[2][ROUTE]['columns'] = [{
                    "data": "codigo_venda",
                    "title": "Código Venda"
                },
                {
                    "data": "criado_em",

                    "title": "Data"
                },
                {
                    "data": "metodo_pagamento",
                    "title": "Método Pagamento"
                },
                {
                    "data": "numero_parcela",
                    "title": "Parcela"
                },
                {
                    "data": "valor_bruto",
                    "title": "Valor Bruto (R$)",
                    "visible": false,
                    "className": "text-end",
                    "render": (data) => `R$ ${convertFunctions.intToReal(data)}`
                },
                {
                    "data": "valor_desconto",
                    "title": "Valor Desconto (R$)",
                    "visible": false,
                    "className": "text-end",
                    "render": (data) => `R$ ${convertFunctions.intToReal(data)}`
                },
                {
                    "data": "valor_liquido",
                    "title": "Valor Líquido (R$)",
                    "className": "text-end",
                    "render": (data) => `R$ ${convertFunctions.intToReal(data)}`
                },
                {
                    "data": "valor_pago_parcial",
                    "title": "Valor Pago (R$)",
                    "className": "text-end",
                    "render": (data) => `R$ ${convertFunctions.intToReal(data)}`
                },
                {
                    "data": "data_vencimento",
                    "title": "Vencimento em"
                },
                {
                    "data": "data_pagamento",
                    "title": "Pago em"
                },
                {
                    "data": "criado_em",
                    "visible": false,
                    "title": "Criado em"
                },
            ];

            // Histórico de Produto
            mapeamento[3] = [];
            mapeamento[3][ROUTE] = [];
            mapeamento[3][ROUTE]['id_column'] = `uuid_venda_produto`;
            mapeamento[3][ROUTE]['ajax_url'] = `${BASEURL}/cliente/getDataGridHistoricoProduto`;
            mapeamento[3][ROUTE]['order_by'] = [{
                "coluna": 6,
                "metodo": "ASC"
            }];
            mapeamento[3][ROUTE]['columns'] = [{
                    "data": "codigo_produto",
                    "title": "Código do Produto"
                },
                {
                    "data": "codigo_barras",
                    "title": "Código de Barras"
                },
                {
                    "data": "codigo_venda",
                    "title": "Código da Venda",
                },
                {
                    "data": "nome_produto",
                    "title": "Nome",
                },
                {
                    "data": "quantidade",
                    "title": "Quantidade",
                },
                {
                    "data": "valor_total",
                    "title": "Valor Total",
                    "isreplace": true,
                    "render": (data) => `R$ ${convertFunctions.intToReal(data)}`
                },
                {
                    "data": "criado_em",
                    "title": "Data da Venda"
                },
                {
                    "data": "vendedor",
                    "title": "Vendedor",
                    "render": (data) => appFunctions.formatName(data)
                },
                {
                    "data": "uuid_venda_produto",
                    "title": "Ações",
                    "className": "text-center"
                }
            ];
            mapeamento[3][ROUTE]['btn_montar'] = false;

            // Historico Financeiro
            mapeamento[4] = [];
            mapeamento[4][ROUTE] = [];
            mapeamento[4][ROUTE]['id_column'] = `uuid_financeiro_fluxo`;
            mapeamento[4][ROUTE]['ajax_url'] = `${BASEURL}/cliente/getDataGridHistoricoFinanceiro`;
            mapeamento[4][ROUTE]['pageLength'] = 25;
            mapeamento[4][ROUTE]['order_by'] = [{
                "coluna": 0,
                "metodo": "ASC"
            }];
            mapeamento[4][ROUTE]['columns'] = [{
                    "data": "codigo_financeiro_fluxo",
                    "visible": false,
                    "title": "Código Fluxo"
                },
                {
                    "data": "data_vencimento",
                    "title": "Vencimento"
                },
                {
                    "data": "nome",
                    "title": "Descrição"
                },
                {
                    "data": "valor_liquido",
                    "title": "Valor Líquido",
                    "className": "text-end",
                    "render": (data) => `R$ ${convertFunctions.intToReal(data)}`
                },
                {
                    "data": "valor_pago_parcial",
                    "title": "Valor já Pago",
                    "className": "text-end",
                    "render": (data) => `R$ ${convertFunctions.intToReal(data)}`
                },
                {
                    "data": "metodo_pagamento",
                    "title": "Método Pagamento"
                },
                {
                    "data": "numero_parcela",
                    "title": "Parcela"
                },
                {
                    "data": "data_pagamento",
                    "title": "Data de Pagamento"
                },
                {
                    "data": "criado_em",
                    "title": "Criado em"
                },
                {
                    "data": "uuid_financeiro_fluxo",
                    "title": "Ações",
                    "className": "text-center"
                }
            ];
            mapeamento[4][ROUTE]['btn_montar'] = true;
            mapeamento[4][ROUTE]['btn'] = [{
                "funcao": "fluxoPagarParcial",
                "metodo": "",
                "compare": {
                    operator: "and",
                    expressions: [{
                        column: "codigo_financeiro_fluxo",
                        type: "!=",
                        value: null
                    }, {
                        column: "situacao",
                        type: "==",
                        value: 'f'
                    }]
                }
            }, ];

            // Historico de Saldo
            mapeamento[5] = [];
            mapeamento[5][ROUTE] = [];
            mapeamento[5][ROUTE]['id_column'] = `uuid_cliente_extrato`;
            mapeamento[5][ROUTE]['ajax_url'] = `${BASEURL}/cliente/getDataGridHistoricoSaldo`;
            mapeamento[5][ROUTE]['pageLength'] = 25;
            mapeamento[5][ROUTE]['order_by'] = [{
                "coluna": 0,
                "metodo": "ASC"
            }];
            mapeamento[5][ROUTE]['columns'] = [{
                    "data": "descricao",
                    "title": "Descrição"
                },
                {
                    "data": "tipo_transacao",
                    "title": "Operação",
                    "render": (data) => `${data == 'C' ? 'Crédito' : 'Débito'}`
                },
                {
                    "data": "valor",
                    "title": "Valor",
                    "className": "text-end",
                    "render": (data) => `R$ ${convertFunctions.intToReal(data)}`
                },
                {
                    "data": "criado_em",
                    "title": "Data da Operação"
                },
                {
                    "data": "usuario_criacao",
                    "title": "Usuário"
                },
                {
                    "data": "uuid_cliente_extrato",
                    "title": "Ações",
                    "visible": false,
                    "className": "text-center"
                }
            ];
            mapeamento[5][ROUTE]['btn_montar'] = false;
        },
    };

    document.addEventListener("DOMContentLoaded", () => {
        clienteFunctions.init();
        select2ClienteFunctions.init();
        dataGridClienteFunctions.init();
    });
</script>
