<!-- Inicio :: Modal de recuperação de senha -->
<div class="modal fade" id="modalEsqueceuSenha" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Esqueceu a senha?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Digite seu e-mail que nós enviaremos um link para você criar uma nova senha.</p>
                <form id="formEsqueceuSenha">
                    <div class="row">
                        <div class="col-12">
                            <label>E-mail cadastrado</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary text-white" data-action="solicitarRecuperacaoEmail">Solicitar</button>
            </div>
        </div>
    </div>
</div>
<!-- Fim :: Modal de recuperação de senha -->
