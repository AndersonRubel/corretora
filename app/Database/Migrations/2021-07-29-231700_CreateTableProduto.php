<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableProduto extends Migration
{
    protected $table = 'produto';
    protected $primaryKey = 'codigo_produto';
    protected $uuidColumn = 'uuid_produto';

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
            'codigo_empresa'        => ['type' => 'BIGINT'],
            'codigo_fornecedor'     => ['type' => 'BIGINT'],
            'referencia_fornecedor' => ['type' => 'VARCHAR', 'null' => true],
            'codigo_barras'         => ['type' => 'VARCHAR', 'null' => true],
            'nome'                  => ['type' => 'VARCHAR', 'null' => true],
            'descricao'             => ['type' => 'VARCHAR', 'null' => true],
            'diretorio_imagem'      => ['type' => 'VARCHAR', 'null' => true],
            'sku'                   => ['type' => 'VARCHAR', 'null' => true],
            'ncm'                   => ['type' => 'VARCHAR', 'null' => true],
            'cest'                  => ['type' => 'VARCHAR', 'null' => true],
            'controla_estoque'      => ['type' => 'BOOLEAN', 'default' => true],
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
