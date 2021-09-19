<script>
    const select2EstoqueBaixaFunctions = {
        init: () => {
            select2EstoqueBaixaFunctions.buscarEmpresa();
            select2EstoqueBaixaFunctions.buscarEstoque();
            select2EstoqueBaixaFunctions.buscarEmpresaCategoria();
            select2EstoqueBaixaFunctions.buscarUsuario();
            select2EstoqueBaixaFunctions.buscarProduto();
            select2EstoqueBaixaFunctions.buscarFornecedor();
        },
        buscarEmpresa: () => {
            let elementSelect2 = $("[data-select='buscarEmpresa']");
            let url = `${BASEURL}/empresa/backendCall/selectEmpresa`;
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
        buscarEstoque: () => {
            let elementSelect2 = $("[data-select='buscarEstoque']");
            let url = `${BASEURL}/estoque/backendCall/selectEstoque`;
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
        buscarEmpresaCategoria: () => {
            let elementSelect2 = $("[data-select='buscarEmpresaCategoria']");
            let url = `${BASEURL}/empresa/backendCall/selectEmpresaCategoria`;
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
                formatSelection: (data) => {
                    $("input[name='quantidade_atual']").val(data.estoque_atual);
                    return data.text;
                }
            });
        },
        buscarFornecedor: () => {
            let elementSelect2 = $("[data-select='buscarFornecedor']");
            let url = `${BASEURL}/fornecedor/backendCall/selectFornecedor`;
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

    const estoqueBaixaFunctions = {
        init: () => {
            estoqueBaixaFunctions.listenerFiltros();
            estoqueBaixaFunctions.listenerBaixarProdutoObservacao();
        },
        listenerFiltros: () => {
            $(document).on('click', "[data-action='btnLimpar']", () => {
                // Busca todos os elementos que possuem o atributo 'data-filtro' e que iniciam com 'filtro_'
                $("[data-filtro^='filtro_']").find("input,select,textarea").val('');
                $("[data-filtro^='filtro_']").find("input").select2('val', '');
                $('#tableVerBaixas').DataTable(dataGridGlobalFunctions.getSettings(0, 'baixas'));
            });

            $(document).on('click', "[data-action='btnFiltrar']", (e) => {
                e.preventDefault();
                dataGridOptionsFunctions.destroyTable("#tableVerBaixas");

                mapeamento[0]['baixas']['custom_data'] = [{
                    data_inicio: $("input[name='data_inicio']").val(),
                    data_fim: $("input[name='data_fim']").val(),
                    codigo_estoque: $("input[name='codigo_estoque']").val(),
                    codigo_usuario: $("input[name='codigo_usuario']").val(),
                    codigo_fornecedor: $("input[name='codigo_fornecedor']").val(),
                    codigo_empresa_categoria: $("input[name='codigo_empresa_categoria']").val(),
                    codigo_produto: $("input[name='codigo_produto']").val(),
                }];

                $('#tableVerBaixas').DataTable(dataGridGlobalFunctions.getSettings(0, 'baixas'));
            })
        },
        listenerBaixarProdutoObservacao: () => {
            $(document).on('click', "[data-action='btnPreenchimentoRapido']", function() {

                $("[data-action='btnPreenchimentoRapido']").each((i, el) => {
                    $(el).removeClass('btn-success').addClass('btn-secondary');
                });

                $(this).addClass('btn-success').css({
                    color: 'white'
                });

                $("input[name='observacao_rapida']").val($(this).text());
            });
        },
    };

    const dataGridEstoqueBaixaFunctions = {
        init: () => {
            if (METODO == 'indexBaixa') {
                dataGridEstoqueBaixaFunctions.mapeamento();
                $("[data-action='btnFiltrar']").trigger('click');
            }
        },
        mapeamento: () => {
            mapeamento[0] = [];
            mapeamento[0]['baixas'] = [];
            mapeamento[0]['baixas']['id_column'] = `uuid_estoque_baixa`;
            mapeamento[0]['baixas']['ajax_url'] = `${BASEURL}/estoque/getDataGridBaixa`;
            mapeamento[0]['baixas']['order_by'] = [{
                "coluna": 0,
                "metodo": "DESC"
            }];
            mapeamento[0]['baixas']['columns'] = [{
                    "data": "codigo_estoque_baixa",
                    "title": "Código da baixa"
                },
                {
                    "data": "usuario_criacao",
                    "title": "Solicitante"
                },
                {
                    "data": "quantidade",
                    "title": "Quantidade baixada"
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
                    "data": "nome_cadastro_movimentacao_tipo",
                    "title": "Motivo",
                    "render": (data, type, row) => `${row.nome_cadastro_movimentacao_tipo} ${row.codigo_venda ? `- Venda: ${row.codigo_venda}` : ''} ${row.observacao ? `(${row.observacao})` : ''}`
                },
                {
                    "data": "criado_em",
                    "title": "Data da baixa"
                },
                {
                    "data": "uuid_estoque_baixa",
                    "title": "Ações",
                    "className": "text-center"
                }
            ];
            mapeamento[0]['baixas']['btn_montar'] = false;
            mapeamento[0]['baixas']['btn'] = [{
                "funcao": "imprimir",
                "metodo": "estoque/recibo/baixa",
                "compare": null
            }, ];
        },
    }

    document.addEventListener("DOMContentLoaded", () => {
        select2EstoqueBaixaFunctions.init();
        estoqueBaixaFunctions.init();
        dataGridEstoqueBaixaFunctions.init();
    });
</script>
