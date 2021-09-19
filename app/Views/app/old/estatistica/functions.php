<script>
    const select2EstatisticaFunctions = {
        init: () => {
            select2EstatisticaFunctions.buscarEmpresa();
        },
        buscarEmpresa: () => {
            let elementSelect2 = $("[data-select='buscarEmpresa']");
            let url = `${BASEURL}/empresa/backendCall/selectEmpresa`;
            elementSelect2.select2({
                placeholder: "Todas as Empresas",
                allowClear: true,
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

    const estatisticaFunctions = {
        init: () => {
            estatisticaFunctions.listenerBtnPeriodo();
            $("[data-action='selecionarPeriodo']:first").click();

            // $("table tbody.sem-dados").addClass('d-none');
        },
        listenerBtnPeriodo: () => {
            $(document).on('click', "[data-action='selecionarPeriodo']", function() {

                $("[data-action='selecionarPeriodo']").each((i, el) => {
                    $(el).removeClass('btn-success').addClass('btn-secondary');
                });

                $(this).addClass('btn-success').css({
                    color: 'white'
                });
            });
        }
    };

    document.addEventListener("DOMContentLoaded", () => {
        estatisticaFunctions.init();
        select2EstatisticaFunctions.init();
    });
</script>
