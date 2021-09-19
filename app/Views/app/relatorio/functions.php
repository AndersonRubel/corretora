<script>
    // Recebe os filtros disponíveis vindos da Controller
    const filtrosDisponiveis = '<?= $filtros; ?>';

    const select2RelatorioFunctions = {
        init: () => {
            select2RelatorioFunctions.buscarUsuario();
            select2RelatorioFunctions.buscarEmpresas();
            select2RelatorioFunctions.buscarCliente();
            select2RelatorioFunctions.buscarVendedor();
        },
        buscarUsuario: () => {
            let elementSelect2 = $("[data-select='buscarUsuario']");
            let url = `${BASEURL}/usuario/backendCall/selectUsuario`;
            elementSelect2.select2({
                placeholder: "Buscar...",
                allowClear: true,
                multiple: false,
                quietMillis: 2000,
                initSelection: function(element, callback) {
                    $.ajax({
                        url: url,
                        dataType: "json",
                        type: "POST",
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
                    type: "POST",
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
        buscarEmpresas: () => {
            let elementSelect2 = $("[data-select='buscarEmpresas']");
            let url = `${BASEURL}/empresa/backendCall/selectEmpresas`;
            elementSelect2.select2({
                placeholder: "Buscar...",
                allowClear: true,
                multiple: false,
                quietMillis: 2000,
                initSelection: function(element, callback) {
                    $.ajax({
                        url: url,
                        dataType: "json",
                        type: "POST",
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
                    type: "POST",
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
                placeholder: "Buscar...",
                allowClear: true,
                multiple: false,
                quietMillis: 2000,
                initSelection: function(element, callback) {
                    $.ajax({
                        url: url,
                        dataType: "json",
                        type: "POST",
                        params: {
                            contentType: "application/json; charset=utf-8",
                        },
                        data: {
                            termo: $(element).val(),
                            codigoVendedor: $("[data-select='buscarVendedor']").val(),
                            page: 1
                        },
                        success: (data) => callback(data.itens[0])
                    })
                },
                ajax: {
                    url: url,
                    dataType: 'json',
                    type: "POST",
                    data: (term, page) => {
                        return {
                            termo: term,
                            codigoVendedor: $("[data-select='buscarVendedor']").val(),
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
                placeholder: "Buscar...",
                allowClear: true,
                multiple: false,
                quietMillis: 2000,
                initSelection: function(element, callback) {
                    $.ajax({
                        url: url,
                        dataType: "json",
                        type: "POST",
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
                    type: "POST",
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

    const relatorioFunctions = {
        init: () => {
            // $("[data-selectTwo='true']").select2();
            relatorioFunctions.listenerLoadRelatorio();
            relatorioFunctions.listenerLimparFiltros();
            relatorioFunctions.showFiltros();
            relatorioFunctions.listenerFiltrar();
        },
        listenerLoadRelatorio: () => {
            $(document).on("change", "select[name='relatorioTipo']", () => {
                let tipo = $("select[name='relatorioTipo']").val();
                if (tipo) {
                    window.location.href = `${BASEURL}/relatorios/${tipo}`;
                }
            });
        },
        listenerLimparFiltros: () => {
            $(document).on('click', "[data-action='btnLimpar']", () => {
                // Busca todos os elementos que possuem o atributo 'data-filtro' e que iniciam com 'filtro_'
                $("[data-filtro^='filtro_']").find("input,select,textarea").val('');
                $("[data-filtro^='filtro_']").find("input").select2('val', '');
                relatorioFunctions.hideArticles();
            });
        },
        hideArticles: () => {
            $("#grafico, #sumario, #listagem").addClass('d-none');
        },
        showFiltros: () => {
            if (typeof filtrosDisponiveis == "string" && filtrosDisponiveis != "[]") {
                let filtros = JSON.parse(filtrosDisponiveis);
                if (filtros && filtros.length > 0) {
                    filtros.forEach(
                        (el) => {
                            // Se o primeiro caracter for um underline, adiciona o atributo REQUIRED
                            if (el[0] == '_') {
                                $(`[data-filtro='${el.substr(1)}']`).removeClass('d-none');
                                $(`[data-filtro='${el.substr(1)}']`).find("input,select,textarea").attr('required', true);
                            } else {
                                $(`[data-filtro='${el}']`).removeClass('d-none');
                            }
                        }
                    );

                    // Exibe os filtros
                    $("#cardFiltros").removeClass('d-none');
                    // Mostra o Botão de Limpar Filtros
                    $(`[data-action='btnLimpar']`).show();
                } else {
                    // Esconde os filtros
                    $("#cardFiltros").AddClass('d-none');
                }
            }
        },
        validationInputs: () => {
            // Valida os Inputs de Cada Relatorio

            // Primeiro valida os campos que possuem REQUIRED
            if (!$("#formRelatorio")[0].reportValidity()) return false;

            return true;
        },
        listenerFiltrar: () => {
            $(document).on('click', "[data-action='btnFiltrar']", (e) => {
                e.preventDefault();

                // Se a validação de inputs falhar, encerra o processo
                if (!relatorioFunctions.validationInputs()) return false;

                // Serializa os dados do Formulario para enviar por POST
                let dadosFormulario = $("#formRelatorio").serialize();

                // Esconde as informações se houver
                relatorioFunctions.hideArticles();

                // Realiza a chamada pra Controller
                appFunctions.backendCall('POST', `relatorios/requestData`, {
                    metodo: METODO,
                    dados: dadosFormulario
                }).then(
                    (res) => {
                        if (res) {
                            // Se vier dados em algum dos arrays
                            if (res.grafico || res.sumario || res.listagem) {

                                // Listagem
                                if (res.listagem && res.listagem.totalRecords > 0) {
                                    relatorioFunctions.mountDataGrid(res.listagem);
                                } else {
                                    statusToastListagem = false;
                                    $("#listagem .sem-dados").removeClass('d-none');
                                }
                            }
                        }
                    }
                ).catch(err => notificationFunctions.toastSmall(err.textStatus, err.mensagem));
            });
        },
        mountDataGrid: async (dadosListagem) => {
            $("#listagem .sem-dados").addClass('d-none');
            let colunasGrid = [];
            dadosListagem.fields.forEach(el => colunasGrid.push({
                'data': el,
                'title': el.replaceAll('_', ' ').toUpperCase()
            }));

            // Configurações Padrão do Mapeamento (Normalização do Array)
            mapeamento = []
            mapeamento['id_column'] = "_0_id";
            mapeamento['columns'] = colunasGrid;
            mapeamento['ajax_nome'] = "";
            mapeamento['order_by'] = [{
                "coluna": 1,
                "metodo": "ASC"
            }];
            mapeamento['pageLength'] = 50;
            mapeamento['multiple_select'] = false;
            mapeamento['toolbar_buttons'] = [];
            mapeamento['btn_montar'] = false;
            mapeamento['footer_montar'] = true;
            mapeamento['btn_column'] = mapeamento['columns'].length - 1;
            mapeamento['pdf_columns'] = relatorioFunctions.getArrayKeys(mapeamento['columns'].length - 1);

            // Destroi o datatable para poder gerar um novo
            $.each($(".dataTable"), function(i, el) {
                $(el).dataTable().fnDestroy();
            });

            const configRelatorio = {
                dom: '<"clear">lrtip', // B = Buttons, L = Length, F = Filter, R = Processing Display, T = Table, I = Summary, P = Pagination
                pagingType: "full_numbers",
                buttons: [],
                stateSave: false,
                processing: true,
                serverSide: true,
                searchDelay: 1000,
                responsive: true,
                searching: true,
                autoWidth: true,
                fixedHeader: true,
                scroller: false,
                paging: true,
                scrollCollapse: true,
                searchable: true,
                retrieve: true,
                pageLength: mapeamento['pageLength'],
                select: {
                    info: false
                },
                columns: mapeamento['columns'],
                order: [mapeamento['order_by'][0]['coluna'], mapeamento['order_by'][0]['metodo']],
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "Todos"]
                ],
                aoColumnDefs: [mapeamento['btn_montar'] ? {
                    "bSortable": false,
                    "aTargets": [mapeamento['btn_column']]
                } : {}],
                ajax: {
                    url: `${BASEURL}/relatorios/requestData`,
                    data: {
                        metodo: METODO,
                        dados: $("#formRelatorio").serialize()
                    },
                },
                fnInitComplete: function(oSettings, json) {
                    // Realiza Alterações no Campo de Pesquisar
                    relatorioFunctions.alterFieldSearch(oSettings);

                    // Verifica se é necessário montar a Coluna de botões de Opção
                    relatorioFunctions.mountColumnButtons(oSettings, mapeamento['btn_montar'], mapeamento['btn_column']);

                    // atualiza os tooltips
                    // appFunctions.tooltip();
                    // appFunctions.hideLoader();
                },
                drawCallback: (settings) => {
                    tableInstance = $(`#${settings.sTableId}`).DataTable(configRelatorio);
                    if ($(document).find(".btnOpcoesDataTables").length > 0) {
                        tableInstance.buttons().container().appendTo($('.btnOpcoesDataTables'));
                    }

                    // Verifica se é necessário montar a Coluna de botões de Opção novamente
                    relatorioFunctions.mountColumnButtons(settings, mapeamento['btn_montar'], mapeamento['btn_column']);
                    relatorioFunctions.optionCollectionBox();
                    // appFunctions.tooltip();
                },
                language: {
                    "sEmptyTable": "Nenhum registro encontrado",
                    "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                    "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                    "sInfoPostFix": "",
                    "sInfoThousands": ".",
                    "sLengthMenu": "_MENU_ resultados por página",
                    "sLoadingRecords": "Carregando...",
                    "sProcessing": "Processando...",
                    "sZeroRecords": "Nenhum registro encontrado",
                    "sSearch": "Pesquisar",
                    "oPaginate": {
                        "sNext": "Próximo",
                        "sPrevious": "Anterior",
                        "sFirst": "Primeiro",
                        "sLast": "Último"
                    },
                    "oAria": {
                        "sSortAscending": ": Ordenar colunas de forma ascendente",
                        "sSortDescending": ": Ordenar colunas de forma descendente"
                    }
                }
            };

            // Adiciona Botões nas Opções do DataTables
            relatorioFunctions.mountOptionButtons(configRelatorio);
            await $('#tableRelatorio').DataTable(configRelatorio);
            $('#tableRelatorio').css({
                width: '100%'
            });
            // $('#tableRelatorio').DataTable().columns.adjust().draw();
            relatorioFunctions.optionCollectionBox();
            $("#listagem").removeClass('d-none');

        },
        optionCollectionBox: () => {

            // personaliza a collection box
            var caixasCriadas = [];
            $('.btnOpcoes').on('click', function() {
                $(".dt-button-collection").find('.boxOpcoes-titulo').html('');
                var aria_controls = $(this).attr('aria-controls');

                // Adiciona um Titulo na Caixa de Opções
                $(".dt-button-collection").prepend("<div class='boxOpcoes-titulo'>Visibilidade das Colunas</div>");

                // Adiciona Um Titulo nas Opções de Exportar
                $(".dt-button-collection").find('.btnExports:first').before(`
                    <div class='boxOpcoes-separador'></div>
                    <div class='boxOpcoes-titulo'>Exportar</div>
                    <div class='btnsBoxExportar'></div>
                `);

                // clona os botões de exportar para colocar eles dentro da nova box e remove a box antiga
                var _btn_exportar = $(".btnExports").clone(true);
                $(".btnExports").remove();
                $(".btnsBoxExportar").html(_btn_exportar);

                // verifica se existe botões adicionais, para colocar um separador após o imprimir
                if ($(".btnExt").length > 0) {
                    $(".dt-button-collection").find('.btnImprimir').after(`<div class='boxOpcoes-separador'></div>`);
                }

                // Adiciona Tooltip no Botões
                $(".btnCopy").attr('title', 'Copiar para a área de transferência').tooltip();
                $(".btnCsv").attr('title', 'Exportar em CSV').tooltip();
                $(".btnExcel").attr('title', 'Exportar em XLSX (Excel)').tooltip();
                $(".btnPdf").attr('title', 'Exportar em PDF').tooltip();
                $(".btnMarcarLinhas").attr('title', 'Marcar ou Desmarcar todas as linhas').tooltip();
                $(".btnImprimirLinhas").attr('title', 'Imprimir todas as linhas ou as linhas selecionadas').tooltip();
                $(".btnRefresh").attr('title', 'Atualizar Dados').tooltip();

                caixasCriadas.push(aria_controls);

                // appFunctions.tooltip();
            });
        },
        mountOptionButtons: (settings) => {
            let newButtons = {
                background: true,
                text: "Opções",
                extend: "collection",
                className: 'btnOpcoes',
                buttons: [{
                        extend: 'columnsToggle',
                        columns: mapeamento['pdf_columns']
                    },
                    {
                        extend: 'copy',
                        text: '<i class="fas fa-lg fa-clipboard"></i>',
                        className: 'btnExports btnCopy',
                        exportOptions: {
                            modifier: {
                                selected: null
                            },
                            columns: ':visible:not(.selectorBtnCol)'
                        },
                        key: {
                            key: 'c',
                            altKey: true,
                            shiftKey: true
                        }
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fas fa-file-alt"</i>',
                        className: 'btnExports btnCsv',
                        exportOptions: {
                            modifier: {
                                selected: null
                            },
                            columns: ':visible:not(.selectorBtnCol)'
                        },
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-lg fa-file-excel"></i>',
                        className: 'btnExports btnExcel',
                        exportOptions: {
                            modifier: {
                                selected: null
                            },
                            columns: ':visible:not(.selectorBtnCol)'
                        },
                    },
                    {
                        extend: "pdfHtml5",
                        text: '<i class="fas fa-file-pdf"</i>',
                        className: 'btnExports btnPdf',
                        orientation: "landscape",
                        exportOptions: {
                            modifier: {
                                selected: null
                            },
                            columns: ':visible:not(.selectorBtnCol)'
                        },
                        customize: function(doc) {
                            // modifica a tabela para 100%
                            var colCount = new Array();
                            for (var i = 1; i <= doc.content[1].table.body[0].length; i++) {
                                colCount.push('*');
                            }
                            doc.content[1].table.widths = colCount;
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-lg fa-print"></i> Imprimir',
                        className: 'btnFull btnImprimirLinhas btnImprimir',
                        exportOptions: {
                            modifier: {
                                selected: null
                            },
                            columns: ':visible:not(.selectorBtnCol)'
                        },
                        key: {
                            key: 'p',
                            shiftKey: true
                        },
                    },
                    {
                        text: '<i class="fas fa-lg fa-sync"></i> Atualizar',
                        className: 'btnFull btnRefresh',
                        action: (e, dt, node, config) => {
                            dt.ajax.reload();
                        }
                    }
                ]
            };

            settings.buttons.push(newButtons);

            // Verifica Quais Colunas Foi setado para Desabilitar a Ordenação
            if (typeof mapeamento['disable_order_by'] != 'undefined' && mapeamento['disable_order_by'].length > 0) {
                $.each(mapeamento['disable_order_by'], (i, value) => {
                    settings.aoColumnDefs.push({
                        bSortable: false,
                        aTargets: value
                    });
                });
            }

            // Verifica se a Opção de Selecionar Varias Linhas esta Habilitada
            if (mapeamento['multiple_select']) {
                settings.buttons[0].buttons.push({
                    extend: "selectAll",
                    text: "Marcar Todos",
                    className: 'btnExt btnMarcarLinhas btnSelectAll'
                });

                settings.buttons[0].buttons.push({
                    extend: "selectNone",
                    text: "Desmarcar Todos",
                    className: 'btnExt btnMarcarLinhas btnSelectAll'
                });
            }

            // Adiciona Botões Personalizados na Toolbar Dinamicamente, Declarados Diretamente no Mapeamento
            if (mapeamento['toolbar_buttons'].length > 0) {
                $.each(mapeamento['toolbar_buttons'], (i, obj) => {
                    settings.buttons[0].buttons.push({
                        text: obj.text,
                        action: obj.onClick,
                        className: 'btnExt'
                    });
                });
            }

            // appFunctions.tooltip();

        },
        getArrayKeys: (value) => {
            if (!value || value == -1) return [];
            return Array.from(Array(value).keys());
        },
        alterFieldSearch: (oSettings) => {
            $("#searchValueDataTables").on('keypress', (e) => {
                if (e.keyCode == 13) {
                    $(`#${$(oSettings.nTable).attr('id')}`).DataTable().search($("#searchValueDataTables").val()).draw();
                    e.preventDefault();
                }
            });

            $(document).on('click', '#btnSearchDataTables', function() {
                $(`#${$(oSettings.nTable).attr('id')}`).DataTable().search($("#searchValueDataTables").val()).draw();
            });
        },
        mountColumnButtons: (oSettings, montarBtn, positionBtn) => {
            // Verifica se é para Exibir os Botões de Opção

            let tableId = $(oSettings.nTable).attr('id');
            let thHeaderElement = $(oSettings.nTHead).find('tr').find('th');
            let thFooterElement = $(`#${tableId}`).children('tfoot').find('tr').find('th');
            if (montarBtn) {
                // verifca se todas as colunas estão sendo exibidas
                if (thHeaderElement.length != positionBtn + 1) {
                    // utiliza a suposição de que a coluna de botões é a ultima, então coloca na ultima posição
                    thHeaderElement.eq(thHeaderElement.length - 1).addClass('selectorBtnCol');
                } else {
                    // utiliza a posição informada pela configuração
                    thHeaderElement.eq(positionBtn).addClass('selectorBtnCol');
                }
            } else {
                // se o Btn Montar for Falso, remove a Coluna de Opções
                thHeaderElement.eq(positionBtn).hide(); // Remove a Header
                thFooterElement.eq(positionBtn).hide(); // Remove a Footer

                $.each($(oSettings.nTBody).find('tr'), function(key, value) {
                    $(value).find('td').eq(positionBtn).hide(); // Remove a Coluna da Row
                });
            }
        }
    };

    document.addEventListener("DOMContentLoaded", () => {
        relatorioFunctions.init();
        select2RelatorioFunctions.init();
    });
</script>
