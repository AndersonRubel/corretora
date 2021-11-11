<?php

namespace App\Models\Cadastro;

use App\Models\BaseModel;
use App\Libraries\NativeSession;

class CadastroGrupoModel extends BaseModel
{
    protected $table = 'cadastro_grupo';
    protected $primaryKey = 'codigo_cadastro_grupo';
    protected $uuidColumn = 'uuid_cadastro_grupo';

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
        'codigo_cadastro_grupo',
        'uuid_cadastro_grupo',
        'criado_em',
        'alterado_em',
        'inativado_em',
        'nome',
        'slug',
        'codigo_empresa'
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
            codigo_cadastro_grupo
          , uuid_cadastro_grupo
          , COALESCE(codigo_empresa, 'Grupo Padrão') AS codigo_empresa
          , nome
          , slug
          , TO_CHAR(criado_em, 'DD/MM/YYYY HH24:MI') AS criado_em
          , TO_CHAR(alterado_em, 'DD/MM/YYYY HH24:MI') AS alterado_em
          , TO_CHAR(inativado_em, 'DD/MM/YYYY HH24:MI') AS inativado_em
          , (SELECT COUNT(empresa_usuario.codigo_cadastro_grupo)
               FROM empresa_usuario
              WHERE empresa_usuario.codigo_cadastro_grupo = cadastro_grupo.codigo_cadastro_grupo
            ) AS usuarios
        ", FALSE);

        // $this->where("{$this->table}.codigo_empresa", $dadosEmpresa['codigo_empresa']);

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
     * Busca os Grupos para o Select2
     * @param array $filtros Filtros para a Busca
     */
    public function selectCadastroGrupo(array $filtros)
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');

        $this->select("
            codigo_cadastro_grupo AS id
          , nome AS text
        ", FALSE);

        if (!empty($filtros)) {
            if (!empty($filtros['termo'])) {
                if (is_numeric($filtros['termo'])) {
                    $this->where("codigo_cadastro_grupo", $filtros['termo']);
                } else {
                    $termo = explode(' ', $filtros['termo']);
                    foreach ($termo as $key => $value) {
                        $this->where("nome ILIKE '%{$value}%'");
                    }
                }
            }
        }

        $this->where('codigo_cadastro_grupo <> 1'); // Remove o superadmin das consultas
        $this->where("codigo_empresa = {$dadosEmpresa['codigo_empresa']} OR codigo_empresa IS NULL");

        $this->orderBy(2, 'ASC');

        $this->limit(30);
        $this->offset(($filtros['page'] - 1) * 30);

        $data['itens'] = $this->find();
        $data['count'] = $this->countAllResults();
        return $data;
    }

    /**
     * Busca os Relatorios para o Select2
     * @param array $filtros Filtros para a Busca
     */
    public function selectRelatorio(array $filtros)
    {
        $builder = $this->db->table('cadastro_relatorio');
        $builder->select("
          , codigo_cadastro_relatorio AS id
          , INITCAP(agrupamento) || ' - ' || nome as text
        ", FALSE);

        if (!empty($filtros)) {
            if (!empty($filtros['termo'])) {
                if (is_numeric($filtros['termo'])) {
                    $builder->where("codigo_cadastro_relatorio", (int)$filtros['termo']);
                } else {
                    if (strpos($filtros['termo'], ',')) {
                        $builder->whereIn('codigo_cadastro_relatorio', explode(',', str_replace(' ', '', $filtros['termo'])));
                    } else {
                        $termo = explode(' ', $filtros['termo']);
                        foreach ($termo as $key => $value) {
                            $builder->where("nome ILIKE '%{$value}%'");
                        }
                    }
                }
            }
        }

        $builder->orderBy(2, 'ASC');

        return $builder->get()->getResultArray();
    }
}
