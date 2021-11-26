<!-- Inicio :: Modal Consultar Produto -->
<div class="modal fade" id="modalConsultarProduto" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Consulta Imóvel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3 col-lg-3 col-sm-12">
                        <img class="w-100" src="<?= base_url('assets/img/sem_imagem.jpg'); ?>" alt="">
                    </div>
                    <div class="col-md-9 col-lg-9 col-sm-12">
                        <div class="row mb-2">
                            <div class="col-md-8 col-lg-8 col-sm-12 mb-2">
                                <label class="mb-2">Buscar Imóvel</label>
                                <!-- <input type="text" class="form-control" data-select="buscarProdutoModal" autofocus /> -->
                            </div>
                            <!-- <div class="col-md-4 col-lg-4 col-sm-12 mt-2 mb-2">
                                <label>Quantidade em Estoque</label>
                                <input type="text" class="form-control" id="quantidade" readonly />
                            </div> -->
                        </div>

                        <!-- <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12 mb-2">
                                <table class="table table-striped table-hover d-none" id="tableConsultaProduto">
                                    <thead>
                                        <tr>
                                            <th>Estoque</th>
                                            <th class="text-center">Quantidade</th>
                                            <th class="text-end">Preço Varejo</th>
                                            <th class="text-end">Preço Atacado</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
<!-- Fim :: Modal Consultar Produto -->
