<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use App\Libraries\NativeSession;

class Permissao implements FilterInterface
{
    // Exceções de Controllers -- Ignora todos os métodos do controlador
    protected $controllerExceptions = ['LoginController', 'DashboardController'];

    // Metodos que serão validados -- Funções que passarão pela regra
    protected $validarMetodos = ['index', 'create', 'edit', 'show', 'store', 'update', 'enable', 'disable'];

    /**
     * This is a demo implementation of using the Throttler class
     * to implement rate limiting for your application.
     *
     * @param RequestInterface|\CodeIgniter\HTTP\IncomingRequest $request
     * @param array|null                                         $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $nativeSession = new NativeSession(true);

        // Verifica se o usuário esta logado ainda
        if (!$nativeSession->get("usuario")) {
            // Redireciona para o login
            return redirect()->to(base_url());
        }

        $router        = Services::router();
        $codigoGrupo   = $nativeSession->get("empresa")['codigo_cadastro_grupo'];
        $menus         = $nativeSession->get("menus");
        $controller    = str_replace('\App\Controllers\\', '', $router->controllerName());
        $rota          = Services::uri()->getPath();

        // Se o Grupo de Acesso for 1 (Super Admin) ignora a validação
        if (!in_array($codigoGrupo, [1])) {
            // Se o Controllador atual não estiver nas exceções, começa a validação
            if (!in_array($controller, $this->controllerExceptions)) {

                // Se a ROTA não estiver no array de permissões, e o o Método atual estiver nos métodos de validação
                if (!in_array($rota, array_column($menus, 'path')) && in_array($router->methodName(), $this->validarMetodos)) {
                    $nativeSession->setFlashData('error', lang('Errors.geral.acessoNaoPermitido'));
                    return redirect()->to(base_url('login'));
                }
            }
        }
    }

    //--------------------------------------------------------------------

    /**
     * We don't have anything to do here.
     *
     * @param RequestInterface|\CodeIgniter\HTTP\IncomingRequest $request
     * @param ResponseInterface|\CodeIgniter\HTTP\Response       $response
     * @param array|null                                         $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
