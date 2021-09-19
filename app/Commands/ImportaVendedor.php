<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;
use Exception;

class ImportaVendedor extends BaseCommand
{
    protected $group       = 'Importação';
    protected $name        = 'importa:vendedor';
    protected $usage       = 'importa:vendedor';
    protected $description = 'Importa os Vendedores no sistema';
    protected $arguments   = [];

    /**
     * Actually execute a command.
     * @param array $params
     */
    public function run(array $params)
    {
        CLI::write('Cadastro de Vendedor Iniciado.', 'light_green');

        helper('string');

        // Instancia os Bancos
        $dbPortaJoias = Database::connect('default');
        $dbBackOffice = Database::connect('bl');

        //Inicia as operações de DB
        $dbPortaJoias->transStart();
        try {
            $vendedores = $dbBackOffice->query("SELECT *, ci.nome AS nome_cidade
                                                  FROM vendedor v
                                            INNER JOIN pessoa p
                                                    ON (p.codigo_pessoa = v.codigo_pessoa)
                                             LEFT JOIN vendedor_endereco ve
                                                    ON ve.codigo_vendedor = v.codigo_vendedor
                                             LEFT JOIN endereco e
                                                    ON e.codigo_endereco = ve.codigo_endereco
                                             LEFT JOIN cidade ci
                                                    ON ci.codigo_cidade = e.codigo_cidade
                                              ORDER BY v.codigo_vendedor ASC
                                              ")->getResultArray();


            foreach ($vendedores as $key => $value) {

                // Apenas os que tem código de vendedor
                if (!empty($value['codigo_vendedor'])) {

                    // Monta o Array para o novo Banco
                    $vendedor = [
                        'usuario_criacao' => 1,
                        'criado_em'       => $value['criado_em'],
                        'alterado_em'     => $value['alterado_em'],
                        'inativado_em'    => $value['excluido_em'],
                        'codigo_empresa'  => $value['codigo_cliente_iluminare'],
                        'nome_fantasia'   => !empty($value['nome_fantasia']) ? ucwords($value['nome_fantasia'], ' ') : $value['nome_completo_razao_social'],
                        'razao_social'    => ucwords("{$value['codigo_vendedor']}#_{$value['nome_completo_razao_social']}", ' '),
                        'cpf_cnpj'        => onlyNumber($value['cpf_cnpj']),
                        'tipo_pessoa'     => strlen(onlyNumber($value['cpf_cnpj'])) == 11 ? 1 : 2,
                        'data_nascimento' => $value['data_nascimento'] == '0000-00-00' ? null : $value['data_nascimento']
                    ];

                    // Busca o endereço do vendedor
                    $vendedorCidade = $dbBackOffice->query("SELECT *
                                                              FROM vendedor_endereco ve
                                                         LEFT JOIN endereco e
                                                                ON e.codigo_endereco = ve.codigo_endereco
                                                         LEFT JOIN cidade ci
                                                                ON ci.codigo_cidade = e.codigo_cidade
                                                             WHERE ve.codigo_vendedor = {$value['codigo_vendedor']}
                                                             LIMIT 1;
                                                          ")->getResultArray();

                    // Verifica se tem Endereço
                    if (!empty($vendedorCidade)) {
                        $vendedor['endereco'] = json_encode([
                            'cep'         => !empty($vendedorCidade[0]['cep']) ? onlyNumber($vendedorCidade[0]['cep']) : "",
                            'rua'         => !empty($vendedorCidade[0]['logradouro']) ? $vendedorCidade[0]['logradouro'] : "",
                            'numero'      => !empty($vendedorCidade[0]['numero']) ? onlyNumber($vendedorCidade[0]['numero']) : "",
                            'bairro'      => !empty($vendedorCidade[0]['bairro']) ? $vendedorCidade[0]['bairro'] : "",
                            'complemento' => !empty($vendedorCidade[0]['complemento']) ? $vendedorCidade[0]['complemento'] : "",
                            'cidade'      => !empty($vendedorCidade[0]['nome']) ? $vendedorCidade[0]['nome'] : "",
                            'uf'          => !empty($vendedorCidade[0]['uf']) ? strtoupper($vendedorCidade[0]['uf']) : ""
                        ]);
                    }

                    // Busca os telefones do vendedor
                    $vendedorTelefone = $dbBackOffice->query("SELECT ddd, telefone
                                                                FROM vendedor_telefone vt
                                                           LEFT JOIN telefone t
                                                                  ON t.codigo_telefone = vt.codigo_telefone
                                                               WHERE vt.codigo_vendedor = {$value['codigo_vendedor']}
                                                            ")->getResultArray();

                    // Verifica se tem Telefones
                    if (!empty($vendedorTelefone)) {
                        $vendedor['celular']  = !empty($vendedorTelefone[0]) ? onlyNumber($vendedorTelefone[0]['ddd']) . onlyNumber($vendedorTelefone[0]['telefone']) : null;
                        $vendedor['telefone'] = !empty($vendedorTelefone[1]) ? onlyNumber($vendedorTelefone[1]['ddd']) . onlyNumber($vendedorTelefone[1]['telefone']) : null;
                    }

                    //verifica se o registro não existe
                    $numRows = $dbPortaJoias->table('vendedor')->where($vendedor)->countAllResults();

                    if ($numRows === 0) {
                        $dbPortaJoias->table('vendedor')->insert($vendedor);
                        $codigoVendedor = $dbPortaJoias->insertID('vendedor_codigo_vendedor_seq');

                        // Cria o Estoque do Vendedor
                        $dbPortaJoias->table('estoque')->insert([
                            'codigo_empresa' => $vendedor['codigo_empresa'],
                            'codigo_vendedor' => $codigoVendedor,
                            'nome'           => ucwords($value['nome_completo_razao_social'], ' '),
                            'padrao'         => true
                        ]);
                    }
                }
            }

            // Finaliza as operações de DB
            $dbPortaJoias->transComplete();

            CLI::write('Cadastro de Vendedor Finalizado.', 'green');
        } catch (Exception $e) {
            CLI::error($e->getMessage());
            die;
        }
    }
}
