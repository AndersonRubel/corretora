/**
 * Retorna a versão principal do Sistema
 * @param {string} versao
 * @returns string
 */
function versaoPrincipal(versao) {
    versao = versao.split(".");
    versao.pop();
    return versao.join(".");
}

/**
 * Função que adiciona recursos ao Core do AJAX,
 *  para validar o login
 */
(function ($) {
    $.fn.versaoSistema = 0;
    $.fn.ajax = $.ajax;
    $.fn.ajaxRequests = [];

    $.ajax = function (url, options) {
        if (typeof url === "object") {
            options = url;
            url = undefined;
        }

        let oldComplete = undefined;
        if (options && options.complete) oldComplete = options.complete;

        let ajax_instance = $.fn.ajax(
            url,
            $.extend({}, options, {
                complete: function (jqXHR, textStatus) {
                    // Verifica a versão atual
                    if ($.fn.versaoSistema == 0) {
                        $.fn.versaoSistema = jqXHR.getResponseHeader("Versao");
                    }

                    switch (jqXHR.status) {
                        case 0:
                            // alert("Ocorreu uma falha na rede.");
                            console.log("ERRO: 0 - Falha na rede.");
                            break;
                        case 403:
                            alert("Acesso negado.");
                            console.log("ERRO: 403 - Acesso negado.");
                            break;
                        case 404:
                            alert("Recurso nao encontrado.");
                            console.log("ERRO: 404 - Pagina nao encontrada.");
                            break;

                        case 500:
                            alert("Erro interno no servidor.");
                            console.log("ERRO: 500 - Erro interno no servidor.");
                            break;

                        case 200:
                            if (jqXHR.getResponseHeader("Sessao-Expirando") == "true") {
                                if (jqXHR.getResponseHeader("Sessao-Expirando-Tempo") == "5") {
                                    if (!localStorage.getItem("Sessao-Expirando-Tempo-5")) {
                                        alert("Atenção: Sua sessão de utilização do sistema irá ser finalizada em 5 minutos devido ao horário limite. Salve todo o trabalho!");
                                        localStorage.setItem("Sessao-Expirando-Tempo-5", "5");
                                    }
                                } else if (jqXHR.getResponseHeader("Sessao-Expirando-Tempo") == "2") {
                                    if (!localStorage.getItem("Sessao-Expirando-Tempo-2")) {
                                        alert("Atenção: Sua sessão de utilização do sistema irá ser finalizada em 2 minutos devido ao horário limite. Salve todo o trabalho!");
                                        localStorage.setItem("Sessao-Expirando-Tempo-2", "2");
                                    }
                                } else if (jqXHR.getResponseHeader("Sessao-Expirando-Tempo") == "0") {
                                    if (!localStorage.getItem("Sessao-Expirando-Tempo-0")) {
                                        alert("Atenção: Sua sessão foi finalizada por estar fora do horário permitido.");
                                        localStorage.setItem("Sessao-Expirando-Tempo-0", "0");
                                    }

                                    localStorage.removeItem("Sessao-Expirando-Tempo-5");
                                    localStorage.removeItem("Sessao-Expirando-Tempo-2");
                                    localStorage.removeItem("Sessao-Expirando-Tempo-0");
                                    window.location.reload();
                                }
                            }
                            break;
                    }

                    if (oldComplete) oldComplete(jqXHR, textStatus);

                    let index = $.inArray(ajax_instance, $.fn.ajaxRequests);
                    if (index > -1) $.fn.ajaxRequests.splice(index, 1);
                },
            })
        );

        if ((options == undefined || options.type == undefined || options.type.toUpperCase() == "GET") && (options == undefined || options.async != false))
            $.fn.ajaxRequests.push(ajax_instance);

        return ajax_instance;
    };

    /**
     * Ao sair da tela cancela todas as requisições AJAX
     */
    $(window).bind("beforeunload", function () {
        $.each($.fn.ajaxRequests, function (i, ajax) {
            if (ajax) ajax.abort();
        });
    });

})(window.jQuery);
