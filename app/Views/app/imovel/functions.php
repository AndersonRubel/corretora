<script>
    const condominio = '<?= !empty($imovel['condominio']) ? $imovel['condominio'] : ''; ?>';
    const publicado = '<?= !empty($imovel['publicado']) ? $imovel['publicado'] : ''; ?>';
    const edicula = '<?= !empty($imovel['edicula']) ? $imovel['edicula'] : ''; ?>';
    const destaque = '<?= !empty($imovel['destaque']) ? $imovel['destaque'] : ''; ?>';
    const codigo_tipo_imovel = '<?= !empty($imovel['codigo_tipo_imovel']) ? $imovel['codigo_tipo_imovel'] : ''; ?>';
    const codigo_categoria_imovel = '<?= !empty($imovel['codigo_categoria_imovel']) ? $imovel['codigo_categoria_imovel'] : ''; ?>';
    const select2ImovelFunctions = {
        init: () => {
            select2ImovelFunctions.buscarProprietario();
            select2ImovelFunctions.buscarCategoriaImovel();
            select2ImovelFunctions.buscarTipoImovel();
            select2ImovelFunctions.buscarImovel();
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
        buscarImovel: (caller) => {
            let elementSelect2 = $("[data-select='buscarImovel']");
            let url = `${BASEURL}/imovel/backendCall/selectImovel`;
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

    const imovelFunctions = {
        init: () => {
            imovelFunctions.listenerFiltros();
            imovelFunctions.listenerUploadImagemDestaque();
            imovelFunctions.listenerUploadImagens();
            imovelFunctions.listenerAlterarImagemDestaque();
            imovelFunctions.confirmDesativar();
            imovelFunctions.listenerBuscarCepImovel();
            imovelFunctions.listenerProprietario();
            imovelFunctions.listenerPopulaFiltros();
            imovelFunctions.listenerTipoImovel();
            imovelFunctions.listenerModalHelp();
            imovelFunctions.listenerOnSubmit();
            $("[data-select='buscarTipoImovel']").change();
        },
        listenerOnSubmit: () => {
            $(document).on('click', "[data-action ='form-imovel-submit']", () => {
                cat_imovel = $("[data-select='buscarCategoriaImovel']").val();
                console.log(cat_imovel)
                if (cat_imovel == 1) {
                    $("#valor_venda").find('input').removeAttr('required');
                    $("#valor_venda").val('');
                } else if (cat_imovel == 2) {
                    $("#valor_aluguel").find('input').removeAttr('required')
                    $("#valor_aluguel").val('');
                }
                $("[id='form-imovel']").submit();
            })

        },
        listenerTipoImovel: () => {
            $(document).on('change', "[data-select='buscarCategoriaImovel']", async function(e) {
                $("#valor_venda").removeClass('d-none')
                $("#valor_aluguel").removeClass('d-none')
                if ($(this).val() == '1' || codigo_categoria_imovel == 1) {
                    $("#valor_venda").addClass('d-none')
                } else if ($(this).val() == '2' || codigo_categoria_imovel == 2) {
                    $("#valor_aluguel").addClass('d-none')
                }

            });
            $(document).on('change', "[data-select='buscarTipoImovel']", async function(e) {

                if ($(this).val() == '3' || codigo_tipo_imovel == 3) {

                    // $("[name='quarto']").addClass('d-none');

                    $("[id='quarto']").addClass('d-none').find('input').removeAttr('required');
                    $("[id='area_construida']").addClass('d-none').find('input').removeAttr('required');
                    $("[id='suite']").addClass('d-none');
                    $("[id='banheiro']").addClass('d-none').find('input').removeAttr('required');
                    $("[id='vaga']").addClass('d-none');
                    $("[id='edicula_campo']").addClass('d-none');

                    $("[id='quarto']").val('');
                    $("[id='area_construida']").val('');
                    $("[id='suite']").val('');
                    $("[id='banheiro']").val('');
                    $("[id='vaga']").val('');


                } else {
                    $("[id='quarto']").removeClass('d-none');
                    $("[id='quarto']").removeAttr('required');
                    $("[id='area_construida']").removeClass('d-none');
                    $("[id='suite']").removeClass('d-none');
                    $("[id='banheiro']").removeClass('d-none');
                    $("[id='vaga']").removeClass('d-none');
                    $("[id='edicula']").removeClass('d-none');
                }

            });
        },
        listenerPopulaFiltros: () => {

            $("#selectFormCondominio").val(condominio);
            $("#selectFormPublicado").val(publicado);
            $("#selectFormEdicula").val(edicula);
            $("#selectFormDestaque").val(destaque);
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
                allowImagePreview: 'false',
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
                    codigo_proprietario: $("input[name='codigo_proprietario']").val(),
                    codigo_imovel: $("input[name='codigo_imovel']").val(),
                    codigo_categoria_imovel: $("input[name='codigo_categoria_imovel']").val(),
                    codigo_tipo_imovel: $("input[name='codigo_tipo_imovel']").val(),
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
        listenerProprietario: () => {
            // Ao fechar a Modal limpa os campos
            $(document).on('hide.bs.modal', '#modalCadastrarProprietario', async function() {
                $("#modalCadastrarProprietario input").val('');
            });

            $(document).on('keyup', "#modalCadastrarProprietario [data-mask='cep']", function() {

                const cep = $(this).val();
                if (cep.length >= 9) {
                    appFunctions.buscarCep(cep).then(
                        (retorno) => {
                            if (retorno) {
                                $("#modalCadastrarProprietario input[name='rua']").val(retorno.street);
                                $("#modalCadastrarProprietario input[name='bairro']").val(retorno
                                    .neighborhood);
                                $("#modalCadastrarProprietario input[name='cidade']").val(retorno.city);
                                $("#modalCadastrarProprietario input[name='cidade_completa']").val(
                                    `${retorno.city}/${retorno.state}`);
                                $("#modalCadastrarProprietario input[name='uf']").val(retorno.state);
                                $("#modalCadastrarProprietario input[name='numero']").val('');
                                $("#modalCadastrarProprietario input[name='numero']").focus();
                            }
                        }
                    )
                }
            });

            // Realiza o salvamento do registro
            $(document).on('click', "[data-action='salvarProprietarioSimplificado']", async function() {
                if (!$("#modalCadastrarProprietario form")[0].reportValidity()) return false;

                await appFunctions.backendCall('POST', `proprietario/storeSimplificado`, {
                    nome_fantasia: $("#modalCadastrarProprietario input[name='nome_fantasia']")
                        .val(),
                    cpf_cnpj: $("#modalCadastrarProprietario input[name='cpf_cnpj']").val(),
                    email: $("#modalCadastrarProprietario input[name='email']").val(),
                    data_nascimento: $(
                            "#modalCadastrarProprietario input[name='data_nascimento']")
                        .val(),
                    telefone: $("#modalCadastrarProprietario input[name='telefone']").val(),
                    celular: $("#modalCadastrarProprietario input[name='celular']").val(),
                    cep: $("#modalCadastrarProprietario input[name='cep']").val(),
                    rua: $("#modalCadastrarProprietario input[name='rua']").val(),
                    numero: $("#modalCadastrarProprietario input[name='numero']").val(),
                    bairro: $("#modalCadastrarProprietario input[name='bairro']").val(),
                    complemento: $("#modalCadastrarProprietario input[name='complemento']")
                        .val(),
                    cidade: $("#modalCadastrarProprietario input[name='cidade']").val(),
                    uf: $("#modalCadastrarProprietario input[name='uf']").val(),
                }).then(res => {
                    if (res && res.proprietario) {
                        $("[data-select='buscarProprietario']").select2('val', res
                            .proprietario);
                        $("#modalCadastrarProprietario").modal('hide');
                    }
                }).catch(err => notificationFunctions.toastSmall(err.textStatus, err.mensagem));
            });

        },
        listenerModalHelp: () => {
            $(document).on('click', "#btnHelp", () => {
                $("#modalHelp").modal('show');
            });
        }


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
                    "data": "valor_venda",
                    "title": "Valor Venda",
                    "className": "text-end",
                    "isreplace": true,
                    "render": (data) => `R$ ${convertFunctions.intToReal(data)}`
                },
                {
                    "data": "valor_aluguel",
                    "title": "Valor Venda",
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
