<script>
    const select2TemplateFunctions = {
        init: () => {
            select2TemplateFunctions.buscarImovel();
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
    }

    const templateFunctions = {
        init: () => {
            // templateFunctions.buscarCep();
            templateFunctions.listenerModalConsultaImovel();

            // Registra o Plugin de Upload de Imagens
            $.fn.filepond.registerPlugin(FilePondPluginImagePreview, FilePondPluginFileEncode);
        },
        listenerModalConsultaImovel: () => {

            // Ao fechar a Modal, limpa os campos e a tabela
            $(document).on('hide.bs.modal', '#modalConsultarImovel', function(e) {
                $('#modalConsultarImovel input').val('');
                $('#modalConsultarImovel img').attr('src', `${BASEURL}/assets/img/sem_imagem.jpg`);
                $('#modalConsultarImovel input').select2('val', '');
                $('#modalConsultarImovel #tableConsultaImovel tbody').html('');
                $('#modalConsultarImovel #tableConsultaImovel').addClass('d-none');
            });

            // Efetua a Busca do Imovel
            $(document).on('change', "[data-select='buscarImovel']", async () => {
                let codigoImovel = $("[data-select='buscarImovel']").val();
                $('#modalConsultarImovel #tableConsultaImovel tbody').html('');

                setTimeout(async () => {
                    // Busca o BASE64 da Imagem
                    let diretorioImg = $("[data-select='buscarImovel']").select2('data').diretorio_imagem;
                    if (diretorioImg && diretorioImg !== '') {
                        const img = await appFunctions.getImageBase64(diretorioImg);
                        $("#modalConsultarImovel img").attr('src', img);
                    } else {
                        $('#modalConsultarImovel img').attr('src', `${BASEURL}/assets/img/sem_imagem.jpg`);
                    }

                }, 1000)
                // Busca o imovel nos estoques disponíveis
                let quantidadeTotalEstoques = 0;
                if (codigoImovel) {
                    await appFunctions.backendCall('POST', `reserva/backendCall/selectReservaImovel`, {
                        termo: codigoImovel,
                        page: 1
                    }).then(res => {
                        $('#modalConsultarImovel #tableConsultaImovel tbody').html('');
                        $('#modalConsultarImovel #tableConsultaImovel').removeClass('d-none');
                        res.itens.forEach(
                            (el) => {
                                $('#modalConsultarImovel #tableConsultaImovel tbody').append(`
                                    <tr>
                                        <td>${el.nome}</td>
                                        <td>${el.data_inicio}</td>
                                        <td>${el.data_fim}</td>
                                    </tr>
                                `);
                            }
                        );
                    }).catch(err => notificationFunctions.toastSmall(err.textStatus, err.mensagem));
                }
            });

            // Habilita o Select2 de Busca

        }
    }

    document.addEventListener("DOMContentLoaded", () => {
        select2TemplateFunctions.init();
        templateFunctions.init();
    });
</script>
