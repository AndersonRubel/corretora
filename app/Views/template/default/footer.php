<?php if (!in_array($base->getControllerName(), ['LoginController', 'PdvController'])) : ?>
    <footer class="app-wrapper">
        <div class="container text-center py-3">
            <small class="copyright">
                Desenvolvido por
                <a class="app-link" href="https://iluminareweb.com.br" target="_blank">Iluminare Web</a>.
                Todos os direitos reservados. - Versão <?= $base->version; ?>
            </small>
        </div>
    </footer>
<?php endif; ?>

<script type="text/javascript" src="<?= base_url("assets/js/popper.min.js"); ?>?versao=<?= $base->version; ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/js/bootstrap.min.js"); ?>?versao=<?= $base->version; ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/js/portal.js"); ?>?versao=<?= $base->version; ?>"></script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        appFunctions.hideLoader();
        appFunctions.init();
        maskFunctions.init();
        notificationFunctions.init();
        uiFunctions.init();
        validFunctions.init();
    });
</script>

</body>

</html>
