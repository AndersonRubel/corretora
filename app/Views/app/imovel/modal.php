<!-- Inicio :: Modal Alterar Preço -->
<div class="modal fade" id="modalimovelAlterarPreco" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Alterar Preço</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12 mb-2">
                            <label>imovel</label>
                            <input type="text" class="form-control" name="nome" readonly />
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                            <label class="form-label">Preço de Custo</label>
                            <input type="text" class="form-control" name="valor_fabrica" data-mask="dinheiro"
                                data-tippy-content="Informe o Preço de Custo do imovel" required placeholder="0,00">
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                            <label class="form-label">Preço de Venda</label>
                            <input type="text" class="form-control" name="valor_venda" data-mask="dinheiro"
                                data-tippy-content="Informe o Preço de Venda do imovel" required placeholder="0,00">
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                            <label class="form-label">Preço de Ecommerce</label>
                            <input type="text" class="form-control" name="valor_ecommerce" data-mask="dinheiro"
                                data-tippy-content="Informe o Preço de Custo Ecommerce do imovel" placeholder="0,00">
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                            <label class="form-label">Preço de Atacado</label>
                            <input type="text" class="form-control" name="valor_atacado" data-mask="dinheiro"
                                placeholder="0,00" data-tippy-content="Informe o Preço de Atacado do imovel">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success text-white"
                        data-action="realizarAlteracaoPreco">Salvar</button>
                </div>

            </form>
        </div>
    </div>
</div>
<!-- Fim :: Modal Alterar Preço -->