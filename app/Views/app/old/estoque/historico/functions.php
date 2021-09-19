<script>
    const select2EstoqueHistoricoFunctions = {
        init: () => {
            select2EstoqueHistoricoFunctions.buscarEstoque();
            select2EstoqueHistoricoFunctions.buscarUsuario();
            select2EstoqueHistoricoFunctions.buscarProduto();
        },
        buscarEstoque: () => {
            let elementSelect2 = $("[data-select='buscarEstoque']");
            let url = `${BASEURL}/estoque/backendCall/selectEstoque`;
            elementSelect2.select2({
                placeholder: "Selecione...",
                allowClear: true,
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
                            page: 1,
                            codEmpresa: $("[data-select='buscarEmpresa']").val()
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
                            codEmpresa: $("[data-select='buscarEmpresa']").val()
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
                allowClear: true,
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
        buscarProduto: () => {
            let elementSelect2 = $("[data-select='buscarProduto']");
            let url = `${BASEURL}/produto/backendCall/selectProduto`;
            elementSelect2.select2({
                placeholder: "Selecione...",
                allowClear: true,
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

    const estoqueHistoricoFunctions = {
        init: () => {
            estoqueHistoricoFunctions.listenerFiltros();
            estoqueHistoricoFunctions.listenerRoutes();
        },
        listenerFiltros: () => {
            $(document).on('click', "[data-action='btnLimpar']", () => {
                // Busca todos os elementos que possuem o atributo 'data-filtro' e que iniciam com 'filtro_'
                $("[data-filtro^='filtro_']").find("input,select,textarea").val('');
                $("[data-filtro^='filtro_']").find("input").select2('val', '');
                $('#tableHistorico').DataTable(dataGridGlobalFunctions.getSettings(0, 'historico'));
            });

            $(document).on('click', "[data-action='btnFiltrar']", (e) => {
                e.preventDefault();
                dataGridOptionsFunctions.destroyTable("#tableHistorico");

                let uuidProduto = '';
                if (window.location.search && window.location.search.includes('?produto=')) {
                    uuidProduto = window.location.search;
                }

                mapeamento[0]['historico']['custom_data'] = [{
                    data_inicio: $("input[name='data_inicio']").val(),
                    data_fim: $("input[name='data_fim']").val(),
                    codigo_estoque: $("input[name='codigo_estoque']").val(),
                    codigo_usuario: $("input[name='codigo_usuario']").val(),
                    codigo_produto: $("input[name='codigo_produto']").val(),
                    uuid_produto: uuidProduto.replace('?produto=', ''),
                }];

                $('#tableHistorico').DataTable(dataGridGlobalFunctions.getSettings(0, 'historico'));
            })
        },
        listenerRoutes: () => {
            $(document).on('click', "[data-action='historicoVisualizar']", function() {
                window.location.href = `${BASEURL}/estoque/historicoItem/${$(this).data('id')}`;
            });

            $(document).on('click', "[data-action='historicoVisualizarItem']", function() {
                window.location.href = `${BASEURL}/estoque/historicoItem/${$(this).data('id')}/f`;
            });
        }
    };

    const dataGridEstoqueHistoricoFunctions = {
        init: () => {

            if (METODO == 'indexHistorico') {
                dataGridEstoqueHistoricoFunctions.mapeamento();
                $("[data-action='btnFiltrar']").trigger('click');
            }
            if (METODO == 'indexHistoricoItem') {
                dataGridEstoqueHistoricoFunctions.mapeamento();
                $('#tableHistoricoItem').DataTable(dataGridGlobalFunctions.getSettings(1, 'historico'));
            }
        },
        mapeamento: () => {
            // Historico
            mapeamento[0] = [];
            mapeamento[0]['historico'] = [];
            mapeamento[0]['historico']['id_column'] = `uuid_estoque_historico`;
            mapeamento[0]['historico']['ajax_url'] = `${BASEURL}/estoque/getDataGridHistorico`;
            mapeamento[0]['historico']['order_by'] = [{
                "coluna": 0,
                "metodo": "DESC"
            }];
            mapeamento[0]['historico']['columns'] = [{
                    "data": "codigo_estoque_historico",
                    "title": "Código"
                },
                {
                    "data": "usuario_criacao",
                    "title": "Solicitante"
                },
                {
                    "data": "quantidade",
                    "title": "Quantidade"
                },
                {
                    "data": "nome_produto",
                    "title": "Produto",
                    "render": (data, type, row) => `${row.nome_produto} (${row.codigo_barras})`
                },
                {
                    "data": "nome_estoque",
                    "title": "Estoque"
                },
                {
                    "data": "criado_em",
                    "title": "Data adicionado"
                },
                {
                    "data": "uuid_estoque_historico",
                    "title": "Ações",
                    "className": "text-center"
                }
            ];
            mapeamento[0]['historico']['btn_montar'] = true;
            mapeamento[0]['historico']['btn'] = [{
                    "funcao": "historicoVisualizar",
                    "metodo": "",
                    "compare": null
                },
                {
                    "funcao": "historicoVisualizarItem",
                    "metodo": "",
                    "compare": null
                }
            ];

            // Historico Item
            mapeamento[1] = [];
            mapeamento[1]['historico'] = [];
            mapeamento[1]['historico']['id_column'] = `uuid_estoque_historico_item`;
            mapeamento[1]['historico']['ajax_url'] = `${BASEURL}/estoque/getDataGridHistoricoItem/${$("input[name='uuid_estoque_historico']").val()}/${$("input[name='estoque_historico_dia']").val()}`;
            mapeamento[1]['historico']['order_by'] = [{
                "coluna": 0,
                "metodo": "DESC"
            }];
            mapeamento[1]['historico']['columns'] = [{
                    "data": "codigo_produto",
                    "title": "Código Produto"
                },
                {
                    "data": "nome_produto",
                    "title": "Produto",
                    "render": (data, type, row) => `${row.nome_produto} (${row.codigo_barras})`
                },
                {
                    "data": "transacao",
                    "title": "Transação"
                },
                {
                    "data": "quantidade",
                    "title": "Quantidade"
                },
                {
                    "data": "data",
                    "title": "Data"
                },
                {
                    "data": "hora",
                    "title": "Hora"
                },
                {
                    "data": "usuario_criacao",
                    "title": "Solicitante"
                },
                {
                    "data": "observacao",
                    "title": "Observação"
                },
                {
                    "data": "movimentacao_lote",
                    "visible": false,
                    "title": "Lote"
                },
                {
                    "data": "nome_estoque",
                    "visible": false,
                    "title": "Estoque"
                },
                {
                    "data": "uuid_estoque_historico_item",
                    "title": "Ações",
                    "visible": false,
                    "className": "text-center"
                }
            ];
            mapeamento[1]['historico']['btn_montar'] = false;
        },
    }

    document.addEventListener("DOMContentLoaded", () => {
        select2EstoqueHistoricoFunctions.init();
        estoqueHistoricoFunctions.init();
        dataGridEstoqueHistoricoFunctions.init();
    });
</script>
