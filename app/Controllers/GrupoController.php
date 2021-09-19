<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\RedirectResponse;
use Exception;

use App\Models\Cadastro\CadastroGrupoModel;
use App\Models\Cadastro\CadastroMenuModel;
use App\Models\Empresa\EmpresaModel;
use App\Models\Usuario\UsuarioGrupoMenuModel;
use App\Models\Usuario\UsuarioGrupoRelatorioModel;

class GrupoController extends BaseController
{
    //////////////////////////////////
    //                              //
    //      OPERAÇÕES DE BUSCA      //
    //                              //
    //////////////////////////////////

    /**
     * Exibe a Tela de Grupo
     * @return html
     */
    public function index()
    {
        return $this->template('grupo', ['index', 'functions']);
    }

    /**
     * Exibe a Tela de Adicionar Registro
     * @return html
     */
    public function create()
    {
        $cadastroMenuModel = new CadastroMenuModel;
        $dados['menu'] = $cadastroMenuModel->get([], [], false, ['nome' => 'ASC']);
        return $this->template('grupo', ['create', 'functions'], $dados);
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
            return redirect()->to(base_url("grupo"));
        }

        $cadastroGrupoModel = new CadastroGrupoModel;
        $cadastroMenuModel = new CadastroMenuModel;
        $usuarioGrupoMenuModel = new UsuarioGrupoMenuModel;

