<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFunctionProcessaEstoqueHistorico extends Migration
{
    public function up()
    {

        $this->db->query("CREATE OR REPLACE FUNCTION processa_estoque_historico() RETURNS pg_catalog.trigger AS $$
            BEGIN

            -- Quando houver uma insercao
            IF (TG_OP = 'INSERT') THEN

                -- Verifica se já existe histórico do produto naquele dia e naquele estoque
                IF (
                    (SELECT COUNT(codigo_produto)
                       FROM estoque_historico
                      WHERE TO_CHAR(criado_em, 'YYYY-MM-DD') = TO_CHAR(NOW(), 'YYYY-MM-DD')
                        AND codigo_empresa = NEW.codigo_empresa
                        AND codigo_estoque = NEW.codigo_estoque
                        AND codigo_produto = NEW.codigo_produto
                    ) = 0
                )
                THEN

                    -- Se o registro foi inserido na tabela de entrada
                    IF (TG_TABLE_NAME = 'estoque_entrada') THEN
                        -- Gera uma linha de historico
                        INSERT INTO estoque_historico (codigo_empresa, codigo_estoque, codigo_produto, quantidade, usuario_criacao, criado_em, valor_fabrica, valor_venda, valor_ecommerce, valor_atacado, movimentacao_lote)
                        SELECT ep.codigo_empresa
                             , e.codigo_estoque
                             , ep.codigo_produto
                             , ee.quantidade
                             , NEW.usuario_criacao
                             , ee.criado_em
                             , ep.valor_fabrica
                             , ep.valor_venda
                             , ep.valor_ecommerce
                             , ep.valor_atacado
                             , NEW.movimentacao_lote
                         FROM estoque_produto ep
                        INNER JOIN estoque e
                           ON e.codigo_estoque = ep.codigo_estoque
                        INNER JOIN estoque_entrada ee
                           ON ee.codigo_produto = ep.codigo_produto
                          AND ee.codigo_estoque = ep.codigo_estoque
                        WHERE ee.codigo_estoque_entrada = NEW.codigo_estoque_entrada
                          AND e.codigo_estoque = NEW.codigo_estoque;

                        -- Gera uma linha com cada item que foi alterado
                        INSERT INTO estoque_historico_item (codigo_empresa, codigo_estoque, codigo_historico, codigo_produto, codigo_entrada, transacao, usuario_criacao, movimentacao_lote)
                        SELECT eh.codigo_empresa
                             , eh.codigo_estoque
                             , eh.codigo_estoque_historico
                             , eh.codigo_produto
                             , NEW.codigo_estoque_entrada
                             , 'ENTRADA ESTOQUE'
                             , NEW.usuario_criacao
                             , NEW.movimentacao_lote
                          FROM estoque_historico eh
                         WHERE TO_CHAR(eh.criado_em, 'YYYY-MM-DD') = TO_CHAR(NOW(), 'YYYY-MM-DD')
                           AND eh.codigo_empresa = NEW.codigo_empresa
                           AND eh.codigo_estoque = NEW.codigo_estoque
                           AND eh.codigo_produto = NEW.codigo_produto
                         LIMIT 1;
                    END IF;

                    -- Se o registro foi inserido na tabela de baixa
                    IF (TG_TABLE_NAME = 'estoque_baixa') THEN
                        -- Gera uma linha de historico
                        INSERT INTO estoque_historico (codigo_empresa, codigo_estoque, codigo_produto, quantidade, usuario_criacao, criado_em, valor_fabrica, valor_venda, valor_ecommerce, valor_atacado, movimentacao_lote)
                        SELECT ep.codigo_empresa
                             , e.codigo_estoque
                             , ep.codigo_produto
                             , eb.quantidade
                             , NEW.usuario_criacao
                             , eb.criado_em
                             , ep.valor_fabrica
                             , ep.valor_venda
                             , ep.valor_ecommerce
                             , ep.valor_atacado
                             , NEW.movimentacao_lote
                          FROM estoque_produto ep
                         INNER JOIN estoque e
                            ON e.codigo_estoque = ep.codigo_estoque
                         INNER JOIN estoque_baixa eb
                            ON eb.codigo_produto = ep.codigo_produto
                           AND eb.codigo_estoque = ep.codigo_estoque
                         WHERE eb.codigo_estoque_baixa = NEW.codigo_estoque_baixa
                           AND e.codigo_estoque = NEW.codigo_estoque;

                        -- Gera uma linha com cada item que foi alterado
                        INSERT INTO estoque_historico_item (codigo_empresa, codigo_estoque, codigo_historico, codigo_produto, codigo_baixa, transacao, usuario_criacao, movimentacao_lote)
                        SELECT eh.codigo_empresa
                             , eh.codigo_estoque
                             , eh.codigo_estoque_historico
                             , eh.codigo_produto
                             , NEW.codigo_estoque_baixa
                             , 'BAIXA ESTOQUE'
                             , NEW.usuario_criacao
                             , NEW.movimentacao_lote
                          FROM estoque_historico eh
                         WHERE TO_CHAR(eh.criado_em, 'YYYY-MM-DD') = TO_CHAR(NOW(), 'YYYY-MM-DD')
                           AND eh.codigo_empresa = NEW.codigo_empresa
                           AND eh.codigo_estoque = NEW.codigo_estoque
                           AND eh.codigo_produto = NEW.codigo_produto
                         LIMIT 1;
                    END IF;

                RETURN NEW;

                END IF;

                IF(
                    (SELECT COUNT(codigo_produto)
                       FROM estoque_historico
                      WHERE TO_CHAR(criado_em, 'YYYY-MM-DD') = TO_CHAR(NOW(), 'YYYY-MM-DD')
                        AND codigo_empresa = NEW.codigo_empresa
                        AND codigo_estoque = NEW.codigo_estoque
                        AND codigo_produto = NEW.codigo_produto
                    ) > 0
                )
                THEN

                    -- Se o registro foi inserido na tabela de entrada
                    IF (TG_TABLE_NAME = 'estoque_entrada') THEN

                        -- Atualiza a quantidade de itens
                        UPDATE estoque_historico
                           SET quantidade = quantidade + NEW.quantidade, alterado_em = NOW(), usuario_alteracao = NEW.usuario_criacao
                         WHERE TO_CHAR(criado_em, 'YYYY-MM-DD') = TO_CHAR(NOW(), 'YYYY-MM-DD')
                           AND codigo_empresa = NEW.codigo_empresa
                           AND codigo_estoque = NEW.codigo_estoque
                           AND codigo_produto = NEW.codigo_produto;

                        INSERT INTO estoque_historico_item (codigo_empresa, codigo_estoque, codigo_historico, codigo_produto, codigo_entrada, transacao, usuario_criacao, movimentacao_lote)
                        SELECT eh.codigo_empresa
                             , eh.codigo_estoque
                             , eh.codigo_estoque_historico
                             , eh.codigo_produto
                             , NEW.codigo_estoque_entrada
                             , 'ENTRADA ESTOQUE'
                             , NEW.usuario_criacao
                             , NEW.movimentacao_lote
                          FROM estoque_historico eh
                         WHERE TO_CHAR(eh.criado_em, 'YYYY-MM-DD') = TO_CHAR(NOW(), 'YYYY-MM-DD')
                           AND codigo_empresa = NEW.codigo_empresa
                           AND codigo_estoque = NEW.codigo_estoque
                           AND codigo_produto = NEW.codigo_produto
                         LIMIT 1;

                        RETURN NEW;

                    END IF;

                    -- Se o registro foi inserido na tabela de baixa
                    IF (TG_TABLE_NAME = 'estoque_baixa') THEN

                        -- Atualiza a quantidade de itens
                        UPDATE estoque_historico
                           SET quantidade = quantidade - NEW.quantidade, alterado_em = NOW(), usuario_alteracao = NEW.usuario_criacao
                         WHERE TO_CHAR(criado_em, 'YYYY-MM-DD') = TO_CHAR(NOW(), 'YYYY-MM-DD')
                           AND codigo_empresa = NEW.codigo_empresa
                           AND codigo_estoque = NEW.codigo_estoque
                           AND codigo_produto = NEW.codigo_produto;

                        INSERT INTO estoque_historico_item (codigo_empresa, codigo_estoque, codigo_historico, codigo_produto, codigo_baixa, transacao, usuario_criacao, movimentacao_lote)
                        SELECT eh.codigo_empresa
                             , eh.codigo_estoque
                             , eh.codigo_estoque_historico
                             , eh.codigo_produto
                             , NEW.codigo_estoque_baixa
                             , 'BAIXA ESTOQUE'
                             , NEW.usuario_criacao
                             , NEW.movimentacao_lote
                          FROM estoque_historico eh
                         WHERE TO_CHAR(eh.criado_em, 'YYYY-MM-DD') = TO_CHAR(NOW(), 'YYYY-MM-DD')
                           AND codigo_empresa = NEW.codigo_empresa
                           AND codigo_estoque = NEW.codigo_estoque
                           AND codigo_produto = NEW.codigo_produto
                         LIMIT 1;

                    END IF;

                    RETURN NEW;
                END IF;
            RETURN NEW;
            END IF;

            RETURN NULL;
            END;
            $$
            LANGUAGE plpgsql VOLATILE
            COST 100;
        ");
    }

    public function down()
    {
        $this->db->query("DROP FUNCTION processa_estoque_historico");
    }
}
