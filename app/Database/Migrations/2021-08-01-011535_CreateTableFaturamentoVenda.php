<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableFaturamentoVenda extends Migration
{
    protected $table = 'faturamento_venda';
    protected $primaryKey = 'codigo_faturamento_venda';
    protected $uuidColumn = 'uuid_faturamento_venda';

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
            'codigo_faturamento'  => ['type' => 'BIGINT'],
            'codigo_venda'        => ['type' => 'BIGINT'],
            'valor_bruto'         => ['type' => 'BIGINT', 'null' => true],
            'valor_liquido'       => ['type' => 'BIGINT', 'null' => true],
            'valor_comissao'      => ['type' => 'BIGINT', 'null' => true],
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
