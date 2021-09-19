<?php

namespace App\Models\Estoque;

use App\Models\BaseModel;

class EstoqueEntradaModel extends BaseModel
{
    protected $table = 'estoque_entrada';
    protected $primaryKey = 'codigo_estoque_entrada';
    protected $uuidColumn = 'uuid_estoque_entrada';

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
        'codigo_estoque_entrada',
        'uuid_estoque_entrada',
        'usuario_criacao',
        'usuario_alteracao',
        'usuario_inativacao',
        'criado_em',
        'alterado_em',
        'inativado_em',
        'codigo_empresa',
        'codigo_estoque',
        'codigo_produto',
        'codigo_fornecedor',
        'codigo_venda',
        'transferencia_de_codigo_estoque',
        'transferencia_para_codigo_estoque',
        'codigo_cadastro_movimentacao_tipo',
        'nome_cadastro_movimentacao_tipo',
        'quantidade',
        'observacao',
        'movimentacao_lote'
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
            {$this->table}.uuid_estoque_entrada
          , {$this->table}.codigo_estoque_entrada
          , {$this->table}.codigo_produto
          , {$this->table}.quantidade
          , {$this->table}.nome_cadastro_movimentacao_tipo
          , {$this->table}.observacao
          , produto.codigo_barras
          , produto.nome AS nome_produto
          , SPLIT_PART(estoque.nome, ' ', 1) AS nome_estoque
          , TO_CHAR({$this->table}.criado_em, 'DD/MM/YYYY HH24:MI') AS criado_em
          , obter_nome_usuario({$this->table}.usuario_criacao) AS usuario_criacao
        ", FALSE);

        $this->join('estoque', 'estoque.codigo_estoque = estoque_entrada.codigo_estoque');
        $this->join('produto', 'produto.codigo_produto = estoque_entrada.codigo_produto');

        /////// Inicio :: Filtros ///////

        // Filtro por Data
        if (!empty($configDataGrid->filtros['data_inicio'])) {
            $this->where("DATE({$this->table}.criado_em) >=", $configDataGrid->filtros['data_inicio']);
        }

        if (!empty($configDataGrid->filtros['data_fim'])) {
            $this->where("DATE({$this->table}.criado_em) <=", $configDataGrid->filtros['data_fim']);
        }

        if (!empty($configDataGrid->filtros['codigo_usuario'])) {
            $this->where("{$this->table}.usuario_criacao", $configDataGrid->filtros['codigo_usuario']);
        }

        if (!empty($configDataGrid->filtros['codigo_produto'])) {
            $this->where("{$this->table}.codigo_produto", $configDataGrid->filtros['codigo_produto']);
        }

        if (!empty($configDataGrid->filtros['codigo_estoque'])) {
            $this->where("{$this->table}.codigo_estoque", $configDataGrid->filtros['codigo_estoque']);
        }

        if (!empty($configDataGrid->filtros['codigo_fornecedor'])) {
            $this->where("produto.codigo_fornecedor", $configDataGrid->filtros['codigo_fornecedor']);
        }

        if (!empty($configDataGrid->filtros['codigo_empresa_categoria'])) {
            $categorias = $configDataGrid->filtros['codigo_empresa_categoria'];
            foreach (explode(',', $categorias) as $key => $value) {
                $this->where("{$value} IN (SELECT pc.codigo_empresa_categoria
                                             FROM produto_categoria pc
                                            WHERE pc.codigo_produto = produto.codigo_produto
                                              AND pc.inativado_em IS NULL
                                        )
                ");
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
}
