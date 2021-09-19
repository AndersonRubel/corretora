<?php

if (!function_exists('snakeCase')) {

    /**
     * Converter texto para snake case.
     * @param string $text Texto a ser convertido.
     * @param bool $lower Converter texto para minúsculo.
     * @return string
     */
    function snakeCase(string $text, bool $lower = true): string
    {
        helper('text');

        $allowCharacters = 'abcdefghijklmnopqrstuvwxyz';
        $allowCharacters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $allowCharacters .= '0123456789_';

        $ascii = convert_accented_characters($text);

        if ($lower) {
            $ascii = strtolower($ascii);
        }

        $snakeText = '';

        for ($i = 0; $i < strlen($ascii); $i++) {
            if (strstr($allowCharacters, $ascii[$i]) !== false) {
                $snakeText .= $ascii[$i];
            } else {
                $snakeText .= '_';
            }
        }

        return $snakeText;
    }
}

if (!function_exists('uuidRegex')) {

    /**
     * Retorna a regex do UUID.
     * @param bool $only
     */
    function uuidRegex(bool $only = true): string
    {
        $regex = '[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}';

        return (($only) ? '/^' . $regex . '$/i' : '/' . $regex . '/i');
    }
}

if (!function_exists('uuidValidation')) {

    /**
     * Validar UUID.
     * @param string $uuid UUID para validação.
     * @return bool
     */
    function uuidValidation(string $uuid): bool
    {
        return ((preg_match(uuidRegex(), $uuid)) ? true : false);
    }
}

if (!function_exists('arrayToString')) {

    /**
     * Função para concatenar todas as strings de um array
     * Deve-se fornecer um array no seguinte formato:
     * ['coluna1', 'coluna2', 'coluna3'...] e irá retornar uma string no formato
     * 'coluna1, coluna2, coluna3'
     * @param array $inputs array de colunas a ser convertido em string
     * @return string
     */
    function arrayToString(array $inputs): string
    {
        if (!is_array($inputs) || empty($inputs)) {
            return '';
        }

        $fixedString = '';

        foreach ($inputs as $key => $input) {
            if (!is_string($input)) {
                return '';
            }

            $fixedString .= $input;

            if ($key < count($inputs) - 1) {
                $fixedString .= ',';
            }
        }

        return $fixedString;
    }
}

if (!function_exists('convertEmptyToNull')) {

    /**
     * Converte os campos que são vazio ("") para NULL
     * @param array $arr Array de entrada
     * @return array
     */
    function convertEmptyToNull(array $arr): array
    {
        if (!empty($arr)) {
            foreach ((array) $arr as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $keyTwo => $valueTwo) {
                        if ($valueTwo == "") {
                            $valueTwo = null;
                        }
                    }
                } else {
                    if ($value == "") {
                        $arr[$key] = null;
                    }
                }
            }
        }
        return (array) $arr;
    }
}

if (!function_exists('ufToNomeEstado')) {

    /**
     * A partir da UF retorna o Nome do Estado
     * @param string $uf UF do Estado
     * @return string Nome do Estado
     */
    function ufToNomeEstado(string $uf)
    {
        $estados = [
            'AC' => 'Acre',
            'AL' => 'Alagoas',
            'AP' => 'Amapá',
            'AM' => 'Amazonas',
            'BA' => 'Bahia',
            'CE' => 'Ceará',
            'DF' => 'Distrito Federal',
            'ES' => 'Espirito Santo',
            'GO' => 'Goiás',
            'MA' => 'Maranhão',
            'MS' => 'Mato Grosso do Sul',
            'MT' => 'Mato Grosso',
            'MG' => 'Minas Gerais',
            'PA' => 'Pará',
            'PB' => 'Paraíba',
            'PR' => 'Paraná',
            'PE' => 'Pernambuco',
            'PI' => 'Piauí',
            'RJ' => 'Rio de Janeiro',
            'RN' => 'Rio Grande do Norte',
            'RS' => 'Rio Grande do Sul',
            'RO' => 'Rondônia',
            'RR' => 'Roraima',
            'SC' => 'Santa Catarina',
            'SP' => 'São Paulo',
            'SE' => 'Sergipe',
            'TO' => 'Tocantins',
        ];

        if (!empty($uf)) {
            $uf = strtoupper($uf);
            echo (string) $estados[$uf];
            return;
        }

        echo (string) $uf;
        return;
    }
}

