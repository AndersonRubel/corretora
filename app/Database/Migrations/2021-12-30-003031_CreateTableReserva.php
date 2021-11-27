<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableReserva extends Migration
{
	protected $table = 'reserva';
    protected $primaryKey = 'codigo_reserva';
    protected $uuidColumn = 'uuid_reserva';

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
            'codigo_cliente'      => ['type' => 'BIGINT'],
            'data_inicio'         => ['type' => 'TIMESTAMPTZ'],
            'data_fim'            => ['type' => 'TIMESTAMPTZ'],
            'descricao'           => ['type' => 'VARCHAR', 'null' => true],
        ]);

        $this->forge->addPrimaryKey($this->primaryKey);
        $this->forge->addForeignKey('codigo_empresa', 'empresa', 'codigo_empresa');
        $this->forge->addForeignKey('codigo_imovel', 'imovel', 'codigo_imovel');
        $this->forge->addForeignKey('codigo_cliente', 'cliente', 'codigo_cliente');
        $this->forge->createTable($this->table);

        $this->db->query("ALTER TABLE {$this->table} ALTER COLUMN {$this->uuidColumn} SET DEFAULT uuid_generate_v4()");
    }

    public function down()
    {
        $this->forge->dropTable($this->table);
    }
}
