<script>
    const siteFunctions = {
        init: () => {
            siteFunctions.listenerOrderValor();
        },
        listenerOrderValor: () => {

            // Ao fechar a Modal limpa os campos
            $(document).on('change', '#selectFormPreco', async function() {

                const queryString = window.location.search;
                const urlParams = new URLSearchParams(queryString);
                const categoria_imovel = urlParams.get('codigo_categoria_imovel');
                $("#codigo_categoria_imovel").val(categoria_imovel);
                $("#formPreco")[0].action = window.location.href;
                console.log(categoria_imovel);
                $("#formPreco").submit();

            });
        },

    };
    document.addEventListener("DOMContentLoaded", () => {
        siteFunctions.init();
    });
</script>
