<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFunctionNumberFormat extends Migration
{
    public function up()
    {
        $this->db->query("
            CREATE OR REPLACE FUNCTION number_format(valor anyelement) RETURNS text
            LANGUAGE plpgsql
            AS $$

            BEGIN

                -- RETURN trim(to_char(valor, '999G999G999G999G999G999G999G990D99'));
                RETURN trim(to_char(valor, 'L999G999G99'));

            END;
            $$;
        ");
    }

    public function down()
    {
        $this->db->query("DROP FUNCTION number_format");
    }
}
