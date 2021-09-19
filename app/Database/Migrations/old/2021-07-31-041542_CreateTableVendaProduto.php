<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableVendaProduto extends Migration
{
    protected $table = 'venda_produto';
    protected $primaryKey = 'codigo_venda_produto';
    protected $uuidColumn = 'uuid_venda_produto';

    public function up()
    {
        $this->forge->addField([
            "{$this->primaryKey}"              => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true, 'unique' => true],
            "{$this->uuidColumn}"              => ['type' => 'UUID', 'unique' => true],
            'usuario_criacao'                  => ['type' => 'BIGINT', 'null' => true],
            'usuario_alteracao'                => ['type' => 'BIGINT', 'null' => true],
            'usuario_inativacao'               => ['type' => 'BIGINT', 'null' => true],
            'criado_em'                        => ['type' => 'TIMESTAMPTZ', 'default' => 'NOW()'],
            'alterado_em'                      => ['type' => 'TIMESTAMPTZ', 'null' => true],
            'inativado_em'                     => ['type' => 'TIMESTAMPTZ', 'null' => true],
            'codigo_empresa'                   => ['type' => 'BIGINT'],
            'codigo_venda'                     => ['type' => 'BIGINT'],
            'codigo_produto'                   => ['type' => 'BIGINT'],
            'nome_produto'                     => ['type' => 'VARCHAR', 'null' => true],
            'quantidade'                       => ['type' => 'BIGINT'],
            'valor_unitario'                   => ['type' => 'BIGINT'],
            'valor_desconto'                   => ['type' => 'BIGINT', 'null' => true],
            'valor_total'                      => ['type' => 'BIGINT']
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
