<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\RedirectResponse;
use Exception;

use App\Models\Imovel\ImovelModel;
use App\Models\Imovel\EnderecoImovelModel;
use App\Models\Imovel\ImagemImovelModel;

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
        return $this->template('imovel', ['create', 'modal', 'functions']);
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
        $imagemImovelModel = new ImagemImovelModel;
        $enderecoImovelModel = new EnderecoImovelModel;

        $dados['imovel'] = $imovelModel->get([$imovelModel->uuidColumn => $uuid], [], true);
        $dados['endereco'] = $enderecoImovelModel->get(['codigo_imovel' => $dados['imovel']['codigo_imovel']], [], true);

        $dados['imagemImovel'] = $imagemImovelModel->get(['codigo_imovel' => $dados['imovel']['codigo_imovel']], ['uuid_imagem_imovel', 'diretorio_imagem']);

        $imagemProduto = [];
        if (!empty($dados['imagemImovel'])) {
            foreach ($dados['imagemImovel'] as $key => $value) {
                $imagem['uuid_imagem_imovel'] = $value['uuid_imagem_imovel'];
                $imagem['diretorio_imagem'] = $this->getFileImagem($value['diretorio_imagem']);
                array_push($imagemProduto, $imagem);
            }
        }

        $dados['imagemImovel'] = $imagemProduto;

        $dados['imovel']['imagem_destaque'] = base_url('assets/img/sem_imagem.jpg');
        if (!empty($dados['imovel']['diretorio_imagem'])) {
            $dados['imovel']['imagem_destaque'] = $this->getFileImagem($dados['imovel']['diretorio_imagem']);
        }
        // dd($dados);
        return $this->template('imovel', ['edit', 'functions'], $dados);
    }

    /**
     * Busca os registros para o Datagrid
     * @param int $status Verifica se a informação está ativa (1 ou 0)
     */
    public function getDataGrid(int $status)
    {
        try {
            $imovelModel = new ImovelModel;
            $dadosRequest = $this->request->getVar();
            $dadosRequest['status'] = $status;
            $data = $imovelModel->getDataGrid($dadosRequest);
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
        helper("file");

        $imovelModel = new ImovelModel;
        $imagemImovelModel = new ImagemImovelModel;
        $enderecoImovelModel = new EnderecoImovelModel;
        $dadosRequest = convertEmptyToNull($this->request->getVar());
        $dadosEmpresa = $this->nativeSession->get("empresa");

        $erros = $this->validarRequisicao($this->request, [
            'codigo_referencia' => 'required|string|max_length[255]',
            'codigo_categoria_imovel' => 'required|integer',
            'codigo_tipo_imovel' => 'required|integer',
            'codigo_proprietario' => 'permit_empty|integer',
            'quarto' => 'permit_empty|integer',
            'suite' => 'permit_empty|integer',
            'banheiro' => 'permit_empty|integer',
            'area_construida' => 'permit_empty|integer',
            'area_total' => 'permit_empty|integer',
            'edicula' => 'permit_empty|string',
            'condominio' => 'permit_empty|string',
            'vaga' => 'permit_empty|integer',
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
            'vaga'                     => $dadosRequest['vaga'],
            'banheiro'                 => $dadosRequest['banheiro'],
            'area_construida'          => onlyNumber($dadosRequest['area_construida']),
            'area_total'               => onlyNumber($dadosRequest['area_total']),
            'edicula'                  => $dadosRequest['edicula'],
            'condominio'               => $dadosRequest['condominio'],
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
            if (!empty($dadosRequest['cep'])) {
                $empresaEndereco = [
                    'codigo_imovel'  => $codigoImovel,
                    'codigo_empresa' => $imovel['codigo_empresa'],
                    'cep'            => onlyNumber($dadosRequest['cep']),
                    'rua'            => $dadosRequest['rua'],
                    'numero'         => onlyNumber($dadosRequest['numero']),
                    'bairro'         => $dadosRequest['bairro'],
                    'complemento'    => $dadosRequest['complemento'],
                    'cidade'         => $dadosRequest['cidade'],
                    'uf'             => $dadosRequest['uf']
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

    /**
     * Altera o Registro
     * @param string $uuid UUID do Registro
     * @return \CodeIgniter\HTTP\RedirectResponse
     */

    public function update($uuid): RedirectResponse
    {
        helper("file");

        if (!$this->verificarUuid($uuid)) {
            $this->nativeSession->setFlashData('error', lang('Errors.geral.validaUuid'));
            return redirect()->to(base_url("grupo"));
        }

        $imovelModel = new ImovelModel;
        $imagemImovelModel = new ImagemImovelModel;
        $enderecoImovelModel = new EnderecoImovelModel;
        $dadosRequest = convertEmptyToNull($this->request->getVar());
        $dadosEmpresa = $this->nativeSession->get("empresa");


        $erros = $this->validarRequisicao($this->request, [
            'codigo_referencia' => 'required|string|max_length[255]',
            'codigo_categoria_imovel' => 'required|integer',
            'codigo_tipo_imovel' => 'required|integer',
            'codigo_proprietario' => 'permit_empty|integer',
            'quarto' => 'permit_empty|integer',
            'suite' => 'permit_empty|integer',
            'banheiro' => 'permit_empty|integer',
            'vaga' => 'permit_empty|integer',
            'area_construida' => 'permit_empty|integer',
            'area_total' => 'permit_empty|integer',
            'edicula' => 'permit_empty|string',
            'condominio' => 'permit_empty|string',
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
        $whereImovelBusca = "codigo_empresa = {$dadosEmpresa['codigo_empresa']} AND codigo_referencia = '{$dadosRequest['codigo_referencia']}' AND uuid_imovel <> '{$uuid}'";
        $imovelBusca = $imovelModel->get($whereImovelBusca);

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
            'vaga'                     => $dadosRequest['vaga'],
            'banheiro'                 => $dadosRequest['banheiro'],
            'area_construida'          => onlyNumber($dadosRequest['area_construida']),
            'area_total'               => onlyNumber($dadosRequest['area_total']),
            'edicula'                  => $dadosRequest['edicula'],
            'condominio'               => $dadosRequest['condominio'],
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
                    'codigo_empresa'        => $imovel['codigo_empresa'],
                    'diretorio_imagem'      => $nomeDocumento,
                ];
                array_push($imagens, $imovelImagem);
            }
        }


        //Inicia as operações de DB
        $this->db->transStart();
        try {
            $imovelModel->where($imovelModel->uuidColumn, $uuid)->set($imovel)->update();
            $imovel = $imovelModel->get([$imovelModel->uuidColumn => $uuid], ['codigo_imovel'], true);
            //Insere as Imagens do imovel
            if (!empty($imagens)) {
                foreach ($imagens as $key => $img) {

                    $img['codigo_imovel'] = $imovel['codigo_imovel'];
                    $imagemImovelModel->save($img);
                }
            }
            //Insere o Endereço do Imóvel
            if (!empty($dadosRequest['cep'])) {
                $empresaEndereco = [
                    'cep'            => onlyNumber($dadosRequest['cep']),
                    'rua'            => $dadosRequest['rua'],
                    'numero'         => onlyNumber($dadosRequest['numero']),
                    'bairro'         => $dadosRequest['bairro'],
                    'complemento'    => $dadosRequest['complemento'],
                    'cidade'         => $dadosRequest['cidade'],
                    'uf'             => $dadosRequest['uf']
                ];

                $enderecoImovelModel->where(['codigo_imovel' => $imovel['codigo_imovel']])->set($empresaEndereco)->update();
            }

            $this->db->transComplete();
            $this->nativeSession->setFlashData('success', lang('Success.default.cadastrado', ['Imóvel']));
        } catch (Exception $e) {
            $this->nativeSession->setFlashData('error', lang('Errors.banco.validaInsercao'));
            return redirect()->back()->withInput();
        }

        return redirect()->to(base_url("imovel"));
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

        $imovelModel = new ImovelModel;

        $dadosimovel = [
            'alterado_em'        => "NOW()",
            'inativado_em'       => null,
        ];

        try {
            $imovelModel->where($imovelModel->uuidColumn, $uuid)->set($dadosimovel)->update();
        } catch (Exception $e) {
            return $this->response->setJSON(['mensagem' => lang('Errors.banco.validaUpdate')], 422);
        }

        return $this->response->setJSON(['mensagem' => lang('Success.default.ativado', ['Imóvel'])], 202);
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

        $imovelModel = new ImovelModel;

        try {
            $imovelModel->customSoftDelete($uuid, true);
        } catch (Exception $e) {
            return $this->response->setJSON(['mensagem' => lang('Errors.banco.validaUpdate')], 422);
        }

        return $this->response->setJSON(['mensagem' => lang('Success.default.inativado', ['Imóvel'])], 202);
    }

    /**
     * Desativa um Registro de imagem do imóvel
     * @param string $uuid Uuid do Registro
     * @return \CodeIgniter\HTTP\Response
     */
    public function disableImagem($uuid): Response
    {
        if (!$this->verificarUuid($uuid)) {
            return $this->response->setJSON(['mensagem' => lang('Errors.geral.validaUuid')], 400);
        }

        $imagemImovelModel = new ImagemImovelModel;

        try {
            $imagemImovelModel->customSoftDelete($uuid, true);
        } catch (Exception $e) {
            return $this->response->setJSON(['mensagem' => lang('Errors.banco.validaUpdate')], 422);
        }

        return $this->response->setJSON(['mensagem' => lang(
            'Success.default.inativada',
            ['Imagem']
        )], 202);
    }
    /**
     * Realiza as chamadas assincronas direto para a Model
     * @param string $function
     */
    public function backendCall(string $function)
    {
        try {
            $request = $this->request->getVar();
            return $this->response->setJSON((new ImovelModel)->$function($request));
        } catch (Exception $e) {
            var_dump($e);
        }
    }
}
