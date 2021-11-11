<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableContatoSite extends Migration
{
    protected $table = 'contato_site';
    protected $primaryKey = 'codigo_contato_site';
    protected $uuidColumn = 'uuid_contato_site';

    public function up()
    {
        $this->forge->addField([
            "{$this->primaryKey}" => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true, 'unique' => true],
            "{$this->uuidColumn}" => ['type' => 'UUID', 'unique' => true],
            'criado_em'           => ['type' => 'TIMESTAMPTZ', 'default' => 'NOW()'],
            'alterado_em'         => ['type' => 'TIMESTAMPTZ', 'null' => true],
            'inativado_em'        => ['type' => 'TIMESTAMPTZ', 'null' => true],
            'codigo_empresa'      => ['type' => 'BIGINT'],
            'nome'                => ['type' => 'VARCHAR'],
            'email'               => ['type' => 'VARCHAR'],
            'telefone'            => ['type' => 'VARCHAR'],
            'descricao'           => ['type' => 'TEXT'],
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
