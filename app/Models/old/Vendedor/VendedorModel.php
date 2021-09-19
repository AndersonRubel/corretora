<?php

namespace App\Models\Vendedor;

use App\Models\BaseModel;
use App\Libraries\NativeSession;

class VendedorModel extends BaseModel
{
    protected $table = 'vendedor';
    protected $primaryKey = 'codigo_vendedor';
    protected $uuidColumn = 'uuid_vendedor';

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
        'codigo_vendedor',
        'uuid_vendedor',
        'usuario_criacao',
        'usuario_alteracao',
        'usuario_inativacao',
        'criado_em',
        'alterado_em',
        'inativado_em',
        'codigo_empresa',
        'tipo_pessoa',
        'razao_social',
        'nome_fantasia',
        'cpf_cnpj',
        'data_nascimento',
        'data_inicio_venda',
        'telefone',
        'celular',
        'endereco',
        'observacao'
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
            uuid_vendedor
          , COALESCE(razao_social, nome_fantasia) AS nome
          , cpf_cnpj
          , telefone
          , celular
          , TO_CHAR(criado_em, 'DD/MM/YYYY HH24:MI') AS criado_em
          , TO_CHAR(alterado_em, 'DD/MM/YYYY HH24:MI') AS alterado_em
          , TO_CHAR(inativado_em, 'DD/MM/YYYY HH24:MI') AS inativado_em
          , obter_nome_usuario(usuario_criacao) AS usuario_criacao
          , obter_nome_usuario(usuario_alteracao) AS usuario_alteracao
          , obter_nome_usuario(usuario_inativacao) AS usuario_inativacao
        ", FALSE);

        $this->where("{$this->table}.codigo_empresa", $dadosEmpresa['codigo_empresa']);

        // Filtra o Tipo de Dados
        switch ($dadosDataGrid['status']) {
            case 0: // Inativos
                $this->where("{$this->table}.inativado_em IS NOT NULL");
                break;
            case 1: // Ativos
                $this->where("{$this->table}.inativado_em IS NULL");
                break;
            default:
                break;
        }

        $queryCompiled = $this->getCompiledSelect();

