<?php

namespace App\Database\Seeds;

class CadastroMovimentacaoTipoSeeder extends DatabaseSeeder
{
    /**
     * Executa o seeder.
     * @return void
     */
    public function run()
    {
        $this->saveOnce('cadastro_movimentacao_tipo', [
            'nome' => 'Entrada de Estoque',
        ]);

        $this->saveOnce('cadastro_movimentacao_tipo', [
            'nome' => 'Baixa de Estoque',
        ]);

        $this->saveOnce('cadastro_movimentacao_tipo', [
            'nome' => 'Transferência para vendedor',
        ]);

        $this->saveOnce('cadastro_movimentacao_tipo', [
            'nome' => 'Entrada de Estoque por Devolução',
        ]);

        $this->saveOnce('cadastro_movimentacao_tipo', [
            'nome' => 'Baixa de Estoque por Devolução para o fornecedor',
        ]);

        $this->saveOnce('cadastro_movimentacao_tipo', [
            'nome' => 'Entrada de Estoque por Estorno da Venda',
        ]);

        $this->saveOnce('cadastro_movimentacao_tipo', [
            'nome' => 'Atualização de Quantidade',
        ]);

        $this->saveOnce('cadastro_movimentacao_tipo', [
            'nome' => 'Atualização de Quantidade (balanço de estoque)',
        ]);

        $this->saveOnce('cadastro_movimentacao_tipo', [
            'nome' => 'Entrada de Estoque por Nota Fiscal',
        ]);

        $this->saveOnce('cadastro_movimentacao_tipo', [
            'nome' => 'Baixa de Estoque por Exclusão de Nota Fiscal',
        ]);
    }
}
