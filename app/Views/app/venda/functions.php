<script>
    const select2VendaFunctions = {
        init: () => {
            select2VendaFunctions.buscarVendedor();
            select2VendaFunctions.buscarCliente();
            select2VendaFunctions.buscarCadastroMetodoPagamento();
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
        buscarVendedor: () => {
            let elementSelect2 = $("[data-select='buscarVendedor']");
            let url = `${BASEURL}/vendedor/backendCall/selectVendedor`;
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
    };

    const vendaFunctions = {
        init: () => {
            vendaFunctions.listenerModalVerVenda()
            vendaFunctions.listenerFiltros();
            vendaFunctions.listenerEstornarVenda();
            vendaFunctions.listenerImprimirComprovante();
        },
        listenerModalVerVenda: () => {
            $(document).on('click', "[data-action='verRegistroModal']", async function(e) {
                let vendaUuid = $(this).data('id');

                await appFunctions.backendCall('POST', `venda/backendCall/selectVenda`, {
                    codUuid: vendaUuid,
                    page: 1
                }).then(async res => {

                    if (res && res.itens.length > 0) {
                        res = res.itens[0];

                        // Se a venda estiver estornada remove o botão de estorno
                        if (res.estornado_em) {
                            $("#modalVisualizarVenda [data-action='btnEstornarVenda']").remove();
                        } else {
                            $("#modalVisualizarVenda [data-action='btnEstornarVenda']").attr('id', res.uuid_venda);
                        }

                        $("#modalVisualizarVenda [data-action='btnImprimirComprovante']").attr('id', res.uuid_venda);

                        // Atribui os valores nos campos
                        $("#modalVisualizarVenda input[name='codigo_venda']").val(res.codigo_venda);
                        $("#modalVisualizarVenda input[name='codigo_cliente']").select2('val', res.codigo_cliente);
                        $("#modalVisualizarVenda input[name='codigo_vendedor']").select2('val', res.codigo_vendedor);
                        $("#modalVisualizarVenda input[name='codigo_cadastro_metodo_pagamento']").select2('val', res.codigo_cadastro_metodo_pagamento);
                        $("#modalVisualizarVenda input[name='valor_bruto']").val(convertFunctions.intToReal(res.valor_bruto));
                        $("#modalVisualizarVenda input[name='valor_desconto']").val(convertFunctions.intToReal(res.valor_desconto));
                        $("#modalVisualizarVenda input[name='valor_liquido']").val(convertFunctions.intToReal(res.valor_liquido));
                        $("#modalVisualizarVenda textarea[name='observacao']").val(res.observacao);

                        // Busca os Produtos da Venda
                        $("#tableVendaProduto tbody").html('');
                        await appFunctions.backendCall('POST', `venda/backendCall/selectVendaProduto`, {
                            codVenda: res.codigo_venda,
                        }).then(resVenda => {
                            if (resVenda && resVenda.length > 0) {
                                resVenda.forEach(it => {
                                    $("#tableVendaProduto tbody").append(`
                                        <tr>
                                            <td>${it.codigo_produto}</td>
                                            <td>${it.nome_produto}</td>
                                            <td class="text-center">${it.quantidade}</td>
                                            <td class="text-end">R$ ${convertFunctions.intToReal(it.valor_unitario)}</td>
                                            <td class="text-end">R$ ${convertFunctions.intToReal(it.valor_desconto)}</td>
                                            <td class="text-end">R$ ${convertFunctions.intToReal(it.valor_total)}</td>
                                        </tr>
                                    `);
                                });
                            }
                        }).catch(err => notificationFunctions.toastSmall('error', 'Venda não encontrada.'));


                        $("#modalVisualizarVenda").modal('show');

                        // Para aplicar as mascaras
                        $("input[name='valor_bruto']").trigger('keyup');
                    } else {
                        notificationFunctions.toastSmall('error', 'Venda não encontrada.')
                    }

                }).catch(err => notificationFunctions.toastSmall('error', 'Venda não encontrada.'));

            });
        },
        listenerFiltros: () => {
            $(document).on('click', "[data-action='btnLimpar']", () => {
                // Busca todos os elementos que possuem o atributo 'data-filtro' e que iniciam com 'filtro_'
                $("[data-filtro^='filtro_']").find("input,select,textarea").val('');
                $("[data-filtro^='filtro_']").find("input").select2('val', '');
                $('#tableRealizadas').DataTable(dataGridGlobalFunctions.getSettings(0));
                $('#tableEstornadas').DataTable(dataGridGlobalFunctions.getSettings(1));
            });

            $(document).on('click', "[data-action='btnFiltrar']", (e) => {
                e.preventDefault();

                dataGridOptionsFunctions.destroyTable("#tableRealizadas");
                dataGridOptionsFunctions.destroyTable("#tableEstornadas");

                let filtros = [{
                    codigo_vendedor: $("input[name='codigo_vendedor']").val(),
                    codigo_cliente: $("input[name='codigo_cliente']").val(),
                    codigo_cadastro_metodo_pagamento: $("input[name='codigo_cadastro_metodo_pagamento']").val(),
                    data_inicio: $("input[name='data_inicio']").val(),
                    data_fim: $("input[name='data_fim']").val(),
                }];

                mapeamento[0][ROUTE]['custom_data'] = filtros;
                mapeamento[1][ROUTE]['custom_data'] = filtros;

                $('#tableRealizadas').DataTable(dataGridGlobalFunctions.getSettings(0));
                $('#tableEstornadas').DataTable(dataGridGlobalFunctions.getSettings(1));
            })
        },
        listenerEstornarVenda: () => {
            $(document).on('click', "[data-action='btnEstornarVenda']", function() {
                let uuid = $(this).attr('id');
                notificationFunctions.popupConfirm('Atenção', 'Deseja realmente estornar essa venda?', 'warning').then(
                    async (result) => {
                        if (result.value) {
                            await appFunctions.backendCall('POST', `venda/estorno/${uuid}`).then(res => {
                                $("#modalVisualizarVenda").modal('hide');
                                notificationFunctions.toastSmall(res.textStatus, res.mensagem);
                                window.location.reload();
                            }).catch(err => notificationFunctions.toastSmall(err.textStatus, err.mensagem));
                        }
                    }
                );
            });
        },
        listenerImprimirComprovante: () => {
            $(document).on('click', "[data-action='btnImprimirComprovante']", function() {
                appFunctions.viewToPrint(`${BASEURL}/venda/comprovante/${$(this).attr('id')}`);
            });
        }
    };

    const dataGridVendaFunctions = {
        init: () => {
            dataGridVendaFunctions.mapeamentoVenda();

            if (METODO == 'index') {
                $("[data-action='btnFiltrar']").trigger('click');
            }
        },
        mapeamentoVenda: () => {
            // Realizadas
            mapeamento[0] = [];
            mapeamento[0][ROUTE] = [];
            mapeamento[0][ROUTE]['id_column'] = `uuid_venda`;
            mapeamento[0][ROUTE]['ajax_url'] = `${BASEURL}/venda/getDataGrid/1`;
            mapeamento[0][ROUTE]['order_by'] = [{
                "coluna": 0,
                "metodo": "ASC"
            }];
            mapeamento[0][ROUTE]['columns'] = [{
                    "data": "codigo_venda",
                    "title": "Código"
                },
                {
                    "data": "data_venda",
                    "title": "Data da Venda"
                },
                {
                    "data": "vendedor",
                    "title": "Vendedor",
                    "render": (data) => appFunctions.formatName(data)
                },
                {
                    "data": "cliente",
                    "title": "Cliente",
                },
                {
                    "data": "metodo_pagamento",
                    "title": "Método de Pagamento",
                },
                {
                    "data": "valor_bruto",
                    "title": "Valor Bruto (R$)",
                    "className": "text-end",
                    "render": (data) => `R$ ${convertFunctions.intToReal(data)}`
                },
                {
                    "data": "valor_desconto",
                    "title": "Valor Desconto (R$)",
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
                    "data": "usuario_alteracao",
                    "visible": false,
                    "title": "Alterado por"
                },
                {
                    "data": "uuid_venda",
                    "title": "Ações",
                    "className": "selectorBtnCol"
                }

            ];
            mapeamento[0][ROUTE]['btn_montar'] = true;
            mapeamento[0][ROUTE]['btn'] = [{
                "funcao": "verModal",
                "metodo": "",
                "compare": {
                    operator: "and",
                    expressions: [{
                        column: "codigo_venda",
                        type: "!=",
                        value: null
                    }]
                }
            }];

            // Estornadas
            mapeamento[1] = [];
            mapeamento[1][ROUTE] = [];
            mapeamento[1][ROUTE]['id_column'] = `uuid_venda`;
            mapeamento[1][ROUTE]['ajax_url'] = `${BASEURL}/venda/getDataGrid/0`;
            mapeamento[1][ROUTE]['order_by'] = [{
                "coluna": 0,
                "metodo": "ASC"
            }];
            mapeamento[1][ROUTE]['columns'] = [{
                    "data": "codigo_venda",
                    "title": "Código"
                },
                {
                    "data": "data_venda",
                    "title": "Data da Venda"
                },
                {
                    "data": "estornado_em",
                    "title": "Data do Estorno"
                },
                {
                    "data": "vendedor",
                    "title": "Vendedor",
                    "render": (data) => appFunctions.formatName(data)
                },
                {
                    "data": "cliente",
                    "title": "Cliente",
                },
                {
                    "data": "metodo_pagamento",
                    "title": "Método de Pagamento",
                },
                {
                    "data": "valor_bruto",
                    "title": "Valor Bruto (R$)",
                    "className": "text-end",
                    "render": (data) => `R$ ${convertFunctions.intToReal(data)}`
                },
                {
                    "data": "valor_desconto",
                    "title": "Valor Desconto (R$)",
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
                    "data": "usuario_alteracao",
                    "visible": false,
                    "title": "Alterado por"
                },
                {
                    "data": "uuid_venda",
                    "title": "Ações",
                    "className": "selectorBtnCol"
                }

            ];
            mapeamento[1][ROUTE]['btn_montar'] = true;
            mapeamento[1][ROUTE]['btn'] = [{
                "funcao": "verModal",
                "metodo": "",
                "compare": {
                    operator: "and",
                    expressions: [{
                        column: "codigo_venda",
                        type: "!=",
                        value: null
                    }]
                }
            }];

        },
    };

    document.addEventListener("DOMContentLoaded", () => {
        vendaFunctions.init();
        select2VendaFunctions.init();
        dataGridVendaFunctions.init();
    });
</script>
