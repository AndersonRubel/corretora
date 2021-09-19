<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;
use Exception;

class ImportaUsuario extends BaseCommand
{
    protected $group       = 'Importação';
    protected $name        = 'importa:usuario';
    protected $usage       = 'importa:usuario';
    protected $description = 'Importa os Usuários no sistema';
    protected $arguments   = [];

    /**
     * Actually execute a command.
     * @param array $params
     */

    public function run(array $params)
    {
        CLI::write('Cadastro de Usuario Iniciado.', 'light_green');

        // Instancia os Bancos
        $dbPortaJoias = Database::connect('default');
        $dbBackOffice = Database::connect('bl');

        //Inicia as operações de DB
        $dbPortaJoias->transStart();

        try {
            $usuarios = $dbBackOffice->query("SELECT *
                                                FROM usuario u
                                          INNER JOIN pessoa p
                                                  ON (p.codigo_pessoa = u.codigo_pessoa)
                                           LEFT JOIN vendedor v
                                                  ON (v.codigo_pessoa = p.codigo_pessoa)
                                            ORDER BY u.codigo_usuario ASC
                                            ")->getResultArray();

            foreach ($usuarios as $key => $value) {
                // Valida a Data de Nascimento
                $value['data_nascimento'] = $value['data_nascimento'] == '0000-00-00' ? null : $value['data_nascimento'];

                $usuario = [
                    'usuario_criacao'       => 1,
                    'criado_em'             => !empty($value['criado_em']) ? $value['criado_em'] : 'NOW()',
                    'alterado_em'           => $value['alterado_em'],
                    'inativado_em'          => $value['excluido_em'],
                    'codigo_empresa_padrao' => $value['codigo_cliente_iluminare'],
                    'codigo_vendedor'       => !empty($value['codigo_vendedor']) ? $value['codigo_vendedor'] : $value['codigo_cliente_iluminare'],
                    'email'                 => $value['login'],
                    'senha'                 => !empty($value['data_nascimento']) ? password_hash(onlyNumber($value['data_nascimento']), PASSWORD_BCRYPT) : password_hash($value['login'], PASSWORD_BCRYPT),
                    'nome'                  => ucwords("{$value['codigo_usuario']}#_{$value['nome_completo_razao_social']}", ' '),
                ];

                //verifica se o registro não existe
                $numRows = $dbPortaJoias->table('usuario')->where('email', $usuario['email'])->countAllResults();

                if ($numRows === 0) {
                    $dbPortaJoias->table('usuario')->insert($usuario);
                }
            }

            // Finaliza as operações de DB
            $dbPortaJoias->transComplete();

            CLI::write('Cadastro de Usuario Finalizado.', 'green');
        } catch (Exception $e) {
            CLI::error($e->getMessage());
            die;
        }
    }
}
