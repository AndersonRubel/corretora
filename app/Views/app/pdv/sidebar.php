<?php
// Filtra somente os menus que não terão Submenu
$menusSidebar = array_filter($menus, function ($menu) {
    return $menu['agrupamento'] == "sidebar";
});

?>

<div id="app-sidepanel" class="pdv app-sidepanel sidepanel-hidden">
    <div id="sidepanel-drop" class="sidepanel-drop"></div>
    <div class="sidepanel-inner d-flex flex-column">

        <a href="#" id="sidepanel-close" class="sidepanel-close d-xl-none">&times;</a>

        <div class="app-branding">
            <a class="app-logo" href="<?= base_url(); ?>">
                <img class="logo-icon me-2" src="<?= base_url('assets/img/app-logo.svg'); ?>" alt="logo">
                <span class="logo-text"><?= env('app.nomeSistema'); ?></span>
            </a>
        </div>

        <nav id="app-nav-main" class="app-nav app-nav-main flex-grow-1">
            <ul class="app-menu list-unstyled accordion" id="menu-accordion">

                <?php if (!empty($menusSidebar)) : ?>
                    <!-- Inicio :: Menus da Sidebar -->
                    <?php foreach ($menusSidebar as $key => $value) : ?>
                        <li class="nav-item">
                            <a href="<?= base_url($value['path']); ?>" class="nav-link <?= $base->getRouterName() == $value['path'] ? 'active' : ''; ?>">
                                <span class="nav-icon">
                                    <i class="<?= !empty($value['icone']) ? $value['icone'] : 'fas fa-home'; ?>"></i>
                                </span>
                                <span class="nav-link-text"><?= $value['nome']; ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    <!-- Fim :: Menus da Sidebar -->
                <?php endif; ?>

                <!-- Inicio :: Menu de Voltar -->
                <li class="nav-item">
                    <a href="<?= base_url(); ?>" class="nav-link">
                        <span class="nav-icon">
                            <i class="fas fa-reply"></i>
                        </span>
                        <span class="nav-link-text">Voltar ao Sistema</span>
                    </a>
                </li>
                <!-- Fim :: Menu de Voltar -->

                <!-- Inicio :: Menu de Sair -->
                <li class="nav-item">
                    <a href="<?= base_url('login/logout'); ?>" class="nav-link">
                        <span class="nav-icon">
                            <i class="fas fa-sign-out-alt"></i>
                        </span>
                        <span class="nav-link-text">Sair do Sistema</span>
                    </a>
                </li>
                <!-- Fim :: Menu de Sair -->

            </ul>
        </nav>
    </div>
</div>
