<div class="app-wrapper">
    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">

            <!-- Inicio :: Titulo e Botões -->
            <div class="row g-3 align-items-center justify-content-between">

                <div class="col-auto">
                    <h1 class="app-page-title mb-0">Alterar imovel</h1>
                </div>
                <div class="col-auto">
                    <div class="page-utilities">
                        <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                            <div class="col-auto">
                                <a class="btn btn-secondary" href="<?= base_url("imovel"); ?>">Voltar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="mb-4">
            <!-- Fim :: Titulo e Botões -->

            <!-- Inicio :: Formulário -->
            <form method="POST" action="<?= base_url("imovel/update/{$imovel['uuid_imovel']}"); ?>">

                <!-- Inicio :: Cadastro Básico -->
                <div class="card">
                    <div class="card-header fw-bold">Dados Básicos</div>
                    <div class="app-card shadow-sm p-4">
                        <div class="app-card-body">
                            <div class="row">
                                <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                    <label class="form-label">Imagem Destaque</label>
                                    <div class="row justify-content-between align-items-center d-none" id="containerPluginImagem">
                                        <div class="col">
                                            <div class="item-data">
                                                <input type="hidden" name="imagem">
                                                <input type="hidden" name="imagem_nome">
                                                <input type="file" id="imagemimovel" data-max-file-size="5MB" data-max-files="1">
                                            </div>
                                        </div>
                                    </div>
                                    <img src="<?= $imovel['imagem_destaque'] ?>" class="w-100" id="imagemEdicao" alt="Imagem de Destaque" height="290" style="object-fit:contain;">
                                    <div class="d-flex justify-content-center">
                                        <button type="button" class="btn-sm app-btn-secondary mt-3" data-action="alterarImagemDestaque">Alterar Imagem</button>
                                        <button type="button" class="btn-sm app-btn-secondary mt-3 d-none" data-action="cancelarAlterarImagemDestaque">Cancelar</button>
                                    </div>
                                </div>
                                <div class="col-md-9 col-lg-9 col-sm-12 mb-2">
                                    <div class="row">
                                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                            <label class="form-label">Código Interno/Barras</label>
                                            <div class="input-group flex-nowrap">
                                                <input type="text" class="form-control" name="codigo_barras" data-verificanumero="true" data-tippy-content="Informe o Código Interno/Barras ou Gere" required value="<?= old('codigo_barras', $imovel['codigo_barras']); ?>">
                                                <button class="btn btn-secondary" type="button" id="dropdownMenuCodigo" data-bs-toggle="dropdown" aria-expanded="false" data-tippy-content="Gere um código automaticamente">
                                                    <i class="fas fa-sync"></i>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuCodigo">
                                                    <li class="dropdown-item" data-action="gerarCodigo" data-tipo="EAN8"><small class="cursor">Gerar Código EAN9</small></li>
                                                    <li class="dropdown-item" data-action="gerarCodigo" data-tipo="aleatorio"><small class="cursor">Gerar Código aleatório</small></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-12 mb-2">
                                            <label class="form-label">Referência Fornecedor</label>
                                            <input type="text" class="form-control" name="referencia_fornecedor" data-tippy-content="Informe a Referência do Fornecedor" value="<?= old('referencia_fornecedor', $imovel['referencia_fornecedor']); ?>">
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                                            <label class="form-label">Nome</label>
                                            <input type="text" class="form-control" name="nome" data-tippy-content="Informe o Nome do imovel" required value="<?= old('nome', $imovel['nome']); ?>">
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                                            <label class="form-label">Categorias</label>
                                            <input type="text" class="form-control" name="categorias" data-select="buscarCategoria" data-tippy-content="Selecione a categoria do imovel" required value="<?= old('categorias', $imovel['categorias']); ?>">
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                                            <label class="form-label">Fornecedor</label>
                                            <input type="text" class="form-control" name="codigo_fornecedor" data-select="buscarFornecedor" data-tippy-content="Selecione o Fornecedor" required value="<?= old('codigo_fornecedor', $imovel['codigo_fornecedor']); ?>">
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                                            <label class="form-label">SKU</label>
                                            <input type="text" class="form-control" name="sku" data-tippy-content="O SKU é um código identificador único de um imovel, e é utilizado para controle do estoque" value="<?= old('sku', $imovel['sku']); ?>">
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                                            <label class="form-label">NCM</label>
                                            <input type="text" class="form-control" name="ncm" data-tippy-content="O NCM é um código de oito dígitos estabelecido pelo Governo Brasileiro para identificar a natureza das mercadorias"value="<?= old('ncm', $imovel['ncm']); ?>">
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                                            <label class="form-label">CEST</label>
                                            <input type="text" class="form-control" name="cest" data-tippy-content="O CEST é um código utilizado para identificar imovels passíveis à substituição tributária" value="<?= old('cest', $imovel['cest']); ?>">
                                        </div>
                                        <div class="col-md-12 col-lg-12 col-sm-12 mb-4">
                                            <label class="form-label">Descrição</label>
                                            <textarea class="form-control" name="descricao" rows="2" data-tippy-content="Informe uma Descrição Para imovel"><?= old('descricao', $imovel['descricao']); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fim :: Cadastro Básico -->

                <!-- Inicio :: Cadastro Imagem -->
                <div class="card mt-4">
                    <div class="card-header fw-bold">Imagens do imovel</div>
                    <div class="app-card shadow-sm p-4">
                        <div class="app-card-body">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12 mb-2">
                                    <div class="row justify-content-between align-items-center">
                                        <div class="col" id="file-itens">
                                            <input type="file" id="imagensimovel" name="filepond[]" data-max-file-size="5MB" data-max-files="10" multiple>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <?php foreach ($imovelImagem as $key => $value) : ?>
                                        <div class="col-md-3 col-lg-3 col-sm-12 mt-3 offset-md-1 offset-lg-1 card justify-content-center" data-image="<?= $value['uuid_imovel_imagem'] ?>">
                                            <div class="row">
                                                <img src="<?= $value['diretorio_imagem'] ?>" class="mt-2" style="object-fit:contain;" width="223" height="291" id="<?= $value['uuid_imovel_imagem'] ?>" alt="Imagem do imovel">
                                            </div>
                                            <div class="d-flex justify-content-center mb-3">
                                                <button data-url="/imovel/desativarImagem/<?= $value['uuid_imovel_imagem'] ?>" type="button" class="btn-sm app-btn-secondary mt-3" data-tippy-content="Desativar" data-action="removerImagem" data-id="<?= $value['uuid_imovel_imagem'] ?>">Remover Imagem</button>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        <!-- Fim :: Cadastro  Imagem -->

        <div class="d-flex justify-content-end mt-2">
            <button type="submit" class="btn app-btn-primary">Salvar</button>
        </div>

        </form>
        <!-- Fim :: Formulário -->


    </div>
</div>
</div>
