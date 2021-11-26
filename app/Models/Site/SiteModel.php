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
    public function selectImoveis($uuid = null)
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');
        //   , estacionamento
        $this->select("codigo_imovel
                      , uuid_imovel
                      , codigo_referencia
                      , quarto
                      , banheiro
                      , suite
                      , quarto
                      , valor
                      , diretorio_imagem
                      , descricao
                      , COALESCE(estacionamento,0) As estacionamento
                      , (SELECT nome FROM categoria_imovel
                          WHERE categoria_imovel.codigo_categoria_imovel = imovel.codigo_categoria_imovel
                        ) as categoria_imovel
                      , (SELECT nome FROM tipo_imovel
                          WHERE tipo_imovel.codigo_tipo_imovel = imovel.codigo_tipo_imovel
                         ) as tipo_imovel
                      , (SELECT rua ||', '|| numero ||' - '|| bairro ||', '|| cidade || ' - ' || uf FROM endereco_imovel
                          WHERE endereco_imovel.codigo_imovel = imovel.codigo_imovel
                          ) as endereco
                      ", FALSE);

        if ($uuid) {
            $this->where('imovel.uuid_imovel', $uuid);
            $data = $this->find();
            return $data;
        }
        // $this->where('imovel.codigo_empresa', $dadosEmpresa['codigo_empresa']);

        $this->orderBy(2, 'ASC');

        $this->limit(30);


        $data['itens'] = $this->find();
        $data['count'] = $this->countAllResults();
        return $data;

    }
}
