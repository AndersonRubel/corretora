<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\RedirectResponse;
use Exception;

use App\Models\Estoque\EstoqueProdutoModel;
use App\Models\Produto\ProdutoModel;
use App\Models\Produto\ProdutoCategoriaModel;

class ProdutoController extends BaseController
{
    //////////////////////////////////
    //                              //
    //      OPERAÇÕES DE BUSCA      //
    //                              //
    //////////////////////////////////

    /**
     * Exibe a Tela de Produto
     * @return html
     */
    public function index()
    {
        return $this->template('produto', ['index', 'modal', 'functions']);
    }

    /**
     * Exibe a Tela de Adicionar Registro
     * @return html
     */
    public function create()
    {
        return $this->template('produto', ['create', 'functions']);
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
            return redirect()->to(base_url("produto"));
        }

        $produtoModel = new ProdutoModel;
        $colunas = [
            "produto.uuid_produto",
            "produto.referencia_fornecedor",
            "produto.codigo_barras",
            "produto.nome",
            "produto.codigo_fornecedor",
            "produto.descricao",
            "produto.diretorio_imagem",
            "produto.sku",
            "produto.ncm",
            "produto.cest",
            "(SELECT array_to_string(array_agg(ec.codigo_empresa_categoria), ', ')
                  FROM produto_categoria pc
                 INNER JOIN empresa_categoria ec
                    ON ec.codigo_empresa_categoria = pc.codigo_empresa_categoria
                 WHERE pc.codigo_produto = produto.codigo_produto
                 AND pc.inativado_em IS NULL
                ) AS categorias",
        ];
        $dados['produto'] = $produtoModel->get([$produtoModel->uuidColumn => $uuid], $colunas, true);

        return $this->template('produto', ['edit', 'functions'], $dados);
    }

    /**
     * Busca os registros para o Datagrid
     * @param int $status Verifica se a informação está ativa (1 ou 0)
     */
    public function getDataGrid(int $status): Response
    {
        $produtoModel = new ProdutoModel;
        $dadosRequest = $this->request->getVar();
        $dadosRequest['status'] = $status;
        $data = $produtoModel->getDataGrid($dadosRequest);

        $dados['data']             = !empty($data['data'])         ? $data['data']           : [];
        $dados['draw']             = !empty($dadosRequest['draw']) ? $dadosRequest['draw']   : 0;
        $dados['recordsTotal']     = !empty($data['count'])        ? $data['count']['total'] : 0;
        $dados['recordsFiltered']  = !empty($data['count'])        ? $data['count']['total'] : 0;

        return $this->response->setJSON($dados);
    }

    /**
     * Gera um código interno/Barras ou aleatório para um produto
     * @return \CodeIgniter\HTTP\Response
     */
    public function gerarCodigoBarras(string $tipo): Response
    {
        try {
            $codigo = null;
            switch ($tipo) {
                case 'EAN8':
                    $codigo = $this->_geraEAN8();
                    break;

                case 'aleatorio':
                    $codigo = $this->_geraCodigoAleatorio();
                    break;

                default:
                    $codigo = null;
                    break;
            }

            // Se não conseguir gerar o código, retorna mensagem
            if (empty($codigo)) {
                return $this->response->setJSON(['mensagem' => lang('Errors.produto.codigoNaogerado')], 422);
            }

            return $this->response->setJSON($codigo);
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    /**
     * Gera um código de barras do tipo EAN8
     * @param int $rodada Numero que controla quantas vezes a geração pode ser executada
     */
    private function _geraEAN8($rodada = 0)
    {
        $produtoModel = new ProdutoModel;
        $dadosEmpresa = $this->nativeSession->get("empresa");

        $codigoPais       = 789; // 3 Digitos
        $codigoProduto    = mt_rand(1000, 9999); // 4 Digitos
        $digitoDeControle = mt_rand(1, 9); // Dígito de controle

        $validCode = "{$codigoPais}{$codigoProduto}{$digitoDeControle}";

        // previne a recursão infinita
        if ($rodada > 10) return '';

        // verifica se não existe um produto no estoque com o mesmo código
        $produtoBusca = $produtoModel->get(['codigo_empresa' => $dadosEmpresa['codigo_empresa'], 'codigo_barras' => (string)$validCode]);
        if (!empty($produtoBusca)) {
            // o código já existe, chama novamente para a geração de um novo
            return $this->_geraEAN8($rodada + 1);
        }

        return $validCode;
    }

    /**
     * Gera um código de 5 digitos válido e não existente nos produtos
     * @param int $rodada Numero que controla quantas vezes a geração pode ser executada
     */
    private function _geraCodigoAleatorio($rodada = 0)
    {
        $produtoModel = new ProdutoModel;
        $dadosEmpresa = $this->nativeSession->get("empresa");

        $validCode = mt_rand(10000, 99999);

        // previne a recursão infinita
        if ($rodada > 10) return '';

        // verifica se não existe um produto no estoque com o mesmo código
        $produtoBusca = $produtoModel->get(['codigo_empresa' => $dadosEmpresa['codigo_empresa'], 'codigo_barras' => (string)$validCode]);
        if (!empty($produtoBusca)) {
            // o código já existe, chama novamente para a geração de um novo
            return $this->_geraCodigoAleatorio($rodada + 1);
        }

        return $validCode;
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

        $produtoModel = new ProdutoModel;
        $produtoCategoriaModel = new ProdutoCategoriaModel;
        $dadosRequest = convertEmptyToNull($this->request->getVar());
        $dadosUsuario = $this->nativeSession->get("usuario");
        $dadosEmpresa = $this->nativeSession->get("empresa");

        $erros = $this->validarRequisicao($this->request, [
            'referencia_fornecedor' => 'permit_empty|string|max_length[255]',
            'codigo_barras' => 'required|string|max_length[255]',
            'nome' => 'required|string|min_length[3]|max_length[255]',
            'codigo_fornecedor' => 'required|integer',
            'categorias' => 'required',
            'descricao' => 'permit_empty|string',
            'imagem' => 'permit_empty|string',
            'imagem_nome' => 'permit_empty|string|max_length[255]',
            'sku' => 'permit_empty|string|max_length[255]',
            'ncm' => 'permit_empty|string|max_length[255]',
            'cest' => 'permit_empty|string|max_length[255]',
        ]);

        if (!empty($erros)) {
            $this->nativeSession->setFlashData('error', formataErros($erros));
            return redirect()->back()->withInput();
        }

        // Verifica se o Código Interno/Barras já esta em uso
        $produtoBusca = $produtoModel->get(['codigo_empresa' => $dadosEmpresa['codigo_empresa'], 'codigo_barras' => $dadosRequest['codigo_barras']]);
        if (!empty($produtoBusca)) {
            $this->nativeSession->setFlashData('error', lang('Erros.produto.codigoEmUso'));
            return redirect()->back()->withInput();
        }

        $produto = [
            'codigo_empresa'        => $dadosEmpresa['codigo_empresa'],
            'usuario_criacao'       => $dadosUsuario['codigo_usuario'],
            'referencia_fornecedor' => $dadosRequest['referencia_fornecedor'],
            'codigo_barras'         => onlyNumber($dadosRequest['codigo_barras']),
            'nome'                  => $dadosRequest['nome'],
            'codigo_fornecedor'     => onlyNumber($dadosRequest['codigo_fornecedor']),
            'sku'                   => $dadosRequest['sku'],
            'ncm'                   => $dadosRequest['ncm'],
            'cest'                  => $dadosRequest['cest'],
        ];

        //Faz upload de imagem se existir
        if (!empty($dadosRequest['imagem'])) {
            $fileStats = verificaDocumento($dadosRequest['imagem'], true);

            if (empty($fileStats) || !$fileStats['size']) {
                $this->nativeSession->setFlashData('error', lang("Errors.produto.imagemInvalida"));
                return redirect()->back()->withInput();
            }

            // Realiza o Upload do arquivo
            $nomeDocumento = "{$dadosEmpresa['codigo_empresa']}/produtos/" . encryptFileName($dadosRequest['imagem_nome']);
            $retornoEnvio = $this->putFileObject($nomeDocumento, $dadosRequest['imagem']);

            if (empty($retornoEnvio)) {
                $this->nativeSession->setFlashData('error', lang("Errors.geral.erroUpload"));
                return redirect()->back()->withInput();
            }

            $produto['diretorio_imagem'] = $nomeDocumento;
        }

        $categorias = explode(",", $dadosRequest['categorias']);

        //Inicia as operações de DB
        $this->db->transStart();
        try {
            $produtoModel->save($produto);
            $codigoProduto = $produtoModel->getInsertID('produto_codigo_produto_seq');

            //Insere a relação entre produto e categoria
            foreach ($categorias as $key => $value) {

                $produtoCategoria = [
                    'codigo_empresa'           => $dadosEmpresa['codigo_empresa'],
                    'usuario_criacao'          => $dadosUsuario['codigo_usuario'],
                    'codigo_produto'           => $codigoProduto,
                    'codigo_empresa_categoria' => $value,
                ];
                $produtoCategoriaModel->save($produtoCategoria);
            }

            $this->db->transComplete();
            $this->nativeSession->setFlashData('success', lang('Success.default.cadastrado', ['Produto']));
        } catch (Exception $e) {
            $this->nativeSession->setFlashData('error', lang('Errors.banco.validaInsercao'));
            return redirect()->back()->withInput();
        }

        return redirect()->to(base_url("produto"));
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
            return redirect()->to(base_url("produto"));
        }

        helper('file');
        $produtoModel = new ProdutoModel;
        $produtoCategoriaModel = new ProdutoCategoriaModel;
        $dadosRequest = convertEmptyToNull($this->request->getVar());
        $dadosUsuario = $this->nativeSession->get("usuario");
        $dadosEmpresa = $this->nativeSession->get("empresa");

        $erros = $this->validarRequisicao($this->request, [
            'referencia_fornecedor' => 'permit_empty|string|max_length[255]',
            'codigo_barras' => 'required|string|max_length[255]',
            'nome' => 'required|string|min_length[3]|max_length[255]',
            'codigo_fornecedor' => 'required|string',
            'categorias' => 'required',
            'descricao' => 'permit_empty|string',
            'imagem' => 'permit_empty|string',
            'imagem_nome' => 'permit_empty|string|max_length[255]',
            'sku' => 'permit_empty|string|max_length[255]',
            'ncm' => 'permit_empty|string|max_length[255]',
            'cest' => 'permit_empty|string|max_length[255]',
        ]);

        if (!empty($erros)) {
            $this->nativeSession->setFlashData('error', formataErros($erros));
            return redirect()->back()->withInput();
        }

        // Verifica se o Código Interno/Barras já esta em uso
        $whereProdutoBusca = "codigo_empresa = {$dadosEmpresa['codigo_empresa']} AND codigo_barras = '{$dadosRequest['codigo_barras']}' AND uuid_produto <> '{$uuid}'";
        $produtoBusca = $produtoModel->get($whereProdutoBusca);
        if (!empty($produtoBusca)) {
            $this->nativeSession->setFlashData('error', lang('Erros.produto.codigoEmUso'));
            return redirect()->back()->withInput();
        }

        $produto = [
            'usuario_alteracao'     => $dadosUsuario['codigo_usuario'],
            'alterado_em'           => "NOW()",
            'referencia_fornecedor' => $dadosRequest['referencia_fornecedor'],
            'codigo_barras'         => onlyNumber($dadosRequest['codigo_barras']),
            'nome'                  => $dadosRequest['nome'],
            'codigo_fornecedor'     => onlyNumber($dadosRequest['codigo_fornecedor']),
            'descricao'             => $dadosRequest['descricao'],
            'sku'                   => $dadosRequest['sku'],
            'ncm'                   => $dadosRequest['ncm'],
            'cest'                  => $dadosRequest['cest'],
        ];

        //Faz upload de imagem se existir
        if (!empty($dadosRequest['imagem'])) {
            $fileStats = verificaDocumento($dadosRequest['imagem'], true);

            if (empty($fileStats) || !$fileStats['size']) {
                $this->nativeSession->setFlashData('error', lang("Errors.produto.imagemInvalida"));
                return redirect()->back()->withInput();
            }

            // Realiza o Upload do arquivo
            $nomeDocumento = "{$dadosEmpresa['codigo_empresa']}/produtos/" . encryptFileName($dadosRequest['imagem_nome']);
            $retornoEnvio = $this->putFileObject($nomeDocumento, $dadosRequest['imagem']);

            if (empty($retornoEnvio)) {
                $this->nativeSession->setFlashData('error', lang("Errors.geral.erroUpload"));
                return redirect()->back()->withInput();
            }

            $produto['diretorio_imagem'] = $nomeDocumento;
        }

        $categorias = explode(",", $dadosRequest['categorias']);

        //Inicia as operações de DB
        $this->db->transStart();
        try {
            $produtoModel->where($produtoModel->uuidColumn, $uuid)->set($produto)->update();

            $produto = $produtoModel->get([$produtoModel->uuidColumn => $uuid], ['codigo_produto'], true);

            //inativa todas relações entre produto e categoria
            $dados = ['usuario_inativacao' => $dadosUsuario['codigo_usuario'], 'inativado_em' => 'NOW()'];
            $produtoCategoriaModel->where('codigo_produto', $produto['codigo_produto'])->set($dados)->update();

            //Insere novamente a relação entre produto e categoria
            foreach ($categorias as $key => $value) {

                $produtoCategoria = [
                    'usuario_criacao'          => $dadosUsuario['codigo_usuario'],
                    'codigo_empresa'           => $dadosEmpresa['codigo_empresa'],
                    'codigo_produto'           => $produto['codigo_produto'],
                    'codigo_empresa_categoria' => $value,
                ];
                $produtoCategoriaModel->save($produtoCategoria);
            }
            $this->db->transComplete();
            $this->nativeSession->setFlashData('success', lang('Success.default.atualizado', ['Produto']));
        } catch (Exception $e) {
            $this->nativeSession->setFlashData('error', lang('Errors.banco.validaUpdate'));
            return redirect()->back()->withInput();
        }

        return redirect()->to(base_url("produto"));
    }

    /**
     * Altera os valores do produto
     * @param string $uuid UUID do Registro
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function alterarPreco(string $uuid): RedirectResponse
    {
        if (!$this->verificarUuid($uuid)) {
            $this->nativeSession->setFlashData('error', lang('Errors.geral.validaUuid'));
            return redirect()->to(base_url("produto"));
        }

        $produtoModel = new ProdutoModel;
        $estoqueProdutoModel = new EstoqueProdutoModel;
        $dadosRequest = convertEmptyToNull($this->request->getVar());
        $dadosUsuario = $this->nativeSession->get("usuario");
        $dadosEmpresa = $this->nativeSession->get("empresa");

        $erros = $this->validarRequisicao($this->request, [
            'valor_fabrica' => 'required|string|max_length[255]',
            'valor_venda' => 'required|string|max_length[255]',
            'valor_ecommerce' => 'permit_empty|string|max_length[255]',
            'valor_atacado' => 'permit_empty|string|max_length[255]',
        ]);

        if (!empty($erros)) {
            $this->nativeSession->setFlashData('error', formataErros($erros));
            return redirect()->back()->withInput();
        }

        //Inicia as operações de DB
        $this->db->transStart();
        try {
            $produtoPreco = [
                'usuario_alteracao' => $dadosUsuario['codigo_usuario'],
                'alterado_em'       => "NOW()",
                'valor_fabrica'     => onlyNumber($dadosRequest['valor_fabrica']),
                'valor_venda'       => onlyNumber($dadosRequest['valor_venda']),
                'valor_ecommerce'   => onlyNumber($dadosRequest['valor_ecommerce']),
                'valor_atacado'     => onlyNumber($dadosRequest['valor_atacado']),
            ];

            // Busca o Código do Produto
            $produto = $produtoModel->get([$produtoModel->uuidColumn => $uuid], ['codigo_produto'], true);

            $estoqueProdutoModel
                ->where('codigo_empresa', $dadosEmpresa['codigo_empresa'])
                ->where('codigo_produto', $produto['codigo_produto'])
                ->set($produtoPreco)
                ->update();

            // TO DO: Inserir um historico de alteração de preço (Para gerar relatorio)

            $this->db->transComplete();
            $this->nativeSession->setFlashData('success', lang('Success.default.atualizado', ['Produto']));
        } catch (Exception $e) {
            $this->nativeSession->setFlashData('error', lang('Errors.banco.validaUpdate'));
            return redirect()->back()->withInput();
        }

        return redirect()->to(base_url("produto"));
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
        $produtoModel = new ProdutoModel;

        $dadosProduto = [
            'alterado_em'        => "NOW()",
            'usuario_alteracao'  => $dadosUsuario['codigo_usuario'],
            'inativado_em'       => null,
            'usuario_inativacao' => null
        ];

        try {
            $produtoModel->where($produtoModel->uuidColumn, $uuid)->set($dadosProduto)->update();
        } catch (Exception $e) {
            return $this->response->setJSON(['mensagem' => lang('Errors.banco.validaUpdate')], 422);
        }

        return $this->response->setJSON(['mensagem' => lang('Success.default.ativado', ['Produto'])], 202);
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
        $produtoModel = new ProdutoModel;

        try {
            $produtoModel->customSoftDelete($uuid, $dadosUsuario['codigo_usuario'], true);
        } catch (Exception $e) {
            return $this->response->setJSON(['mensagem' => lang('Errors.banco.validaUpdate')], 422);
        }

        return $this->response->setJSON(['mensagem' => lang('Success.default.inativado', ['Produto'])], 202);
    }

    /**
     * Realiza as chamadas assincronas direto para a Model
     * @param string $function
     */
    public function backendCall(string $function)
    {
        try {
            $request = $this->request->getVar();
            return $this->response->setJSON((new ProdutoModel)->$function($request));
        } catch (Exception $e) {
            var_dump($e);
        }
    }
}
