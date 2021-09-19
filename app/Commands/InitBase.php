<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Exception;

class InitBase extends BaseCommand
{
    protected $group       = 'Importação';
    protected $name        = 'importa:initbase';
    protected $usage       = 'importa:initbase';
    protected $description = 'Roda as Migrations e os Seeders';
    protected $arguments   = [];

    /**
     * Actually execute a command.
     * @param array $params
     */
    public function run(array $params)
    {
        CLI::write('Iniciando...', 'blue');

        try {
            echo command('migrate:rollback');
            CLI::newLine();

            echo command('migrate');
            CLI::newLine();

            echo command('db:seed DatabaseSeeder');
            CLI::newLine();

            // Pergunta se quer importar os dados
            $respostaImportacao = CLI::prompt('Deseja importar os dados?', ['S', 'N']);

            // Se veio o parametro, roda a importacao
            if (strtoupper($respostaImportacao) == 'S') {
                echo command('importa:start');
                CLI::newLine();
            }

            CLI::write('Finalizado com sucesso...', 'blue');
        } catch (Exception $e) {
            CLI::error($e->getMessage());
            die;
        }
    }
}
