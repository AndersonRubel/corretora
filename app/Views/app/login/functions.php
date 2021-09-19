<script>
    const loginFunctions = {
        init: () => {
            loginFunctions.solicitarRecuperacaoEmail();
            loginFunctions.listenerVisibilidadeSenha();
        },
        solicitarRecuperacaoEmail: () => {
            // Desabilita o ENTER no formulario
            $('input').keypress(function(e) {
                let code = null;
                code = (e.keyCode ? e.keyCode : e.which);
                return (code == 13) ? false : true;
            });

            $(document).on('click', "[data-action='solicitarRecuperacaoEmail']", function(e) {
                if (!$("#formEsqueceuSenha")[0].reportValidity()) return false;

                appFunctions.backendCall('POST', `/habilita-recuperacao-senha`, {
                    email: $("#formEsqueceuSenha input").val()
                }).then(
                    (res) => {
                        if (res) {
                            $("#formEsqueceuSenha input").val('');
                            $("#modalEsqueceuSenha").modal('hide');
                            notificationFunctions.toastSmall(res.textStatus, res.mensagem);
                        }
                    }
                ).catch(err => notificationFunctions.toastSmall(err.textStatus, err.mensagem));

            });
        },
        listenerVisibilidadeSenha: () => {
            $(document).on('mousedown', "[data-action='visualizarSenha']", function(e) {
                $("#senha").attr("type", "text");
            });

            $(document).on('mouseup, mouseout', "[data-action='visualizarSenha']", function(e) {
                $("#senha").attr("type", "password");
            });
        }
    }

    // solicitarRecuperacaoEmail
    document.addEventListener("DOMContentLoaded", () => {
        loginFunctions.init();
    });
</script>
