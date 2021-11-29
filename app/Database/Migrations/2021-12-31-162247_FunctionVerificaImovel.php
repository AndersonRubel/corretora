<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FunctionVerificaImovel extends Migration
{
    public function up()
    {
        $this->db->query("CREATE OR REPLACE FUNCTION verifica_imovel() RETURNS pg_catalog.trigger AS $$
            BEGIN

                 IF NEW.area_total IS NOT NULL THEN
                    IF NEW.area_total :: integer  <= 0 THEN
                       RAISE EXCEPTION '|A area util tem que ser maior que 0|';
                    END IF;
                 END IF;

                 IF NEW.area_construida IS NOT NULL THEN

                    IF NEW.codigo_tipo_imovel :: integer != 3 THEN
                       IF NEW.area_construida :: integer <= 0 THEN
                          RAISE EXCEPTION '|A area construida tem que ser maior que 0|';
                       END IF;
                    END IF;

                 END IF;

                 IF NEW.valor <= 0 THEN
                    RAISE EXCEPTION '|O valor tem que ser maior que 0|';
                 END IF;
                 RETURN NEW;
            END;

            $$
            LANGUAGE plpgsql VOLATILE
        ");
    }

    public function down()
    {
        $this->db->query("DROP FUNCTION verifica_imovel");
    }
}
