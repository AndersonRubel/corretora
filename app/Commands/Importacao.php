<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Exception;

class Importacao extends BaseCommand
{
    protected $group       = 'Importação';
    protected $name        = 'importa:start';
    protected $usage       = 'importa:start';
    protected $description = 'Executa a importação de todos registros no sistema';
    protected $arguments   = [];

    /**
     * Actually execute a command.
     * @param array $params
     */
    public function run(array $params)
    {
        $totalArquivos = 19;

        CLI::write('Iniciando Importacao.', 'blue');

        try {
            echo command('importa:empresa');
            CLI::showProgress(1, $totalArquivos);

            echo command('importa:empresacategoria');
            CLI::newLine();
            CLI::showProgress(2, $totalArquivos);

            echo command('importa:empresacomissao');
            CLI::newLine();
            CLI::showProgress(3, $totalArquivos);

            echo command('importa:usuario');
            CLI::newLine();
            CLI::showProgress(4, $totalArquivos);

            echo command('importa:empresausuario');
            CLI::newLine();
            CLI::showProgress(5, $totalArquivos);

            echo command('importa:vendedor');
            CLI::newLine();
            CLI::showProgress(6, $totalArquivos);

            echo command('importa:cliente');
            CLI::newLine();
            CLI::showProgress(7, $totalArquivos);

            echo command('importa:vendedorcliente');
            CLI::newLine();
            CLI::showProgress(8, $totalArquivos);

            echo command('importa:fornecedor');
            CLI::newLine();
            CLI::showProgress(9, $totalArquivos);

            echo command('importa:produto');
            CLI::newLine();
            CLI::showProgress(10, $totalArquivos);

            echo command('importa:produtocategoria');
            CLI::newLine();
            CLI::showProgress(11, $totalArquivos);

            echo command('importa:estoque');
            CLI::newLine();
            CLI::showProgress(12, $totalArquivos);

            echo command('importa:estoquemovimentacao');
            CLI::newLine();
            CLI::showProgress(13, $totalArquivos);

            echo command('importa:venda');
            CLI::newLine();
            CLI::showProgress(15, $totalArquivos);

            echo command('importa:financeirofluxovenda');
            CLI::newLine();
            CLI::showProgress(16, $totalArquivos);

            echo command('importa:faturamento');
            CLI::newLine();
            CLI::showProgress(17, $totalArquivos);

            echo command('importa:financeirofluxofechamento');
            CLI::newLine();
            CLI::showProgress(18, $totalArquivos);

            echo command('importa:fixnames');
            CLI::newLine();
            CLI::showProgress(19, $totalArquivos);

            CLI::newLine();
            CLI::write('Importacao Finalizada com sucesso.', 'blue');
        } catch (Exception $e) {
            CLI::error($e->getMessage());
        }
    }
}
