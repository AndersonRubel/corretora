<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FunctionVerificaReserva extends Migration
{
	public function up()
	{
		$this->db->query("CREATE OR REPLACE FUNCTION verifica_reserva() RETURNS pg_catalog.trigger AS $$
            BEGIN

                 IF NEW.data_inicio > NEW.data_fim THEN
                    RAISE EXCEPTION '|A Data de início não pode ser maior que a data de término|';
                 END IF;

                 IF NEW.data_inicio < CURRENT_DATE THEN
                    RAISE EXCEPTION '|A Data de início não pode ser menor que a data atual|';
                 END IF;
              IF (TG_OP = 'INSERT') THEN
                 -- verifica se não existe reserva para o imovel no periodo
                 IF EXISTS (
                     SELECT 1
                       FROM reserva
                      WHERE codigo_imovel = NEW.codigo_imovel
                        AND inativado_em IS NULL
                        AND ((data_inicio, data_fim) OVERLAPS
                            (NEW.data_inicio, NEW.data_fim))
                 )
                 THEN
                     RAISE EXCEPTION '|impossível reservar - existe outra reserva para este imovel neste periodo|';
                 END IF;
               END IF;
               IF (TG_OP = 'UPDATE') THEN
                 -- verifica se não existe reserva para o imovel no periodo
                 IF EXISTS (
                     SELECT 1
                       FROM reserva
                      WHERE codigo_imovel = NEW.codigo_imovel
                        AND uuid_reserva != NEW.uuid_reserva
                        AND inativado_em IS NULL
                        AND ((data_inicio, data_fim) OVERLAPS
                            (NEW.data_inicio, NEW.data_fim))
                 )
                 THEN
                     RAISE EXCEPTION '|impossível reservar - existe outra reserva para este imovel neste periodo|';
                 END IF;
               END IF;
                 RETURN NEW;
            END;

            $$
            LANGUAGE plpgsql VOLATILE
        ");
    }

	public function down()
	{
		$this->db->query("DROP FUNCTION verifica_reserva");
	}
}