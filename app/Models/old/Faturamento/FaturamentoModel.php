<?php

namespace App\Models\Faturamento;

use App\Models\BaseModel;
use App\Libraries\NativeSession;

class FaturamentoModel extends BaseModel
{
    protected $table = 'faturamento';
    protected $primaryKey = 'codigo_faturamento';
    protected $uuidColumn = 'uuid_faturamento';

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
        'codigo_faturamento',
        'uuid_faturamento',
        'usuario_criacao',
        'usuario_alteracao',
        'usuario_inativacao',
        'criado_em',
        'alterado_em',
        'inativado_em',
        'codigo_empresa',
        'codigo_vendedor',
        'periodo_inicio',
        'periodo_fim',
        'codigo_comissao',
        'percentual_comissao',
        'valor_bruto',
        'valor_comissao',
        'valor_acrescimo',
        'valor_desconto',
        'valor_entrada',
        'valor_liquido',
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
            uuid_faturamento
          , TO_CHAR({$this->table}.periodo_inicio, 'DD/MM/YYYY') AS periodo_inicio
          , TO_CHAR({$this->table}.periodo_fim, 'DD/MM/YYYY') AS periodo_fim
          , valor_bruto
          , valor_liquido
          , valor_comissao
          , percentual_comissao
          , COALESCE(v.nome_fantasia, v.razao_social) AS vendedor
          , TO_CHAR({$this->table}.criado_em, 'DD/MM/YYYY HH24:MI') AS criado_em
          , TO_CHAR({$this->table}.alterado_em, 'DD/MM/YYYY HH24:MI') AS alterado_em
          , obter_nome_usuario({$this->table}.usuario_criacao) AS usuario_criacao
          , obter_nome_usuario({$this->table}.usuario_alteracao) AS usuario_alteracao
        ", FALSE);

        $this->join("vendedor v", "v.codigo_vendedor = faturamento.codigo_vendedor");
        $this->where("{$this->table}.inativado_em IS NULL");

        /////// Inicio :: Filtros ///////

        if (!empty($configDataGrid->filtros['codigo_empresa'])) {
            $this->where("{$this->table}.codigo_empresa", $configDataGrid->filtros['codigo_empresa']);
        } else {
            $this->where("{$this->table}.codigo_empresa", $dadosEmpresa['codigo_empresa']);
        }

        if (!empty($configDataGrid->filtros['codigo_vendedor'])) {
            $this->where("{$this->table}.codigo_vendedor", $configDataGrid->filtros['codigo_vendedor']);
        }

        if (!empty($configDataGrid->filtros['periodo_inicio'])) {
            $this->where("{$this->table}.periodo_inicio >=", $configDataGrid->filtros['periodo_inicio']);
        }

        if (!empty($configDataGrid->filtros['periodo_fim'])) {
            $this->where("{$this->table}.periodo_fim <=", $configDataGrid->filtros['periodo_fim']);
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
     * Busca os Faturamentos para o Select2
     * @param array $filtros Filtros para a Busca
     */
    public function selectFaturamento(array $filtros)
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');

        $this->select("
            codigo_faturamento AS id
          , 'Vend. ' || COALESCE(v.nome_fantasia, v.razao_social) || ' | Período: ' || TO_CHAR(periodo_inicio, 'DD/MM/YYYY') || ' - ' || TO_CHAR(periodo_fim, 'DD/MM/YYYY')  AS text
        ", FALSE);

        $this->join("vendedor v", "v.codigo_vendedor = faturamento.codigo_vendedor");
        $this->where('faturamento.codigo_empresa', $dadosEmpresa['codigo_empresa']);

        if (!empty($filtros)) {
            if (!empty($filtros['termo'])) {
                if (is_numeric($filtros['termo'])) {
                    $this->where("codigo_faturamento", $filtros['termo']);
                } else {
                    $termo = explode(' ', $filtros['termo']);
                    foreach ($termo as $key => $value) {
                        $this->where("
                            periodo_inicio ILIKE '%{$value}%'
                         OR periodo_fim ILIKE '%{$value}%'
                        ");
                    }
                }
            }

            if (!empty($filtros['faturamentoUuid'])) {
                $this->where("uuid_faturamento", $filtros['faturamentoUuid']);
            }
        }

        $this->orderBy(1, 'ASC');

        $this->limit(30);
        $this->offset(($filtros['page'] - 1) * 30);

        $data['itens'] = $this->find();
        $data['count'] = $this->countAllResults();
        return $data;
    }

    /**
     * Busca as Categorias da Empresa para o Select2
     * @param array $filtros Filtros para a Busca
     */
    public function selectVenda(array $filtros)
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');

        $builder = $this->builder('venda');
        $builder->select("
            codigo_venda
          , venda.codigo_vendedor
          , venda.codigo_cliente
          , cmp.nome AS metodo_pagamento
          , valor_bruto
          , valor_entrada
          , valor_desconto
          , valor_troco
          , valor_liquido
          , parcelas
          , venda.observacao
          , estornado_em
          , COALESCE(COALESCE(c.razao_social, c.nome_fantasia), 'Não Identificado') AS cliente
          , c.cpf_cnpj
          , TO_CHAR(venda.criado_em, 'DD/MM/YYYY') AS criado_em
        ", FALSE);

        $builder->join("cliente c", " c.codigo_cliente = venda.codigo_cliente", "LEFT");
        $builder->join("cadastro_metodo_pagamento cmp", " cmp.codigo_cadastro_metodo_pagamento = venda.codigo_cadastro_metodo_pagamento");

        $builder->where('venda.codigo_empresa', $dadosEmpresa['codigo_empresa']);

        $builder->where('venda.estornado_em IS NULL');
        $builder->where('venda.inativado_em IS NULL');
        // Filtra apenas as vendas que nao estao em outro faturamento
        $builder->where("(venda.codigo_venda NOT IN (SELECT fv.codigo_venda
                                                      FROM faturamento_venda fv
                                                     WHERE fv.codigo_venda = venda.codigo_venda
                                                       AND fv.inativado_em IS NULL
                                                    )
                        )
        ");

        if (!empty($filtros)) {

            if (!empty($filtros['periodo_inicio'])) {
                $builder->where("DATE(venda.criado_em) >=", $filtros['periodo_inicio']);
            }

            if (!empty($filtros['periodo_fim'])) {
                $builder->where("DATE(venda.criado_em) <=", $filtros['periodo_fim']);
            }

            if (!empty($filtros['codigo_vendedor'])) {
                $builder->where("venda.codigo_vendedor", $filtros['codigo_vendedor']);
            }
        }

        $builder->limit(20);
        $builder->orderBy(1, 'ASC');

        return $builder->get()->getResultArray();
    }
}
