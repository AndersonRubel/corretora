<?php

namespace App\Controllers;

use Exception;

use App\Models\Cadastro\CadastroMenuModel;
use App\Models\Empresa\EmpresaModel;
use App\Models\Imovel\ImovelModel;
use App\Models\Log\LogLoginModel;
use App\Models\Usuario\UsuarioModel;

class LoginController extends BaseController
{
    /**
     * Exibe a Tela de Login
     * @return html
     */
    public function index()
    {
        if ($this->nativeSession->get("usuario")) {
            // Redirecionar para a primeira tela que o usuário tiver nas permissoes
            return redirect()->to(base_url('dashboard'));
        } else {
            return $this->template('login', ['index', 'modal', 'functions'], [], false, false);
        }
    }

    /**
     * Realiza o Logout do Usuário
     */
    public function logout()
    {
        $this->nativeSession->destroy();
        return redirect()->to(base_url());
    }

    //////////////////////////////////
    //                              //
    //    OPERAÇÕES DE CADASTRO     //
    //                              //
    //////////////////////////////////

    /**
     * Realiza o Login do Usuário com Email e Senha
     */
    public function loginEmail()
    {
        $empresaModel = new EmpresaModel;
        $cadastroMenuModel = new CadastroMenuModel;
        $usuarioModel = new UsuarioModel;
        $imovelModel = new ImovelModel;
        $dadosRequest = $this->request->getVar();
        $erros = $this->validarRequisicao($this->request, [
            'email' => 'required|valid_email',
            'senha' => 'required|string|min_length[6]'
        ]);

        if (!empty($erros)) {
            $this->nativeSession->setFlashData('error', lang("Errors.login.usuarioSenhaIncorreto"));
            return redirect()->to(base_url());
        }

        // Se o email e a senha foram iguais, significa que é o primeiro acesso. Então força a troca da senha
        if ($dadosRequest['email'] == $dadosRequest['senha']) {
            try {
                $response = $this->sendCurl(base_url("habilita-recuperacao-senha"), "POST", ["email" => $dadosRequest['email']]);
                if ($response) {
                    $this->nativeSession->setFlashData('error', lang("Errors.login.necessarioTrocarSenha"));
                    return redirect()->to(base_url());
                }
            } catch (Exception $e) {
                var_dump($e);
                die;
            }
        }

        // Se passar na Validação, busca o Usuário atraves do email
        $colunas = [
            'codigo_usuario',
            'uuid_usuario',
            'codigo_empresa',
            'senha',
            'nome',
            'email',
            'celular',
            'diretorio_avatar'
        ];
        $usuario = $usuarioModel->get(["email" => $dadosRequest['email']], $colunas, true);

        if (empty($usuario)) {
            $this->_logLogin("f", lang("Errors.login.usuarioSenhaIncorreto"), null, $this->request);
            $this->nativeSession->setFlashData('error', lang("Errors.login.usuarioSenhaIncorreto"));
            return redirect()->to(base_url());
        }

        // Verifica se a Senha esta correta
        if (!password_verify($dadosRequest['senha'], $usuario['senha'])) {
            $this->_logLogin("f", lang("Errors.login.usuarioSenhaIncorreto"), $usuario['uuid_usuario'], $this->request);
            $this->nativeSession->setFlashData('error', lang("Errors.login.usuarioSenhaIncorreto"));
            return redirect()->to(base_url());
        }

        // Refaz a Hash da senha do usuário
        if (password_needs_rehash($usuario['senha'], PASSWORD_BCRYPT)) {
            $usuario['senha'] = password_hash($dadosRequest['senha'], PASSWORD_BCRYPT);
        }

        // Atualiza o Usuário
        $usuario['ultimo_login'] = date('Y-m-d H:i:s');
        $usuarioModel->update($usuario['codigo_usuario'], $usuario);

        unset($usuario['senha']); // Remove a Senha do array para salvar na sessão

        // Busca os dados da Empresa
        $empresa = $empresaModel->getEmpresaUsuario($usuario['codigo_empresa']);
        if (empty($empresa)) {
            $this->_logLogin("f", lang("Errors.login.naoPossuiEmpresa"), null, $this->request);
            $this->nativeSession->setFlashData('error', lang("Errors.login.naoPossuiEmpresa"));
            return redirect()->to(base_url());
        }

        // Busca os dados da Empresa
        $imovel = $imovelModel->get(['codigo_empresa' => $empresa['codigo_empresa']]);

        // Monta um Array com os dados que ficarão na Sessão
        $dadosSessao['usuario']                   = $usuario;
        $dadosSessao['usuario']['codigo_imovel'] = !empty($imovel['codigo_produto']) ? $imovel['codigo_produto'] : null;
        $dadosSessao['usuario']['avatar_base64']  = $this->getFileImagem($usuario['diretorio_avatar']);
        $dadosSessao['usuario']['data_login']     = date('Y-m-d H:i');
        $dadosSessao['empresa']                   = $empresa;
        $dadosSessao['menus']                     = $cadastroMenuModel->getGrupoMenu($empresa['codigo_cadastro_grupo']);

        // Grava a Sessão
        $this->nativeSession->set($dadosSessao);

        // Grava o Sucesso no Login
        $this->_logLogin("t", null, $usuario['uuid_usuario'], $this->request);

        // Redireciona para a tela Inicial
        return redirect()->to(base_url('dashboard'));
    }

    /**
     * Grava o LOG do Login
     * @param string  $sucesso   Indica se a Tentativa falhou
     * @param string  $mensagem  Mensagem adicional
     * @param integer $userUuid  UUID do Usuario que realizou a tentativa
     */
    private function _logLogin(string $sucesso = "t", $mensagem = null, $userUuid = null, $request = null)
    {
        try {
            $logLoginModel = new LogLoginModel;
            $agent = $request->getUserAgent();

            $logLogin = [
                'usuario'           => $userUuid,
                'sucesso_tentativa' => $sucesso,
                'input'             => !empty($request->getVar()['email']) ? $request->getVar()['email'] : $request->getVar()['telefone'],
                'ip'                => $request->getIPAddress(),
                'user_agent'        => $agent->getBrowser() . ' ' . $agent->getVersion() . ' ' . $agent->getPlatform(),
                'motivo'            => $mensagem,
            ];
            $logLoginModel->save($logLogin);
        } catch (Exception $e) {
        }
    }
}
