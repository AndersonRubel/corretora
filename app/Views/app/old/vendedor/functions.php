<script>
    const select2VendedorFunctions = {
        init: () => {
            select2VendedorFunctions.buscarProduto();
            select2VendedorFunctions.buscarCadastroMetodoPagamento();
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

    const vendedorFunctions = {
        init: () => {
            vendedorFunctions.listenerTipoPessoa();
            vendedorFunctions.listenerBuscarCep();
            vendedorFunctions.listenerFiltros();

            // Atualiza os campos conforme a o tipo de pessoa
            $("#tipoPessoa").trigger('change');
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
                    $("#cpfCnpj").find('label').text('CPF *');

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

                maskFunctions.init();
            });
        },
        listenerBuscarCep: () => {
            $(document).on('keyup', "input[name='cep']", function() {
                const cep = $(this).val();
                if (cep.length >= 9) {
                    appFunctions.buscarCep(cep).then(
                        (retorno) => {
                            if (retorno) {
                                $("input[name='rua']").val(retorno.street);
                                $("input[name='bairro']").val(retorno.neighborhood);
                                $("input[name='cidade']").val(retorno.city);
                                $("input[name='cidade_completa']").val(`${retorno.city}/${retorno.state}`);
                                $("input[name='uf']").val(retorno.state);
                                $("input[name='numero']").val('');
                                $("input[name='numero']").focus();
                            }
                        }
                    )
                }
            });
        },
        listenerFiltros: () => {
            $(document).on('click', "[data-action='btnLimpar']", () => {
                // Busca todos os elementos que possuem o atributo 'data-filtro' e que iniciam com 'filtro_'
                $("[data-filtro^='filtro_']").find("input,select,textarea").val('');
                $("[data-filtro^='filtro_']").find("input").select2('val', '');
                $("[data-action='btnFiltrar']").click();
            });

            $(document).on('click', "[data-action='btnFiltrar']", (e) => {
                e.preventDefault();

                dataGridOptionsFunctions.destroyTable("#tableEstoque");
                dataGridOptionsFunctions.destroyTable("#tableHistoricoVendas");
                dataGridOptionsFunctions.destroyTable("#tableHistoricoFinanceiro");
                dataGridOptionsFunctions.destroyTable("#tableHistoricoSaldo");

                let filtros = [{
                    exibir_pago: $("select[name='exibir_pago']").val(),
                    codigo_vendedor: $("input[name='codigo_vendedor']").val(),
                    codigo_produto: $("input[name='codigo_produto']").val(),
                    codigo_cadastro_metodo_pagamento: $("input[name='codigo_cadastro_metodo_pagamento']").val(),
                    data_inicio: $("input[name='data_inicio']").val(),
                    data_fim: $("input[name='data_fim']").val(),
                }];

                mapeamento[2][ROUTE]['custom_data'] = filtros;
                mapeamento[3][ROUTE]['custom_data'] = filtros;
                mapeamento[4][ROUTE]['custom_data'] = filtros;

                $('#tableEstoque').DataTable(dataGridGlobalFunctions.getSettings(2));
                $('#tableHistoricoVendas').DataTable(dataGridGlobalFunctions.getSettings(3));
                $('#tableHistoricoFinanceiro').DataTable(dataGridGlobalFunctions.getSettings(4));
            })
        },
    };

    const dataGridVendedorFunctions = {
        init: () => {
            dataGridVendedorFunctions.mapeamentoVendedor();
            dataGridVendedorFunctions.mapeamentoVendedorVisualizar();

            if (METODO == 'index') {
                $('#tableAtivos').DataTable(dataGridGlobalFunctions.getSettings(0));
                $('#tableInativos').DataTable(dataGridGlobalFunctions.getSettings(1));
            }

            if (METODO == 'view') {
                $("[data-action='btnFiltrar']").click();
            }
        },
        mapeamentoVendedor: () => {
            // Ativos
            mapeamento[0] = [];
            mapeamento[0][ROUTE] = [];
            mapeamento[0][ROUTE]['id_column'] = `uuid_vendedor`;
            mapeamento[0][ROUTE]['ajax_url'] = `${BASEURL}/vendedor/getDataGrid/1`;
            mapeamento[0][ROUTE]['order_by'] = [{
                "coluna": 0,
                "metodo": "ASC"
            }];
            mapeamento[0][ROUTE]['columns'] = [{
                    "data": "nome",
                    "title": "Nome"
                },
                {
                    "data": "celular",
                    "title": "Celular",
                    "isreplace": true,
                    "render": (data) => convertFunctions.intToPhone(data)
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
                    "data": "uuid_vendedor",
                    "title": "Ações"
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
                    "funcao": "desativar",
                    "metodo": "",
                    "compare": null
                },
            ];

            // Inativos
            mapeamento[1] = [];
            mapeamento[1][ROUTE] = [];
            mapeamento[1][ROUTE]['id_column'] = `uuid_vendedor`;
            mapeamento[1][ROUTE]['ajax_url'] = `${BASEURL}/vendedor/getDataGrid/0`;
            mapeamento[1][ROUTE]['order_by'] = [{
                "coluna": 0,
                "metodo": "ASC"
            }];
            mapeamento[1][ROUTE]['columns'] = [{
                    "data": "nome",
                    "title": "Nome"
                },
                {
                    "data": "celular",
                    "title": "Celular",
                    "isreplace": true,
                    "render": (data) => convertFunctions.intToPhone(data)
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
                    "data": "uuid_vendedor",
                    "title": "Ações"
                }
            ];
            mapeamento[1][ROUTE]['btn_montar'] = true;
            mapeamento[1][ROUTE]['btn'] = [{
                "funcao": "ativar",
                "metodo": "",
                "compare": null
            }, ];
        },
        mapeamentoVendedorVisualizar: () => {
            // Estoque do Vendedor
            mapeamento[2] = [];
            mapeamento[2][ROUTE] = [];
            mapeamento[2][ROUTE]['id_column'] = `uuid_estoque_produto`;
            mapeamento[2][ROUTE]['ajax_url'] = `${BASEURL}/vendedor/getDataGridEstoque`;
            mapeamento[2][ROUTE]['order_by'] = [{
                "coluna": 3,
                "metodo": "ASC"
            }];
            mapeamento[2][ROUTE]['columns'] = [{
                "data": "codigo_produto",
                "title": "Código"
            }, {
                "data": "codigo_barras",
                "title": "Código de Barras"
            }, {
                "data": "referencia_fornecedor",
                "title": "Referência do Fornecedor"
            }, {
                "data": "nome_produto",
                "title": "Nome do Produto"
            }, {
                "data": "nome_estoque",
                "title": "Estoque"
            }, {
                "data": "estoque_atual",
                "title": "Estoque Atual",
                "className": "text-center"
            }, {
                "data": "valor_fabrica",
                "title": "Valor de Custo",
                "className": "text-end",
                "isreplace": true,
                "render": (data) => `R$ ${convertFunctions.intToReal(data)}`
            }, {
                "data": "valor_venda",
                "title": "Valor de Venda",
                "className": "text-end",
                "isreplace": true,
                "render": (data) => `R$ ${convertFunctions.intToReal(data)}`
            }, {
                "data": "valor_ecommerce",
                "title": "Valor de Ecommerce",
                "visible": false,
                "className": "text-end",
                "isreplace": true,
                "render": (data) => `R$ ${convertFunctions.intToReal(data)}`
            }, {
                "data": "valor_atacado",
                "title": "Valor de Atacado",
                "visible": false,
                "className": "text-end",
                "isreplace": true,
                "render": (data) => `R$ ${convertFunctions.intToReal(data)}`
            }, {
                "data": "uuid_estoque_produto",
                "visible": false,
                "title": "Ações",
                "className": "text-center"
            }];

            // Histórico de Venda
            mapeamento[3] = [];
            mapeamento[3][ROUTE] = [];
            mapeamento[3][ROUTE]['id_column'] = `uuid_venda_produto`;
            mapeamento[3][ROUTE]['ajax_url'] = `${BASEURL}/vendedor/getDataGridHistoricoVenda`;
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
            mapeamento[4][ROUTE]['ajax_url'] = `${BASEURL}/vendedor/getDataGridHistoricoFinanceiro`;
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
            }];

        }
    };

    document.addEventListener("DOMContentLoaded", () => {
        vendedorFunctions.init();
        select2VendedorFunctions.init();
        dataGridVendedorFunctions.init();
    });
</script>
