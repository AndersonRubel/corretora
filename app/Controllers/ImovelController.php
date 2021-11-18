<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\RedirectResponse;
use Exception;

use App\Models\imovel\ImovelModel;
use App\Models\imovel\EnderecoImovelModel;
use App\Models\imovel\ImagemImovelModel;

class ImovelController extends BaseController
{
    //////////////////////////////////
    //                              //
    //      OPERAÇÕES DE BUSCA      //
    //                              //
    //////////////////////////////////

    /**
     * Exibe a Tela de imovel
     * @return html
     */
    public function index()
    {
        return $this->template('imovel', ['index', 'modal', 'functions']);
    }

    /**
     * Exibe a Tela de Adicionar Registro
     * @return html
     */
    public function create()
    {
        return $this->template('imovel', ['create', 'functions']);
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
            return redirect()->to(base_url("imovel"));
        }

        $imovelModel = new ImovelModel;
        $colunas = [
            "imovel.uuid_imovel",
            "imovel.codigo_referencia",
            "imovel.codigo_empresa",
            "imovel.codigo_endereco",
            "imovel.codigo_proprietario",
            "imovel.codigo_empresa_categoria",
            "imovel.quarto",
            "imovel.suite",
            "imovel.banheiro",
            "imovel.area_util",
            "imovel.area_construida",
            "imovel.edicula",
            "imovel.descricao",
            "imovel.destaque",
            "imovel.publicado",
            // "(SELECT array_to_string(array_agg(ec.codigo_empresa_categoria), ', ')
            //       FROM imovel_categoria pc
            //      INNER JOIN empresa_categoria ec
            //         ON ec.codigo_empresa_categoria = pc.codigo_empresa_categoria
            //      WHERE pc.codigo_imovel = imovel.codigo_imovel
            //      AND pc.inativado_em IS NULL
            //     ) AS categorias",
        ];
        $dados['imovel'] = $imovelModel->get([$imovelModel->uuidColumn => $uuid], $colunas, true);

