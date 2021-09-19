<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

use App\Libraries\NativeSession;

class Sessao implements FilterInterface
{
    /**
     * Instance of the main response object.
     * @var ResponseInterface
     */
    protected $response;

    public function before(RequestInterface $request, $arguments = null)
    {
        // Intancia a Sessão novamente
        $session = new NativeSession(true);
        // Verifica se existe sessão
        if (!$session->get("usuario")) {
            return redirect()->to(base_url());
        }

        // Valida quanto tempo faz que o usuário esta logado
        // Enquanto tiver interação no sistema renova a sessão até o máximo cadastrado
        $tempoMaximoSessao = null;
        $dataLogin = $session->get("usuario")['data_login'];
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
