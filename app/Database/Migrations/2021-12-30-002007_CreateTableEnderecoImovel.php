<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableEnderecoImovel extends Migration
{
    protected $table = 'endereco_imovel';
    protected $primaryKey = 'codigo_endereco_imovel';
    protected $uuidColumn = 'uuid_endereco_imovel';

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
            'cep'                 => ['type' => 'VARCHAR', 'null' => true],
            'rua'                 => ['type' => 'VARCHAR', 'null' => true],
            'numero'              => ['type' => 'VARCHAR', 'null' => true],
            'complemento'         => ['type' => 'VARCHAR', 'null' => true],
            'bairro'              => ['type' => 'VARCHAR', 'null' => true],
            'cidade'              => ['type' => 'VARCHAR', 'null' => true],
            'uf'                  => ['type' => 'VARCHAR', 'null' => true],
            'lat'                 => ['type' => 'VARCHAR', 'null' => true],
            'lng'                 => ['type' => 'VARCHAR', 'null' => true],
        ]);

        $this->forge->addPrimaryKey($this->primaryKey);
        $this->forge->addForeignKey('codigo_empresa', 'empresa', 'codigo_empresa');
        $this->forge->addForeignKey('codigo_imovel', 'imovel', 'codigo_imovel');
        $this->forge->createTable($this->table);

        $this->db->query("ALTER TABLE {$this->table} ALTER COLUMN {$this->uuidColumn} SET DEFAULT uuid_generate_v4()");
    }

    public function down()
    {
        $this->forge->dropTable($this->table);
    }
}
