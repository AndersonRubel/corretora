<?php

namespace App\Models\Produto;

use App\Models\BaseModel;

class ProdutoCategoriaModel extends BaseModel
{
    protected $table = 'produto_categoria';
    protected $primaryKey = 'codigo_produto_categoria';
    protected $uuidColumn = 'uuid_produto_categoria';

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
        'codigo_produto_categoria',
        'uuid_produto_categoria',
        'usuario_criacao',
        'usuario_alteracao',
        'usuario_inativacao',
        'criado_em',
        'alterado_em',
        'inativado_em',
        'codigo_empresa',
        'codigo_produto',
        'codigo_empresa_categoria',

    ];
}
