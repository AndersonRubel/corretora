<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\RedirectResponse;

use Exception;
use Firebase\JWT\ExpiredException;
use App\ThirdParty\CiAuthJwt\Jwt;

use App\Models\Cadastro\CadastroGrupoModel;
use App\Models\Empresa\EmpresaModel;
use App\Models\Empresa\EmpresaUsuarioModel;
use App\Models\Usuario\UsuarioModel;

class UsuarioController extends BaseController
{
    //////////////////////////////////
    //                              //
    //      OPERAÇÕES DE BUSCA      //
    //                              //
    //////////////////////////////////

    /**
     * Exibe a Tela de Usuario
     * @return html
     */
    public function index()
    {
        return $this->template('usuario', ['index', 'functions']);
    }

    /**
     * Exibe a Tela de Perfil
     * @return html
     */
    public function indexPerfil()
    {
        $usuarioModel = new UsuarioModel;
        $codigoUsuario = $this->nativeSession->get("usuario")['codigo_usuario'];

        $colunas = ['nome', 'email', 'celular'];
        $dados['usuario'] = $usuarioModel->get(['codigo_usuario' => $codigoUsuario], $colunas, true);
        return $this->template('usuario', ['profile', 'functions'], $dados);
    }

    /**
     * Exibe a Tela de Adicionar Registro
     * @return html
     */
    public function create()
    {
        return $this->template('usuario', ['create', 'functions']);
    }

