<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\RedirectResponse;
use Exception;

use App\Models\Site\SiteModel;
use App\Models\Imovel\EnderecoImovelModel;
use App\Models\Imovel\ImagemImovelModel;


class SiteController extends BaseController
{
    //////////////////////////////////
    //                              //
    //      OPERAÇÕES DE BUSCA      //
    //                              //
    //////////////////////////////////

    /**
     * Exibe a Tela de inicial site
     * @return html
     */
    public function index()
    {
        $siteModel = new SiteModel;
        $imagemImovelModel = new ImagemImovelModel;
        $enderecoImovelModel = new EnderecoImovelModel;
        $colunas = [
            "imovel.uuid_imovel"
        ];

        $dados['imovel'] = $siteModel->selectImoveis();
        dd($dados);
        foreach ($dados['imovel'] as $key => $value) {
            $dados['imovel']['endereco'] = $enderecoImovelModel->get(['codigo_imovel' => $value['codigo_imovel']], [], true);
        }


        // $dados['imagemImovel'] = $imagemImovelModel->get(['codigo_imovel' => $dados['imovel']['codigo_imovel']], ['uuid_imagem_imovel', 'diretorio_imagem']);
        return $this->templateSite('site', ['index'], $dados);
    }
}