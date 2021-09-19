<?php

namespace App\Models\Empresa;

use App\Models\BaseModel;

class EmpresaCentroCustoModel extends BaseModel
{
    protected $table = 'empresa_centro_custo';
    protected $primaryKey = 'codigo_empresa_centro_custo';
    protected $uuidColumn = 'uuid_empresa_centro_custo';

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
        'codigo_empresa_centro_custo',
        'uuid_empresa_centro_custo',
        'usuario_criacao',
        'usuario_alteracao',
        'usuario_inativacao',
        'criado_em',
        'alterado_em',
        'inativado_em',
        'codigo_empresa',
        'nome',
        'padrao'
    ];
}
