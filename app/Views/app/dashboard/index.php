<div class="app-wrapper" id="dashboard">
    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">
            <h1 class="app-page-title">Dashboard</h1>

            <!-- Inicio :: Listagem de Menus -->
            <div class="row g-4">
                <?php foreach ($menusDashboard as $key => $value) : ?>
                    <div class="col-sm-12 col-md-4 col-xl-3 col-xxl-2">
                        <div class="app-card app-card-doc shadow-sm h-100">
                            <div class="app-card-thumb-holder p-3">
                                <span class="icon-holder">
                                    <i class="<?= !empty($value['icone']) ? $value['icone'] : 'fas fa-home'; ?>"></i>
                                </span>
                                <a class="app-card-link-mask" href="<?= base_url($value['path']); ?>"></a>
                            </div>
                            <div class="app-card-body p-3 has-card-actions">

                                <h4 class="app-doc-title truncate mb-0">
                                    <a class="submenu-link" href="<?= base_url($value['path']); ?>">
                                        <?= $value['nome']; ?>
                                    </a>
                                </h4>

                                <div class="app-doc-meta">
                                    <ul class="list-unstyled mb-0">
                                        <li><?= !empty($value['descricao']) ? $value['descricao'] : '?'; ?></li>
                                    </ul>
                                </div>

                                <?php if (!empty($value['submenus'][0])) : ?>
                                    <div class="app-card-actions">
                                        <div class="dropdown">

                                            <div class="dropdown-toggle no-toggle-arrow cursor" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </div>

                                            <ul class="dropdown-menu">
                                                <?php foreach ($value['submenus'][0] as $keySubMenus => $valueSubMenus) : ?>
                                                    <li>
                                                        <a class="dropdown-item" href="<?= base_url($valueSubMenus['path']); ?>">
                                                            <i class="<?= !empty($valueSubMenus['icone']) ? $valueSubMenus['icone'] : 'fas fa-home'; ?>"></i>
                                                            <?= $valueSubMenus['nome'] ?>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- Fim :: Listagem de Menus -->

        </div>

    </div>
</div>
