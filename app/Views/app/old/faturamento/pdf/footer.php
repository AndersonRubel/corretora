<?php setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo'); ?>
<div class="footer">
    <div class="w-50 float-left">
        <?= !empty($empresa['endereco']['rua']) ? $empresa['endereco']['rua'] : ''; ?>,
        <?= !empty($empresa['endereco']['numero']) ? $empresa['endereco']['numero'] : ''; ?> -
        <?= !empty($empresa['endereco']['bairro']) ? $empresa['endereco']['bairro'] : ''; ?> <br>
        <?= !empty($empresa['endereco']['cidade']) ? $empresa['endereco']['cidade'] : ''; ?> -
        <?= !empty($empresa['endereco']['uf']) ? $empresa['endereco']['uf'] : ''; ?> -
        <?= !empty($empresa['endereco']['cep']) ? intToCep($empresa['endereco']['cep']) : ''; ?> <br>
        <?= !empty($empresa['telefone']) ? intToPhone($empresa['telefone']) : ''; ?>
        <?= strftime('%d de %B de %Y', strtotime('today')); ?>
    </div>
    <div class="w-50 float-left paginacao">
        <span class="pagination">{PAGENO}/{nb}</span>
    </div>
</div>
