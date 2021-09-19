<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\RedirectResponse;
use Exception;

use App\Models\Cadastro\CadastroGrupoModel;
use App\Models\Empresa\EmpresaUsuarioModel;
use App\Models\Usuario\UsuarioModel;
use App\Models\Vendedor\VendedorModel;

class VendedorController extends BaseController
{
    //////////////////////////////////
    //                              //
    //      OPERAÇÕES DE BUSCA      //
    //                              //
    //////////////////////////////////

    /**
     * Exibe a Tela de Vendedor
     * @return html
     */
    public function index()
    {
        return $this->template('vendedor', ['index', 'functions']);
    }

    /**
     * Exibe a Tela de Adicionar Registro
     * @return html
     */
    public function create()
    {
        return $this->template('vendedor', ['create', 'functions']);
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
            return redirect()->to(base_url("vendedor"));
        }

        $vendedorModel = new VendedorModel;
        $usuarioModel = new UsuarioModel;
        $dados['vendedor'] = $vendedorModel->get([$vendedorModel->uuidColumn => $uuid], [], true);

        $usuario = $usuarioModel->get(['codigo_vendedor' => $dados['vendedor']['codigo_vendedor']], ['email'], true);
        $dados['vendedor']['email'] = !empty($usuario['email']) ? $usuario['email'] : '';

        // Desestrutura os JSONB para preencher os campos no formulário
        $dados['vendedor']['endereco'] = json_decode($dados['vendedor']['endereco'], true);

