<div class="row g-0 app-auth-wrapper">
    <div class="col-12 col-md-7 col-lg-6 auth-main-col text-center p-5">
        <div class="d-flex flex-column align-content-end">
            <div class="app-auth-body mx-auto">
                <div class="app-auth-branding mb-4">
                    <a class="app-logo" href="<?= base_url(); ?>">
                        <img class="logo-icon me-2" src="<?= base_url("assets/img/app-logo.svg"); ?>" alt="logo">
                    </a>
                </div>
                <h2 class="auth-heading text-center mb-5">Corretora</h2>
                <div class="auth-intro mb-4 text-center">Digite sua nova senha abaixo.</div>
                <div class="auth-form-container text-start">
                    <form class="auth-form login-form" method="POST" action="<?= base_url("update-password"); ?>">
                        <input type="hidden" name="token" value="<?= $token; ?>">

                        <div class="email mb-3">
                            <label class="sr-only" for="senha">Senha</label>
                            <input type="password" id="senha" name="senha" class="form-control" placeholder="Nova senha" required autofocus>
                        </div>

                        <div class="email mb-3">
                            <label class="sr-only" for="confirmar_senha">Confirmar Senha</label>
                            <input type="password" id="confirmar_senha" name="confirmar_senha" class="form-control" placeholder="Confirme a nova senha" required autofocus>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn app-btn-primary w-100 theme-btn mx-auto">Alterar Senha</button>
                        </div>
                    </form>
                </div>
            </div>

            <footer class="app-auth-footer">
                <div class="container text-center py-3">
                    <small class="copyright">
                        Desenvolvido por
                        <a class="app-link" href="/" target="_blank">Anderson Rubel</a>.
                        Todos os direitos reservados. - Versão <?= $base->version; ?>
                    </small>
                </div>
            </footer>

        </div>
    </div>

    <div class="col-12 col-md-5 col-lg-6 h-100 auth-background-col">
        <div class="auth-background-holder">
        </div>
        <div class="auth-background-mask"></div>
    </div>

</div>
