<?php

namespace App\Models\Cadastro;

use App\Models\BaseModel;

class CadastroMenuModel extends BaseModel
{
    protected $table = 'cadastro_menu';
    protected $primaryKey = 'codigo_cadastro_menu';
    protected $uuidColumn = 'uuid_cadastro_menu';

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
        'codigo_cadastro_menu',
        'uuid_cadastro_menu',
        'criado_em',
        'alterado_em',
        'inativado_em',
        'nome',
        'path',
        'agrupamento',
        'ordenacao',
        'icone',
    ];

    /**
     * Busca os Menus que o Grupo tem permissão
     * @param int $grupoId ID do Grupo
     * @return array
     */
    public function getGrupoMenu(int $grupoId)
    {
        $this->select("
            ugm.codigo_usuario_grupo_menu
          , ugm.codigo_cadastro_grupo
          , ugm.codigo_cadastro_menu
          , ugm.consultar
          , ugm.inserir
          , ugm.modificar
          , ugm.deletar
          , {$this->table}.nome
          , {$this->table}.descricao
          , {$this->table}.path
          , {$this->table}.agrupamento
          , {$this->table}.localizacao
          , {$this->table}.ordenacao
          , {$this->table}.icone
        ", FALSE);

        // Se for o Grupo Administrador (1) não faz o INNER JOIN, e nem o WHERE de GRUPO
        if ($grupoId == 1) {
            $this->join("usuario_grupo_menu ugm", "ugm.codigo_usuario_grupo_menu = {$this->table}.{$this->primaryKey}", "LEFT");
        } else {
            $this->join("usuario_grupo_menu ugm", "ugm.codigo_usuario_grupo_menu = {$this->table}.{$this->primaryKey}");
            $this->where('ugm.codigo_cadastro_grupo', $grupoId);
        }

        $this->orderBy("{$this->table}.ordenacao", "ASC");
        $this->orderBy("{$this->table}.nome", "ASC");

        return $this->find();
    }
}
