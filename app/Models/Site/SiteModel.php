<?php

namespace App\Models\Site;

use App\Models\BaseModel;
use App\Libraries\NativeSession;

class SiteModel extends BaseModel
{
    protected $table = 'imovel';
    protected $primaryKey = 'codigo_imovel';
    protected $uuidColumn = 'uuid_imovel';

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



    /**
     * Busca os imoveis para o Select2
     * @param array $filtros Filtros para a Busca
     */
    public function selectImoveis()
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');

        $this->select("
            codigo_imovel AS id
          , codigo_referencia AS text
        ", FALSE);

        // $this->where('imovel.codigo_empresa', $dadosEmpresa['codigo_empresa']);

        $this->orderBy(2, 'ASC');

        $this->limit(30);


        $data['itens'] = $this->find();
        $data['count'] = $this->countAllResults();
        return $data;

        return $this->find();
    }
}