    /**
     * Exibe a Tela de Alterar o Registro
     * @param string $uuid UUID do Registro
     * @return html
     */
    public function edit(string $uuid)
    {
        if (!$this->verificarUuid($uuid)) {
            $this->nativeSession->setFlashData('error', lang('Errors.geral.validaUuid'));
            return redirect()->to(base_url("usuario"));
        }

        $usuarioModel = new UsuarioModel;
        $colunas = [
            'uuid_usuario',
            'nome',
            'email',
            'celular',
            '(SELECT empresa_usuario.codigo_empresa
                FROM empresa_usuario
               WHERE empresa_usuario.codigo_usuario = usuario.codigo_usuario
            ) AS empresa_padrao',
            '(SELECT empresa_usuario.codigo_cadastro_grupo
                FROM empresa_usuario
               WHERE empresa_usuario.codigo_usuario = usuario.codigo_usuario
            ) AS grupo_acesso'
        ];
        $dados['usuario'] = $usuarioModel->get([$usuarioModel->uuidColumn => $uuid], $colunas, true);
        return $this->template('usuario', ['edit', 'functions'], $dados);
    }

    /**
     * Busca os registros para o Datagrid
     * @param int $status Verifica se a informação está ativa (1 ou 0)
     */
    public function getDataGrid(int $status)
    {
        $usuarioModel = new UsuarioModel;
        $dadosRequest = $this->request->getVar();
        $dadosRequest['status'] = $status;
        $data = $usuarioModel->getDataGrid($dadosRequest);
        return $this->responseDataGrid($data, $dadosRequest);
    }

    //////////////////////////////////
    //                              //
    //    OPERAÇÕES DE CADASTRO     //
    //                              //
    //////////////////////////////////

    /**
     * Realiza o Cadastro do Registro
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function store(): RedirectResponse
    {
        $usuarioModel = new UsuarioModel;
        $empresaUsuarioModel = new EmpresaUsuarioModel;
        $dadosRequest = convertEmptyToNull($this->request->getVar());
        $dadosUsuario = $this->nativeSession->get("usuario");
        $dadosSessaoEmpresa = $this->nativeSession->get("empresa");

        $erros = $this->validarRequisicao($this->request, [
            'nome' => 'required|string|min_length[3]|max_length[255]',
            'email' => 'required|valid_email|max_length[255]|is_unique[usuario.email]',
            'codigo_cadastro_grupo' => 'required|integer',
            'codigo_empresa_padrao' => 'permit_empty|integer',
            'celular' => [
                'rules' => 'permit_empty|checkTelefone',
                'errors' => ['checkTelefone' => 'Errors.geral.telefoneInvalido'],
            ],
            'confirmar_senha' => [
                'rules' => 'required|string|matches[senha]',
                'errors' => ['matches' => 'Errors.usuario.confirmaSenha'],
            ],
            'senha' => [
                'rules' => 'required|string|checkPassword|min_length[6]',
                'errors' => ['checkPassword' => 'Errors.usuario.senhaRegex'],
            ],
        ]);

        if (!empty($erros)) {
            $this->nativeSession->setFlashData('error', formataErros($erros));
            return redirect()->back()->withInput();
        }

        $empresa = [
            'usuario_criacao'       => $dadosUsuario['codigo_usuario'],
            'codigo_empresa_padrao' => !empty($dadosRequest['codigo_empresa_padrao']) ? $dadosRequest['codigo_empresa_padrao'] : $dadosSessaoEmpresa['codigo_empresa'],
            'nome'                  => $dadosRequest['nome'],
            'email'                 => $dadosRequest['email'],
            'celular'               => onlyNumber($dadosRequest['celular']),
            'senha'                 => password_hash($dadosRequest['senha'], PASSWORD_BCRYPT)
        ];

        //Inicia as operações de DB
        $this->db->transStart();
        try {
            $usuarioModel->save($empresa);
            $usuarioId = $usuarioModel->insertID('usuario_codigo_usuario_seq');

            // Cria o vinculo do usuario com a Empresa
            $empresaUsuario = [
                'usuario_criacao'       => $dadosUsuario['codigo_usuario'],
                'codigo_empresa'        => $dadosRequest['codigo_empresa_padrao'],
                'codigo_usuario'        => $usuarioId,
                'codigo_cadastro_grupo' => $dadosRequest['codigo_cadastro_grupo'],
            ];
            $empresaUsuarioModel->save($empresaUsuario);

            $this->db->transComplete();
            $this->nativeSession->setFlashData('success', lang('Success.default.cadastrado', ['Usuário']));
        } catch (Exception $e) {
            $this->nativeSession->setFlashData('error', lang('Errors.banco.validaInsercao'));
            return redirect()->back()->withInput();
        }

        return redirect()->to(base_url("usuario"));
    }

    /**
     * Altera o Registro
     * @param string $uuid UUID do Registro
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update(string $uuid): RedirectResponse
    {
        if (!$this->verificarUuid($uuid)) {
            $this->nativeSession->setFlashData('error', lang('Errors.geral.validaUuid'));
            return redirect()->to(base_url("usuario"));
        }

        $usuarioModel = new UsuarioModel;
        $empresaUsuarioModel = new EmpresaUsuarioModel;
        $dadosRequest = convertEmptyToNull($this->request->getVar());
        $dadosUsuario = $this->nativeSession->get("usuario");

        $erros = $this->validarRequisicao($this->request, [
            'nome' => 'required|string|min_length[3]|max_length[255]',
            'email' => 'required|valid_email|max_length[255]',
            'codigo_cadastro_grupo' => 'required|integer',
            'codigo_empresa_padrao' => 'permit_empty|integer',
            'celular' => [
                'rules' => 'permit_empty|checkTelefone',
                'errors' => ['checkTelefone' => 'Errors.geral.telefoneInvalido'],
            ]
        ]);

        if (!empty($erros)) {
            $this->nativeSession->setFlashData('error', formataErros($erros));
            return redirect()->back()->withInput();
        }

        // Busca o usuário
        $usuario = $usuarioModel->get(['uuid_usuario' => $uuid], ['codigo_usuario', 'uuid_usuario', 'email'], true);

        // Verifica se o email é diferente e se já existe no banco
        if ($dadosRequest['email'] !== $usuario['email']) {
            $emailExistente = $usuarioModel->get(['email' => $dadosRequest['email']], ['email']);

            if (!empty($emailExistente)) {
                $this->nativeSession->setFlashData('error', lang('Errors.geral.validaEmail'));
                return redirect()->back()->withInput();
            }
        }

        $usuarioUpdate = [
            'usuario_alteracao' => $dadosUsuario['codigo_usuario'],
            'alterado_em'       => "NOW()",
            'nome'              => $dadosRequest['nome'],
            'email'             => $dadosRequest['email'],
            'celular'           => onlyNumber($dadosRequest['celular']),
        ];

        //Inicia as operações de DB
        $this->db->transStart();
        try {
            $usuarioModel->where($usuarioModel->uuidColumn, $uuid)->set($usuarioUpdate)->update();

            // Altera o vinculo do usuario com a Empresa
            $empresaUsuario = [
                'usuario_alteracao'     => $dadosUsuario['codigo_usuario'],
                'alterado_em'           => "NOW()",
                'codigo_usuario'        => $usuario['codigo_usuario'],
                'codigo_cadastro_grupo' => $dadosRequest['codigo_cadastro_grupo'],
            ];

            $empresaUsuarioModel->where([
                'codigo_empresa' => $dadosRequest['codigo_empresa_padrao'],
                'codigo_usuario' => $usuario['codigo_usuario']
            ])->set($empresaUsuario)->update();

            $this->db->transComplete();
            $this->nativeSession->setFlashData('success', lang('Success.default.atualizado', ['Usuário']));
        } catch (Exception $e) {
            $this->nativeSession->setFlashData('error', lang('Errors.banco.validaUpdate'));
            return redirect()->back()->withInput();
        }

        return redirect()->to(base_url("usuario"));
    }

    /**
     * Altera o Perfil do usuário logado
     * @return \CodeIgniter\HTTP\Response
     */
    public function updateProfile(): Response
    {
        helper("file");
        $usuarioModel = new UsuarioModel;
        $dadosRequest = convertEmptyToNull($this->request->getVar());
        $dadosUsuario = $this->nativeSession->get("usuario");

        $erros = $this->validarRequisicao($this->request, [
            'nome' => 'required|string|min_length[3]|max_length[255]',
            'email' => 'required|valid_email|max_length[255]',
            'avatar' => 'permit_empty|string',
            'avatar_nome' => 'permit_empty|string|max_length[255]',
            'celular' => [
                'rules' => 'permit_empty|string|checkTelefone',
                'errors' => ['checkTelefone' => 'Errors.geral.telefoneInvalido'],
            ],
            'senha_anterior' => [
                'rules' => 'permit_empty|string|checkPassword|min_length[6]',
                'errors' => ['checkPassword' => 'Errors.usuario.senhaRegex'],
            ],
            'confirmar_senha' => [
                'rules' => 'permit_empty|string|matches[senha]',
                'errors' => ['matches' => 'Errors.usuario.confirmaSenha'],
            ],
            'senha' => [
                'rules' => 'permit_empty|string|checkPassword|min_length[6]',
                'errors' => ['checkPassword' => 'Errors.usuario.senhaRegex'],
            ],
        ]);

        if (!empty($erros)) {
            $this->nativeSession->setFlashData('error', formataErros($erros));
            return redirect()->to(base_url("perfil"));
        }

        // Busca os dados do Usuário Logado
        $usuario = $usuarioModel->get([$usuarioModel->primaryKey => $dadosUsuario['codigo_usuario']], ['email', 'senha'], true);

        if (empty($usuario)) {
            $this->nativeSession->setFlashData('error', lang('Errors.usuario.buscaUsuario'));
            return redirect()->to(base_url("perfil"));
        }

        // Verifica se o email foi alterado e se já existe no banco
        if ($dadosRequest['email'] !== $usuario['email']) {
            $emailExistente = $usuarioModel->get(['email' => $dadosRequest['email']], ['email']);

            if (!empty($emailExistente)) {
                $this->nativeSession->setFlashData('error', lang('Errors.geral.validaEmail'));
                return redirect()->to(base_url("perfil"));
            }
        }

        $usuarioUpdate = [
            'usuario_alteracao' => $dadosUsuario['codigo_usuario'],
            'alterado_em'       => "NOW()",
            'nome'              => $dadosRequest['nome'],
            'email'             => $dadosRequest['email']
        ];

        // Verifica se a Senha digitada é a mesma que foi enviada no formulario
        if (array_key_exists('senha_anterior', $dadosRequest) && !empty($dadosRequest['senha_anterior'])) {
            if (!password_verify($dadosRequest['senha_anterior'], $usuario['senha'])) {
                $this->nativeSession->setFlashData('error', lang("Errors.usuario.senhaAntigaNaoBate"));
                return redirect()->to(base_url("perfil"));
            }

            $usuarioUpdate['senha'] = password_hash($dadosRequest['senha'], PASSWORD_BCRYPT);
        }

        // Verifica se veio o celular
        if (!empty($dadosRequest['celular'])) {
            $usuarioUpdate['celular'] = onlyNumber($dadosRequest['celular']);
        }

        // Verifica se veio o Avatar
        if (!empty($dadosRequest['avatar'])) {
            $fileStats = verificaDocumento($dadosRequest['avatar'], true);

            if (empty($fileStats) || !$fileStats['size']) {
                $this->nativeSession->setFlashData('error', lang("Errors.usuario.avatarInvalido"));
                return redirect()->to(base_url("perfil"));
            }

            // Realiza o Upload do arquivo
            $nomeDocumento = "usuario_avatar/" . encryptFileName($dadosRequest['avatar_nome']);
            $retornoEnvio = $this->putFileObject($nomeDocumento, $dadosRequest['avatar']);

            if (empty($retornoEnvio)) {
                $this->nativeSession->setFlashData('error', lang("Errors.geral.erroUpload"));
                return redirect()->to(base_url("perfil"));
            }

            $usuarioUpdate['diretorio_avatar'] = $nomeDocumento;
        }

        //Inicia as operações de DB
        $this->db->transStart();
        try {
            // Atualiza o usuario
            $usuarioModel->where($usuarioModel->primaryKey, $dadosUsuario['codigo_usuario'])->set($usuarioUpdate)->update();

            // Atualiza a Sessão
            $colunas = ['codigo_usuario', 'uuid_usuario', 'codigo_empresa_padrao', 'nome', 'email', 'celular', 'diretorio_avatar'];
            $usuario = $usuarioModel->get(["codigo_usuario" => $dadosUsuario['codigo_usuario']], $colunas, true);
            $dadosSessao['usuario']                  = $usuario;
            $dadosSessao['usuario']['avatar_base64'] = $this->getFileImagem($dadosSessao['usuario']['diretorio_avatar']);
            $this->nativeSession->set($dadosSessao);

            $this->db->transComplete();

            $this->nativeSession->setFlashData('success', lang('Success.default.atualizado', ['Usuário']));
        } catch (Exception $e) {
            $this->nativeSession->setFlashData('error', lang('Errors.banco.validaUpdate'));
        }

        return redirect()->to(base_url("perfil"));
    }

    /**
     * Ativa um Registro
     * @param string $uuid Uuid do Registro
     * @return \CodeIgniter\HTTP\Response
     */
    public function enable(string $uuid): Response
    {
        if (!$this->verificarUuid($uuid)) {
            return $this->response->setJSON(['mensagem' => lang('Errors.geral.validaUuid')], 400);
        }

        $dadosUsuario = $this->nativeSession->get("usuario");
        $usuarioModel = new UsuarioModel;

        $usuario = [
            'alterado_em'        => "NOW()",
            'usuario_alteracao'  => $dadosUsuario['codigo_usuario'],
            'inativado_em'       => null,
            'usuario_inativacao' => null
        ];

        try {
            $usuarioModel->where($usuarioModel->uuidColumn, $uuid)->set($usuario)->update();
        } catch (Exception $e) {
            return $this->response->setJSON(['mensagem' => lang('Errors.banco.validaUpdate')], 422);
        }

        return $this->response->setJSON(['mensagem' => lang('Success.default.ativado', ['Usuário'])], 202);
    }

    /**
     * Desativa um Registro
     * @param string $uuid Uuid do Registro
     * @return \CodeIgniter\HTTP\Response
     */
    public function disable(string $uuid): Response
    {
        if (!$this->verificarUuid($uuid)) {
            return $this->response->setJSON(['mensagem' => lang('Errors.geral.validaUuid')], 400);
        }

        $dadosUsuario = $this->nativeSession->get("usuario");
        $usuarioModel = new UsuarioModel;

        try {
            $usuarioModel->customSoftDelete($uuid, $dadosUsuario['codigo_usuario'], true);
        } catch (Exception $e) {
            return $this->response->setJSON(['mensagem' => lang('Errors.banco.validaUpdate')], 422);
        }

        return $this->response->setJSON(['mensagem' => lang('Success.default.inativado', ['Usuário'])], 202);
    }

    /**
     * Realiza as chamadas assincronas direto para a Model
     * @param string $function
     */
    public function backendCall(string $function)
    {
        try {
            $request = $this->request->getVar();
            return $this->response->setJSON((new UsuarioModel)->$function($request));
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    /**
     * Habilita a Recuperação de Senha para um usuário
     * @return \CodeIgniter\HTTP\Response
     */
    public function habilitaRecuperacaoSenha(): Response
    {
        $usuarioModel = new UsuarioModel;
        $dadosRequest = convertEmptyToNull($this->request->getVar());

        $erros = $this->validarRequisicao($this->request, [
            'email' => 'required|valid_email|max_length[255]',
        ]);

        if (!empty($erros)) {
            return $this->response->setJSON(['mensagem' => formataErros($erros)], 422);
        }

        $usuario = $usuarioModel->get(['email' => $dadosRequest['email']], ['uuid_usuario', 'nome', 'email_recover'], true);

        if (empty($usuario)) {
            return $this->response->setJSON(['mensagem' => lang('Errors.usuario.emailNaoEncontrado')], 422);
        }

        //Inicia as operações de DB
        $this->db->transStart();
        try {

            // Cria um JWT de recuperação
            $payloadAdicional = ['email' => $dadosRequest['email']];
            $jwt = Jwt::encode($payloadAdicional, 900); // 900 = 15 minutos

            // Adiciona o Token no registro do usuario
            $usuarioModel
                ->where("uuid_usuario", $usuario['uuid_usuario'])
                ->set(['email_recover' => $jwt['token']])
                ->update();

            // Envia o Email
            $dadosEmailView = [
                'email' => $dadosRequest['email'],
                'nome' => $usuario['nome'],
                'link' => base_url("alterar-senha/{$jwt['token']}")
            ];
            $mensagem = view('mail/recover-password.php', $dadosEmailView);
            $enviado = $this->enviarEmail($dadosRequest['email'], 'Recuperação de Senha', $mensagem);

            $this->db->transComplete();

            if ($enviado) {
                return $this->response->setJSON(['mensagem' => $enviado]);
            }
        } catch (Exception $e) {
            return $this->response->setJSON(['mensagem' => lang('Errors.banco.validaUpdate')], 422);
        }
    }

    /**
     * Exibe a Tela e valida o Token de Recuperação
     * @return \CodeIgniter\HTTP\Response
     */
    public function recuperarSenha(string $token)
    {
        $usuarioModel = new UsuarioModel;
        $usuario = $usuarioModel->get(['email_recover' => $token], ['codigo_usuario'], true);

        // Verifica se tem alguem com esse token no banco
        if (empty($usuario)) {
            $this->nativeSession->setFlashData('error', lang('Errors.usuario.tokenSenhaInvalido'));
            return redirect()->to(base_url("login"));
        }

        // Valida se o JWT ainda é valido
        try {
            $jwt = (array) Jwt::decode($token);
        } catch (ExpiredException $expiredException) {
            $usuarioModel->where("codigo_usuario", $usuario['codigo_usuario'])->set(['email_recover' => null])->update();
            $this->nativeSession->setFlashData('error', lang('Errors.usuario.tokenSenhaInvalido'));
            return redirect()->to(base_url("login"));
        }

        // Exibe a Tela
        $dados['token'] = $token;
        return $this->template('login', ['recover-password', 'functions'], $dados, false, false);
    }

    /**
     * Alterar Senha atraves da Recuperação
     */
    public function alterarSenha()
    {
        $usuarioModel = new UsuarioModel;
        $dadosRequest = convertEmptyToNull($this->request->getVar());

        $erros = $this->validarRequisicao($this->request, [
            'token' => 'required|string',
            'confirmar_senha' => [
                'rules' => 'required|string|matches[senha]',
                'errors' => ['matches' => 'Errors.usuario.confirmaSenha'],
            ],
            'senha' => [
                'rules' => 'required|string|checkPassword|min_length[6]',
                'errors' => ['checkPassword' => 'Errors.usuario.senhaRegex'],
            ],
        ]);

        if (!empty($erros)) {
            $this->nativeSession->setFlashData('error', formataErros($erros));
            return redirect()->back()->withInput();
        }

        $usuarioUpdate = [
            'senha'         => password_hash($dadosRequest['senha'], PASSWORD_BCRYPT),
            'email_recover' => null
        ];

        //Inicia as operações de DB
        $this->db->transStart();
        try {
            $usuarioModel->where('email_recover', $dadosRequest['token'])->set($usuarioUpdate)->update();
            $this->db->transComplete();
            $this->nativeSession->setFlashData('success', lang('Success.usuario.redefinirSenha'));
        } catch (Exception $e) {
            $this->nativeSession->setFlashData('error', lang('Errors.banco.validaUpdate'));
        }

        return redirect()->to(base_url("login"));
    }
}
