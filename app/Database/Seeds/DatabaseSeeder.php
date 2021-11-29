<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Sempre salvar os dados.
     * @param string $table Tabela que os dados serão inseridos.
     * @param array $data Dados a serem inseridos.
     * @return void
     */
    protected function alwaysSave(string $table, array $data): void
    {
        $this->db->table($table)->insert($data);
    }

    /**
     * Se os dados não existirem no banco de dados, serão inseridos.
     * @param string $table Tabela que os dados serão inseridos.
     * @param array $data Dados a serem inseridos.
     * @return void
     */
    protected function saveOnce(string $table, array $data): void
    {
        $numRows = $this->db->table($table)->where($data)->countAllResults();

        if ($numRows === 0) {
            $this->alwaysSave($table, $data);
        }
    }

    /**
     * Executa os seeders.
     *
     * @return void
     */
    public function run()
    {
        $this->call('CadastroEmpresaSeeder');
        $this->call('CadastroGrupoSeeder');
        $this->call('CadastroMenuSeeder');
        $this->call('UsuarioSeeder');
        $this->call('EmpresaUsuarioSeeder');
        $this->call('CadastroCategoriaImovelSeeder');
        $this->call('CadastroTipoImovelSeeder');
        $this->call('PermissaoSeeder');
    }
}

