<?php

namespace App\Models\Cliente;

use App\Models\BaseModel;

class ClienteExtratoModel extends BaseModel
{
    protected $table = 'cliente_extrato';
    protected $primaryKey = 'codigo_cliente_extrato';
    protected $uuidColumn = 'uuid_cliente_extrato';

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
        'codigo_cliente_extrato',
        'uuid_cliente_extrato',
        'usuario_criacao',
        'usuario_alteracao',
        'usuario_inativacao',
        'criado_em',
        'alterado_em',
        'inativado_em',
        'codigo_empresa',
        'codigo_cliente',
        'descricao',
        'tipo_transacao',
        'valor',
        'saldo',
    ];
}
