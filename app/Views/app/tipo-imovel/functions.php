<script>
    const select2TipoImovelFunctions = {
        init: () => {
            // select2TipoImovelFunctions.buscarGrupo();
        },
        buscarGrupo: (caller) => {
            let elementSelect2 = $("[data-select='buscarGrupo']");
            let url = `${BASEURL}/grupo/backendCall/selectCadastroGrupo`;
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

    const TipoImovelFunctions = {
        init: () => {},
    };

    const TipoImovelPerfilFunctions = {
        init: () => {
            TipoImovelPerfilFunctions.listenerUploadAvatar();
        },
        listenerUploadAvatar: () => {
            //Ativa o Plugin
            const pond = FilePond.create(document.getElementById("avatar"), {
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
            pondBox.addEventListener('FilePond:addfile', e => {
                let base64Avatar = pond.getFile().getFileEncodeDataURL();
                $("input[name='avatar']").val(base64Avatar);
                $("input[name='avatar_nome']").val(pond.getFile().filename);
            });
        }
    };

    const dataGridTipoImovelFunctions = {
        init: () => {
            dataGridTipoImovelFunctions.mapeamentoTipoImovel();

            if (METODO == 'index') {
                $('#tableAtivos').DataTable(dataGridGlobalFunctions.getSettings(0));
                $('#tableInativos').DataTable(dataGridGlobalFunctions.getSettings(1));
            }
        },
        mapeamentoTipoImovel: () => {
            // Ativos
            mapeamento[0] = [];
            mapeamento[0][ROUTE] = [];
            mapeamento[0][ROUTE]['id_column'] = `uuid_tipo_imovel`;
            mapeamento[0][ROUTE]['ajax_url'] = `${BASEURL}/tipoImovel/getDataGrid/1`;
            mapeamento[0][ROUTE]['order_by'] = [{
                "coluna": 0,
                "metodo": "ASC"
            }];
            mapeamento[0][ROUTE]['columns'] = [{
                    "data": "codigo_tipo_imovel",
                    "title": "Código"
                },
                {
                    "data": "nome",
                    "title": "Nome"
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
                    "data": "uuid_tipo_imovel",
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
            mapeamento[1][ROUTE]['id_column'] = `uuid_tipo_imovel`;
            mapeamento[1][ROUTE]['ajax_url'] = `${BASEURL}/tipoImovel/getDataGrid/0`;
            mapeamento[1][ROUTE]['order_by'] = [{
                "coluna": 0,
                "metodo": "ASC"
            }];
            mapeamento[1][ROUTE]['columns'] = [{
                    "data": "codigo_tipo_imovel",
                    "title": "Código"
                },
                {
                    "data": "nome",
                    "title": "Nome"
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
                    "data": "uuid_tipo_imovel",
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
        TipoImovelFunctions.init();
        select2TipoImovelFunctions.init();
        dataGridTipoImovelFunctions.init();

        if (METODO == 'indexPerfil') {
            // TipoImovelPerfilFunctions.init();
        }
    });
</script>
