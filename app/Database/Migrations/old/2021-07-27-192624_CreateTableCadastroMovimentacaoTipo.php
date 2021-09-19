<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableCadastroMovimentacaoTipo extends Migration
{
    protected $table = 'cadastro_movimentacao_tipo';
    protected $primaryKey = 'codigo_cadastro_movimentacao_tipo';
    protected $uuidColumn = 'uuid_cadastro_movimentacao_tipo';

    public function up()
    {
        $this->forge->addField([
            "{$this->primaryKey}" => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true, 'unique' => true],
            "{$this->uuidColumn}" => ['type' => 'UUID', 'unique' => true],
            'usuario_criacao'     => ['type' => 'BIGINT', 'null' => true],
            'usuario_alteracao'   => ['type' => 'BIGINT', 'null' => true],
            'usuario_inativacao'  => ['type' => 'BIGINT', 'null' => true],
            'criado_em'           => ['type' => 'TIMESTAMPTZ', 'default' => 'NOW()'],
            'alterado_em'         => ['type' => 'TIMESTAMPTZ', 'null' => true],
            'inativado_em'        => ['type' => 'TIMESTAMPTZ', 'null' => true],
            'nome'                => ['type' => 'VARCHAR', 'null' => true],
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
