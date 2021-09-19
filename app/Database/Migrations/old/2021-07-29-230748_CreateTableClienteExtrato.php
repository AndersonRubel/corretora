<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableClienteExtrato extends Migration
{
    protected $table = 'cliente_extrato';
    protected $primaryKey = 'codigo_cliente_extrato';
    protected $uuidColumn = 'uuid_cliente_extrato';

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
            'codigo_cliente'      => ['type' => 'BIGINT'],
            'codigo_venda'        => ['type' => 'BIGINT', 'null' => true],
            'codigo_parcelamento' => ['type' => 'BIGINT', 'null' => true],
            'descricao'           => ['type' => 'VARCHAR', 'null' => true],
            'tipo_transacao'      => ['type' => 'VARCHAR', 'null' => true, 'comment' => 'C = Credito, D = Debito'],
            'valor'               => ['type' => 'INT', 'null' => true],
            'saldo'               => ['type' => 'INT', 'null' => true],
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
