<?php

namespace App\Models\Imovel;

use App\Models\BaseModel;
use App\Libraries\NativeSession;

class ImovelModel extends BaseModel
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
        'codigo_proprietario',
        'codigo_categoria_imovel',
        'codigo_tipo_imovel',
        'codigo_referencia',
        'quarto',
        'suite',
        'banheiro',
        'area_total',
        'area_construida',
        'vaga',
        'edicula',
        'mobilia',
        'condominio',
        'descricao',
        'destaque',
        'publicado',
        'diretorio_imagem',
        'valor_venda',
        'valor_aluguel'
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
            {$this->table}.uuid_imovel
          , {$this->table}.codigo_imovel
          , {$this->table}.codigo_referencia
          , {$this->table}.quarto
          , {$this->table}.suite
          , {$this->table}.banheiro
          , {$this->table}.area_total
          , {$this->table}.area_construida
          , {$this->table}.edicula
          , {$this->table}.mobilia
          , {$this->table}.vaga
          , {$this->table}.condominio
          , {$this->table}.publicado
          , {$this->table}.valor_venda
          , {$this->table}.valor_aluguel
          , (SELECT array_to_string(array_agg(ci.nome), ', ')
               FROM categoria_imovel ci
              WHERE ci.codigo_categoria_imovel = {$this->table}.codigo_categoria_imovel
                AND ci.inativado_em IS NULL
            ) AS categoria
             , (SELECT array_to_string(array_agg(ci.nome), ', ')
               FROM tipo_imovel ci
              WHERE ci.codigo_tipo_imovel = {$this->table}.codigo_tipo_imovel
                AND ci.inativado_em IS NULL
            ) AS tipo
          , TO_CHAR({$this->table}.criado_em, 'DD/MM/YYYY HH24:MI') AS criado_em
          , TO_CHAR({$this->table}.alterado_em, 'DD/MM/YYYY HH24:MI') AS alterado_em
          , TO_CHAR({$this->table}.inativado_em, 'DD/MM/YYYY HH24:MI') AS inativado_em
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
        if (!empty($configDataGrid->filtros['codigo_tipo_imovel'])) {
            $this->where("{$this->table}.codigo_tipo_imovel", $configDataGrid->filtros['codigo_tipo_imovel']);
        }
        if (!empty($configDataGrid->filtros['codigo_categoria_imovel'])) {
            $this->where("{$this->table}.codigo_categoria_imovel", $configDataGrid->filtros['codigo_categoria_imovel']);
        }
        if (!empty($configDataGrid->filtros['codigo_proprietario'])) {
            $this->where("{$this->table}.codigo_proprietario", $configDataGrid->filtros['codigo_proprietario']);
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
     * Busca os imoveis para o Select2
     * @param array $filtros Filtros para a Busca
     */
    public function selectImovel(array $filtros)
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');

        $this->select("
            codigo_imovel AS id
          , 'Ref: ' || codigo_referencia || ' - ('  || (select nome from tipo_imovel as ti where ti.codigo_tipo_imovel = imovel.codigo_tipo_imovel )|| ' - '  || (select bairro from endereco_imovel as ei where ei.codigo_imovel = imovel.codigo_imovel ) || ')' AS text
          , diretorio_imagem
        ", FALSE);

        $this->where('imovel.codigo_empresa', $dadosEmpresa['codigo_empresa']);

        if (!empty($filtros)) {
            if (!empty($filtros['termo'])) {
                if (is_numeric($filtros['termo'])) {
                    $termo = explode(' ', $filtros['termo']);
                    foreach ($termo as $key => $value) {
                        $this->where("imovel.codigo_referencia ILIKE '%{$value}%'");
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
