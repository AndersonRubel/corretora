<?php

namespace App\Controllers;

use App\Models\Relatorio\RelatorioModel;
use CodeIgniter\HTTP\Response;

/**
 * Relatorio
 * @author Matheus L Lange <matheus@iluminareweb.com.br>
 *
 *     Obrigatóriamente a Model Especifica do Relatorio (relatorioDinamicoModel) deve conter as seguintes Funções
 *      - getFiltrosDisponiveis | Disponibiliza os filtros disponiveis na Tela
 *      - getInstrucoes         | Disponibiliza Instruções em HTML para o Relatório
 *      - selectRelatorio()     | Reune em uma Chamada só, todas as Chamadas do Relatório
 *      - getDataGrid()         | Busca os dados para a Listagem do DataGrid
 *      - _getListagem()        | Busca os dados para um grid de Informações
 *      - _getSumario()         | Busca os dados para um sumário em tela
 *      - _getGrafico()         | Busca os dados para um Gráfico em tela
 *
 *   INSTRUÇÕES
 *     1º Ao abrir a tela de relatório, é chamada a função _remap que devolve a VIEW e os relatórios permitidos
 *     2º Ao selecionar o relatório, é devolvido os filtros e instruções daquele relatório
 *     3º Ao gerar o relatório, é chamada a função "requestData" que chama a MODEL correspondente, e devolvido um array com os dados
 */
class RelatorioController extends BaseController
{
    // Rotas que não passam pelo _REMAP pois possuem suas próprias funções
    private $_exception = ['requestData', 'backendCall'];

    /**
     * _remap - Realiza o Mapeamento de todas as rotas de relatório
     *  Todas as rotas renderizam a mesma view
     * @return html View de Relatorios
     */
    public function _remap($method = "", $params = null)
    {
        // Verifica as Exceções de Funções
        if (in_array($method, $this->_exception)) return call_user_func_array(array($this, $method), [$this->request->getGet()]);

        // Busca os Relatórios Disponiveis para acesso
        $relatorioModel = new RelatorioModel;

        //substituir o grupo padrão passado pelo que sera savo na seção de usuario
        $dados['relatorios'] = $relatorioModel->getRelatorios(1);

        // Inicializa a Variavel
        $dados['filtros'] = json_encode([]);

        // Carrega a Model Dinamicamente
        if (file_exists(APPPATH . "Models/Relatorio/Relatorio{$method}Model.php")) {

            $relatorioDinamicoModel = model("App\\Models\\Relatorio\\Relatorio{$method}Model");

            $dados['filtros']    = $relatorioDinamicoModel->getFiltrosDisponiveis();
            $dados['instrucoes'] = $relatorioDinamicoModel->getInstrucoes();

            if (!empty($dados['filtros'])) {
                $dados['filtros'] = json_encode($dados['filtros']);
            } else {
                $dados['filtros'] = json_encode([]);
            }
        }
        $dados['method'] = $method;

        return $this->template('relatorio', ['index', 'functions'], $dados);
    }

    /**
     * requestData - Retorna os Dados Solicitados do Relatorio
     * @param mixed $parametros Qualquer tipo de parametro opcional na URL
     * @return json Consulta dos dados
     */
    public function requestData($parametros = null)
    {
        // Inicializa a Variavel
        $dados = [];
        $dadosRequest = $this->request->getVar();

        if (!empty($dadosRequest)) {
            // Realiza o Parse dos Parametros da Request
            parse_str($dadosRequest['dados'], $filtros);

            // Recebe o nome do relatorio
            $method = $dadosRequest['metodo'];

            // Carrega a Model Dinamicamente
            if (file_exists(APPPATH . "Models/Relatorio/Relatorio{$method}Model.php")) {
                $relatorioDinamicoModel = model("App\\Models\\Relatorio\\Relatorio{$method}Model");

                $params = [
                    'parametros' => $parametros,
                    'filtros'    => $filtros,
                    // 'usuario'    => usuario(),
                ];
                $dados = $relatorioDinamicoModel->selectRelatorio($params);
            }
        }

        return $this->response->setJSON($dados);
    }

    /**
     * Recebe as requisições assincronas para a controller
     * @param string $function Nome da função desejada na model
     * @return \CodeIgniter\HTTP\Response
     */
    public function backendCall(array $parametros): Response
    {
        $request = explode('/', $this->request->uri->getPath());
        $function = $request[2];
        $relatorioModel = new RelatorioModel();

        // $parametros['usuario'] = usuario();
        return $this->response->setJSON($relatorioModel->$function($parametros));
    }
}
