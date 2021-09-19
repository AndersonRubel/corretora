<?php

namespace App\Libraries;

use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\Response;
use Config\Database;
use Config\Services;
use App\Libraries\NativeSession;

class CrudDinamico
{

    public function __construct()
    {
        $this->request = Services::request();
        $this->db = Database::connect();
    }

    //////////////////////////////////////
    //                                  //
    //         OPERAÇÕES DE GET         //
    //                                  //
    //////////////////////////////////////

    /**
     * Verifica se existe o registro já
     * @param string $table Nome da tabela
     * @param string $field Nome do campo da tabela
     * @param string $value Valor a ser procura no campo da tabela
     * @return bool
     */
    public function verifyField(string $table, string $field, $value)
    {
        // Valida se a Tabela contem alias
        if (strpos($table, '%20') === true) {
            $table = explode('%20', $table)[0];
        }

        // Verifica se o Campo existe na tabela
        if (!$this->db->fieldExists($field, $table)) return false;

        // Realiza a busca
        $builder = $this->db->table($table);
        $builder->where("UPPER({$field}::VARCHAR)", strtoupper($value));
        return $builder->countAllResults($table) > 0;
    }

    /**
     *
     */
    public function getDataSelect2(
        $table,
        $format,
        $formatFields,
        $primaryKey = null,
        $primaryKeyValue = null,
        $select = null,
        $join = null,
        $search = null,
        $orderBy = null,
        $whereConditions = null
    ) {

        $dadosEmpresa = (new NativeSession(true))->get('empresa');
        $builder = $this->db->table($table);

        if ($select != null) {
            if (is_array($select)) {
                $select_str = '';
                foreach ($select as $key => $value) {
                    if ($select_str == '') {
                        $select_str = $key . ' AS ' . $value;
                    } else {
                        $select_str = $select_str . ', ' . $key . ' AS ' . $value;
                    }
                }
                $builder->select($select_str, FALSE);
            }
        }

        if ($join != null) {
            if (is_array($join)) {
                foreach ($join as $key => $value) {
                    $builder->join($value['table'], $value['column'], $value['side']);
                }
            }
        }

        if ($primaryKey != null && $primaryKeyValue != null) {
            $builder->where($primaryKey, $primaryKeyValue);
        }

        if ($search != null && is_array($search) && !empty($search['value'])) {
            $array_search = explode(" ", $search['value']);
            foreach ($array_search as $key => $value) {
                $search_str = '(';
                foreach ($search['fields'] as $field) {
                    if ($search_str == '(') {
                        $search_str .= "{$field}::VARCHAR ILIKE '%{$value}%'";
                    } else {
                        $search_str .= " OR {$field}::VARCHAR ILIKE '%{$value}%'";
                    }
                }
                $search_str .= ')';
                $builder->where($search_str);
            }
        }

        if ($whereConditions != null && is_array($whereConditions)) {
            foreach ($whereConditions as $whereRaw) {
                $builder->where($whereRaw);
            }
        }

        // Filtro de Empresa
        if ($this->db->fieldExists('codigo_empresa', $table)) {
            $builder->where("codigo_empresa", $dadosEmpresa['codigo_empresa']);
        }

        if ($orderBy != null && is_array($orderBy)) {
            $builder->orderBy($orderBy['field'], strtolower($orderBy['method']) == 'asc' ? 'asc' : 'desc');
        }

        $data = array();
        $rows = $builder->get()->getResultArray();

        foreach ($rows as $row) {
            $columns = array();
            foreach ($formatFields as $value) {
                $columns[] = $row[$value];
            }

            $data[] = array(
                'id'    => $row['id'],
                'text'  => vsprintf($format, $columns)
            );
        }

        return $data;
    }


