<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableEmpresaUsuario extends Migration
{
    protected $table = 'empresa_usuario';
    protected $primaryKey = 'codigo_empresa_usuario';
    protected $uuidColumn = 'uuid_empresa_usuario';

    public function up()
    {
        $this->forge->addField([
            "{$this->primaryKey}"   => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true, 'unique' => true],
            "{$this->uuidColumn}"   => ['type' => 'UUID', 'unique' => true],
            'criado_em'             => ['type' => 'TIMESTAMPTZ', 'default' => 'NOW()'],
            'alterado_em'           => ['type' => 'TIMESTAMPTZ', 'null' => true],
            'inativado_em'          => ['type' => 'TIMESTAMPTZ', 'null' => true],
            'codigo_empresa'        => ['type' => 'BIGINT'],
            'codigo_usuario'        => ['type' => 'BIGINT'],
            'codigo_cadastro_grupo' => ['type' => 'BIGINT'],
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
