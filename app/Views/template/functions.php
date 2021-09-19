<script>
    const select2TemplateFunctions = {
        init: () => {
            select2TemplateFunctions.buscarProdutoModal();
        },
        buscarProdutoModal: () => {
            let elementSelect2 = $("[data-select='buscarProdutoModal']");
            let url = `${BASEURL}/estoque/backendCall/selectConsultaProduto`;
            elementSelect2.select2({
                placeholder: "Buscar Produto...",
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
    }

    const templateFunctions = {
        init: () => {
            // templateFunctions.buscarCep();
            templateFunctions.listenerModalConsultaProduto();

            // Registra o Plugin de Upload de Imagens
            $.fn.filepond.registerPlugin(FilePondPluginImagePreview, FilePondPluginFileEncode);
        },
        buscarCep: () => {
            $(document).on('keyup', "[data-mask='cep']", function() {
                const cep = $(this).val();
                if (cep.length >= 9) {
                    appFunctions.buscarCep(cep).then(
                        (retorno) => {
                            if (retorno) {
                                $("input[name='rua']").val(retorno.street);
                                $("input[name='bairro']").val(retorno.neighborhood);
                                $("input[name='cidade']").val(retorno.city);
                                $("input[name='cidade_completa']").val(`${retorno.city}/${retorno.state}`);
                                $("input[name='uf']").val(retorno.state);
                                $("input[name='numero']").val('');
                                $("input[name='numero']").focus();
                            }
                        }
                    )
                }
            });
        },
        listenerModalConsultaProduto: () => {

            // Ao fechar a Modal, limpa os campos e a tabela
            $(document).on('hide.bs.modal', '#modalConsultarProduto', function(e) {
                $('#modalConsultarProduto input').val('');
                $('#modalConsultarProduto img').attr('src', `${BASEURL}/assets/img/sem_imagem.jpg`);
                $('#modalConsultarProduto input').select2('val', '');
                $('#modalConsultarProduto #tableConsultaProduto tbody').html('');
                $('#modalConsultarProduto #tableConsultaProduto').addClass('d-none');
            });

            // Efetua a Busca do Produto
            $(document).on('change', "[data-select='buscarProdutoModal']", async () => {
                let codigoProduto = $("[data-select='buscarProdutoModal']").val();
                $('#modalConsultarProduto #tableConsultaProduto tbody').html('');

                // Busca o BASE64 da Imagem
                let diretorioImg = $("[data-select='buscarProdutoModal']").select2('data').diretorio_imagem;
                if (diretorioImg && diretorioImg !== '') {
                    const img = await appFunctions.getImageBase64(diretorioImg);
                    $("#modalConsultarProduto img").attr('src', img);
                } else {
                    $('#modalConsultarProduto img').attr('src', `${BASEURL}/assets/img/sem_imagem.jpg`);
                }

                // Busca o produto nos estoques disponíveis
                let quantidadeTotalEstoques = 0;
                if (codigoProduto) {
                    await appFunctions.backendCall('POST', `estoque/backendCall/selectEstoqueProduto`, {
                        termo: codigoProduto,
                        page: 1
                    }).then(res => {
                        $('#modalConsultarProduto #tableConsultaProduto tbody').html('');
                        $('#modalConsultarProduto #tableConsultaProduto').removeClass('d-none');
                        res.itens.forEach(
                            (el) => {
                                quantidadeTotalEstoques = (quantidadeTotalEstoques + convertFunctions.onlyNumber(el.estoque_atual))
                                $('#modalConsultarProduto #tableConsultaProduto tbody').append(`
                                    <tr>
                                        <td>${el.nome_estoque}</td>
                                        <td class="text-center">${el.estoque_atual}</td>
                                        <td class="text-end">R$ ${convertFunctions.intToReal(el.valor_venda)}</td>
                                        <td class="text-end">R$ ${convertFunctions.intToReal(el.valor_atacado)}</td>
                                    </tr>
                                `);
                            }
                        );
                        $("#modalConsultarProduto #quantidade").val(quantidadeTotalEstoques);
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
