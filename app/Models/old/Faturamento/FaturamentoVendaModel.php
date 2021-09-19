<?php

namespace App\Models\Faturamento;

use App\Libraries\NativeSession;
use App\Models\BaseModel;

class FaturamentoVendaModel extends BaseModel
{
    protected $table = 'faturamento_venda';
    protected $primaryKey = 'codigo_faturamento_venda';
    protected $uuidColumn = 'uuid_faturamento_venda';

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
        'codigo_faturamento_venda',
        'uuid_faturamento_venda',
        'usuario_criacao',
        'usuario_alteracao',
        'usuario_inativacao',
        'criado_em',
        'alterado_em',
        'inativado_em',
        'codigo_empresa',
        'codigo_faturamento',
        'codigo_venda',
        'valor_bruto',
        'valor_liquido',
        'valor_comissao'
    ];

    /**
     * Busca as Vendas do Faturamento para o PDF
     * @param int $codigoFaturamento Código do Faturamento
     */
    public function getFaturamentoVendas(int $codigo)
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');

        $this->select("
            v.codigo_vendedor
          , COALESCE(v.nome_fantasia, v.razao_social) AS vendedor
        ", FALSE);

        $this->join("faturamento f", "f.codigo_faturamento = {$this->table}.codigo_faturamento");
        $this->join("vendedor v", "v.codigo_vendedor = f.codigo_vendedor");
        $this->where("{$this->table}.codigo_faturamento", $codigo);
        $this->groupBy("1, 2");
        $this->where("{$this->table}.codigo_empresa", $dadosEmpresa['codigo_empresa']);
        $arr = $this->find();

        foreach ($arr as $key => $value) {
            $this->select("
                {$this->table}.codigo_faturamento
              , {$this->table}.codigo_venda
              , {$this->table}.valor_liquido
              , {$this->table}.valor_bruto
              , (SELECT COALESCE(COALESCE(c.razao_social, c.nome_fantasia), 'Não Identificado')
                   FROM cliente c
                  INNER JOIN venda v
                     ON v.codigo_venda = {$this->table}.codigo_venda
                  WHERE c.codigo_cliente = v.codigo_cliente
                ) AS cliente
              , TO_CHAR(v.criado_em, 'DD/MM/YYYY') AS data_venda
            ", FALSE);

            $this->where("{$this->table}.codigo_empresa", $dadosEmpresa['codigo_empresa']);
            $this->join("faturamento f", "f.codigo_faturamento = {$this->table}.codigo_faturamento");
            $this->join("venda v", "v.codigo_venda = {$this->table}.codigo_venda");
            $this->where("{$this->table}.codigo_faturamento", $codigo);
            // $this->where("v.codigo_vendedor", $value['codigo_vendedor']);
            $arr[$key]['vendas'] = $this->find();
        }

        return $arr;
    }
}
