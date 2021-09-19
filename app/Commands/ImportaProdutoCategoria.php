<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;
use Exception;

class ImportaProdutoCategoria extends BaseCommand
{
    protected $group       = 'Importação';
    protected $name        = 'importa:produtocategoria';
    protected $usage       = 'importa:produtocategoria';
    protected $description = 'Importa as Categorias dos Produtos no sistema';
    protected $arguments   = [];

    /**
     * Actually execute a command.
     * @param array $params
     */
    public function run(array $params)
    {
        CLI::write('Cadastro de Produto Categoria Iniciado.', 'light_green');

        helper('string');

        // Instancia os Bancos
        $dbPortaJoias = Database::connect('default');
        $dbBackOffice = Database::connect('bl');

        //Inicia as operações de DB
        $dbPortaJoias->transStart();
        try {
            $produtoCategorias = $dbBackOffice->query("SELECT *
                                                         FROM produto_categoria
                                                     ORDER BY codigo_produto ASC
                                                     ")->getResultArray();

            foreach ($produtoCategorias as $key => $value) {

                $produto = $dbPortaJoias->query("SELECT codigo_produto, codigo_empresa
                                                   FROM produto
                                                  WHERE SPLIT_PART(nome, '#_', 1) = {$value['codigo_produto']}::text
                                               ")->getResultArray();

                $empresaCateg = $dbPortaJoias->query("SELECT codigo_empresa_categoria
                                                        FROM empresa_categoria
                                                       WHERE SPLIT_PART(nome, '#_', 1) = {$value['codigo_categoria']}::text
                                                    ")->getResultArray();
                $prodCat = [
                    'usuario_criacao'          => 1,
                    'codigo_empresa'           => $produto[0]['codigo_empresa'],
                    'codigo_produto'           => $produto[0]['codigo_produto'],
                    'codigo_empresa_categoria' => $empresaCateg[0]['codigo_empresa_categoria'],
                ];

                //verifica se o registro não existe
                $numRows = $dbPortaJoias->table('produto_categoria')->where($prodCat)->countAllResults();

                if ($numRows === 0) {
                    $dbPortaJoias->table('produto_categoria')->insert($prodCat);
                }
            }
            // Finaliza as operações de DB
            $dbPortaJoias->transComplete();

            CLI::write('Cadastro de Produto Categoria Finalizado.', 'green');
        } catch (Exception $e) {
            CLI::error($e->getMessage());
            die;
        }
    }
}
