<?php

namespace App\Models\Empresa;

use App\Models\BaseModel;

class EmpresaUsuarioModel extends BaseModel
{
    protected $table = 'empresa_usuario';
    protected $primaryKey = 'codigo_empresa_usuario';
    protected $uuidColumn = 'uuid_empresa_usuario';

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
        'codigo_empresa_usuario',
        'uuid_empresa_usuario',
        'usuario_criacao',
        'usuario_alteracao',
        'usuario_inativacao',
        'criado_em',
        'alterado_em',
        'inativado_em',
        'codigo_empresa',
        'codigo_usuario',
        'codigo_cadastro_grupo'
    ];
}
