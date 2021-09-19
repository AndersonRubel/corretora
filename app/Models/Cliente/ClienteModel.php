<?php

namespace App\Models\Cliente;

use App\Models\BaseModel;
use App\Libraries\NativeSession;

class ClienteModel extends BaseModel
{
    protected $table = 'cliente';
    protected $primaryKey = 'codigo_cliente';
    protected $uuidColumn = 'uuid_cliente';

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
        'codigo_cliente',
        'uuid_cliente',
        'usuario_criacao',
        'usuario_alteracao',
        'usuario_inativacao',
        'criado_em',
        'alterado_em',
        'inativado_em',
        'codigo_empresa',
        'codigo_vendedor',
        'tipo_pessoa',
        'razao_social',
        'nome_fantasia',
        'cpf_cnpj',
        'data_nascimento',
        'email',
        'telefone',
        'celular',
        'observacao',
        'saldo'
    ];

    /**
     * Busca os registros para o Datagrid
     * @param array $dadosDataGrid Dados da tabela do dataGrid
     * @param string $condicoes Where de condições
     */
    public function getDataGrid(array $dadosDataGrid, string $condicoes = "1=1")
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');
        $dadosUsuario = (new NativeSession(true))->get('usuario');
        $configDataGrid = $this->configDataGrid($dadosDataGrid);
        $condicoes = "{$condicoes} {$configDataGrid->whereSearch}";

        $this->select("
            uuid_cliente
          , COALESCE(razao_social, nome_fantasia) AS nome
          , email
          , cpf_cnpj
          , telefone
          , celular
          , COALESCE(saldo, 0) AS saldo
          , TO_CHAR(criado_em, 'DD/MM/YYYY HH24:MI') AS criado_em
          , TO_CHAR(alterado_em, 'DD/MM/YYYY HH24:MI') AS alterado_em
          , TO_CHAR(inativado_em, 'DD/MM/YYYY HH24:MI') AS inativado_em
          , obter_nome_usuario(usuario_criacao) AS usuario_criacao
          , obter_nome_usuario(usuario_alteracao) AS usuario_alteracao
          , obter_nome_usuario(usuario_inativacao) AS usuario_inativacao
        ", FALSE);

        $this->where("{$this->table}.codigo_empresa", $dadosEmpresa['codigo_empresa']);

        // Filtra apenas os clientes de cada vendedor
        $this->where("{$this->table}.codigo_vendedor", $dadosUsuario['codigo_vendedor']);


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
     * Busca os Clientes para o Select2
     * @param array $filtros Filtros para a Busca
     */
    public function selectCliente(array $filtros)
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');
        $dadosUsuario = (new NativeSession(true))->get('usuario');

        $this->select("
            codigo_cliente AS id
          , nome_fantasia || ' (' || COALESCE(razao_social, '') || ')' AS text
          , cpf_cnpj
        ", FALSE);

        $this->where('codigo_empresa', $dadosEmpresa['codigo_empresa']);

        // Filtra apenas os clientes de cada vendedor
        // $this->where("codigo_vendedor", $dadosUsuario['codigo_vendedor']);

        if (!empty($filtros)) {
            if (!empty($filtros['termo'])) {
                if (is_numeric($filtros['termo']) && !in_array(strlen($filtros['termo']), [11, 14])) {
                    $this->where("codigo_cliente", $filtros['termo']);
                } else {
                    $termo = explode(' ', $filtros['termo']);
                    foreach ($termo as $key => $value) {
                        $this->where("(
                            cpf_cnpj ILIKE '%{$value}%'
                         OR razao_social ILIKE '%{$value}%'
                         OR nome_fantasia ILIKE '%{$value}%'
                        )");
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
    public function getDataGridAniversariantes(array $dadosDataGrid)
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');
        $configDataGrid = $this->configDataGrid($dadosDataGrid);

        // Cliente
        $builderCliente = $this->builder("cliente");
        $builderCliente->select("
            uuid_cliente AS uuid
          , COALESCE(cliente.razao_social, cliente.nome_fantasia) || ' - (Cliente)' AS nome
          , TO_CHAR(data_nascimento, 'DD/MM') AS data_nascimento
          , COALESCE(email, 'Não Cadastrado') AS email
          , COALESCE(celular, 'Não Cadastrado') AS celular
        ", FALSE);
        $builderCliente->where("codigo_empresa", $dadosEmpresa['codigo_empresa']);
        $builderCliente->where("data_nascimento IS NOT NULL");

        if (!empty($configDataGrid->filtros['data_de'])) {
            $dataDe = explode('-', $configDataGrid->filtros['data_de']); // separa dia, mes e ano
            $dataDeDia = $dataDe[2]; // pega somente o mes
            $dataDeMes = $dataDe[1]; // pega somente o dia
            $builderCliente->where("(TO_CHAR(data_nascimento, 'MM-DD') >= '{$dataDeMes}-{$dataDeDia}')");
        }

        if (!empty($configDataGrid->filtros['data_ate'])) {
            $dataAte = explode('-', $configDataGrid->filtros['data_ate']); // separa dia, mes e ano
            $dataAteDia = $dataAte[2]; // pega somente o mes
            $dataAteMes = $dataAte[1]; // pega somente o dia
            $builderCliente->where("(TO_CHAR(data_nascimento, 'MM-DD') <= '{$dataAteMes}-{$dataAteDia}')");
        }

        $dadosCliente = $builderCliente->get()->getResultArray();

        // Vendedor
        $builderVendedor = $this->builder("vendedor");
        $builderVendedor->select("
            uuid_vendedor AS uuid
          , COALESCE(vendedor.nome_fantasia, vendedor.razao_social) || ' - (Vendedor)' AS nome
          , TO_CHAR(data_nascimento, 'DD/MM') AS data_nascimento
          , COALESCE(usuario.email, 'Não Cadastrado') AS email
          , COALESCE(COALESCE(vendedor.celular, usuario.celular), 'Não Cadastrado') AS celular
        ", FALSE);
        $builderVendedor->join("usuario", "usuario.codigo_vendedor = vendedor.codigo_vendedor");
        $builderVendedor->where("codigo_empresa", $dadosEmpresa['codigo_empresa']);
        $builderVendedor->where("data_nascimento IS NOT NULL");


        if (!empty($configDataGrid->filtros['data_de'])) {
            $dataDe = explode('-', $configDataGrid->filtros['data_de']); // separa dia, mes e ano
            $dataDeDia = $dataDe[2]; // pega somente o mes
            $dataDeMes = $dataDe[1]; // pega somente o dia
            $builderVendedor->where("(TO_CHAR(data_nascimento, 'MM-DD') >= '{$dataDeMes}-{$dataDeDia}')");
        }

        if (!empty($configDataGrid->filtros['data_ate'])) {
            $dataAte = explode('-', $configDataGrid->filtros['data_ate']); // separa dia, mes e ano
            $dataAteDia = $dataAte[2]; // pega somente o mes
            $dataAteMes = $dataAte[1]; // pega somente o dia
            $builderVendedor->where("(TO_CHAR(data_nascimento, 'MM-DD') <= '{$dataAteMes}-{$dataAteDia}')");
        }

        $dadosVendedor = $builderVendedor->get()->getResultArray();

        $data['data'] = array_merge($dadosCliente, $dadosVendedor);
        $data['count']['total'] = count($data['data']);
        return $data;
    }

    /**
     * Busca os Indicadores do Extrato do Cliente
     * @param int $codigoCliente Código do Cliente
     */
    public function getIndicadores(int $codigoCliente)
    {
        return $this->query("
        (
            SELECT
            'Total de Compras' AS nome
            , ( SELECT COUNT(*)
                  FROM venda
                 WHERE estornado_em IS NULL
                   AND codigo_cliente = {$codigoCliente}
            ) || ' compras' AS descricao
            , (SELECT COALESCE(SUM(valor_liquido), 0)
                 FROM venda
                WHERE estornado_em IS NULL
                  AND codigo_cliente = {$codigoCliente}
            ) AS valor
        )

        UNION ALL

        (
            SELECT
            'Valor Pago ' AS nome
            , '' AS descricao
            , (SELECT COALESCE(SUM(valor_liquido), 0)
                 FROM financeiro_fluxo
                WHERE codigo_cadastro_fluxo_tipo = 1
                  AND inativado_em IS NULL
                  AND situacao = 't'
                  AND codigo_cliente = {$codigoCliente}
            ) AS valor
        )

        UNION ALL

        (
            SELECT
            'Valor Aberto' AS nome
            , '' AS descricao
            , (SELECT COALESCE(SUM(valor_liquido), 0)
                 FROM financeiro_fluxo
                WHERE codigo_cadastro_fluxo_tipo = 1
                  AND inativado_em IS NULL AND situacao = 'f'
                  AND codigo_cliente = {$codigoCliente}
            ) AS valor
        )")->getResultArray();
    }

    /**
     * Busca os registros para o Datagrid
     * @param array $dadosDataGrid Dados da tabela do dataGrid
     */
    public function getDataGridExtrato(array $dadosDataGrid)
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');
        $configDataGrid = $this->configDataGrid($dadosDataGrid);
        $dadosUsuario = (new NativeSession(true))->get('usuario');

        // Cliente
        $builderExtrato = $this->builder("financeiro_fluxo");
        $builderExtrato->select("
            uuid_financeiro_fluxo AS uuid
          , codigo_venda
          , cmp.nome AS metodo_pagamento
          , valor_bruto
          , valor_desconto
          , valor_liquido
          , valor_pago_parcial
          , COALESCE(numero_parcela,1) AS numero_parcela
          , fluxo_lote
          , TO_CHAR(financeiro_fluxo.criado_em, 'DD/MM/YYYY') AS criado_em
          , TO_CHAR(data_vencimento, 'DD/MM/YYYY') AS data_vencimento
          , TO_CHAR(data_pagamento, 'DD/MM/YYYY') AS data_pagamento
        ", FALSE);

        $builderExtrato->join("cadastro_metodo_pagamento cmp", "cmp.codigo_cadastro_metodo_pagamento = financeiro_fluxo.codigo_cadastro_metodo_pagamento");

        $builderExtrato->where("codigo_empresa", $dadosEmpresa['codigo_empresa']);
        $builderExtrato->where("codigo_vendedor", $dadosUsuario['codigo_vendedor']);
        $builderExtrato->where("codigo_cliente", $configDataGrid->filtros['codigo_cliente']);
        $builderExtrato->where("financeiro_fluxo.inativado_em IS NULL");

        // Filtros
        if (!empty($configDataGrid->filtros['data_inicio'])) {
            $builderExtrato->where("DATE(financeiro_fluxo.criado_em) >=", $configDataGrid->filtros['data_inicio']);
        }

        if (!empty($configDataGrid->filtros['data_fim'])) {
            $builderExtrato->where("DATE(financeiro_fluxo.criado_em) <=", $configDataGrid->filtros['data_fim']);
        }

        if (!empty($configDataGrid->filtros['codigo_cadastro_metodo_pagamento'])) {
            $builderExtrato->where("financeiro_fluxo.codigo_cadastro_metodo_pagamento", $configDataGrid->filtros['codigo_cadastro_metodo_pagamento']);
        }

        if (!empty($configDataGrid->filtros['exibir_pago'])) {
            if ($configDataGrid->filtros['exibir_pago'] == 't') {
                $builderExtrato->where("financeiro_fluxo.data_pagamento IS NOT NULL");
            } else if ($configDataGrid->filtros['exibir_pago'] == 'f') {
                $builderExtrato->where("financeiro_fluxo.data_pagamento IS NULL");
            }
        }

        $queryCompiled = $builderExtrato->getCompiledSelect();

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
    public function getDataGridHistoricoProduto(array $dadosDataGrid)
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
        $builder->where("venda.codigo_vendedor", $dadosUsuario['codigo_vendedor']);
        $builder->where("venda.codigo_cliente", $configDataGrid->filtros['codigo_cliente']);
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
        $builder->where("financeiro_fluxo.codigo_cliente", $configDataGrid->filtros['codigo_cliente']);
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

    /**
     * Busca os registros para o Datagrid de Historico Saldo
     * @param array $dadosDataGrid Dados da tabela do dataGrid
     */
    public function getDataGridHistoricoSaldo(array $dadosDataGrid)
    {
        $configDataGrid = $this->configDataGrid($dadosDataGrid);
        $dadosEmpresa = (new NativeSession(true))->get('empresa');

        $builder = $this->builder("cliente_extrato");
        $builder->select("
            uuid_cliente_extrato
          , codigo_cliente_extrato
          , descricao
          , tipo_transacao
          , valor
          , saldo
          , TO_CHAR(criado_em, 'DD/MM/YYYY') AS criado_em
          , obter_nome_usuario(usuario_criacao) AS usuario_criacao
        ", FALSE);

        /////// Inicio :: Filtros ///////

        $builder->where("codigo_empresa", $dadosEmpresa['codigo_empresa']);
        $builder->where("codigo_cliente", $configDataGrid->filtros['codigo_cliente']);
        $builder->where("inativado_em IS NULL");

        if (!empty($configDataGrid->filtros['data_inicio'])) {
            $builder->where("DATE(criado_em) >=", $configDataGrid->filtros['data_inicio']);
        }

        if (!empty($configDataGrid->filtros['data_fim'])) {
            $builder->where("DATE(criado_em) <=", $configDataGrid->filtros['data_fim']);
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
     * Busca os Valores em Aberto que o Cliente possui
     * @param array $filtros Filtros para a Busca
     */
    public function selectValorEmAberto(array $filtros)
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');

        $builder = $this->builder("financeiro_fluxo");
        $builder->select("COALESCE(SUM(financeiro_fluxo.valor_liquido), 0) AS valor", FALSE);
        $builder->join('cliente', 'cliente.codigo_cliente = financeiro_fluxo.codigo_cliente');

        $builder->where('financeiro_fluxo.data_pagamento IS NULL');
        $builder->where('financeiro_fluxo.inativado_em IS NULL');
        $builder->where('financeiro_fluxo.codigo_empresa', $dadosEmpresa['codigo_empresa']);
        $builder->where('cliente.uuid_cliente', $filtros['clienteUuid']);

        return $builder->get()->getResultArray();
    }
}
