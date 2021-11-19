<script>
const select2ImovelFunctions = {
    init: () => {
        select2ImovelFunctions.buscarProprietario();
        select2ImovelFunctions.buscarCategoriaImovel();
        select2ImovelFunctions.buscarTipoImovel();
    },
    buscarProprietario: (caller) => {
        let elementSelect2 = $("[data-select='buscarProprietario']");
        let url = `${BASEURL}/proprietario/backendCall/selectProprietario`;
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

const imovelFunctions = {
    init: () => {
        imovelFunctions.listenerFiltros();
        imovelFunctions.listenerUploadImagemDestaque();
        imovelFunctions.listenerUploadImagens();
        imovelFunctions.listenerAlterarImagemDestaque();
        imovelFunctions.confirmDesativar();
        imovelFunctions.listenerBuscarCepImovel();

    },
    listenerBuscarCepImovel: () => {
        $(document).on('keyup', "input[name='cep']", function() {
            const cep = $(this).val();
            if (cep.length >= 9) {
                appFunctions.buscarCep(cep).then(
                    (retorno) => {
                        if (retorno) {
                            $("input[name='rua']").val(retorno.street);
                            $("input[name='bairro']").val(retorno.neighborhood);
                            $("input[name='cidade']").val(retorno.city);
                            $("input[name='cidade_completa']").val(
                                `${retorno.city}/${retorno.state}`);
                            $("input[name='uf']").val(retorno.state);
                            $("input[name='numero']").val('');
                            $("input[name='numero']").focus();
                        }
                    }
                )
            }
        });
    },
    listenerUploadImagemDestaque: () => {
        //Ativa o Plugin
        const pondImgDestaque = FilePond.create(document.getElementById("imagemImovel"), {
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
        const pondBoxImgDestaque = document.querySelector('.filepond--root');
        if (pondBoxImgDestaque !== null) {
            pondBoxImgDestaque.addEventListener('FilePond:addfile', e => {
                let base64Img = pondImgDestaque.getFile().getFileEncodeDataURL();
                $("input[name='imagem']").val(base64Img);
                $("input[name='imagem_nome']").val(pondImgDestaque.getFile().filename);
            });
        }
    },
    listenerUploadImagens: () => {
        //Ativa o Plugin
        const pondImgDestaque = FilePond.create(document.getElementById("imagensImovel"), {
            labelIdle: `Arraste e solte sua imagem aqui ou <span class="filepond--label-action">escolha</span>`,
            imagePreviewHeight: 170,
            imageCropAspectRatio: '1:1',
            imageResizeTargetWidth: 200,
            imageResizeTargetHeight: 200,
            stylePanelLayout: 'compact',
            allowFileEncode: true,
            allowReorder: true,
            dropOnPage: true,
            type: 'local',
            labelTapToCancel: 'Cancelar',
            labelTapToUndo: 'Voltar',
        });
    },
    listenerFiltros: () => {
        $(document).on('click', "#imovel [data-action='btnLimpar']", () => {
            // Busca todos os elementos que possuem o atributo 'data-filtro' e que iniciam com 'filtro_'
            $("[data-filtro^='filtro_']").find("input,select,textarea").val('');
            $("[data-filtro^='filtro_']").find("input").select2('val', '');
            $('#tableAtivos').DataTable(dataGridGlobalFunctions.getSettings(0));
            $('#tableInativos').DataTable(dataGridGlobalFunctions.getSettings(1));
        });

        $(document).on('click', "#imovel [data-action='btnFiltrar']", (e) => {
            e.preventDefault();
            if ($.fn.DataTable.isDataTable("#tableAtivos")) {
                $('#tableAtivos').DataTable().destroy();
            }
            if ($.fn.DataTable.isDataTable("#tableInativos")) {
                $('#tableInativos').DataTable().destroy();
            }

            let filtros = [{
                codigo_fornecedor: $("input[name='codigo_fornecedor']").val(),
                codigo_imovel: $("input[name='codigo_imovel']").val(),
                categorias: $("input[name='categorias']").val(),
            }];

            mapeamento[0][ROUTE]['custom_data'] = filtros;
            mapeamento[1][ROUTE]['custom_data'] = filtros;

            $('#tableAtivos').DataTable(dataGridGlobalFunctions.getSettings(0));
            $('#tableInativos').DataTable(dataGridGlobalFunctions.getSettings(1));
        })
    },
    doOperation: (handler) => {

        // Realiza a Requisição das Operações
        $.ajax({
            url: $(handler).data('url'),
            dataType: 'json',
            type: 'POST',
            beforeSend: () => $('body').addClass('carregando'),
            error: (error) => $('body').removeClass('carregando'),
            success: function(data) {
                if (data) {
                    notificationFunctions.toastSmall('success', 'Imagem removida sucesso!');
                    $(`[data-image='${$(handler).data('id')}']`).addClass('d-none');
                } else {
                    notificationFunctions.toastSmall('error',
                        ' Esta imagem não pode ser removida!');
                }

            }
        }).done(() => $('body').removeClass('carregando'));
    },

    confirmDesativar: () => {
        //
        // Desativar Registro
        $(document).on('click', "[data-action='removerImagem']", function(e) {
            let _this = this;
            notificationFunctions.popupConfirm('Atenção', 'Deseja realmente remover a imagem ?',
                'warning').then(
                (result) => {
                    if (result.value) {
                        imovelFunctions.doOperation(_this);
                        e.preventDefault();
                    } else {
                        e.preventDefault();
                    }
                }
            );
        });
    },
    listenerAlterarImagemDestaque: () => {
        $(document).on('click', "[data-action='alterarImagemDestaque']", async function(e) {
            $("#containerPluginImagem").removeClass('d-none');
            $("[data-action='cancelarAlterarImagemDestaque']").removeClass('d-none');

            $("#imagemEdicao").addClass('d-none');
            $("[data-action='alterarImagemDestaque']").addClass('d-none');
        });

        // Cancela a alteracao
        $(document).on('click', "[data-action='cancelarAlterarImagemDestaque']", async function(e) {
            $("#containerPluginImagem").addClass('d-none');
            $("[data-action='alterarImagemDestaque']").removeClass('d-none');
            $("input[name='imagem']").val('');
            $("input[name='imagem_nome']").val('');
            $("#imagemimovel").val('');

            $("#imagemEdicao").removeClass('d-none');
            $("[data-action='cancelarAlterarImagemDestaque']").addClass('d-none');
        });
    },
};

const dataGridImovelFunctions = {
    init: () => {
        dataGridImovelFunctions.mapeamentoImovel();

        if (METODO == 'index') {
            $('#tableAtivos').DataTable(dataGridGlobalFunctions.getSettings(0));
            $('#tableInativos').DataTable(dataGridGlobalFunctions.getSettings(1));
        }
    },
    mapeamentoImovel: () => {
        // Ativos
        mapeamento[0] = [];
        mapeamento[0][ROUTE] = [];
        mapeamento[0][ROUTE]['id_column'] = `uuid_imovel`;
        mapeamento[0][ROUTE]['ajax_url'] = `${BASEURL}/imovel/getDataGrid/1`;
        mapeamento[0][ROUTE]['order_by'] = [{
            "coluna": 0,
            "metodo": "ASC"
        }];
        mapeamento[0][ROUTE]['columns'] = [{
                "data": "codigo_imovel",
                "title": "Código"
            }, {
                "data": "codigo_referencia",
                "title": "Referência"
            },
            {
                "data": "categoria",
                "title": "Categoria",
            },
            {
                "data": "valor",
                "title": "Valor",
                "className": "text-end",
                "isreplace": true,
                "render": (data) => `R$ ${convertFunctions.intToReal(data)}`
            },
            {
                "data": "criado_em",
                "visible": false,
                "title": "Criado em",
                "render": (data) => `${moment(data).format('DD/MM/YYYY HH:mm')}`
            },
            {
                "data": "alterado_em",
                "visible": false,
                "title": "Alterado em",
                "render": (data) => `${moment(data).format('DD/MM/YYYY HH:mm')}`
            },
            {
                "data": "uuid_imovel",
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
        mapeamento[1][ROUTE]['id_column'] = `uuid_imovel`;
        mapeamento[1][ROUTE]['ajax_url'] = `${BASEURL}/imovel/getDataGrid/0`;
        mapeamento[1][ROUTE]['order_by'] = [{
            "coluna": 0,
            "metodo": "ASC"
        }];
        mapeamento[1][ROUTE]['columns'] = [{
                "data": "codigo_imovel",
                "visible": true,
                "title": "Código"
            }, {
                "data": "codigo_referencia",
                "visible": true,
                "title": "Referência"
            },
            {
                "data": "categoria",
                "title": "Categoria",
            },
            {
                "data": "valor",
                "title": "Valor",
                "className": "text-end",
                "isreplace": true,
                "render": (data) => `R$ ${convertFunctions.intToReal(data)}`
            },
            {
                "data": "criado_em",
                "visible": false,
                "title": "Criado em",
                "render": (data) => `${moment(data).format('DD/MM/YYYY HH:mm')}`
            },
            {
                "data": "alterado_em",
                "visible": false,
                "title": "Alterado em",
                "render": (data) => `${moment(data).format('DD/MM/YYYY HH:mm')}`
            },
            {
                "data": "uuid_imovel",
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
    dataGridImovelFunctions.init();
    imovelFunctions.init();
    select2ImovelFunctions.init();

});
</script>
