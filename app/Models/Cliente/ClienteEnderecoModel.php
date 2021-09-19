<?php

namespace App\Models\Cliente;

use App\Models\BaseModel;

class ClienteEnderecoModel extends BaseModel
{
    protected $table = 'cliente_endereco';
    protected $primaryKey = 'codigo_cliente_endereco';
    protected $uuidColumn = 'uuid_cliente_endereco';

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
        'codigo_cliente_endereco',
        'uuid_cliente_endereco',
        'usuario_criacao',
        'usuario_alteracao',
        'usuario_inativacao',
        'criado_em',
        'alterado_em',
        'inativado_em',
        'codigo_empresa',
        'codigo_cliente',
        'cep',
        'rua',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'uf'
    ];
}
