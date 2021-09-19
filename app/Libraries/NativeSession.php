<?php

namespace App\Libraries;

class NativeSession
{
    protected $session;
    protected $sessName;
    protected $sessExpireTime;

    public function __construct($instanciaSecundaria = false)
    {
        // Init Session
        $this->session        = session();
        $this->sessName       = env('security.sessionName');
        $this->sessExpireTime = env('security.expires'); // 4Horas

        // Verifica se a Sessão é valida
        if (isset($_SESSION[$this->sessName]) && $_SESSION[$this->sessName]) {
            if (!$this->_verifyExpireTime()) {
                $this->destroy();
            } else {
                // Na Instancia Secundária não realiza Validações
                if (!$instanciaSecundaria) {
                    // Verifica o FlashData
                    if (isset($_SESSION[$this->sessName]['keep_flash']) && $_SESSION[$this->sessName]['keep_flash']) {
                        $_SESSION[$this->sessName]['keep_flash'] = false;
                    } else if (isset($_SESSION[$this->sessName]['flashdata']) && $_SESSION[$this->sessName]['flashdata']) {
                        // Destrói o FlashData
                        $_SESSION[$this->sessName]['flashdata']  = [];
                        $_SESSION[$this->sessName]['keep_flash'] = false;
                    }
                }
            }
        }

        // Verifica se existe a sessão ainda
        if (!isset($_SESSION[$this->sessName]) || !$_SESSION[$this->sessName]) {
            $_SESSION[$this->sessName]               = [];
            $_SESSION[$this->sessName]['expire']     = time() + $this->sessExpireTime;
            $_SESSION[$this->sessName]['flashdata']  = [];
            $_SESSION[$this->sessName]['keep_flash'] = false;
        }
    }


    //////////////////////////////////
    //                              //
    //           SESSÃO             //
    //                              //
    //////////////////////////////////

    /**
     *
     */
    private function _verifyExpireTime2()
    {
        header("Sessao-Expirando: true"); // Avisa se a Sessão esta expirando
        header("Sessao-Expirando-Tempo: 5"); // Avisa quanto tempo tem de sessão ainda
    }

    /**
     * Verifica se a Sessão está expirada
     * @return bool
     */
    private function _verifyExpireTime(): bool
    {
        if ((time() - $_SESSION[$this->sessName]['expire']) < $this->sessExpireTime) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Inclui os dados na Sessão
     * @param string|array $chave
     * @param string $valor
     */
    public function set($chave, $valor = null)
    {
        if ($chave) {
            if (is_array($chave)) {
                foreach ($chave as $key => $value) {
                    $_SESSION[$this->sessName][$key] = $value;
                }
            } else {
                $_SESSION[$this->sessName][$chave] = $valor;
            }
            return true;
        } else {
            return null;
        }
    }

    /**
     * Busca uma Sessão
     * @param string $key
     */
    public function get($key)
    {
        if (isset($_SESSION[$this->sessName][$key])) {
            return $_SESSION[$this->sessName][$key];
        } else {
            return null;
        }
    }

    /**
     * Busca Todas as Sessões Não expiradas
     */
    public function allSession()
    {
        if ($this->_verifyExpireTime()) {
            return $_SESSION[$this->sessName];
        } else {
            $this->destroy();
            return false;
        }
    }

    /**
     *  Destrói a Sessão
     */
    public function destroy()
    {
        $_SESSION = [];
        // session_destroy();
        // $this->session->destroy();
        return redirect()->to(base_url());
    }

    //////////////////////////////////
    //                              //
    //          FLASHDATA           //
    //                              //
    //////////////////////////////////

    /**
     * Busca um FlashData
     * @param string $key
     */
    public function getFlashdata(string $key)
    {
        if (isset($_SESSION[$this->sessName]['flashdata'][$key])) {
            return $_SESSION[$this->sessName]['flashdata'][$key];
        } else {
            return null;
        }
    }

    /**
     * Cria os retornos flash do sistema
     * @param string $tipo  success, error, info
     * @param string $mensagem
     * @param string $chave Nome da variavel
     */
    public function setFlashData(string $tipo = 'info', string $mensagem = '', string $chave = 'responseFlash')
    {
        if ($chave) {
            if (is_array($chave)) {
                foreach ($chave as $key => $value) {
                    $_SESSION[$this->sessName]['flashdata'][$key] = $value;
                }
            } else {
                $_SESSION[$this->sessName]['flashdata'][$chave] = ['tipo' => $tipo, 'mensagem' => $mensagem];
            }

            // Informa que deve manter o FlashData salvo
            $_SESSION[$this->sessName]['keep_flash'] = true;

            return true;
        } else {
            return null;
        }
    }

    //////////////////////////////////
    //                              //
    //      OUTRAS OPERAÇÕES        //
    //                              //
    //////////////////////////////////

    /**
     * Identifica se o Usuario logado é Super Admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->get("empresa")['codigo_cadastro_grupo'] == 1 ? true : false;
    }
}
