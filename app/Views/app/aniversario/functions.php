<script>
    const select2AniversarioFunctions = {
        init: () => {}
    };

    const aniversarioFunctions = {
        init: () => {
            aniversarioFunctions.listenerFiltroDataRapido();
            aniversarioFunctions.listenerRemoverRegistro();
            aniversarioFunctions.listenerTipoMensagem();
            aniversarioFunctions.listenerValidaOnSubmit();

            // Inicializa
            $("select[name='envia_sms'], select[name='envia_email']").change();
        },
        listenerFiltroDataRapido: () => {

            $(document).on('click', "[data-action='btnFiltroAniversariante']", function() {

                if ($.fn.DataTable.isDataTable("#tableAniversariantes")) {
                    $('#tableAniversariantes').DataTable().destroy();
                }

                mapeamento[0][ROUTE]['custom_data'] = [{
                    data_de: $("input[name='data_de']").val(),
                    data_ate: $("input[name='data_ate']").val()
                }];

                $('#tableAniversariantes').DataTable(dataGridGlobalFunctions.getSettings(0));
            });

            $(document).on('click', "[data-action='btnFiltroDataRapido']", function() {

                $("[data-action='btnFiltroDataRapido']").each((i, el) => {
                    $(el).removeClass('btn-success').addClass('btn-secondary');
                });

                $(this).addClass('btn-success').css({
                    color: 'white'
                });

                let dataDe = '';
                let dataAte = '';

                switch ($(this).text().toLocaleLowerCase()) {
                    case 'hoje':
                        dataDe = moment().format('YYYY-MM-DD');
                        dataAte = moment().format('YYYY-MM-DD');
                        break;
                    case 'semana':
                        dataDe = moment().day(0).format('YYYY-MM-DD');
                        dataAte = moment().day(6).format('YYYY-MM-DD');
                        break;
                    case 'mês':
                        dataDe = moment().format('YYYY-MM-01');
                        dataAte = moment().endOf('month').format('YYYY-MM-DD');
                        break;
                    case 'customizado':
                        dataDe = moment().format('YYYY-MM-01');
                        dataAte = moment().endOf('month').format('YYYY-MM-DD');
                        $("input[name='data_de']").focus();
                        break;

                    default:
                        break;
                }

                $("input[name='data_de']").val(dataDe);
                $("input[name='data_ate']").val(dataAte);

                // Chama o Filtro
                $("[data-action='btnFiltroAniversariante']").click();
            });

        },
        listenerRemoverRegistro: () => {
            $(document).on('click', "[data-action='excluirRegistroCustom']", function() {
                $(this).parent('td').parent('tr').remove();
            });
        },
        listenerTipoMensagem: () => {
            $(document).on('change', "select[name='envia_sms']", function() {
                if ($(this).val() == 't') {
                    $("#selectorMensagemSMS").removeClass('d-none');
                } else {
                    $("#selectorMensagemSMS").addClass('d-none');
                }
            });

            $(document).on('change', "select[name='envia_email']", function() {
                if ($(this).val() == 't') {
                    $("#selectorMensagemEmail").removeClass('d-none');
                } else {
                    $("#selectorMensagemEmail").addClass('d-none');
                }
            });
        },
        listenerValidaOnSubmit: () => {
            $(document).on('click', "[data-action='realizarSubmit']", async function(e) {
                // Realiza Validações antes de realizar o Submit do formulário

                // Se a tabela nao tiver registro avisa
                if (!$('#tableAniversariantes').DataTable().data().count()) {
                    e.preventDefault();
                    notificationFunctions.toastSmall('error', 'A listagem de envio não pode ser vazia.');
                    return;
                }

                // Se o campo de SMS for marcado como SIM, deve conter texto
                if ($("select[name='envia_sms']").val() == 't') {
                    if ($("textarea[name='mensagem_sms']").val() == '') {
                        e.preventDefault();
                        notificationFunctions.toastSmall('error', 'O campo de mensagem de SMS não pode ser vazio.');
                        return;
                    }
                }

                // Se o campo de EMAIL for marcado como SIM, deve conter texto
                if ($("select[name='envia_email']").val() == 't') {
                    if ($("textarea[name='mensagem_email']").val() == '') {
                        e.preventDefault();
                        notificationFunctions.toastSmall('error', 'O campo de mensagem de email não pode ser vazio.');
                        return;
                    }
                }

                // Se passar nas validações, adiciona os registros da listagem na requisição
                console.log($('#tableAniversariantes tbody tr'));
                e.preventDefault();
                return;
            });
        },
    };

    const dataGridAniversarioFunctions = {
        init: () => {
            dataGridAniversarioFunctions.mapeamentoAniversario();

            $("[data-action='btnFiltroAniversariante']").click();
        },
        mapeamentoAniversario: () => {
            // Ativos
            mapeamento[0] = [];
            mapeamento[0][ROUTE] = [];
            mapeamento[0][ROUTE]['id_column'] = `uuid`;
            mapeamento[0][ROUTE]['ajax_url'] = `${BASEURL}/aniversario/getDataGrid`;
            mapeamento[0][ROUTE]['order_by'] = [{
                "coluna": 0,
                "metodo": "ASC"
            }];
            mapeamento[0][ROUTE]['columns'] = [{
                    "data": "nome",
                    "title": "Nome"
                },
                {
                    "data": "data_nascimento",
                    "title": "Data do Aniversário",
                    "className": "text-center",
                },
                {
                    "data": "email",
                    "title": "Email",
                    "className": "text-center",
                },
                {
                    "data": "celular",
                    "title": "Celular",
                    "className": "text-center",
                    "isreplace": true,
                    "render": (data) => convertFunctions.intToPhone(data)
                },
                {
                    "data": "uuid",
                    "title": "Ações",
                    "className": "selectorBtnCol"
                }
            ];
            mapeamento[0][ROUTE]['btn_montar'] = true;
            mapeamento[0][ROUTE]['btn'] = [{
                "funcao": "excluirCustom",
                "metodo": "",
                "compare": null
            }, ];
        },
    };

    document.addEventListener("DOMContentLoaded", () => {
        aniversarioFunctions.init();
        select2AniversarioFunctions.init();
        dataGridAniversarioFunctions.init();
    });
</script>
