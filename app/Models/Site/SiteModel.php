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

        $this->select("codigo_imovel
                      , uuid_imovel
                      , codigo_referencia
                      , COALESCE(quarto,0) AS quarto
                      , COALESCE(banheiro,0) AS banheiro
                      , COALESCE(suite,0) AS suite
                      , valor_venda
                      , valor_aluguel
                      , diretorio_imagem
                      , descricao
                      , COALESCE(vaga,0) As vaga
                      , COALESCE(area_construida,0) AS area_construida
                      , COALESCE(area_total,0) AS area_total
                      , edicula
                      , (SELECT nome FROM categoria_imovel
                          WHERE categoria_imovel.codigo_categoria_imovel = imovel.codigo_categoria_imovel
                        ) as categoria_imovel
                      , (SELECT nome FROM tipo_imovel
                          WHERE tipo_imovel.codigo_tipo_imovel = imovel.codigo_tipo_imovel
                         ) as tipo_imovel
                      , (SELECT rua ||', '|| numero ||' - '|| bairro ||', '|| cidade || ' - ' || uf FROM endereco_imovel
                          WHERE endereco_imovel.codigo_imovel = imovel.codigo_imovel
                          ) as endereco
                      , (SELECT mapa FROM endereco_imovel
                          WHERE endereco_imovel.codigo_imovel = imovel.codigo_imovel
                          ) as mapa
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
    /**
     * Busca os imoveis para o Select2
     * @param array $filtros Filtros para a Busca
     */
    public function selectImoveisFiltrar($filtros)
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');

        $this->select("imovel.codigo_imovel
                      , uuid_imovel
                      , codigo_referencia
                      , COALESCE(quarto,0) AS quarto
                      , COALESCE(banheiro,0) AS banheiro
                      , COALESCE(suite,0) AS suite
                      , valor_venda
                      , valor_aluguel
                      , diretorio_imagem
                      , descricao
                      , imovel.criado_em
                      , COALESCE(vaga,0) As vaga
                      , COALESCE(area_construida,0) AS area_construida
                      , COALESCE(area_total,0) AS area_total
                      , edicula
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

        $this->where('imovel.publicado = true');
        if (!empty($filtros['destaque'])) {
            $this->where('imovel.destaque', $filtros['destaque']);
        }
        if (!empty($filtros['codigo_tipo_imovel'])) {
            $this->where('imovel.codigo_tipo_imovel', $filtros['codigo_tipo_imovel']);
        }
        if (!empty($filtros['codigo_categoria_imovel'])) {
            $this->where('imovel.codigo_categoria_imovel', $filtros['codigo_categoria_imovel']);
            $this->where('imovel.codigo_categoria_imovel', '3');
        }
        if (!empty($filtros['condominio'])) {
            $this->where('imovel.condominio', $filtros['condominio']);
        }

        if (!empty($filtros['cidade'])) {
            $cidade = explode('/', $filtros['cidade']);
            $this->join('endereco_imovel', "endereco_imovel.codigo_imovel = imovel.codigo_imovel");
            $this->like('endereco_imovel.cidade', $cidade[0]);
        }
        if (!empty($filtros['quarto'])) {
            if ($filtros['quarto'] != "4") {
                $this->where('imovel.quarto', $filtros['quarto']);
            } else {
                $this->where('imovel.quarto >=', $filtros['quarto']);
            }
        }
        // $this->where('imovel.codigo_empresa', $dadosEmpresa['codigo_empresa']);
        if (!empty($filtros['ordenar_valor'])) {
            if (!empty($filtros['codigo_categoria_imovel'])) {
                if ($filtros['ordenar_valor'] == "menor") {

                    if ($filtros['codigo_categoria_imovel'] == 1) {
                        $this->orderBy('imovel.valor_aluguel', 'ASC');
                    } else {
                        $this->orderBy('imovel.valor_venda', 'ASC');
                    }
                } else {
                    if ($filtros['codigo_categoria_imovel'] == 1) {
                        $this->orderBy('imovel.valor_aluguel', 'DESC');
                    } else {
                        $this->orderBy('imovel.valor_venda', 'DESC');
                    }
                }
            }
        } else {
            $this->orderBy('imovel.criado_em', 'DESC');
        }



        $this->limit(30);


        $data['itens'] = $this->find();
        // dd($data);
        $data['count'] = $this->countAllResults();
        return $data;
    }
    /**
     * Busca os imoveis para o Select2
     * @param array $filtros Filtros para a Busca
     */
    public function selectCidades($uuid = null)
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');

        $this->select("
                      , DISTINCT(endereco_imovel.cidade) || '/' ||endereco_imovel.uf AS cidade
                      ", FALSE);

        $this->join('endereco_imovel', 'endereco_imovel.codigo_imovel = imovel.codigo_imovel');
        $this->where('imovel.publicado = true');
        $this->where('imovel.inativado_em IS NULL');
        // $this->where('imovel.codigo_empresa', $dadosEmpresa['codigo_empresa']);

        $this->orderBy(1, 'ASC');

        $data = $this->find();
        return $data;
    }
}
