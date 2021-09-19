<?php

if (!function_exists('getArquivoExtensao')) {

    /**
     * Devolve a Extensão de um arquivo
     * @return string extensão do arquivo
     */
    function getArquivoExtensao(string $nomeOriginal): string
    {
        $extensao = explode(".", $nomeOriginal);
        $tamanho = count($extensao);
        return empty($extensao[$tamanho - 1]) ? '' : $extensao[$tamanho - 1];
    }
}

if (!function_exists('encryptFileName')) {

    /**
     * encryptFileName - Criptografa o nome de um arquivo
     * @param string $filename Nome do arquivo
     * @return string
     */
    function encryptFileName($filename = null): string
    {
        $date = DateTime::createFromFormat('U.u', microtime(TRUE));
        if ($filename !== null) {
            $explodename = explode('.', $filename);
            $endName = "." . end($explodename);
            $fileName = basename($filename, '.' . $endName);

            if (preg_match('/\./', $filename)) {
                return sha1("{$date->format('Y-m-d H:i:s.u')}{$fileName}") . $endName;
            } else {
                return sha1("{$date->format('Y-m-d H:i:s.u')}{$fileName}");
            }
        } else {
            return sha1("{$date->format('Y-m-d H:i:s.u')}");
        }
    }
}

if (!function_exists('verificaDocumento')) {

    /**
     * Função para validar se o MIME do documento é permitido.
     * @param string documento encodificado em base64
     * @param bool $isImagem true se for imagem, false se for documentos
     * @return array array vazio se não passar na verificação e array com as propriedades do documento/imagem caso passe pela verificação
     */
    function verificaDocumento(string $documento, bool $isImagem = false): array
    {
        $allowedImageMimes = ['image/jpg', 'image/jpeg', 'image/png'];
        $allowedMimes = [
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-excel.sheet.macroEnabled.12',
            'application/vnd.ms-powerpoint',
            'application/vnd.ms-excel',
            'application/pdf',
            'application/msword',
        ];

        $documentoArray = explode(',', $documento);

        if (count($documentoArray) < 2) return [];

        $documento = $documentoArray[1];

        $temp = tmpfile();

        fwrite($temp, base64_decode($documento));
        fseek($temp, 0);

        $mime = mime_content_type($temp);
        $fileStats = fstat($temp);

        fclose($temp);

        if (!$isImagem) {
            if (!in_array($mime, $allowedMimes)) return [];
        } else {
            if (!in_array($mime, $allowedImageMimes)) return [];
        }

        if (empty($fileStats)) return [];

        return $fileStats;
    }
}
