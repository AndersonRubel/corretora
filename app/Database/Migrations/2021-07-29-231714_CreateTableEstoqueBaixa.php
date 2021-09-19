<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableEstoqueBaixa extends Migration
{
    protected $table = 'estoque_baixa';
    protected $primaryKey = 'codigo_estoque_baixa';
    protected $uuidColumn = 'uuid_estoque_baixa';

    public function up()
    {
        $this->forge->addField([
            "{$this->primaryKey}"               => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true, 'unique' => true],
            "{$this->uuidColumn}"               => ['type' => 'UUID', 'unique' => true],
            'usuario_criacao'                   => ['type' => 'BIGINT', 'null' => true],
            'usuario_alteracao'                 => ['type' => 'BIGINT', 'null' => true],
            'usuario_inativacao'                => ['type' => 'BIGINT', 'null' => true],
            'criado_em'                         => ['type' => 'TIMESTAMPTZ', 'default' => 'NOW()'],
            'alterado_em'                       => ['type' => 'TIMESTAMPTZ', 'null' => true],
            'inativado_em'                      => ['type' => 'TIMESTAMPTZ', 'null' => true],
            'codigo_empresa'                    => ['type' => 'BIGINT'],
            'codigo_estoque'                    => ['type' => 'BIGINT'],
            'codigo_produto'                    => ['type' => 'BIGINT'],
            'codigo_fornecedor'                 => ['type' => 'BIGINT', 'null' => true],
            'codigo_venda'                      => ['type' => 'BIGINT', 'null' => true],
            'transferencia_de_codigo_estoque'   => ['type' => 'BIGINT', 'null' => true],
            'transferencia_para_codigo_estoque' => ['type' => 'BIGINT', 'null' => true],
            'codigo_cadastro_movimentacao_tipo' => ['type' => 'BIGINT'],
            'nome_cadastro_movimentacao_tipo'   => ['type' => 'VARCHAR'],
            'quantidade'                        => ['type' => 'BIGINT'],
            'observacao'                        => ['type' => 'VARCHAR', 'null' => true],
            'movimentacao_lote'                 => ['type' => 'BIGINT', 'null' => true],
        ]);

        $this->forge->addPrimaryKey($this->primaryKey);
        $this->forge->createTable($this->table);

        $this->db->query("ALTER TABLE {$this->table} ALTER COLUMN {$this->uuidColumn} SET DEFAULT uuid_generate_v4()");

        // Cria a Trigger de Historico do Estoque
        $this->db->query("CREATE TRIGGER processa_estoque_historico_baixa_trg AFTER INSERT OR UPDATE OR DELETE ON {$this->table} FOR EACH ROW EXECUTE PROCEDURE processa_estoque_historico()");
    }

    public function down()
    {
        $this->forge->dropTable($this->table);
    }
}
