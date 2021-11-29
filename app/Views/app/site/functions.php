<script>
    const siteFunctions = {
        init: () => {
            siteFunctions.listenerOrderValor();
            siteFunctions.listenerQtdQuarto();
            siteFunctions.listenerPopulaFiltros();
            siteFunctions.listenerCondominio();
        },
        listenerOrderValor: () => {
            $(document).on('change', '#selectFormPreco', async function() {
                $("#formPreco")[0].action = window.location.href;
                $("#formPreco").submit();

            });
        },
        listenerQtdQuarto: () => {
            $(document).on('change', '#selectFormQuarto', async function() {
                $("#formQuarto")[0].action = window.location.href;
                $("#formQuarto").submit();
            });
        },
        listenerCondominio: () => {
            $(document).on('change', '#selectFormCondominio', async function() {
                $("#formCondominio")[0].action = window.location.href;
                $("#formCondominio").submit();
            });
        },
        listenerPopulaFiltros: () => {

            const queryString = window.location.search;
            const urlParams = new URLSearchParams(queryString);
            const categoria_imovel = urlParams.get('codigo_categoria_imovel');
            const quarto = urlParams.get('quarto');
            const ordenarValor = urlParams.get('ordenar_valor');
            const condominio = urlParams.get('condominio');
            console.log(queryString);
            //popula os input de quarto
            $("#quarto_valor").val(quarto);
            $("#quarto_condominio").val(quarto);
            //popula os inputs de valor
            $("#ordenar_valor_quarto").val(ordenarValor);
            $("#ordenar_valor_condominio").val(ordenarValor);
            //popula os inputs de condominio
            $("#condominio_quarto").val(condominio);
            $("#condominio_valor").val(condominio);
            //popula os selects com os filtros selecionados
            $("#selectFormQuarto").val(quarto);
            $("#selectFormPreco").val(ordenarValor);
            $("#selectFormCondominio").val(condominio);
            //popula os input de cada form
            $("#codigo_categoria_imovel_quarto").val(categoria_imovel);
            $("#codigo_categoria_imovel_valor").val(categoria_imovel);
            $("#codigo_categoria_imovel_condominio").val(categoria_imovel);

            if (categoria_imovel == 1) {
                $("#aluguel").addClass("active");
            } else if (categoria_imovel == 2) {
                $("#venda").addClass("active");
            } else {
                $("#todos").addClass("active");
            }
        }
    };
    document.addEventListener("DOMContentLoaded", () => {
        siteFunctions.init();
    });
</script>
