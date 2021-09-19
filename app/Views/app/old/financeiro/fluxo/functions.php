<script>
    const select2FinanceiroFluxoFunctions = {
        init: () => {
            select2FinanceiroFluxoFunctions.buscarEmpresa();
            select2FinanceiroFluxoFunctions.buscarCliente();
            select2FinanceiroFluxoFunctions.buscarFornecedor();
            select2FinanceiroFluxoFunctions.buscarUsuario();
            select2FinanceiroFluxoFunctions.buscarVendedor();
            select2FinanceiroFluxoFunctions.buscarEmpresaConta();
            select2FinanceiroFluxoFunctions.buscarCadastroMetodoPagamento();
            select2FinanceiroFluxoFunctions.buscarCadastroFluxoTipo();
            select2FinanceiroFluxoFunctions.buscarEmpresaCentroCusto();
        },
        buscarEmpresa: () => {
            let elementSelect2 = $("[data-select='buscarEmpresa']");
            let url = `${BASEURL}/empresa/backendCall/selectEmpresa`;
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
        buscarFornecedor: () => {
            let elementSelect2 = $("[data-select='buscarFornecedor']");
            let url = `${BASEURL}/fornecedor/backendCall/selectFornecedor`;
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
        buscarUsuario: () => {
            let elementSelect2 = $("[data-select='buscarUsuario']");
            let url = `${BASEURL}/usuario/backendCall/selectUsuario`;
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
        buscarEmpresaConta: () => {
            let elementSelect2 = $("[data-select='buscarEmpresaConta']");
            let url = `${BASEURL}/empresa/backendCall/selectEmpresaConta`;
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
        buscarCadastroFluxoTipo: () => {
            let elementSelect2 = $("[data-select='buscarCadastroFluxoTipo']");
            let url = `${BASEURL}/cadastro/selectCadastroFluxoTipo`;
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
        buscarEmpresaCentroCusto: () => {
            let elementSelect2 = $("[data-select='buscarEmpresaCentroCusto']");
            let url = `${BASEURL}/empresa/backendCall/selectEmpresaCentroCusto`;
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
        }
    };

    const financeiroFluxoFunctions = {
        init: () => {
            financeiroFluxoFunctions.listenerFiltros();
            financeiroFluxoFunctions.listenerAlternaCamposFluxo();
            financeiroFluxoFunctions.listenerAlternaTipoOcorrencia();
            financeiroFluxoFunctions.listenerAlternaTipoSituacao();
            financeiroFluxoFunctions.calculoValorTotal();
            financeiroFluxoFunctions.gerarParcelas();
            financeiroFluxoFunctions.validaOnSubmit();
            financeiroFluxoFunctions.listenerVerSaldo();
            financeiroFluxoFunctions.listenerModalVerFluxo();
            financeiroFluxoFunctions.listenerMarcarComoPago();
            financeiroFluxoFunctions.listenerMarcarComoPendente();
            financeiroFluxoFunctions.listenerPagamentoParcial();
            financeiroFluxoFunctions.listenerGraficoResumo();
            financeiroFluxoFunctions.listenerImprimirRecibo();
            financeiroFluxoFunctions.listenerImprimirComprovante();

            // Ativa o Gráfico
            $("#selectorDataGrafico").trigger('change');
        },
        listenerFiltros: () => {
            $(document).on('click', "[data-action='btnLimpar']", () => {
                // Busca todos os elementos que possuem o atributo 'data-filtro' e que iniciam com 'filtro_'
                $("[data-filtro^='filtro_']").find("input,select,textarea").val('');
                $("[data-filtro^='filtro_']").find("input").select2('val', '');
                $('#tableFluxo').DataTable(dataGridGlobalFunctions.getSettings(0));
                financeiroFluxoFunctions.calculaSumario();
            });

            $(document).on('click', "[data-action='btnFiltrar']", (e) => {
                e.preventDefault();
                dataGridOptionsFunctions.destroyTable("#tableFluxo");

                mapeamento[0][ROUTE]['custom_data'] = [{
                    codigo_empresa: $("input[name='codigo_empresa']").val(),
                    tipo_data: $("select[name='tipo_data']").val(),
                    data_inicio: $("input[name='data_inicio']").val(),
                    data_fim: $("input[name='data_fim']").val(),
                    data_competencia: $("input[name='data_competencia']").val(),
                    codigo_empresa_conta: $("input[name='codigo_empresa_conta']").val(),
                    codigo_empresa_centro_custo: $("input[name='codigo_empresa_centro_custo']").val(),
                    codigo_cadastro_fluxo_tipo: $("input[name='codigo_cadastro_fluxo_tipo']").val(),
                    situacao: $("select[name='situacao']").val(),
                    codigo_cadastro_metodo_pagamento: $("input[name='codigo_cadastro_metodo_pagamento']").val(),
                    codigo_fornecedor: $("input[name='codigo_fornecedor']").val(),
                    codigo_cliente: $("input[name='codigo_cliente']").val(),
                    codigo_usuario: $("input[name='codigo_usuario']").val(),
                    insercao_automatica: $("select[name='insercao_automatica']").val(),
                    incluir_estorno: $("select[name='incluir_estorno']").val()
                }];

                $('#tableFluxo').DataTable(dataGridGlobalFunctions.getSettings(0));
                financeiroFluxoFunctions.calculaSumario();
            })
        },
        listenerAlternaCamposFluxo: () => {
            // Inicializa a tela escondendo os campos AVANÇADO
            $("[data-tipo='avancado']").addClass('d-none');

            $(document).on('click', "[data-action='alternaCampos']", function() {
                $("[data-action='alternaCampos']").removeAttr('disabled');
                $(this).prop('disabled', true);

                if ($(this).val() == 'Avançado') {
                    $("[data-tipo='avancado']").removeClass('d-none');
                } else {
                    $("[data-tipo='avancado']").addClass('d-none');
                }
            });
        },
        listenerAlternaTipoOcorrencia: () => {
            $(document).on('change', "select[name='ocorrencia']", function() {
                if ($(this).val() == 'P') {
                    // Exibe o Card de Parcelamento
                    $("#cardOcorrenciaParcelada").removeClass('d-none');

                    $("#selectorDataVencimento").find('input').prop('required', false).val('');
                    $("#selectorDataVencimento").addClass('d-none');

                    $("#selectorSituacao").find('select').val('f').change();
                    $("#selectorSituacao").addClass('d-none');

                    $("#selectorObservacao").find('textarea').val('');
                    $("#selectorObservacao").addClass('d-none');

                    // Coloca Obrigatoriedade nos campos
                    $("select[name='parcelamento_tipo']").prop("required", true);
                    $("select[name='parcelamento_periodo']").prop("required", true);
                    $("select[name='parcelamento_quantidade']").prop("required", true);
                    $("select[name='parcelamento_data_primeira_parcela']").prop("required", true);
                } else if ($(this).val() == 'U') {
                    // Oculta o Card de Parcelamento
                    $("#cardOcorrenciaParcelada").addClass('d-none');

                    $("#selectorDataVencimento").removeClass('d-none');
                    $("#selectorDataVencimento").find('input').prop('required', true).val('');

                    $("#selectorSituacao").removeClass('d-none');
                    $("#selectorSituacao").find('select').val('f');

                    $("#selectorObservacao").removeClass('d-none');
                    $("#selectorObservacao").find('textarea').val('');

                    // Remove Obrigatoriedade nos campos
                    $("select[name='parcelamento_tipo']").prop("required", false);
                    $("select[name='parcelamento_periodo']").prop("required", false);
                    $("select[name='parcelamento_quantidade']").prop("required", false);
                    $("select[name='parcelamento_data_primeira_parcela']").prop("required", false);
                }
            });
        },
        listenerAlternaTipoSituacao: () => {
            $(document).on('change', "select[name='situacao']", function() {
                if ($(this).val() == 't') {
                    $("#selectorDataPagamento").removeClass('d-none');
                    $("#selectorDataPagamento").find('input').prop('required', true);
                    $("#selectorDataPagamento").find('input').val(moment().format('YYYY-MM-DD')).focus();
                } else {
                    $("#selectorDataPagamento").find('input').removeAttr('required');
                    $("#selectorDataPagamento").find('input').val('');
                    $("#selectorDataPagamento").addClass('d-none');
                }

                appFunctions.addInputLabelRequired();
            });
        },
        listenerVerSaldo: () => {
            $(document).on('click', "[data-action='btnVerSaldo']", async function(e) {
                // Busca os dados do Fluxo
                await appFunctions.backendCall('POST', `financeiro/backendCall/selectFluxoSumarioSaldo`, {
                    dados: mapeamento[0][ROUTE]['custom_data']
                }).then((res) => {
                    // Atribui os valores na tela
                    $("#sumario #selectorSaldo input").val(`R$ ${convertFunctions.intToReal(res)}`);

                    if (convertFunctions.intToReal(res) > 0) {
                        $("#sumario #selectorSaldo input").addClass('text-success');
                    } else if (convertFunctions.intToReal(res) < 0) {
                        $("#sumario #selectorSaldo input").addClass('text-danger');
                    }

                    $("#selectorBtnVerSaldo").addClass('d-none');
                    $("#selectorSaldo").removeClass('d-none');
                }).catch(err => notificationFunctions.toastSmall('error', 'Saldo não encontrado.'));
            });

            // Oculta o Saldo
            $(document).on('click', "[data-action='btnOcultarSaldo']", function(e) {
                $("#sumario #selectorSaldo input").val('');
                $("#selectorBtnVerSaldo").removeClass('d-none');
                $("#selectorSaldo").addClass('d-none');
            });
        },
        listenerModalVerFluxo: () => {
            $(document).on('click', "[data-action='verRegistroModal']", async function(e) {
                let fluxoUuid = $(this).data('id');

                await appFunctions.backendCall('POST', `financeiro/backendCall/selectFluxo`, {
                        codUuid: fluxoUuid,
                        page: 1
                    })
                    .then((res) => {
                        if (res && res.itens.length > 0) {
                            res = res.itens[0];
                            // Atribui os valores nos campos
                            $("#modalVisualizarFluxo input[name='codigo_cadastro_fluxo_tipo']").select2('val', res.codigo_cadastro_fluxo_tipo);
                            $("#modalVisualizarFluxo input[name='nome']").val(res.nome);
                            $("#modalVisualizarFluxo input[name='codigo_empresa_centro_custo']").select2('val', res.codigo_empresa_centro_custo);
                            $("#modalVisualizarFluxo input[name='valor_bruto']").val(convertFunctions.intToReal(res.valor_bruto));
                            $("#modalVisualizarFluxo input[name='valor_juros']").val(convertFunctions.intToReal(res.valor_juros));
                            $("#modalVisualizarFluxo input[name='valor_acrescimo']").val(convertFunctions.intToReal(res.valor_acrescimo));
                            $("#modalVisualizarFluxo input[name='valor_desconto']").val(convertFunctions.intToReal(res.valor_desconto));
                            $("#modalVisualizarFluxo input[name='valor_pago_parcial']").val(convertFunctions.intToReal(res.valor_pago_parcial));
                            $("#modalVisualizarFluxo select[name='situacao']").val(res.situacao);
                            $("#modalVisualizarFluxo input[name='data_vencimento']").val(res.data_vencimento);
                            $("#modalVisualizarFluxo input[name='data_pagamento']").val(res.data_pagamento);
                            $("#modalVisualizarFluxo input[name='data_competencia']").val(res.data_competencia);
                            $("#modalVisualizarFluxo input[name='codigo_empresa_conta']").select2('val', res.codigo_empresa_conta);
                            $("#modalVisualizarFluxo input[name='codigo_cadastro_metodo_pagamento']").select2('val', res.codigo_cadastro_metodo_pagamento);
                            $("#modalVisualizarFluxo input[name='codigo_barras']").val(res.codigo_barras);
                            $("#modalVisualizarFluxo input[name='codigo_fornecedor']").select2('val', res.codigo_fornecedor);
                            $("#modalVisualizarFluxo input[name='codigo_cliente']").select2('val', res.codigo_cliente);
                            $("#modalVisualizarFluxo input[name='codigo_vendedor']").select2('val', res.codigo_vendedor);
                            $("#modalVisualizarFluxo textarea[name='observacao']").val(res.observacao);
                            $("#modalVisualizarFluxo [data-action='btnImprimirRecibo']").attr('data-id', res.uuid_financeiro_fluxo);
                            $("#modalVisualizarFluxo [data-action='btnImprimirComprovante']").attr('data-id', res.uuid_financeiro_fluxo);

                            $("#modalVisualizarFluxo").modal('show');

                            // Para aplicar as mascaras
                            $("input[name='valor_bruto']").trigger('keyup');
                        } else {
                            notificationFunctions.toastSmall('error', 'Fluxo não encontrado.')
                        }
                    }).catch(err => notificationFunctions.toastSmall('error', 'Fluxo não encontrado.'));

            });
        },
        listenerMarcarComoPago: () => {
            $(document).on('click', "[data-action='fluxoMarcarPago']", function(e) {
                let fluxoUuid = $(this).data('id');
                notificationFunctions.popupConfirm('Atenção', 'Deseja realmente marcar esse fluxo como Pago?', 'warning').then(
                    async (result) => {
                        if (result.value) {
                            await appFunctions.backendCall('POST', `financeiro/marcarPago/${fluxoUuid}`)
                                .then(res => {
                                    $("[data-action='btnFiltrar']").trigger('click');
                                    notificationFunctions.toastSmall(res.textStatus, res.mensagem);
                                }).catch(err => notificationFunctions.toastSmall(err.textStatus, err.mensagem));
                        }
                    }
                );
            });
        },
        listenerMarcarComoPendente: () => {
            $(document).on('click', "[data-action='fluxoMarcarPendente']", function(e) {
                let fluxoUuid = $(this).data('id');
                notificationFunctions.popupConfirm('Atenção', 'Deseja realmente marcar esse fluxo como Pendente?', 'warning').then(
                    async (result) => {
                        if (result.value) {
                            await appFunctions.backendCall('POST', `financeiro/marcarPendente/${fluxoUuid}`)
                                .then(res => {
                                    $("[data-action='btnFiltrar']").trigger('click');
                                    notificationFunctions.toastSmall(res.textStatus, res.mensagem);
                                }).catch(err => notificationFunctions.toastSmall(err.textStatus, err.mensagem));
                        }
                    }
                );
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
                    })
                    .then((res) => {
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
                            await appFunctions.backendCall('POST', `financeiro/removerPagamentoParcial/${fluxoUuidParcial}`)
                                .then(res => {
                                    $("#modalFluxoParcial").modal('hide');
                                    $("[data-action='btnFiltrar']").trigger('click');
                                    notificationFunctions.toastSmall(res.textStatus, res.mensagem);
                                }).catch(err => notificationFunctions.toastSmall(err.textStatus, err.mensagem));
                        }
                    }
                );
            });

        },
        listenerGraficoResumo: () => {
            $(document).on("change", '#selectorDataGrafico', async function() {
                let inputData = new Date(`${$(this).val()}-01 12:00:00`);
                let inputDataMes = inputData.getMonth() + 1;
                let inputDataAno = inputData.getFullYear();
                inputDataMes = inputDataMes < 10 ? '0' + inputDataMes : inputDataMes;

                await appFunctions.backendCall('GET', `financeiro/getGraficoResumo`, {
                    data: $(this).val()
                }).then(res => {
                    let ctx = document.querySelector(`#graficoMes`);
                    if ($(ctx).hasClass('chartjs-render-monitor')) {
                        myChart.destroy();
                    }

                    const config = {
                        type: 'line',
                        data: {
                            labels: res.categorias_mes,
                            datasets: [{
                                    label: 'Receitas',
                                    data: res.receber,
                                    borderColor: '#5cb85c',
                                    backgroundColor: '#5cb85c',
                                },
                                {
                                    label: 'Despesas',
                                    data: res.pagar,
                                    borderColor: '#d9534f',
                                    backgroundColor: '#d9534f',
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                title: {
                                    display: true,
                                    text: `Receitas e Despesas do Mês ${inputDataMes}/${inputDataAno}`
                                },
                                subtitle: {
                                    display: true,
                                    text: `Valores em R$`
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.dataset.label || '';
                                            let dia = new Date(`${$("#selectorDataGrafico").val()}-${context.label || '01'} 12:00:00`);
                                            let diasSemana = new Array("Domingo", "Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado");

                                            if (label) label += ': ';

                                            if (context.parsed.y !== null) {
                                                label += Intl.NumberFormat('pt-br', {
                                                    style: 'currency',
                                                    currency: 'BRL',
                                                    minimumFractionDigits: 2
                                                }).format(context.parsed.y)
                                            }

                                            context.label = diasSemana[dia.getDay()];

                                            return label;
                                        }
                                    }
                                }
                            }
                        },
                    };

                    myChart = new Chart(ctx.getContext('2d'), config)

                }).catch(err => notificationFunctions.toastSmall(err.textStatus, err.mensagem));

            });
        },
        calculoValorTotal: () => {
            $(document).on('keyup', "input[name='valor_bruto'], input[name='valor_juros'], input[name='valor_acrescimo'], input[name='valor_desconto']", function(e) {
                valorLiquido = 0;

                valorBruto = convertFunctions.onlyNumber($("input[name='valor_bruto']").val()) || 0;
                valorJuro = convertFunctions.onlyNumber($("input[name='valor_juros']").val()) || 0;
                valorAcrescimo = convertFunctions.onlyNumber($("input[name='valor_acrescimo']").val()) || 0;
                valorDesconto = convertFunctions.onlyNumber($("input[name='valor_desconto']").val()) || 0;

                valorLiquido = (valorBruto + valorJuro + valorAcrescimo) - valorDesconto;

                $("#calculoValorTotal").text(convertFunctions.intToReal(valorLiquido.toString()));

                // Se a Ocorrencia for Parcelada/Recorrencia, realiza o recalculo dos valores tambem
                if ($("select[name='ocorrencia']").val() == 'P' &&
                    $("input[name='parcelamento_quantidade']").val() != '' &&
                    $("input[name='parcelamento_quantidade']").val() != '0,00' &&
                    $("input[name='parcelamento_data_primeira_parcela']").val() != '' &&
                    $('#cardOcorrenciaParcelada').is(':visible')
                ) {
                    $("[data-action='btnGerarParcelasPag']").click()
                }
            });
        },
        gerarParcelas: () => {
            $(document).on('click', "[data-action='btnGerarParcelasPag']", function() {

                if ($("input[name='valor_bruto']").val() == '' || $("input[name='valor_bruto']").val() == '0,00') {
                    notificationFunctions.toastSmall('error', 'O campo Valor é obrigatório para gerar as parcelas.');
                    $("input[name='valor_bruto']").focus();
                    return;
                }

                if ($("input[name='parcelamento_quantidade']").val() == '') {
                    notificationFunctions.toastSmall('error', 'O campo quantidade é obrigatório.');
                    $("input[name='parcelamento_quantidade']").focus();
                    return;
                }

                if ($("input[name='parcelamento_quantidade']").val() <= '1') {
                    notificationFunctions.toastSmall('error', 'O campo quantidade deve ser maior que 1.');
                    $("input[name='parcelamento_quantidade']").focus();
                    return;
                }

                if ($("input[name='parcelamento_data_primeira_parcela']").val() == '') {
                    notificationFunctions.toastSmall('error', 'O campo data da 1ª parcela é obrigatório.');
                    $("input[name='parcelamento_data_primeira_parcela']").focus();
                    return;
                }

                //Gera as Parcelas
                let dados = [];
                let tipo = $("select[name='parcelamento_tipo']").val();
                let repeticao = $("select[name='parcelamento_periodo']").val();
                let quantidade = $("input[name='parcelamento_quantidade']").val();
                let dataPrimeiraParcela = $("input[name='parcelamento_data_primeira_parcela']").val();

                let html = `<div class="table-responsive">
                                <table class="table table-bordered table-sm" cellspacing="0">
                                    <thead>
                                        <tr class="fw-bold">
                                            <td class="text-center">Data</td>
                                            <td class="text-center">Valor (R$)</td>
                                            <td class="text-center">Pago</td>
                                            <td class="text-center">Observação</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                            `;

                for (let i = 0; i <= quantidade - 1; i++) {
                    dados[i] = [];

                    html += '<tr style="vertical-align: middle;">';

                    // Datas
                    if (repeticao == 'M') { // Mensal
                        dados[i]['data'] = moment(dataPrimeiraParcela).add(i, 'M').format('YYYY-MM-DD');
                    } else if (repeticao == 'Q') { // Quinezal
                        dados[i]['data'] = moment(dataPrimeiraParcela).add(i * '15', 'days').format('YYYY-MM-DD');
                    } else if (repeticao == 'W') { // Semanal
                        dados[i]['data'] = moment(dataPrimeiraParcela).add(i, 'week').format('YYYY-MM-DD');
                    } else if (repeticao == 'T') { // Trimestral
                        dados[i]['data'] = moment(dataPrimeiraParcela).add(i * 3, 'month').format('YYYY-MM-DD');
                    } else if (repeticao == 'S') { // Semestral
                        dados[i]['data'] = moment(dataPrimeiraParcela).add(i * 6, 'month').format('YYYY-MM-DD');
                    } else if (repeticao == 'A') { // Anual
                        dados[i]['data'] = moment(dataPrimeiraParcela).add(i, 'year').format('YYYY-MM-DD');
                    }

                    // Valores
                    let valorLiquido = convertFunctions.realToNumeric($("#calculoValorTotal").text());

                    if (tipo == 'D') { // Divide o Valor total nas Parcelas
                        valorLiquido = (valorLiquido / quantidade);
                        dados[i]['valor'] = appFunctions.numberFormat(valorLiquido, 2, ',', '.');
                    } else if (tipo == 'M') { // Multiplica o valor total nas parcelas (Recebe ele mesmo)
                        dados[i]['valor'] = appFunctions.numberFormat(valorLiquido, 2, ',', '.');
                    }

                    html += `<td>
                                <input type="date" class="form-control" name="parcelas[${i}][data]" value="${dados[i]['data']}" />
                            </td>
                            <td>
                                <input type="text" class="form-control text-end" name="parcelas[${i}][valor]" value="${dados[i]['valor']}" readonly />
                            </td>
                            <td>
                                <select class="form-control text-center" name="parcelas[${i}][status]">
                                    <option value="f" selected>Não</option>
                                    <option value="t">Sim</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="parcelas[${i}][observacao]" />
                            </td>`;

                    html += '</tr>';

                }

                html += '</tbody></table></div>';
                $('#divParcelas').html('');
                $('#divParcelas').html(html);
            });
            appFunctions.tooltip();
        },
        validaOnSubmit: () => {
            $(document).on('click', "[data-action='realizarSubmit']", async function(e) {
                // Realiza Validações antes de realizar o Submit do formulário

                // Se a ocorrencia for UNICA, deve obrigatoriamente ter uma data de Vencimento
                if ($("select[name='ocorrencia']").val() == 'U') {
                    if ($("input[name='data_vencimento']").val() == '') {
                        e.preventDefault();
                        notificationFunctions.toastSmall('error', 'O campo data de vencimento é obrigatório.');
                        $("input[name='data_vencimento']").focus();
                        return;
                    }
                }

                // Se for marcado como PAGO, deve obrigatoriamente ter uma data de Pagamento
                if ($("select[name='situacao']").val() == 't') {
                    if ($("input[name='data_pagamento']").val() == '') {
                        e.preventDefault();
                        notificationFunctions.toastSmall('error', 'O campo data de pagamento é obrigatório.');
                        $("input[name='data_pagamento']").focus();
                        return;
                    }
                }

                // Valida Fluxo para apenas dia da Semana
                // let diaSemana = new Date($("input[name='data_vencimento']").val());
                // if ($.inArray(diaSemana.getDay(), [5, 6]) !== -1) {
                //     const res = await notificationFunctions.popupConfirm('Atenção', 'Você está gerando um fluxo para um dia não útil (Final de Semana). Deseja continuar?', 'warning').then(result => result);
                //     if (res.isDismissed == true) {
                //         e.preventDefault();
                //         return;
                //     }
                // }

                $("input[name='state_submit']").val($(this).data('state'));
            });
        },
        calculaSumario: async () => {
            await appFunctions.backendCall('POST', `financeiro/backendCall/selectFluxoSumarioTotais`, {
                dados: mapeamento[0][ROUTE]['custom_data']
            }).then((res) => {
                if (res) {
                    // Atribui os valores na tela
                    $("#sumario #selectorReceitaPaga input").val(`R$ ${convertFunctions.intToReal(res[0].valor)}`);
                    $("#sumario #selectorDespesaPaga input").val(`R$ ${convertFunctions.intToReal(res[1].valor)}`);
                    $("#sumario #selectorBalancoReceitaDespesa input").val(`R$ ${convertFunctions.intToReal(res[0].valor - res[1].valor)}`);

                    $("#sumario #selectorReceitaPendente input").val(`R$ ${convertFunctions.intToReal(res[2].valor)}`);
                    $("#sumario #selectorDespesaPendente input").val(`R$ ${convertFunctions.intToReal(res[3].valor)}`);
                    $("#sumario #selectorSaldo input").val('');

                    $("#selectorBtnVerSaldo").removeClass('d-none');
                    $("#selectorSaldo").addClass('d-none');
                }
            }).catch(err => notificationFunctions.toastSmall('error', 'Fluxo não encontrado.'));
        },
        listenerImprimirRecibo: () => {
            $(document).on('click', "[data-action='btnImprimirRecibo']", function() {
                appFunctions.viewToPrint(`${BASEURL}/financeiro/recibo/${$(this).attr('data-id')}`);
            });
        },
        listenerImprimirComprovante: () => {
            $(document).on('click', "[data-action='btnImprimirComprovante']", function() {
                appFunctions.viewToPrint(`${BASEURL}/financeiro/comprovante/${$(this).attr('data-id')}`);
            });
        }
    };

    const dataGridFinanceiroFluxoFunctions = {
        init: () => {
            dataGridFinanceiroFluxoFunctions.mapeamentoFinanceiroFluxo();

            if (METODO == 'index') {
                $("[data-action='btnFiltrar']").trigger('click'); //  Inicia dessa forma para aplicar os filtros
                $('#tableReceberHoje').DataTable(dataGridGlobalFunctions.getSettings(1));
                $('#tableReceberVencida').DataTable(dataGridGlobalFunctions.getSettings(2));
                $('#tableReceberFutura').DataTable(dataGridGlobalFunctions.getSettings(3));
                $('#tablePagarHoje').DataTable(dataGridGlobalFunctions.getSettings(4));
                $('#tablePagarVencida').DataTable(dataGridGlobalFunctions.getSettings(5));
                $('#tablePagarFutura').DataTable(dataGridGlobalFunctions.getSettings(6));
            }

            if (METODO == 'edit') {
                // Para aplicar as mascaras
                $("input[name='valor_bruto']").trigger('keyup');
            }
        },
        mapeamentoFinanceiroFluxo: () => {
            // Fluxo detalhado
            mapeamento[0] = [];
            mapeamento[0][ROUTE] = [];
            mapeamento[0][ROUTE]['id_column'] = `uuid_financeiro_fluxo`;
            mapeamento[0][ROUTE]['ajax_url'] = `${BASEURL}/financeiro/getDataGrid/0`;
            mapeamento[0][ROUTE]['order_by'] = [{
                "coluna": 0,
                "metodo": "ASC"
            }];
            mapeamento[0][ROUTE]['columns'] = [{
                    "data": "codigo_financeiro_fluxo",
                    "visible": false,
                    "title": "Código"
                },
                {
                    "data": "tipo",
                    "title": "Tipo"
                },
                {
                    "data": "data_vencimento",
                    "title": "Vencimento"
                },
                {
                    "data": "data_pagamento",
                    "visible": false,
                    "title": "Pagamento"
                },
                {
                    "data": "data_competencia",
                    "visible": false,
                    "title": "Competência"
                },
                {
                    "data": "agente",
                    "visible": false,
                    "title": "Cliente/Fornecedor/Vendedor"
                },
                {
                    "data": "nome",
                    "title": "Descrição"
                },
                {
                    "data": "codigo_barras",
                    "visible": false,
                    "title": "Código de Barras"
                },
                {
                    "data": "valor_liquido",
                    "title": "Valor (R$)",
                    "className": "text-end"
                },
                {
                    "data": "valor_pago_parcial",
                    "title": "Valor Parcial (R$)",
                    "className": "text-end"
                },
                {
                    "data": "saldo_devedor",
                    "title": "Saldo Devedor (R$)",
                    "className": "text-end"
                },
                {
                    "data": "centro_custo",
                    "visible": false,
                    "title": "Centro de Custo"
                },
                {
                    "data": "conta",
                    "visible": false,
                    "title": "Conta"
                },
                {
                    "data": "status",
                    "title": "Status"
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
                    "data": "uuid_financeiro_fluxo",
                    "title": "Ações",
                    "className": "text-center"
                }
            ];
            mapeamento[0][ROUTE]['btn_montar'] = true;
            mapeamento[0][ROUTE]['btn'] = [{
                    "funcao": "editar",
                    "metodo": "alterar",
                    "compare": {
                        operator: "and",
                        expressions: [{
                            column: "codigo_financeiro_fluxo",
                            type: "!=",
                            value: null
                        }, {
                            column: "status",
                            type: "==",
                            value: 'Pendente'
                        }]
                    }
                },
                {
                    "funcao": "verModal",
                    "metodo": "",
                    "compare": {
                        operator: "and",
                        expressions: [{
                            column: "codigo_financeiro_fluxo",
                            type: "!=",
                            value: null
                        }]
                    }
                },
                {
                    "funcao": "fluxoMarcarPago",
                    "metodo": "",
                    "compare": {
                        operator: "and",
                        expressions: [{
                            column: "codigo_financeiro_fluxo",
                            type: "!=",
                            value: null
                        }, {
                            column: "status",
                            type: "==",
                            value: 'Pendente'
                        }]
                    }
                },
                {
                    "funcao": "fluxoMarcarPendente",
                    "metodo": "",
                    "compare": {
                        operator: "and",
                        expressions: [{
                            column: "codigo_financeiro_fluxo",
                            type: "!=",
                            value: null
                        }, {
                            column: "status",
                            type: "==",
                            value: 'Pago'
                        }]
                    }
                },
                {
                    "funcao": "fluxoPagarParcial",
                    "metodo": "",
                    "compare": {
                        operator: "and",
                        expressions: [{
                            column: "codigo_financeiro_fluxo",
                            type: "!=",
                            value: null
                        }, {
                            column: "status",
                            type: "==",
                            value: 'Pendente'
                        }]
                    }
                },
            ];

            // Contas a Receber - Hoje
            mapeamento[1] = [];
            mapeamento[1][ROUTE] = [];
            mapeamento[1][ROUTE]['id_column'] = `uuid_financeiro_fluxo`;
            mapeamento[1][ROUTE]['ajax_url'] = `${BASEURL}/financeiro/getDataGridResumo/1`;
            mapeamento[1][ROUTE]['pageLength'] = 10;
            mapeamento[1][ROUTE]['order_by'] = [{
                "coluna": 2,
                "metodo": "ASC"
            }];
            mapeamento[1][ROUTE]['columns'] = [{
                    "data": "codigo_financeiro_fluxo",
                    "visible": false,
                    "title": "Código"
                }, {
                    "data": "nome",
                    "title": "Nome"
                },
                {
                    "data": "data_vencimento",
                    "title": "Data"
                },
                {
                    "data": "valor",
                    "title": "Valor",
                    "className": "text-end"
                },
                {
                    "data": "uuid_financeiro_fluxo",
                    "title": "Ações",
                    "className": "text-center"
                }
            ];
            mapeamento[1][ROUTE]['btn_montar'] = true;
            mapeamento[1][ROUTE]['btn'] = [{
                "funcao": "verModal",
                "metodo": "",
                "compare": {
                    operator: "and",
                    expressions: [{
                        column: "codigo_financeiro_fluxo",
                        type: "!=",
                        value: null
                    }]
                }
            }];

            // Contas a Receber - Vencidas
            mapeamento[2] = [];
            mapeamento[2][ROUTE] = [];
            mapeamento[2][ROUTE]['id_column'] = `uuid_financeiro_fluxo`;
            mapeamento[2][ROUTE]['ajax_url'] = `${BASEURL}/financeiro/getDataGridResumo/2`;
            mapeamento[2][ROUTE]['pageLength'] = 10;
            mapeamento[2][ROUTE]['order_by'] = [{
                "coluna": 2,
                "metodo": "ASC"
            }];
            mapeamento[2][ROUTE]['columns'] = [{
                    "data": "codigo_financeiro_fluxo",
                    "visible": false,
                    "title": "Código"
                }, {
                    "data": "nome",
                    "title": "Nome"
                },
                {
                    "data": "data_vencimento",
                    "title": "Data"
                },
                {
                    "data": "valor",
                    "title": "Valor",
                    "className": "text-end"
                },
                {
                    "data": "uuid_financeiro_fluxo",
                    "title": "Ações",
                    "className": "text-center"
                }
            ];
            mapeamento[2][ROUTE]['btn_montar'] = true;
            mapeamento[2][ROUTE]['btn'] = [{
                "funcao": "verModal",
                "metodo": "",
                "compare": {
                    operator: "and",
                    expressions: [{
                        column: "codigo_financeiro_fluxo",
                        type: "!=",
                        value: null
                    }]
                }
            }];

            // Contas a Receber - Futuras
            mapeamento[3] = [];
            mapeamento[3][ROUTE] = [];
            mapeamento[3][ROUTE]['id_column'] = `uuid_financeiro_fluxo`;
            mapeamento[3][ROUTE]['ajax_url'] = `${BASEURL}/financeiro/getDataGridResumo/3`;
            mapeamento[3][ROUTE]['pageLength'] = 10;
            mapeamento[3][ROUTE]['order_by'] = [{
                "coluna": 2,
                "metodo": "ASC"
            }];
            mapeamento[3][ROUTE]['columns'] = [{
                    "data": "codigo_financeiro_fluxo",
                    "visible": false,
                    "title": "Código"
                }, {
                    "data": "nome",
                    "title": "Nome"
                },
                {
                    "data": "data_vencimento",
                    "title": "Data"
                },
                {
                    "data": "valor",
                    "title": "Valor",
                    "className": "text-end"
                },
                {
                    "data": "uuid_financeiro_fluxo",
                    "title": "Ações",
                    "className": "text-center"
                }
            ];
            mapeamento[3][ROUTE]['btn_montar'] = true;
            mapeamento[3][ROUTE]['btn'] = [{
                "funcao": "verModal",
                "metodo": "",
                "compare": {
                    operator: "and",
                    expressions: [{
                        column: "codigo_financeiro_fluxo",
                        type: "!=",
                        value: null
                    }]
                }
            }, ];

            // Contas a Pagar - Hoje
            mapeamento[4] = [];
            mapeamento[4][ROUTE] = [];
            mapeamento[4][ROUTE]['id_column'] = `uuid_financeiro_fluxo`;
            mapeamento[4][ROUTE]['ajax_url'] = `${BASEURL}/financeiro/getDataGridResumo/4`;
            mapeamento[4][ROUTE]['pageLength'] = 10;
            mapeamento[4][ROUTE]['order_by'] = [{
                "coluna": 2,
                "metodo": "ASC"
            }];
            mapeamento[4][ROUTE]['columns'] = [{
                    "data": "codigo_financeiro_fluxo",
                    "visible": false,
                    "title": "Código"
                }, {
                    "data": "nome",
                    "title": "Nome"
                },
                {
                    "data": "data_vencimento",
                    "title": "Data"
                },
                {
                    "data": "valor",
                    "title": "Valor",
                    "className": "text-end"
                },
                {
                    "data": "uuid_financeiro_fluxo",
                    "title": "Ações",
                    "className": "text-center"
                }
            ];
            mapeamento[4][ROUTE]['btn_montar'] = true;
            mapeamento[4][ROUTE]['btn'] = [{
                "funcao": "verModal",
                "metodo": "",
                "compare": {
                    operator: "and",
                    expressions: [{
                        column: "codigo_financeiro_fluxo",
                        type: "!=",
                        value: null
                    }]
                }
            }, ];

            // Contas a Pagar - Vencidas
            mapeamento[5] = [];
            mapeamento[5][ROUTE] = [];
            mapeamento[5][ROUTE]['id_column'] = `uuid_financeiro_fluxo`;
            mapeamento[5][ROUTE]['ajax_url'] = `${BASEURL}/financeiro/getDataGridResumo/5`;
            mapeamento[5][ROUTE]['pageLength'] = 10;
            mapeamento[5][ROUTE]['order_by'] = [{
                "coluna": 2,
                "metodo": "ASC"
            }];
            mapeamento[5][ROUTE]['columns'] = [{
                    "data": "codigo_financeiro_fluxo",
                    "visible": false,
                    "title": "Código"
                }, {
                    "data": "nome",
                    "title": "Nome"
                },
                {
                    "data": "data_vencimento",
                    "title": "Data"
                },
                {
                    "data": "valor",
                    "title": "Valor",
                    "className": "text-end"
                },
                {
                    "data": "uuid_financeiro_fluxo",
                    "title": "Ações",
                    "className": "text-center"
                }
            ];
            mapeamento[5][ROUTE]['btn_montar'] = true;
            mapeamento[5][ROUTE]['btn'] = [{
                "funcao": "verModal",
                "metodo": "",
                "compare": {
                    operator: "and",
                    expressions: [{
                        column: "codigo_financeiro_fluxo",
                        type: "!=",
                        value: null
                    }]
                }
            }, ];

            // Contas a Pagar - Futuras
            mapeamento[6] = [];
            mapeamento[6][ROUTE] = [];
            mapeamento[6][ROUTE]['id_column'] = `uuid_financeiro_fluxo`;
            mapeamento[6][ROUTE]['ajax_url'] = `${BASEURL}/financeiro/getDataGridResumo/6`;
            mapeamento[6][ROUTE]['pageLength'] = 10;
            mapeamento[6][ROUTE]['order_by'] = [{
                "coluna": 2,
                "metodo": "ASC"
            }];
            mapeamento[6][ROUTE]['columns'] = [{
                    "data": "codigo_financeiro_fluxo",
                    "visible": false,
                    "title": "Código"
                }, {
                    "data": "nome",
                    "title": "Nome"
                },
                {
                    "data": "data_vencimento",
                    "title": "Data"
                },
                {
                    "data": "valor",
                    "title": "Valor",
                    "className": "text-end"
                },
                {
                    "data": "uuid_financeiro_fluxo",
                    "title": "Ações",
                    "className": "text-center"
                }
            ];
            mapeamento[6][ROUTE]['btn_montar'] = true;
            mapeamento[6][ROUTE]['btn'] = [{
                "funcao": "verModal",
                "metodo": "",
                "compare": {
                    operator: "and",
                    expressions: [{
                        column: "codigo_financeiro_fluxo",
                        type: "!=",
                        value: null
                    }]
                }
            }, ];
        },
    };

    document.addEventListener("DOMContentLoaded", () => {
        financeiroFluxoFunctions.init();
        dataGridFinanceiroFluxoFunctions.init();
        select2FinanceiroFluxoFunctions.init();
    });
</script>
