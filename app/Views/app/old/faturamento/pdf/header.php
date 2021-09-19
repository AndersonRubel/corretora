<div class="header">
    <div class="row" style="height: 50px;">

        <?php
        if (!empty($empresa['logomarca'])) {
            $largura1 = "float: left; width: 12.66%;";
            $largura2 = "float: left; width: 37.33%;";
            $largura3 = "width: 50.00%;";
        } else {
            $largura1 = "";
            $largura2 = "float: left; width: 50%;";
            $largura3 = "width: 50%;";
        }
        ?>

        <?php if (!empty($empresa['logomarca'])) : ?>
            <div class="column" style="<?= $largura1 ?>">
                <img src="<?= $empresa['img_logo'] ?>" alt="" style="width: 75px; border-radius: 15px;">
            </div>
        <?php endif; ?>

        <div class="column" style="<?= $largura2 ?>">
            <span style="font-size: 11px;">
                <b>
                    <?= !empty($empresa['nome_fantasia']) ? $empresa['nome_fantasia'] . ' | ' : '' ?>
                    <?= !empty($empresa['telefone'])      ? intToPhone($empresa['telefone']) : '' ?>
                </b>
                <br>
                <?= !empty($empresa['endereco']['rua']) ? $empresa['endereco']['rua'] : ''; ?>,
                <?= !empty($empresa['endereco']['numero']) ? $empresa['endereco']['numero'] : ''; ?> -
                <?= !empty($empresa['endereco']['bairro']) ? $empresa['endereco']['bairro'] : ''; ?> <br>
                <?= !empty($empresa['endereco']['cidade']) ? $empresa['endereco']['cidade'] : ''; ?> -
                <?= !empty($empresa['endereco']['uf']) ? $empresa['endereco']['uf'] : ''; ?> -
                <?= !empty($empresa['endereco']['cep']) ? intToCep($empresa['endereco']['cep']) : ''; ?> <br>
            </span>
        </div>

        <div class="column text-right" style="<?= $largura3 ?>">
            <img src="<?= base_url('assets/img/logo.png') ?>" style="width: 130px;">
        </div>
    </div>

</div>
