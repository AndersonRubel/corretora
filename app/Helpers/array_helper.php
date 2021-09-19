<?php

if (!function_exists('orderByDayWeek')) {

    /**
     * Ordena um array conforme o Dia da Semana
     * @param array $arr Array pra ordenação
     * @return array Array ordenado
     */
    function orderByDayWeek(array $arr): array
    {
        // Ordem Padrão
        $dayMap = ['domingo', 'segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado'];
        uasort($arr, function ($a, $b) use ($dayMap) {
            $c = explode(',', $a['dia_semana']);
            $d = explode(',', $b['dia_semana']);
            for ($i = 0, $len = count($c); $i < $len; $i++) {
                $d1 = @array_search($c[$i], $dayMap);
                $d2 = @array_search($d[$i], $dayMap);
                if ($d1 == $d2) continue;
                return $d1 - $d2;
            }
        });

        return $arr;
    }
}
