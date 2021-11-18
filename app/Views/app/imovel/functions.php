<script>
const select2imovelFunctions = {
    init: () => {
        select2imovelFunctions.buscarProprietario();
        select2imovelFunctions.buscarCategoriaImovel();
        select2imovelFunctions.buscarTipoImovel();
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
        imovelFunctions.listenerGerarCodigoBarras();
        imovelFunctions.listenerAlterarPreco();
        imovelFunctions.listenerAlterarImagemDestaque();
        imovelFunctions.listenerVisualizarEstoque();
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
        const pondImgDestaque = FilePond.create(document.getElementById("imagem_imovel"), {
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
        const pondImgDestaque = FilePond.create(document.getElementById("imagens_imovel"), {
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
    listenerGerarCodigoBarras: () => {
        $(document).on('click', "[data-action='gerarCodigo']", async function() {
            await appFunctions.backendCall('GET',
                    `imovel/gerarCodigoBarras/${$(this).data('tipo')}`)
                .then(res => $("input[name='codigo_barras']").val(res))
                .catch(err => notificationFunctions.toastSmall(err.textStatus, err.mensagem));
        });
    },
    listenerAlterarPreco: () => {
        // Abre a Modal de Alterar Preço
        $(document).on('click', "[data-action='imovelAlterarPreco']", async function() {
            await appFunctions.backendCall('POST', `estoque/backendCall/selectEstoqueimovel`, {
                uuid_imovel: $(this).data('id'),
                page: 1,
                orderBy: 'quantidade'
            }).then(
                (res) => {
                    if (res && res.itens) {
                        let prod = res.itens[0];
                        $("#modalimovelAlterarPreco input[name='nome']").val(prod.nome);
                        $("#modalimovelAlterarPreco input[name='valor_fabrica']").val(
                            convertFunctions.intToReal(prod.valor_fabrica));
                        $("#modalimovelAlterarPreco input[name='valor_venda']").val(
                            convertFunctions.intToReal(prod.valor_venda));
                        $("#modalimovelAlterarPreco input[name='valor_ecommerce']").val(
                            convertFunctions.intToReal(prod.valor_ecommerce));
                        $("#modalimovelAlterarPreco input[name='valor_atacado']").val(
                            convertFunctions.intToReal(prod.valor_atacado));

                        $("#modalimovelAlterarPreco form").attr('action',
                            `${BASEURL}/imovel/alterarPreco/${$(this).data('id')}`)
                        $("#modalimovelAlterarPreco").modal('show');
                    }
                }
            ).catch(err => notificationFunctions.toastSmall(err.textStatus, err.mensagem));
        });

        // Realiza a alteracao de preco
        $(document).on('click', "[data-action='realizarAlteracaoPreco']", async function(e) {
            // Realiza Validações antes de realizar o Submit do formulário

            if ($("#modalimovelAlterarPreco input[name='valor_fabrica']").val() == '0,00') {
                e.preventDefault();
                notificationFunctions.toastSmall('error', 'O preço de custo não pode ser vazio.');
                return;
            }

            if ($("#modalimovelAlterarPreco input[name='valor_venda']").val() == '0,00') {
                e.preventDefault();
                notificationFunctions.toastSmall('error', 'O preço de venda não pode ser vazio.');
                return;
            }

        });
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
                        // imovelFunctions.listenerRemoverImagem();
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
    listenerVisualizarEstoque: () => {
        // Abre a Modal de Visualizar Estoque do imovel
        $(document).on('click', "[data-action='imovelVisualizarEstoque']", function() {
            $("#modalConsultarimovel").modal('show');
            $("[data-select='buscarimovelModal']").select2('val', $(this).data('id')).change();
        });
    },
};

const dataGridimovelFunctions = {
    init: () => {
        dataGridimovelFunctions.mapeamentoimovel();

        if (METODO == 'index') {
            $('#tableAtivos').DataTable(dataGridGlobalFunctions.getSettings(0));
            $('#tableInativos').DataTable(dataGridGlobalFunctions.getSettings(1));
        }
    },
    mapeamentoimovel: () => {
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
                "data": "referencia",
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
                "data": "referencia",
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
            "funcao": "desativar",
            "metodo": "",
            "compare": null
        }, ];
    },
};

document.addEventListener("DOMContentLoaded", () => {
    dataGridimovelFunctions.init();
    imovelFunctions.init();
    select2imovelFunctions.init();

});
</script>
