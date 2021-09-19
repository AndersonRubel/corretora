<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class InitDatabase extends Migration
{
	public function up()
	{
		// Cria a Extensão de UUID
        $this->db->query('CREATE EXTENSION IF NOT EXISTS "uuid-ossp"');
	}

	public function down()
	{
		//
	}
}
