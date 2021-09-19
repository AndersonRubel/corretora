<?php

namespace App\Validations;

class CustomRules
{
    /**
     * Confere se a data informada é maior que a data atual
     * @param string $str Data informada
     * @param mixed $error
     * @return bool
     */
    function checkDateToday(string $str, string &$error = null): bool
    {
        if ($str < date('Y-m-d')) return false;

        return true;
    }

    /**
     * Confere se o Telefone esta correto
     * @param string $str Telefone informado
     * @param mixed $error
     * @return bool
     */
    function checkTelefone(string $str, string &$error = null): bool
    {
        if (!strlen($str) > 11 && !strlen($str) < 15) return false;

        if (!preg_match('/^\([0-9]{2}\) [0-9]{4,5}-[0-9]{4,5}$/', $str)) return false;

        return true;
    }

    /**
     * Confere se o CEP esta correto
     * @param string $str CEP informado
     * @param mixed $error
     * @return bool
     */
    function checkCep(string $str, string &$error = null): bool
    {
        if (strlen($str) != 9) return false;

        if (!preg_match('/^[0-9]{5}-[0-9]{3}$/', $str)) return false;

        return true;
    }

    /**
     * Confere se o Pedido mínimo é maior que 0
     * @param string $str Valor
     * @param mixed $error
     * @return bool
     */
    function checkPedidoMinimo($value, string &$error = null): bool
    {
        if (!preg_replace('/\D/', '', $value) > 0) return false;

        return true;
    }

    /**
     * Confere se o CPF esta correto
     * @param string $str CPF informado
     * @param mixed $error
     * @return bool
     */
    function checkCpf(string $str, string &$error = null): bool
    {
        if (strlen($str) != 14) return false;

        if (!preg_match('/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/', $str)) return false;

        return true;
    }

    /**
     * Confere se a string é uma UUID válida
     * @param string $str UUID informada
     * @param mixed $error
     * @return bool
     */
    function checkUuid(string $str, string &$error = null): bool
    {

        // 031a8aea-d882-11eb-95c4-a87eead22084
        // [0-9A-F]{8}
        // if (!preg_match('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i', $str)) return false;
        if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $str)) return false;

        return true;
    }

    /**
     * Confere se a string esta dentro das regras de Senha
     * @param string $str UUID informada
     * @param mixed $error
     * @return bool
     */
    function checkPassword(string $str, string &$error = null): bool
    {
        if (!preg_match('/[a-zA-Z]/i', $str)) return false;
        if (!preg_match('/[0-9]/i', $str)) return false;

        return true;
    }

    /**
     * Confere se o valor esta dentro dos dias permitidos
     * @param string $valor Dia desejado
     * @param mixed $error
     * @return bool
     */
    function checkDiasPagamento(int $valor, string &$error = null): bool
    {
        if (in_array($valor, [5, 6, 7, 8, 9, 10])) {
            return true;
        }

        return false;
    }
}
