<?php

if (!function_exists('numericToReal')) {
    /**
     * Converte valor de numerico em valor em reais ( . => ,)
     * @param string|int $valor
     * @param string|int $casasDecimais
     */
    function numericToReal($valor, $casasDecimais = 2)
    {
        return number_format($valor, $casasDecimais, ",", ".");
    }
}

if (!function_exists('realToNumeric')) {
    /**
     * Converte valor de real em valor numerico ( , => .)
     * @param string|int $valor
     */
    function realToNumeric($valor)
    {
        return str_replace(',', '.', str_replace('.', '', $valor));
    }
}

if (!function_exists('realToInt')) {
    /**
     * Converte valor de real em valor inteiro ( , => '')
     * @param string|int $valor
     */
    function realToInt($valor)
    {
        return str_replace(',', '', str_replace('.', '', str_replace('R$', '', $valor)));
    }
}

if (!function_exists('intToReal')) {
    /**
     * Converte valor de real em valor inteiro ( , => .)
     * @param string|int $valor
     * @param int $casasDecimais
     */
    function intToReal($valor, int $casasDecimais = 2)
    {
        if (empty($valor)) $valor = 0;

        $casasDecimaisDivisor = pow(10, $casasDecimais);
        return number_format($valor / $casasDecimaisDivisor, $casasDecimais, ",", ".");
    }
}

if (!function_exists('intToPhone')) {
    /**
     * Converte valor de inteiro em Telefone/Celular com Mascara
     * @param int $valor
     */
    function intToPhone(int $valor)
    {
        $formatedPhone = preg_replace('/[^0-9]/', '', $valor);
        $matches = [];
        preg_match('/^([0-9]{2})([0-9]{4,5})([0-9]{4})$/', $formatedPhone, $matches);

        if ($matches) {
            return "({$matches[1]}) {$matches[2]}-{$matches[3]}";
        }
    }
}

if (!function_exists('intToCpf')) {
    /**
     * Converte valor de inteiro em CPF com Mascara
     * @param int $valor
     */
    function intToCpf($valor)
    {
        return substr($valor, 0, 3) . '.' . substr($valor, 3, 3) . '.' . substr($valor, 6, 3) . '-' . substr($valor, 9, 3);
    }
}

if (!function_exists('intToCnpj')) {
    /**
     * Converte valor de inteiro em CNPJ com Mascara
     * @param int $valor
     */
    function intToCnpj($valor)
    {
        return substr($valor, 0, 2) . '.' . substr($valor, 2, 3) . '.' . substr($valor, 5, 3) . '/' . substr($valor, 8, 4) . '-' . substr($valor, 12, 2);
    }
}

if (!function_exists('intToCep')) {
    /**
     * Converte valor de inteiro em CEP com Mascara
     * @param int $valor
     */
    function intToCep($valor)
    {
        return substr($valor, 0, 5) . '-' . substr($valor, 5, 3);
    }
}

if (!function_exists('intToRg')) {
    /**
     * Converte valor de inteiro em RG com Mascara
     * @param int $valor
     */
    function intToRg($valor)
    {
        return substr($valor, 0, 2) . '.' . substr($valor, 2, 3) . '.' . substr($valor, 5, 3);
    }
}
