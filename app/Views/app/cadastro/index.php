<div class="app-wrapper">
    <div class="app-content pt-3 p-md-3 p-lg-4">
        <?php if ($function == 'lista') : ?>
            <div class="row g-3 mb-4 align-items-center justify-content-between">
                <div class="col-auto">
                    <h1 class="app-page-title mb-0"><?= $nomeCrud; ?></h1>
                </div>
                <div class="col-auto">
                    <div class="page-utilities">
                        <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                            <div class="col-auto">
                                <?php if (!empty($btnAdicionarLabel)) : ?>
                                    <a class="btn btn-primary success text-white" href="<?= base_url("cadastro/{$return}/adicionar"); ?>"><?= $btnAdicionarLabel; ?></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row gy-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header fw-bold">Listagem</div>
                        <div class="card-body">
                            <?= $html; ?>
                        </div>
                    </div>
                </div>
            </div>

        <?php else : ?>
            <h1 class="app-page-title"><?= $nomeCrud; ?></h1>
            <div class="row gy-4">
                <div class="col-12">
                    <?= $formOpen; ?>
                    <div class="card">
                        <div class="card-header fw-bold">Cadastro</div>
                        <div class="card-body">
                            <?= $html; ?>
                        </div>
                        <div class="card-footer d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary success text-white text-right" data-action="btnSalvar">Salvar</button>
                        </div>
                    </div>
                    <?= $formClose; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
