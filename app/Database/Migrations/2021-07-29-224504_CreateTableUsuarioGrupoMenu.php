<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableUsuarioGrupoMenu extends Migration
{
    protected $table = 'usuario_grupo_menu';
    protected $primaryKey = 'codigo_usuario_grupo_menu';
    protected $uuidColumn = 'uuid_usuario_grupo_menu';

    public function up()
    {
        $this->forge->addField([
            "{$this->primaryKey}"   => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true, 'unique' => true],
            "{$this->uuidColumn}"   => ['type' => 'UUID', 'unique' => true],
            'criado_em'             => ['type' => 'TIMESTAMPTZ', 'default' => 'NOW()'],
            'alterado_em'           => ['type' => 'TIMESTAMPTZ', 'null' => true],
            'inativado_em'          => ['type' => 'TIMESTAMPTZ', 'null' => true],
            'codigo_cadastro_grupo' => ['type' => 'BIGINT'],
            'codigo_cadastro_menu'  => ['type' => 'BIGINT'],
            'consultar'             => ['type' => 'BOOLEAN', 'default' => true],
            'inserir'               => ['type' => 'BOOLEAN', 'default' => true],
            'modificar'             => ['type' => 'BOOLEAN', 'default' => true],
            'deletar'               => ['type' => 'BOOLEAN', 'default' => true],
        ]);

        $this->forge->addPrimaryKey($this->primaryKey);
        $this->forge->createTable($this->table);

        $this->db->query("ALTER TABLE {$this->table} ALTER COLUMN {$this->uuidColumn} SET DEFAULT uuid_generate_v4()");
    }

    public function down()
    {
        $this->forge->dropTable($this->table);
    }
}
