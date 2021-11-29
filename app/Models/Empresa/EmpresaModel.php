<?php

namespace App\Models\Empresa;

use App\Models\BaseModel;
use App\Libraries\NativeSession;

class EmpresaModel extends BaseModel
{
    protected $table = 'empresa';
    protected $primaryKey = 'codigo_empresa';
    protected $uuidColumn = 'uuid_empresa';

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
        'codigo_empresa',
        'uuid_empresa',
        'criado_em',
        'alterado_em',
        'inativado_em',
        'codigo_empresa',
        'tipo_pessoa',
        'razao_social',
        'nome_fantasia',
        'cnpj',
        'data_nascimento',
        'email',
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
            uuid_empresa
          , COALESCE(razao_social, nome_fantasia) AS nome
          , cnpj
          , email
          , telefone
          , celular
          , TO_CHAR(criado_em, 'DD/MM/YYYY HH24:MI') AS criado_em
          , TO_CHAR(alterado_em, 'DD/MM/YYYY HH24:MI') AS alterado_em
          , TO_CHAR(inativado_em, 'DD/MM/YYYY HH24:MI') AS inativado_em
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
     * Busca os Fornecedores para o Select2
     * @param array $filtros Filtros para a Busca
     */
    public function selectEmpresa(array $filtros)
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');

        $this->select("
            codigo_empresa AS id
          , COALESCE(razao_social, nome_fantasia) AS text
        ", FALSE);

        /////// Inicio :: Filtros ///////

        $this->where('codigo_empresa', $dadosEmpresa['codigo_empresa']);

        if (!empty($filtros)) {
            if (!empty($filtros['termo'])) {
                if (is_numeric($filtros['termo'])) {
                    $this->where("codigo_empresa", $filtros['termo']);
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

        /////// Fim :: Filtros ///////

        $this->orderBy(2, 'ASC');

        $this->limit(30);
        $this->offset(($filtros['page'] - 1) * 30);

        $data['itens'] = $this->find();
        $data['count'] = $this->countAllResults();
        return $data;
    }

    /**
     * Busca os dados da Empresa que o usuário tem por padrão
     * @param int $codigoEmpresa Código da Empresa
     * @return array
     */
    public function getEmpresaUsuario(array $usuario)
    {
        $this->select("
            {$this->table}.codigo_empresa
          , {$this->table}.uuid_empresa
          , {$this->table}.razao_social
          , {$this->table}.nome_fantasia
          , {$this->table}.cnpj
          , eu.codigo_empresa_usuario
          , eu.uuid_empresa_usuario
          , eu.codigo_empresa
          , eu.codigo_usuario
          , eu.codigo_cadastro_grupo
        ", FALSE);

        $this->join("empresa_usuario eu", "eu.codigo_usuario = {$usuario['codigo_usuario']}");
        $this->where("{$this->table}.codigo_empresa", $usuario['codigo_empresa']);

        return $this->first();
    }
}
