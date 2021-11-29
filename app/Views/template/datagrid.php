<script>
    //////////////////////////////////////
    //                                  //
    //     DECLARAÇÃO DAS VARIAVEIS     //
    //                                  //
    //////////////////////////////////////

    // Globais JS Paginação
    var mapeamento = []; // Array com os Dados do Mapeamento dos Grids
    const PERMISSAO = [];


    //////////////////////////////////////
    //                                  //
    //   DECLARAÇÃO DO CRUD DINAMICO    //
    //                                  //
    //////////////////////////////////////
    <?php if (!empty($configCrudDinamico)) { ?>
        <?php foreach ($configCrudDinamico as $n => $tab) : ?>
            <?php $temp_primary_key                                = explode('.', $primaryKey); ?>

            mapeamento[<?= $n ?>] = []; // cria um array
            mapeamento[<?= $n ?>]['cadastro'] = []; // cria um array
            mapeamento[<?= $n ?>]['cadastro']['pageLength'] = "<?= $paginadorMaximo; ?>"; // itens por página
            mapeamento[<?= $n ?>]['cadastro']['id_column'] = "<?= end($temp_primary_key); ?>"; // chave primária, para montar id dos botões
            mapeamento[<?= $n ?>]['cadastro']['btn_montar'] = <?= $tab['options']['enabled'] ? 'true' : 'false' ?>; // montar os botões?
            mapeamento[<?= $n ?>]['cadastro']['btn_column'] = <?= $tab['options']['enabled'] ? count(array_diff($tab['fields'], [NULL])) : 0 ?>; // localização da coluna de botoes
            mapeamento[<?= $n ?>]['cadastro']['ajax_nome'] = METODO; // nome da função de paginação
            mapeamento[<?= $n ?>]['cadastro']['ajax_url'] = `${BASEURL}/cadastro/${mapeamento[<?= $n ?>]['cadastro']['ajax_nome']}`; // nome da função de paginação
            mapeamento[<?= $n ?>]['cadastro']['pdf_columns'] = [<?php for ($i = 0; $i < count(array_diff($tab['fields'], [NULL])); $i++) : echo $i + 1 == count(array_diff($tab['fields'], [NULL])) ? $i : $i . ', ';
                                                                endfor; ?>];
            mapeamento[<?= $n ?>]['cadastro']['disable_order_by'] = [<?= $disable_order_by; ?>];

            <?php
            $order_by = 0;
            foreach ($tab['fields'] as $key => $value) {
                if ($value == NULL) continue;

                $temp_order_by  = explode('.', $tab['order_by']['field']);
                $temp_key       = explode('.', $key);
                $temp_key       = end($temp_key);
                $temp_key       = explode(' ', $temp_key);

                if (end($temp_order_by) == end($temp_key)) {
                    break;
                }

                $order_by++;
            }
            ?>

            mapeamento[<?= $n ?>]['cadastro']['order_by'] = [{
                "coluna": <?= $order_by; ?>,
                "metodo": "<?= $tab['order_by']['method']; ?>"
            }];
            mapeamento[<?= $n ?>]['cadastro']['custom_data'] = [{
                config_number: <?= $n ?>,
                type: 'datatables'
            }];

            mapeamento[<?= $n ?>]['cadastro']['columns'] = <?php
                                                            $fields = array();
                                                            foreach ($tab['fields'] as $key => $value) {
                                                                if ($value == NULL) {
                                                                    continue;
                                                                }

                                                                $temp_key_parenteses        = explode(' ', $key);
                                                                $temp_key_parenteses        = end($temp_key_parenteses);
                                                                $temp_key_parenteses        = explode('(', $temp_key_parenteses);
                                                                $temp_key_parenteses        = end($temp_key_parenteses);
                                                                $temp_key_parenteses        = explode('.', $temp_key_parenteses);
                                                                $current_field              = new stdClass();
                                                                $current_field->data        = str_replace(')', '', str_replace(',', '', end($temp_key_parenteses)));
                                                                $current_field->name        = str_replace(')', '', str_replace(',', '', end($temp_key_parenteses)));
                                                                $fields[]                   = $current_field;
                                                            }

                                                            if ($tab['options']['enabled']) {
                                                                $current_field              = new stdClass();
                                                                $current_field->data        = end($temp_primary_key);

                                                                $fields[]                   = $current_field;
                                                            }

                                                            echo json_encode($fields);
                                                            ?>;

            mapeamento[<?= $n ?>]['cadastro']['btn'] = <?php
                                                        $btn                            = array();

                                                        if (isset($tab['options']['enabled']) && $tab['options']['enabled']) {
                                                            $temp_table                 = explode(' ', $table);

                                                            if (isset($tab['options']['edit']) && $tab['options']['edit']) {
                                                                $current_btn            = new stdClass();
                                                                $current_btn->funcao    = "editar";
                                                                $current_btn->metodo    = 'alterar';
                                                                $current_btn->compare   = null;
                                                                $btn[]                  = $current_btn;
                                                            }

                                                            if (isset($tab['options']['copiar']) && $tab['options']['copiar']) {
                                                                $current_btn            = new stdClass();
                                                                $current_btn->funcao    = "copiarCrud";
                                                                $current_btn->metodo    = 'copiarRegistro/' . reset($temp_table) . '/' . end($temp_primary_key);
                                                                $current_btn->compare   = array(
                                                                    'operator'          => 'OR',
                                                                    'expressions'       => array(
                                                                        array(
                                                                            'column'    => 'reservado',
                                                                            'type'      => '!=',
                                                                            'value'     => 't'
                                                                        )
                                                                    )
                                                                );

                                                                $btn[]                  = $current_btn;
                                                            }

                                                            if (isset($tab['options']['delete']) && $tab['options']['delete']) {
                                                                $current_btn            = new stdClass();
                                                                $current_btn->funcao    = "excluirCrud";
                                                                $current_btn->metodo    = 'delete/' . reset($temp_table) . '/' . end($temp_primary_key);
                                                                $current_btn->compare   = array(
                                                                    'operator'          => 'OR',
                                                                    'expressions'       => array(
                                                                        array(
                                                                            'column'    => 'reservado',
                                                                            'type'      => '!=',
                                                                            'value'     => 't'
                                                                        )
                                                                    )
                                                                );

                                                                $btn[]                  = $current_btn;
                                                            }

                                                            if (isset($tab['options']['ativar']) && $tab['options']['ativar']) {
                                                                $current_btn            = new stdClass();
                                                                $current_btn->funcao    = "ativarCrud";
                                                                $current_btn->metodo    = 'toggle-status/' . reset($temp_table) . '/' . end($temp_primary_key);
                                                                $current_btn->compare   = array(
                                                                    'operator'          => 'OR',
                                                                    'expressions'       => array(
                                                                        array(
                                                                            'column'    => 'reservado',
                                                                            'type'      => '!=',
                                                                            'value'     => 't'
                                                                        )
                                                                    )
                                                                );

                                                                $btn[]                  = $current_btn;
                                                            }

                                                            if (isset($tab['options']['desativar']) && $tab['options']['desativar']) {
                                                                $current_btn            = new stdClass();
                                                                $current_btn->funcao    = "desativarCrud";
                                                                $current_btn->metodo    = 'toggle-status/' . reset($temp_table) . '/' . end($temp_primary_key);
                                                                $current_btn->compare   = array(
                                                                    'operator'          => 'OR',
                                                                    'expressions'       => array(
                                                                        array(
                                                                            'column'    => 'reservado',
                                                                            'type'      => '!=',
                                                                            'value'     => 't'
                                                                        )
                                                                    )
                                                                );

                                                                $btn[]                  = $current_btn;
                                                            }
                                                        }

                                                        echo json_encode($btn);
                                                        ?>;

            mapeamento[<?= $n ?>]['cadastro']['multiple_select'] = false;
            mapeamento[<?= $n ?>]['cadastro']['toolbar_buttons'] = [];
        <?php endforeach; ?>
    <?php } ?>


    //////////////////////////////////////
    //                                  //
    //  DECLARAÇÃO DAS FUNÇÕES GLOBAIS  //
    //                                  //
    //////////////////////////////////////

    const dataGridOptionsFunctions = {
        init: () => {
            dataGridOptionsFunctions.ativarRegistro();
            dataGridOptionsFunctions.desativarRegistro();
            dataGridOptionsFunctions.toggleStatusRegistro();
        },
        ativarRegistro: () => {
            $(document).on('click', "[data-action='ativarRegistro']", function(e) {
                notificationFunctions.popupConfirm('Atenção', 'Deseja realmente ativar o registro ?', 'warning').then(
                    (result) => {
                        if (result.value) {
                            appFunctions.backendCall('POST', `${ROUTE}/ativar/${$(this).data('id')}`).then(
                                (res) => {
                                    notificationFunctions.toastSmall(res.textStatus, res.mensagem);
                                    $('#tableAtivos, #tableInativos').DataTable().destroy();
                                    $('#tableAtivos').DataTable(dataGridGlobalFunctions.getSettings(0));
                                    $('#tableInativos').DataTable(dataGridGlobalFunctions.getSettings(1));
                                }
                            );
                        }
                    }
                );
            });
        },
        desativarRegistro: () => {
            $(document).on('click', "[data-action='desativarRegistro']", function(e) {
                notificationFunctions.popupConfirm('Atenção', 'Deseja realmente desativar o registro ?', 'warning').then(
                    (result) => {
                        if (result.value) {
                            appFunctions.backendCall('POST', `${ROUTE}/desativar/${$(this).data('id')}`).then(
                                (res) => {
                                    notificationFunctions.toastSmall(res.textStatus, res.mensagem);
                                    $('#tableAtivos, #tableInativos').DataTable().destroy();
                                    $('#tableAtivos').DataTable(dataGridGlobalFunctions.getSettings(0));
                                    $('#tableInativos').DataTable(dataGridGlobalFunctions.getSettings(1));
                                }
                            );
                        }
                    }
                );
            });
        },
        toggleStatusRegistro: () => {
            $(document).on('click', "[data-action='toggleStatus']", function(e) {
                notificationFunctions.popupConfirm('Atenção', 'Deseja realmente alterar o status do registro ?', 'warning').then(
                    (result) => {
                        if (result.value) {
                            appFunctions.backendCall('POST', `${ROUTE}/toggle-status/${$(this).data('id')}`).then(
                                (res) => {
                                    notificationFunctions.toastSmall(res.textStatus, res.mensagem);
                                    $('#tableAtivos, #tableInativos').DataTable().destroy();
                                    $('#tableAtivos').DataTable(dataGridGlobalFunctions.getSettings(0));
                                    $('#tableInativos').DataTable(dataGridGlobalFunctions.getSettings(1));
                                }
                            );
                        }
                    }
                );
            });
        },
        buttonList: (nome, method, id, data) => {
            /**
             *  Botões Para inserir na Tabela
             *  @param nome Nome do Botão
             *  @param method Função que executara
             *  @param id PrimaryKey da Row
             *  @param data Dados Adicionais da Row
             */

            const URL = `${BASEURL}/${ROUTE}/${method}/${id}`;
            const buttons = {
                "ativar": () => `<i class="fas fa-power-off mx-1 cursor text-success" data-id="${id}" data-action="ativarRegistro" data-tippy-content="Ativar"></i>`,
                "desativar": () => `<i class="fas fa-power-off mx-1 cursor text-danger" data-id="${id}" data-action="desativarRegistro" data-tippy-content="Desativar"></i>`,
                "editar": () => `<a href="${URL}"><i class="fas fa-edit mx-1 cursor text-info" data-tippy-content="Editar"></i></a>`,
                "visualizar": () => `<a href="${URL}"><i class="fas fa-search mx-1 cursor text-warning" data-tippy-content="Visualizar"></i></a>`,
                "copiar": () => `<a href="${URL}"><i class="fas fa-copy mx-1 cursor text-warning" data-id="${id}" data-action="copiarRegistro" data-url="${URL}" data-tippy-content="Criar cópia"></i></a>`,
                "ativarCustom": () => `<i class="fas fa-power-off mx-1 cursor text-success" data-id="${id}" data-action="ativarRegistroCustom" data-tippy-content="Ativar"></i>`,
                "desativarCustom": () => `<i class="fas fa-power-off mx-1 cursor text-danger" data-id="${id}" data-action="desativarRegistroCustom" data-tippy-content="Desativar"></i>`,
                "excluirCustom": () => `<i class="fas fa-trash mx-1 cursor text-danger" data-id="${id}" data-action="excluirRegistroCustom" data-tippy-content="Excluir"></i>`,
                "editarModal": () => `<i class="fas fa-edit mx-1 cursor text-info" data-id="${id}" data-action="editarRegistroModal" data-tippy-content="Editar"></i>`,
                "toggleStatus": () => `<i class="fas fa-power-off mx-1 cursor" data-id="${id}" data-action="toggleStatus" data-tippy-content="Alterar o Status" data-status="${method}"></i>`,
                "verModal": () => `<i class="fas fa-search mx-1 cursor text-warning" data-id="${id}" data-action="verRegistroModal" data-tippy-content="Visualizar"></i>`,
                "imprimir": () => `<a href="${BASEURL}/${method}/${id}"><i class="fas fa-print mx-1 cursor" data-id="${id}" data-action="imprimirRegistro" data-tippy-content="Imprimir"></i></a>`,
                "imprimirCustom": () => `<i class="fas fa-print mx-1 cursor" data-id="${id}" data-action="imprimirRegistro" data-tippy-content="Imprimir"></i>`,
                "gerarPdf": () => `<a href="${URL}"><i class="fas fa-file-pdf mx-1 cursor text-body" data-tippy-content="Gerar PDF"></i></a>`,
                "ativarCrud": () => `<i class="fas fa-power-off mx-1 cursor" data-id="${id}" data-url="${BASEURL}/cadastro/${method}/${id}" data-action="ativarRegistroCrud" data-tippy-content="Ativar"></i>`,
                "desativarCrud": () => `<i class="fas fa-power-off mx-1 cursor" data-id="${id}" data-url="${BASEURL}/cadastro/${method}/${id}" data-action="desativarRegistroCrud" data-tippy-content="Desativar"></i>`,
                "copiarCrud": () => `<i class="fas fa-copy mx-1 cursor" data-id="${id}" data-url="${BASEURL}/cadastro/${method}/${id}" data-action="copiarRegistroCrud" data-tippy-content="Criar cópia"></i>`,
                "excluirCrud": () => `<i class="fas fa-trash mx-1 cursor" data-id="${id}" data-url="${BASEURL}/cadastro/${method}/${id}" data-action="excluirRegistroCrud" data-tippy-content="Excluir"></i>`,
                "toggleStatusCrud": () => `<i class="fas fa-power-off mx-1 cursor" data-id="${id}" data-status="${method}" data-action="toggleStatusCrud" data-tippy-content="Alterar Status"></i>`,
                "fluxoEditar": () => `<i class="fas fa-edit mx-1 cursor text-info" data-id="${id}" data-status="${method}" data-action="fluxoEditar" data-tippy-content="Editar Fluxo Financeiro"></i>`,
                "fluxoMarcarPago": () => `<i class="fas fa-dollar-sign mx-1 cursor text-success" data-id="${id}" data-status="${method}" data-action="fluxoMarcarPago" data-tippy-content="Marcar como Pago"></i>`,
                "fluxoMarcarPendente": () => `<i class="fas fa-dollar-sign mx-1 cursor text-danger" data-id="${id}" data-status="${method}" data-action="fluxoMarcarPendente" data-tippy-content="Marcar como Pendente"></i>`,
                "fluxoPagarParcial": () => `<i class="fas fa-file-invoice-dollar mx-1 cursor text-info" data-id="${id}" data-status="${method}" data-action="fluxoPagarParcial" data-tippy-content="Realizar Pagamento Parcial"></i>`,
                "clienteAdicionarSaldo": () => `<i class="fas fa-plus mx-1 cursor" data-id="${id}" data-status="${method}" data-action="adicionarSaldo" data-tippy-content="Adicionar Saldo"></i>`,
                "historicoVisualizar": () => `<i class="fas fa-search mx-1 cursor" data-id="${id}" data-status="${method}" data-action="historicoVisualizar" data-tippy-content="Visualizar"></i>`,
                "historicoVisualizarItem": () => `<i class="fas fa-eye mx-1 cursor" data-id="${id}" data-status="${method}" data-action="historicoVisualizarItem" data-tippy-content="Visualizar Histórico Total"></i>`,
                "imovelAlterarPreco": () => `<i class="fas fa-dollar-sign mx-1 cursor" data-id="${id}" data-status="${method}" data-action="imovelAlterarPreco" data-tippy-content="Alterar Preço do Imovel"></i>`,
                "imovelVisualizarHistorico": () => `<a href="${BASEURL}/estoque/historico/?imovel=${id}"><i class="fas fa-search mx-1 cursor text-warning" data-tippy-content="Visualizar Histórico"></i></a>`,
            }

            return buttons.hasOwnProperty(nome) ? buttons[nome]() : '';
        },
        getCounter: (element, idTable, indexTable) => {
            $(element).html(`(${$(idTable).dataTable(dataGridGlobalFunctions.getSettings(indexTable)).fnSettings().fnRecordsTotal()})`);
        },
        destroyTable: (element) => {
            if ($.fn.DataTable.isDataTable(element)) {
                $(element).DataTable().destroy();
            }
        }
    };

    const dataGridGlobalFunctions = {
        init: () => {
            dataGridGlobalFunctions.adjustsButtonColumn();
            dataGridGlobalFunctions.optionCollectionBox();
        },
        getSettings: (position, nameRoute = ROUTE) => {
            /**
             * Realiza a Configuração para o DataTables
             * @param position Posição do array
             */

            // Configurações Padrão do Mapeamento (Normalização do Array)
            mapeamento[position][nameRoute]['id_column'] = mapeamento[position][nameRoute]['id_column'] || "uuid";
            mapeamento[position][nameRoute]['ajax_nome'] = mapeamento[position][nameRoute]['ajax_nome'] || "getDataGrid";
            mapeamento[position][nameRoute]['ajax_url'] = mapeamento[position][nameRoute]['ajax_url'] || `${BASEURL}/${ROUTE}/${mapeamento[position][nameRoute]['ajax_nome']}`;
            mapeamento[position][nameRoute]['custom_data'] = mapeamento[position][nameRoute]['custom_data'] || [];
            mapeamento[position][nameRoute]['order_by'] = mapeamento[position][nameRoute]['order_by'] || [{
                "coluna": 1,
                "metodo": "ASC"
            }];
            mapeamento[position][nameRoute]['pageLength'] = mapeamento[position][nameRoute]['pageLength'] || 25;
            mapeamento[position][nameRoute]['multiple_select'] = mapeamento[position][nameRoute]['multiple_select'] || false;
            mapeamento[position][nameRoute]['toolbar_buttons'] = mapeamento[position][nameRoute]['toolbar_buttons'] || [];
            mapeamento[position][nameRoute]['btn_montar'] = mapeamento[position][nameRoute]['btn_montar'] || false;
            mapeamento[position][nameRoute]['footer_montar'] = mapeamento[position][nameRoute]['footer_montar'] || true;
            mapeamento[position][nameRoute]['btn_column'] = mapeamento[position][nameRoute]['columns'].length - 1;
            mapeamento[position][nameRoute]['pdf_columns'] = dataGridGlobalFunctions.getArrayKeys(mapeamento[position][nameRoute]['columns'].length - 1);

            let mapeamentoObj = mapeamento[position][nameRoute];
            let settings = {
                dom: 'B<"clear">lfrtip', // B = Buttons, L = Length, F = Filter, R = Processing Display, T = Table, I = Summary, p = Pagination, Q = Search Builder, P = Search Panel
                pagingType: "full_numbers",
                buttons: [],
                stateSave: false,
                processing: true,
                serverSide: true,
                searchDelay: 1000,
                responsive: true,
                searching: true,
                autoWidth: true,
                fixedHeader: true,
                scroller: false,
                paging: true,
                scrollCollapse: true,
                searchable: true,
                retrieve: true,
                pageLength: mapeamentoObj['pageLength'],
                select: {
                    info: false
                },
                columns: mapeamentoObj['columns'],
                order: [mapeamentoObj['order_by'][0]['coluna'], mapeamentoObj['order_by'][0]['metodo']],
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "Todos"]
                ],
                aoColumnDefs: [mapeamentoObj['btn_montar'] ? {
                    "bSortable": false,
                    "aTargets": [mapeamentoObj['btn_column']]
                } : {}],
                language: dataGridGlobalFunctions.getLanguage(),
                ajax: {
                    method: 'POST',
                    url: mapeamentoObj['ajax_url'],
                    data: {
                        custom_data: mapeamentoObj['custom_data'][0]
                    }
                },
                fnInitComplete: (oSettings, json) => {
                    // Realiza Alterações no Campo de Pesquisar
                    // dataGridGlobalFunctions.alterFieldSearch(oSettings);

                    // Verifica se é necessário montar a Coluna de botões de Opção
                    dataGridGlobalFunctions.mountColumnButtons(oSettings, mapeamentoObj['btn_montar'], mapeamentoObj['btn_column']);

                    // Atualiza os tooltips
                    appFunctions.tooltip();
                },
                drawCallback: (settings) => {
                    // Atualiza os Counters da Tabela
                    $(`.count-${settings.sTableId}`).html(`(${$(`#${settings.sTableId}`).dataTable(dataGridGlobalFunctions.getSettings(position, nameRoute)).fnSettings().fnRecordsTotal()})`);

                    // Verifica se é necessário montar a Coluna de botões de Opção novamente
                    dataGridGlobalFunctions.mountColumnButtons(settings, mapeamentoObj['btn_montar'], mapeamentoObj['btn_column']);
                    dataGridGlobalFunctions.optionCollectionBox();

                    // Atualiza os tooltips
                    appFunctions.tooltip();
                },
                fnCreatedRow: (nRow, aData, iDataIndex) => {
                    // Verifica se a opção de selecionar multiplas linhas esta habilitada
                    if (mapeamentoObj['multiple_select']) {
                        $(nRow).click(function() {
                            $(this).toggleClass('selected');
                        });
                    }

                    // Seta o ID da Coluna como PrimaryKey da Linha
                    $(nRow).data('primary-key', aData[mapeamentoObj['id_column']]);

                    // Verifica as Condições para Inserir os Botões Personalizados
                    if (mapeamentoObj['btn_montar']) {
                        var btnHtml = '';
                        $.each(mapeamentoObj['btn'], (i, o) => {
                            var addBtn = true;
                            if (o.compare != null) {
                                var condition = false;
                                $.each(o.compare.expressions, (j, obj) => {
                                    switch (obj.type) {
                                        case '==':
                                            condition = aData[obj.column] == obj.value;
                                            break;
                                        case '!=':
                                            condition = aData[obj.column] != obj.value;
                                            break;
                                        case '>':
                                            condition = aData[obj.column] > obj.value;
                                            break;
                                        case '>=':
                                            condition = aData[obj.column] >= obj.value;
                                            break;
                                        case '<':
                                            condition = aData[obj.column] < obj.value;
                                            break;
                                        case '<=':
                                            condition = aData[obj.column] <= obj.value;
                                            break;
                                    }
                                    var retn = dataGridGlobalFunctions.getOperatorType(o.compare.operator);
                                    if ((condition == false && retn == true) || (condition == true && retn == false))
                                        return false;
                                    return true;
                                });

                                addBtn = condition;
                            }

                            if (addBtn) {
                                //     let codigoGrupo = null;
                                //     if (o.permissao != null && codigoGrupo != 1) {
                                //         let permissoes = [];
                                //         if (permissoes.includes(o.permissao)) {
                                btnHtml = btnHtml + dataGridOptionsFunctions.buttonList(o['funcao'], o['metodo'], aData[mapeamentoObj['id_column']], aData);
                                //         }
                                //     } else {
                                //         btnHtml = btnHtml + dataGridOptionsFunctions.buttonList(o['funcao'], o['metodo'], aData[mapeamentoObj['id_column']], aData);
                                //     }
                            }
                        });

                        // Adiciona Os Botões na Coluna de Opções
                        $(nRow).find('td').eq($(nRow).parents('table').find('thead').find('tr').find('th[class="selectorBtnCol"]').index()).html(btnHtml);
                    }
                }
            };

            // Adiciona Botões nas Opções do DataTables
            dataGridGlobalFunctions.mountOptionButtons(settings, mapeamentoObj);

            return settings;
        },
        getArrayKeys: (value) => {
            /**
             * Cria um Array de Indices com o valor da Entrada =>  3 = [0,1,2]
             * @param number value
             */
            if (!value || value == -1) return [];
            return Array.from(Array(value).keys());
        },
        getOperatorType: (type) => {
            /**
             * Valida e Normaliza o Tipo de Comparação Desejada
             * @param boolean type
             */
            if (type == null) return true;
            let typeOf = type.toLowerCase();

            if (typeOf == 'and' || typeOf == 'e' || typeOf == '') return true;
            else if (typeOf == 'or' || typeOf == 'ou') return false;
        },
        adjustsButtonColumn: () => {
            /**
             * Ajusta o Tamanho da Coluna de Opções Dinamicamente
             */
            $(document).on('draw.dt', '.dataTable', function() {

                // Realiza a Contagem de Botões na Coluna de Opções
                var qtdBotoes = 0;
                $(this).find('thead').find('tr').find('th:last').addClass('all');
                $.each($(this).find('tbody').find('tr'), function(key, value) {
                    if (parseInt($(value).find('td:last').children().length) > qtdBotoes) {
                        qtdBotoes = parseInt($(value).find('td:last').children().length);
                    }
                });

                if (qtdBotoes > 0) {
                    // Remove a Classe Existente (Pode Conter uma das 7 classes de tamanho)
                    for (let i = 0; i <= 7; i++) {
                        $(this).find('thead').find('tr').find('th:last').removeClass('th-' + i);
                    }
                    // Adiciona a Classe Adequada
                    $(this).find('thead').find('tr').find('th:last').addClass('th-' + qtdBotoes);
                }
            });
        },
        optionCollectionBox: () => {
            /**
             * Cria os Botões personalizados no menu de Opções do DataTables
             */

            // personaliza a collection box
            var caixasCriadas = [];
            $('.btnOpcoes').on('click', function() {
                // Limpa o titulo da Caixa de Opções
                $(".dt-button-collection").find('.boxOpcoes-titulo').html('');
                var ariaControls = $(this).attr('aria-controls');

                // Adiciona um Novo Titulo na Caixa de Opções
                $(".dt-button-collection").prepend("<div class='boxOpcoes-titulo'>Visibilidade das Colunas</div>");

                // Adiciona Um Titulo nas Opções de Exportar
                $(".dt-button-collection").find('.btnExports:first').before("\
                    <div class='boxOpcoes-separador'></div>\
                    <div class='boxOpcoes-titulo'>Exportar</div>\
                    <div class='btnsBoxExportar'></div>\
                    <div class='boxOpcoes-separador'></div>\
                ");

                // clona os botões de exportar para colocar eles dentro da nova box e remove a box antiga
                let btnExports = $(".btnExports").clone(true);
                $(".btnExports").remove();
                $(".btnsBoxExportar").html(btnExports);

                // verifica se existe botões adicionais, para colocar um separador após o imprimir
                if ($(".btnExt").length > 0) {
                    $(".dt-button-collection").find('.btnImprimir').after("<div class='boxOpcoes-separador'></div>");
                }

                // Adiciona Tooltip no Botões
                $(".btnCopy").attr('data-tippy-content', 'Copiar para a área de transferência');
                $(".btnCsv").attr('data-tippy-content', 'Exportar em CSV');
                $(".btnExcel").attr('data-tippy-content', 'Exportar em XLSX (Excel)');
                $(".btnPdf").attr('data-tippy-content', 'Exportar em PDF');
                $(".btnMarcarLinhas").attr('data-tippy-content', 'Marcar ou Desmarcar todas as linhas');
                $(".btnImprimir").attr('data-tippy-content', 'Imprimir todas as linhas ou as linhas selecionadas');
                $(".btnRefresh").attr('data-tippy-content', 'Atualizar Dados');

                caixasCriadas.push(ariaControls);

                appFunctions.tooltip();
            });
        },
        getLanguage: () => {
            /**
             * Informa a Tradução para PT-BR das Legendas do DataTables
             */
            return {
                "sEmptyTable": "Nenhum registro encontrado",
                "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                "sInfoPostFix": "",
                "sInfoThousands": ".",
                "sLengthMenu": "_MENU_ resultados por página",
                "sLoadingRecords": "Carregando...",
                "sProcessing": "Processando...",
                "sZeroRecords": "Nenhum registro encontrado",
                "sSearch": "Pesquisar",
                "oPaginate": {
                    "sNext": "Próximo",
                    "sPrevious": "Anterior",
                    "sFirst": "Primeiro",
                    "sLast": "Último"
                },
                "oAria": {
                    "sSortAscending": ": Ordenar colunas de forma ascendente",
                    "sSortDescending": ": Ordenar colunas de forma descendente"
                },
                "select": {
                    "rows": {
                        "_": "Selecionado %d linhas",
                        "0": "Nenhuma linha selecionada",
                        "1": "Selecionado 1 linha"
                    }
                },
                "buttons": {
                    "copy": "Copiar para a área de transferência",
                    "copyTitle": "Cópia bem sucedida",
                    "copySuccess": {
                        "1": "Uma linha copiada com sucesso",
                        "_": "%d linhas copiadas com sucesso"
                    }
                }
            }
        },
        mountColumnButtons: (oSettings, montarBtn, positionBtn) => {
            /**
             * Disponibiliza a Coluna de Opções na Tabela para Inserir os Botões
             */
            let thHeaderElement = $(oSettings.nTHead).find('tr').find('th');
            let thFooterElement = $("#" + $(oSettings.nTable).attr('id')).children('tfoot').find('tr').find('th');
            if (montarBtn) {
                // verifca se todas as colunas estão sendo exibidas
                if (thHeaderElement.length != positionBtn + 1) {
                    // utiliza a suposição de que a coluna de botões é a ultima, então coloca na ultima posição
                    thHeaderElement.eq(thHeaderElement.length - 1).addClass('selectorBtnCol');
                } else {
                    // utiliza a posição informada pela configuração
                    thHeaderElement.eq(positionBtn).addClass('selectorBtnCol');
                }
            } else {
                // se o Btn Montar for Falso, remove a Coluna de Opções
                thHeaderElement.eq(positionBtn).hide(); // Remove a Header
                thFooterElement.eq(positionBtn).hide(); // Remove a Footer

                $.each($(oSettings.nTBody).find('tr'), function(key, value) {
                    $(value).find('td').eq(positionBtn).hide(); // Remove a Coluna da Row
                });
            }
        },
        replaceValueSearch: (oSettings, valorPesquisa) => {
            let novoValor = valorPesquisa;
            oSettings.aoColumns.forEach(
                (el) => {
                    if (el.isreplace) {
                        novoValor = valorPesquisa.replace(/[^0-9]/g, '');
                    }
                }
            );
            return novoValor;
        },
        alterFieldSearch: (oSettings) => {
            /**
             * Realiza Adaptações no Campo de Pesquisa do DataTables
             */
            $(".dataTables_filter input").on('keypress', (e) => {
                if (e.keyCode == 13) {
                    let valorPesquisa = dataGridGlobalFunctions.replaceValueSearch(oSettings, $("#searchValueDataTables").val());
                    $('#' + $(oSettings.nTable).attr('id')).DataTable().search(valorPesquisa).draw();
                    e.preventDefault();
                }
            });
        },
        mountOptionButtons: (settings, mapeamentoObj) => {
            /**
             * Adiciona Botões na Box de Opções do DataTables
             */
            let newButtons = {
                background: true,
                text: "Opções",
                extend: "collection",
                className: 'btnOpcoes',
                buttons: [{
                        extend: 'columnsToggle',
                        columns: mapeamentoObj['pdf_columns']
                    },
                    {
                        extend: 'copy',
                        text: '<i class="fas fa-lg fa-clipboard"></i>',
                        className: 'btnExports btnCopy',
                        exportOptions: {
                            columns: ':visible:not(.selectorBtnCol)'
                        },
                        key: {
                            key: 'c',
                            altKey: true,
                            shiftKey: true
                        }
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fas fa-file-alt"</i>',
                        className: 'btnExports btnCsv',
                        exportOptions: {
                            columns: ':visible:not(.selectorBtnCol)'
                        },
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-lg fa-file-excel"></i>',
                        className: 'btnExports btnExcel',
                        exportOptions: {
                            columns: ':visible:not(.selectorBtnCol)'
                        },
                    },
                    {
                        extend: "pdfHtml5",
                        text: '<i class="fas fa-file-pdf"</i>',
                        className: 'btnExports btnPdf',
                        orientation: "landscape",
                        exportOptions: {
                            columns: ':visible:not(.selectorBtnCol)'
                        },
                        customize: function(doc) {
                            // modifica a tabela para 100%
                            var colCount = new Array();
                            for (var i = 1; i <= doc.content[1].table.body[0].length; i++) {
                                colCount.push('*');
                            }
                            doc.content[1].table.widths = colCount;
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-lg fa-print"></i> Imprimir',
                        className: 'btnFull btnImprimir',
                        exportOptions: {
                            columns: ':visible:not(.selectorBtnCol)'
                        },
                        key: {
                            key: 'p',
                            shiftKey: true
                        },
                    },
                    {
                        text: '<i class="fas fa-lg fa-sync"></i> Atualizar',
                        className: 'btnFull btnRefresh',
                        action: (e, dt, node, config) => {
                            dt.ajax.reload();
                        }
                    }
                ]
            };

            settings.buttons.push(newButtons);

            // Verifica Quais Colunas Foi setado para Desabilitar a Ordenação
            if (typeof mapeamentoObj['disable_order_by'] != 'undefined' && mapeamentoObj['disable_order_by'].length > 0) {
                $.each(mapeamentoObj['disable_order_by'], (i, value) => {
                    settings.aoColumnDefs.push({
                        bSortable: false,
                        aTargets: value
                    });
                });
            }

            // Verifica se a Opção de Selecionar Varias Linhas esta Habilitada
            if (mapeamentoObj['multiple_select']) {
                settings.buttons[0].buttons.push({
                    extend: "selectAll",
                    text: "Marcar Todos",
                    className: 'btnExt btnMarcarLinhas btnSelectAll'
                });

                settings.buttons[0].buttons.push({
                    extend: "selectNone",
                    text: "Desmarcar Todos",
                    className: 'btnExt btnMarcarLinhas btnSelectAll'
                });
            }

            // Adiciona Botões Personalizados na Toolbar Dinamicamente, Declarados Diretamente no Mapeamento
            if (mapeamentoObj['toolbar_buttons'].length > 0) {
                $.each(mapeamentoObj['toolbar_buttons'], (i, obj) => {
                    settings.buttons[0].buttons.push({
                        text: obj.text,
                        action: obj.onClick,
                        className: 'btnExt'
                    });
                });
            }

            appFunctions.tooltip();

        },
        createTableFooter: (nameRoute = ROUTE) => {
            $.each($('table'), (index, element) => {
                // Se ja existir um TFOOT remove ele
                if ($(element).find('tfoot').length) {
                    $(element).find('tfoot').remove()
                };
                // Adiciona o TFOOT com uma TR
                $(element).append('<tfoot></tfoot>');
                $(element).children('tfoot').html('')
                $(element).children('tfoot').append('<tr></tr>');
                $(element).children('tfoot').find('tr').html(''); // Limpa o que tiver no Footer

                // Percorre as colunas e Insere
                mapeamento[index][nameRoute]['columns'].forEach(
                    (el) => {
                        $(element).children('tfoot').find('tr').append("<th>" + el.title + "</th>");
                    }
                );
            });
        },
        createExpandRow: (_this, html) => {
            /**
             *  Adiciona a Função de Exandir uma Linha e Incluir conteudo nela
             *  @param _this
             *  @param html
             */
            let tr = $(_this).parent('td').closest('tr');
            let row = $(_this).parents('table').DataTable().row(tr);

            // Verifica se é para Expandir ou Comprimir
            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            } else {
                row.child(html).show();
                tr.addClass('shown');
            }
        },
    };

    document.addEventListener("DOMContentLoaded", () => {
        dataGridGlobalFunctions.init()
        dataGridOptionsFunctions.init()
    });
</script>
