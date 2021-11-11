<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableImovel extends Migration
{
    protected $table = 'imovel';
    protected $primaryKey = 'codigo_imovel';
    protected $uuidColumn = 'uuid_imovel';

    public function up()
    {
        $this->forge->addField([
            "{$this->primaryKey}"      => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true, 'unique' => true],
            "{$this->uuidColumn}"      => ['type' => 'UUID', 'unique' => true],
            'criado_em'                => ['type' => 'TIMESTAMPTZ', 'default' => 'NOW()'],
            'alterado_em'              => ['type' => 'TIMESTAMPTZ', 'null' => true],
            'inativado_em'             => ['type' => 'TIMESTAMPTZ', 'null' => true],
            'codigo_empresa'           => ['type' => 'BIGINT'],
            'codigo_endereco'          => ['type' => 'BIGINT'],
            'codigo_proprietario'      => ['type' => 'BIGINT'],
            'codigo_empresa_categoria' => ['type' => 'BIGINT'],
            'codigo_referencia'        => ['type' => 'VARCHAR', 'null' => true],
            'quarto'                   => ['type' => 'VARCHAR', 'null' => true],
            'suite'                    => ['type' => 'VARCHAR', 'null' => true],
            'banheiro'                 => ['type' => 'VARCHAR', 'null' => true],
            'area_util'                => ['type' => 'VARCHAR', 'null' => true],
            'area_construida'          => ['type' => 'VARCHAR', 'null' => true],
            'edicula'                  => ['type' => 'BOOLEAN', 'default' => false],
            'mobilia'                  => ['type' => 'BOOLEAN', 'default' => false],
            'condominio'               => ['type' => 'BOOLEAN', 'default' => false],
            'descricao'                => ['type' => 'VARCHAR', 'null' => true],
            'destaque'                 => ['type' => 'BOOLEAN', 'default' => true],
            'publicado'                => ['type' => 'BOOLEAN', 'default' => true],
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
