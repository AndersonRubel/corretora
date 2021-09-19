<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;
use Exception;

class ImportaEmpresa extends BaseCommand
{
    protected $group       = 'Importação';
    protected $name        = 'importa:empresa';
    protected $usage       = 'importa:empresa';
    protected $description = 'Importa as Empresas no sistema';
    protected $arguments   = [];

    /**
     * Actually execute a command.
     * @param array $params
     */
    public function run(array $params)
    {
        CLI::write('Cadastro de Empresa Iniciado.', 'light_green');

        helper('string');

        // Instancia os Bancos
        $dbPortaJoias = Database::connect('default');
        $dbBackOffice = Database::connect('bl');

        //Inicia as operações de DB
        $dbPortaJoias->transStart();
        try {
            $empresas = $dbBackOffice->query('SELECT * FROM cliente_iluminare ORDER BY codigo_cliente_iluminare ASC')->getResultArray();

            foreach ($empresas as $key => $value) {
                $empresa = [
                    'usuario_criacao'          => 1,
                    'criado_em'                => $value['criado_em'],
                    'alterado_em'              => $value['alterado_em'],
                    'inativado_em'             => $value['excluido_em'],
                    'tipo_pessoa'              => strlen(onlyNumber($value['cpf_cnpj'])) == 11 ? 1 : 2,
                    'razao_social'             => !empty($value['nome_razao_social']) ? $value['nome_razao_social'] : $value['nome_fantasia'],
                    'nome_fantasia'            => !empty($value['nome_fantasia']) ? $value['nome_fantasia'] : $value['nome_razao_social'],
                    'cpf_cnpj'                 => onlyNumber($value['cpf_cnpj']),
                    'codigo_empresa_situacao'  => 1,
                    'endereco'                 => json_encode([]),
                    'responsavel'              => json_encode([]),
                    'configuracao_nota_fiscal' => json_encode([]),
                ];

                //verifica se o registro não existe
                $numRows = $dbPortaJoias->table('empresa')->where($empresa)->countAllResults();

                if ($numRows === 0) {
                    $dbPortaJoias->table('empresa')->insert($empresa);

                    $codigoEmpresa = $dbPortaJoias->insertID('empresa_codigo_empresa_seq');

                    // Insere um centro de custo padrão
                    $dbPortaJoias->table('empresa_centro_custo')->insert([
                        'codigo_empresa' => $codigoEmpresa,
                        'nome'           => 'Padrão',
                        'padrao'         => true
                    ]);

                    // Insere uma conta do Financeiro padrão
                    $dbPortaJoias->table('empresa_conta')->insert([
                        'codigo_empresa' => $codigoEmpresa,
                        'nome'           => 'Padrão',
                        'padrao'         => true
                    ]);

                    // Insere um Estoque
                    $dbPortaJoias->table('estoque')->insert([
                        'codigo_empresa' => $codigoEmpresa,
                        'nome'           => ucwords($value['nome_razao_social'], ' '),
                        'padrao'         => true
                    ]);
                }
            }

            // Finaliza as operações de DB
            $dbPortaJoias->transComplete();

            CLI::write('Cadastro de Empresa Finalizado.', 'green');
        } catch (Exception $e) {
            CLI::error($e->getMessage());
            die;
        }
    }
}
