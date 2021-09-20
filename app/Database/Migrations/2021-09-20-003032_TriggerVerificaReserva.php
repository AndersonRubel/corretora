<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TriggerVerificaReserva extends Migration
{
	public function up()
	{
		$this->db->query("CREATE TRIGGER verifica_reserva
                          BEFORE INSERT OR UPDATE ON reserva FOR EACH ROW
                          EXECUTE PROCEDURE verifica_reserva();");
	}

	public function down()
	{
		$this->db->query("DROP TRIGGER IF EXISTS verifica_reserva ON reserva");
	}
}
