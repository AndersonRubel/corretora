<?php

namespace App\Models\Estoque;

use App\Models\BaseModel;

class EstoqueHistoricoItemModel extends BaseModel
{
    protected $table = 'estoque_historico_item';
    protected $primaryKey = 'codigo_estoque_historico_item';
    protected $uuidColumn = 'uuid_estoque_historico_item';

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
        'codigo_estoque_historico_item',
        'uuid_estoque_historico_item',
        'usuario_criacao',
        'usuario_alteracao',
        'usuario_inativacao',
        'criado_em',
        'alterado_em',
        'inativado_em',
        'codigo_empresa',
        'codigo_estoque',
        'codigo_historico',
        'codigo_produto',
        'codigo_entrada',
        'codigo_baixa',
        'transacao',
        'movimentacao_lote'
    ];

    /**
     * Busca os registros para o Datagrid de Item
     * @param array $dadosDataGrid Dados da tabela do dataGrid
     * @param string $condicoes Where de condições
     */
    public function getDataGrid(array $dadosDataGrid)
    {
        $configDataGrid = $this->configDataGrid($dadosDataGrid);
        $codProduto = "";
        $codEmpresa = "";

        // Se for Igual a f, traz todo o historico do Produto
        if (!empty($dadosDataGrid['onlyDay']) && $dadosDataGrid['onlyDay'] == "f") {
            $builder = $this->builder('estoque_historico');
            $builder->select("codigo_produto, codigo_empresa", FALSE);
            $builder->where("uuid_estoque_historico", $dadosDataGrid['uuid']);
            $data = $builder->get()->getRowArray();
            $codProduto = $data['codigo_produto'];
            $codEmpresa = $data['codigo_empresa'];
        }

        $this->select("
            {$this->table}.uuid_estoque_historico_item
          , {$this->table}.codigo_estoque_historico_item
          , {$this->table}.codigo_historico
          , {$this->table}.codigo_produto
          , {$this->table}.transacao
          , {$this->table}.movimentacao_lote
          , COALESCE(eb.quantidade::varchar, ee.quantidade::varchar, 'Não Definido') AS quantidade,
          , COALESCE(eb.observacao::varchar, ee.observacao::varchar, 'Não Definido') AS observacao,
          , p.codigo_barras
          , p.nome AS nome_produto
          , e.nome AS nome_estoque
          , TO_CHAR({$this->table}.criado_em, 'DD/MM/YYYY') AS data
          , {$this->table}.criado_em::TIME(0) AS hora
          , obter_nome_usuario({$this->table}.usuario_criacao) AS usuario_criacao
        ", FALSE);

        $this->join('estoque e', "e.codigo_estoque = {$this->table}.codigo_estoque");
        $this->join('produto p', "p.codigo_produto = {$this->table}.codigo_produto");
        $this->join('estoque_historico eh', "eh.codigo_estoque_historico = {$this->table}.codigo_historico");
        $this->join('estoque_entrada ee', "ee.codigo_estoque_entrada = {$this->table}.codigo_entrada", "LEFT");
        $this->join('estoque_baixa eb', "eb.codigo_estoque_baixa = {$this->table}.codigo_baixa", "LEFT");

        /////// Inicio :: Filtros ///////

        if (!empty($codProduto)) {
            $this->where("{$this->table}.codigo_produto", $codProduto);
            $this->where("{$this->table}.codigo_empresa", $codEmpresa);
        } else {
            $this->where("eh.uuid_estoque_historico", $dadosDataGrid['uuid']);
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
