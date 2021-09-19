<?php

namespace App\Models\Cadastro;

use App\Models\BaseModel;

class CadastroMovimentacaoTipoModel extends BaseModel
{
    protected $table = 'cadastro_movimentacao_tipo';
    protected $primaryKey = 'codigo_cadastro_movimentacao_tipo';
    protected $uuidColumn = 'uuid_cadastro_movimentacao_tipo';

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
        'codigo_cadastro_movimentacao_tipo',
        'uuid_cadastro_movimentacao_tipo',
        'usuario_criacao',
        'usuario_alteracao',
        'usuario_inativacao',
        'criado_em',
        'alterado_em',
        'inativado_em',
        'nome',
    ];
}
