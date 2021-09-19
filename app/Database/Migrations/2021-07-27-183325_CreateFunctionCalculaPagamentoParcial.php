<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFunctionCalculaPagamentoParcial extends Migration
{
    public function up()
    {

        $this->db->query("CREATE OR REPLACE FUNCTION calcula_pagamento_parcial() RETURNS pg_catalog.trigger AS $$
            BEGIN

            -- Quando for realizado um pagamento parcial
            IF (TG_OP = 'INSERT') THEN
                -- Atualiza o valor pago parcial na tabela de fluxo
                UPDATE financeiro_fluxo
                   SET valor_pago_parcial = (SELECT SUM(valor)
                                               FROM financeiro_fluxo_parcial
                                              WHERE codigo_financeiro_fluxo = NEW.codigo_financeiro_fluxo
                                                AND inativado_em IS NULL
                                            )
                 WHERE codigo_financeiro_fluxo = NEW.codigo_financeiro_fluxo;

                -- Se o valor pago parcial atingir o valor total liquido do fluxo, marca como PAGO
                IF (
                    (SELECT valor_liquido FROM financeiro_fluxo WHERE codigo_financeiro_fluxo = NEW.codigo_financeiro_fluxo)
                    =
                    (SELECT SUM(valor) FROM financeiro_fluxo_parcial WHERE codigo_financeiro_fluxo = NEW.codigo_financeiro_fluxo AND inativado_em IS NULL)
                )
                THEN
                    UPDATE financeiro_fluxo SET situacao = 't', data_pagamento = NEW.data_pagamento, usuario_alteracao = NEW.usuario_criacao WHERE codigo_financeiro_fluxo = NEW.codigo_financeiro_fluxo;
                ELSE
                    UPDATE financeiro_fluxo SET situacao = 'f', data_pagamento = NULL, usuario_alteracao = NEW.usuario_criacao WHERE codigo_financeiro_fluxo = NEW.codigo_financeiro_fluxo;
                END IF;

                RETURN NEW;

            -- Quando for Inativado/Reativado um pagamento parcial
            ELSIF (TG_OP = 'UPDATE') THEN
                UPDATE financeiro_fluxo
                   SET valor_pago_parcial = (SELECT SUM(valor)
                                               FROM financeiro_fluxo_parcial
                                              WHERE codigo_financeiro_fluxo = NEW.codigo_financeiro_fluxo
                                                AND inativado_em IS NULL
                                            )
                 WHERE codigo_financeiro_fluxo = NEW.codigo_financeiro_fluxo;

                IF (NEW.inativado_em IS NOT NULL)
                THEN
                -- Significa que o pagamento parcial foi excluido, ja marca como pendente (Significa que o fluxo nao ta pago totalmente)
                    UPDATE financeiro_fluxo SET situacao = 'f', data_pagamento = NULL, usuario_alteracao = NEW.usuario_alteracao, alterado_em = NOW() WHERE codigo_financeiro_fluxo = NEW.codigo_financeiro_fluxo;
                ELSE
                -- Caso contrario, verifica se os valores batem, e segue a logica
                    IF (
                        (SELECT valor_liquido FROM financeiro_fluxo WHERE codigo_financeiro_fluxo = NEW.codigo_financeiro_fluxo)
                        =
                        (SELECT SUM(valor) FROM financeiro_fluxo_parcial WHERE codigo_financeiro_fluxo = NEW.codigo_financeiro_fluxo AND inativado_em IS NULL)
                    )
                    THEN
                        UPDATE financeiro_fluxo SET situacao = 't', data_pagamento = NEW.data_pagamento, usuario_alteracao = NEW.usuario_alteracao, alterado_em = NOW() WHERE codigo_financeiro_fluxo = NEW.codigo_financeiro_fluxo;
                    ELSE
                        UPDATE financeiro_fluxo SET situacao = 'f', data_pagamento = NULL, usuario_alteracao = NEW.usuario_alteracao, alterado_em = NOW() WHERE codigo_financeiro_fluxo = NEW.codigo_financeiro_fluxo;
                    END IF;
                END IF;

                RETURN NEW;

            ELSIF (TG_OP = 'DELETE') THEN
                UPDATE financeiro_fluxo
                   SET valor_pago_parcial = (SELECT SUM(valor)
                                               FROM financeiro_fluxo_parcial
                                              WHERE codigo_financeiro_fluxo = OLD.codigo_financeiro_fluxo
                                                AND inativado_em IS NULL
                                            )
                 WHERE codigo_financeiro_fluxo = OLD.codigo_financeiro_fluxo;

                IF (
                    (SELECT valor_liquido FROM financeiro_fluxo WHERE codigo_financeiro_fluxo = OLD.codigo_financeiro_fluxo)
                    =
                    (SELECT SUM(valor) FROM financeiro_fluxo_parcial WHERE codigo_financeiro_fluxo = OLD.codigo_financeiro_fluxo)
                )
                THEN
                    UPDATE financeiro_fluxo SET situacao = 't', data_pagamento = OLD.data_pagamento, usuario_alteracao = OLD.usuario_alteracao, alterado_em = NOW() WHERE codigo_financeiro_fluxo = OLD.codigo_financeiro_fluxo;
                ELSE
                    UPDATE financeiro_fluxo SET situacao = 'f', data_pagamento = NULL, usuario_alteracao = OLD.usuario_alteracao, alterado_em = NOW() WHERE codigo_financeiro_fluxo = OLD.codigo_financeiro_fluxo;
                END IF;

                RETURN OLD;

            END IF;
            RETURN NULL;

            RETURN NEW;
            end;
            $$
            LANGUAGE plpgsql VOLATILE
            COST 100;
        ");
    }

    public function down()
    {
        $this->db->query("DROP FUNCTION calcula_pagamento_parcial");
    }
}