    /**
     *
     */
    public function getData(
        $selectFields,
        $table,
        $primaryKey = null,
        $primaryKeyValue = null,
        $select = null,
        $join = null,
        $search = null,
        $orderBy = null,
        $limit = null,
        $offset = null,
        $whereConditions = null
    ) {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');

        $builder = $this->db->table($table);

        if ($select != null && $selectFields) {
            if (is_array($select)) {
                $select_str = '';
                foreach ($select as $key => $value) {
                    if ($select_str == '') {
                        $select_str = $key;
                    } else {
                        $select_str = $select_str . ', ' . $key;
                    }
                }
                $builder->select($select_str, FALSE);
            }
        }

        if (!$selectFields) {
            $builder->select("COUNT(*) AS count", FALSE);
        }

        if ($join != null) {
            if (is_array($join)) {
                foreach ($join as $key => $value) {
                    $builder->join($value['table'], $value['column'], $value['side']);
                }
            }
        }

        if ($primaryKey != null && $primaryKeyValue != null) {
            $builder->where($primaryKey, $primaryKeyValue);
        }

        $whereSearch = '';
        $whereSearch2 = '';
        if ($search != null && is_array($search) && !empty($search['value'])) {
            $arrayBusca = explode(" ", $search['value']);
            if (!empty($arrayBusca)) {
                // Percorre as Colunas do DataTables Criando uma Condição WHERE
                foreach ($select as $key => $value) {
                    $field = explode('as', strtolower($key)); // Remove o Alias do Campo
                    $field = reset($field);
                    $whereSearch .= "{$field}::varchar ILIKE '%::VALORPESQUISA::%' OR ";
                }
                $whereSearch = substr($whereSearch, 0, -3); // Remove o ultimo ' OR'
                foreach ($arrayBusca as $key => $value) {
                    $whereSearch2 = "AND (" . str_replace('::VALORPESQUISA::', $value, $whereSearch) . ")";
                }
                $whereSearch2 = substr($whereSearch2, 3, -1) . ')'; // Remove o primeiro 'AND '
                $builder->where($whereSearch2);
            }
        }

        if ($whereConditions != null && is_array($whereConditions)) {
            foreach ($whereConditions as $whereRaw) {
                $builder->where($whereRaw);
            }
        }

        // Filtro de Empresa
        if ($this->db->fieldExists('codigo_empresa', $table)) {
            $builder->where("codigo_empresa", $dadosEmpresa['codigo_empresa']);
        }

        if ($orderBy != null && is_array($orderBy) && $selectFields) {
            foreach ($orderBy as $column => $value) {
                if (!empty($orderBy['field'])) {
                    $builder->orderBy($orderBy['field'], $orderBy['method']);
                }
            }
        }

        if ($selectFields && $limit != null && $offset != null) {
            $builder->limit($limit, $offset);
        }

        if (!$selectFields) {
            return $builder->get()->getFirstRow()->count;
        } else {
            return $builder->get()->getResultArray();
        }
    }

    /**
     * Cria o Label dos inputs
     * @param string $label
     * @param array $data
     * @return string HTML da Label
     */
    private function _createLabel(string $label, array $data)
    {
        // caso não seja possível criar o label retorna uma string vazia para não concatenar nada no chamador
        $element = '';

        if (!empty($data['id'])) $element = "<label for='{$data['id']}'>{$label}</label>";

        return $element;
    }

    /**
     * Cria os Elementos HTML do Formulario
     * @param string $type
     * @param array $data
     * @param array $label
     */
    private function _createElement(string $type, array $data, array $label = [])
    {
        // caso não seja possível criar o elemento retorna uma string vazia para não concatenar nada no chamador
        $element = '';

        // verifica o tipo de elemento requisitado
        switch ($type) {
            case 'textarea':
                if (!empty($label) && $label['enabled']) {
                    $element = $element . $this->_createLabel($label['text'], $data);
                }

                // cria o elemento do tipo textarea
                $element = $element . '<textarea';
                foreach ($data as $key => $value) {
                    if ($key == 'text') continue;
                    $element = $element . ' ' . $key . '="' . $value . '" ';
                }
                $element = $element . '>';
                if (isset($data['text'])) $element = $element . $data['text'];
                $element = $element . '</textarea>';
                break;

            case 'input':
                if (!empty($label) && $label['enabled']) {
                    $element = $element . $this->_createLabel($label['text'], $data);
                }

                // cria o elemento do tipo input
                $element = $element . '<input';
                foreach ($data as $key => $value) {
                    $element = $element . ' ' . $key . '="' . $value . '" ';
                }
                $element = $element . ' />';
                break;

            case 'select':
                if (!empty($label) && $label['enabled']) {
                    $element = $element . $this->_createLabel($label['text'], $data);
                }

                $selected_id = '';

                // cria o elemento do tipo input
                $element = $element . '<select';
                foreach ($data as $key => $value) {
                    if ($key != 'options') {
                        $element = $element . ' ' . $key . '="' . $value . '" ';
                    }
                    if ($key == 'value') {
                        $selected_id = $value;
                    }
                }
                $element = $element . '>';

                // adiciona as options do select
                foreach ($data['options'] as $key => $value) {
                    if ($selected_id == $value[array_keys($value)[0]]) {
                        $element = $element . '<option value="' . $value[array_keys($value)[0]] . '" selected>' . $value[array_keys($value)[1]] . '</option>';
                    } else {
                        $element = $element . '<option value="' . $value[array_keys($value)[0]] . '">' . $value[array_keys($value)[1]] . '</option>';
                    }
                }

                // fecha a tag select
                $element = $element . '</select>';
                break;


            default:
                # code...
                break;
        }
        return $element;
    }

