<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableImovelImagem extends Migration
{
    protected $table = 'imovel_imagem';
    protected $primaryKey = 'codigo_imovel_imagem';
    protected $uuidColumn = 'uuid_imovel_imagem';

    public function up()
    {
        $this->forge->addField([
            "{$this->primaryKey}" => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true, 'unique' => true],
            "{$this->uuidColumn}" => ['type' => 'UUID', 'unique' => true],
            'criado_em'           => ['type' => 'TIMESTAMPTZ', 'default' => 'NOW()'],
            'alterado_em'         => ['type' => 'TIMESTAMPTZ', 'null' => true],
            'inativado_em'        => ['type' => 'TIMESTAMPTZ', 'null' => true],
            'codigo_empresa'      => ['type' => 'BIGINT'],
            'codigo_imovel'       => ['type' => 'BIGINT'],
            'diretorio_imagem'    => ['type' => 'VARCHAR'],
            'capa'                => ['type' => 'BOOLEAN', 'default' => true],
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
