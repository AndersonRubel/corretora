<script>
    const CONFIG_CRUD_DINAMICO = JSON.parse(`<?= json_encode(!empty($configCrudDinamico) ? $configCrudDinamico : []); ?>`); // Recebe as Configurações do Crud Dinâmico

    const cadastroFunctions = {
        init: () => {
            cadastroFunctions.dataVerify();
            cadastroFunctions.select2();
            cadastroFunctions.confirmDesativar();
            cadastroFunctions.confirmAtivar();
            cadastroFunctions.confirmExcluir();
            cadastroFunctions.confirmCopiar();
            cadastroFunctions.dataTableCounter();
        },
        dataVerify: () => {
            $(document).on('change', '[data-verify="true"]', function() {
                $.ajax({
                    url: `${BASEURL}/cadastro/${METODO}`,
                    dataType: 'json',
                    context: this,
                    data: {
                        field: $(this).attr('name'),
                        value: $(this).val(),
                        custom_data: {
                            type: 'verify-field',
                        }
                    },
                    success: function(data) {
                        $(this).parent().find('small').remove();
                        if (data.exists) {
                            notificationFunctions.toastSmall('error', `${$(this).siblings('label').text()}já cadastrado.`);

                            if ($(this).data('verify-clear')) {
                                $(this).val('');
                            }
                        }
                    }
                });
            });
        },
        select2: () => {

            // Ajusta o Index dos Select2
            $('.selector-select2').each(function() {
                $(this).data('row', $(this).closest('.row').index());
                $(this).data('element', $(this).closest('[class^="col-"]').index());
            });

            // Inicializa os Select2
            $('.selector-select2').select2({
                placeholder: "Buscar",
                minimumInputLength: 0,
                ajax: {
                    url: `${BASEURL}/cadastro/${METODO}`,
                    dataType: 'json',
                    context: this,
                    data: function(term, page) {
                        return {
                            q: term,
                            custom_data: {
                                type: 'select2',
                                row: $(this).data('row'),
                                element: $(this).data('element')
                            }
                        };
                    },
                    results: function(data, page) {
                        return {
                            results: data
                        };
                    }
                },
                initSelection: function(element, callback) {
                    $.ajax({
                        url: `${BASEURL}/cadastro/${METODO}`,
                        dataType: "json",
                        data: {
                            id: $(element).val(),
                            custom_data: {
                                type: 'select2',
                                row: $(element).data('row'),
                                element: $(element).data('element')
                            }
                        }
                    }).done(function(data) {
                        callback(data[0]);
                    });
                },
                formatResult: function(data) {
                    return data.text;
                },
                formatSelection: function(data) {
                    return data.text;
                }
            }).on('focus', function() {
                $(this).select2('open');
            });


        },
        doOperation: (handler) => {
            // Realiza a Requisição das Operações
            $.ajax({
                url: $(handler).data('url'),
                dataType: 'json',
                beforeSend: () => $('body').addClass('carregando'),
                error: (error) => $('body').removeClass('carregando'),
                success: function(data) {
                    if (data) {
                        notificationFunctions.toastSmall('success', 'Operação realizada com sucesso!')
                    } else {
                        notificationFunctions.toastSmall('error', ' Este registro não pode ser modificado!');
                    }
                    $('table').each(function() {
                        if ($.fn.DataTable.isDataTable(this)) {
                            $(this).DataTable().ajax.reload(null, false);
                        }
                    });
                }
            }).done(() => $('body').removeClass('carregando'));
        },
        confirmDesativar: () => {
            // Desativar Registro
            $(document).on('click', "[data-action='desativarRegistroCrud']", function(e) {
                let _this = this;
                notificationFunctions.popupConfirm('Atenção', 'Deseja realmente desativar o registro ?', 'warning').then(
                    (result) => {
                        if (result.value) {
                            cadastroFunctions.doOperation(_this);
                            e.preventDefault();
                        } else {
                            e.preventDefault();
                        }
                    }
                );
            });
        },
        confirmAtivar: () => {
            // Ativar Registro
            $(document).on('click', "[data-action='ativarRegistroCrud']", function(e) {
                let _this = this;
                notificationFunctions.popupConfirm('Atenção', 'Deseja realmente ativar o registro ?', 'warning').then(
                    (result) => {
                        if (result.value) {
                            cadastroFunctions.doOperation(_this);
                            e.preventDefault();
                        } else {
                            e.preventDefault();
                        }
                    }
                );
            });
        },
        confirmExcluir: () => {
            $(document).on('click', "[data-action='excluirRegistroCrud']", function(e) {
                let _this = this;
                notificationFunctions.popupConfirm('Atenção', 'Deseja realmente excluir o registro ?', 'warning').then(
                    (result) => {
                        if (result.value) {
                            cadastroFunctions.doOperation(_this);
                            e.preventDefault();
                        } else {
                            e.preventDefault();
                        }
                    }
                );
            });
        },
        confirmCopiar: () => {
            //  Copiar Registro
            $(document).on('click',"[data-action='copiarRegistroCrud']", function(e) {
                let _this = this;
                notificationFunctions.popupConfirm('Atenção', 'Deseja realmente copiar esse registro ?', 'warning').then(
                    (result) => {
                        if (result.value) {
                            cadastroFunctions.doOperation(_this);
                            e.preventDefault();
                        } else {
                            e.preventDefault();
                        }
                    }
                );
            });
        },
        dataTableCounter: () => {
            // evento responsável por montar o contador da DataTable Ativo
            $(document).on('draw.dt', '.selector-table-crud-ativos', function() {
                $(".selector-contador-0").html(' (' + $(this).dataTable().fnSettings().fnRecordsTotal() + ')');
            });

            // evento responsável por montar o contador da DataTable Inativo
            $(document).on('draw.dt', '.selector-table-crud-inativos', function() {
                $(".selector-contador-1").html(' (' + $(this).dataTable().fnSettings().fnRecordsTotal() + ')');
            });
        },
    };

    const cadastroDataGridFunctions = {
        init: () => {
            cadastroDataGridFunctions.inicializaTables();
        },
        inicializaTables: () => {
            CONFIG_CRUD_DINAMICO.forEach(
                (el, index) => {
                    let name = el.tab_name ? el.tab_name.replace(' ', '_') : '';
                    $(`.selector-table-crud-${name ? name.toLowerCase() : ''}`).DataTable(dataGridGlobalFunctions.getSettings(index, 'cadastro'));
            });
        }
    }

    // Executa Somente depois de Carregar o Documento
    $(document).ready(function() {
        cadastroFunctions.init();
        cadastroDataGridFunctions.init();
    });
</script>
