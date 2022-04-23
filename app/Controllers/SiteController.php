<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\RedirectResponse;
use Exception;

use App\Models\Site\SiteModel;
use App\Models\Imovel\ImagemImovelModel;
use App\Models\Cadastro\CadastroTipoImovelModel;
use App\Models\Cadastro\CadastroCategoriaImovelModel;


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
        $tipoImovelModel = new CadastroTipoImovelModel;
        $categoriaImovelModel = new CadastroCategoriaImovelModel;

        $dados['tipoImovel'] = $tipoImovelModel->get();
        $dados['categoriaImovel'] = $categoriaImovelModel->get();

        $dados['imovel'] = $siteModel->selectImoveisFiltrar(['destaque' => 'true']);
        $dados['cidades'] = $siteModel->selectCidades();
        // dd($dados);

        foreach ($dados['imovel']['itens'] as $key => $value) {
            $value['imagem_destaque'] = base_url('assets/img/sem_imagem.jpg');
            if (!empty($value['diretorio_imagem'])) {
                $value['imagem_destaque'] = $this->getFileImagem($value['diretorio_imagem']);
            }
            $dados['imovel']['itens'][$key] = $value;
        }
        return $this->templateSite('site', ['index'], $dados);
    }

    /**
     * Exibe a Tela de inicial site
     * @return html
     */
    public function detalhes($uuid)
    {
        $siteModel = new SiteModel;
        $imagemImovelModel = new ImagemImovelModel;
        /// buscar apenas imoveis destaques
        $dados = $siteModel->selectImoveis();
        foreach ($dados['itens'] as $key => $value) {
            $value['imagem_destaque'] = base_url('assets/img/sem_imagem.jpg');
            if (!empty($value['diretorio_imagem'])) {
                $value['imagem_destaque'] = $this->getFileImagem($value['diretorio_imagem']);
            }
            $dados['itens'][$key] = $value;
        }

        $dados['imovel'] = $siteModel->selectImoveis($uuid);
        $dados['imovel'] = $dados['imovel'][0];
        $dados['imovel']['imagem_destaque'] = base_url('assets/img/sem_imagem.jpg');
        if (!empty($dados['imovel']['diretorio_imagem'])) {
            $dados['imovel']['imagem_destaque'] = $this->getFileImagem($dados['imovel']['diretorio_imagem']);
        }
        $dados['imagemImovel'] = $imagemImovelModel->get(['codigo_imovel' =>   $dados['imovel']['codigo_imovel']], ['diretorio_imagem']);

        if (!empty($dados['imagemImovel'])) {
            foreach ($dados['imagemImovel'] as $key => $value) {
                $dados['imagemImovel'][$key]['diretorio_imagem'] = $this->getFileImagem($value['diretorio_imagem']);
            }
        }
        // dd($dados);
        return $this->templateSite('site', ['detalhes'], $dados);
    }
    /**
     * Exibe a Tela de inicial site
     * @return html
     */
    public function buscarImoveis()
    {

        $siteModel = new SiteModel;
        $dadosRequest = convertEmptyToNull($this->request->getVar());

        $dados['imovel'] = $siteModel->selectImoveisFiltrar($dadosRequest);
        foreach ($dados['imovel']['itens'] as $key => $value) {
            $value['imagem_destaque'] = base_url('assets/img/sem_imagem.jpg');
            if (!empty($value['diretorio_imagem'])) {
                $value['imagem_destaque'] = $this->getFileImagem($value['diretorio_imagem']);
            }
            $dados['imovel']['itens'][$key] = $value;
        }
        return $this->templateSite('site', ['comprar', 'functions'], $dados);
    }
    /**
     * Exibe a Tela de inicial site
     * @return html
     */
    public function contato()
    {
        return $this->templateSite('site', ['contato']);
    }
    /**
     * Exibe a Tela de inicial site
     * @return html
     */
    public function sobre()
    {
        return $this->templateSite('site', ['sobre']);
    }
}
