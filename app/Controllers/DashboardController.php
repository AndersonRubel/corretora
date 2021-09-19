<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    /**
     * Exibe a Tela de Dashboard
     * @return html
     */
    public function index()
    {
        $menus = $this->nativeSession->get('menus');

        // Filtra apenas os principais
        $novosMenus = array_filter($menus, function ($menu) {
            return empty($menu['localizacao']);
        });

        // Percorre os menus adicionando os submenus
        foreach ($novosMenus as $key => $value) {
            $novosMenus[$key]['submenus'][] = array_filter($menus, function ($menu) use ($value) {
                return !empty($menu['localizacao']) && $menu['agrupamento'] == $value['agrupamento'];
            });
        }

        $dados['menusDashboard'] = $novosMenus;
        return $this->template('dashboard', ['index'], $dados);
    }
}
