<?php

namespace App\Models\Relatorio;

use App\Models\BaseModel;
use App\Libraries\NativeSession;

class RelatorioModel extends BaseModel
{
    protected $table = 'cadastro_relatorio';
    protected $primaryKey = 'codigo_cadastro_relatorio';

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
        'codigo_cadastro_relatorio',
        'usuario_criacao',
        'usuario_alteracao',
        'usuario_inativacao',
        'criado_em',
        'alterado_em',
        'inativao_em',
        'nome',
        'agrupamento',
        'slug'
    ];

    /**
     * Busca os relatórios que o usuário possui acesso
     * @param bool $isAdmin Se o usuário é um Admin ou não
     * @param int $codigoGrupo Código do grupo que o usuario pertence
     * @return array
     */
    public function getRelatorios(int $codigoGrupo): array
    {

        $relatorios = [];

        /////// INICIO :: Busca os agrupamentos disponiveis ///////
        $this->distinct()->select("UPPER(agrupamento) AS agrupamento", FALSE);

        // Verifica a Permissão de acesso do Grupo do usuário
        $this->join("usuario_grupo_relatorio ugr", "ugr.codigo_usuario_grupo_relatorio = {$this->table}.{$this->primaryKey}");
        $this->where("ugr.codigo_cadastro_grupo", $codigoGrupo);
        $this->orderBy('agrupamento', 'ASC');
        $consultaAgrupamento = $this->find();
        /////// FIM :: Busca os agrupamentos disponiveis ///////

        if (!empty($consultaAgrupamento)) {
            foreach ($consultaAgrupamento as $key => $value) {
                /////// INICIO :: Busca os Relatórios através do agrupamento ///////

                $this->select("
                    {$this->table}.{$this->primaryKey}
				  , {$this->table}.nome
				  , {$this->table}.slug
				  , UPPER({$this->table}.agrupamento) AS agrupamento
				", FALSE);
                $this->where("UPPER({$this->table}.agrupamento)", $value['agrupamento']);

                // Verifica a Permissão de acesso do Grupo do usuário
                $this->join("usuario_grupo_relatorio ugr", "ugr.codigo_usuario_grupo_relatorio = {$this->table}.{$this->primaryKey}");
                $this->where("ugr.codigo_cadastro_grupo", $codigoGrupo);
                $rels = $this->find();

                // Verifica se o Agrupamento e a listagem nao sao Vazias
                if (!empty($value['agrupamento']) && !empty($rels)) {
                    $relatorios[] = array(
                        "descricao"  => $value['agrupamento'],
                        "relatorios" => $rels
                    );
                }
                /////// FIM :: Busca os Relatórios através do agrupamento ///////
            }
        }

        return $relatorios;
    }
}
