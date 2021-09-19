<?php

namespace App\Models\Estoque;

use App\Libraries\NativeSession;
use App\Models\BaseModel;

class EstoqueModel extends BaseModel
{
    protected $table = 'estoque';
    protected $primaryKey = 'codigo_estoque';
    protected $uuidColumn = 'uuid_estoque';

    protected $useAutoIncrement = true;

    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $useCache = false;

    protected $useTimestamps = true;
    protected $createdField = 'criado_em';
    protected $updatedField = 'alterado_em';
    protected $deletedField = 'inativado_em';

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

    protected $allowedFields = [
        'codigo_estoque',
        'uuid_estoque',
        'usuario_criacao',
        'usuario_alteracao',
        'usuario_inativacao',
        'criado_em',
        'alterado_em',
        'inativado_em',
        'codigo_empresa',
        'codigo_vendedor',
        'nome',
        'padrao'
    ];

    /**
     * Busca os registros para o Datagrid
     * @param array $dadosDataGrid Dados da tabela do dataGrid
     * @param string $condicoes Where de condições
     */
    public function getDataGrid(array $dadosDataGrid, string $condicoes = "1=1")
    {
        $configDataGrid = $this->configDataGrid($dadosDataGrid);
        $condicoes = "{$condicoes} {$configDataGrid->whereSearch}";

        $this->select("
            DISTINCT ON (ep.codigo_produto) ep.codigo_produto
          , ep.codigo_estoque_produto
          , ep.uuid_estoque_produto
          , produto.nome AS nome_produto
          , produto.codigo_barras
          , produto.referencia_fornecedor
          , ep.valor_fabrica
          , ep.valor_venda
          , ep.valor_ecommerce
          , ep.valor_atacado
          , ep.estoque_minimo
          , COALESCE(ep.estoque_atual, 0) AS estoque_atual
          , SPLIT_PART(estoque.nome, ' ', 1) AS nome_estoque
          , TO_CHAR(produto.criado_em, 'DD/MM/YYYY HH24:MI') AS criado_em
          , obter_nome_usuario(produto.usuario_criacao) AS usuario_criacao
        ", FALSE);

        $this->join('estoque_produto ep', 'ep.codigo_estoque = estoque.codigo_estoque');
        $this->join('produto', 'produto.codigo_produto = ep.codigo_produto');

        /////// Inicio :: Filtros ///////

        if (!empty($configDataGrid->filtros['codigo_fornecedor'])) {
            $this->where("produto.codigo_fornecedor", $configDataGrid->filtros['codigo_fornecedor']);
        }

        if (!empty($configDataGrid->filtros['codigo_produto'])) {
            $this->where("produto.codigo_produto", $configDataGrid->filtros['codigo_produto']);
        }

        if (!empty($configDataGrid->filtros['codigo_estoque'])) {
            $this->where("estoque.codigo_estoque", $configDataGrid->filtros['codigo_estoque']);
        }

        if (!empty($configDataGrid->filtros['exibir_produtos'])) {
            if ($configDataGrid->filtros['exibir_produtos'] == 2) {
                $this->where('COALESCE(ep.estoque_atual, 0) < 0');
            } else if ($configDataGrid->filtros['exibir_produtos'] == 3) {
                $this->where('COALESCE(ep.estoque_atual, 0) > 0 ');
            } else if ($configDataGrid->filtros['exibir_produtos'] == 4) {
                $this->where('COALESCE(ep.estoque_atual, 0) = 0 ');
            }
        }

        /////// Fim :: Filtros ///////

        $queryCompiled = $this->getCompiledSelect();

        // Retorno do DataGrid
        $queryStringSelect = "SELECT * FROM ({$queryCompiled}) AS x WHERE 1 = 1 {$configDataGrid->whereSearch} ORDER BY {$configDataGrid->fieldOrder} {$configDataGrid->orderDir} LIMIT {$configDataGrid->limit} OFFSET {$configDataGrid->offset}";
        $queryStringTotal = "SELECT COUNT(1) AS total FROM ({$queryCompiled}) AS x WHERE 1 = 1 {$configDataGrid->whereSearch}";
        $data['data'] = $this->query($queryStringSelect)->getResultArray();
        $data['count']['total'] = $this->query($queryStringTotal)->getResultArray()[0]['total'];
        return $data;
    }

    /**
     * Busca os Estoques para o Select2
     * @param array $filtros Filtros para a Busca
     */
    public function selectEstoque(array $filtros)
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');

        $this->select("
            codigo_estoque AS id
          , nome AS text
        ", FALSE);

        /////// Inicio :: Filtros ///////

        if (!empty($filtros)) {
            if (!empty($filtros['termo'])) {
                if (is_numeric($filtros['termo'])) {
                    $this->where("codigo_estoque", $filtros['termo']);
                } else {
                    $termo = explode(' ', $filtros['termo']);
                    foreach ($termo as $key => $value) {
                        $this->where("nome ILIKE '%{$value}%'");
                    }
                }
            }

            if (!empty($filtros['codEmpresa'])) {
                $this->where('codigo_empresa', $filtros['codEmpresa']);
            } else {
                $this->where('codigo_empresa', $dadosEmpresa['codigo_empresa']);
            }

            // Se for um usuario diferente de ADMIN e for VENDEDOR faz um filtro de codigo_vendedor

        }

        /////// Fim :: Filtros ///////

        $this->orderBy(2, 'ASC');

        $this->limit(30);
        $this->offset(($filtros['page'] - 1) * 30);

        $data['itens'] = $this->find();
        $data['count'] = $this->countAllResults();
        return $data;
    }

    /**
     * Busca os registros para o Datagrid
     * @param array $dadosDataGrid Dados da tabela do dataGrid
     * @param string $condicoes Where de condições
     */
    public function selectConsultaProduto(array $filtros)
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');

        $this->select("
            DISTINCT ON (ep.codigo_produto) ep.codigo_produto AS id
          , produto.nome || ' (' || COALESCE(produto.codigo_barras, produto.referencia_fornecedor) ||')' AS text
          , COALESCE(ep.estoque_atual, 0) AS estoque_atual
          , produto.diretorio_imagem
        ", FALSE);

        $this->join('estoque_produto ep', 'ep.codigo_estoque = estoque.codigo_estoque');
        $this->join('produto', 'produto.codigo_produto = ep.codigo_produto');

        /////// Inicio :: Filtros ///////

        if (!empty($filtros)) {
            if (!empty($filtros['termo'])) {
                if (is_numeric($filtros['termo'])) {
                    $this->where("ep.codigo_produto", $filtros['termo']);
                } else {
                    $termo = explode(' ', $filtros['termo']);
                    foreach ($termo as $key => $value) {
                        $this->where("
                                (produto.codigo_barras ILIKE '%{$value}%'
                            OR produto.nome ILIKE '%{$value}%'
                            OR produto.referencia_fornecedor ILIKE '%{$value}%')
                        ");
                    }
                }
            }
        }

        $this->where('produto.codigo_empresa', $dadosEmpresa['codigo_empresa']);

        /////// Fim :: Filtros ///////

        $this->orderBy(1, 'ASC');
        $this->orderBy(2, 'ASC');

        $this->limit(30);
        $this->offset(($filtros['page'] - 1) * 30);

        $data['itens'] = $this->find();
        $data['count'] = $this->countAllResults();
        return $data;
    }

    /**
     * Busca o Estoque de um produto
     * @param array $dadosDataGrid Dados da tabela do dataGrid
     * @param string $condicoes Where de condições
     */
    public function selectEstoqueProduto(array $filtros)
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');

        $this->select("
            ep.codigo_produto
          , produto.nome || ' (' || COALESCE(produto.codigo_barras, produto.referencia_fornecedor) ||')' AS nome
          , COALESCE(ep.valor_fabrica, 0) AS valor_fabrica
          , COALESCE(ep.valor_venda, 0) AS valor_venda
          , COALESCE(ep.valor_ecommerce, 0) AS valor_ecommerce
          , COALESCE(ep.valor_atacado, 0) AS valor_atacado
          , COALESCE(ep.estoque_atual, 0) AS estoque_atual
          , SPLIT_PART(estoque.nome, ' ', 1) AS nome_estoque
        ", FALSE);

        $this->join('estoque_produto ep', 'ep.codigo_estoque = estoque.codigo_estoque');
        $this->join('produto', 'produto.codigo_produto = ep.codigo_produto');

        /////// Inicio :: Filtros ///////

        $this->where('estoque.codigo_empresa', $dadosEmpresa['codigo_empresa']);

        if (!empty($filtros)) {
            if (!empty($filtros['termo'])) {
                if (is_numeric($filtros['termo'])) {
                    $this->where("ep.codigo_produto", $filtros['termo']);
                } else {
                    $termo = explode(' ', $filtros['termo']);
                    foreach ($termo as $key => $value) {
                        $this->where("
                            (produto.codigo_barras ILIKE '%{$value}%'
                            OR produto.nome ILIKE '%{$value}%'
                            OR produto.referencia_fornecedor ILIKE '%{$value}%')
                        ");
                    }
                }
            }

            if (!empty($filtros['uuid_produto'])) {
                $this->where("produto.uuid_produto", $filtros['uuid_produto']);
            }
        }

        /////// Fim :: Filtros ///////

        $this->orderBy('estoque.nome', 'ASC');

        $this->limit(30);
        $this->offset(($filtros['page'] - 1) * 30);

        $data['itens'] = $this->find();
        $data['count'] = $this->countAllResults();
        return $data;
    }

    /**
     * Verifica se o produto possui estoque
     * @param int $codigoProduto Código do produto
     */
    public function verificaEstoqueProduto(int $codigoProduto)
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');
        $dadosUsuario = (new NativeSession(true))->get('usuario');

        $this->select("
            DISTINCT ON (ep.codigo_produto) ep.codigo_produto AS codigo_produto
          , produto.nome || ' (' || COALESCE(produto.codigo_barras, produto.referencia_fornecedor) ||')' AS text
          , ep.estoque_atual
        ", FALSE);

        $this->join('estoque_produto ep', 'ep.codigo_estoque = estoque.codigo_estoque');
        $this->join('produto', 'produto.codigo_produto = ep.codigo_produto');

        /////// Inicio :: Filtros ///////

        $this->where("ep.codigo_produto", $codigoProduto);
        $this->where("estoque.codigo_estoque", $dadosUsuario['codigo_estoque']);
        $this->where('ep.codigo_empresa', $dadosEmpresa['codigo_empresa']);
        $this->where('ep.estoque_atual > ', 0);

        /////// Fim :: Filtros ///////

        return $this->find();
    }
}
