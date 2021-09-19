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

        // Rotas de Aniversários
        $routes->group('aniversario', function ($routes) {
            // Páginas
            $routes->get('', 'AniversarioController::index');

            // Funcionalidades
            $routes->post('envio', 'AniversarioController::send');
            $routes->post('getDataGrid', 'AniversarioController::getDataGrid');
            $routes->post('backendCall/(:alphanum)', 'AniversarioController::backendCall/$1');
        });

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
            $routes->add('empresaCategoria(:any)', 'CadastroController::empresaCategoria$1$2');
            $routes->add('empresaCentroCusto(:any)', 'CadastroController::empresaCentroCusto$1$2');
            $routes->add('empresaComissao(:any)', 'CadastroController::empresaComissao$1$2');
            $routes->add('empresaSituacao(:any)', 'CadastroController::empresaSituacao$1$2');
            $routes->add('fluxoTipo(:any)', 'CadastroController::fluxoTipo$1$2');
            $routes->add('grupo(:any)', 'CadastroController::grupo$1$2');
            $routes->add('menu(:any)', 'CadastroController::menu$1$2');
            $routes->add('metodoPagamento(:any)', 'CadastroController::metodoPagamento$1$2');
            $routes->add('movimentacaoTipo(:any)', 'CadastroController::movimentacaoTipo$1$2');
            $routes->add('relatorio(:any)', 'CadastroController::relatorio$1$2');
        });

        // Rotas de Cliente
        $routes->group('cliente', function ($routes) {
            // Páginas
            $routes->get('', 'ClienteController::index');
            $routes->get('adicionar', 'ClienteController::create');
            $routes->get('alterar/(:hash)', 'ClienteController::edit/$1');
            $routes->get('visualizar/(:hash)', 'ClienteController::view/$1');

            // Funcionalidades
            $routes->post('store', 'ClienteController::store');
            $routes->post('storeSimplificado', 'ClienteController::storeSimplificado');
            $routes->post('adicionarSaldo', 'ClienteController::adicionarSaldo');
            $routes->post('update/(:hash)', 'ClienteController::update/$1');
            $routes->post('ativar/(:hash)', 'ClienteController::enable/$1');
            $routes->post('desativar/(:hash)', 'ClienteController::disable/$1');
            $routes->post('desativarEndereco/(:hash)', 'ClienteController::disableEndereco/$1');
            $routes->post('getDataGrid/(:num)', 'ClienteController::getDataGrid/$1');
            $routes->post('getDataGridExtrato/(:num)', 'ClienteController::getDataGridExtrato/$1');
            $routes->post('getDataGridHistoricoProduto', 'ClienteController::getDataGridHistoricoProduto');
            $routes->post('getDataGridHistoricoFinanceiro', 'ClienteController::getDataGridHistoricoFinanceiro');
            $routes->post('getDataGridHistoricoSaldo', 'ClienteController::getDataGridHistoricoSaldo');
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

        // Rotas de Estatisticas
        $routes->group('estatistica', function ($routes) {
            // Páginas
            $routes->get('', 'EstatisticaController::index');

            // Funcionalidades
            $routes->post('backendCall/(:alphanum)', 'EstatisticaController::backendCall/$1');
        });

        // Rotas de Estoque
        $routes->group('estoque', function ($routes) {
            // Páginas
            $routes->get('', 'EstoqueController::index');
            $routes->get('adicionar', 'EstoqueController::adicionar');
            $routes->get('baixar', 'EstoqueController::baixar');
            $routes->get('transferir', 'EstoqueController::transferir');
            $routes->get('devolver', 'EstoqueController::devolver');
            $routes->get('entradas', 'EstoqueController::indexEntrada');
            $routes->get('baixas', 'EstoqueController::indexBaixa');
            $routes->get('historico', 'EstoqueController::indexHistorico');
            $routes->get('historicoItem/(:any)', 'EstoqueController::indexHistoricoItem/$1');
            $routes->get('historicoItem/(:any)/(:any)', 'EstoqueController::indexHistoricoItem/$1/$2');
            $routes->get('recibo/(:any)/(:hash)', 'EstoqueController::recibo/$1/$2');

            // Funcionalidades
            $routes->post('realizarEntrada', 'EstoqueController::realizarEntrada');
            $routes->post('realizarBaixa', 'EstoqueController::realizarBaixa');
            $routes->post('realizarTransferencia', 'EstoqueController::realizarTransferencia');
            $routes->post('realizarDevolucao', 'EstoqueController::realizarDevolucao');
            $routes->post('getDataGrid/(:num)', 'EstoqueController::getDataGrid/$1');
            $routes->post('getDataGridEntrada', 'EstoqueController::getDataGridEntrada');
            $routes->post('getDataGridBaixa', 'EstoqueController::getDataGridBaixa');
            $routes->post('getDataGridHistorico', 'EstoqueController::getDataGridHistorico');
            $routes->post('getDataGridHistoricoItem/(:hash)/(:hash)', 'EstoqueController::getDataGridHistoricoItem/$1/$2');
            $routes->post('backendCall/(:alphanum)', 'EstoqueController::backendCall/$1');
        });

        // Rotas de Faturamento
        $routes->group('faturamento', function ($routes) {
            // Páginas
            $routes->get('', 'FaturamentoController::index');
            $routes->get('adicionar', 'FaturamentoController::create');

            // Funcionalidades
            $routes->get('gerarPdf/(:hash)', 'FaturamentoController::gerarPdf/$1');
            $routes->post('store', 'FaturamentoController::store');
            $routes->post('update/(:hash)', 'FaturamentoController::update/$1');
            $routes->post('desativar/(:hash)', 'FaturamentoController::disable/$1');
            $routes->post('getDataGrid', 'FaturamentoController::getDataGrid');
            $routes->post('backendCall/(:alphanum)', 'FaturamentoController::backendCall/$1');
        });

        // Rotas de Financeiro
        $routes->group('financeiro', function ($routes) {
            // Páginas
            $routes->get('', 'FinanceiroController::index');
            $routes->get('adicionar', 'FinanceiroController::create');
            $routes->get('alterar/(:hash)', 'FinanceiroController::edit/$1');
            $routes->get('recibo/(:hash)', 'FinanceiroController::recibo/$1');
            $routes->get('comprovante/(:hash)', 'FinanceiroController::comprovante/$1');

            // Funcionalidades
            $routes->get('getGraficoResumo', 'FinanceiroController::getGraficoResumo');
            $routes->post('store', 'FinanceiroController::store');
            $routes->post('update/(:hash)', 'FinanceiroController::update/$1');
            $routes->post('abaterValores', 'FinanceiroController::abaterValores');
            $routes->post('marcarPago/(:hash)', 'FinanceiroController::marcarPago/$1');
            $routes->post('marcarPendente/(:hash)', 'FinanceiroController::marcarPendente/$1');
            $routes->post('pagarParcial/(:hash)', 'FinanceiroController::pagarParcial/$1');
            $routes->post('removerPagamentoParcial/(:hash)', 'FinanceiroController::removerPagamentoParcial/$1');
            $routes->post('ativar/(:hash)', 'FinanceiroController::enable/$1');
            $routes->post('desativar/(:hash)', 'FinanceiroController::disable/$1');
            $routes->post('getDataGrid/(:num)', 'FinanceiroController::getDataGrid/$1');
            $routes->post('getDataGridResumo/(:num)', 'FinanceiroController::getDataGridResumo/$1');
            $routes->post('backendCall/(:alphanum)', 'FinanceiroController::backendCall/$1');
        });

        // Rotas de Fornecedor
        $routes->group('fornecedor', function ($routes) {
            // Páginas
            $routes->get('', 'FornecedorController::index');
            $routes->get('adicionar', 'FornecedorController::create');
            $routes->get('alterar/(:hash)', 'FornecedorController::edit/$1');

            // Funcionalidades
            $routes->post('store', 'FornecedorController::store');
            $routes->post('update/(:hash)', 'FornecedorController::update/$1');
            $routes->post('ativar/(:hash)', 'FornecedorController::enable/$1');
            $routes->post('desativar/(:hash)', 'FornecedorController::disable/$1');
            $routes->post('getDataGrid/(:num)', 'FornecedorController::getDataGrid/$1');
            $routes->post('backendCall/(:alphanum)', 'FornecedorController::backendCall/$1');
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

        // Rotas do PDV
        $routes->group('pdv', function ($routes) {
            // Páginas
            $routes->get('', 'PdvController::index');

            // Funcionalidades
            $routes->post('store', 'PdvController::store');
            $routes->post('backendCall/(:alphanum)', 'PdvController::backendCall/$1');
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

        // Rotas de Vendas
        $routes->group('venda', function ($routes) {
            // Páginas
            $routes->get('', 'VendaController::index');
            $routes->get('adicionar', 'VendaController::create');
            $routes->get('alterar/(:hash)', 'VendaController::edit/$1');
            $routes->get('comprovante/(:hash)', 'VendaController::comprovante/$1');

            // Funcionalidades
            $routes->post('store', 'VendaController::store');
            $routes->post('update/(:hash)', 'VendaController::update/$1');
            $routes->post('ativar/(:hash)', 'VendaController::enable/$1');
            $routes->post('desativar/(:hash)', 'VendaController::disable/$1');
            $routes->post('estorno/(:hash)', 'VendaController::estornarVenda/$1');
            $routes->post('getDataGrid/(:num)', 'VendaController::getDataGrid/$1');
            $routes->post('backendCall/(:alphanum)', 'VendaController::backendCall/$1');
        });

        // Rotas de Vendedor
        $routes->group('vendedor', function ($routes) {
            // Páginas
            $routes->get('', 'VendedorController::index');
            $routes->get('adicionar', 'VendedorController::create');
            $routes->get('alterar/(:hash)', 'VendedorController::edit/$1');
            $routes->get('visualizar/(:hash)', 'VendedorController::view/$1');

            // Funcionalidades
            $routes->post('store', 'VendedorController::store');
            $routes->post('update/(:hash)', 'VendedorController::update/$1');
            $routes->post('ativar/(:hash)', 'VendedorController::enable/$1');
            $routes->post('desativar/(:hash)', 'VendedorController::disable/$1');
            $routes->post('getDataGrid/(:num)', 'VendedorController::getDataGrid/$1');
            $routes->post('getDataGridEstoque', 'VendedorController::getDataGridEstoque');
            $routes->post('getDataGridHistoricoVenda', 'VendedorController::getDataGridHistoricoVenda');
            $routes->post('getDataGridHistoricoFinanceiro', 'VendedorController::getDataGridHistoricoFinanceiro');
            $routes->post('backendCall/(:alphanum)', 'VendedorController::backendCall/$1');
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
