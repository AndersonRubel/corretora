<?php

namespace App\Models\imovel;

use App\Models\BaseModel;
use App\Libraries\NativeSession;

class imovelModel extends BaseModel
{
    protected $table = 'imovel';
    protected $primaryKey = 'codigo_imovel';
    protected $uuidColumn = 'uuid_imovel';

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
        'codigo_imovel',
        'uuid_imovel',
        'criado_em',
        'alterado_em',
        'inativado_em',
        'codigo_empresa',
        'codigo_endereco',
        'codigo_proprietario',
        'codigo_empresa_categoria',
        'codigo_referencia',
        'quarto',
        'suite',
        'banheiro',
        'area_util',
        'area_construida',
        'edicula',
        'mobilia',
        'condominio',
        'descricao',
        'destaque',
        'publicado',
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

        /**
         *  , (SELECT array_to_string(array_agg(ec.nome), ', ')
         *      FROM imovel_categoria pc
         *     INNER JOIN empresa_categoria ec
         *        ON ec.codigo_empresa_categoria = pc.codigo_empresa_categoria
         *     WHERE pc.codigo_imovel = {$this->table}.codigo_imovel
         *       AND pc.inativado_em IS NULL
         *   ) AS categorias
         */

        $this->select("
            {$this->table}.uuid_imovel
          , {$this->table}.codigo_imovel
          , {$this->table}.referencia
          , '' AS categorias
          , TO_CHAR({$this->table}.criado_em, 'DD/MM/YYYY HH24:MI') AS criado_em
          , TO_CHAR({$this->table}.alterado_em, 'DD/MM/YYYY HH24:MI') AS alterado_em
          , TO_CHAR({$this->table}.inativado_em, 'DD/MM/YYYY HH24:MI') AS inativado_em
          , obter_nome_usuario({$this->table}.usuario_criacao) AS usuario_criacao
          , obter_nome_usuario({$this->table}.usuario_alteracao) AS usuario_alteracao
          , obter_nome_usuario({$this->table}.usuario_inativacao) AS usuario_inativacao
        ", FALSE);

        /////// Inicio :: Filtros ///////
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

        if (!empty($configDataGrid->filtros['codigo_imovel'])) {
            $this->where("{$this->table}.codigo_imovel", $configDataGrid->filtros['codigo_imovel']);
        }

        if (!empty($configDataGrid->filtros['categorias'])) {
            $categorias = $configDataGrid->filtros['categorias'];
            foreach (explode(',', $categorias) as $key => $value) {
                $this->where("{$value} IN (SELECT pc.codigo_empresa_categoria
                                             FROM imovel_categoria pc
                                            WHERE pc.codigo_imovel = {$this->table}.codigo_imovel
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

    /**
     * Busca os imovels para o Select2
     * @param array $filtros Filtros para a Busca
     */
    public function selectImovel(array $filtros)
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');

        // se vier o codigo do Estoque adiciona a SubSelect para buscar a quantidade no estoque desejado
        $fieldEstoqueAtual = "0 AS estoque_atual";
        if (!empty($filtros)) {
            if (!empty($filtros['codEstoque'])) {
                $fieldEstoqueAtual = "(SELECT SUM(COALESCE(estoque_imovel.estoque_atual, 0))
                                         FROM estoque_imovel
                                        WHERE estoque_imovel.codigo_imovel = imovel.codigo_imovel
                                          AND estoque_imovel.inativado_em IS NULL
                                    ) AS estoque_atual";
            }
        }

        $this->select("
            codigo_imovel AS id
          , nome AS text
          , codigo_barras
          , COALESCE(fornecedor.nome_fantasia, fornecedor.razao_social) AS fornecedor
          , {$fieldEstoqueAtual}
        ", FALSE);

        $this->join('fornecedor', 'fornecedor.codigo_fornecedor = imovel.codigo_fornecedor');
        $this->where('imovel.codigo_empresa', $dadosEmpresa['codigo_empresa']);

        if (!empty($filtros)) {
            if (!empty($filtros['termo'])) {
                if (is_numeric($filtros['termo'])) {
                    $this->where("imovel.codigo_imovel", $filtros['termo']);
                } else {
                    $termo = explode(' ', $filtros['termo']);
                    foreach ($termo as $key => $value) {
                        $this->where("imovel.nome ILIKE '%{$value}%'");
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

        return $this->find();
    }
}
