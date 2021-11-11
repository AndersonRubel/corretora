<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableCadastroConfiguracao extends Migration
{
    protected $table = 'cadastro_configuracao';
    protected $primaryKey = 'codigo_cadastro_configuracao';
    protected $uuidColumn = 'uuid_cadastro_configuracao';

    public function up()
    {
        $this->forge->addField([
            "{$this->primaryKey}" => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true, 'unique' => true],
            "{$this->uuidColumn}" => ['type' => 'UUID', 'unique' => true],
            'criado_em'           => ['type' => 'TIMESTAMPTZ', 'default' => 'NOW()'],
            'alterado_em'         => ['type' => 'TIMESTAMPTZ', 'null' => true],
            'inativado_em'        => ['type' => 'TIMESTAMPTZ', 'null' => true],
            'chave'               => ['type' => 'VARCHAR', 'null' => true],
            'valor'               => ['type' => 'TEXT', 'null' => true],
            'observacao'          => ['type' => 'VARCHAR', 'null' => true],
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
