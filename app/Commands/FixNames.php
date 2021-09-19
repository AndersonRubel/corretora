<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;
use Exception;

class FixNames extends BaseCommand
{
    protected $group       = 'Importação';
    protected $name        = 'importa:fixnames';
    protected $usage       = 'importa:fixnames';
    protected $description = 'Ajusta os nomes nas tabelas';
    protected $arguments   = [];

    /**
     * Actually execute a command.
     * @param array $params
     */
    public function run(array $params)
    {
        CLI::write('Ajustando os nomes dos registros.', 'light_green');

        helper('string');

        // Instancia os Bancos
        $dbPortaJoias = Database::connect('default');

        //Inicia as operações de DB
        $dbPortaJoias->transStart();
        try {

            $dbPortaJoias->query("UPDATE cliente SET razao_social = SPLIT_PART(razao_social, '#_', 2) WHERE razao_social LIKE '%#_%'");
            $dbPortaJoias->query("UPDATE empresa_categoria SET nome = SPLIT_PART(nome, '#_', 2) WHERE nome LIKE '%#_%'");
            $dbPortaJoias->query("UPDATE fornecedor SET razao_social = SPLIT_PART(razao_social, '#_', 2) WHERE razao_social LIKE '%#_%'");
            $dbPortaJoias->query("UPDATE produto SET nome = SPLIT_PART(nome, '#_', 2) WHERE nome LIKE '%#_%'");
            $dbPortaJoias->query("UPDATE usuario SET nome = SPLIT_PART(nome, '#_', 2) WHERE nome LIKE '%#_%'");
            $dbPortaJoias->query("UPDATE vendedor SET razao_social = SPLIT_PART(razao_social, '#_', 2) WHERE razao_social LIKE '%#_%'");
            $dbPortaJoias->query("UPDATE financeiro_fluxo SET nome = SPLIT_PART(nome, '#_', 2) WHERE nome LIKE '%#_%'");
            $dbPortaJoias->query("UPDATE venda_produto SET nome_produto = SPLIT_PART(nome_produto, '#_', 2) WHERE nome_produto LIKE '%#_%'");

            // Finaliza as operações de DB
            $dbPortaJoias->transComplete();

            CLI::write('Ajuste Finalizado.', 'green');
        } catch (Exception $e) {
            CLI::error($e->getMessage());
            die;
        }
    }
}
