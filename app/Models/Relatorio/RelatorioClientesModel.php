<?php

namespace App\Models\Relatorio;

use App\Models\BaseModel;

/**
 * Relatorio de Vendas
 * @author Anderson Rubel <andersonrubel@gmail.com.br>
 */
class RelatorioClientesModel extends BaseModel
{

    /**
     * getFiltrosDisponiveis
     *  - Disponibiliza o Atributo do Filtro disponível para esse relatório
     *  - Caso deseje que o preenchimento do filtro na tela seja obrigatorio adicione "_" (underline) antes do nome
     * @return array Array de Dados
     */
    public function getFiltrosDisponiveis(): array
    {
        return [
            "filtro_vendedor",
            "filtro_cliente",
        ];
    }

    /**
     * getInstrucoes - Disponibiliza instruções para esse relatório que serão mostradas dentro de uma modal
     * @return string|html String HTML ou uma VIEW HTML
     */
    public function getInstrucoes()
    {
        return "";
    }

    /**
     * selectRelatorio - Busca os dados do relatório
     * @param array $parametros Parametros passados da controller
     * @return array Array de Dados
     */
    public function selectRelatorio(array $parametros): array
    {
        // Formato do DataGrid
        if (!empty($parametros['parametros']['draw'])) {
            $result = $this->_getListagem($parametros);
            $dados['data']            = $result['data'];
            $dados['draw']            = $parametros['parametros']['draw'];
            $dados['recordsTotal']    = $result['total'];
            $dados['recordsFiltered'] = $result['total'];
            return $dados;
        } else {
            return [
                "listagem" => $this->_getListagem($parametros),
                "sumario"  => $this->_getSumario($parametros),
                "grafico"  => $this->_getGrafico($parametros)
            ];
        }
    }

    /**
     * _getListagem - Busca os dados do DataGrid
     * - Se for uma requisição simples, devolve os metadados apenas
     * - Caso venha o parametro "DRAW" que é especifico do DataTables, executa a QUERY e devolve o resultado
     * @param array $parametros Parametros passados da controller
     * @return array
     */
    private function _getListagem(array $parametros): array
    {
        /////// Inicio :: SQL DO RELATÓRIO ///////
        $builder = $this->builder('cliente c');
        $builder->select("c.*");

        //se tiver filtros usa este padrão
        // if ($parametros['filtros']['mostrar_estorno'] == 'nao') {
        //     $builder->where('v.situacao <>', 3);
        // } elseif ($parametros['filtros']['mostrar_estorno'] == 'apenas') {
        //     $builder->where('v.situacao', 3);
        // }

        /////// Fim :: SQL DO RELATÓRIO ///////

        // Recebe a String da SQL
        $queryCompiled = $builder->getCompiledSelect();

        // Chama a Função que montará o retorno
        return $this->_dataGrid($parametros, $queryCompiled);
    }

    /**
     * _dataGrid - Monta o Retorno para o DataGrid
     * @param array $parametros Parametros passados da controller
     * @param string $queryCompiled String da SQL desejada
     * @return array
     */
    private function _dataGrid(array $parametros, string $queryCompiled): array
    {
        $dados = [];
        // Monta um Objeto para uso no DataGrid
        $configDataGrid = $this->configDataGrid($parametros['parametros']);

        $queryStringSelect = "SELECT * FROM ({$queryCompiled}) AS x WHERE 1 = 1 {$configDataGrid->whereSearch}";
        $queryStringTotal = "SELECT COUNT(1) AS total FROM ({$queryCompiled}) AS x WHERE 1 = 1 {$configDataGrid->whereSearch}";

        if (!empty($configDataGrid->fieldOrder)) {
            $queryStringSelect .= " ORDER BY {$configDataGrid->fieldOrder} {$configDataGrid->orderDir}";
            $queryStringSelect .= " LIMIT {$configDataGrid->limit} OFFSET {$configDataGrid->offset}";
        }

        // Executa as SQLs
        $queryExecuteSelect = $this->query($queryStringSelect);

        // Nome dos Campos da Tabela
        $dados['fields'] = $queryExecuteSelect->getFieldNames();
        // Total de Registros para saber se deve ou nao, chamar o datagrid
        $dados['totalRecords'] = $queryExecuteSelect->getNumRows();

        // Retorno para o formato do DataGrid
        if (!empty($parametros['parametros']['draw'])) {
            $dados['data']  = $queryExecuteSelect->getResultArray();
            $dados['total'] = $this->query($queryStringTotal)->getResultArray()[0]['total'];
        }

        return $dados;
    }

    /**
     * _getSumario - Busca os dados para Montar o Sumário
     * @param array $parametros Parametros passados da controller
     * @return array
     */
    private function _getSumario(array $parametros): array
    {
        return [];
    }

    /**
     * _getGrafico - Busca os dados para Montar o Gráfico
     * @param array $parametros Parametros passados da controller
     * @return array
     */
    private function _getGrafico(array $parametros): array
    {
        return [];
    }
}