        $colunasGrupo = [
            "codigo_cadastro_grupo",
            "uuid_cadastro_grupo",
            "codigo_empresa",
            "nome",
            "slug",
            "(SELECT array_to_string(array_agg(usuario_grupo_relatorio.codigo_cadastro_relatorio),',')
                FROM usuario_grupo_relatorio
               WHERE usuario_grupo_relatorio.codigo_cadastro_grupo = cadastro_grupo.codigo_cadastro_grupo
            ) AS relatorios"
        ];

        $dados['grupo'] = $cadastroGrupoModel->get([$cadastroGrupoModel->uuidColumn => $uuid], $colunasGrupo, true);
        $dados['menu'] = $cadastroMenuModel->get([], [], false);
        $dados['permissoes'] = $usuarioGrupoMenuModel->get(['codigo_cadastro_grupo' => $dados['grupo']['codigo_cadastro_grupo']], [], false);

        return $this->template('grupo', ['edit', 'functions'], $dados);
    }

    /**
     * Busca os registros para o Datagrid
     * @param int $status Verifica se a informação está ativa (1 ou 0)
     */
    public function getDataGrid(int $status)
    {
        $cadastroGrupoModel = new CadastroGrupoModel;
        $dadosRequest = $this->request->getVar();
        $dadosRequest['status'] = $status;
        $data = $cadastroGrupoModel->getDataGrid($dadosRequest);
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
        $cadastroGrupoModel = new CadastroGrupoModel;
        $usuarioGrupoMenuModel = new UsuarioGrupoMenuModel;
        $usuarioGrupoRelatorioModel = new UsuarioGrupoRelatorioModel;
        $dadosRequest = convertEmptyToNull($this->request->getVar());
        $dadosUsuario = $this->nativeSession->get("usuario");

        $erros = $this->validarRequisicao($this->request, [
            'nome' => 'required|string|min_length[3]|max_length[255]|is_unique[cadastro_grupo.nome]',
            'slug' => 'required|string|min_length[3]|max_length[255]',
            'codigo_empresa' => 'permit_empty|integer',
        ]);

        if (!empty($erros)) {
            $this->nativeSession->setFlashData('error', formataErros($erros));
            return redirect()->back()->withInput();
        }

        $grupo = [
            'usuario_criacao' => $dadosUsuario['codigo_usuario'],
            'nome'            => $dadosRequest['nome'],
            'slug'            => snakeCase($dadosRequest['slug'], true)
        ];

        // Verifica se foi passado uma empresa
        if (!empty($dadosRequest['codigo_empresa'])) {
            $grupo['codigo_empresa'] = $dadosRequest['codigo_empresa'];
        }

        //Inicia as operações de DB
        $this->db->transStart();
        try {
            $cadastroGrupoModel->save($grupo);
            $codigoGrupo = $cadastroGrupoModel->insertID('cadastro_grupo_codigo_cadastro_grupo_seq');

            // Percorre os menus desejados para gravar a permissão
            if ($codigoGrupo) {
                if (!empty($dadosRequest['permissao'])) {
                    foreach ($dadosRequest['permissao'] as $keyCodigoMenu => $valuePermissao) {
                        $permissoes = [
                            'usuario_criacao'       => $dadosUsuario['codigo_usuario'],
                            'codigo_cadastro_grupo' => $codigoGrupo,
                            'codigo_cadastro_menu'  => $keyCodigoMenu,
                            'consultar'             => !empty($valuePermissao['consultar']) ? $valuePermissao['consultar'] : 0,
                            'inserir'               => !empty($valuePermissao['inserir'])   ? $valuePermissao['inserir']   : 0,
                            'modificar'             => !empty($valuePermissao['modificar']) ? $valuePermissao['modificar'] : 0,
                            'deletar'               => !empty($valuePermissao['deletar'])   ? $valuePermissao['deletar']   : 0
                        ];
                        $usuarioGrupoMenuModel->save($permissoes);
                    }
                }

                // Insere as permissões de relatórios
                if (!empty($dadosRequest['relatorio'])) {
                    foreach (explode(',', $dadosRequest['relatorio']) as $id) {
                        if (!empty($id)) {
                            $dadosRel = ['codigo_cadastro_grupo' => $codigoGrupo, 'codigo_cadastro_relatorio' => $id];
                            $usuarioGrupoRelatorioModel->save($dadosRel);
                        }
                    }
                }
            }

            $this->db->transComplete();
            $this->nativeSession->setFlashData('success', lang('Success.default.cadastrado', ['Grupo']));
        } catch (Exception $e) {
            $this->nativeSession->setFlashData('error', lang('Errors.banco.validaInsercao'));
            return redirect()->back()->withInput();
        }

        return redirect()->to(base_url("grupo"));
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
            return redirect()->to(base_url("grupo"));
        }

        $cadastroGrupoModel = new CadastroGrupoModel;
        $usuarioGrupoMenuModel = new UsuarioGrupoMenuModel;
        $usuarioGrupoRelatorioModel = new UsuarioGrupoRelatorioModel;
        $dadosRequest = convertEmptyToNull($this->request->getVar());
        $dadosUsuario = $this->nativeSession->get("usuario");

        $erros = $this->validarRequisicao($this->request, [
            'nome' => 'required|string|min_length[3]|max_length[255]',
            'slug' => 'required|string|min_length[3]|max_length[255]',
            'codigo_empresa' => 'permit_empty|integer',
        ]);

        if (!empty($erros)) {
            $this->nativeSession->setFlashData('error', formataErros($erros));
            return redirect()->back()->withInput();
        }

        $grupoUpdate = [
            'usuario_alteracao' => $dadosUsuario['codigo_usuario'],
            'nome'              => $dadosRequest['nome'],
            'slug'              => snakeCase($dadosRequest['slug'], true)
        ];

        // Verifica se foi passado uma empresa
        if (!empty($dadosRequest['codigo_empresa'])) {
            $grupoUpdate['codigo_empresa'] = $dadosRequest['codigo_empresa'];
        }

        // Busca o Código do Grupo
        $grupo = $cadastroGrupoModel->get([$cadastroGrupoModel->uuidColumn => $uuid], [$cadastroGrupoModel->primaryKey], true);

        //Inicia as operações de DB
        $this->db->transStart();
        try {
            $cadastroGrupoModel->where($cadastroGrupoModel->uuidColumn, $uuid)->set($grupoUpdate)->update();

            if (!empty($dadosRequest['permissao'])) {
                foreach ($dadosRequest['permissao'] as $keyCodigoMenu => $valuePermissao) {
                    $permissoes = [
                        'codigo_cadastro_grupo' => $grupo['codigo_cadastro_grupo'],
                        'codigo_cadastro_menu'  => $keyCodigoMenu,
                        'consultar'             => !empty($valuePermissao['consultar']) ? $valuePermissao['consultar'] : 0,
                        'inserir'               => !empty($valuePermissao['inserir']) ? $valuePermissao['inserir'] : 0,
                        'modificar'             => !empty($valuePermissao['modificar']) ? $valuePermissao['modificar'] : 0,
                        'deletar'               => !empty($valuePermissao['deletar']) ? $valuePermissao['deletar'] : 0
                    ];

                    // Verifica se tem que criar ou editar o registro
                    if (!empty($valuePermissao['codigo_usuario_grupo_menu'])) {
                        // Edita o registro
                        $permissoes['usuario_alteracao'] = $dadosUsuario['codigo_usuario'];
                        $permissoes['alterado_em'] = "NOW()";
                        $usuarioGrupoMenuModel->save($valuePermissao['codigo_usuario_grupo_menu'], $permissoes);
                    } else {
                        // Cria o registro
                        $permissoes['usuario_criacao'] = $dadosUsuario['codigo_usuario'];
                        $usuarioGrupoMenuModel->save($permissoes);
                    }
                }
            }

            // Atualiza ou Insere as permissões de relatórios
            if (!empty($dadosRequest['relatorio'])) {
                // Inativa os registros anteriores
                $usuarioGrupoRelatorioModel->where('codigo_cadastro_grupo', $grupo['codigo_cadastro_grupo'])->set(
                    ['usuario_inativacao' => $dadosUsuario['codigo_usuario'], 'inativado_em' => "NOW()"]
                )->update();

                foreach (explode(',', $dadosRequest['relatorio']) as $id) {
                    if (!empty($id)) {
                        $dadosUpdateRel = ['codigo_cadastro_grupo' => $grupo['codigo_cadastro_grupo'], 'codigo_cadastro_relatorio' => $id];
                        $usuarioGrupoRelatorioModel->save($dadosUpdateRel);
                    }
                }
            }

            $this->db->transComplete();
            $this->nativeSession->setFlashData('success', lang('Success.default.atualizado', ['Grupo']));
        } catch (Exception $e) {
            $this->nativeSession->setFlashData('error', lang('Errors.banco.validaUpdate'));
            return redirect()->back()->withInput();
        }

        return redirect()->to(base_url("grupo"));
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
        $cadastroGrupoModel = new CadastroGrupoModel;

        $dadosGrupo = [
            'alterado_em'        => "NOW()",
            'usuario_alteracao'  => $dadosUsuario['codigo_usuario'],
            'inativado_em'       => null,
            'usuario_inativacao' => null
        ];

        try {
            $cadastroGrupoModel->where($cadastroGrupoModel->uuidColumn, $uuid)->set($dadosGrupo)->update();
        } catch (Exception $e) {
            return $this->response->setJSON(['mensagem' => lang('Errors.banco.validaUpdate')], 422);
        }

        return $this->response->setJSON(['mensagem' => lang('Success.default.ativado', ['Grupo'])], 202);
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
        $cadastroGrupoModel = new CadastroGrupoModel;

        try {
            $cadastroGrupoModel->customSoftDelete($uuid, $dadosUsuario['codigo_usuario'], true);
        } catch (Exception $e) {
            return $this->response->setJSON(['mensagem' => lang('Errors.banco.validaUpdate')], 422);
        }

        return $this->response->setJSON(['mensagem' => lang('Success.default.desativado', ['Grupo'])], 202);
    }

    /**
     * Realiza as chamadas assincronas direto para a Model
     * @param string $function
     */
    public function backendCall(string $function)
    {
        try {
            $request = $this->request->getVar();
            return $this->response->setJSON((new CadastroGrupoModel)->$function($request));
        } catch (Exception $e) {
            var_dump($e);
        }
    }
}
