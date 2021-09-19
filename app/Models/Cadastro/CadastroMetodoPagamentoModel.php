<?php

namespace App\Models\Cadastro;

use App\Models\BaseModel;

class CadastroMetodoPagamentoModel extends BaseModel
{
    protected $table = 'cadastro_metodo_pagamento';
    protected $primaryKey = 'codigo_cadastro_metodo_pagamento';
    protected $uuidColumn = 'uuid_cadastro_metodo_pagamento';

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
        'codigo_cadastro_metodo_pagamento',
        'uuid_cadastro_metodo_pagamento',
        'usuario_criacao',
        'usuario_alteracao',
        'usuario_inativacao',
        'criado_em',
        'alterado_em',
        'inativado_em',
        'nome',
    ];

    /**
     * Busca os Metodos de Pagamento para o Select2
     * @param array $filtros Filtros para a Busca
     */
    public function selectCadastroMetodoPagamento(array $filtros)
    {
        $this->select("
            codigo_cadastro_metodo_pagamento AS id
          , nome AS text
        ", FALSE);

        if (!empty($filtros)) {
            if (!empty($filtros['termo'])) {
                if (is_numeric($filtros['termo'])) {
                    $this->where("codigo_cadastro_metodo_pagamento", $filtros['termo']);
                } else {
                    $termo = explode(' ', $filtros['termo']);
                    foreach ($termo as $key => $value) {
                        $this->where("nome ILIKE '%{$value}%'");
                    }
                }
            }
        }

        $this->orderBy(2, 'ASC');

        $this->limit(30);
        $this->offset(($filtros['page'] - 1) * 30);

        $data['itens'] = $this->find();
        $data['count'] = $this->countAllResults();
        return $data;
    }
}
