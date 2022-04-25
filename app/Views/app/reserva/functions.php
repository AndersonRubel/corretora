<script>
    const select2ReservaFunctions = {
        init: () => {
            select2ReservaFunctions.buscarCliente();
            select2ReservaFunctions.buscarImovel();
            select2ReservaFunctions.buscarCategoriaImovel();
            select2ReservaFunctions.buscarTipoImovel();
        },
        buscarCliente: (caller) => {
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
        buscarImovel: (caller) => {
            let elementSelect2 = $("[data-select='buscarImovel']");
            let url = `${BASEURL}/imovel/backendCall/selectImovel`;
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
        buscarCategoriaImovel: (caller) => {
            let elementSelect2 = $("[data-select='buscarCategoriaImovel']");
            let url = `${BASEURL}/cadastro/selectCategoriaImovel`;
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
        buscarTipoImovel: (caller) => {
            let elementSelect2 = $("[data-select='buscarTipoImovel']");
            let url = `${BASEURL}/cadastro/selectTipoImovel`;
            elementSelect2.select2({
                placeholder: "Selecione...",
                allowClear: false,
                multiple: false,
                quietMillis: 2000,
                minimumInputLength: 0,
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
                            page: 1,
                            exibirValores: 1,

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
                            exibirValores: 1,

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

    const reservaFunctions = {
        init: () => {
            reservaFunctions.listenerCliente();
            reservaFunctions.listenerFiltros();
            reservaFunctions.listenerModalHelp();

        },
        listenerModalHelp: () => {
            $(document).on('click', "#btnHelp", () => {
                $("#modalHelp").modal('show');
            });
        },

        listenerCliente: () => {

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
                                $("#modalCadastrarCliente input[name='bairro']").val(retorno
                                    .neighborhood);
                                $("#modalCadastrarCliente input[name='cidade']").val(retorno.city);
                                $("#modalCadastrarCliente input[name='cidade_completa']").val(
                                    `${retorno.city}/${retorno.state}`);
                                $("#modalCadastrarCliente input[name='uf']").val(retorno.state);
                                $("#modalCadastrarCliente input[name='numero']").val('');
                                $("#modalCadastrarCliente input[name='numero']").focus();
                            }
                        }
                    )
                }
            });

            // Realiza o salvamento do registro
            $(document).on('click', "[data-action='salvarClienteSimplificado']", async function() {
                if (!$("#modalCadastrarCliente form")[0].reportValidity()) return false;

                await appFunctions.backendCall('POST', `cliente/storeSimplificado`, {
                    nome_fantasia: $("#modalCadastrarCliente input[name='nome_fantasia']")
                        .val(),
                    cpf_cnpj: $("#modalCadastrarCliente input[name='cpf_cnpj']").val(),
                    email: $("#modalCadastrarCliente input[name='email']").val(),
                    data_nascimento: $("#modalCadastrarCliente input[name='data_nascimento']")
                        .val(),
                    telefone: $("#modalCadastrarCliente input[name='telefone']").val(),
                    celular: $("#modalCadastrarCliente input[name='celular']").val(),
                    cep: $("#modalCadastrarCliente input[name='cep']").val(),
                    rua: $("#modalCadastrarCliente input[name='rua']").val(),
                    numero: $("#modalCadastrarCliente input[name='numero']").val(),
                    bairro: $("#modalCadastrarCliente input[name='bairro']").val(),
                    complemento: $("#modalCadastrarCliente input[name='complemento']").val(),
                    cidade: $("#modalCadastrarCliente input[name='cidade']").val(),
                    uf: $("#modalCadastrarCliente input[name='uf']").val(),
                }).then(res => {
                    if (res && res.cliente) {
                        $("[data-select='buscarCliente']").select2('val', res.cliente);
                        $("#modalCadastrarCliente").modal('hide');
                    }
                }).catch(err => notificationFunctions.toastSmall(err.textStatus, err.mensagem));
            });

        },
        listenerFiltros: () => {

            $(document).on('click', "[data-action='btnLimpar']", () => {
                // Busca todos os elementos que possuem o atributo 'data-filtro' e que iniciam com 'filtro_'
                $("[data-filtro^='filtro_']").find("input,select,textarea").val('');
                $("[data-filtro^='filtro_']").find("input").select2('val', '');
                $('#tableAtivos').DataTable(dataGridGlobalFunctions.getSettings(0));
                $('#tableInativos').DataTable(dataGridGlobalFunctions.getSettings(1));
            });

            $(document).on('click', "[data-action='btnFiltrar']", (e) => {
                e.preventDefault();
                if ($.fn.DataTable.isDataTable("#tableAtivos")) {
                    $('#tableAtivos').DataTable().destroy();
                }
                if ($.fn.DataTable.isDataTable("#tableInativos")) {
                    $('#tableInativos').DataTable().destroy();
                }

                let filtros = [{
                    codigo_imovel: $("input[name='codigo_imovel']").val(),
                    codigo_tipo_imovel: $("input[name='codigo_tipo_imovel']").val(),
                    codigo_categoria_imovel: $("input[name='codigo_categoria_imovel']").val(),
                    codigo_cliente: $("input[name='codigo_cliente']").val(),
                }];

                mapeamento[0][ROUTE]['custom_data'] = filtros;
                mapeamento[1][ROUTE]['custom_data'] = filtros;

                $('#tableAtivos').DataTable(dataGridGlobalFunctions.getSettings(0));
                $('#tableInativos').DataTable(dataGridGlobalFunctions.getSettings(1));
            })
        },
    };

    const dataGridReservaFunctions = {
        init: () => {
            dataGridReservaFunctions.mapeamentoReserva();

            if (METODO == 'index') {
                $('#tableAtivos').DataTable(dataGridGlobalFunctions.getSettings(0));
                $('#tableInativos').DataTable(dataGridGlobalFunctions.getSettings(1));
            }
        },
        mapeamentoReserva: () => {
            // Ativos
            mapeamento[0] = [];
            mapeamento[0][ROUTE] = [];
            mapeamento[0][ROUTE]['id_column'] = `uuid_reserva`;
            mapeamento[0][ROUTE]['ajax_url'] = `${BASEURL}/reserva/getDataGrid/1`;
            mapeamento[0][ROUTE]['order_by'] = [{
                "coluna": 1,
                "metodo": "ASC"
            }];
            mapeamento[0][ROUTE]['columns'] = [{
                    "data": "codigo_reserva",
                    "title": "Código"
                },
                {
                    "data": "codigo_referencia",
                    "title": "Referência"
                },
                {
                    "data": "nome_cliente",
                    "title": "Cliente"
                },
                {
                    "data": "data_inicio",
                    "title": "Data Início"
                },
                {
                    "data": "data_fim",
                    "title": "Data Fim"
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
                    "data": "uuid_reserva",
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
            mapeamento[1][ROUTE]['id_column'] = `uuid_reserva`;
            mapeamento[1][ROUTE]['ajax_url'] = `${BASEURL}/reserva/getDataGrid/0`;
            mapeamento[1][ROUTE]['order_by'] = [{
                "coluna": 1,
                "metodo": "ASC"
            }];
            mapeamento[1][ROUTE]['columns'] = [{
                    "data": "codigo_reserva",
                    "title": "Código"
                },
                {
                    "data": "codigo_referencia",
                    "title": "Referência"
                },
                {
                    "data": "nome_cliente",
                    "title": "Cliente"
                },
                {
                    "data": "data_inicio",
                    "title": "Data Início"
                },
                {
                    "data": "data_fim",
                    "title": "Data Fim"
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
                    "data": "uuid_reserva",
                    "title": "Ações",
                    "className": "text-center"
                }
            ];
            mapeamento[1][ROUTE]['btn_montar'] = true;
            mapeamento[1][ROUTE]['btn'] = [{
                "funcao": "visualizar",
                "metodo": "visualizar",
                "compare": null
            }, ];
        },
    }

    document.addEventListener("DOMContentLoaded", () => {
        reservaFunctions.init();
        select2ReservaFunctions.init();
        dataGridReservaFunctions.init();
    });
</script>