    /**
     * Cria o HTML das Páginas
     * @param array
     * @param array
     * @param array
     * @param array
     * @return array
     */
    public function createPage(array $config)
    {

        if ($this->request->isAJAX()) {
            $dadosRequest = $this->request->getVar();

            switch ($dadosRequest['custom_data']['type']) {
                case 'verify-field':
                    return ['exists' => $this->verifyField($config['table'], $dadosRequest['field'], $dadosRequest['value'])];
                    break;
                case 'datatables':
                    $config_number = $dadosRequest['custom_data']['config_number'];

                    if (!empty($dadosRequest['search']['value'])) {
                        $config['dataGrid'][$config_number]['search']['value'] = $dadosRequest['search']['value'];
                    } else {
                        $config['dataGrid'][$config_number]['search']['value'] = "";
                    }

                    // Adiciona um Where Completo (EX: (where x.field = xx.field2) )
                    if (empty($config['dataGrid'][$config_number]['where'])) {
                        $config['dataGrid'][$config_number]['where'] = null;
                    }

                    $order_by['field'] = $dadosRequest['columns'][$dadosRequest['order'][0]['column']]['name'];
                    $order_by['method'] = $dadosRequest['order'][0]['dir'];

                    if (empty($order_by['field'])) {
                        $order_by['field'] = $config['dataGrid'][0]['order_by']['field'];
                        $order_by['method'] = $config['dataGrid'][0]['order_by']['method'];
                    }

                    $data  = $this->getData(true,  $config['table'], $config['primaryKey'], null, $config['dataGrid'][$config_number]['fields'], $config['dataGrid'][$config_number]['joins'], $config['dataGrid'][$config_number]['search'], $order_by, $dadosRequest['length'], $dadosRequest['start'], $config['dataGrid'][$config_number]['where']);
                    $total = $this->getData(false, $config['table'], $config['primaryKey'], null, $config['dataGrid'][$config_number]['fields'], $config['dataGrid'][$config_number]['joins'], $config['dataGrid'][$config_number]['search'], $order_by, $dadosRequest['length'], $dadosRequest['start'], $config['dataGrid'][$config_number]['where']);

                    $dados['data']            = $data;
                    $dados['draw']            = $dadosRequest['draw'];
                    $dados['recordsTotal']    = $total;
                    $dados['recordsFiltered'] = $total;

                    return $dados;
                    break;
                case 'select2':
                    break;

                default:
                    # code...
                    break;
            }
        }

        $html      = '';
        $data      = [];
        $formOpen  = '';
        $formClose = '';

        switch ($config['function']) {
            case 'adicionar':
                $data = $config['formFields'];

                // Monta página de adicionar
                $table    = explode(' ', $config['table']);
                $formOpen = '<form action="' . base_url('cadastro/insert/' . reset($table) . '/' . $config['return']) . '" method="POST">';
                $html     = '<div class="col-12">';

                foreach ($data as $row) {
                    // abre a row
                    $html = $html . '<div class="card w-100">';
                    $html = $html . '<div class="card-body">';
                    $html = $html . '<div class="row">';

                    foreach ($row as $column) {
                        // abre o grid
                        $html = $html . "<div class='col-{$column['grid']}'>";
                        // cria o elemento
                        $html = $html . $this->_createElement($column['type'], $column['data'], $column['label']);
                        // fecha o grid
                        $html = $html . '</div>';
                    }

                    // fecha a row
                    $html = $html . '</div>';
                }
                $html = $html . '</div>';
                $html = $html . '</div>';
                $html = $html . '</div>';

                $formClose = '</form>';
                break;
            case 'alterar':
                $data = $config['formFields'];

                // selectiona os dados pra editar
                $result = $this->getData(true, $config['table'], $config['primaryKey'], $config['primaryKeyValue'], null, null, null, null);

                $table          = explode(' ', $config['table']);
                $primary_key    = explode('.', $config['primaryKey']);
                $formOpen      = $html = $html . '<form action="' . base_url('cadastro/update/' . reset($table) . '/' . end($primary_key) . '/' . $config['primaryKeyValue'] . '/' . $config['return']) . '" method="POST">';
                $html           = '<div class="col-12">';

                foreach ($data as $row) {
                    // abre a row
                    $html = $html . '<div class="card w-100">';
                    $html = $html . '<div class="card-body">';
                    $html = $html . '<div class="row">';

                    foreach ($row as $column) {
                        // abre o grid
                        $html = $html . '<div class="col-' . $column['grid'] . '">';

                        if (isset($column['data']['name'])) {
                            // cria o elemento
                            $column['data'][$column['type'] == 'textarea' ? 'text' : 'value'] = $result[0][$column['data']['name']];
                            $html = $html . $this->_createElement($column['type'], $column['data'], $column['label']);
                        } else {
                            // cria o elemento
                            $html = $html . $this->_createElement($column['type'], $column['data'], $column['label']);
                        }

                        // fecha o grid
                        $html = $html . '</div>';
                    }

                    // fecha a row
                    $html = $html . '</div>';
                    $html = $html . '</div>';
                    $html = $html . '</div>';
                }
                $html = $html . '</div>';

                $formClose = '</form>';

                break;
            case 'lista':
                $data = $config['dataGrid'];

                if (count($data) > 1) {
                    $html = $html . '<ul class="nav nav-tabs">';
                    foreach ($data as $i => $tab) {
                        $html = $html . '<li class="nav-item"><button class="nav-link ' . ($i == 0 ? 'active' : '') . '" type="button" role="tab" data-bs-toggle="tab" data-bs-target="#' . strtolower(str_replace(' ', '_', $tab['tab_name'])) . '">' . $tab['tab_name'] . '<span class="selector-contador-' . $i . '"> (0)</span></button>';
                    }
                    $html = $html . '</ul>';

                    $html = $html . '<div class="tab-content">';

                    foreach ($data as $i => $tab) {
                        $html = $html . '<div class="tab-pane fade' . ($i == 0 ? 'in active show' : '') . '" id="' . strtolower(str_replace(' ', '_', $tab['tab_name'])) . '">';

                        // abre a table
                        $html = $html . '<table class="table display table-striped table-hover selector-table-crud-' . strtolower(str_replace(' ', '_', $tab['tab_name'])) . '">';

                        // abre a tr
                        $html = $html . '<thead><tr>';

                        // monta a th
                        foreach ($tab['fields'] as $key => $value) {
                            if ($value == NULL)
                                continue;

                            // abre a td
                            $html = $html . '<th>';

                            // coloca o nome da coluna
                            $html = $html . $value;

                            // fecha a td
                            $html = $html . '</th>';
                        }

                        if ($tab['options']['enabled']) {
                            // adiciona a coluna opcoes
                            $html = $html . '<th class="all">Ações</th>';
                        }

                        // fecha a tr
                        $html = $html . '</tr></thead>';

                        $html = $html . '</table>';

                        $html = $html . '</div>';
                    }
                } else {
                    // // abre a table
                    $html = '<table class="table display table-striped table-hover selector-table-crud-' . strtolower(str_replace(' ', '_', $data[0]['tab_name'])) . '">';

                    // abre a tr
                    $html = $html . '<thead><tr>';

                    // monta a th
                    foreach ($data[0]['fields'] as $key => $value) {
                        if ($value == NULL)
                            continue;
                        // abre a td
                        $html = $html . '<th>';

                        // coloca o nome da coluna
                        $html = $html . $value;

                        // fecha a td
                        $html = $html . '</th>';
                    }

                    if ($data[0]['options']['enabled']) {
                        // adiciona a coluna opcoes
                        $html = $html . '<th class="all">Ações</th>';
                    }

                    // fecha a tr
                    $html = $html . '</tr></thead>';

                    $html = $html . '</table>';
                }

                break;
            default:
        }

        $data['html'] = $html;
        $data['formOpen'] = $formOpen;
        $data['formClose'] = $formClose;

        if (empty($config['disable_order_by'])) {
            $config['disable_order_by'] = '';
        }

        foreach ($config as $key => $value) {
            $data[$key] = $value;
        }

        $data['configCrudDinamico'] = $config['dataGrid'];

        return (array) $data;
    }

