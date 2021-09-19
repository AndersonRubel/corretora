<?php

namespace App\Models\Estoque;

use App\Libraries\NativeSession;
use App\Models\BaseModel;

class EstoqueProdutoModel extends BaseModel
{
    protected $table = 'estoque_produto';
    protected $primaryKey = 'codigo_estoque_produto';
    protected $uuidColumn = 'uuid_estoque_produto';

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
        'codigo_estoque_produto',
        'uuid_estoque_produto',
        'usuario_criacao',
        'usuario_alteracao',
        'usuario_inativacao',
        'criado_em',
        'alterado_em',
        'inativado_em',
        'codigo_empresa',
        'codigo_estoque',
        'codigo_produto',
        'estoque_minimo',
        'estoque_atual',
        'valor_fabrica',
        'valor_venda',
        'valor_ecommerce',
        'valor_atacado',
        'quantidade_atacado',
        'ultima_compra'
    ];

    /**
     * Busca os registros para o Datagrid
     * @param array $dadosDataGrid Dados da tabela do dataGrid
     * @param string $condicoes Where de condições
     */
    public function getDataGridGrade(array $dadosDataGrid, string $condicoes = "1=1")
    {
        $nativeSession = new NativeSession(true);
        $dadosEmpresa = $nativeSession->get('empresa');
        $configDataGrid = $this->configDataGrid($dadosDataGrid);
        $condicoes = "{$condicoes} {$configDataGrid->whereSearch}";


        $selectEstoque = 'true';
        $selectEstoqueMain = 'true';
        // Status 2 = Grade
        $builderEstoques = $this->builder('estoque')
            ->select("(SPLIT_PART(UPPER(estoque.nome), ' ', 1))::varchar AS nome, codigo_estoque", FALSE)
            ->where('codigo_empresa', $dadosEmpresa['codigo_empresa'])
            ->limit(5)
            ->get()
            ->getResultArray();

        $selectEstoque = '';
        $selectEstoqueMain = '';
        foreach ($builderEstoques as $key => $value) {
            $selectEstoque = $selectEstoque . ", ''::varchar AS estoque" . $value['codigo_estoque'];
            $whereQtde = 'true';

            $quantidadeSql = "(SELECT COALESCE(SUM(ep.estoque_atual),0)
                                     FROM estoque_produto ep
                                    WHERE ep.codigo_estoque = {$value['codigo_estoque']}
                                      AND ep.inativado_em IS NULL
                                      AND ep.codigo_produto = produto.codigo_produto
                                )";

            if (!empty($dados_get['custom_data']['exibir_produtos'])) {
                if ($dados_get['custom_data']['exibir_produtos'] == 2) {
                    $whereQtde = $quantidadeSql . ' < 0';
                } else if ($dados_get['custom_data']['exibir_produtos'] == 3) {
                    $whereQtde = $quantidadeSql . ' > 0';
                } else if ($dados_get['custom_data']['exibir_produtos'] == 4) {
                    $whereQtde = $quantidadeSql . ' = 0';
                }
            }

            if ($key > 0) {
                $selectEstoqueMain = $selectEstoqueMain . ', ';
            }

            $selectEstoqueMain = $selectEstoqueMain . "
                (CASE WHEN " . $whereQtde . "
                        THEN ( SELECT COALESCE(SUM(ep.estoque_atual)::varchar, '-')
                                 FROM estoque_produto ep
                                WHERE ep.codigo_estoque = {$value['codigo_estoque']}
                                  AND ep.inativado_em IS NULL
                                  AND ep.codigo_produto = produto.codigo_produto
                            )
                    ELSE '-'
                    END
                ) AS estoque" . $value['codigo_estoque'];
        }

        $this->select("
                {$this->table}.uuid_estoque_produto
              , {$this->table}.codigo_estoque
              , {$this->table}.codigo_produto
              , produto.nome AS nome_produto
              , produto.codigo_barras
              , produto.referencia_fornecedor
              , $selectEstoqueMain
            ", FALSE);

        $this->join("estoque", "estoque.codigo_estoque = {$this->table}.codigo_estoque");
        $this->join("produto", "produto.codigo_produto = {$this->table}.codigo_produto");

        $queryCompiled = $this->getCompiledSelect();

        // Retorno do DataGrid
        $queryStringSelect = "SELECT * FROM ({$queryCompiled}) AS x WHERE 1 = 1 {$configDataGrid->whereSearch} ORDER BY {$configDataGrid->fieldOrder} {$configDataGrid->orderDir} LIMIT {$configDataGrid->limit} OFFSET {$configDataGrid->offset}";
        $queryStringTotal = "SELECT COUNT(1) AS total FROM ({$queryCompiled}) AS x WHERE 1 = 1 {$configDataGrid->whereSearch}";
        $data['data'] = $this->query($queryStringSelect)->getResultArray();
        $data['count']['total'] = $this->query($queryStringTotal)->getResultArray()[0]['total'];
        return $data;
    }
}
