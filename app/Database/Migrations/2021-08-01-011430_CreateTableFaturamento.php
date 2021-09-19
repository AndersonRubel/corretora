<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableFaturamento extends Migration
{
    protected $table = 'faturamento';
    protected $primaryKey = 'codigo_faturamento';
    protected $uuidColumn = 'uuid_faturamento';

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
            'codigo_vendedor'     => ['type' => 'BIGINT'],
            'periodo_inicio'      => ['type' => 'DATE'],
            'periodo_fim'         => ['type' => 'DATE'],
            'valor_bruto'         => ['type' => 'BIGINT', 'comment' => 'Valor total das vendas'],
            'codigo_comissao'     => ['type' => 'BIGINT', 'null' => true, 'comment' => 'Código de comissao para a vendedora'],
            'percentual_comissao' => ['type' => 'VARCHAR', 'null' => true, 'comment' => 'Percentual de comissao para a vendedora'],
            'valor_comissao'      => ['type' => 'BIGINT', 'null' => true, 'comment' => 'Valor da comissao para a vendedora'],
            'valor_acrescimo'     => ['type' => 'BIGINT', 'null' => true, 'comment' => ''],
            'valor_desconto'      => ['type' => 'BIGINT', 'null' => true],
            'valor_entrada'       => ['type' => 'BIGINT', 'null' => true],
            'valor_liquido'       => ['type' => 'BIGINT', 'null' => true],
            'observacao'          => ['type' => 'TEXT', 'null' => true],
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