if (!function_exists('formataErros')) {

    /**
     * Formata os arrays de Erros do Validate para Strings
     * @param array $arr Array de entrada
     * @return string
     */
    function formataErros(array $arr)
    {
        return implode('<br> ', array_values($arr));
    }
}

if (!function_exists('onlyNumber')) {

    /**
     * Converte a string em apenas números
     * @param array $str String de entrada
     * @return string
     */
    function onlyNumber($str)
    {
        $res = preg_replace('/\D/', '', $str);
        return !empty($res) ? $res : null;
        // return preg_replace('/\D/', '', $str);
    }
}

if (!function_exists('formatTime')) {

    /**
     * Adiciona ':' na Hora que é um Inteiro
     * @param array $str String de entrada
     * @return string
     */
    function formatTime(string $str)
    {
        return substr_replace($str, ':', 2, 0);
    }
}

if (!function_exists('removeAccents')) {

    /**
     * Remove os acentos da string
     * @param string $str String de entrada
     * @return string
     */
    function removeAccents(string $str)
    {
        $search = ['À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ', 'Ά', 'ά', 'Έ', 'έ', 'Ό', 'ό', 'Ώ', 'ώ', 'Ί', 'ί', 'ϊ', 'ΐ', 'Ύ', 'ύ', 'ϋ', 'ΰ', 'Ή', 'ή'];
        $replace = ['A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o', 'Α', 'α', 'Ε', 'ε', 'Ο', 'ο', 'Ω', 'ω', 'Ι', 'ι', 'ι', 'ι', 'Υ', 'υ', 'υ', 'υ', 'Η', 'η'];
        return str_replace($search, $replace, $str);
    }
}

if (!function_exists('valorExtenso')) {

    /**
     * Remove os acentos da string
     * @param string $str String de entrada
     * @return string
     */
    function valorExtenso($valor = 0, $tipo = 0, $caixa = "alta")
    {
        ini_set('default_charset', 'UTF-8');

        $valor          = strval($valor);
        $valor          = str_replace(".", " ", $valor);
        $valor          = str_replace(",", ".", $valor);

        if ($tipo == 1) {
            $singular   = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural     = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
        } else {
            $pos        = strpos($valor, ".");
            $valor      = substr($valor, 0, $pos);
            $singular   = array("", "", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural     = array("", "", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
        }

        $c              = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d              = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
        $d10            = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove");
        $u              = array("", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");
        $z              = 0;
        $valor          = number_format($valor, 2, ".", ".");
        $inteiro        = explode(".", $valor);
        for ($i = 0; $i < count($inteiro); $i++)
            for ($ii = strlen($inteiro[$i]); $ii < 3; $ii++)
                $inteiro[$i] = "0" . $inteiro[$i];

        $fim            = count($inteiro) - ($inteiro[count($inteiro) - 1] > 0 ? 1 : 2);
        $rt             = null;
        for ($i = 0; $i < count($inteiro); $i++) {
            $valor      = $inteiro[$i];
            $rc         = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
            $rd         = ($valor[1] < 2) ? "" : $d[$valor[1]];
            $ru         = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";
            $r          = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
            $t          = count($inteiro) - 1 - $i;
            $r          .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";

            if ($valor == "000")
                $z++;
            elseif ($z > 0)
                $z--;
            if (($t == 1) && ($z > 0) && ($inteiro[0] > 0))
                $r .= (($z > 1) ? " de " : "") . $plural[$t];
            if ($r)
                $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? (($i < $fim) ? ", " : " e ") : " ") . $r;
        }

        if ($caixa == "alta") {
            $rt         = mb_strtoupper($rt);
        }

        return $rt;
    }
}
