const uiFunctions = {
    init: () => {
        uiFunctions.listenersMenus();
    },
    listenersMenus: () => {

        // Fecha todos os Drawer ao clicar em qualquer lugar da tela
        document.addEventListener("click", (e) => {
            if ($(".drawer-container").is(e.target)) {
                $("form").find('input, select').val('');
                $(".drawer-container").removeClass("drawer-container-open");
            }
        });

        $(document).on('click', "[data-action='fecharMenuDrawer']", function(e) {
            uiFunctions.abrirFecharMenuDrawer($(this).parents('.drawer-container').attr('id'), false);
        });
    },
    abrirFecharMenuDrawer: (element, status) => {
        if (status) {
            $(`#${element}`).addClass("drawer-container-open"); // Abre o Menu
        } else {
            $(`#${element}`).trigger('click'); // Força o click para que o Drawer feche
        }
    },
    fullscreen: (el) => {
        var isInFullScreen = (document.fullScreenElement && document.fullScreenElement !== null) ||  (document.mozFullScreen || document.webkitIsFullScreen);

        if (isInFullScreen) {
            var el = document;
            var requestMethod = el.cancelFullScreen || el.webkitCancelFullScreen || el.mozCancelFullScreen || el.exitFullscreen || el.webkitExitFullscreen;

            if (requestMethod) { // cancela fullscreen.
                requestMethod.call(el);
            } else if (typeof window.ActiveXObject !== "undefined") { // Older IE.
                var wscript = new ActiveXObject("WScript.Shell");
                if (wscript !== null) {
                    wscript.SendKeys("{F11}");
                }
            }
        } else {
            // Supports most browsers and their versions.
            var requestMethod = el.requestFullScreen || el.webkitRequestFullScreen || el.mozRequestFullScreen || el.msRequestFullscreen;

            if (requestMethod) { // Native fullscreen.
                requestMethod.call(el);
            } else if (typeof window.ActiveXObject !== "undefined") { // Older IE.
                var wscript = new ActiveXObject("WScript.Shell");
                if (wscript !== null) {
                    wscript.SendKeys("{F11}");
                }
            }
        }

        return !isInFullScreen;
    }
}
