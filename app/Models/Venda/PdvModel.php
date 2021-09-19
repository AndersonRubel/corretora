<?php

namespace App\Models\Venda;

use App\Models\BaseModel;
use App\Libraries\NativeSession;

class PdvModel extends BaseModel
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
        'observacao',
        'estornado_em'
    ];

    /**
     * Busca os Clientes
     * @param array $filtros Filtro
     */
    public function selectCliente(array $filtros)
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');

        $builder = $this->builder('cliente');

        if (!empty($filtros['modo']) && $filtros['modo'] == 'completo') {
            $builder->select("
                *
              , nome_fantasia || ' (' || COALESCE(razao_social, '') || ')' AS text
            ", FALSE);
        } else {
            $builder->select("
                codigo_cliente AS id
              , nome_fantasia || ' (' || COALESCE(razao_social, '') || ')' AS text
              , cpf_cnpj
            ", FALSE);
        }

        /////// Inicio :: Filtros ///////

        $builder->where('codigo_empresa', $dadosEmpresa['codigo_empresa']);

        if (!empty($filtros)) {
            if (!empty($filtros['termo'])) {
                if (is_numeric($filtros['termo']) && !in_array(strlen($filtros['termo']), [11, 14])) {
                    $builder->where("codigo_cliente", $filtros['termo']);
                } else {
                    $termo = explode(' ', $filtros['termo']);
                    foreach ($termo as $key => $value) {
                        $builder->where("(
                               cpf_cnpj ILIKE '%{$value}%'
                            OR razao_social ILIKE '%{$value}%'
                            OR nome_fantasia ILIKE '%{$value}%'
                        )");
                    }
                }
            }
        }

        /////// Fim :: Filtros ///////

        $builder->orderBy(2, 'ASC');

        $builder->limit(30);
        $builder->offset(($filtros['page'] - 1) * 30);

        $data['itens'] = $builder->get()->getResultArray();
        $data['count'] = $builder->countAllResults();
        return $data;
    }

    /**
     * Busca os Produtos
     * @param array $filtros Filtro
     */
    public function selectProduto(array $filtros)
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');
        $dadosUsuario = (new NativeSession(true))->get('usuario');

        $builder = $this->builder('estoque');

        $builder->select("
            DISTINCT ON (ep.codigo_produto) ep.codigo_produto AS id
          , produto.nome || ' (' || COALESCE(produto.codigo_barras, produto.referencia_fornecedor) ||')' AS text
          , COALESCE(ep.estoque_atual, 0) AS estoque_atual
          , COALESCE(ep.valor_venda, 0) AS valor
          , produto.nome AS produto
          , produto.codigo_barras
          , produto.referencia_fornecedor
          , (SELECT fornecedor.nome_fantasia || ' (' || COALESCE(fornecedor.razao_social, fornecedor.nome_fantasia) ||')'
               FROM fornecedor
              WHERE fornecedor.codigo_fornecedor = produto.codigo_fornecedor
            ) AS fornecedor
        ", FALSE);

        $builder->join('estoque_produto ep', 'ep.codigo_estoque = estoque.codigo_estoque');
        $builder->join('produto', 'produto.codigo_produto = ep.codigo_produto');

        /////// Inicio :: Filtros ///////

        if (!empty($filtros)) {
            if (!empty($filtros['termo'])) {
                if (is_numeric($filtros['termo'])) {
                    $builder->where("ep.codigo_produto", $filtros['termo']);
                } else {
                    $termo = explode(' ', $filtros['termo']);
                    foreach ($termo as $key => $value) {
                        $builder->where("
                                (produto.codigo_barras ILIKE '%{$value}%'
                            OR produto.nome ILIKE '%{$value}%'
                            OR produto.referencia_fornecedor ILIKE '%{$value}%')
                        ");
                    }
                }
            }
        }

        $builder->where("estoque.codigo_estoque", $dadosUsuario['codigo_estoque']);
        $builder->where('ep.codigo_empresa', $dadosEmpresa['codigo_empresa']);

        /////// Fim :: Filtros ///////

        $builder->orderBy(1, 'ASC');
        $builder->orderBy(2, 'ASC');

        $builder->limit(30);
        $builder->offset(($filtros['page'] - 1) * 30);

        $data['itens'] = $builder->get()->getResultArray();
        $data['count'] = $builder->countAllResults();
        return $data;
    }

    /**
     * Busca os Metodos de Pagamentos
     * @param array $filtros Filtro
     */
    public function selectCadastroMetodoPagamento(array $filtros)
    {
        $builder = $this->builder('cadastro_metodo_pagamento');

        $builder->select("
            codigo_cadastro_metodo_pagamento AS id
          , nome AS text
        ", FALSE);


        /////// Inicio :: Filtros ///////

        if (!empty($filtros)) {
            if (!empty($filtros['termo'])) {
                if (is_numeric($filtros['termo'])) {
                    $builder->where("codigo_cadastro_metodo_pagamento", $filtros['termo']);
                } else {
                    $termo = explode(' ', $filtros['termo']);
                    foreach ($termo as $key => $value) {
                        $builder->where("nome ILIKE '%{$value}%'");
                    }
                }
            }
        }

        /////// Fim :: Filtros ///////

        $builder->orderBy(2, 'ASC');

        $builder->limit(30);
        $builder->offset(($filtros['page'] - 1) * 30);

        $data['itens'] = $builder->get()->getResultArray();
        $data['count'] = $builder->countAllResults();
        return $data;
    }
}
