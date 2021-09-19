<script>
    const select2EstoqueFunctions = {
        init: () => {
            select2EstoqueFunctions.buscarEstoque();
            select2EstoqueFunctions.buscarProduto();
            select2EstoqueFunctions.buscarFornecedor();
        },
        buscarEstoque: () => {
            let elementSelect2 = $("[data-select='buscarEstoque']");
            let url = `${BASEURL}/estoque/backendCall/selectEstoque`;
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
        }
    };

    const estoqueFunctions = {
        init: () => {
            estoqueFunctions.listenerFiltros();
        },
        listenerFiltros: () => {
            $(document).on('click', "#estoque [data-action='btnLimpar']", () => {
                // Busca todos os elementos que possuem o atributo 'data-filtro' e que iniciam com 'filtro_'
                $("[data-filtro^='filtro_']").find("input,select,textarea").val('');
                $("[data-filtro^='filtro_']").find("input").select2('val', '');
                $('#tableLista').DataTable(dataGridGlobalFunctions.getSettings(0));
            });

            $(document).on('click', "#estoque [data-action='btnFiltrar']", (e) => {
                e.preventDefault();
                dataGridOptionsFunctions.destroyTable("#tableLista");

                mapeamento[0][ROUTE]['custom_data'] = [{
                    codigo_estoque: $("input[name='codigo_estoque']").val(),
                    codigo_forecedor: $("input[name='codigo_forecedor']").val(),
                    codigo_produto: $("input[name='codigo_produto']").val(),
                    exibir_produtos: $("select[name='exibir_produtos']").val(),
                }];

                $('#tableLista').DataTable(dataGridGlobalFunctions.getSettings(0));
            })
        }
    };

    const dataGridEstoqueFunctions = {
        init: () => {

            if (METODO == 'index') {
                dataGridEstoqueFunctions.mapeamento();
                $("[data-action='btnFiltrar']").trigger('click');
            }
        },
        mapeamento: async () => {
            // Lista
            mapeamento[0] = [];
            mapeamento[0][ROUTE] = [];
            mapeamento[0][ROUTE]['id_column'] = `uuid_estoque_produto`;
            mapeamento[0][ROUTE]['ajax_url'] = `${BASEURL}/estoque/getDataGrid/1`;
            mapeamento[0][ROUTE]['order_by'] = [{
                "coluna": 3,
                "metodo": "ASC"
            }];
            mapeamento[0][ROUTE]['columns'] = [{
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
            mapeamento[0][ROUTE]['btn_montar'] = false;

            // Grade
            mapeamento[1] = [];
            mapeamento[1][ROUTE] = [];
            mapeamento[1][ROUTE]['id_column'] = `uuid_estoque_produto`;
            mapeamento[1][ROUTE]['ajax_url'] = `${BASEURL}/estoque/getDataGrid/0`;
            mapeamento[1][ROUTE]['order_by'] = [{
                "coluna": 0,
                "metodo": "ASC"
            }];
            mapeamento[1][ROUTE]['columns'] = [{
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
            }];
            mapeamento[1][ROUTE]['btn_montar'] = false;


            // Busca os estoques que o usuário tem acesso antes de montar o Datagrid
            await appFunctions.backendCall('POST', `estoque/backendCall/selectEstoque`, {}).then(
                (res) => {
                    if (res && res.statusCode == 200) {
                        res.forEach((el, i) => {
                            if (i > 0 && i < 4) {
                                mapeamento[1][ROUTE]['columns'].push({
                                    "data": `estoque${el.id}`,
                                    "title": el.text ? `${el.text.split(' ')[0].toUpperCase()}` : ''
                                })
                            }
                        });
                    }
                }
            );
            // $("#tableGrade").DataTable(dataGridGlobalFunctions.getSettings(1)).draw(false);
        },
    };

    document.addEventListener("DOMContentLoaded", () => {
        estoqueFunctions.init();
        select2EstoqueFunctions.init();
        dataGridEstoqueFunctions.init();
    });
</script>
