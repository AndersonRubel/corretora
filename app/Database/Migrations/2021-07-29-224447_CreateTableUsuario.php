<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableUsuario extends Migration
{
    protected $table = 'usuario';
    protected $primaryKey = 'codigo_usuario';
    protected $uuidColumn = 'uuid_usuario';

    public function up()
    {
        $this->forge->addField([
            "{$this->primaryKey}"   => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true, 'unique' => true],
            "{$this->uuidColumn}"   => ['type' => 'UUID', 'unique' => true],
            'usuario_criacao'       => ['type' => 'BIGINT', 'null' => true],
            'usuario_alteracao'     => ['type' => 'BIGINT', 'null' => true],
            'usuario_inativacao'    => ['type' => 'BIGINT', 'null' => true],
            'criado_em'             => ['type' => 'TIMESTAMPTZ', 'default' => 'NOW()'],
            'alterado_em'           => ['type' => 'TIMESTAMPTZ', 'null' => true],
            'inativado_em'          => ['type' => 'TIMESTAMPTZ', 'null' => true],
            'codigo_empresa_padrao' => ['type' => 'BIGINT', 'null' => true],
            'codigo_vendedor'       => ['type' => 'BIGINT', 'null' => true],
            'email'                 => ['type' => 'VARCHAR', 'unique' => true],
            'senha'                 => ['type' => 'VARCHAR'],
            'nome'                  => ['type' => 'VARCHAR', 'null' => true],
            'celular'               => ['type' => 'VARCHAR', 'null' => true],
            'ultimo_login'          => ['type' => 'VARCHAR', 'null' => true],
            'email_recover'         => ['type' => 'TEXT', 'null' => true],
            'diretorio_avatar'      => ['type' => 'VARCHAR', 'null' => true],
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
