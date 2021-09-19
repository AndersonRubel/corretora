<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableFornecedor extends Migration
{
	protected $table = 'fornecedor';
    protected $primaryKey = 'codigo_fornecedor';
    protected $uuidColumn = 'uuid_fornecedor';

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
            'razao_social'        => ['type' => 'VARCHAR', 'null' => true],
            'nome_fantasia'       => ['type' => 'VARCHAR', 'null' => true],
            'cpf_cnpj'            => ['type' => 'VARCHAR', 'null' => true],
            'tipo_pessoa'         => ['type' => 'INT', 'default' => 1],
            'data_nascimento'     => ['type' => 'DATE', 'null' => true],
            'email'               => ['type' => 'VARCHAR', 'null' => true],
            'telefone'            => ['type' => 'VARCHAR', 'null' => true],
            'celular'             => ['type' => 'VARCHAR', 'null' => true],
            'endereco'            => ['type' => 'JSONB', 'null' => true],
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
