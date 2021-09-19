<script>
    const select2UsuarioFunctions = {
        init: () => {
            select2UsuarioFunctions.buscarEmpresa();
            select2UsuarioFunctions.buscarRelatorio();
        },
        buscarEmpresa: (caller) => {
            let elementSelect2 = $("[data-select='buscarEmpresa']");
            let url = `${BASEURL}/empresa/backendCall/selectEmpresa`;
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
        buscarRelatorio: (caller) => {
            let elementSelect2 = $("[data-select='buscarRelatorio']");
            let url = `${BASEURL}/grupo/backendCall/selectRelatorio`;
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
        }
    };

    const grupoFunctions = {
        init: () => {
            grupoFunctions.selecionarTodasOpcoes();
        },
        selecionarTodasOpcoes: () => {
            //Marca todos os elementos da coluna
            $(document).on('click', "[data-action='marcarTodosColuna']", function() {
                $(this).parent().find("[data-action='desmarcaTodosColuna']").removeClass('d-none');
                $(this).addClass('d-none');

                let coluna = $(this).parents('th').index();
                $.each($('tbody').find('tr'), function(key, value) {
                    $(value).find('td').eq(coluna).find('input').prop('checked', true);
                });
            });

            //Desmarca todos os elementos da coluna
            $(document).on('click', "[data-action='desmarcaTodosColuna']", function() {
                $(this).parent().find("[data-action='marcarTodosColuna']").removeClass('d-none');
                $(this).addClass('d-none');

                let coluna = $(this).parents('th').index();
                $.each($('tbody').find('tr'), function(key, value) {
                    $(value).find('td').eq(coluna).find('input').prop('checked', false);
                });
            });

            // Marca todas as os elementos da linha
            $(document).on('click', "[data-action='selecionarTodosLinha']", function() {
                $(this).parent().find("[data-action='desmarcaTodosLinha']").removeClass('d-none');
                $(this).addClass('d-none');

                for (let i = 1; i <= 4; i++) {
                    let input = $(this).parents('tr').find('td').eq(i).find('input').prop('checked', true);
                }
            });

            // Desmarca todas as os elementos da linha
            $(document).on('click', "[data-action='desmarcaTodosLinha']", function() {
                $(this).parent().find("[data-action='selecionarTodosLinha']").removeClass('d-none');
                $(this).addClass('d-none');
                for (let i = 1; i <= 4; i++) {
                    let input = $(this).parents('tr').find('td').eq(i).find('input').prop('checked', false);
                }
            });
        },
    };

    const dataGridGrupoFunctions = {
        init: () => {
            dataGridGrupoFunctions.mapeamentoGrupo();

            if (METODO == 'index') {
                $('#tableAtivos').DataTable(dataGridGlobalFunctions.getSettings(0));
                $('#tableInativos').DataTable(dataGridGlobalFunctions.getSettings(1));
            }
        },
        mapeamentoGrupo: () => {
            // Ativos
            mapeamento[0] = [];
            mapeamento[0][ROUTE] = [];
            mapeamento[0][ROUTE]['id_column'] = `uuid_cadastro_grupo`;
            mapeamento[0][ROUTE]['ajax_url'] = `${BASEURL}/grupo/getDataGrid/1`;
            mapeamento[0][ROUTE]['order_by'] = [{
                "coluna": 1,
                "metodo": "ASC"
            }];
            mapeamento[0][ROUTE]['columns'] = [{
                    "data": "codigo_cadastro_grupo",
                    "title": "Código"
                },
                {
                    "data": "nome",
                    "title": "Nome"
                },
                {
                    "data": "slug",
                    "title": "Slug"
                },
                {
                    "data": "usuarios",
                    "title": "Quantidade de Usuários"
                },
                {
                    "data": "relatorios",
                    "title": "Quantidade de Relatórios"
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
                    "data": "uuid_cadastro_grupo",
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
                    "funcao": "toggleStatus",
                    "metodo": "",
                    "compare": null
                },
            ];

            // Inativos
            mapeamento[1] = [];
            mapeamento[1][ROUTE] = [];
            mapeamento[1][ROUTE]['id_column'] = `uuid_cadastro_grupo`;
            mapeamento[1][ROUTE]['ajax_url'] = `${BASEURL}/grupo/getDataGrid/0`;
            mapeamento[1][ROUTE]['order_by'] = [{
                "coluna": 1,
                "metodo": "ASC"
            }];
            mapeamento[1][ROUTE]['columns'] = [{
                    "data": "codigo_cadastro_grupo",
                    "title": "Código"
                },
                {
                    "data": "nome",
                    "title": "Nome"
                },
                {
                    "data": "slug",
                    "title": "Slug"
                },
                {
                    "data": "usuarios",
                    "title": "Quantidade de Usuários"
                },
                {
                    "data": "relatorios",
                    "title": "Quantidade de Relatórios"
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
                    "data": "uuid_cadastro_grupo",
                    "title": "Ações",
                    "className": "text-center"
                }
            ];
            mapeamento[1][ROUTE]['btn_montar'] = true;
            mapeamento[1][ROUTE]['btn'] = [{
                "funcao": "toggleStatus",
                "metodo": "",
                "compare": null
            }, ];
        },
    }

    document.addEventListener("DOMContentLoaded", () => {
        select2UsuarioFunctions.init();
        grupoFunctions.init();
        dataGridGrupoFunctions.init();
    });
</script>
