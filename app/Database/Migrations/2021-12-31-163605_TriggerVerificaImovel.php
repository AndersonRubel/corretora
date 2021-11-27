<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TriggerVerificaImovel extends Migration
{
	public function up()
	{
		$this->db->query("CREATE TRIGGER verifica_imovel
                          BEFORE INSERT OR UPDATE ON imovel FOR EACH ROW
                          EXECUTE PROCEDURE verifica_imovel();");
	}

	public function down()
	{
		$this->db->query("DROP TRIGGER IF EXISTS verifica_imovel ON imovel");
	}
}
