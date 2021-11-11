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
        'criado_em',
        'alterado_em',
        'inativado_em',
        'codigo_empresa',
        'tipo_pessoa',
        'razao_social',
        'nome_fantasia',
        'cpf_cnpj',
        'data_nascimento',
        'email',
        'telefone',
        'celular',
        'observacao',
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
          , TO_CHAR(criado_em, 'DD/MM/YYYY HH24:MI') AS criado_em
          , TO_CHAR(alterado_em, 'DD/MM/YYYY HH24:MI') AS alterado_em
          , TO_CHAR(inativado_em, 'DD/MM/YYYY HH24:MI') AS inativado_em
        ", FALSE);

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
}
