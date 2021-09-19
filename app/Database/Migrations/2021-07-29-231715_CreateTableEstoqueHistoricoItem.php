<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableEstoqueHistoricoItem extends Migration
{
    protected $table = 'estoque_historico_item';
    protected $primaryKey = 'codigo_estoque_historico_item';
    protected $uuidColumn = 'uuid_estoque_historico_item';

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
            'codigo_empresa'      => ['type' => 'BIGINT'],
            'codigo_estoque'      => ['type' => 'BIGINT'],
            'codigo_historico'    => ['type' => 'BIGINT'],
            'codigo_produto'      => ['type' => 'BIGINT'],
            'codigo_entrada'      => ['type' => 'BIGINT', 'null' => true],
            'codigo_baixa'        => ['type' => 'BIGINT', 'null' => true],
            'transacao'           => ['type' => 'VARCHAR'],
            'movimentacao_lote'   => ['type' => 'BIGINT', 'null' => true],
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
