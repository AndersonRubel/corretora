<?php

namespace App\Models\Imovel;

use App\Models\BaseModel;

class EnderecoImovelModel extends BaseModel
{
    protected $table = 'endereco_imovel';
    protected $primaryKey = 'codigo_endereco_imovel';
    protected $uuidColumn = 'uuid_endereco_imovel';

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
        'codigo_endereco_imovel',
        'uuid_endereco_imovel',
        'criado_em',
        'alterado_em',
        'inativado_em',
        'codigo_empresa',
        'codigo_imovel',
        'cep',
        'rua',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'uf',
        'lat',
        'lng'
    ];
}