        return $this->template('imovel', ['edit', 'functions'], $dados);
    }

    /**
     * Busca os registros para o Datagrid
     * @param int $status Verifica se a informação está ativa (1 ou 0)
     */
    public function getDataGrid(int $status): Response
    {
        $imovelModel = new imovelModel;

        $dadosRequest = $this->request->getVar();

        $dadosRequest['status'] = $status;
        $data = $imovelModel->getDataGrid($dadosRequest);

        $dados['data']             = !empty($data['data'])         ? $data['data']           : [];
        $dados['draw']             = !empty($dadosRequest['draw']) ? $dadosRequest['draw']   : 0;
        $dados['recordsTotal']     = !empty($data['count'])        ? $data['count']['total'] : 0;
        $dados['recordsFiltered']  = !empty($data['count'])        ? $data['count']['total'] : 0;

        return $this->response->setJSON($dados);
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
        helper("file");

        $imovelModel = new imovelModel;
        $imagemImovelModel = new ImagemImovelModel;
        $enderecoImovelModel = new EnderecoImovelModel;
        $dadosRequest = convertEmptyToNull($this->request->getVar());
        $dadosEmpresa = $this->nativeSession->get("empresa");

        $erros = $this->validarRequisicao($this->request, [
            'codigo_referencia' => 'permit_empty|string|max_length[255]',
            'codigo_categoria_imovel' => 'required|integer',
            'codigo_tipo_imovel' => 'required|integer',
            'codigo_proprietario' => 'permit_empty|integer',
            'quarto' => 'required|integer',
            'suite' => 'permit_empty|integer',
            'banheiro' => 'required|integer',
            'area_construida' => 'permit_empty|integer',
            'area_util' => 'permit_empty|integer',
            'edicula' => 'permit_empty|string',
            'destaque' => 'permit_empty|string',
            'publicado' => 'permit_empty|string',
            'descricao' => 'permit_empty|string',
            'valor' => 'required|string',
            'cep' => [
                'rules' => 'required|checkCep',
                'errors' => ['checkCep' => 'Errors.geral.cepInvalido'],
            ],
            'rua' => 'required|string|max_length[255]',
            'numero' => 'required|integer|max_length[255]',
            'bairro' => 'required|string|max_length[255]',
            'complemento' => 'permit_empty|string|max_length[255]',
            'cidade' => 'required|string|max_length[255]',
            'uf' => 'required|string|max_length[255]',
        ]);


        if (!empty($erros)) {
            $this->nativeSession->setFlashData('error', formataErros($erros));
            return redirect()->back()->withInput();
        }

        // Verifica se o Código Referencia já esta em uso
        $imovelBusca = $imovelModel->get(['codigo_empresa' => $dadosEmpresa['codigo_empresa'], 'codigo_referencia' => $dadosRequest['codigo_referencia']]);
        if (!empty($imovelBusca)) {
            $this->nativeSession->setFlashData('error', lang('Erros.imovel.codigoReferenciaEmUso'));
            return redirect()->back()->withInput();
        }

        $imovel = [
            'codigo_empresa'           => $dadosEmpresa['codigo_empresa'],
            'codigo_referencia'        => $dadosRequest['codigo_referencia'],
            'codigo_categoria_imovel'  => onlyNumber($dadosRequest['codigo_categoria_imovel']),
            'codigo_tipo_imovel'       => $dadosRequest['codigo_tipo_imovel'],
            'codigo_proprietario'      => $dadosRequest['codigo_proprietario'],
            'quarto'                   => $dadosRequest['quarto'],
            'suite'                    => $dadosRequest['suite'],
            'banheiro'                 => $dadosRequest['banheiro'],
            'area_construida'          => onlyNumber($dadosRequest['area_construida']),
            'area_util'                => onlyNumber($dadosRequest['area_util']),
            'edicula'                  => $dadosRequest['edicula'],
            'destaque'                 => $dadosRequest['destaque'],
            'publicado'                => $dadosRequest['publicado'],
            'descricao'                => $dadosRequest['descricao'],
            'valor'                    => onlyNumber($dadosRequest['valor']),

        ];


        //Faz upload de imagem de destaque se existir
        if (!empty($dadosRequest['imagem'])) {
            $fileStats = verificaDocumento($dadosRequest['imagem'], true);

            if (empty($fileStats) || !$fileStats['size']) {
                $this->nativeSession->setFlashData('error', lang("Errors.imovel.imagemInvalida"));
                return redirect()->back()->withInput();
            }

            // Realiza o Upload do arquivo
            $nomeDocumento = "{$dadosEmpresa['codigo_empresa']}/imoveis/" . encryptFileName($dadosRequest['imagem_nome']);
            $retornoEnvio = $this->putFileObject($nomeDocumento, $dadosRequest['imagem']);

            if (empty($retornoEnvio)) {
                $this->nativeSession->setFlashData('error', lang("Errors.geral.erroUpload"));
                return redirect()->back()->withInput();
            }

            $imovel['diretorio_imagem'] = $nomeDocumento;
        }

        //Faz upload de imagens do imovel se existir
        if ($dadosRequest['filepond'][0] != '') {
            $files = [];

            foreach ($dadosRequest['filepond'] as $key => $value) {

                array_push($files, json_decode($value));

                //normaliza para o padrão do helper
                $files[$key]->data = "data: " . $files[$key]->type . ";base64," . $files[$key]->data;
                $fileStats = verificaDocumento($files[$key]->data, true);

                if (empty($fileStats) || !$fileStats['size']) {
                    $this->nativeSession->setFlashData('error', lang("Errors.imovel.imagemInvalida"));
                    return redirect()->back()->withInput();
                }
            }

            $imagens = [];
            foreach ($files as $key => $file) {

                // Realiza o Upload do arquivo
                $nomeDocumento = "{$dadosEmpresa['codigo_empresa']}/imoveis/" . encryptFileName($file->name);
                $retornoEnvio = $this->putFileObject($nomeDocumento, $file->data);

                if (empty($retornoEnvio)) {
                    $this->nativeSession->setFlashData('error', lang("Errors.geral.erroUpload"));
                    return redirect()->back()->withInput();
                }
                $imovelImagem = [
                    'usuario_criacao'       => $imovel['usuario_criacao'],
                    'codigo_empresa'        => $imovel['codigo_empresa'],
                    'diretorio_imagem'      => $nomeDocumento,
                ];
                array_push($imagens, $imovelImagem);
            }
        }

        // dd($imovel);
        $imovelModel->save($imovel);
        //Inicia as operações de DB
        $this->db->transStart();
        try {

            $codigoImovel = $imovelModel->getInsertID('imovel_codigo_imovel_seq');
            // dd($imovel);
            //Insere as Imagens do imovel
            if (!empty($imagens)) {
                foreach ($imagens as $key => $img) {

                    $img['codigo_imovel'] = $codigoImovel;
                    $imagemImovelModel->save($img);
                }
            }
            //Insere o Endereço do Imóvel
            if (!empty($imagens)) {
                $empresaEndereco = [
                    'codigo_imovel' => $codigoImovel,
                    'cep'           => onlyNumber($dadosRequest['cep']),
                    'rua'           => $dadosRequest['rua'],
                    'numero'        => onlyNumber($dadosRequest['numero']),
                    'bairro'        => $dadosRequest['bairro'],
                    'complemento'   => $dadosRequest['complemento'],
                    'cidade'        => $dadosRequest['cidade'],
                    'uf'            => $dadosRequest['uf']
                ];
                $enderecoImovelModel->save($empresaEndereco);
            }

            $this->db->transComplete();
            $this->nativeSession->setFlashData('success', lang('Success.default.cadastrado', ['Produto']));
        } catch (Exception $e) {
            $this->nativeSession->setFlashData('error', lang('Errors.banco.validaInsercao'));
            return redirect()->back()->withInput();
        }

        return redirect()->to(base_url("imovel"));
    }

    // /**
    //  * Altera o Registro
    //  * @param string $uuid UUID do Registro
    //  * @return \CodeIgniter\HTTP\RedirectResponse
    //  */
    // public function update(string $uuid): RedirectResponse
    // {
    //     if (!$this->verificarUuid($uuid)) {
    //         $this->nativeSession->setFlashData('error', lang('Errors.geral.validaUuid'));
    //         return redirect()->to(base_url("imovel"));
    //     }

    //     helper('file');
    //     $imovelModel = new imovelModel;
    //     $imovelCategoriaModel = new imovelCategoriaModel;
    //     $dadosRequest = convertEmptyToNull($this->request->getVar());
    //     $dadosUsuario = $this->nativeSession->get("usuario");
    //     $dadosEmpresa = $this->nativeSession->get("empresa");

    //     $erros = $this->validarRequisicao($this->request, [
    //         'referencia_fornecedor' => 'permit_empty|string|max_length[255]',
    //         'codigo_barras' => 'required|string|max_length[255]',
    //         'nome' => 'required|string|min_length[3]|max_length[255]',
    //         'codigo_fornecedor' => 'required|string',
    //         'categorias' => 'required',
    //         'descricao' => 'permit_empty|string',
    //         'imagem' => 'permit_empty|string',
    //         'imagem_nome' => 'permit_empty|string|max_length[255]',
    //         'sku' => 'permit_empty|string|max_length[255]',
    //         'ncm' => 'permit_empty|string|max_length[255]',
    //         'cest' => 'permit_empty|string|max_length[255]',
    //     ]);

    //     if (!empty($erros)) {
    //         $this->nativeSession->setFlashData('error', formataErros($erros));
    //         return redirect()->back()->withInput();
    //     }

    //     // Verifica se o Código Interno/Barras já esta em uso
    //     $whereimovelBusca = "codigo_empresa = {$dadosEmpresa['codigo_empresa']} AND codigo_barras = '{$dadosRequest['codigo_barras']}' AND uuid_imovel <> '{$uuid}'";
    //     $imovelBusca = $imovelModel->get($whereimovelBusca);
    //     if (!empty($imovelBusca)) {
    //         $this->nativeSession->setFlashData('error', lang('Erros.imovel.codigoEmUso'));
    //         return redirect()->back()->withInput();
    //     }

    //     $imovel = [
    //         'usuario_alteracao'     => $dadosUsuario['codigo_usuario'],
    //         'alterado_em'           => "NOW()",
    //         'referencia_fornecedor' => $dadosRequest['referencia_fornecedor'],
    //         'codigo_barras'         => onlyNumber($dadosRequest['codigo_barras']),
    //         'nome'                  => $dadosRequest['nome'],
    //         'codigo_fornecedor'     => onlyNumber($dadosRequest['codigo_fornecedor']),
    //         'descricao'             => $dadosRequest['descricao'],
    //         'sku'                   => $dadosRequest['sku'],
    //         'ncm'                   => $dadosRequest['ncm'],
    //         'cest'                  => $dadosRequest['cest'],
    //     ];

    //     //Faz upload de imagem se existir
    //     if (!empty($dadosRequest['imagem'])) {
    //         $fileStats = verificaDocumento($dadosRequest['imagem'], true);

    //         if (empty($fileStats) || !$fileStats['size']) {
    //             $this->nativeSession->setFlashData('error', lang("Errors.imovel.imagemInvalida"));
    //             return redirect()->back()->withInput();
    //         }

    //         // Realiza o Upload do arquivo
    //         $nomeDocumento = "{$dadosEmpresa['codigo_empresa']}/imovels/" . encryptFileName($dadosRequest['imagem_nome']);
    //         $retornoEnvio = $this->putFileObject($nomeDocumento, $dadosRequest['imagem']);

    //         if (empty($retornoEnvio)) {
    //             $this->nativeSession->setFlashData('error', lang("Errors.geral.erroUpload"));
    //             return redirect()->back()->withInput();
    //         }

    //         $imovel['diretorio_imagem'] = $nomeDocumento;
    //     }

    //     $categorias = explode(",", $dadosRequest['categorias']);

    //     //Inicia as operações de DB
    //     $this->db->transStart();
    //     try {
    //         $imovelModel->where($imovelModel->uuidColumn, $uuid)->set($imovel)->update();

    //         $imovel = $imovelModel->get([$imovelModel->uuidColumn => $uuid], ['codigo_imovel'], true);

    //         //inativa todas relações entre imovel e categoria
    //         $dados = ['usuario_inativacao' => $dadosUsuario['codigo_usuario'], 'inativado_em' => 'NOW()'];
    //         $imovelCategoriaModel->where('codigo_imovel', $imovel['codigo_imovel'])->set($dados)->update();

    //         //Insere novamente a relação entre imovel e categoria
    //         foreach ($categorias as $key => $value) {

    //             $imovelCategoria = [
    //                 'usuario_criacao'          => $dadosUsuario['codigo_usuario'],
    //                 'codigo_empresa'           => $dadosEmpresa['codigo_empresa'],
    //                 'codigo_imovel'           => $imovel['codigo_imovel'],
    //                 'codigo_empresa_categoria' => $value,
    //             ];
    //             $imovelCategoriaModel->save($imovelCategoria);
    //         }
    //         $this->db->transComplete();
    //         $this->nativeSession->setFlashData('success', lang('Success.default.atualizado', ['imovel']));
    //     } catch (Exception $e) {
    //         $this->nativeSession->setFlashData('error', lang('Errors.banco.validaUpdate'));
    //         return redirect()->back()->withInput();
    //     }

    //     return redirect()->to(base_url("imovel"));
    // }

    // /**
    //  * Altera os valores do imovel
    //  * @param string $uuid UUID do Registro
    //  * @return \CodeIgniter\HTTP\RedirectResponse
    //  */
    // public function alterarPreco(string $uuid): RedirectResponse
    // {
    //     if (!$this->verificarUuid($uuid)) {
    //         $this->nativeSession->setFlashData('error', lang('Errors.geral.validaUuid'));
    //         return redirect()->to(base_url("imovel"));
    //     }

    //     $imovelModel = new imovelModel;
    //     $estoqueimovelModel = new EstoqueimovelModel;
    //     $dadosRequest = convertEmptyToNull($this->request->getVar());
    //     $dadosUsuario = $this->nativeSession->get("usuario");
    //     $dadosEmpresa = $this->nativeSession->get("empresa");

    //     $erros = $this->validarRequisicao($this->request, [
    //         'valor_fabrica' => 'required|string|max_length[255]',
    //         'valor_venda' => 'required|string|max_length[255]',
    //         'valor_ecommerce' => 'permit_empty|string|max_length[255]',
    //         'valor_atacado' => 'permit_empty|string|max_length[255]',
    //     ]);

    //     if (!empty($erros)) {
    //         $this->nativeSession->setFlashData('error', formataErros($erros));
    //         return redirect()->back()->withInput();
    //     }

    //     //Inicia as operações de DB
    //     $this->db->transStart();
    //     try {
    //         $imovelPreco = [
    //             'usuario_alteracao' => $dadosUsuario['codigo_usuario'],
    //             'alterado_em'       => "NOW()",
    //             'valor_fabrica'     => onlyNumber($dadosRequest['valor_fabrica']),
    //             'valor_venda'       => onlyNumber($dadosRequest['valor_venda']),
    //             'valor_ecommerce'   => onlyNumber($dadosRequest['valor_ecommerce']),
    //             'valor_atacado'     => onlyNumber($dadosRequest['valor_atacado']),
    //         ];

    //         // Busca o Código do imovel
    //         $imovel = $imovelModel->get([$imovelModel->uuidColumn => $uuid], ['codigo_imovel'], true);

    //         $estoqueimovelModel
    //             ->where('codigo_empresa', $dadosEmpresa['codigo_empresa'])
    //             ->where('codigo_imovel', $imovel['codigo_imovel'])
    //             ->set($imovelPreco)
    //             ->update();

    //         // TO DO: Inserir um historico de alteração de preço (Para gerar relatorio)

    //         $this->db->transComplete();
    //         $this->nativeSession->setFlashData('success', lang('Success.default.atualizado', ['imovel']));
    //     } catch (Exception $e) {
    //         $this->nativeSession->setFlashData('error', lang('Errors.banco.validaUpdate'));
    //         return redirect()->back()->withInput();
    //     }

    //     return redirect()->to(base_url("imovel"));
    // }

    // /**
    //  * Ativa um Registro
    //  * @param string $uuid Uuid do Registro
    //  * @return \CodeIgniter\HTTP\Response
    //  */
    // public function enable(string $uuid): Response
    // {

    //     if (!$this->verificarUuid($uuid)) {
    //         return $this->response->setJSON(['mensagem' => lang('Errors.geral.validaUuid')], 400);
    //     }

    //     $dadosUsuario = $this->nativeSession->get("usuario");
    //     $imovelModel = new imovelModel;

    //     $dadosimovel = [
    //         'alterado_em'        => "NOW()",
    //         'usuario_alteracao'  => $dadosUsuario['codigo_usuario'],
    //         'inativado_em'       => null,
    //         'usuario_inativacao' => null
    //     ];

    //     try {
    //         $imovelModel->where($imovelModel->uuidColumn, $uuid)->set($dadosimovel)->update();
    //     } catch (Exception $e) {
    //         return $this->response->setJSON(['mensagem' => lang('Errors.banco.validaUpdate')], 422);
    //     }

    //     return $this->response->setJSON(['mensagem' => lang('Success.default.ativado', ['imovel'])], 202);
    // }

    // /**
    //  * Desativa um Registro
    //  * @param string $uuid Uuid do Registro
    //  * @return \CodeIgniter\HTTP\Response
    //  */
    // public function disable(string $uuid): Response
    // {
    //     if (!$this->verificarUuid($uuid)) {
    //         return $this->response->setJSON(['mensagem' => lang('Errors.geral.validaUuid')], 400);
    //     }

    //     $dadosUsuario = $this->nativeSession->get("usuario");
    //     $imovelModel = new imovelModel;

    //     try {
    //         $imovelModel->customSoftDelete($uuid, $dadosUsuario['codigo_usuario'], true);
    //     } catch (Exception $e) {
    //         return $this->response->setJSON(['mensagem' => lang('Errors.banco.validaUpdate')], 422);
    //     }

    //     return $this->response->setJSON(['mensagem' => lang('Success.default.inativado', ['imovel'])], 202);
    // }

    /**
     * Realiza as chamadas assincronas direto para a Model
     * @param string $function
     */
    public function backendCall(string $function)
    {
        try {
            $request = $this->request->getVar();
            return $this->response->setJSON((new imovelModel)->$function($request));
        } catch (Exception $e) {
            var_dump($e);
        }
    }
}