    //////////////////////////////////////
    //                                  //
    //         OPERAÇÕES DE CRUD        //
    //                                  //
    //////////////////////////////////////

    /**
     * Insere um registro
     * @param string $table Nome da tabela
     * @param string $field Nome do campo da tabela
     * @param string $value Valor a ser procurado
     * @param string $pathReturn PATH de redirecionamento depois de atualizar
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function insert(string $table, string $pathReturn): RedirectResponse
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');
        $dadosUsuario = (new NativeSession(true))->get('usuario');

        // Valida se a Tabela contem alias
        if (strpos($table, '%20') === true) {
            $table = explode('%20', $table)[0];
        }

        // Recebe os Dados via POST e valida os campos, convertendo vazio para null
        $dadosRequest = $this->request->getPost();
        foreach ($dadosRequest as $key => $value) {
            $dadosRequest[$key] = $value == '' ? null : $value;
        }

        // Realiza a Validação de Campos da Tabela
        if ($this->db->fieldExists('usuario_criacao', $table)) {
            $dadosRequest['usuario_criacao'] = $dadosUsuario['codigo_usuario'];
        }

        if ($this->db->fieldExists('codigo_empresa', $table)) {
            $dadosRequest['codigo_empresa'] = $dadosEmpresa['codigo_empresa'];
        }

        // Insere o registro
        $this->db->table($table)->insert($dadosRequest);

        return redirect()->to(base_url("cadastro/{$pathReturn}"));
    }

    /**
     * Atualiza um registro
     * @param string $table Nome da tabela
     * @param string $field Nome do campo da tabela
     * @param string $value Valor a ser procurado
     * @param string $pathReturn PATH de redirecionamento depois de atualizar
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update(string $table, $primaryKey, $primaryKeyValue, string $pathReturn): RedirectResponse
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');
        $dadosUsuario = (new NativeSession(true))->get('usuario');

        // Valida se a Tabela contem alias
        if (strpos($table, '%20') === true) {
            $table = explode('%20', $table)[0];
        }

        // Valida se a Primary Key contem alias
        if (strpos($primaryKey, '.') === true) {
            $primaryKey = explode('.', $primaryKey)[1];
        }

        // Recebe os Dados via POST e valida os campos, convertendo vazio para null
        $dadosRequest = $this->request->getPost();
        foreach ($dadosRequest as $key => $value) {
            $dadosRequest[$key] = $value == '' ? null : $value;
        }

        // Realiza a Validação de Campos da Tabela
        if ($this->db->fieldExists('usuario_alteracao', $table)) {
            $dadosRequest['usuario_alteracao'] = $dadosUsuario['codigo_usuario'];
        }

        if ($this->db->fieldExists('alterado_em', $table)) {
            $dadosRequest['alterado_em'] = "NOW()";
        }

        if ($this->db->fieldExists('codigo_empresa', $table)) {
            $dadosRequest['codigo_empresa'] = $dadosEmpresa['codigo_empresa'];
        }

        // Atualiza o registro
        $this->db->table($table)->where($primaryKey, $primaryKeyValue)->set($dadosRequest)->update();

        return redirect()->to(base_url("cadastro/{$pathReturn}"));
    }

    /**
     * Exclui um registro
     * @param string $table Nome da tabela
     * @param string $field Nome do campo da tabela
     * @param string $value Valor a ser procurado
     * @return \CodeIgniter\HTTP\Response
     */
    public function delete(string $table, $primaryKey, $primaryKeyValue)
    {
        // Valida se a Tabela contem alias
        if (strpos($table, '%20') === true) {
            $table = explode('%20', $table)[0];
        }

        // Valida se a Primary Key contem alias
        if (strpos($primaryKey, '.') === true) {
            $primaryKey = explode('.', $primaryKey)[1];
        }

        // Deleta o registro
        return $this->db->table($table)->where($primaryKey, $primaryKeyValue)->delete();
    }

