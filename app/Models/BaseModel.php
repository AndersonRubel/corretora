<?php

namespace App\Models;

use CodeIgniter\Model;
use stdClass;

class BaseModel extends Model
{

    //////////////////////////////////
    //                              //
    //       FUNÇÕES BÁSICAS        //
    //                              //
    //////////////////////////////////

    /**
     * Busca as informações do modelo
     * @param array  $condicoes Condições do where
     * @param array  $colunas Nomes das colunas ex: ["id","nome","data_adicionado"]
     * @param bool   $first Traz apenas o primeiro registro
     * @param array  $orderBy Realiza a ordenação do resultado
     * @param int    $pagina: [0] = Valor do Limit; [1] Valor do Offset;
     * @param bool   $onlyDeleted Traz os registros deletados tambem
     * @return array
     */
    public function get($condicoes = [], $colunas = [], $first = false, $orderBy = [], $pagina = false, $onlyDeleted = false)
    {
        $colunas = !empty($colunas) ? implode(",", $colunas) : "*";
        $this->select($colunas, FALSE);

        if ($onlyDeleted) {
            $this->where($condicoes)->onlyDeleted();
        } else {
            $this->where($condicoes);
        }

        if (!empty($orderBy)) {
            foreach ($orderBy as $key => $value) {
                $this->orderBy($key, $value);
            }
        }

        if (!empty($pagina)) {
            $this->limit($pagina[0]);
            $this->offset($pagina[1]);
        }

        return $first ? $this->first() : $this->find();
    }

    /**
     * Formata os dados da paginação.
     * @param \CodeIgniter\Model $model Model para fazer a paginação.
     * @param int $currentPage Página atual da paginação.
     * @return array
     */
    public function pagination(Model $model, int $currentPage, $perPage = 15): array
    {
        $data = $model->paginate($perPage, 'default', $currentPage);
        $total = $model->pager->getTotal();

        $from = (($currentPage - 1) * $model->perPage) + 1;

        if ($from > $total) $from = $total;

        $to = $currentPage * $model->perPage;

        if ($to > $total) $to = $total;

        return [
            'current_page'   => $currentPage,
            'first_page_url' => $model->pager->getPageURI($model->pager->getFirstPage()),
            'from'           => $from,
            'last_page'      => $model->pager->getLastPage(),
            'last_page_url'  => $model->pager->getPageURI($model->pager->getLastPage()),
            'next_page_url'  => $model->pager->getNextPageURI(),
            'per_page'       => $model->perPage,
            'prev_page_url'  => $model->pager->getPreviousPageURI(),
            'to'             => $to,
            'total'          => $total,
            'data'           => $data
        ];
    }

    /**
     * Função para dar soft delete no registro atualizando todos os campos que forem necessários.
     * @param int|string id ou uuid do registro
     * @param int codigoUsuario Código do usuário que deletou o registro
     * @param bool byUuid Verifica se a chave primaria deve ser ID ou UUID
     */
    public function customSoftDelete(int|string $id, bool $byUuid = false)
    {
        if ($byUuid) {
            return $this->where($this->uuidColumn, $id)->set(['inativado_em' => 'NOW()'])->update();
        } else {
            return $this->update($id, ['inativado_em' => 'NOW()']);
        }
    }

    /**
     * Retorna as Configurações do DataGrid
     * @param array $dadosGet Informações do DataTables
     * @return object
     */
    public function configDataGrid($dadosGet)
    {
        $config = new stdClass();
        $config->whereSearch = $this->_searchFieldDataGridWhere($dadosGet);
        $config->limit       = isset($dadosGet['length']) ? $dadosGet['length'] : 1;
        $config->offset      = isset($dadosGet['start'])  ? $dadosGet['start'] : 0;
        $config->fieldOrder  = isset($dadosGet['order'])  ? $dadosGet['columns'][$dadosGet['order'][0]['column']]['data'] : [];
        $config->orderDir    = isset($dadosGet['order'])  ? $dadosGet['order'][0]['dir'] : 'ASC';
        $config->filtros     = isset($dadosGet['custom_data']) ? $dadosGet['custom_data'] : [];
        $config->status      = isset($dadosGet['status']) ? $dadosGet['status'] : 1;

        // Se o Status for 1 (true), inverte o valor para trazer APENAS os Ativos,
        // Caso contrário (0) traz os deletados também
        if ($config->status == 1) {
            $config->status = 0;
        } else {
            $config->status = 1;
        }
        return $config;
    }

    /**
     * Retorna a Condição Where para filtrar no campo de busca do DataTables
     * @param array $dadosGet Informações do DataTables
     * @return string
     */
    private function _searchFieldDataGridWhere($dadosGet): string
    {
        $whereSearch = '';
        $whereSearch2 = '';
        if (!empty($dadosGet)) {
            if (!empty($dadosGet['search']['value'])) {
                $arrayBusca = explode(" ", str_replace("'", "", $dadosGet['search']['value']));
                if (!empty($arrayBusca)) {
                    // Percorre as Colunas do DataTables Criando uma Condição WHERE
                    foreach ($dadosGet['columns'] as $key => $value) {
                        if (is_array($value['data'])) {
                            if (!empty($value['data']['filtro'])) {
                                foreach ($value['data']['filtro'] as $key => $valueFiltro) {
                                    $whereSearch .= "{$valueFiltro}::varchar ILIKE '%::VALORPESQUISA::%' OR ";
                                }
                            }
                        } else {
                            $whereSearch .= "{$value['data']}::varchar ILIKE '%::VALORPESQUISA::%' OR ";
                        }
                    }
                    $whereSearch = substr($whereSearch, 0, -3); // Remove o ultimo ' OR'
                    foreach ($arrayBusca as $key => $value) {
                        $whereSearch2 = "AND (" . str_replace('::VALORPESQUISA::', $value, $whereSearch) . ")";
                    }
                }
            }
        }
        return $whereSearch2;
    }
}
