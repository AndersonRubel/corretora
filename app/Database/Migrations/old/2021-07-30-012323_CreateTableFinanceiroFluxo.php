<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableFinanceiroFluxo extends Migration
{
    protected $table = 'financeiro_fluxo';
    protected $primaryKey = 'codigo_financeiro_fluxo';
    protected $uuidColumn = 'uuid_financeiro_fluxo';

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
            'codigo_cadastro_metodo_pagamento' => ['type' => 'BIGINT', 'null' => true],
            'codigo_cadastro_fluxo_tipo'       => ['type' => 'BIGINT'],
            'codigo_empresa_centro_custo'      => ['type' => 'BIGINT'],
            'codigo_empresa_conta'             => ['type' => 'BIGINT'],
            'codigo_fornecedor'                => ['type' => 'BIGINT', 'null' => true],
            'codigo_cliente'                   => ['type' => 'BIGINT', 'null' => true],
            'codigo_vendedor'                  => ['type' => 'BIGINT', 'null' => true],
            'codigo_venda'                     => ['type' => 'BIGINT', 'null' => true],
            'codigo_faturamento'               => ['type' => 'BIGINT', 'null' => true],
            'nome'                             => ['type' => 'VARCHAR'],
            'data_vencimento'                  => ['type' => 'DATE', 'null' => true],
            'data_pagamento'                   => ['type' => 'DATE', 'null' => true],
            'data_competencia'                 => ['type' => 'DATE', 'null' => true],
            'valor_bruto'                      => ['type' => 'BIGINT', 'null' => true],
            'valor_juros'                      => ['type' => 'BIGINT', 'null' => true],
            'valor_acrescimo'                  => ['type' => 'BIGINT', 'null' => true],
            'valor_desconto'                   => ['type' => 'BIGINT', 'null' => true],
            'valor_liquido'                    => ['type' => 'BIGINT'],
            'valor_pago_parcial'               => ['type' => 'BIGINT', 'null' => true],
            'situacao'                         => ['type' => 'BOOLEAN', 'default' => false],
            'observacao'                       => ['type' => 'TEXT', 'null' => true],
            'fluxo_lote'                       => ['type' => 'BIGINT', 'null' => true],
            'numero_parcela'                   => ['type' => 'BIGINT', 'null' => true],
            'insercao_automatica'              => ['type' => 'BOOLEAN', 'default' => false],
            'codigo_barras'                    => ['type' => 'VARCHAR', 'null' => true],
            'fluxo_empresa'                    => ['type' => 'BOOLEAN', 'default' => true, 'comment' => 'Se true significa que o fluxo é da empresa, se false, o fluxo é do contexto vendedor'],
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
