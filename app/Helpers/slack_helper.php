<?php

if (!function_exists('sendMessage')) {

    /**
     * Envia uma mensagem para o Canal desejado no SLACK
     * @param string canal Canal que recebera a mensagem
     * @param string mensagem Mensagem desejada
     * @return bool
     */
    function sendMessage(string $canal, string $mensagem, bool $priorizar = false): bool
    {
        $url = env('app.slackHooks');
        if (empty($url)) return false;

        // Se for para priorizar adicionar o @here, que notifica os usuarios
        $priorizarTexto = $priorizar ? "@here" : "";

        // Canal e Texto
        $texto = "{ 'channel': '#{$canal}','text': '{$mensagem} {$priorizarTexto}'}";

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $texto,
            CURLOPT_HTTPHEADER => ["Content-Type:application/json;charset=utf-8"],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        return (bool) strtolower($response) === "ok" ? true : false;
    }
}
