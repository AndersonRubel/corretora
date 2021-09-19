<script>
    const select2FaturamentoFunctions = {
        init: () => {
            select2FaturamentoFunctions.buscarEmpresa();
            select2FaturamentoFunctions.buscarVendedor();
            select2FaturamentoFunctions.buscarEmpresaConta();
            select2FaturamentoFunctions.buscarCadastroMetodoPagamento();
            select2FaturamentoFunctions.buscarEmpresaCentroCusto();
            select2FaturamentoFunctions.buscarEmpresaComissao();
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
        },
        buscarEmpresaComissao: () => {
            let elementSelect2 = $("[data-select='buscarEmpresaComissao']");
            let url = `${BASEURL}/empresa/backendCall/selectEmpresaComissao`;
            elementSelect2.select2({
                placeholder: "Selecione...",
                allowClear: true,
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
                            page: 1,
                            codVendedor: $("[data-select='buscarVendedor']").val()
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
                            codVendedor: $("[data-select='buscarVendedor']").val()
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

    const faturamentoFunctions = {
        init: () => {
            faturamentoFunctions.listenerFiltros();
            faturamentoFunctions.listenerBuscarVendas();
            faturamentoFunctions.listenerGerarFaturamento();
            faturamentoFunctions.listenerCheckFaturar();
            faturamentoFunctions.listenerCalculos();
            faturamentoFunctions.listenerFluxoEditar();
        },
        listenerFiltros: () => {
            $(document).on('click', "[data-action='btnLimpar']", () => {
                // Busca todos os elementos que possuem o atributo 'data-filtro' e que iniciam com 'filtro_'
                $("[data-filtro^='filtro_']").find("input,select,textarea").val('');
                $("[data-filtro^='filtro_']").find("input").select2('val', '');
                $('#tableAtivos').DataTable(dataGridGlobalFunctions.getSettings(0));
            });

            $(document).on('click', "[data-action='btnFiltrar']", (e) => {
                e.preventDefault();
                dataGridOptionsFunctions.destroyTable("#tableAtivos");

                let filtros = [{
                    periodo_inicio: $("input[name='periodo_inicio']").val(),
                    periodo_fim: $("input[name='periodo_fim']").val(),
                    codigo_empresa: $("input[name='codigo_empresa']").val(),
                    codigo_vendedor: $("input[name='codigo_vendedor']").val()
                }];
                mapeamento[0][ROUTE]['custom_data'] = filtros;

                $('#tableAtivos').DataTable(dataGridGlobalFunctions.getSettings(0));
            })
        },
        changeCheckFaturar: (tipo) => {
            switch (tipo) {
                case 'marcar':
                    $('.check-faturar').prop('checked', true).attr('valor', "on").change();
                    break;
                case 'desmarcar':
                    $('.check-faturar').prop('checked', false).attr('valor', "off").change();
                    break;
                case 'inverter':
                    $('.check-faturar').each(function(i, el) {
                        if ($(el).prop('checked')) {
                            $(el).prop('checked', false).attr('valor', "off").change();
                        } else {
                            $(el).prop('checked', true).attr('valor', "on").change();
                        }
                    });
                    break;
                default:
                    break;
            }
        },
        listenerCheckFaturar: () => {
            $(document).on('change', ".check-faturar", function() {
                let valorBrutoTotalCheck = 0;

                $("input:checkbox").each(function(i, el) {
                    if ($(el).prop('checked')) {
                        valorBrutoTotalCheck += convertFunctions.onlyNumber($(el).parent().parent().find("input[name='valor_liquido[]']").val());
                    }
                });

                $("input[name='valor_total_bruto']").val(convertFunctions.intToReal(valorBrutoTotalCheck)).trigger('keyup');

            });
        },
        listenerBuscarVendas: () => {
            $(document).on('click', "[data-action='btnBuscarDados']", function(e) {

                if ($("input[name='periodo_inicio']").val() == "") {
                    notificationFunctions.alertPopup('warning', 'É obrigatório preencher o período de início', 'Atenção!');
                    return;
                }

                if ($("input[name='periodo_fim']").val() == "") {
                    notificationFunctions.alertPopup('warning', 'É obrigatório preencher o período de fim', 'Atenção!');
                    return;
                }

                if ($('[data-select="buscarVendedor"]').val() == "") {
                    notificationFunctions.alertPopup('warning', 'É obrigatório preencher o Vendedor', 'Atenção!');
                    return;
                }

                let valorTotalBruto = 0;
                appFunctions.backendCall('POST', `faturamento/backendCall/selectVenda`, {
                    periodo_inicio: $("input[name='periodo_inicio']").val(),
                    periodo_fim: $("input[name='periodo_fim']").val(),
                    codigo_vendedor: $('[data-select="buscarVendedor"]').val()
                }).then(
                    (res) => {
                        if (res && res.length > 0) {
                            $("#listagemFaturamento").find("table tbody").html('');
                            res.forEach(
                                (el) => {
                                    valorTotalBruto = (valorTotalBruto + convertFunctions.onlyNumber(el.valor_liquido));

                                    $("#listagemFaturamento").find("table tbody").append(`
                                        <tr>
                                            <td class="text-center align-middle">
                                                <input type="hidden" name="codigo_venda[]" value="${el.codigo_venda}">
                                                <input type="hidden" name="valor_bruto[]" value="${el.valor_bruto}">
                                                <input type="hidden" name="valor_liquido[]" value="${el.valor_liquido}">
                                                <div class="form-check form-switch d-flex justify-content-center">
                                                    <input type="checkbox" class="form-check-input check-faturar" checked="true" valor="on" name="check_faturar[]" id="faturar_${el.codigo_venda}">
                                                    <label class="form-check-label" for="faturar_${el.codigo_venda}"> </label>
                                                </div>
                                            </td>
                                            <td class="text-center align-middle">
                                                ${el.cliente} <br>
                                                ${el.cpf_cnpj ? '(' + (el.cpf_cnpj.length == 11 ? convertFunctions.intToCpf(el.cpf_cnpj) : convertFunctions.intToCnpj(el.cpf_cnpj)) + ')' : ''}
                                            </td>
                                            <td class="w-50">
                                                <div class="d-flex justify-content-between">
                                                    <div class="col text-start">
                                                        <b>Código: </b> ${el.codigo_venda}<br>
                                                        <b>Data: </b> ${el.criado_em}<br>
                                                        <b>Método de Pagamento: </b> ${el.metodo_pagamento}<br>
                                                    </div>

                                                    <div class="col text-end">
                                                        <b>Valor Bruto: </b> R$ ${convertFunctions.intToReal(el.valor_bruto)}<br>
                                                        <b>Valor Desconto: </b> R$ ${convertFunctions.intToReal(el.valor_desconto)}<br>
                                                        <b>Valor Líquido: </b> R$ ${convertFunctions.intToReal(el.valor_liquido)}<br>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    `);
                                }
                            );

                            maskFunctions.dinheiro();
                            $("#listagemFaturamento").removeClass('d-none');
                            $("#tableFaturamentos").removeClass('d-none');
                            $("#semDados").addClass('d-none');
                            $("#blocoRecebimento").removeClass('d-none');
                            $("[data-action='realizarSubmit']").removeClass('d-none');
                        } else {
                            $("#listagemFaturamento").removeClass('d-none');
                            $("#tableFaturamentos").addClass('d-none');
                            $("#semDados").removeClass('d-none');
                            $("#blocoRecebimento").addClass('d-none');
                            $("[data-action='realizarSubmit']").addClass('d-none');
                        }

                        $("input[name='valor_total_bruto']").val(convertFunctions.intToReal(valorTotalBruto));
                        $("input[name='valor_total_liquido']").val(convertFunctions.intToReal(valorTotalBruto));
                        $("input[name='valor_restante']").val(convertFunctions.intToReal(valorTotalBruto));

                    }
                );
            });

        },
        listenerGerarFaturamento: () => {
            $("#formFaturamento").on('click', "[data-action='realizarSubmit']", function(e) {
                e.preventDefault();

                // Realiza as Validações antes de enviar

                if ($("input[name='codigo_empresa_conta']").val() == '') {
                    notificationFunctions.alertPopup('warning', 'É obrigatório preencher a Conta.', 'Atenção!');
                    return;
                }

                if ($("input[name='codigo_cadastro_metodo_pagamento']").val() == '') {
                    notificationFunctions.alertPopup('warning', 'É obrigatório preencher o Método de Pagamento.', 'Atenção!');
                    return;
                }

                if ($("input[name='codigo_empresa_centro_custo']").val() == '') {
                    notificationFunctions.alertPopup('warning', 'É obrigatório preencher o Centro de Custo.', 'Atenção!');
                    return;
                }

                // Valida o Estado dos Checkbox Não marcados, para enviar por POST tambem
                $("input:checkbox:not(:checked)").each(function() {
                    $(this).val("off");
                    $(this).prop("checked", true);
                });

                $("#formFaturamento").submit();
            });
        },
        listenerCalculos: () => {
            $(document).on('keyup', "input[name='valor_desconto'], input[name='valor_entrada'], input[name='valor_total_bruto']", faturamentoFunctions.calculaValores);
            $(document).on('change', "input[name='codigo_empresa_comissao']", faturamentoFunctions.calculaValores);
        },
        calculaValores: () => {
            let valorLiquido = 0,
                valorBruto = 0,
                valorComissao = 0,
                valorEntrada = 0,
                valorRestante = 0,
                percentual = 0;

            let dataComissao = $("[data-select='buscarEmpresaComissao']").select2('data');
            if (dataComissao) percentual = dataComissao.percentual;

            valorBruto = convertFunctions.onlyNumber($("input[name='valor_total_bruto']").val()) || 0;
            valorDesconto = convertFunctions.onlyNumber($("input[name='valor_desconto']").val()) || 0;
            valorEntrada = convertFunctions.onlyNumber($("input[name='valor_entrada']").val()) || 0;

            valorComissao = (valorBruto * (percentual / 100));
            valorLiquido = (valorBruto) - valorDesconto - valorComissao;
            valorRestante = valorLiquido - valorEntrada;

            $("input[name='valor_comissao']").val(convertFunctions.intToReal(valorComissao));
            $("input[name='valor_total_liquido']").val(convertFunctions.intToReal(valorLiquido));
            $("input[name='valor_restante']").val(convertFunctions.intToReal(valorRestante));
        },
        listenerFluxoEditar: () => {
            $(document).on('click', "[data-action='fluxoEditar']", async function() {
                // Busca o Código do Faturamento
                const responseFat = await appFunctions.backendCall('POST', `faturamento/backendCall/selectFaturamento`, {
                    faturamentoUuid: $(this).data('id'),
                    page: 1,
                });

                if (responseFat && responseFat.itens && responseFat.itens.length > 0) {
                    // Busca a UUID do Fluxo vinculado
                    const responseFlux = await appFunctions.backendCall('POST', `financeiro/backendCall/selectFluxo`, {
                        faturamentoCodigo: responseFat.itens[0].id,
                        page: 1,
                    });

                    if (responseFlux && responseFlux.itens && responseFlux.itens.length > 0) {
                        // Redireciona para o Fluxo desse Faturamento
                        window.location.href = `${BASEURL}/financeiro/alterar/${responseFlux.itens[0].uuid_financeiro_fluxo}`;
                    } else {
                        notificationFunctions.toastSmall('error', 'Fluxo não encontrado.');
                    }
                } else {
                    notificationFunctions.toastSmall('error', 'Faturamento não encontrado.');
                }
            });
        }
    }

    const dataGridFaturamentoFunctions = {
        init: () => {
            dataGridFaturamentoFunctions.mapeamentoFaturamento();
            $("[data-action='btnFiltrar']").trigger('click');
        },
        mapeamentoFaturamento: () => {
            mapeamento[0] = [];
            mapeamento[0][ROUTE] = [];
            mapeamento[0][ROUTE]['id_column'] = `uuid_faturamento`;
            mapeamento[0][ROUTE]['ajax_url'] = `${BASEURL}/faturamento/getDataGrid`;
            mapeamento[0][ROUTE]['order_by'] = [{
                "coluna": 1,
                "metodo": "ASC"
            }];
            mapeamento[0][ROUTE]['columns'] = [{
                    "data": "vendedor",
                    "title": "Vendedor"
                },
                {
                    "data": "periodo_inicio",
                    "title": "Período Início"
                },
                {
                    "data": "periodo_fim",
                    "title": "Período Fim"
                },
                {
                    "data": "valor_bruto",
                    "title": "Valor Bruto",
                    "className": "text-end",
                    "isreplace": true,
                    "render": (data) => `R$ ${convertFunctions.intToReal(data)}`
                },
                {
                    "data": "valor_comissao",
                    "title": "Valor Comissão",
                    "className": "text-end",
                    "isreplace": true,
                    "render": (data) => `R$ ${convertFunctions.intToReal(data)}`
                },
                {
                    "data": "valor_liquido",
                    "title": "Valor Líquido",
                    "className": "text-end",
                    "isreplace": true,
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
                    "data": "uuid_faturamento",
                    "title": "Ações",
                    "className": "text-center"
                }
            ];
            mapeamento[0][ROUTE]['btn_montar'] = true;
            mapeamento[0][ROUTE]['btn'] = [{
                    "funcao": "fluxoEditar",
                    "metodo": "alterar",
                    "compare": null
                },
                {
                    "funcao": "gerarPdf",
                    "metodo": "gerarPdf",
                    "compare": null
                },
                {
                    "funcao": "desativar",
                    "metodo": "",
                    "compare": null
                }
            ];
        },
    }

    document.addEventListener("DOMContentLoaded", () => {
        faturamentoFunctions.init();
        dataGridFaturamentoFunctions.init();
        select2FaturamentoFunctions.init();
    });
</script>
