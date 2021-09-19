<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableClienteEndereco extends Migration
{
    protected $table = 'cliente_endereco';
    protected $primaryKey = 'codigo_cliente_endereco';
    protected $uuidColumn = 'uuid_cliente_endereco';

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
            'cep'                 => ['type' => 'VARCHAR', 'null' => true],
            'rua'                 => ['type' => 'VARCHAR', 'null' => true],
            'numero'              => ['type' => 'VARCHAR', 'null' => true],
            'complemento'         => ['type' => 'VARCHAR', 'null' => true],
            'bairro'              => ['type' => 'VARCHAR', 'null' => true],
            'cidade'              => ['type' => 'VARCHAR', 'null' => true],
            'uf'                  => ['type' => 'VARCHAR', 'null' => true],
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
