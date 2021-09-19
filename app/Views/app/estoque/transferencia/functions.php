<script>
    const select2EstoqueTransferenciaFunctions = {
        init: () => {
            select2EstoqueTransferenciaFunctions.buscarEmpresa();
            select2EstoqueTransferenciaFunctions.buscarEstoque();
            select2EstoqueTransferenciaFunctions.buscarProduto();
        },
        buscarEmpresa: () => {
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
                            page: 1,
                            codEstoque: $("[data-select='buscarEstoque']").val()
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
                            codEstoque: $("[data-select='buscarEstoque']").val()
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
                formatResult: (data) => {
                    return data.text + " (" + data.codigo_barras + ") - Quantidade: " + data.estoque_atual;
                },
                formatSelection: (data) => data.text
            });
        }
    };

    const estoqueTransferenciaFunctions = {
        init: () => {
            estoqueTransferenciaFunctions.listenerEstoquePara();
            estoqueTransferenciaFunctions.listenerAdicionaProduto();
        },
        listenerEstoquePara: () => {
            $(document).on('change', "input[name='transferencia_para_codigo_estoque']", function() {
                // Limpa a tabela
                $("#tableTransferenciaProdutos tbody").html('');

                if ($(this).val() == '') {
                    $("#cardBuscarProduto").addClass('d-none');
                } else {
                    $("#cardBuscarProduto").removeClass('d-none');
                    $("[data-select='buscarProduto']").select2('open');
                }
            });
        },
        listenerAdicionaProduto: () => {
            // Ao apertar o ENTER no campo de quantidade, voltar pro campo de buscar produto
            $(document).on('keydown', '.transferencia-produto-quantidade', function(e) {
                var keyCode = e.keyCode || e.which;

                if (keyCode == 9 || keyCode == 13) {
                    e.preventDefault();
                    $("[data-select='buscarProduto']").select2('open');
                }
            });

            // A cada mudança do campo de Quantidade, recalcula a contagem de itens
            $(document).on('keyup', '.transferencia-produto-quantidade', estoqueTransferenciaFunctions.calculaTotalItensTransferencia);

            // Remove um produto da lista
            $(document).on('click', "[data-action='removerDaLista']", function() {
                $(this).parents('tr').remove();

                // Se não tiver mais nenhum, oculta a tabela
                if ($("[data-action='removerDaLista']").length == 0) {
                    $("#tableTransferenciaProdutos").addClass('d-none');
                }
                estoqueTransferenciaFunctions.calculaTotalItensTransferencia();
            });

            // Adiciona o Produto na listagem
            $(document).on('change', "[data-select='buscarProduto']", function() {
                let produto = $(this).val();
                $("#tableTransferenciaProdutos").removeClass('d-none');


                if ($("input[name='transferencia_produto_codigo[]']").filter(function() {
                        return $(this).val() == $("[data-select='buscarProduto']").select2('val') ? true : false;
                    }).length > 0) {

                    // Se encontrar produto que ja ta na lista, incrementa sua quantidade
                    $("input[name='transferencia_produto_codigo[]']").each(function(i, el) {
                        if ($(el).val() == $("[data-select='buscarProduto']").select2('val')) {
                            qtdeAtual = $(el).parent().parent().find("input[name='transferencia_produto_quantidade[]']").val();
                            qtdeAtual = parseInt(qtdeAtual) + 1;
                            $(el).parent().parent().find("input[name='transferencia_produto_quantidade[]']").val(qtdeAtual);
                        }
                    });

                    // notificationFunctions.toastSmall('warning', 'Este produto já foi adicionado na lista!');
                    $("[data-select='buscarProduto']").select2('open');
                } else {
                    if ($("[data-select='buscarProduto']").select2('data').estoque_atual > 0) {

                        $("#tableTransferenciaProdutos tbody").prepend(`
                            <tr style="vertical-align: middle;">
                                <td>
                                    <input type='hidden' name='transferencia_produto_codigo[]' value="${$("[data-select='buscarProduto']").select2('val')}">
                                    ${$("[data-select='buscarProduto']").select2('data').codigo_barras}
                                </td>
                                <td>${$("[data-select='buscarProduto']").select2('data').text}</td>
                                <td>${$("[data-select='buscarProduto']").select2('data').estoque_atual}</td>
                                <td><input type='text' class="transferencia-produto-quantidade form-control" name="transferencia_produto_quantidade[]" data-verificanumero="true" value="1"></td>
                                <td><button type="button" class="btn btn-secondary" data-action="removerDaLista" data-tippy-content="Remover produto da lista"><i class="fas fa-trash"></i></button></td>
                            </tr>
                        `);
                        // $('.transferencia-produto-quantidade:first').focus();
                        $("[data-select='buscarProduto']").select2('open');
                    } else {
                        notificationFunctions.toastSmall('error', 'Não é possível adicionar um produto sem estoque.');
                    }
                }

                appFunctions.tooltip();

                // Limpa o campo
                $("[data-select='buscarProduto']").select2('val', '');

                estoqueTransferenciaFunctions.calculaTotalItensTransferencia();
            });
        },
        calculaTotalItensTransferencia: () => {
            // Calcula quantos itens estão sendo transferidos
            let valorTotal = 0;

            $('.transferencia-produto-quantidade').each(function() {
                if ($(this).val() != '') {
                    valorTotal += parseFloat($(this).val());
                }
            });

            $('#totalItensTransferencia').text(`Total de itens a transferir: ${valorTotal}`);
        }
    };

    document.addEventListener("DOMContentLoaded", () => {
        select2EstoqueTransferenciaFunctions.init();
        estoqueTransferenciaFunctions.init();
    });
</script>
