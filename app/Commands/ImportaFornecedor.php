<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;
use Exception;

class ImportaFornecedor extends BaseCommand
{
    protected $group       = 'Importação';
    protected $name        = 'importa:fornecedor';
    protected $usage       = 'importa:fornecedor';
    protected $description = 'Importa os fornecedores no sistema';
    protected $arguments   = [];

    /**
     * Actually execute a command.
     * @param array $params
     */
    public function run(array $params)
    {
        CLI::write('Cadastro de Fornecedor Iniciado.', 'light_green');

        helper('string');

        // Instancia os Bancos
        $dbPortaJoias = Database::connect('default');
        $dbBackOffice = Database::connect('bl');

        //Inicia as operações de DB
        $dbPortaJoias->transStart();
        try {
            $fornecedores = $dbBackOffice->query("SELECT *, ci.nome AS nome_cidade
                                                    FROM fornecedor f
                                              INNER JOIN pessoa p
                                                      ON (p.codigo_pessoa = f.codigo_pessoa)
                                               LEFT JOIN fornecedor_endereco fe
                                                      ON fe.codigo_fornecedor = f.codigo_fornecedor
                                               LEFT JOIN endereco e
                                                      ON e.codigo_endereco = fe.codigo_endereco
                                               LEFT JOIN cidade ci
                                                      ON ci.codigo_cidade = e.codigo_cidade
                                                ORDER BY f.codigo_fornecedor ASC
                                                ")->getResultArray();


            foreach ($fornecedores as $key => $value) {

                // Apenas os que tem código de fornecedor
                if (!empty($value['codigo_fornecedor'])) {

                    // Monta o Array para o novo Banco
                    $fornecedor = [
                        'usuario_criacao' => 1,
                        'criado_em'       => $value['criado_em'],
                        'alterado_em'     => $value['alterado_em'],
                        'inativado_em'    => $value['excluido_em'],
                        'codigo_empresa'  => $value['codigo_cliente_iluminare'],
                        'nome_fantasia'   => !empty($value['nome_fantasia']) ? ucwords($value['nome_fantasia'], ' ') : $value['nome_completo_razao_social'],
                        'razao_social'    => ucwords("{$value['codigo_fornecedor']}#_{$value['nome_completo_razao_social']}", ' '),
                        'cpf_cnpj'        => onlyNumber($value['cpf_cnpj']),
                        'tipo_pessoa'     => strlen(onlyNumber($value['cpf_cnpj'])) == 11 ? 1 : 2,
                        'data_nascimento' => empty($value['data_nascimento']) || ($value['data_nascimento'] == '0000-00-00') ? null : $value['data_nascimento']
                    ];

                    // Busca o endereço do fornecedor
                    $fornecedorCidade = $dbBackOffice->query("SELECT *
                                                                FROM fornecedor_endereco fe
                                                           LEFT JOIN endereco e
                                                                  ON e.codigo_endereco = fe.codigo_endereco
                                                           LEFT JOIN cidade ci
                                                                  ON ci.codigo_cidade = e.codigo_cidade
                                                               WHERE fe.codigo_fornecedor = {$value['codigo_fornecedor']}
                                                               LIMIT 1;
                                                            ")->getResultArray();

                    // Verifica se tem Endereço
                    if (!empty($fornecedorCidade)) {
                        $fornecedor['endereco'] = json_encode([
                            'cep'         => !empty($fornecedorCidade[0]['cep']) ? onlyNumber($fornecedorCidade[0]['cep']) : "",
                            'rua'         => !empty($fornecedorCidade[0]['logradouro']) ? $fornecedorCidade[0]['logradouro'] : "",
                            'numero'      => !empty($fornecedorCidade[0]['numero']) ? onlyNumber($fornecedorCidade[0]['numero']) : "",
                            'bairro'      => !empty($fornecedorCidade[0]['bairro']) ? $fornecedorCidade[0]['bairro'] : "",
                            'complemento' => !empty($fornecedorCidade[0]['complemento']) ? $fornecedorCidade[0]['complemento'] : "",
                            'cidade'      => !empty($fornecedorCidade[0]['nome']) ? $fornecedorCidade[0]['nome'] : "",
                            'uf'          => !empty($fornecedorCidade[0]['uf']) ? strtoupper($fornecedorCidade[0]['uf']) : ""
                        ]);
                    }

                    // Busca os telefones do fornecedor
                    $fornecedorTelefone = $dbBackOffice->query("SELECT ddd, telefone
                                                                  FROM fornecedor_telefone ft
                                                             LEFT JOIN telefone t
                                                                    ON t.codigo_telefone = ft.codigo_telefone
                                                                 WHERE ft.codigo_fornecedor = {$value['codigo_fornecedor']}
                                                              ")->getResultArray();

                    // Verifica se tem Telefones
                    if (!empty($fornecedorTelefone)) {
                        $fornecedor['celular']  = !empty($fornecedorTelefone[0]) ? onlyNumber($fornecedorTelefone[0]['ddd']) . onlyNumber($fornecedorTelefone[0]['telefone']) : null;
                        $fornecedor['telefone'] = !empty($fornecedorTelefone[1]) ? onlyNumber($fornecedorTelefone[1]['ddd']) . onlyNumber($fornecedorTelefone[1]['telefone']) : null;
                    }

                    //verifica se o registro não existe
                    $numRows = $dbPortaJoias->table('fornecedor')->where($fornecedor)->countAllResults();

                    if ($numRows === 0) {
                        $dbPortaJoias->table('fornecedor')->insert($fornecedor);
                    }
                }
            }

            // Finaliza as operações de DB
            $dbPortaJoias->transComplete();

            CLI::write('Cadastro de Fornecedor Finalizado.', 'green');
        } catch (Exception $e) {
            CLI::error($e->getMessage());
            die;
        }
    }
}
