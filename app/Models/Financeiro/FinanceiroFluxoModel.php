<?php

namespace App\Models\Financeiro;

use App\Models\BaseModel;
use App\Libraries\NativeSession;

class FinanceiroFluxoModel extends BaseModel
{
    protected $table = 'financeiro_fluxo';
    protected $primaryKey = 'codigo_financeiro_fluxo';
    protected $uuidColumn = 'uuid_financeiro_fluxo';

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
        'codigo_financeiro_fluxo',
        'uuid_financeiro_fluxo',
        'usuario_criacao',
        'usuario_alteracao',
        'usuario_inativacao',
        'criado_em',
        'alterado_em',
        'inativado_em',
        'codigo_empresa',
        'codigo_cadastro_metodo_pagamento',
        'codigo_cadastro_fluxo_tipo',
        'codigo_empresa_centro_custo',
        'codigo_empresa_conta',
        'codigo_fornecedor',
        'codigo_cliente',
        'codigo_vendedor',
        'codigo_faturamento',
        'nome',
        'data_vencimento',
        'data_pagamento',
        'data_competencia',
        'valor_bruto',
        'valor_juros',
        'valor_acrescimo',
        'valor_desconto',
        'valor_liquido',
        'valor_pago_parcial',
        'situacao',
        'observacao',
        'fluxo_lote',
        'numero_parcela',
        'insercao_automatica',
        'codigo_barras',
        'fluxo_empresa'
    ];

    /**
     * Busca os registros para o Datagrid
     * @param array $dadosDataGrid Dados da tabela do dataGrid
     * @param string $condicoes Where de condições
     */
    public function getDataGrid(array $dadosDataGrid)
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');
        $configDataGrid = $this->configDataGrid($dadosDataGrid);

        $this->select("
            {$this->table}.uuid_financeiro_fluxo
          , {$this->table}.codigo_financeiro_fluxo
          , {$this->table}.nome
          , {$this->table}.codigo_cadastro_fluxo_tipo
          , {$this->table}.codigo_barras
          , {$this->table}.valor_liquido
          , {$this->table}.valor_pago_parcial
          , (CASE WHEN {$this->table}.situacao = 't'
                  THEN 0
                  ELSE {$this->table}.valor_liquido - COALESCE((SELECT SUM(ffp.valor) FROM financeiro_fluxo_parcial ffp WHERE ffp.codigo_financeiro_fluxo = {$this->table}.codigo_financeiro_fluxo AND ffp.inativado_em IS NULL),0)
            END) AS saldo_devedor
          , COALESCE(COALESCE(f.razao_social, f.nome_fantasia), COALESCE(v.razao_social, v.nome_fantasia), COALESCE(c.razao_social, c.nome_fantasia), 'Não Informado') AS agente
          , cft.nome AS tipo
          , ecc.nome AS centro_custo
          , ec.nome AS conta
          , (CASE WHEN {$this->table}.situacao = 't' THEN 'Pago' ELSE 'Pendente' END) AS status
          , TO_CHAR({$this->table}.data_vencimento, 'DD/MM/YYYY') AS data_vencimento
          , TO_CHAR({$this->table}.data_pagamento, 'DD/MM/YYYY') AS data_pagamento
          , TO_CHAR({$this->table}.data_competencia, 'MM/YYYY') AS data_competencia
          , TO_CHAR({$this->table}.criado_em, 'DD/MM/YYYY HH24:MI') AS criado_em
          , TO_CHAR({$this->table}.alterado_em, 'DD/MM/YYYY HH24:MI') AS alterado_em
          , TO_CHAR({$this->table}.inativado_em, 'DD/MM/YYYY HH24:MI') AS inativado_em
          , obter_nome_usuario({$this->table}.usuario_criacao) AS usuario_criacao
          , obter_nome_usuario({$this->table}.usuario_alteracao) AS usuario_alteracao
          , obter_nome_usuario({$this->table}.usuario_inativacao) AS usuario_inativacao
        ", FALSE);

        $this->join("cadastro_fluxo_tipo cft", "cft.codigo_cadastro_fluxo_tipo = {$this->table}.codigo_cadastro_fluxo_tipo");
        $this->join("empresa_centro_custo ecc", "ecc.codigo_empresa_centro_custo = {$this->table}.codigo_empresa_centro_custo");
        $this->join("empresa_conta ec", "ec.codigo_empresa_conta = {$this->table}.codigo_empresa_conta");

        $this->join("cliente c", "c.codigo_cliente = {$this->table}.codigo_cliente", "LEFT");
        $this->join("fornecedor f", "f.codigo_fornecedor = {$this->table}.codigo_fornecedor", "LEFT");
        $this->join("vendedor v", "v.codigo_vendedor = {$this->table}.codigo_vendedor", "LEFT");

        /////// Inicio :: Filtros ///////

        if (!empty($configDataGrid->filtros['codigo_empresa'])) {
            $this->where("{$this->table}.codigo_empresa", $configDataGrid->filtros['codigo_empresa']);
        } else {
            $this->where("{$this->table}.codigo_empresa", $dadosEmpresa['codigo_empresa']);
        }


        if (!empty($configDataGrid->filtros['tipo_data'])) {
            if ($configDataGrid->filtros['tipo_data'] == 'pagamento') {
                $this->where("DATE({$this->table}.data_pagamento) >=", $configDataGrid->filtros['data_inicio']);
                $this->where("DATE({$this->table}.data_pagamento) <=", $configDataGrid->filtros['data_fim']);
            } else if ($configDataGrid->filtros['tipo_data'] == 'vencimento') {
                $this->where("DATE({$this->table}.data_vencimento) >=", $configDataGrid->filtros['data_inicio']);
                $this->where("DATE({$this->table}.data_vencimento) <=", $configDataGrid->filtros['data_fim']);
            } else if ($configDataGrid->filtros['tipo_data'] == 'lancamento') {
                $this->where("DATE({$this->table}.criado_em) >=", $configDataGrid->filtros['data_inicio']);
                $this->where("DATE({$this->table}.criado_em) <=", $configDataGrid->filtros['data_fim']);
            }
        }

        if (!empty($configDataGrid->filtros['codigo_fornecedor'])) {
            $this->where("{$this->table}.codigo_fornecedor", $configDataGrid->filtros['codigo_fornecedor']);
        }

        if (!empty($configDataGrid->filtros['codigo_cliente'])) {
            $this->where("{$this->table}.codigo_cliente", $configDataGrid->filtros['codigo_cliente']);
        }

        if (!empty($configDataGrid->filtros['codigo_usuario'])) {
            $this->where("{$this->table}.codigo_usuario", $configDataGrid->filtros['codigo_usuario']);
        }

        if (!empty($configDataGrid->filtros['codigo_empresa_conta'])) {
            $this->where("{$this->table}.codigo_empresa_conta", $configDataGrid->filtros['codigo_empresa_conta']);
        }

        if (!empty($configDataGrid->filtros['codigo_empresa_centro_custo'])) {
            $this->where("{$this->table}.codigo_empresa_centro_custo", $configDataGrid->filtros['codigo_empresa_centro_custo']);
        }

        if (!empty($configDataGrid->filtros['codigo_cadastro_fluxo_tipo'])) {
            $this->where("{$this->table}.codigo_cadastro_fluxo_tipo", $configDataGrid->filtros['codigo_cadastro_fluxo_tipo']);
        }

        if (!empty($configDataGrid->filtros['codigo_cadastro_metodo_pagamento'])) {
            $this->where("{$this->table}.codigo_cadastro_metodo_pagamento", $configDataGrid->filtros['codigo_cadastro_metodo_pagamento']);
        }

        if (!empty($configDataGrid->filtros['insercao_automatica'])) {
            $this->where("{$this->table}.insercao_automatica", $configDataGrid->filtros['insercao_automatica']);
        }

        if (!empty($configDataGrid->filtros['situacao'])) {
            $this->where("{$this->table}.situacao", $configDataGrid->filtros['situacao']);
        }

        if (!empty($configDataGrid->filtros['incluir_estorno'])) {
        }

        /////// Fim :: Filtros ///////

        $queryCompiled = $this->getCompiledSelect();

        $data['data'] = $this->query("
                                    (SELECT main_query.uuid_financeiro_fluxo
                                          , main_query.codigo_financeiro_fluxo
                                          , main_query.nome
                                          , main_query.codigo_barras
                                          , (CASE WHEN main_query.codigo_cadastro_fluxo_tipo = 1
                                                  THEN '<span style=\"color: green;\"> ' || NUMBER_FORMAT(main_query.valor_liquido) || '</span>'
                                                  WHEN main_query.codigo_cadastro_fluxo_tipo = 2
                                                  THEN '<span style=\"color: red;\"> ' || NUMBER_FORMAT(main_query.valor_liquido) || '</span>'
                                                  ELSE 'R$' || NUMBER_FORMAT(main_query.valor_liquido)
                                                   END
                                            ) AS valor_liquido
                                          , NUMBER_FORMAT(main_query.valor_pago_parcial) AS valor_pago_parcial
                                          , (CASE WHEN main_query.codigo_cadastro_fluxo_tipo = 1
                                                  THEN '<span style=\"color: green;\"> ' || NUMBER_FORMAT(main_query.saldo_devedor) || '</span>'
                                                  WHEN main_query.codigo_cadastro_fluxo_tipo = 2
                                                  THEN '<span style=\"color: red;\"> ' || NUMBER_FORMAT(main_query.saldo_devedor) || '</span>'
                                                  ELSE 'R$' || NUMBER_FORMAT(main_query.saldo_devedor)
                                                   END
                                            ) AS saldo_devedor
                                          , main_query.agente
                                          , main_query.tipo
                                          , main_query.centro_custo
                                          , main_query.conta
                                          , main_query.status
                                          , main_query.data_vencimento
                                          , main_query.data_pagamento
                                          , main_query.data_competencia
                                          , main_query.criado_em
                                          , main_query.alterado_em
                                          , main_query.inativado_em
                                          , main_query.usuario_criacao
                                          , main_query.usuario_alteracao
                                          , main_query.usuario_inativacao
                                       FROM ({$queryCompiled}) AS main_query
                                      ORDER BY {$configDataGrid->fieldOrder} {$configDataGrid->orderDir}
                                      LIMIT {$configDataGrid->limit}
                                     OFFSET {$configDataGrid->offset}
                                    )
                                    UNION ALL
                                    (SELECT null AS uuid_financeiro_fluxo
                                          , null AS codigo_financeiro_fluxo
                                          , null AS nome
                                          , null AS codigo_barras
                                          , '<b>Total</b>: ' || NUMBER_FORMAT(COALESCE(SUM(main_query.valor_liquido), 0.0)) AS valor_liquido
                                          , null AS valor_pago_parcial
                                          , null AS saldo_devedor
                                          , null AS agente
                                          , null AS tipo
                                          , null AS centro_custo
                                          , null AS conta
                                          , null AS status
                                          , null AS data_vencimento
                                          , null AS data_pagamento
                                          , null AS data_competencia
                                          , null AS criado_em
                                          , null AS alterado_em
                                          , null AS inativado_em
                                          , null AS usuario_criacao
                                          , null AS usuario_alteracao
                                          , null AS usuario_inativacao
                                       FROM (SELECT *
                                               FROM ({$queryCompiled}) AS main_query
                                               ORDER BY {$configDataGrid->fieldOrder} {$configDataGrid->orderDir}
                                               LIMIT {$configDataGrid->limit}
                                              OFFSET {$configDataGrid->offset}
                                            ) as main_query
                                    )
        ")->getResultArray();

        // Retorno do DataGrid
        $queryStringTotal = "SELECT COUNT(1) AS total FROM ({$queryCompiled}) AS x WHERE 1 = 1 {$configDataGrid->whereSearch}";
        $data['count']['total'] = $this->query($queryStringTotal)->getResultArray()[0]['total'];
        return $data;
    }

    /**
     * Busca os registros para o Datagrid de Resumo
     * @param array $dadosDataGrid Dados da tabela do dataGrid
     * @param string $condicoes Where de condições
     */
    public function getDataGridResumo(array $dadosDataGrid)
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');
        $configDataGrid = $this->configDataGrid($dadosDataGrid);

        $this->select("
            {$this->table}.uuid_financeiro_fluxo
          , {$this->table}.codigo_financeiro_fluxo
          , {$this->table}.nome
          , TO_CHAR({$this->table}.data_vencimento, 'DD/MM/YYYY') AS data_vencimento
          , '' AS agente
          , {$this->table}.valor_liquido - COALESCE((SELECT SUM(ffp.valor) FROM financeiro_fluxo_parcial ffp WHERE ffp.codigo_financeiro_fluxo = {$this->table}.codigo_financeiro_fluxo AND ffp.inativado_em IS NULL),0) AS valor
        ", FALSE);

        $this->where("{$this->table}.codigo_empresa", $dadosEmpresa['codigo_empresa']);

        /////// Inicio :: Filtros ///////

        // Somente os Pendentes
        $this->where("{$this->table}.situacao", 'f');

        // Filtra o Tipo de Dados
        switch ($dadosDataGrid['status']) {
            case 1: // A Receber - Hoje
                $this->where("{$this->table}.codigo_cadastro_fluxo_tipo", 1);
                $this->where("DATE({$this->table}.data_vencimento) = DATE(NOW())");
                break;
            case 2: // A Receber - Vencidas
                $this->where("{$this->table}.codigo_cadastro_fluxo_tipo", 1);
                $this->where("DATE({$this->table}.data_vencimento) < DATE(NOW())");
                break;
            case 3: // A Receber - Futuras
                $this->where("{$this->table}.codigo_cadastro_fluxo_tipo", 1);
                $this->where("DATE({$this->table}.data_vencimento) > DATE(NOW())");
                break;
            case 4: // A Pagar - Hoje
                $this->where("{$this->table}.codigo_cadastro_fluxo_tipo", 2);
                $this->where("DATE({$this->table}.data_vencimento) = DATE(NOW())");
                break;
            case 5: // A Pagar - Vencidas
                $this->where("{$this->table}.codigo_cadastro_fluxo_tipo", 2);
                $this->where("DATE({$this->table}.data_vencimento) < DATE(NOW())");
                break;
            case 6: // A Pagar - Futuras
                $this->where("{$this->table}.codigo_cadastro_fluxo_tipo", 2);
                $this->where("DATE({$this->table}.data_vencimento) > DATE(NOW())");
                break;
            default:
                break;
        }

        /////// Fim :: Filtros ///////

        $queryCompiled = $this->getCompiledSelect();

        $data['data'] = $this->query("
                                    (SELECT main_query.uuid_financeiro_fluxo
                                          , main_query.codigo_financeiro_fluxo
                                          , main_query.nome
                                          , main_query.data_vencimento
                                          , main_query.agente
                                          , NUMBER_FORMAT(main_query.valor) AS valor
                                       FROM ({$queryCompiled}) AS main_query
                                      ORDER BY {$configDataGrid->fieldOrder} {$configDataGrid->orderDir}
                                      LIMIT {$configDataGrid->limit}
                                     OFFSET {$configDataGrid->offset}
                                    )
                                    UNION ALL
                                    (SELECT null AS uuid_financeiro_fluxo
                                          , null as codigo_financeiro_fluxo
                                          , null as nome
                                          , null as data_vencimento
                                          , null as agente
                                          , '<b>Total</b>: ' || NUMBER_FORMAT(COALESCE(SUM(main_query.valor), 0.0)) AS valor
                                       FROM (SELECT *
                                               FROM ({$queryCompiled}) AS main_query
                                               ORDER BY {$configDataGrid->fieldOrder} {$configDataGrid->orderDir}
                                               LIMIT {$configDataGrid->limit}
                                              OFFSET {$configDataGrid->offset}
                                            ) as main_query
                                    )
        ")->getResultArray();

        // Retorno do DataGrid
        $queryStringTotal = "SELECT COUNT(1) AS total FROM ({$queryCompiled}) AS x WHERE 1 = 1 {$configDataGrid->whereSearch}";
        $data['count']['total'] = $this->query($queryStringTotal)->getResultArray()[0]['total'];
        return $data;
    }

    /**
     * Busca os FLuxos para o Select2
     * @param array $filtros Filtros para a Busca
     */
    public function selectFluxo(array $filtros)
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');

        $this->select("
            *
          , codigo_financeiro_fluxo AS id
          , uuid_financeiro_fluxo
          , nome AS text
          , {$this->table}.valor_liquido - COALESCE((SELECT SUM(ffp.valor)
                                                       FROM financeiro_fluxo_parcial ffp
                                                      WHERE ffp.codigo_financeiro_fluxo = {$this->table}.codigo_financeiro_fluxo
                                                        AND ffp.inativado_em IS NULL
                                                    ), 0) AS saldo_devedor
        ", FALSE);

        $this->where('codigo_empresa', $dadosEmpresa['codigo_empresa']);

        if (!empty($filtros)) {
            if (!empty($filtros['termo'])) {
                if (is_numeric($filtros['termo'])) {
                    $this->where("codigo_financeiro_fluxo", $filtros['termo']);
                } else {
                    $termo = explode(' ', $filtros['termo']);
                    foreach ($termo as $key => $value) {
                        $this->where("nome ILIKE '%{$value}%'");
                    }
                }
            }

            if (!empty($filtros['codUuid'])) {
                $this->where("uuid_financeiro_fluxo", $filtros['codUuid']);
            }

            if (!empty($filtros['faturamentoCodigo'])) {
                $this->where("codigo_faturamento", $filtros['faturamentoCodigo']);
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
     * Busca o fluxo com os dados para impressao
     * @param string $uuid UUID do Fluxo
     */
    public function selectFluxoImpressao(string $uuid)
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');

        $this->select("
            {$this->table}.uuid_financeiro_fluxo
          , {$this->table}.codigo_financeiro_fluxo
          , {$this->table}.nome
          , {$this->table}.valor_liquido
          , {$this->table}.valor_bruto
          , {$this->table}.valor_acrescimo
          , {$this->table}.valor_juros
          , {$this->table}.valor_desconto
          , {$this->table}.valor_pago_parcial
          , COALESCE(f.nome_fantasia, f.razao_social) AS fornecedor
          , COALESCE(v.nome_fantasia, v.razao_social) AS vendedor
          , COALESCE(c.nome_fantasia, c.razao_social) AS cliente
          , c.cpf_cnpj AS cliente_cpf_cnpj
          , cft.nome AS tipo
          , {$this->table}.situacao
          , {$this->table}.codigo_empresa
          , {$this->table}.observacao
          , cmp.nome AS metodo_pagamento
          , TO_CHAR({$this->table}.data_vencimento, 'DD/MM/YYYY') AS data_vencimento
          , TO_CHAR({$this->table}.data_pagamento, 'DD/MM/YYYY') AS data_pagamento
          , TO_CHAR({$this->table}.criado_em, 'DD/MM/YYYY HH24:MI') AS criado_em
          , obter_nome_usuario({$this->table}.usuario_criacao) AS usuario_criacao
        ", FALSE);

        $this->join("cadastro_fluxo_tipo cft", "cft.codigo_cadastro_fluxo_tipo = {$this->table}.codigo_cadastro_fluxo_tipo");
        $this->join("cadastro_metodo_pagamento cmp", "cmp.codigo_cadastro_metodo_pagamento = {$this->table}.codigo_cadastro_metodo_pagamento");

        $this->join("cliente c", "c.codigo_cliente = {$this->table}.codigo_cliente", "LEFT");
        $this->join("fornecedor f", "f.codigo_fornecedor = {$this->table}.codigo_fornecedor", "LEFT");
        $this->join("vendedor v", "v.codigo_vendedor = {$this->table}.codigo_vendedor", "LEFT");

        $this->where("{$this->table}.codigo_empresa", $dadosEmpresa['codigo_empresa']);
        $this->where("{$this->table}.uuid_financeiro_fluxo", $uuid);

        return $this->first();
    }

    /**
     * Busca os Fluxos Parciais para o Select2
     * @param array $filtros Filtros para a Busca
     */
    public function selectFluxoParcial(array $filtros)
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');

        $builder = $this->builder('financeiro_fluxo_parcial');
        $builder->select("
            financeiro_fluxo_parcial.*
          , financeiro_fluxo_parcial.codigo_financeiro_fluxo_parcial AS id
          , financeiro_fluxo.nome AS text
          , cadastro_metodo_pagamento.nome AS metodo_pagamento
        ", FALSE);

        $builder->join("financeiro_fluxo", "financeiro_fluxo.codigo_financeiro_fluxo = financeiro_fluxo_parcial.codigo_financeiro_fluxo");
        $builder->join("cadastro_metodo_pagamento", "cadastro_metodo_pagamento.codigo_cadastro_metodo_pagamento = financeiro_fluxo_parcial.codigo_cadastro_metodo_pagamento");

        if (!empty($filtros)) {
            if (!empty($filtros['termo'])) {
                if (is_numeric($filtros['termo'])) {
                    $builder->where("financeiro_fluxo_parcial.codigo_financeiro_fluxo_parcial", $filtros['termo']);
                } else {
                    $termo = explode(' ', $filtros['termo']);
                    foreach ($termo as $key => $value) {
                        $builder->where("financeiro_fluxo.nome ILIKE '%{$value}%'");
                    }
                    $builder->where("financeiro_fluxo_parcial.inativado_em IS NULL");
                }
            }

            if (!empty($filtros['codUuid'])) {
                $builder->where("financeiro_fluxo.uuid_financeiro_fluxo", $filtros['codUuid']);
            }
        }

        $builder->where("financeiro_fluxo_parcial.inativado_em IS NULL");

        $builder->where('financeiro_fluxo_parcial.codigo_empresa', $dadosEmpresa['codigo_empresa']);

        $builder->limit(30);
        $builder->offset(($filtros['page'] - 1) * 30);

        $data['itens'] = $builder->get()->getResultArray();
        $data['count'] = $builder->countAllResults();
        return $data;
    }

    /**
     * Busca os dados para o gráfico de resumo financeiro
     * @param array $filtros Filtros para a Busca
     */
    public function getGraficoResumo(array $filtros)
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');

        $this->select("
            COALESCE(SUM(valor_liquido), 0) AS valor
          , TO_CHAR(data_vencimento, 'DD/MM/YYYY') AS data
          , codigo_cadastro_fluxo_tipo
        ", FALSE);

        if (!empty($filtros)) {
            if (!empty($filtros['data'])) {
                $mesAno = explode('-', $filtros['data']);
                $this->where("data_vencimento >=", date('Y-m-d', mktime(0, 0, 0, $mesAno[1], 1, $mesAno[0])));
                $this->where("data_vencimento <=", date('Y-m-d', mktime(0, 0, 0, $mesAno[1], date('t'), $mesAno[0])));
            }
        }

        $this->where('codigo_empresa', $dadosEmpresa['codigo_empresa']);

        $this->groupBy(['data_vencimento', 'codigo_cadastro_fluxo_tipo']);
        $this->orderBy('data_vencimento');
        return $this->find();
    }

    /**
     * Busca os dados para os totais do resumo financeiro
     * @param array $filtros Filtros para a Busca
     */
    public function selectFluxoSumarioTotais(array $filtros)
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');

        /////// Inicio :: Filtros ///////

        $whereEmpresa     = "";
        $whereData        = "";
        $whereConta       = "";
        $whereMetodoPag   = "";
        $whereCentroCusto = "";
        $whereCliente     = "";
        $whereFornecedor  = "";
        $whereUsuario     = "";

        if (!empty($filtros)) {

            if (!empty($filtros['codigo_empresa'])) {
                $whereEmpresa = " AND codigo_empresa = {$filtros['codigo_empresa']}";
            } else {
                $whereEmpresa = " AND codigo_empresa = {$dadosEmpresa['codigo_empresa']}";
            }

            if (!empty($filtros['tipo_data'])) {
                if ($filtros['tipo_data'] == 'pagamento') {
                    $whereData = " AND (DATE(data_pagamento) >= {$filtros['data_inicio']} AND DATE(data_pagamento) <= {$filtros['data_fim']})";
                } else if ($filtros['tipo_data'] == 'vencimento') {
                    $whereData = " AND (DATE(data_vencimento) >= {$filtros['data_inicio']} AND DATE(data_vencimento) <= {$filtros['data_fim']})";
                } else if ($filtros['tipo_data'] == 'lancamento') {
                    $whereData = " AND (DATE(criado_em) >= {$filtros['data_inicio']} AND DATE(criado_em) <= {$filtros['data_fim']})";
                }
            }

            if (!empty($filtros['codigo_empresa_conta'])) {
                $whereConta = " AND codigo_empresa_conta = {$filtros['codigo_empresa_conta']}";
            }

            if (!empty($filtros['codigo_fornecedor'])) {
                $whereFornecedor = " AND codigo_fornecedor = {$filtros['codigo_fornecedor']}";
            }

            if (!empty($filtros['codigo_cliente'])) {
                $whereCliente = " AND codigo_cliente = {$filtros['codigo_cliente']}";
            }

            if (!empty($filtros['codigo_usuario'])) {
                $whereUsuario = " AND codigo_usuario = {$filtros['codigo_usuario']}";
            }

            if (!empty($filtros['codigo_empresa_centro_custo'])) {
                $whereCentroCusto = " AND codigo_empresa_centro_custo = {$filtros['codigo_empresa_centro_custo']}";
            }

            if (!empty($filtros['codigo_cadastro_metodo_pagamento'])) {
                $whereMetodoPag = " AND codigo_cadastro_metodo_pagamento = {$filtros['codigo_cadastro_metodo_pagamento']}";
            }
        }
        /////// Fim :: Filtros ///////

        return $this->query("
        (
            SELECT
            'Receitas' AS nome,
            (
                SELECT COALESCE(SUM(valor_liquido), 0)
                  FROM financeiro_fluxo
                 WHERE codigo_cadastro_fluxo_tipo = 1
                   AND inativado_em IS NULL AND situacao = 't'
                   {$whereEmpresa} {$whereData} {$whereConta} {$whereMetodoPag} {$whereCentroCusto} {$whereCliente} {$whereFornecedor} {$whereUsuario}
            ) AS valor
        )

        UNION ALL

        (
            SELECT
            'Despesas' AS nome,
            (
                SELECT COALESCE(SUM(valor_liquido), 0)
                  FROM financeiro_fluxo
                 WHERE codigo_cadastro_fluxo_tipo = 2
                   AND inativado_em IS NULL AND situacao = 't'
                   {$whereEmpresa} {$whereData} {$whereConta} {$whereMetodoPag} {$whereCentroCusto} {$whereCliente} {$whereFornecedor} {$whereUsuario}
            ) AS valor
        )

        UNION ALL

        (
            SELECT
            'A Receber' AS nome,
            (
                SELECT COALESCE(SUM(valor_liquido), 0)
                  FROM financeiro_fluxo
                 WHERE codigo_cadastro_fluxo_tipo = 1
                   AND inativado_em IS NULL AND situacao = 'f'
                   {$whereEmpresa} {$whereData} {$whereConta} {$whereMetodoPag} {$whereCentroCusto} {$whereCliente} {$whereFornecedor} {$whereUsuario}
            )
            -
            (
                SELECT COALESCE(SUM(valor_pago_parcial), 0)
                  FROM financeiro_fluxo
                 WHERE codigo_cadastro_fluxo_tipo = 1
                   AND inativado_em IS NULL AND situacao = 'f'
                   {$whereEmpresa} {$whereData} {$whereConta} {$whereMetodoPag} {$whereCentroCusto} {$whereCliente} {$whereFornecedor} {$whereUsuario}
            ) AS valor
        )

        UNION ALL

        (
            SELECT
            'A Pagar' AS nome,
            (
                SELECT COALESCE(SUM(valor_liquido), 0)
                  FROM financeiro_fluxo
                 WHERE codigo_cadastro_fluxo_tipo = 2
                   AND inativado_em IS NULL AND situacao = 'f'
                   {$whereEmpresa} {$whereData} {$whereConta} {$whereMetodoPag} {$whereCentroCusto} {$whereCliente} {$whereFornecedor} {$whereUsuario}
            )
            -
            (
                SELECT COALESCE(SUM(valor_pago_parcial), 0)
                  FROM financeiro_fluxo
                 WHERE codigo_cadastro_fluxo_tipo = 2
                   AND inativado_em IS NULL AND situacao = 'f'
                   {$whereEmpresa} {$whereData} {$whereConta} {$whereMetodoPag} {$whereCentroCusto} {$whereCliente} {$whereFornecedor} {$whereUsuario}
            ) AS valor
        )
        ")->getResultArray();
    }

    /**
     * Saldo Total do Financeiro conforme filtros
     * @param array $filtros Filtros para a Busca
     */
    public function selectFluxoSumarioSaldo(array $filtros)
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');

        /////// Inicio :: Filtros ///////

        $whereEmpresa     = "";
        $whereData        = "";
        $whereConta       = "";

        if (!empty($filtros)) {

            if (!empty($filtros['codigo_empresa'])) {
                $whereEmpresa = " AND codigo_empresa = {$filtros['codigo_empresa']}";
            } else {
                $whereEmpresa = " AND codigo_empresa = {$dadosEmpresa['codigo_empresa']}";
            }

            if (!empty($filtros['tipo_data'])) {
                if ($filtros['tipo_data'] == 'pagamento') {
                    $whereData = " AND (DATE(data_pagamento) >= {$filtros['data_inicio']} AND DATE(data_pagamento) <= {$filtros['data_fim']})";
                } else if ($filtros['tipo_data'] == 'vencimento') {
                    $whereData = " AND (DATE(data_vencimento) >= {$filtros['data_inicio']} AND DATE(data_vencimento) <= {$filtros['data_fim']})";
                } else if ($filtros['tipo_data'] == 'lancamento') {
                    $whereData = " AND (DATE(criado_em) >= {$filtros['data_inicio']} AND DATE(criado_em) <= {$filtros['data_fim']})";
                }
            }

            if (!empty($filtros['codigo_empresa_conta'])) {
                $whereConta = " AND codigo_empresa_conta = {$filtros['codigo_empresa_conta']}";
            }
        }
        /////// Fim :: Filtros ///////

        return $this->query("
        (
            SELECT
            (
                SELECT COALESCE(SUM(valor_liquido), 0)
                  FROM financeiro_fluxo
                 WHERE codigo_cadastro_fluxo_tipo = 1
                   AND inativado_em IS NULL
                   AND situacao = 't'
                   {$whereEmpresa}
                   {$whereData}
                   {$whereConta}
            )
            -
            (
                SELECT COALESCE(SUM(valor_liquido), 0)
                  FROM financeiro_fluxo
                 WHERE codigo_cadastro_fluxo_tipo = 2
                   AND inativado_em IS NULL
                   AND situacao = 't'
                   {$whereEmpresa}
                   {$whereData}
                   {$whereConta}
            ) AS valor
        )
        ")->getResultArray()[0]['valor'];
    }

    /**
     * Busca os Fluxos em Aberto que o Cliente possui
     * @param array $filtros Filtros para a Busca
     */
    public function selectFluxosEmAberto(array $filtros)
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');

        $this->select("
            financeiro_fluxo.*
        ", FALSE);

        $this->join('cliente', 'cliente.codigo_cliente = financeiro_fluxo.codigo_cliente');

        $this->where('financeiro_fluxo.data_pagamento IS NULL');
        $this->where('financeiro_fluxo.inativado_em IS NULL');
        $this->where('financeiro_fluxo.codigo_empresa', $dadosEmpresa['codigo_empresa']);
        $this->where('cliente.uuid_cliente', $filtros['clienteUuid']);
        $this->orderBy('financeiro_fluxo.criado_em', 'ASC');

        return $this->find();
    }
}
