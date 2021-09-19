<script>
    const select2ProdutoFunctions = {
        init: () => {
            select2ProdutoFunctions.buscarFornecedor();
            select2ProdutoFunctions.buscarCategoria();
            select2ProdutoFunctions.buscarProduto();
        },
        buscarFornecedor: (caller) => {
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
        buscarCategoria: (caller) => {
            let elementSelect2 = $("[data-select='buscarCategoria']");
            let url = `${BASEURL}/empresa/backendCall/selectEmpresaCategoria`;
            elementSelect2.select2({
                placeholder: "Selecione...",
                allowClear: false,
                multiple: true,
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
        buscarProduto: (caller) => {
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
    };

    const produtoFunctions = {
        init: () => {
            produtoFunctions.listenerFiltros();
            produtoFunctions.listenerUploadImagem();
            produtoFunctions.listenerGerarCodigoBarras();
            produtoFunctions.listenerAlterarPreco();
        },
        listenerUploadImagem: () => {
            //Ativa o Plugin
            const pond = FilePond.create(document.getElementById("imagemProduto"), {
                labelIdle: `Arraste e solte sua imagem aqui ou <span class="filepond--label-action">escolha</span>`,
                imagePreviewHeight: 170,
                imageCropAspectRatio: '1:1',
                imageResizeTargetWidth: 200,
                imageResizeTargetHeight: 200,
                stylePanelLayout: 'compact',
                allowFileEncode: true,
                type: 'local',
                labelTapToCancel: 'Cancelar',
                labelTapToUndo: 'Voltar',
            });
            const pondBox = document.querySelector('.filepond--root');
            if (pondBox !== null) {
                pondBox.addEventListener('FilePond:addfile', e => {
                    let base64Img = pond.getFile().getFileEncodeDataURL();
                    $("input[name='imagem']").val(base64Img);
                    $("input[name='imagemProduto']").val(pond.getFile().filename);
                });
            }
        },
        listenerFiltros: () => {
            $(document).on('click', "#produto [data-action='btnLimpar']", () => {
                // Busca todos os elementos que possuem o atributo 'data-filtro' e que iniciam com 'filtro_'
                $("[data-filtro^='filtro_']").find("input,select,textarea").val('');
                $("[data-filtro^='filtro_']").find("input").select2('val', '');
                $('#tableAtivos').DataTable(dataGridGlobalFunctions.getSettings(0));
                $('#tableInativos').DataTable(dataGridGlobalFunctions.getSettings(1));
            });

            $(document).on('click', "#produto [data-action='btnFiltrar']", (e) => {
                e.preventDefault();
                if ($.fn.DataTable.isDataTable("#tableAtivos")) {
                    $('#tableAtivos').DataTable().destroy();
                }
                if ($.fn.DataTable.isDataTable("#tableInativos")) {
                    $('#tableInativos').DataTable().destroy();
                }

                let filtros = [{
                    codigo_fornecedor: $("input[name='codigo_fornecedor']").val(),
                    codigo_produto: $("input[name='codigo_produto']").val(),
                    categorias: $("input[name='categorias']").val(),
                }];

                mapeamento[0][ROUTE]['custom_data'] = filtros;
                mapeamento[1][ROUTE]['custom_data'] = filtros;

                $('#tableAtivos').DataTable(dataGridGlobalFunctions.getSettings(0));
                $('#tableInativos').DataTable(dataGridGlobalFunctions.getSettings(1));
            })
        },
        listenerGerarCodigoBarras: () => {
            $(document).on('click', "[data-action='gerarCodigo']", async function() {
                await appFunctions.backendCall('GET', `produto/gerarCodigoBarras/${$(this).data('tipo')}`)
                    .then(res => $("input[name='codigo_barras']").val(res))
                    .catch(err => notificationFunctions.toastSmall(err.textStatus, err.mensagem));
            });
        },
        listenerAlterarPreco: () => {
            // Abre a Modal de Alterar Preço
            $(document).on('click', "[data-action='produtoAlterarPreco']", async function() {
                await appFunctions.backendCall('POST', `estoque/backendCall/selectEstoqueProduto`, {
                    uuid_produto: $(this).data('id'),
                    page: 1
                }).then(
                    (res) => {
                        if (res && res.itens) {
                            let prod = res.itens[0];
                            $("#modalProdutoAlterarPreco input[name='nome']").val(prod.nome);
                            $("#modalProdutoAlterarPreco input[name='valor_fabrica']").val(convertFunctions.intToReal(prod.valor_fabrica));
                            $("#modalProdutoAlterarPreco input[name='valor_venda']").val(convertFunctions.intToReal(prod.valor_venda));
                            $("#modalProdutoAlterarPreco input[name='valor_ecommerce']").val(convertFunctions.intToReal(prod.valor_ecommerce));
                            $("#modalProdutoAlterarPreco input[name='valor_atacado']").val(convertFunctions.intToReal(prod.valor_atacado));

                            $("#modalProdutoAlterarPreco form").attr('action', `${BASEURL}/produto/alterarPreco/${$(this).data('id')}`)
                            $("#modalProdutoAlterarPreco").modal('show');
                        }
                    }
                ).catch(err => notificationFunctions.toastSmall(err.textStatus, err.mensagem));
            });

            // Realiza a alteracao de preco
            $(document).on('click', "[data-action='realizarAlteracaoPreco']", async function(e) {
                // Realiza Validações antes de realizar o Submit do formulário

                if ($("#modalProdutoAlterarPreco input[name='valor_fabrica']").val() == '0,00') {
                    e.preventDefault();
                    notificationFunctions.toastSmall('error', 'O preço de custo não pode ser vazio.');
                    return;
                }

                if ($("#modalProdutoAlterarPreco input[name='valor_venda']").val() == '0,00') {
                    e.preventDefault();
                    notificationFunctions.toastSmall('error', 'O preço de venda não pode ser vazio.');
                    return;
                }

            });
        }
    };

    const dataGridProdutoFunctions = {
        init: () => {
            dataGridProdutoFunctions.mapeamentoProduto();

            if (METODO == 'index') {
                $('#tableAtivos').DataTable(dataGridGlobalFunctions.getSettings(0));
                $('#tableInativos').DataTable(dataGridGlobalFunctions.getSettings(1));
            }
        },
        mapeamentoProduto: () => {
            // Ativos
            mapeamento[0] = [];
            mapeamento[0][ROUTE] = [];
            mapeamento[0][ROUTE]['id_column'] = `uuid_produto`;
            mapeamento[0][ROUTE]['ajax_url'] = `${BASEURL}/produto/getDataGrid/1`;
            mapeamento[0][ROUTE]['order_by'] = [{
                "coluna": 3,
                "metodo": "ASC"
            }];
            mapeamento[0][ROUTE]['columns'] = [{
                    "data": "codigo_produto",
                    "title": "Código Produto"
                },
                {
                    "data": "codigo_barras",
                    "title": "Código Barras",
                },
                {
                    "data": "nome",
                    "title": "Nome"
                },
                {
                    "data": "referencia_fornecedor",
                    "title": "Referência Fornecedor"
                },
                // {
                //     "data": "categorias",
                //     "title": "Categorias",
                // },
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
                    "data": "uuid_produto",
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
                    "funcao": "produtoVisualizarHistorico",
                    "metodo": "",
                    "compare": null
                },
                {
                    "funcao": "produtoAlterarPreco",
                    "metodo": "",
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
            mapeamento[1][ROUTE]['id_column'] = `uuid_produto`;
            mapeamento[1][ROUTE]['ajax_url'] = `${BASEURL}/produto/getDataGrid/0`;
            mapeamento[1][ROUTE]['order_by'] = [{
                "coluna": 0,
                "metodo": "ASC"
            }];
            mapeamento[1][ROUTE]['columns'] = [{
                    "data": "codigo_produto",
                    "title": "Código Produto"
                },
                {
                    "data": "codigo_barras",
                    "title": "Código barras",
                },
                {
                    "data": "nome",
                    "title": "Nome"
                },
                {
                    "data": "referencia_fornecedor",
                    "title": "Referência Fornecedor"
                },
                // {
                //     "data": "categorias",
                //     "title": "Categorias",
                // },
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
                    "data": "uuid_produto",
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
        dataGridProdutoFunctions.init();
        produtoFunctions.init();
        select2ProdutoFunctions.init();

    });
</script>
