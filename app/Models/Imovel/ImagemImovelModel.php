<?php

namespace App\Models\Imovel;

use App\Models\BaseModel;

class ImagemImovelModel extends BaseModel
{
    protected $table = 'imagem_imovel';
    protected $primaryKey = 'codigo_imagem_imovel';
    protected $uuidColumn = 'uuid_imagem_imovel';

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
        'codigo_produto_imagem',
        'uuid_produto_imagem',
        'criado_em',
        'alterado_em',
        'inativado_em',
        'codigo_empresa',
        'codigo_imovel',
        'diretorio_imagem',

    ];
}