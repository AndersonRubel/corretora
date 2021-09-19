<?php

namespace App\Models\Usuario;

use App\Models\BaseModel;

class UsuarioGrupoRelatorioModel extends BaseModel
{
    protected $table = 'usuario_grupo_relatorio';
    protected $primaryKey = 'codigo_usuario_grupo_relatorio';
    protected $uuidColumn = 'uuid_usuario_grupo_relatorio';

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
        'codigo_usuario_grupo_relatorio',
        'uuid_usuario_grupo_relatorio',
        'usuario_criacao',
        'usuario_alteracao',
        'usuario_inativacao',
        'criado_em',
        'alterado_em',
        'inativado_em',
        'codigo_cadastro_grupo',
        'codigo_cadastro_relatorio'
    ];
}
