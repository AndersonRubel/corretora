<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;
use Exception;

class ImportaVendedorCliente extends BaseCommand
{
    protected $group       = 'Importação';
    protected $name        = 'importa:vendedorcliente';
    protected $usage       = 'importa:vendedorcliente';
    protected $description = 'Importa a relação entre vendedor e cliente no sistema';
    protected $arguments   = [];

    /**
     * Actually execute a command.
     * @param array $params
     */
    public function run(array $params)
    {
        CLI::write('Cadastro de Vendedor Cliente Iniciado.', 'light_green');

        helper('string');

        // Instancia os Bancos
        $dbPortaJoias = Database::connect('default');
        $dbBackOffice = Database::connect('bl');

        //Inicia as operações de DB
        $dbPortaJoias->transStart();
        try {
            $vendedorclientes = $dbBackOffice->query("SELECT vc.*,p.nome_completo_razao_social,p.codigo_cliente_iluminare AS codigo_empresa
                                                        FROM vendedor_cliente as vc
                                                   LEFT JOIN cliente as c
                                                          ON c.codigo_cliente = vc.codigo_cliente
                                                   LEFT JOIN pessoa as p
                                                          ON p.codigo_pessoa = c.codigo_pessoa
                                                    ORDER BY vc.codigo_vendedor ASC
                                                    ")->getResultArray();

            foreach ($vendedorclientes as $key => $value) {

                $vendedor =  $dbPortaJoias->query("SELECT v.codigo_vendedor
                                                    FROM vendedor as v
                                                   WHERE SPLIT_PART(v.razao_social, '#_', 1) = {$value['codigo_vendedor']}::text
                                                 ")->getResultArray();


                $cliente =  $dbPortaJoias->query("SELECT codigo_cliente
                                                    FROM cliente
                                                  WHERE SPLIT_PART(razao_social, '#_', 1) = {$value['codigo_cliente']}::text
                                                ")->getResultArray();

                $vendedorcliente = [
                    'usuario_criacao' => 1,
                    'codigo_empresa'  => $value['codigo_empresa'],
                    'codigo_vendedor' => $vendedor[0]['codigo_vendedor'],
                    'codigo_cliente'  => $cliente[0]['codigo_cliente']
                ];

                //verifica se o registro não existe
                $numRows = $dbPortaJoias->table('vendedor_cliente')->where($vendedorcliente)->countAllResults();

                if ($numRows === 0) {
                    $dbPortaJoias->table('vendedor_cliente')->insert($vendedorcliente);
                }
            }
            // Finaliza as operações de DB
            $dbPortaJoias->transComplete();

            CLI::write('Cadastro de Vendedor Cliente Finalizado.', 'green');
        } catch (Exception $e) {
            CLI::error($e->getMessage());
            die;
        }
    }
}