        // Retorno do DataGrid
        $queryStringSelect = "SELECT * FROM ({$queryCompiled}) AS x WHERE 1 = 1 {$configDataGrid->whereSearch} ORDER BY {$configDataGrid->fieldOrder} {$configDataGrid->orderDir} LIMIT {$configDataGrid->limit} OFFSET {$configDataGrid->offset}";
        $queryStringTotal = "SELECT COUNT(1) AS total FROM ({$queryCompiled}) AS x WHERE 1 = 1 {$configDataGrid->whereSearch}";
        $data['data'] = $this->query($queryStringSelect)->getResultArray();
        $data['count']['total'] = $this->query($queryStringTotal)->getResultArray()[0]['total'];
        return $data;
    }

    /**
     * Busca os Vendedores para o Select2
     * @param array $filtros Filtros para a Busca
     */
    public function selectVendedor(array $filtros)
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');

        $this->select("
            codigo_vendedor AS id
          , COALESCE(razao_social, nome_fantasia) AS text
        ", FALSE);

        $this->where('codigo_empresa', $dadosEmpresa['codigo_empresa']);

        if (!empty($filtros)) {
            if (!empty($filtros['termo'])) {
                if (is_numeric($filtros['termo'])) {
                    $this->where("codigo_vendedor", $filtros['termo']);
                } else {
                    $termo = explode(' ', $filtros['termo']);
                    foreach ($termo as $key => $value) {
                        $this->where("
                            razao_social ILIKE '%{$value}%'
                            OR nome_fantasia ILIKE '%{$value}%'
                        ");
                    }
                }
            }
        }

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
     */
    public function getDataGridEstoque(array $dadosDataGrid)
    {
        $configDataGrid = $this->configDataGrid($dadosDataGrid);
        $dadosEmpresa = (new NativeSession(true))->get('empresa');

        $builder = $this->builder("estoque");
        $builder->select("
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

        $builder->join('estoque_produto ep', 'ep.codigo_estoque = estoque.codigo_estoque');
        $builder->join('produto', 'produto.codigo_produto = ep.codigo_produto');

        /////// Inicio :: Filtros ///////
        $builder->where("estoque.codigo_empresa", $dadosEmpresa['codigo_empresa']);
        $builder->where("estoque.codigo_vendedor", $configDataGrid->filtros['codigo_vendedor']);

        if (!empty($configDataGrid->filtros['codigo_produto'])) {
            $builder->where("produto.codigo_produto", $configDataGrid->filtros['codigo_produto']);
        }

        /////// Fim :: Filtros ///////

        $queryCompiled = $builder->getCompiledSelect();

        // Retorno do DataGrid
        $queryStringSelect = "SELECT * FROM ({$queryCompiled}) AS x WHERE 1 = 1 {$configDataGrid->whereSearch} ORDER BY {$configDataGrid->fieldOrder} {$configDataGrid->orderDir} LIMIT {$configDataGrid->limit} OFFSET {$configDataGrid->offset}";
        $queryStringTotal = "SELECT COUNT(1) AS total FROM ({$queryCompiled}) AS x WHERE 1 = 1 {$configDataGrid->whereSearch}";
        $data['data'] = $this->query($queryStringSelect)->getResultArray();
        $data['count']['total'] = $this->query($queryStringTotal)->getResultArray()[0]['total'];
        return $data;
    }

    /**
     * Busca os registros para o Datagrid de Historico de Produtos
     * @param array $dadosDataGrid Dados da tabela do dataGrid
     */
    public function getDataGridHistoricoVenda(array $dadosDataGrid)
    {
        $configDataGrid = $this->configDataGrid($dadosDataGrid);
        $dadosEmpresa = (new NativeSession(true))->get('empresa');
        $dadosUsuario = (new NativeSession(true))->get('usuario');

        $builder = $this->builder("venda_produto");
        $builder->select("
            uuid_venda_produto
          , venda_produto.codigo_produto
          , produto.codigo_barras
          , venda_produto.nome_produto
          , venda_produto.quantidade
          , venda_produto.valor_total
          , venda.codigo_venda
          , COALESCE(vendedor.nome_fantasia, vendedor.razao_social) AS vendedor
          , TO_CHAR(venda.criado_em, 'DD/MM/YYYY') AS criado_em
        ", FALSE);

        $builder->join("venda", "venda.codigo_venda = venda_produto.codigo_venda");
        $builder->join("vendedor", "vendedor.codigo_vendedor = venda.codigo_vendedor");
        $builder->join("produto", "produto.codigo_produto = venda_produto.codigo_produto");

        /////// Inicio :: Filtros ///////

        $builder->where("venda.codigo_empresa", $dadosEmpresa['codigo_empresa']);
        $builder->where("venda.codigo_vendedor", $configDataGrid->filtros['codigo_vendedor']);
        $builder->where("venda_produto.inativado_em IS NULL");
        $builder->where("venda.inativado_em IS NULL");

        if (!empty($configDataGrid->filtros['data_inicio'])) {
            $builder->where("DATE(venda.criado_em) >=", $configDataGrid->filtros['data_inicio']);
        }

        if (!empty($configDataGrid->filtros['data_fim'])) {
            $builder->where("DATE(venda.criado_em) <=", $configDataGrid->filtros['data_fim']);
        }

        if (!empty($configDataGrid->filtros['codigo_cadastro_metodo_pagamento'])) {
            $builder->where("venda.codigo_cadastro_metodo_pagamento", $configDataGrid->filtros['codigo_cadastro_metodo_pagamento']);
        }

        if (!empty($configDataGrid->filtros['codigo_produto'])) {
            $builder->where("produto.codigo_produto", $configDataGrid->filtros['codigo_produto']);
        }

        /////// Fim :: Filtros ///////

        $queryCompiled = $builder->getCompiledSelect();

        // Retorno do DataGrid
        $queryStringSelect = "SELECT * FROM ({$queryCompiled}) AS x WHERE 1 = 1 {$configDataGrid->whereSearch} ORDER BY {$configDataGrid->fieldOrder} {$configDataGrid->orderDir} LIMIT {$configDataGrid->limit} OFFSET {$configDataGrid->offset}";
        $queryStringTotal = "SELECT COUNT(1) AS total FROM ({$queryCompiled}) AS x WHERE 1 = 1 {$configDataGrid->whereSearch}";
        $data['data'] = $this->query($queryStringSelect)->getResultArray();
        $data['count']['total'] = $this->query($queryStringTotal)->getResultArray()[0]['total'];
        return $data;
    }

    /**
     * Busca os registros para o Datagrid de Historico Financeiro
     * @param array $dadosDataGrid Dados da tabela do dataGrid
     */
    public function getDataGridHistoricoFinanceiro(array $dadosDataGrid)
    {
        $configDataGrid = $this->configDataGrid($dadosDataGrid);
        $dadosEmpresa = (new NativeSession(true))->get('empresa');

        $builder = $this->builder("financeiro_fluxo");
        $builder->select("
            uuid_financeiro_fluxo
          , financeiro_fluxo.codigo_financeiro_fluxo
          , financeiro_fluxo.nome
          , financeiro_fluxo.situacao
          , financeiro_fluxo.valor_liquido
          , financeiro_fluxo.valor_bruto
          , financeiro_fluxo.valor_desconto
          , financeiro_fluxo.valor_pago_parcial
          , COALESCE(cmp.nome, 'Não Informado') AS metodo_pagamento
          , COALESCE(financeiro_fluxo.numero_parcela,1) AS numero_parcela
          , TO_CHAR(financeiro_fluxo.data_vencimento, 'DD/MM/YYYY') AS data_vencimento
          , TO_CHAR(financeiro_fluxo.data_pagamento, 'DD/MM/YYYY') AS data_pagamento
          , TO_CHAR(financeiro_fluxo.criado_em, 'DD/MM/YYYY') AS criado_em
        ", FALSE);

        $builder->join("cadastro_metodo_pagamento cmp", "cmp.codigo_cadastro_metodo_pagamento = financeiro_fluxo.codigo_cadastro_metodo_pagamento", "LEFT");

        /////// Inicio :: Filtros ///////
        $builder->where("financeiro_fluxo.codigo_empresa", $dadosEmpresa['codigo_empresa']);
        $builder->where("financeiro_fluxo.codigo_vendedor", $configDataGrid->filtros['codigo_vendedor']);
        $builder->where("financeiro_fluxo.inativado_em IS NULL");

        if (!empty($configDataGrid->filtros['data_inicio'])) {
            $builder->where("DATE(financeiro_fluxo.criado_em) >=", $configDataGrid->filtros['data_inicio']);
        }

        if (!empty($configDataGrid->filtros['data_fim'])) {
            $builder->where("DATE(financeiro_fluxo.criado_em) <=", $configDataGrid->filtros['data_fim']);
        }

        if (!empty($configDataGrid->filtros['codigo_cadastro_metodo_pagamento'])) {
            $builder->where("financeiro_fluxo.codigo_cadastro_metodo_pagamento", $configDataGrid->filtros['codigo_cadastro_metodo_pagamento']);
        }

        if (!empty($configDataGrid->filtros['exibir_pago'])) {
            if ($configDataGrid->filtros['exibir_pago'] == 't') {
                $builder->where("financeiro_fluxo.data_pagamento IS NOT NULL");
            } else if ($configDataGrid->filtros['exibir_pago'] == 'f') {
                $builder->where("financeiro_fluxo.data_pagamento IS NULL");
            }
        }
        /////// Fim :: Filtros ///////

        $queryCompiled = $builder->getCompiledSelect();

        // Retorno do DataGrid
        $queryStringSelect = "SELECT * FROM ({$queryCompiled}) AS x WHERE 1 = 1 {$configDataGrid->whereSearch} ORDER BY {$configDataGrid->fieldOrder} {$configDataGrid->orderDir} LIMIT {$configDataGrid->limit} OFFSET {$configDataGrid->offset}";
        $queryStringTotal = "SELECT COUNT(1) AS total FROM ({$queryCompiled}) AS x WHERE 1 = 1 {$configDataGrid->whereSearch}";
        $data['data'] = $this->query($queryStringSelect)->getResultArray();
        $data['count']['total'] = $this->query($queryStringTotal)->getResultArray()[0]['total'];
        return $data;
    }
}
