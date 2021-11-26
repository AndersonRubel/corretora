<script>
const select2ReservaFunctions = {
    init: () => {
        select2ReservaFunctions.buscarCliente();
        select2ReservaFunctions.buscarImovel();
    },
    buscarCliente: (caller) => {
        let elementSelect2 = $("[data-select='buscarCliente']");
        let url = `${BASEURL}/cliente/backendCall/selectCliente`;
        elementSelect2.select2({
            placeholder: "Selecione...",
            allowClear: false,
            multiple: false,
            quietMillis: 2000,
            initSelection: function(element, callback) {
                $.ajax({
                    url: url,
                    dataType: "json",
                    type: 'POST',
                    params: {
                        contentType: "application/json; charset=utf-8",
                    },
                    data: {
                        termo: $(element).val(),
                        page: 1
                    },
                    success: (data) => callback(data.itens[0])
                })
            },
            ajax: {
                url: url,
                dataType: 'json',
                type: 'POST',
                data: (term, page) => {
                    return {
                        termo: term,
                        page: page,
                    };
                },
                results: (data, page) => {
                    if (page == 1) {
                        $(elementSelect2).data('count', data.count);
                    }
                    return {
                        results: data.itens,
                        more: (page * 30) < $(elementSelect2).data('count')
                    };
                }
            },
            formatResult: (data) => data.text,
            formatSelection: (data) => data.text
        });
    },
    buscarImovel: (caller) => {
        let elementSelect2 = $("[data-select='buscarImovel']");
        let url = `${BASEURL}/imovel/backendCall/selectImovel`;
        elementSelect2.select2({
            placeholder: "Selecione...",
            allowClear: false,
            multiple: false,
            quietMillis: 2000,
            initSelection: function(element, callback) {
                $.ajax({
                    url: url,
                    dataType: "json",
                    type: 'POST',
                    params: {
                        contentType: "application/json; charset=utf-8",
                    },
                    data: {
                        termo: $(element).val(),
                        page: 1
                    },
                    success: (data) => callback(data.itens[0])
                })
            },
            ajax: {
                url: url,
                dataType: 'json',
                type: 'POST',
                data: (term, page) => {
                    return {
                        termo: term,
                        page: page,
                    };
                },
                results: (data, page) => {
                    if (page == 1) {
                        $(elementSelect2).data('count', data.count);
                    }
                    return {
                        results: data.itens,
                        more: (page * 30) < $(elementSelect2).data('count')
                    };
                }
            },
            formatResult: (data) => data.text,
            formatSelection: (data) => data.text
        });
    },
};

const reservaFunctions = {
    init: () => {
        reservaFunctions.nomefunc();
    },
    nomefunc: () => {},
};

const dataGridGrupoFunctions = {
    init: () => {
        dataGridGrupoFunctions.mapeamentoGrupo();

        if (METODO == 'index') {
            $('#tableAtivos').DataTable(dataGridGlobalFunctions.getSettings(0));
            $('#tableInativos').DataTable(dataGridGlobalFunctions.getSettings(1));
        }
    },
    mapeamentoGrupo: () => {
        // Ativos
        mapeamento[0] = [];
        mapeamento[0][ROUTE] = [];
        mapeamento[0][ROUTE]['id_column'] = `uuid_reserva`;
        mapeamento[0][ROUTE]['ajax_url'] = `${BASEURL}/reserva/getDataGrid/1`;
        mapeamento[0][ROUTE]['order_by'] = [{
            "coluna": 1,
            "metodo": "ASC"
        }];
        mapeamento[0][ROUTE]['columns'] = [{
                "data": "codigo_reserva",
                "title": "Código"
            },
            {
                "data": "codigo_referencia",
                "title": "Referência"
            },
            {
                "data": "data_inicio",
                "title": "Data Início"
            },
            {
                "data": "data_fim",
                "title": "Data Fim"
            },
            {
                "data": "criado_em",
                "visible": false,
                "title": "Criado em"
            },
            {
                "data": "alterado_em",
                "visible": false,
                "title": "Alterado em"
            },
            {
                "data": "uuid_reserva",
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
        mapeamento[1][ROUTE]['id_column'] = `uuid_reserva`;
        mapeamento[1][ROUTE]['ajax_url'] = `${BASEURL}/reserva/getDataGrid/0`;
        mapeamento[1][ROUTE]['order_by'] = [{
            "coluna": 1,
            "metodo": "ASC"
        }];
        mapeamento[1][ROUTE]['columns'] = [{
                "data": "codigo_reserva",
                "title": "Código"
            },
            {
                "data": "codigo_referencia",
                "title": "Referência"
            },
            {
                "data": "data_inicio",
                "title": "Data Início"
            },
            {
                "data": "data_fim",
                "title": "Data Fim"
            },
            {
                "data": "criado_em",
                "visible": false,
                "title": "Criado em"
            },
            {
                "data": "alterado_em",
                "visible": false,
                "title": "Alterado em"
            },
            {
                "data": "inativado_em",
                "visible": false,
                "title": "Inativado em"
            },
            {
                "data": "uuid_reserva",
                "title": "Ações",
                "className": "text-center"
            }
        ];
        mapeamento[1][ROUTE]['btn_montar'] = true;
        mapeamento[1][ROUTE]['btn'] = [{
            "funcao": "ativar",
            "metodo": "",
            "compare": null
        }, ];
    },
}

document.addEventListener("DOMContentLoaded", () => {
    select2ReservaFunctions.init();
    reservaFunctions.init();
    dataGridGrupoFunctions.init();
});
</script>
