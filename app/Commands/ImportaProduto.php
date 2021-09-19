<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;
use Exception;

class ImportaProduto extends BaseCommand
{
    protected $group       = 'Importação';
    protected $name        = 'importa:produto';
    protected $usage       = 'importa:produto';
    protected $description = 'Importa os Produtos no sistema';
    protected $arguments   = [];

    /**
     * Actually execute a command.
     * @param array $params
     */
    public function run(array $params)
    {
        CLI::write('Cadastro de Produto Iniciado.', 'light_green');

        helper('string');

        // Instancia os Bancos
        $dbPortaJoias = Database::connect('default');
        $dbBackOffice = Database::connect('bl');

        //Inicia as operações de DB
        $dbPortaJoias->transStart();
        try {

            $produtos = $dbBackOffice->query("SELECT *
                                                FROM produto
                                            ORDER BY codigo_produto ASC
                                            ")->getResultArray();

            foreach ($produtos as $key => $value) {

                $fornecedor = $dbPortaJoias->query("SELECT codigo_fornecedor
                                                      FROM fornecedor
                                                     WHERE SPLIT_PART(razao_social, '#_', 1) = {$value['codigo_fornecedor']}::text
                                                  ")->getResultArray();

                $produto = [
                    'usuario_criacao'       => 1,
                    'criado_em'             => $value['criado_em'],
                    'alterado_em'           => $value['alterado_em'],
                    'inativado_em'          => $value['excluido_em'],
                    'codigo_empresa'        => $value['codigo_cliente_iluminare'],
                    'codigo_fornecedor'     => $fornecedor[0]['codigo_fornecedor'],
                    'referencia_fornecedor' => $value['referencia_fornecedor'],
                    'codigo_barras'         => $value['codigo_loja'],
                    'nome'                  => "{$value['codigo_produto']}#_{$value['nome']}",
                    'descricao'             => $value['descricao'],
                    'diretorio_imagem'      => $value['imagem'],
                ];

                //verifica se o registro não existe
                // $numRows = $dbPortaJoias->table('produto')->where($produto)->countAllResults();

                // if ($numRows === 0) {
                $dbPortaJoias->table('produto')->insert($produto);
                // }
            }
            // Finaliza as operações de DB
            $dbPortaJoias->transComplete();

            CLI::write('Cadastro de Produto Finalizado.', 'green');
        } catch (Exception $e) {
            CLI::error($e->getMessage());
            die;
        }
    }
}
