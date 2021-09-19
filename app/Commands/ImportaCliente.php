<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;
use Exception;

class ImportaCliente extends BaseCommand
{
    protected $group       = 'Importação';
    protected $name        = 'importa:cliente';
    protected $usage       = 'importa:cliente';
    protected $description = 'Importa os clientes no sistema';
    protected $arguments   = [];

    /**
     * Actually execute a command.
     * @param array $params
     */
    public function run(array $params)
    {
        CLI::write('Cadastro de Cliente Iniciado.', 'light_green');

        helper('string');

        // Instancia os Bancos
        $dbPortaJoias = Database::connect('default');
        $dbBackOffice = Database::connect('bl');

        //Inicia as operações de DB
        $dbPortaJoias->transStart();
        try {
            $clientes = $dbBackOffice->query("SELECT c.*,vc.codigo_vendedor,p.*
                                                , ci.nome AS nome_cidade
                                                  FROM cliente c
												  LEFT JOIN vendedor_cliente vc
                                                    ON (vc.codigo_cliente = c.codigo_cliente)
                                                  LEFT JOIN pessoa p
                                                    ON (p.codigo_pessoa = c.codigo_pessoa)
                                                  LEFT JOIN cliente_endereco fe
                                                    ON fe.codigo_cliente = c.codigo_cliente
                                                  LEFT JOIN endereco e
                                                    ON e.codigo_endereco = fe.codigo_endereco
                                                  LEFT JOIN cidade ci
                                                    ON ci.codigo_cidade = e.codigo_cidade
                                            ")->getResultArray();




            foreach ($clientes as $key => $value) {
                // Apenas os que tem código de cliente

                if (!empty($value['codigo_cliente'])) {
                    $vendedor = [];
                    if (!empty($value['codigo_vendedor'])) {
                        $vendedor =  $dbPortaJoias->query("SELECT codigo_vendedor
                                                             FROM vendedor
                                                            WHERE SPLIT_PART(razao_social, '#_', 1) = {$value['codigo_vendedor']}::text
                                                         ")->getResultArray();
                    }
                    // Monta o Array para o novo Banco
                    $cliente = [
                        'usuario_criacao' => 1,
                        'criado_em'       => $value['criado_em'],
                        'alterado_em'     => $value['alterado_em'],
                        'inativado_em'    => $value['excluido_em'],
                        'codigo_vendedor' => !empty($vendedor) ? $vendedor[0]['codigo_vendedor'] : $value['codigo_cliente_iluminare'],
                        'codigo_empresa'  =>  $value['codigo_cliente_iluminare'],
                        'nome_fantasia'   => ucwords($value['nome_fantasia'], ' '),
                        'razao_social'    => ucwords("{$value['codigo_cliente']}#_{$value['nome_completo_razao_social']}", ' '),
                        'cpf_cnpj'        => onlyNumber($value['cpf_cnpj']),
                        'tipo_pessoa'     => strlen(onlyNumber($value['cpf_cnpj'])) == 11 ? 1 : 2,
                        'data_nascimento' => empty($value['data_nascimento']) || ($value['data_nascimento'] == '0000-00-00') ? null : $value['data_nascimento']
                    ];

                    // Busca os telefones do cliente
                    $clienteTelefone = $dbBackOffice->query("SELECT ddd, telefone
                                                               FROM cliente_telefone ft
                                                          LEFT JOIN telefone t
                                                                 ON t.codigo_telefone = ft.codigo_telefone
                                                              WHERE ft.codigo_cliente = {$value['codigo_cliente']}
                                                           ")->getResultArray();

                    // Verifica se tem Telefones
                    if (!empty($clienteTelefone)) {
                        $cliente['celular']  = !empty($clienteTelefone[0]) ? onlyNumber($clienteTelefone[0]['ddd']) . onlyNumber($clienteTelefone[0]['telefone']) : null;
                        $cliente['telefone'] = !empty($clienteTelefone[1]) ? onlyNumber($clienteTelefone[1]['ddd']) . onlyNumber($clienteTelefone[1]['telefone']) : null;
                    }

                    //verifica se o registro não existe
                    $numRows = $dbPortaJoias->table('cliente')->where($cliente)->countAllResults();

                    if ($numRows === 0) {
                        $inserido = $dbPortaJoias->table('cliente')->insert($cliente);
                        if ($inserido) {

                            // Busca o endereço do cliente
                            $clienteCidades = $dbBackOffice->query("SELECT *
                                                                      FROM cliente_endereco fe
                                                                 LEFT JOIN endereco e
                                                                        ON e.codigo_endereco = fe.codigo_endereco
                                                                 LEFT JOIN cidade ci
                                                                        ON ci.codigo_cidade = e.codigo_cidade
                                                                     WHERE fe.codigo_cliente = {$value['codigo_cliente']};
                                                                 ")->getResultArray();

                            // Verifica se tem Endereço
                            if (!empty($clienteCidades)) {
                                foreach ($clienteCidades as $key => $clienteCidade) {

                                    $id =  $dbPortaJoias->insertID("cliente_codigo_cliente_seq");
                                    $clienteEndereco = [
                                        'codigo_cliente' => $id,
                                        'codigo_empresa' =>  $value['codigo_cliente_iluminare'],
                                        'cep'            => !empty($clienteCidade['cep']) ? onlyNumber($clienteCidade['cep']) : null,
                                        'rua'            => !empty($clienteCidade['logradouro']) ? $clienteCidade['logradouro'] : null,
                                        'numero'         => !empty($clienteCidade['numero']) ? onlyNumber($clienteCidade['numero']) : null,
                                        'bairro'         => !empty($clienteCidade['bairro']) ? $clienteCidade['bairro'] : null,
                                        'complemento'    => !empty($clienteCidade['complemento']) ? $clienteCidade['complemento'] : null,
                                        'cidade'         => !empty($clienteCidade['nome']) ? $clienteCidade['nome'] : null,
                                        'uf'             => !empty($clienteCidade['uf']) ? strtoupper($clienteCidade['uf']) : null
                                    ];

                                    //verifica se não existe
                                    $numRows = $dbPortaJoias->table('cliente_endereco')->where($clienteEndereco)->countAllResults();

                                    if ($numRows === 0) {
                                        $dbPortaJoias->table('cliente_endereco')->insert($clienteEndereco);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            // Finaliza as operações de DB
            $dbPortaJoias->transComplete();

            CLI::write('Cadastro de Cliente Finalizado.', 'green');
        } catch (Exception $e) {
            CLI::error($e->getMessage());
            die;
        }
    }
}
