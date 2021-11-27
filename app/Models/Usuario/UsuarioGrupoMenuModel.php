<?php

namespace App\Models\Usuario;

use App\Models\BaseModel;

class UsuarioGrupoMenuModel extends BaseModel
{
    protected $table = 'usuario_grupo_menu';
    protected $primaryKey = 'codigo_usuario_grupo_menu';
    protected $uuidColumn = 'uuid_usuario_grupo_menu';

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
        'codigo_usuario_grupo_menu',
        'uuid_usuario_grupo_menu',
        'criado_em',
        'alterado_em',
        'inativado_em',
        'codigo_cadastro_grupo',
        'codigo_cadastro_menu',
        'consultar',
        'inserir',
        'modificar',
        'deletar'
    ];
}
