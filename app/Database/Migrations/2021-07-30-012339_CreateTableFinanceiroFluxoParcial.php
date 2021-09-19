<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableFinanceiroFluxoParcial extends Migration
{
    protected $table = 'financeiro_fluxo_parcial';
    protected $primaryKey = 'codigo_financeiro_fluxo_parcial';
    protected $uuidColumn = 'uuid_financeiro_fluxo_parcial';

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
            'codigo_financeiro_fluxo'          => ['type' => 'BIGINT'],
            'codigo_cadastro_metodo_pagamento' => ['type' => 'BIGINT'],
            'data_pagamento'                   => ['type' => 'DATE', 'null' => true],
            'valor'                            => ['type' => 'BIGINT'],
        ]);

        $this->forge->addPrimaryKey($this->primaryKey);
        $this->forge->createTable($this->table);

        $this->db->query("ALTER TABLE {$this->table} ALTER COLUMN {$this->uuidColumn} SET DEFAULT uuid_generate_v4()");

        // Cria a Trigger de calculo automatico
        $this->db->query("CREATE TRIGGER calcula_pagamento_parcial AFTER INSERT OR UPDATE OR DELETE ON {$this->table} FOR EACH ROW EXECUTE PROCEDURE calcula_pagamento_parcial()");
    }

    public function down()
    {
        $this->forge->dropTable($this->table);
    }
}
