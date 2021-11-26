<?php

namespace App\Models\Reserva;

use App\Models\BaseModel;
use App\Libraries\NativeSession;

class ReservaModel extends BaseModel
{
    protected $table = 'reserva';
    protected $primaryKey = 'codigo_reserva';
    protected $uuidColumn = 'uuid_reserva';

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
        'codigo_reserva',
        'uuid_reserva',
        'criado_em',
        'alterado_em',
        'inativado_em',
        'codigo_imovel',
        'codigo_cliente',
        'codigo_empresa',
        'data_inicio',
        'data_fim',
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
            {$this->table}.uuid_reserva
          , {$this->table}.codigo_reserva
          , imovel.codigo_referencia
          , TO_CHAR({$this->table}.data_inicio, 'DD/MM/YYYY HH24:MI') AS data_inicio
          , TO_CHAR({$this->table}.data_fim, 'DD/MM/YYYY HH24:MI') AS data_fim
          , TO_CHAR({$this->table}.criado_em, 'DD/MM/YYYY HH24:MI') AS criado_em
          , TO_CHAR({$this->table}.alterado_em, 'DD/MM/YYYY HH24:MI') AS alterado_em
          , TO_CHAR({$this->table}.inativado_em, 'DD/MM/YYYY HH24:MI') AS inativado_em
        ", FALSE);
        $this->join("imovel", "imovel.codigo_imovel = {$this->table}.codigo_imovel");
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
     * Busca as reservas para o Select2
     * @param array $filtros Filtros para a Busca
     */
    // public function selectReserva(array $filtros)
    // {
    //     $dadosEmpresa = (new NativeSession(true))->get('empresa');

    //     $this->select("
    //         codigo_reserva AS id
    //       , imovel.codigo_referencia AS text
    //     ", FALSE);

    //     $this->where('reserva.codigo_empresa', $dadosEmpresa['codigo_empresa']);

    //     if (!empty($filtros)) {
    //         if (!empty($filtros['termo'])) {
    //             if (is_numeric($filtros['termo'])) {
    //                 $termo = explode(' ', $filtros['termo']);
    //                 foreach ($termo as $key => $value) {
    //                     $this->where("reserva.codigo_referencia ILIKE '%{$value}%'");
    //                 }
    //             }
    //         }
    //     }

    //     $this->orderBy(2, 'ASC');

    //     $this->limit(30);
    //     $this->offset(($filtros['page'] - 1) * 30);

    //     $data['itens'] = $this->find();
    //     $data['count'] = $this->countAllResults();
    //     return $data;

    //     return $this->find();
    // }
}