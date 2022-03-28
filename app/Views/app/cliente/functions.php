<script>
    const select2ClienteFunctions = {
        init: () => {
            select2ClienteFunctions.buscarCliente();
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
    };

    const clienteFunctions = {
        init: () => {
            clienteFunctions.listenerTipoPessoa();
            clienteFunctions.listenerBuscarCep();
            clienteFunctions.listenerFiltros();
            clienteFunctions.listenerModalHelp();
            // Atualiza os campos conforme a o tipo de pessoa
            $("#tipoPessoa").trigger('change');
        },
        listenerModalHelp: () => {
            $(document).on('click', "#btnHelp", () => {
                $("#modalHelp").modal('show');
            });
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
                dataGridOptionsFunctions.destroyTable("#tableHistoricoImovel");
                dataGridOptionsFunctions.destroyTable("#tableHistoricoFinanceiro");
                dataGridOptionsFunctions.destroyTable("#tableHistoricoSaldo");

                let filtros = [{
                    exibir_pago: $("select[name='exibir_pago']").val(),
                    codigo_cliente: $("input[name='codigo_cliente']").val(),
                    codigo_imovel: $("input[name='codigo_imovel']").val(),
                    codigo_cadastro_metodo_pagamento: $(
                        "input[name='codigo_cadastro_metodo_pagamento']").val(),
                    data_inicio: $("input[name='data_inicio']").val(),
                    data_fim: $("input[name='data_fim']").val(),
                }];

                mapeamento[2][ROUTE]['custom_data'] = filtros;
                mapeamento[3][ROUTE]['custom_data'] = filtros;
                mapeamento[4][ROUTE]['custom_data'] = filtros;
                mapeamento[5][ROUTE]['custom_data'] = filtros;

                $('#tableExtrato').DataTable(dataGridGlobalFunctions.getSettings(2));
                $('#tableHistoricoImovel').DataTable(dataGridGlobalFunctions.getSettings(3));
                $('#tableHistoricoFinanceiro').DataTable(dataGridGlobalFunctions.getSettings(4));
                $('#tableHistoricoSaldo').DataTable(dataGridGlobalFunctions.getSettings(5));
            })
        },
        listenerTipoPessoa: () => {
            $(document).on('change', "#tipoPessoa", function() {

                if ($(this).val() !== $('#tipoPessoa').val()) {
                    // Zera o valor dos campos
                    $("input[name='razao_social'], input[name='nome_fantasia'], input[name='cpf_cnpj']")
                        .val('');
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
            $(document).on('keyup', "input[name='cep']", function() {
                const cep = $(this).val();
                if (cep.length >= 9) {
                    appFunctions.buscarCep(cep).then(
                        (retorno) => {
                            if (retorno) {
                                $("input[name='rua']").val(retorno.street);
                                $("input[name='bairro']").val(retorno.neighborhood);
                                $("input[name='cidade']").val(retorno.city);
                                $("input[name='cidade_completa']").val(
                                    `${retorno.city}/${retorno.state}`);
                                $("input[name='uf']").val(retorno.state);
                                $("input[name='numero']").val('');
                                $("input[name='numero']").focus();
                            }
                        }
                    )
                }
            });
        },
    };

    const dataGridClienteFunctions = {
        init: () => {
            dataGridClienteFunctions.mapeamentoCliente();

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
                    "data": "criado_em",
                    "visible": false,
                    "title": "Criado em"
                },
                {
                    "data": "alterado_em",
                    "visible": false,
                    "title": "Alterado em"
                },
                {
                    "data": "uuid_cliente",
                    "title": "Ações",
                    "className": "text-center"
                }
            ];
            mapeamento[0][ROUTE]['btn_montar'] = true;
            mapeamento[0][ROUTE]['btn'] = [{
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
                    "data": "criado_em",
                    "visible": false,
                    "title": "Criado em"
                },
                {
                    "data": "alterado_em",
                    "visible": false,
                    "title": "Alterado em"
                },
                {
                    "data": "inativado_em",
                    "visible": false,
                    "title": "Inativado em"
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
    };

    document.addEventListener("DOMContentLoaded", () => {
        clienteFunctions.init();
        select2ClienteFunctions.init();
        dataGridClienteFunctions.init();
    });
</script>
