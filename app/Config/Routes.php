<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('LoginController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
* --------------------------------------------------------------------
* Rotas Livres
* --------------------------------------------------------------------
*/

$routes->get('/', 'LoginController::index');

// Recuperação da Senha
$routes->get('alterar-senha/(:hash)', 'UsuarioController::recuperarSenha/$1');
$routes->post('habilita-recuperacao-senha', 'UsuarioController::habilitaRecuperacaoSenha');
$routes->post('update-password', 'UsuarioController::alterarSenha');

// Rotas de login
$routes->group('login', function ($routes) {
    $routes->get('', 'LoginController::index');
    $routes->get('logout', 'LoginController::logout');
    $routes->post('email', 'LoginController::loginEmail');
});

/*
* --------------------------------------------------------------------
* Rotas Autenticadas
* --------------------------------------------------------------------
*/

$routes->group('', ['filter' => 'sessao'], function ($routes) {

    // Outras Rotas
    $routes->get('perfil', 'UsuarioController::indexPerfil');

    // Busca o BASE64 da Imagem atraves do diretorio
    $routes->get('getImagem/(:any)', function ($routes) {
        return (new \App\Controllers\BaseController)->getFileImagem($routes);
    });

    // Rotas que necessitam de Permissão
    $routes->group('', ['filter' => 'permissao'], function ($routes) {

        // Rotas da Dashboard
        $routes->group('dashboard', function ($routes) {
            $routes->get('', 'DashboardController::index');
        });

        // Rotas de Cadastro - Crud Dinâmico
        $routes->group('cadastro', function ($routes) {
            $routes->get('', 'CadastroController::index');
            $routes->add('insert(:any)', 'CadastroController::insert$1$2');
            $routes->add('update(:any)', 'CadastroController::update$1$2$3$4');
            $routes->add('delete(:any)', 'CadastroController::delete$1$2$3');
            $routes->add('copiar(:any)', 'CadastroController::copiarRegistro$1$2$3');
            $routes->add('toggle-status(:any)', 'CadastroController::toggleStatus$1$2$3');

            // Select2
            $routes->post('selectCadastroFluxoTipo', 'CadastroController::selectCadastroFluxoTipo');
            $routes->post('selectCadastroMetodoPagamento', 'CadastroController::selectCadastroMetodoPagamento');

            // Telas de Crud
            $routes->add('configuracao(:any)', 'CadastroController::configuracao$1$2');
            $routes->add('grupo(:any)', 'CadastroController::grupo$1$2');
            $routes->add('menu(:any)', 'CadastroController::menu$1$2');
            $routes->add('tipoImovel(:any)', 'CadastroController::tipoImovel$1$2');
            $routes->add('categoriaImovel(:any)', 'CadastroController::categoriaImovel$1$2');

        });

        // Rotas de Cliente
        $routes->group('cliente', function ($routes) {
            // Páginas
            $routes->get('', 'ClienteController::index');
            $routes->get('adicionar', 'ClienteController::create');
            $routes->get('alterar/(:hash)', 'ClienteController::edit/$1');

            // Funcionalidades
            $routes->post('store', 'ClienteController::store');
            $routes->post('update/(:hash)', 'ClienteController::update/$1');
            $routes->post('ativar/(:hash)', 'ClienteController::enable/$1');
            $routes->post('desativar/(:hash)', 'ClienteController::disable/$1');
            $routes->post('desativarEndereco/(:hash)', 'ClienteController::disableEndereco/$1');
            $routes->post('getDataGrid/(:num)', 'ClienteController::getDataGrid/$1');
            $routes->post('backendCall/(:alphanum)', 'ClienteController::backendCall/$1');
        });

        // Rotas de Empresa
        $routes->group('empresa', function ($routes) {
            // Páginas
            $routes->get('', 'EmpresaController::index');
            $routes->get('adicionar', 'EmpresaController::create');
            $routes->get('alterar/(:hash)', 'EmpresaController::edit/$1');

            // Funcionalidades
            $routes->post('store', 'EmpresaController::store');
            $routes->post('update/(:hash)', 'EmpresaController::update/$1');
            $routes->post('ativar/(:hash)', 'EmpresaController::enable/$1');
            $routes->post('desativar/(:hash)', 'EmpresaController::disable/$1');
            $routes->post('getDataGrid/(:num)', 'EmpresaController::getDataGrid/$1');
            $routes->post('backendCall/(:alphanum)', 'EmpresaController::backendCall/$1');
        });

        // Rotas de Grupo
        $routes->group('grupo', function ($routes) {
            // Páginas
            $routes->get('', 'GrupoController::index');
            $routes->get('adicionar', 'GrupoController::create');
            $routes->get('alterar/(:hash)', 'GrupoController::edit/$1');

            // Funcionalidades
            $routes->post('store', 'GrupoController::store');
            $routes->post('update/(:hash)', 'GrupoController::update/$1');
            $routes->post('ativar/(:hash)', 'GrupoController::enable/$1');
            $routes->post('desativar/(:hash)', 'GrupoController::disable/$1');
            $routes->post('getDataGrid/(:num)', 'GrupoController::getDataGrid/$1');
            $routes->post('backendCall/(:alphanum)', 'GrupoController::backendCall/$1');
        });

        // Rotas de Produto
        $routes->group('produto', function ($routes) {
            // Páginas
            $routes->get('', 'ProdutoController::index');
            $routes->get('adicionar', 'ProdutoController::create');
            $routes->get('alterar/(:hash)', 'ProdutoController::edit/$1');

            // Funcionalidades
            $routes->get('gerarCodigoBarras/(:hash)', 'ProdutoController::gerarCodigoBarras/$1');
            $routes->post('store', 'ProdutoController::store');
            $routes->post('update/(:hash)', 'ProdutoController::update/$1');
            $routes->post('alterarPreco/(:hash)', 'ProdutoController::alterarPreco/$1');
            $routes->post('ativar/(:hash)', 'ProdutoController::enable/$1');
            $routes->post('desativar/(:hash)', 'ProdutoController::disable/$1');
            $routes->post('getDataGrid/(:num)', 'ProdutoController::getDataGrid/$1');
            $routes->post('backendCall/(:alphanum)', 'ProdutoController::backendCall/$1');
        });

        // Rotas de relatórios
        $routes->group('relatorios', function ($routes) {
            $routes->add('(:any)', 'RelatorioController::$1');
            $routes->add('', 'RelatorioController::index');
        });
        // Rotas de tipo de imovel
        $routes->group('tipoImovel', function ($routes) {
          // Páginas
          $routes->get('', 'TipoImovelController::index');
          $routes->get('adicionar', 'TipoImovelController::create');
          $routes->get('alterar/(:hash)', 'TipoImovelController::edit/$1');

          // Funcionalidades
          $routes->post('store', 'TipoImovelController::store');
          $routes->post('update/(:hash)', 'TipoImovelController::update/$1');
          $routes->post('updateProfile', 'TipoImovelController::updateProfile');
          $routes->post('ativar/(:hash)', 'TipoImovelController::enable/$1');
          $routes->post('desativar/(:hash)', 'TipoImovelController::disable/$1');
          $routes->post('getDataGrid/(:num)', 'TipoImovelController::getDataGrid/$1');
          $routes->post('backendCall/(:alphanum)', 'TipoImovelController::backendCall/$1');
           });

        // Rotas de Usuario
        $routes->group('usuario', function ($routes) {
            // Páginas
            $routes->get('', 'UsuarioController::index');
            $routes->get('adicionar', 'UsuarioController::create');
            $routes->get('alterar/(:hash)', 'UsuarioController::edit/$1');

            // Funcionalidades
            $routes->post('store', 'UsuarioController::store');
            $routes->post('update/(:hash)', 'UsuarioController::update/$1');
            $routes->post('updateProfile', 'UsuarioController::updateProfile');
            $routes->post('ativar/(:hash)', 'UsuarioController::enable/$1');
            $routes->post('desativar/(:hash)', 'UsuarioController::disable/$1');
            $routes->post('getDataGrid/(:num)', 'UsuarioController::getDataGrid/$1');
            $routes->post('backendCall/(:alphanum)', 'UsuarioController::backendCall/$1');
        });
    });
});




/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
