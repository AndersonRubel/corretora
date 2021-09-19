<div class="row g-0 app-auth-wrapper">
    <div class="col-12 col-md-7 col-lg-6 auth-main-col text-center p-5">
        <div class="d-flex flex-column align-content-end">
            <div class="app-auth-body mx-auto">
                <div class="app-auth-branding mb-4">
                    <a class="app-logo" href="<?= base_url(); ?>">
                        <img class="logo-icon me-2" src="assets/img/app-logo.svg" alt="logo">
                    </a>
                </div>
                <h2 class="auth-heading text-center mb-5">Porta Jóias</h2>
                <div class="auth-form-container text-start">
                    <form class="auth-form login-form" method="POST" action="<?= base_url("login/email"); ?>">

                        <div class="email mb-3">
                            <label class="sr-only" for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="Seu Email" required autofocus>
                        </div>

                        <div class="password mb-3">
                            <label class="sr-only" for="senha">Senha</label>
                            <div class="input-group flex-nowrap">
                                <input type="password" id="senha" name="senha" class="form-control" placeholder="Sua Senha" required>
                                <span class="input-group-text cursor" data-action="visualizarSenha"><i class="fas fa-eye"></i></span>
                            </div>
                            <div class="extra mt-3 row justify-content-between">
                                <div class="col-6"></div>
                                <div class="col-6">
                                    <div class="forgot-password text-end">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalEsqueceuSenha">Esqueceu a senha?</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn app-btn-primary w-100 theme-btn mx-auto">Entrar</button>
                        </div>
                    </form>
                </div>
            </div>

            <footer class="app-auth-footer">
                <div class="container text-center py-3">
                    <small class="copyright">
                        Desenvolvido por
                        <a class="app-link" href="https://iluminareweb.com.br" target="_blank">Iluminare Web</a>.
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
        <!-- <div class="auth-background-overlay p-3 p-lg-5">
            <div class="d-flex flex-column align-content-end h-100">
                <div class="h-100"></div>
                <div class="overlay-content p-3 p-lg-4 rounded">
                    <h5 class="mb-3 overlay-title">Explore Portal Admin Template</h5>
                    <div>Portal is a free Bootstrap 5 admin dashboard template. You can download and view the template license <a href="https://themes.3rdwavemedia.com/bootstrap-templates/admin-dashboard/portal-free-bootstrap-admin-dashboard-template-for-developers/">here</a>.</div>
                </div>
            </div>
        </div> -->
    </div>

</div>
