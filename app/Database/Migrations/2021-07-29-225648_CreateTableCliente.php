<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableCliente extends Migration
{
    protected $table = 'cliente';
    protected $primaryKey = 'codigo_cliente';
    protected $uuidColumn = 'uuid_cliente';

    public function up()
    {
        $this->forge->addField([
            "{$this->primaryKey}" => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true, 'unique' => true],
            "{$this->uuidColumn}" => ['type' => 'UUID', 'unique' => true],
            'criado_em'           => ['type' => 'TIMESTAMPTZ', 'default' => 'NOW()'],
            'alterado_em'         => ['type' => 'TIMESTAMPTZ', 'null' => true],
            'inativado_em'        => ['type' => 'TIMESTAMPTZ', 'null' => true],
            'codigo_empresa'      => ['type' => 'BIGINT'],
            'tipo_pessoa'         => ['type' => 'INT', 'default' => 1],
            'razao_social'        => ['type' => 'VARCHAR', 'null' => true],
            'nome_fantasia'       => ['type' => 'VARCHAR', 'null' => true],
            'cpf_cnpj'            => ['type' => 'VARCHAR', 'null' => true],
            'data_nascimento'     => ['type' => 'DATE', 'null' => true],
            'email'               => ['type' => 'VARCHAR', 'null' => true],
            'telefone'            => ['type' => 'VARCHAR', 'null' => true],
            'endereco'            => ['type' => 'JSONB', 'null' => true],
            'celular'             => ['type' => 'VARCHAR', 'null' => true],
            'observacao'          => ['type' => 'TEXT', 'null' => true],
        ]);

        $this->forge->addPrimaryKey($this->primaryKey);
        $this->forge->addForeignKey('codigo_empresa', 'empresa', 'codigo_empresa');
        $this->forge->createTable($this->table);

        $this->db->query("ALTER TABLE {$this->table} ALTER COLUMN {$this->uuidColumn} SET DEFAULT uuid_generate_v4()");
    }

    public function down()
    {
        $this->forge->dropTable($this->table);
    }
}
