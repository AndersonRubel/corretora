<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;
use Exception;

class ImportaEmpresaUsuario extends BaseCommand
{
    protected $group       = 'Importação';
    protected $name        = 'importa:empresausuario';
    protected $usage       = 'importa:empresausuario';
    protected $description = 'Importa a relação entre empresa e usuário no sistema';
    protected $arguments   = [];

    /**
     * Actually execute a command.
     * @param array $params
     */
    public function run(array $params)
    {
        CLI::write('Cadastro de Empresa Usuario Iniciado.', 'light_green');

        helper('string');

        // Instancia os Bancos
        $dbPortaJoias = Database::connect('default');
        $dbBackOffice = Database::connect('bl');

        //Inicia as operações de DB
        $dbPortaJoias->transStart();
        try {
            $usuarios = $dbBackOffice->query("SELECT u.*,p.codigo_cliente_iluminare AS codigo_empresa
                                                FROM usuario u
                                           LEFT JOIN pessoa p
                                                  ON p.codigo_pessoa = u.codigo_pessoa
                                               WHERE u.login <> 'iluminareweb@gmail.com'
                                            ")->getResultArray();

            foreach ($usuarios as $key => $value) {

                $usuarioPortaJoias = $dbPortaJoias->query("SELECT u.codigo_usuario
                                                             FROM usuario u
                                                            WHERE SPLIT_PART(u.nome, '#_', 1) = {$value['codigo_usuario']}::text
                                                         ")->getResultArray();

                //Transpõe o Grupo de Acesso para um Grupo novo
                $codigoGrupoNovo = 3;
                switch ($value['codigo_grupo_usuario']) {
                    case 4:
                        $codigoGrupoNovo = 1;
                        break;
                    case 5:
                    case 8:
                        $codigoGrupoNovo = 2;
                        break;
                    case 6:
                        $codigoGrupoNovo = 3;
                        break;
                    case 7:
                        $codigoGrupoNovo = 4;
                        break;
                    default:
                        $codigoGrupoNovo = 3;
                        break;
                }

                $empresausuario = [
                    'usuario_criacao'       => 1,
                    'criado_em'             => $value['criado_em'],
                    'alterado_em'           => $value['alterado_em'],
                    'inativado_em'          => $value['excluido_em'],
                    'codigo_empresa'        => $value['codigo_empresa'],
                    'codigo_usuario'        => $usuarioPortaJoias[0]['codigo_usuario'],
                    'codigo_cadastro_grupo' => $codigoGrupoNovo
                ];

                //verifica se o registro não existe
                $numRows = $dbPortaJoias->table('empresa_usuario')->where($empresausuario)->countAllResults();

                if ($numRows === 0) {
                    $dbPortaJoias->table('empresa_usuario')->insert($empresausuario);
                }
            }
            // Finaliza as operações de DB
            $dbPortaJoias->transComplete();

            CLI::write('Cadastro de Empresa Usuario Finalizado.', 'green');
        } catch (Exception $e) {
            CLI::error($e->getMessage());
            die;
        }
    }
}
