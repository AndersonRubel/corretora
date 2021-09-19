<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;
use Exception;

class ImportaEmpresaComissao extends BaseCommand
{
    protected $group       = 'Importação';
    protected $name        = 'importa:empresacomissao';
    protected $usage       = 'importa:empresacomissao';
    protected $description = 'Importa as Comissões da Empresa no sistema';
    protected $arguments   = [];

    /**
     * Actually execute a command.
     * @param array $params
     */
    public function run(array $params)
    {
        CLI::write('Cadastro de Empresa Comissao Iniciado.', 'light_green');

        helper('string');

        // Instancia os Bancos
        $dbPortaJoias = Database::connect('default');
        $dbBackOffice = Database::connect('bl');

        //Inicia as operações de DB
        $dbPortaJoias->transStart();
        try {
            $comissoes = $dbBackOffice->query('SELECT * FROM comissionamento')->getResultArray();
            foreach ($comissoes as $key => $value) {
                $comissao = [
                    'usuario_criacao' => 1,
                    'criado_em'       => $value['criado_em'],
                    'alterado_em'     => $value['alterado_em'],
                    'inativado_em'    => $value['excluido_em'],
                    'codigo_empresa'  => $value['codigo_cliente_iluminare'],
                    'codigo_vendedor' => !empty($value['codigo_vendedor']) ? $value['codigo_vendedor'] : null,
                    'percentual'      => onlyNumber($value['percentual']),
                    'valor_inicial'   => !empty($value['valor_inicial']) ? onlyNumber($value['valor_inicial']) : null,
                    'valor_final'     => !empty($value['valor_final']) ? onlyNumber($value['valor_final']) : null,
                ];

                //verifica se o registro não existe
                $numRows = $dbPortaJoias->table('empresa_comissao')->where($comissao)->countAllResults();

                if ($numRows === 0) {
                    $dbPortaJoias->table('empresa_comissao')->insert($comissao);
                }
            }

            // Finaliza as operações de DB
            $dbPortaJoias->transComplete();

            CLI::write('Cadastro de Empresa Comissao Finalizado.', 'green');
        } catch (Exception $e) {
            CLI::error($e->getMessage());
            die;
        }
    }
}
