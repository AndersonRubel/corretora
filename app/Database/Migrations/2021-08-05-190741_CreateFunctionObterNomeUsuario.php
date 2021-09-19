<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFunctionObterNomeUsuario extends Migration
{
    public function up()
    {
        // Cria a Extensão de UUID
        $this->db->query("
            CREATE OR REPLACE FUNCTION obter_nome_usuario(id anyelement, is_nome_abreviado int default 0, is_uuid int default 0) RETURNS text
            LANGUAGE plpgsql
            AS $$

            DECLARE v_nome text;

            BEGIN

                -- Verifica se deve fazer o Where por código ou uuid
                IF is_uuid = 0 THEN
                    SELECT COALESCE(nome, 'Não Identificado')::text AS nome
                      FROM usuario
                     WHERE 1 = 1
                       AND codigo_usuario::text = id::text
                      INTO v_nome;
                ELSE
                    SELECT COALESCE(nome, 'Não Identificado')::text AS nome
                      FROM usuario
                     WHERE 1 = 1
                       AND uuid_usuario::text = id::text
                      INTO v_nome;

                END IF;

                -- Se não encontrar o registro
                IF v_nome IS NULL THEN
                    v_nome = 'Não Identificado';
                END IF;

                -- Verifica se deve retornar o nome completo ou abreviado
                IF is_nome_abreviado = 0 THEN
                    RETURN v_nome;
                ELSE
                    RETURN SPLIT_PART(v_nome, ' ', 1) || ' ' || SPLIT_PART(v_nome, ' ', 2);
                END IF;

            END;
            $$;
        ");
    }

    public function down()
    {
        $this->db->query("DROP FUNCTION obter_nome_usuario");
    }
}
