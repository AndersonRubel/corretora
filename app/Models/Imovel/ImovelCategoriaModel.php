<?php

namespace App\Models\Produto;

use App\Models\BaseModel;

class ProdutoCategoriaModel extends BaseModel
{
    protected $table = 'imovel_categoria';
    protected $primaryKey = 'codigo_imovel_categoria';
    protected $uuidColumn = 'uuid_imovel_categoria';

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
        'codigo_imovel_categoria',
        'uuid_imovel_categoria',
        'criado_em',
        'alterado_em',
        'inativado_em',
        'codigo_empresa',
        'codigo_imovel',
        'codigo_categoria',

    ];
}