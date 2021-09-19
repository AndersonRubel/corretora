<?php

namespace App\Models\Financeiro;

use App\Models\BaseModel;

class FinanceiroFluxoParcialModel extends BaseModel
{
    protected $table = 'financeiro_fluxo_parcial';
    protected $primaryKey = 'codigo_financeiro_fluxo_parcial';
    protected $uuidColumn = 'uuid_financeiro_fluxo_parcial';

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
        'codigo_financeiro_fluxo_parcial',
        'uuid_financeiro_fluxo_parcial',
        'usuario_criacao',
        'usuario_alteracao',
        'usuario_inativacao',
        'criado_em',
        'alterado_em',
        'inativado_em',
        'codigo_empresa',
        'codigo_financeiro_fluxo',
        'codigo_cadastro_metodo_pagamento',
        'data_pagamento',
        'valor'
    ];
}
