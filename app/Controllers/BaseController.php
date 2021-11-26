<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use Config\Database;
use Config\Services;
use Sentry;
use Exception;

use App\Libraries\NativeSession;
use CodeIgniter\HTTP\Response;
use PHPMailer\PHPMailer\PHPMailer;

class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     * @var IncomingRequest|CLIRequest
     */
    protected $request;

    // Recebe a Instancia do Banco de Dados
    protected $db;

    // Recebe a Instancia de Sessão
    protected $nativeSession;

    // Recebe a versão atual do sistema
    public $version = "1.0.0";

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['string', 'numeric'];

    /**
     * Constructor.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param LoggerInterface   $logger
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        //--------------------------------------------------------------------
        // Preload any models, libraries, etc, here.
        //--------------------------------------------------------------------
        // Só lança os erros ao Sentry em Produção
        if (ENVIRONMENT !== "development") {
            // Sentry\init(['dsn' => env("sentryDns")]);
        }

        //Inicia a sessão
        if (empty($this->nativeSession)) {
            $this->nativeSession = new NativeSession();
        }

        // Adiciona a versão do sistema
        header("Versao: {$this->version}");

        // Carregar banco de dados para ser utilizado como $this->db pois dessa
        // maneira é possível usar as transactions dentro das controllers
        $this->db = Database::connect();
    }

    //////////////////////////////////
    //                              //
    //    FUNÇÕES DE VERIFICAÇÃO    //
    //                              //
    //////////////////////////////////

    /**
     * Validar dados das requisições.
     * @param  \CodeIgniter\HTTP\IncomingRequest  $request
     * @param  array $rules - Regras a serem validadas
     * @return array Array com os erros obtidos
     */
    protected function validarRequisicao(IncomingRequest $request, array $rules): array
    {
        $validacoes = Services::validation();
        $validacoes->withRequest($request)->setRules($rules)->run();
        return $validacoes->getErrors();
    }

    /**
     * Verifica se o uuid é uma string e possui tamanho apropriado (36).
     * @param mixed $uuid pode ser uma string de uuid ou um array de uuids.
     */
    protected function verificarUuid($uuid): bool
    {
        if (is_string($uuid)) {
            if (!uuidValidation($uuid)) {
                return false;
            }
        } elseif (is_array($uuid)) {
            foreach ($uuid as $uid) {
                if (!is_string($uid) || !uuidValidation($uid)) {
                    return false;
                }
            }
        } else {
            return false;
        }

        return true;
    }

    //////////////////////////////////
    //                              //
    //       FUNÇÕES BÁSICAS        //
    //                              //
    //////////////////////////////////

    /**
     * Retorna o nome do controlador
     * @return string nome do controlador
     */
    public function getControllerName(): string
    {
        return str_replace('\App\Controllers\\', '', Services::router()->controllerName());
    }

    /**
     * Retorna o nome do método atual
     */
    public function getMethodName(): string
    {
        return Services::router()->methodName();
    }

    /**
     * Retorna o nome da rota atual
     */
    public function getRouterName(): string
    {
        return Services::uri()->getPath();
    }


    /**
     * Função Padrão para envio de emails
     * @param string $destinatario Endereço de email
     * @param string $assunto - Assunto do E-mail
     * @param string $mensagem - Mensagem do E-mail
     * @param array  $anexo - ['buffer','name', 'type']
     */
    protected function enviarEmail($destinatario, $assunto = null, $mensagem = false, $anexos = [])
    {
        if (empty($assunto)) $assunto = env('app.nomeSistema');

        $email = new PHPMailer();
        $email->IsSMTP();
        $email->SMTPDebug = 0; // Debugar: 1 = erros e mensagens, 2 = mensagens apenas
        $email->SMTPAutoTLS = true;
        $email->SMTPAuth = true;
        $email->SMTPSecure = false;
        $email->Host = env('smtp_host');
        $email->Port = env('smtp_port');
        $email->Username = env('smtp_user');
        $email->Password = env('smtp_pass');
        $email->CharSet = 'UTF-8';
        $email->Subject = $assunto;
        $email->Body = $mensagem;
        $email->isHTML(true);

        $email->SetFrom(env('smtp_mail_from'), env('app.nomeSistema') . ' — ' . $assunto);
        $email->AddAddress($destinatario);

        if (!empty($anexos)) {
            foreach ($anexos as $key => $valueAnexo) {
                $email->addAttachment($valueAnexo['arquivo']);
            }
        }

        $enviado = $email->send();

        $email->ClearAllRecipients();

        if ($enviado) {
            return lang('Success.geral.enviaEmail');
        } else {
            return $email->ErrorInfo;
        }
    }

    /**
     * Função Padrão para envio de SMS
     * @param string $celular Celular
     * @param string $mensagem - Mensagem do SMS
     */
    protected function enviarSms($celular, $mensagem = "")
    {
        return true;
    }

    /**
     * Busca a Imagem em Uploads
     * @param string $diretorio Diretorio do arquivo na pasta upload
     * @return base64
     */
    public function getFileImagem($diretorio = null)
    {
        if (!empty($diretorio)) {
            $path = WRITEPATH . 'uploads\\' . env("file_bucket");
            try {
                $imagem  = base64_encode(file_get_contents($path . "\\$diretorio"));
                $ext     = explode(".", $diretorio);
                $ext     = end($ext);
                return "data: image/{$ext};base64,{$imagem}";
            } catch (Exception $e) {
                $arquivo = file_get_contents(base_url('assets/img/logo.png'));
                $imagem  = base64_encode($arquivo);
                return "data: image/png;base64,{$imagem}";
            }
        } else {
            $arquivo = file_get_contents('assets/img/logo.png');
            $imagem  = base64_encode($arquivo);
            return "data: image/png;base64,{$imagem}";
        }
    }

    /**
     * Salva um arquivo em Uploads
     * @param string $diretorio Diretorio do arquivo na pasta upload
     * @return bool
     */
    public function putFileObject($diretorio, $objeto)
    {
        if (empty($diretorio) || empty($objeto)) return [];

        // Inicio :: Trata o Diretorio e PATH

        // Base do diretorio
        $path = WRITEPATH . 'uploads\\' . env("file_bucket");
        $diretorioCompleto = $path . '/' . str_replace('/' . basename($diretorio), '', $diretorio);
        $pathCompleto = "{$path}/{$diretorio}";

        // Cria o diretorio se ainda não existir
        if (!is_dir($diretorioCompleto)) mkdir($diretorioCompleto, 0755, true);

        // Fim :: Trata o Diretorio e PATH

        // Inicio :: Trata o Objeto
        $base64Document = explode('base64,', $objeto);
        $contentType = $base64Document[0];
        $contentType = str_replace('data:', '', $contentType);
        $contentType = str_replace(';', '', $contentType);
        // Fim :: Trata o Objeto

        try {
            file_put_contents($pathCompleto, base64_decode($base64Document[1]));
            return $diretorio;
        } catch (Exception $e) {
            return "";
        }

        return $diretorio;
    }

    /**
     * Carrega o template HTML do sistema
     * @param string $pasta Pasta onde se localiza a view
     * @param array  $arquivos Arquivos que devem ser carregados da pasta
     * @param array  $dados  Informações adicionais para a view
     * @param bool   $navbar Define se vai exibir a navbar
     * @param bool   $sidebar Define se vai exibir a sidebar
     */
    protected function template(string $pasta, array $arquivos = [], array $dados = [], bool $navbar = true, bool $sidebar = true)
    {
        $templateAtivo = env('app.template');

        //Carrega a sessão e a Instancia da Base Controller
        $dados['nativeSession'] = $this->nativeSession;
        $dados['dadosUsuario']  = $this->nativeSession->get("usuario");
        $dados['responseFlash'] = $this->nativeSession->getFlashdata('responseFlash');
        $dados['menus']         = empty($this->nativeSession->get("menus")) ? [] : $this->nativeSession->get("menus");
        $dados['base']          = $this;

        // Adiciona o Header e as funções Base do Documento
        echo view("template/{$templateAtivo}/header", $dados);
        echo view('template/modal', $dados);
        echo view('template/functions', $dados);
        echo view('template/datagrid', $dados);

        // verifica se tem que carregar a Navbar
        if ($navbar) echo view("template/{$templateAtivo}/navbar", $dados);

        // verifica se tem que carregar a Sidebar
        if ($sidebar) echo view("template/{$templateAtivo}/sidebar", $dados);

        // Carrega os arquivos da pasta desejada
        if (!empty($arquivos)) {
            foreach ($arquivos as $valueArquivo) {
                echo view("app/{$pasta}/{$valueArquivo}", $dados);
            }
        }

        // Adiciona a Footer do Documento
        echo view("template/{$templateAtivo}/footer", $dados);
    }
    /**
     * Carrega o template HTML do sistema
     * @param string $pasta Pasta onde se localiza a view
     * @param array  $arquivos Arquivos que devem ser carregados da pasta
     * @param array  $dados  Informações adicionais para a view
     * @param bool   $navbar Define se vai exibir a navbar
     * @param bool   $sidebar Define se vai exibir a sidebar
     */
    protected function templateSite(string $pasta, array $arquivos = [], array $dados = [])
    {
        $templateAtivo = env('app.templateSite');

        //Carrega a sessão e a Instancia da Base Controller
        // $dados['nativeSession'] = $this->nativeSession;
        // $dados['dadosUsuario']  = $this->nativeSession->get("usuario");
        // $dados['responseFlash'] = $this->nativeSession->getFlashdata('responseFlash');
        // $dados['menus']         = empty($this->nativeSession->get("menus")) ? [] : $this->nativeSession->get("menus");
        // $dados['base']          = $this;

        // Adiciona o Header e as funções Base do Documento
        echo view("templateSite/{$templateAtivo}/header", $dados);

        // verifica se tem que carregar a Navbar
        echo view("templateSite/{$templateAtivo}/navbar", $dados);

        // Carrega os arquivos da pasta desejada
        if (!empty($arquivos)) {
            foreach ($arquivos as $valueArquivo) {
                echo view("app/{$pasta}/{$valueArquivo}", $dados);
            }
        }

        // Adiciona a Footer do Documento
        echo view("templateSite/{$templateAtivo}/footer", $dados);
    }

    /**
     * @param string $endPoint URL da Requisição
     * @param string $method Método da Requisição (GET,POST)
     * @param array $bodyRequest Corpo da Requisição
     * @param array $headers Headers da Requisição
     * @param bool $isJson Se o Corpo da Requisição deve ser um JSON
     */
    public function sendCurl(string $endPoint, string $method, array $bodyRequest = [], array $headers = [], bool $isJson = false)
    {
        if ($endPoint == null || $method == null) {
            return false;
        }

        if ($isJson) {
            $bodyRequest = json_encode($bodyRequest);
            array_push($headers, ["Content-Type: application/json"]);
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $endPoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $bodyRequest,
            CURLOPT_HTTPHEADER => $headers == [] ? array("Cache-Control: no-cache") : $headers,
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }

    /**
     * Realiza o retorno da requisição de um datagrid
     * @param array $data Dados que serão retornados
     * @param array $dadosRequest Informações da Requisição
     * @return \CodeIgniter\HTTP\Response
     */
    public function responseDataGrid(array $data, array $dadosRequest): Response
    {
        $dados['data']            = !empty($data['data'])         ? $data['data']           : [];
        $dados['draw']            = !empty($dadosRequest['draw']) ? $dadosRequest['draw']   : 0;
        $dados['recordsTotal']    = !empty($data['count'])        ? $data['count']['total'] : 0;
        $dados['recordsFiltered'] = !empty($data['count'])        ? $data['count']['total'] : 0;

        return $this->response->setJSON($dados);
    }
}