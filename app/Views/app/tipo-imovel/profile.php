<div class="app-wrapper">
    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">
            <h1 class="app-page-title">Minha conta</h1>
            <form method="POST" action="<?= base_url('usuario/updateProfile'); ?>" enctype="multipart/form-data">
                <div class="row gy-4">

                    <!-- Inicio :: Card Perfil -->
                    <div class="col-12 col-lg-6">
                        <div class="app-card app-card-account shadow-sm d-flex flex-column align-items-start">
                            <div class="app-card-header p-3 border-bottom-0">
                                <div class="row align-items-center gx-3">
                                    <div class="col-auto">
                                        <div class="app-icon-holder-avatar">
                                            <img src="<?= $nativeSession->get('usuario')['avatar_base64'] ?>" alt="Avatar do usuário">
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <h4 class="app-card-title">Perfil</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="app-card-body px-4 w-100">
                                <div class="item border-bottom py-2">
                                    <div class="row justify-content-between align-items-center">
                                        <div class="col">
                                            <label class="form-label mb-2">Avatar</label>
                                            <div class="item-data">
                                                <input type="hidden" name="avatar">
                                                <input type="hidden" name="avatar_nome">
                                                <input type="file" id="avatar" data-max-file-size="5MB" data-max-files="1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="item py-2">
                                    <div class="row justify-content-between align-items-center">
                                        <div class="col">
                                            <label class="form-label">Nome</label>
                                            <div class="item-data">
                                                <input type="text" class="form-control" name="nome" required value="<?= old('nome', $usuario['nome']); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="item py-2">
                                    <div class="row justify-content-between align-items-center">
                                        <div class="col">
                                            <label class="form-label">Email</label>
                                            <div class="item-data">
                                                <input type="email" class="form-control" name="email" required value="<?= old('email', $usuario['email']); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="item py-2 mb-2">
                                    <div class="row justify-content-between align-items-center">
                                        <div class="col">
                                            <label class="form-label">Celular</label>
                                            <div class="item-data">
                                                <input type="text" class="form-control" name="celular" data-mask="telefoneCelular" value="<?= old('celular', $usuario['celular']); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Fim :: Card Perfil -->

                    <!-- Inicio :: Card Segurança -->
                    <div class="col-12 col-lg-6">
                        <div class="app-card app-card-account shadow-sm d-flex flex-column align-items-start">
                            <div class="app-card-header p-3 border-bottom-0">
                                <div class="row align-items-center gx-3">
                                    <div class="col-auto">
                                        <div class="app-icon-holder">
                                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-shield-check" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M5.443 1.991a60.17 60.17 0 0 0-2.725.802.454.454 0 0 0-.315.366C1.87 7.056 3.1 9.9 4.567 11.773c.736.94 1.533 1.636 2.197 2.093.333.228.626.394.857.5.116.053.21.089.282.11A.73.73 0 0 0 8 14.5c.007-.001.038-.005.097-.023.072-.022.166-.058.282-.111.23-.106.525-.272.857-.5a10.197 10.197 0 0 0 2.197-2.093C12.9 9.9 14.13 7.056 13.597 3.159a.454.454 0 0 0-.315-.366c-.626-.2-1.682-.526-2.725-.802C9.491 1.71 8.51 1.5 8 1.5c-.51 0-1.49.21-2.557.491zm-.256-.966C6.23.749 7.337.5 8 .5c.662 0 1.77.249 2.813.525a61.09 61.09 0 0 1 2.772.815c.528.168.926.623 1.003 1.184.573 4.197-.756 7.307-2.367 9.365a11.191 11.191 0 0 1-2.418 2.3 6.942 6.942 0 0 1-1.007.586c-.27.124-.558.225-.796.225s-.526-.101-.796-.225a6.908 6.908 0 0 1-1.007-.586 11.192 11.192 0 0 1-2.417-2.3C2.167 10.331.839 7.221 1.412 3.024A1.454 1.454 0 0 1 2.415 1.84a61.11 61.11 0 0 1 2.772-.815z" />
                                                <path fill-rule="evenodd" d="M10.854 6.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 8.793l2.646-2.647a.5.5 0 0 1 .708 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <h4 class="app-card-title">Segurança</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="app-card-body px-4 w-100">
                                <div class="item py-2">
                                    <div class="row justify-content-between align-items-center">
                                        <div class="col">
                                            <label class="form-label">Senha Anterior</label>
                                            <div class="item-data">
                                                <input type="password" class="form-control" name="senha_anterior" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="item py-2">
                                    <div class="row justify-content-between align-items-center">
                                        <div class="col">
                                            <label class="form-label">Senha</label>
                                            <div class="item-data">
                                                <input type="password" class="form-control" name="senha" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="item py-2">
                                    <div class="row justify-content-between align-items-center">
                                        <div class="col">
                                            <label class="form-label">Confirmar Senha</label>
                                            <div class="item-data">
                                                <input type="password" class="form-control" name="confirmar_senha" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="item border-bottom py-2">
                                <div class="row justify-content-between align-items-center">
                                    <div class="col-auto">
                                        <div class="item-label fw-bold">Autenticação de dois fatores</div>
                                        <div class="item-data">Você não configurou a autenticação de dois fatores.</div>
                                    </div>
                                    <div class="col text-end">
                                        <a class="btn-sm app-btn-secondary" href="#">Configurar</a>
                                    </div>
                                </div>
                            </div> -->
                            </div>
                        </div>
                    </div>
                    <!-- Fim :: Card Segurança -->

                    <!-- Inicio :: Card Preferencias -->
                    <!-- <div class="col-12 col-lg-6">
                        <div class="app-card app-card-account shadow-sm d-flex flex-column align-items-start">
                            <div class="app-card-header p-3 border-bottom-0">
                                <div class="row align-items-center gx-3">
                                    <div class="col-auto">
                                        <div class="app-icon-holder">
                                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-sliders" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M11.5 2a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM9.05 3a2.5 2.5 0 0 1 4.9 0H16v1h-2.05a2.5 2.5 0 0 1-4.9 0H0V3h9.05zM4.5 7a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM2.05 8a2.5 2.5 0 0 1 4.9 0H16v1H6.95a2.5 2.5 0 0 1-4.9 0H0V8h2.05zm9.45 4a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zm-2.45 1a2.5 2.5 0 0 1 4.9 0H16v1h-2.05a2.5 2.5 0 0 1-4.9 0H0v-1h9.05z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <h4 class="app-card-title">Preferências</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="app-card-body px-4 w-100">
                                <div class="item border-bottom py-2">
                                    <div class="row justify-content-between align-items-center">
                                        <div class="col-auto">
                                            <div class="item-label fw-bold">Language </div>
                                            <div class="item-data">English</div>
                                        </div>
                                        <div class="col text-end">
                                            <a class="btn-sm app-btn-secondary" href="#">Alterar</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="item border-bottom py-2">
                                    <div class="row justify-content-between align-items-center">
                                        <div class="col-auto">
                                            <div class="item-label fw-bold">Time Zone</div>
                                            <div class="item-data">Central Standard Time (UTC-6)</div>
                                        </div>

                                        <div class="col text-end">
                                            <a class="btn-sm app-btn-secondary" href="#">Alterar</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="item border-bottom py-2">
                                    <div class="row justify-content-between align-items-center">
                                        <div class="col-auto">
                                            <div class="item-label fw-bold">Currency</div>
                                            <div class="item-data">$(US Dollars)</div>
                                        </div>
                                        <div class="col text-end">
                                            <a class="btn-sm app-btn-secondary" href="#">Alterar</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="item border-bottom py-2">
                                    <div class="row justify-content-between align-items-center">
                                        <div class="col-auto">
                                            <div class="item-label fw-bold">Email Subscription</div>
                                            <div class="item-data">Off</div>
                                        </div>
                                        <div class="col text-end">
                                            <a class="btn-sm app-btn-secondary" href="#">Alterar</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="item border-bottom py-2">
                                    <div class="row justify-content-between align-items-center">
                                        <div class="col-auto">
                                            <div class="item-label fw-bold">SMS Notifications</div>
                                            <div class="item-data">On</div>
                                        </div>
                                        <div class="col text-end">
                                            <a class="btn-sm app-btn-secondary" href="#">Alterar</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <!-- Fim :: Card Preferencias -->

                    <!-- Inicio :: Card Pagamento -->
                    <!-- <div class="col-12 col-lg-6">
                        <div class="app-card app-card-account shadow-sm d-flex flex-column align-items-start">
                            <div class="app-card-header p-3 border-bottom-0">
                                <div class="row align-items-center gx-3">
                                    <div class="col-auto">
                                        <div class="app-icon-holder">
                                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-credit-card" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v1h14V4a1 1 0 0 0-1-1H2zm13 4H1v5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V7z" />
                                                <path d="M2 10a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-1z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <h4 class="app-card-title">Payment methods</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="app-card-body px-4 w-100">
                                <div class="item border-bottom py-2">
                                    <div class="row justify-content-between align-items-center">
                                        <div class="col-auto">
                                            <div class="item-label fw-bold"><i class="fab fa-cc-visa me-2"></i>Credit/Debit Card </div>
                                            <div class="item-data">1234*******5678</div>
                                        </div>
                                        <div class="col text-end">
                                            <a class="btn-sm app-btn-secondary" href="#">Edit</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="item border-bottom py-2">
                                    <div class="row justify-content-between align-items-center">
                                        <div class="col-auto">
                                            <div class="item-label fw-bold"><i class="fab fa-paypal me-2"></i>PayPal</div>
                                            <div class="item-data">Not connected</div>
                                        </div>
                                        <div class="col text-end">
                                            <a class="btn-sm app-btn-secondary" href="#">Connect</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <!-- Fim :: Card Pagamento -->

                </div>

                <div class="d-flex justify-content-end mt-2">
                    <button type="submit" class="btn app-btn-primary">Salvar</button>
                </div>

            </form>
        </div>
    </div>
</div>