    /**
     * Inverte o Status do Registro (Ativo/Inativo)
     * @param string $table Nome da tabela
     * @param string $field Nome do campo da tabela
     * @param string $value Valor a ser procurado
     * @return
     */
    public function toggleStatus(string $table, $primaryKey, $primaryKeyValue)
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');
        $dadosUsuario = (new NativeSession(true))->get('usuario');

        // Valida se a Tabela contem alias
        if (strpos($table, '%20') === true) {
            $table = explode('%20', $table)[0];
        }

        // Valida se a Primary Key contem alias
        if (strpos($primaryKey, '.') === true) {
            $primaryKey = explode('.', $primaryKey)[1];
        }

        // Realiza a busca do registro
        $builder = $this->db->table($table);
        $builder->where($primaryKey, $primaryKeyValue);
        $consulta = (array) $builder->get()->getFirstRow();

        // Verifica se o registro esta ativo ou inativo
        if (empty($consulta['inativado_em'])) {
            $dadosUpdate['inativado_em'] = 'NOW()';
            $dadosUpdate['usuario_inativacao'] = $dadosUsuario['codigo_usuario'];
        } else {
            $dadosUpdate['inativado_em'] = null;
            $dadosUpdate['usuario_inativacao'] = null;
        }

        // Atualiza o registro
        return $this->db->table($table)->where($primaryKey, $primaryKeyValue)->set($dadosUpdate)->update();
    }

    /**
     * Duplica um registro existente
     * @param string $table Nome da tabela
     * @param string $field Nome do campo da tabela
     * @param string $value Valor a ser procurado
     * @return \CodeIgniter\HTTP\Response
     */
    public function copiarRegistro(string $table, $primaryKey, $primaryKeyValue): Response
    {
        $dadosEmpresa = (new NativeSession(true))->get('empresa');
        $dadosUsuario = (new NativeSession(true))->get('usuario');

        // Valida se a Tabela contem alias
        if (strpos($table, '%20') === true) {
            $table = explode('%20', $table)[0];
        }

        // Valida se a Primary Key contem alias
        if (strpos($primaryKey, '.') === true) {
            $primaryKey = explode('.', $primaryKey)[1];
        }

        // Realiza a busca do registro
        $builder = $this->db->table($table);
        $builder->where($primaryKey, $primaryKeyValue);
        $consulta = (array) $builder->get()->getFirstRow();

        // Verifica se veio o Código ou a UUID como PrimaryKey para remover
        if (strpos($primaryKey, 'codigo_') === true) {
            unset($consulta[$primaryKey]); // Remove o Código
            unset($consulta[str_replace('codigo_', 'uuid_', $primaryKey)]); // Remove a UUID
        } else {
            unset($consulta[$primaryKey]); // Remove a UUID
            unset($consulta[str_replace('uuid_', 'codigo_', $primaryKey)]); // Remove o Código
        }

        // Realiza a Validação de Campos da Tabela
        if ($this->db->fieldExists('nome', $table)) {
            $consulta['nome'] = "{$consulta['nome']} - Cópia";
        }

        if ($this->db->fieldExists('descricao', $table)) {
            $consulta['descricao'] = "{$consulta['descricao']} - Cópia";
        }

        if ($this->db->fieldExists('criado_em', $table)) {
            $consulta['criado_em'] = "NOW()";
        }

        if ($this->db->fieldExists('alterado_em', $table)) {
            $consulta['alterado_em'] = null;
        }

        if ($this->db->fieldExists('inativado_em', $table)) {
            $consulta['inativado_em'] = null;
        }

        if ($this->db->fieldExists('usuario_criacao', $table)) {
            $consulta['usuario_criacao'] = $dadosUsuario['codigo_usuario'];
        }

        if ($this->db->fieldExists('usuario_alteracao', $table)) {
            $consulta['usuario_alteracao'] = null;
        }

        if ($this->db->fieldExists('usuario_inativacao', $table)) {
            $consulta['usuario_inativacao'] = null;
        }

        // Insere o registro
        $data = $this->db->insert($table, $consulta);
        return $this->response->setJSON($data);
    }
}
