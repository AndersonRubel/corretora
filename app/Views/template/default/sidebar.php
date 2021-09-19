<?php

// Filtra apenas os principais
$novosMenus = array_filter($menus, function ($menu) {
    return empty($menu['localizacao']);
});

// Percorre os menus adicionando os submenus
foreach ($novosMenus as $key => $value) {
    $novosMenus[$key]['submenus'][] = array_filter($menus, function ($menu) use ($value) {
        return !empty($menu['localizacao']) && $menu['agrupamento'] == $value['agrupamento'];
    });
}

$menusDashboard = $novosMenus;

// Filtra somente os menus que não terão Submenu
$menusSidebar = array_filter($menus, function ($menu) {
    return $menu['agrupamento'] == "sidebar";
});

$menusSidebarGestao = array_filter($menus, function ($menu) {
    return $menu['agrupamento'] == "gestao";
});

$menusSidebarEstoque = array_filter($menus, function ($menu) {
    return $menu['agrupamento'] == "estoque";
});

$menusSidebarFinanceiro = array_filter($menus, function ($menu) {
    return $menu['agrupamento'] == "financas";
});

?>

<div id="app-sidepanel" class="app-sidepanel sidepanel-hidden">
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

                <?php foreach ($menusDashboard as $key => $value) : ?>

                    <?php if (empty($value['submenus'][0])) : ?>
                        <!-- Inicio :: Menus sem SubMenu -->
                        <li class="nav-item">
                            <a href="<?= base_url($value['path']); ?>" class="nav-link <?= $base->getRouterName() == $value['path'] ? 'active' : ''; ?>" data-tippy-content="<?= $value['descricao']; ?>" data-tippy-placement="right">
                                <span class="nav-icon">
                                    <i class="<?= !empty($value['icone']) ? $value['icone'] : 'fas fa-home'; ?>"></i>
                                </span>
                                <span class="nav-link-text"><?= $value['nome']; ?></span>
                            </a>
                        </li>
                        <!-- Fim :: Menus sem SubMenu -->
                    <?php else : ?>
                        <!-- Inicio :: Menus com SubMenu -->
                        <li class="nav-item has-submenu">
                            <a class="nav-link submenu-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#<?= $value['agrupamento'] ?>" aria-expanded="false" aria-controls="<?= $value['agrupamento'] ?>">
                                <span class="nav-icon">
                                    <i class="<?= !empty($value['icone']) ? $value['icone'] : 'fas fa-home'; ?>"></i>
                                </span>
                                <span class="nav-link-text"><?= $value['nome']; ?></span>

                                <span class="submenu-arrow">
                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chevron-down" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"></path>
                                    </svg>
                                </span>
                            </a>
                            <div id="<?= $value['agrupamento'] ?>" class="collapse submenu <?= $value['agrupamento'] ?>" data-bs-parent="#menu-accordion">
                                <ul class="submenu-list list-unstyled">
                                    <?php foreach ($value['submenus'][0] as $keySubMenus => $valueSubMenus) : ?>
                                        <li class="submenu-item" data-tippy-content="<?= $valueSubMenus['descricao']; ?>" data-tippy-placement="right">
                                            <a class="submenu-link <?= $base->getRouterName() == $valueSubMenus['path'] ? 'active' : ''; ?>" href="<?= base_url($valueSubMenus['path']); ?>">
                                                <?= $valueSubMenus['nome']; ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </li>
                        <!-- Fim :: Menus com SubMenu -->
                    <?php endif; ?>

                <?php endforeach; ?>

            </ul>
        </nav>
    </div>
</div>
