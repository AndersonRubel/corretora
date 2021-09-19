<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;
use Exception;

class ImportaEmpresaCategoria extends BaseCommand
{
    protected $group       = 'Importação';
    protected $name        = 'importa:empresacategoria';
    protected $usage       = 'importa:empresacategoria';
    protected $description = 'Importa as Categorias do Estoque da Empresa no sistema';
    protected $arguments   = [];

    /**
     * Actually execute a command.
     * @param array $params
     */
    public function run(array $params)
    {
        CLI::write('Cadastro de Empresa Categoria Iniciado.', 'light_green');

        helper('string');

        // Instancia os Bancos
        $dbPortaJoias = Database::connect('default');
        $dbBackOffice = Database::connect('bl');

        //Inicia as operações de DB
        $dbPortaJoias->transStart();
        try {
            $categorias = $dbBackOffice->query('SELECT * FROM categoria')->getResultArray();
            foreach ($categorias as $key => $value) {
                $categoria = [
                    'usuario_criacao' => 1,
                    'criado_em'       => $value['criado_em'],
                    'alterado_em'     => $value['alterado_em'],
                    'inativado_em'    => $value['excluido_em'],
                    'codigo_empresa'  => $value['codigo_cliente_iluminare'],
                    'nome'            => "{$value['codigo_categoria']}#_{$value['rotulo']}",
                ];

                //verifica se o registro não existe
                $numRows = $dbPortaJoias->table('empresa_categoria')->where($categoria)->countAllResults();

                if ($numRows === 0) {
                    $dbPortaJoias->table('empresa_categoria')->insert($categoria);
                }
            }

            // Finaliza as operações de DB
            $dbPortaJoias->transComplete();

            CLI::write('Cadastro de Empresa Categoria Finalizado.', 'green');
        } catch (Exception $e) {
            CLI::error($e->getMessage());
            die;
        }
    }
}
