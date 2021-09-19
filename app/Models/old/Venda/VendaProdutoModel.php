<?php

namespace App\Models\Venda;

use App\Models\BaseModel;
use App\Libraries\NativeSession;

class VendaProdutoModel extends BaseModel
{
    protected $table = 'venda_produto';
    protected $primaryKey = 'codigo_venda_produto';
    protected $uuidColumn = 'uuid_venda_produto';

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
        'codigo_venda_produto',
        'uuid_venda_produto',
        'usuario_criacao',
        'usuario_alteracao',
        'usuario_inativacao',
        'criado_em',
        'alterado_em',
        'inativado_em',
        'codigo_empresa',
        'codigo_venda',
        'codigo_produto',
        'nome_produto',
        'quantidade',
        'valor_unitario',
        'valor_desconto',
        'valor_total'
    ];
}