        return $this->template('vendedor', ['edit', 'functions'], $dados);
    }

    /**
     * Exibe a Tela de Visualizar o Registro
     * @param string $uuid UUID do Registro
     * @return html
     */
    public function view(string $uuid)
    {
        if (!$this->verificarUuid($uuid)) {
            $this->nativeSession->setFlashData('error', lang('Errors.geral.validaUuid'));
            return redirect()->to(base_url("vendedor"));
        }

        $vendedorModel = new VendedorModel;
        $dados['vendedor'] = $vendedorModel->get([$vendedorModel->uuidColumn => $uuid], [], true);
        $dados['indicadores'] = [];

        return $this->template('vendedor', ['view', 'functions'], $dados);
    }

    /**
     * Busca os registros para o Datagrid
     * @param int $status Verifica se a informação está ativa (1 ou 0)
     */
    public function getDataGrid(int $status)
    {
        try {
            $vendedorModel = new VendedorModel;
            $dadosRequest = $this->request->getVar();
            $dadosRequest['status'] = $status;
            $data = $vendedorModel->getDataGrid($dadosRequest);
            return $this->responseDataGrid($data, $dadosRequest);
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    /**
     * Busca os registros para o DatagridExtrato
     */
    public function getDataGridEstoque()
    {
        try {
            $vendedorModel = new VendedorModel;
            $dadosRequest = $this->request->getVar();
            $data = $vendedorModel->getDataGridEstoque($dadosRequest);
            return $this->responseDataGrid($data, $dadosRequest);
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    /**
     * Busca os registros para o getDataGridHistoricoVenda
     */
    public function getDataGridHistoricoVenda()
    {
        try {
            $vendedorModel = new VendedorModel;
            $dadosRequest = $this->request->getVar();
            $data = $vendedorModel->getDataGridHistoricoVenda($dadosRequest);
            return $this->responseDataGrid($data, $dadosRequest);
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    /**
     * Busca os registros para o DatagridExtrato
     */
    public function getDataGridHistoricoFinanceiro()
    {
        try {
            $vendedorModel = new VendedorModel;
            $dadosRequest = $this->request->getVar();
            $data = $vendedorModel->getDataGridHistoricoFinanceiro($dadosRequest);
            return $this->responseDataGrid($data, $dadosRequest);
        } catch (Exception $e) {
            var_dump($e);
        }
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
        $vendedorModel = new VendedorModel;
        $usuarioModel = new UsuarioModel;
        $cadastroGrupoModel = new CadastroGrupoModel;
        $empresaUsuarioModel = new EmpresaUsuarioModel;
        $dadosRequest = convertEmptyToNull($this->request->getVar());
        $dadosUsuario = $this->nativeSession->get("usuario");
        $dadosEmpresa = $this->nativeSession->get("empresa");

        $erros = $this->validarRequisicao($this->request, [
            'tipo_pessoa' => 'required|integer|in_list[1,2]',
            'razao_social' => 'permit_empty|string|min_length[3]|max_length[255]',
            'nome_fantasia' => 'required|string|min_length[3]|max_length[255]',
            'cpf_cnpj' => 'required|string|min_length[11]|max_length[18]',
            'observacao' => 'permit_empty|string',
            'data_nascimento' => 'permit_empty|valid_date',
            'telefone' => [
                'rules' => 'permit_empty|checkTelefone',
                'errors' => ['checkTelefone' => 'Errors.geral.telefoneInvalido'],
            ],
            'celular' => [
                'rules' => 'permit_empty|checkTelefone',
                'errors' => ['checkTelefone' => 'Errors.geral.telefoneInvalido'],
            ],
            'cep' => [
                'rules' => 'permit_empty|checkCep',
                'errors' => ['checkCep' => 'Errors.geral.cepInvalido'],
            ],
            'rua' => 'permit_empty|string|max_length[255]',
            'numero' => 'permit_empty|integer|max_length[255]',
            'bairro' => 'permit_empty|string|max_length[255]',
            'complemento' => 'permit_empty|string|max_length[255]',
            'cidade' => 'permit_empty|string|max_length[255]',
            'uf' => 'permit_empty|string|max_length[255]',
            'email' => 'required|valid_email|max_length[255]|is_unique[usuario.email]',
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

        // Valida se o Email já não esta em uso
        $emailExistente = $usuarioModel->get(['email' => $dadosRequest['email']], ['email']);

        if (!empty($emailExistente)) {
            $this->nativeSession->setFlashData('error', lang('Errors.geral.validaEmail'));
            return redirect()->back()->withInput();
        }

        // Busca o Código do Grupo de Acesso
        $grupo = $cadastroGrupoModel->get(['slug' => 'vendedor'], ['codigo_cadastro_grupo'], true);

        // JSONB de Dados do Endereço
        $vendedorEndereco = [
            'cep'         => !empty($dadosRequest['cep'])         ? onlyNumber($dadosRequest['cep'])    : '',
            'rua'         => !empty($dadosRequest['rua'])         ? $dadosRequest['rua']                : '',
            'numero'      => !empty($dadosRequest['numero'])      ? onlyNumber($dadosRequest['numero']) : '',
            'bairro'      => !empty($dadosRequest['bairro'])      ? $dadosRequest['bairro']             : '',
            'complemento' => !empty($dadosRequest['complemento']) ? $dadosRequest['complemento']        : '',
            'cidade'      => !empty($dadosRequest['cidade'])      ? $dadosRequest['cidade']             : '',
            'uf'          => !empty($dadosRequest['uf'])          ? $dadosRequest['uf']                 : ''
        ];

        $vendedor = [
            'codigo_empresa'  => $dadosEmpresa['codigo_empresa'],
            'usuario_criacao' => $dadosUsuario['codigo_usuario'],
            'tipo_pessoa'     => onlyNumber($dadosRequest['tipo_pessoa']),
            'nome_fantasia'   => $dadosRequest['nome_fantasia'],
            'razao_social'    => $dadosRequest['razao_social'],
            'cpf_cnpj'        => onlyNumber($dadosRequest['cpf_cnpj']),
            'telefone'        => onlyNumber($dadosRequest['telefone']),
            'celular'         => onlyNumber($dadosRequest['celular']),
            'email'           => $dadosRequest['email'],
            'observacao'      => $dadosRequest['observacao'],
            'data_nascimento' => $dadosRequest['data_nascimento'],
            'endereco'        => !empty($vendedorEndereco) ? json_encode($vendedorEndereco) : null,
        ];

        //Inicia as operações de DB
        $this->db->transStart();
        try {
            $vendedorModel->save($vendedor);
            $vendedorId = $vendedorModel->insertID('vendedor_codigo_vendedor_seq');

            // Gera um usuário para o Vendedor
            $usuario = [
                'usuario_criacao'       => $dadosUsuario['codigo_usuario'],
                'codigo_empresa_padrao' => $dadosEmpresa['codigo_empresa'],
                'codigo_vendedor'       => $vendedorId,
                'nome'                  => $vendedor['razao_social'],
                'email'                 => $dadosRequest['email'],
                'celular'               => onlyNumber($dadosRequest['celular']),
                'senha'                 => password_hash($dadosRequest['senha'], PASSWORD_BCRYPT)
            ];
            $usuarioModel->save($usuario);
            $usuarioId = $usuarioModel->insertID('usuario_codigo_usuario_seq');

            // Cria o vinculo do usuario com a Empresa
            $empresaUsuario = [
                'usuario_criacao'       => $dadosUsuario['codigo_usuario'],
                'codigo_empresa'        => $dadosEmpresa['codigo_empresa'],
                'codigo_usuario'        => $usuarioId,
                'codigo_cadastro_grupo' => $grupo['codigo_cadastro_grupo'],
            ];
            $empresaUsuarioModel->save($empresaUsuario);

            $this->db->transComplete();
            $this->nativeSession->setFlashData('success', lang('Success.default.cadastrado', ['Vendedor']));
        } catch (Exception $e) {
            $this->nativeSession->setFlashData('error', lang('Errors.banco.validaInsercao'));
            return redirect()->back()->withInput();
        }

        return redirect()->to(base_url("vendedor"));
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
            return redirect()->to(base_url("vendedor"));
        }

        $vendedorModel = new VendedorModel;
        $usuarioModel = new UsuarioModel;
        $dadosRequest = convertEmptyToNull($this->request->getVar());
        $dadosUsuario = $this->nativeSession->get("usuario");

        $erros = $this->validarRequisicao($this->request, [
            'razao_social' => 'permit_empty|string|min_length[3]|max_length[255]',
            'nome_fantasia' => 'required|string|min_length[3]|max_length[255]',
            'cpf_cnpj' => 'required|string|min_length[11]|max_length[18]',
            'observacao' => 'permit_empty|string',
            'data_nascimento' => 'permit_empty|valid_date',
            'telefone' => [
                'rules' => 'permit_empty|checkTelefone',
                'errors' => ['checkTelefone' => 'Errors.geral.telefoneInvalido'],
            ],
            'celular' => [
                'rules' => 'permit_empty|checkTelefone',
                'errors' => ['checkTelefone' => 'Errors.geral.telefoneInvalido'],
            ],
            'cep' => [
                'rules' => 'permit_empty|checkCep',
                'errors' => ['checkCep' => 'Errors.geral.cepInvalido'],
            ],
            'rua' => 'permit_empty|string|max_length[255]',
            'numero' => 'permit_empty|integer|max_length[255]',
            'bairro' => 'permit_empty|string|max_length[255]',
            'complemento' => 'permit_empty|string|max_length[255]',
            'cidade' => 'permit_empty|string|max_length[255]',
            'uf' => 'permit_empty|string|max_length[255]',
            'email' => 'required|valid_email|max_length[255]',
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
            return redirect()->back()->withInput();
        }

        // Busca o Código do Vendedor
        $vendedor = $vendedorModel->get(['uuid_vendedor' => $uuid], ['codigo_vendedor', 'uuid_vendedor'], true);

        if (empty($vendedor)) {
            $this->nativeSession->setFlashData('error', lang('Errors.geral.registroNaoEncontrado'));
            return redirect()->to(base_url("vendedor"));
        }

        // Busca o usuário
        $usuario = $usuarioModel->get(['codigo_vendedor' => $vendedor['codigo_vendedor']], ['codigo_usuario', 'uuid_usuario', 'email'], true);

        if (!empty($usuario)) {
            // Verifica se o email é diferente e se já existe no banco
            if ($dadosRequest['email'] !== $usuario['email']) {
                $emailExistente = $usuarioModel->get(['email' => $dadosRequest['email']], ['email']);

                if (!empty($emailExistente)) {
                    $this->nativeSession->setFlashData('error', lang('Errors.geral.validaEmail'));
                    return redirect()->back()->withInput();
                }
            }
        }

        // JSONB de Dados do Endereço
        $vendedorEndereco = [
            'cep'         => !empty($dadosRequest['cep'])         ? onlyNumber($dadosRequest['cep'])    : '',
            'rua'         => !empty($dadosRequest['rua'])         ? $dadosRequest['rua']                : '',
            'numero'      => !empty($dadosRequest['numero'])      ? onlyNumber($dadosRequest['numero']) : '',
            'bairro'      => !empty($dadosRequest['bairro'])      ? $dadosRequest['bairro']             : '',
            'complemento' => !empty($dadosRequest['complemento']) ? $dadosRequest['complemento']        : '',
            'cidade'      => !empty($dadosRequest['cidade'])      ? $dadosRequest['cidade']             : '',
            'uf'          => !empty($dadosRequest['uf'])          ? $dadosRequest['uf']                 : ''
        ];

        $vendedorUpdate = [
            'usuario_alteracao' => $dadosUsuario['codigo_usuario'],
            'data_alteracao'    => "NOW()",
            'nome_fantasia'     => $dadosRequest['nome_fantasia'],
            'razao_social'      => !empty($dadosRequest['razao_social']) ? $dadosRequest['razao_social'] : $dadosRequest['nome_fantasia'],
            'cpf_cnpj'          => onlyNumber($dadosRequest['cpf_cnpj']),
            'telefone'          => onlyNumber($dadosRequest['telefone']),
            'celular'           => onlyNumber($dadosRequest['celular']),
            'email'             => $dadosRequest['email'],
            'observacao'        => $dadosRequest['observacao'],
            'data_nascimento'   => $dadosRequest['data_nascimento'],
            'endereco'          => !empty($vendedorEndereco) ? json_encode($vendedorEndereco) : null,
        ];

        //Inicia as operações de DB
        $this->db->transStart();
        try {
            $vendedorModel->where($vendedorModel->uuidColumn, $uuid)->set($vendedorUpdate)->update();

            if (!empty($usuario)) {
                // Começa a atualização do usuário
                $usuarioUpdate = [
                    'usuario_alteracao' => $dadosUsuario['codigo_usuario'],
                    'alterado_em'       => "NOW()",
                    'nome'              => $dadosRequest['nome_fantasia'],
                    'email'             => $dadosRequest['email'],
                    'senha'             => password_hash($dadosRequest['senha'], PASSWORD_BCRYPT)
                ];

                // Atualiza o usuario
                $usuarioModel->where($usuarioModel->primaryKey, $usuario['codigo_usuario'])->set($usuarioUpdate)->update();
            }

            $this->db->transComplete();
            $this->nativeSession->setFlashData('success', lang('Success.default.atualizado', ['Vendedor']));
        } catch (Exception $e) {
            $this->nativeSession->setFlashData('error', lang('Errors.banco.validaUpdate'));
            return redirect()->back()->withInput();
        }

        return redirect()->to(base_url("vendedor"));
    }

    /**
     * Ativa um Registro
     * @param string $uuid Uuid do Registro
     * @return \CodeIgniter\HTTP\Response
     */
    public function enable(string $uuid): Response
    {
        $dadosUsuario = $this->nativeSession->get("usuario");
        $vendedorModel = new VendedorModel;

        if (!$this->verificarUuid($uuid)) {
            return $this->response->setJSON(['mensagem' => lang('Errors.geral.validaUuid')], 400);
        }

        $dadosVendedor = [
            'alterado_em'        => "NOW()",
            'usuario_alteracao'  => $dadosUsuario['codigo_usuario'],
            'inativado_em'       => null,
            'usuario_inativacao' => null
        ];

        try {
            $vendedorModel->where($vendedorModel->uuidColumn, $uuid)->set($dadosVendedor)->update();
        } catch (Exception $e) {
            return $this->response->setJSON(['mensagem' => lang('Errors.banco.validaUpdate')], 422);
        }

        return $this->response->setJSON(['mensagem' => lang('Success.default.ativado', ['Vendedor'])], 202);
    }

    /**
     * Desativa um Registro
     * @param string $uuid Uuid do Registro
     * @return \CodeIgniter\HTTP\Response
     */
    public function disable(string $uuid): Response
    {
        $dadosUsuario = $this->nativeSession->get("usuario");
        $vendedorModel = new VendedorModel;

        if (!$this->verificarUuid($uuid)) {
            return $this->response->setJSON(['mensagem' => lang('Errors.geral.validaUuid')], 400);
        }

        try {
            $vendedorModel->customSoftDelete($uuid, $dadosUsuario['codigo_usuario'], true);
        } catch (Exception $e) {
            return $this->response->setJSON(['mensagem' => lang('Errors.banco.validaUpdate')], 422);
        }

        return $this->response->setJSON(['mensagem' => lang('Success.default.inativado', ['Vendedor'])], 202);
    }

    /**
     * Realiza as chamadas assincronas direto para a Model
     * @param string $function
     */
    public function backendCall(string $function)
    {
        try {
            $request = $this->request->getVar();
            return $this->response->setJSON((new VendedorModel)->$function($request));
        } catch (Exception $e) {
            var_dump($e);
        }
    }
}
