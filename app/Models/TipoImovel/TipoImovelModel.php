<?php

namespace App\Models\TipoImovel;

use App\Models\BaseModel;
use App\Libraries\NativeSession;

class TipoImovelModel extends BaseModel
{
    protected $table = 'tipo_imovel';
    protected $primaryKey = 'codigo_tipo_imovel';
    protected $uuidColumn = 'uuid_tipo_imovel';

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
        'codigo_tipo_imovel',
        'uuid_tipo_imovel',
        'inativado_em',
        'codigo_empresa',
        'nome'
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
            DISTINCT ON ({$this->table}.uuid_tipo_imovel) {$this->table}.uuid_tipo_imovel
          , {$this->table}.codigo_tipo_imovel
          , {$this->table}.nome
          , TO_CHAR({$this->table}.criado_em, 'DD/MM/YYYY HH24:MI') AS criado_em
          , TO_CHAR({$this->table}.alterado_em, 'DD/MM/YYYY HH24:MI') AS alterado_em
          , TO_CHAR({$this->table}.inativado_em, 'DD/MM/YYYY HH24:MI') AS inativado_em
        ", FALSE);
        $this->where('codigo_empresa', $dadosEmpresa['codigo_empresa']);

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
     * Busca os tipo_imovels para o Select2
     * @param array $filtros Filtros para a Busca
     */
    public function selectTipoImovel(array $filtros)
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');

        $this->select("
            DISTINCT ON (tipo_imovel.codigo_tipo_imovel) tipo_imovel.codigo_tipo_imovel AS id
          , nome AS text
        ", FALSE);

        if (!empty($filtros)) {
            if (!empty($filtros['termo'])) {
                if (is_numeric($filtros['termo'])) {
                    $this->where("codigo_tipo_imovel", $filtros['termo']);
                } else {
                    $termo = explode(' ', $filtros['termo']);
                    foreach ($termo as $key => $value) {
                        $this->where("nome ILIKE '%{$value}%'");
                    }
                }
            }
        }

        $this->where('codigo_empresa', $dadosEmpresa['codigo_empresa']);

        $this->orderBy(1, 'ASC');
        $this->orderBy(2, 'ASC');

        $this->limit(30);
        $this->offset(($filtros['page'] - 1) * 30);

        $data['itens'] = $this->find();
        $data['count'] = $this->countAllResults();
        return $data;
    }
}
