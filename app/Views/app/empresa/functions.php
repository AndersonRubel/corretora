<script>
    const empresaFunctions = {
        init: () => {
            empresaFunctions.listenerTipoPessoa();
            empresaFunctions.listenerInscricaoEstadual();
            empresaFunctions.listenerBuscarCepEmpresa();
            empresaFunctions.listenerBuscarCepResponsavel();

            // Atualiza os campos conforme a o tipo de pessoa
            $("#tipoPessoa").trigger('change');
        },
        listenerTipoPessoa: () => {
            $(document).on('change', "#tipoPessoa", function() {

                if ($(this).val() !== $('#tipoPessoa').val()) {
                    // Zera o valor dos campos
                    $("input[name='razao_social'], input[name='nome_fantasia'], input[name='cpf_cnpj']").val('');
                }

                if ($(this).val() == 1) {
                    // PESSOA FÍSICA

                    // Esconde o Campo de Razão Social, remove a obrigatoriedade
                    $("#razaoSocial").addClass('d-none').find('input').removeAttr('required');

                    // Troca a Label de Nome Fantasia para Nome
                    $("#nomeFantasia").find('label').text('Nome *');

                    // Troca a Label de CNPJ para CPF, e atualiza o tipo de mascara
                    $("#cpfCnpj").find('label').text('CPF *');

                    // Oculta o Bloco Fiscal
                    $("#blocoFiscal").addClass('d-none');

                    // Oculta o Bloco de Responsavel
                    $("#blocoResponsavel").find('input').removeAttr('required');
                    $("#blocoResponsavel").addClass('d-none');
                } else {
                    // PESSOA JURÍDICA

                    // Exibe o Campo de Razão Social, e adiciona a obrigatoriedade
                    $("#razaoSocial").removeClass('d-none').find('input').attr('required');
                    $("#nomeFantasia").find('label').text('Nome Fantasia *');

                    // Troca a Label de CPF para CNPJ, e atualiza o tipo de mascara
                    $("#cpfCnpj").find('label').text('CNPJ *');

                    // Exibe o Bloco Fiscal
                    $("#blocoFiscal").removeClass('d-none');

                    // Exibe o Bloco de Responsavel
                    $("#blocoResponsavel").removeClass('d-none');
                    $("#blocoResponsavel").find('input').attr('required');
                }

                maskFunctions.init();
            });
        },
        listenerInscricaoEstadual: () => {
            $(document).on('change', "select[name='possui_inscricao_estadual']", function() {
                // Zera o valor dos campos
                $("input[name='inscricao_estadual']").val('');

                if ($(this).val() == "sim") {
                    $("#fieldInscricaoEstadual").removeClass('d-none');
                    $("input[name='inscricao_estadual']").val('').attr('required');
                } else {
                    $("input[name='inscricao_estadual']").val('').removeAttr('required');;
                    $("#fieldInscricaoEstadual").addClass('d-none');
                }
            });
        },
        listenerBuscarCepEmpresa: () => {
            $(document).on('keyup', "input[name='cep']", function() {
                const cep = $(this).val();
                if (cep.length >= 9) {
                    appFunctions.buscarCep(cep).then(
                        (retorno) => {
                            if (retorno) {
                                $("input[name='rua']").val(retorno.street);
                                $("input[name='bairro']").val(retorno.neighborhood);
                                $("input[name='cidade']").val(retorno.city);
                                $("input[name='cidade_completa']").val(`${retorno.city}/${retorno.state}`);
                                $("input[name='uf']").val(retorno.state);
                                $("input[name='numero']").val('');
                                $("input[name='numero']").focus();
                            }
                        }
                    )
                }
            });
        },
        listenerBuscarCepResponsavel: () => {
            $(document).on('keyup', "input[name='responsavel_cep']", function() {
                const cep = $(this).val();
                if (cep.length >= 9) {
                    appFunctions.buscarCep(cep).then(
                        (retorno) => {
                            if (retorno) {
                                $("input[name='responsavel_rua']").val(retorno.street);
                                $("input[name='responsavel_bairro']").val(retorno.neighborhood);
                                $("input[name='responsavel_cidade']").val(retorno.city);
                                $("input[name='responsavel_cidade_completa']").val(`${retorno.city}/${retorno.state}`);
                                $("input[name='responsavel_uf']").val(retorno.state);
                                $("input[name='responsavel_numero']").val('');
                                $("input[name='responsavel_numero']").focus();
                            }
                        }
                    )
                }
            });
        },
    }

    const dataGridEmpresaFunctions = {
        init: () => {
            dataGridEmpresaFunctions.mapeamentoEmpresa();

            if (METODO == 'index') {
                $('#tableAtivos').DataTable(dataGridGlobalFunctions.getSettings(0));
                $('#tableInativos').DataTable(dataGridGlobalFunctions.getSettings(1));
            }
        },
        mapeamentoEmpresa: () => {
            // Ativos
            mapeamento[0] = [];
            mapeamento[0][ROUTE] = [];
            mapeamento[0][ROUTE]['id_column'] = `uuid_empresa`;
            mapeamento[0][ROUTE]['ajax_url'] = `${BASEURL}/empresa/getDataGrid/1`;
            mapeamento[0][ROUTE]['order_by'] = [{
                "coluna": 0,
                "metodo": "ASC"
            }];
            mapeamento[0][ROUTE]['columns'] = [{
                    "data": "nome",
                    "title": "Nome"
                },
                {
                    "data": "email",
                    "title": "Email"
                },
                {
                    "data": "telefone",
                    "title": "Telefone",
                    "isreplace": true,
                    "render": (data) => convertFunctions.intToPhone(data)
                },
                {
                    "data": "criado_em",
                    "visible": false,
                    "title": "Criado em"
                },
                {
                    "data": "usuario_criacao",
                    "visible": false,
                    "title": "Criado por"
                },
                {
                    "data": "alterado_em",
                    "visible": false,
                    "title": "Alterado em"
                },
                {
                    "data": "usuario_alteracao",
                    "visible": false,
                    "title": "Alterado por"
                },
                {
                    "data": "uuid_empresa",
                    "title": "Ações",
                    "className": "text-center"
                }
            ];
            mapeamento[0][ROUTE]['btn_montar'] = true;
            mapeamento[0][ROUTE]['btn'] = [{
                    "funcao": "editar",
                    "metodo": "alterar",
                    "compare": null
                },
                {
                    "funcao": "desativar",
                    "metodo": "",
                    "compare": null
                },
            ];

            // Inativos
            mapeamento[1] = [];
            mapeamento[1][ROUTE] = [];
            mapeamento[1][ROUTE]['id_column'] = `uuid_empresa`;
            mapeamento[1][ROUTE]['ajax_url'] = `${BASEURL}/empresa/getDataGrid/0`;
            mapeamento[1][ROUTE]['order_by'] = [{
                "coluna": 0,
                "metodo": "ASC"
            }];
            mapeamento[1][ROUTE]['columns'] = [{
                    "data": "nome",
                    "title": "Nome"
                },
                {
                    "data": "email",
                    "title": "Email"
                },
                {
                    "data": "telefone",
                    "title": "Telefone",
                    "isreplace": true,
                    "render": (data) => convertFunctions.intToPhone(data)
                },
                {
                    "data": "criado_em",
                    "visible": false,
                    "title": "Criado em"
                },
                {
                    "data": "usuario_criacao",
                    "visible": false,
                    "title": "Criado por"
                },
                {
                    "data": "alterado_em",
                    "visible": false,
                    "title": "Alterado em"
                },
                {
                    "data": "usuario_alteracao",
                    "visible": false,
                    "title": "Alterado por"
                },
                {
                    "data": "inativado_em",
                    "visible": false,
                    "title": "Inativado em"
                },
                {
                    "data": "usuario_inativacao",
                    "visible": false,
                    "title": "Inativado por"
                },
                {
                    "data": "uuid_empresa",
                    "title": "Ações",
                    "className": "text-center"
                }
            ];
            mapeamento[1][ROUTE]['btn_montar'] = true;
            mapeamento[1][ROUTE]['btn'] = [{
                "funcao": "ativar",
                "metodo": "",
                "compare": null
            }];
        },
    }

    document.addEventListener("DOMContentLoaded", () => {
        empresaFunctions.init();
        dataGridEmpresaFunctions.init();
    });
</script>
