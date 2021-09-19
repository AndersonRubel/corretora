<?php

namespace App\Models\Log;

use App\Models\BaseModel;

class LogLoginModel extends BaseModel
{
    protected $table = 'log_login';
    protected $primaryKey = 'codigo_log_login';
    protected $uuidColumn = 'uuid_log_login';

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
        'codigo_log_login',
        'uuid_log_login',
        'usuario_criacao',
        'usuario_alteracao',
        'usuario_inativacao',
        'criado_em',
        'alterado_em',
        'inativado_em',
        'usuario',
        'sucesso_tentativa',
        'input',
        'ip',
        'user_agent',
        'motivo'
    ];
}
