<?php

namespace App\Models\Venda;

use App\Models\BaseModel;
use App\Libraries\NativeSession;

class VendaModel extends BaseModel
{
    protected $table = 'venda';
    protected $primaryKey = 'codigo_venda';
    protected $uuidColumn = 'uuid_venda';

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
        'codigo_venda',
        'uuid_venda',
        'usuario_criacao',
        'usuario_alteracao',
        'usuario_inativacao',
        'criado_em',
        'alterado_em',
        'inativado_em',
        'codigo_empresa',
        'codigo_vendedor',
        'codigo_cliente',
        'cpf_cnpj',
        'codigo_cadastro_metodo_pagamento',
        'valor_bruto',
        'valor_entrada',
        'valor_desconto',
        'valor_troco',
        'valor_liquido',
        'parcelas',
        'observacao',
        'estornado_em'
    ];

    /**
     * Busca os registros para o Datagrid
     * @param array $dadosDataGrid Dados da tabela do dataGrid
     * @param string $condicoes Where de condições
     */
    public function getDataGrid(array $dadosDataGrid, string $condicoes = "1=1")
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');
        $configDataGrid = $this->configDataGrid($dadosDataGrid);
        $condicoes = "{$condicoes} {$configDataGrid->whereSearch}";

        $this->select("
            {$this->table}.uuid_venda
          , {$this->table}.codigo_venda
          , {$this->table}.valor_bruto
          , {$this->table}.valor_desconto
          , {$this->table}.valor_liquido
          , {$this->table}.observacao
          , COALESCE(c.razao_social,c.nome_fantasia) AS cliente
          , COALESCE(v.razao_social,v.nome_fantasia) AS vendedor
          , cmp.nome AS metodo_pagamento
          , TO_CHAR({$this->table}.criado_em, 'DD/MM/YYYY HH24:MI') AS data_venda
          , TO_CHAR({$this->table}.criado_em, 'DD/MM/YYYY HH24:MI') AS criado_em
          , TO_CHAR({$this->table}.estornado_em, 'DD/MM/YYYY HH24:MI') AS estornado_em
          , obter_nome_usuario({$this->table}.usuario_criacao) AS usuario_criacao
          , obter_nome_usuario({$this->table}.usuario_alteracao) AS usuario_alteracao
          , obter_nome_usuario({$this->table}.usuario_inativacao) AS usuario_inativacao
        ", FALSE);

        $this->join("cliente as c", " c.codigo_cliente = {$this->table}.codigo_cliente", "LEFT");
        $this->join("vendedor as v", " v.codigo_vendedor = {$this->table}.codigo_vendedor", "LEFT");
        $this->join("cadastro_metodo_pagamento cmp", " cmp.codigo_cadastro_metodo_pagamento = {$this->table}.codigo_cadastro_metodo_pagamento", "LEFT");

        /////// Inicio :: Filtros ///////
        $this->where("{$this->table}.codigo_empresa", $dadosEmpresa['codigo_empresa']);

        // Filtra o Tipo de Dados
        switch ($dadosDataGrid['status']) {
            case 0: // Estornados
                $this->where("{$this->table}.estornado_em IS NOT NULL");

                if (!empty($configDataGrid->filtros['data_inicio'])) {
                    $this->where("DATE({$this->table}.estornado_em) >=", $configDataGrid->filtros['data_inicio']);
                }

                if (!empty($configDataGrid->filtros['data_fim'])) {
                    $this->where("DATE({$this->table}.estornado_em) <=", $configDataGrid->filtros['data_fim']);
                }

                break;
            case 1: // Realizadas
                $this->where("{$this->table}.estornado_em IS NULL");

                if (!empty($configDataGrid->filtros['data_inicio'])) {
                    $this->where("DATE({$this->table}.criado_em) >=", $configDataGrid->filtros['data_inicio']);
                }

                if (!empty($configDataGrid->filtros['data_fim'])) {
                    $this->where("DATE({$this->table}.criado_em) <=", $configDataGrid->filtros['data_fim']);
                }

                break;
            default:
                break;
        }

        if (!empty($configDataGrid->filtros['codigo_vendedor'])) {
            $this->where("{$this->table}.codigo_vendedor", $configDataGrid->filtros['codigo_vendedor']);
        }

        if (!empty($configDataGrid->filtros['codigo_cliente'])) {
            $this->where("{$this->table}.codigo_cliente", $configDataGrid->filtros['codigo_cliente']);
        }

        if (!empty($configDataGrid->filtros['codigo_cadastro_metodo_pagamento'])) {
            $this->where("{$this->table}.codigo_cadastro_metodo_pagamento", $configDataGrid->filtros['codigo_cadastro_metodo_pagamento']);
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
     * Busca as Vendas para o Select2
     * @param array $filtros Filtros para a Busca
     */
    public function selectVenda(array $filtros)
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');

        $this->select("
            *
          , codigo_venda AS id
          , '' AS text
          ", FALSE);

        $this->where('codigo_empresa', $dadosEmpresa['codigo_empresa']);

        if (!empty($filtros)) {
            if (!empty($filtros['termo'])) {
                if (is_numeric($filtros['termo'])) {
                    $this->where("codigo_venda", $filtros['termo']);
                } else {
                    $termo = explode(' ', $filtros['termo']);
                    foreach ($termo as $key => $value) {
                        $this->where("codigo_venda ILIKE '%{$value}%'");
                    }
                }
            }

            if (!empty($filtros['codUuid'])) {
                $this->where("uuid_venda", $filtros['codUuid']);
            }
        }

        $this->limit(30);
        $this->offset(($filtros['page'] - 1) * 30);

        $data['itens'] = $this->find();
        $data['count'] = $this->countAllResults();
        return $data;
    }

    /**
     * Busca os produtos pertencentes a uma venda
     * @param array $filtros Filtros para a Busca
     */
    public function selectVendaProduto(array $filtros)
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');

        $builder = $this->builder('venda_produto');
        $builder->select("
            codigo_venda_produto
          , uuid_venda_produto
          , codigo_venda
          , codigo_produto
          , nome_produto
          , quantidade
          , valor_unitario
          , valor_desconto
          , valor_total
        ", FALSE);

        $builder->where('codigo_empresa', $dadosEmpresa['codigo_empresa']);
        $builder->where("inativado_em IS NULL");

        if (!empty($filtros['codVenda'])) {
            $builder->where("codigo_venda", $filtros['codVenda']);
        }

        $builder->orderBy(1, 'ASC');

        return $builder->get()->getResultArray();
    }
}
