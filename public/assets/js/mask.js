const maskFunctions = {
    init: () => {
        maskFunctions.verificaNumero();
        maskFunctions.verificaNumeroPonto();
        maskFunctions.data();
        maskFunctions.hora();
        maskFunctions.dataHora();
        maskFunctions.telefone();
        maskFunctions.celular();
        maskFunctions.cpf();
        maskFunctions.cnpj();
        maskFunctions.cep();
        maskFunctions.percentual();
        maskFunctions.dinheiro();
        maskFunctions.numerico();
        maskFunctions.numericoPercentual();
        maskFunctions.telefoneCelular();
        maskFunctions.cnpjCpf();
    },
    data: () => {
        $('[data-mask="data"]').attr('data-verificaNumero', true);
        $('[data-mask="data"]').mask('00/00/0000')
    },
    hora: () => {
        $('[data-mask="hora"]').attr('data-verificaNumero', true);
        $('[data-mask="hora"]').mask('00:00')
    },
    dataHora: () => {
        $('[data-mask="dataHora"]').attr('data-verificaNumero', true);
        $('[data-mask="dataHora"]').mask('00/00/0000 00:00')
    },
    telefone: () => {
        $('[data-mask="telefone"]').attr('data-verificaNumero', true);
        $('[data-mask="telefone"]').mask("(99) 9999-9999").focusout(function (event) {
            let target = (event.currentTarget) ? event.currentTarget : event.srcElement;
            if ($(target).val().length != 14 && $(target).val() != '') {
                $(this).focus()
                notificationFunctions.toastSmall('error', 'Complete o telefone corretamente');
            }
        });
    },
    celular: () => {
        $('[data-mask="celular"]').attr('data-verificaNumero', true);
        $('[data-mask="celular"]').mask("(99) 9999-9999#").focusout(function (event) {
            let target = (event.currentTarget) ? event.currentTarget : event.srcElement;
            if ($(target).val().length == 15) {
                $(target).mask('(99) 99999-9999');
            } else {
                $(target).mask('(99) 9999-9999');
            }
            if ($(target).val().length < 14 && $(target).val() != '') {
                $(this).focus()
                notificationFunctions.toastSmall('error', 'Complete o telefone celular corretamente');
            }
        });
    },
    cpf: () => {
        $('[data-mask="cpf"]').attr('data-verificaNumero', true);
        $('[data-mask="cpf"]').mask('999.999.999-99').focusout(function (event) {
            let target = (event.currentTarget) ? event.currentTarget : event.srcElement;
            if ($(target).val().length == 14 && $(target).val() != '') {
                if (!validFunctions.cpf($(target).val())) {
                    $(this).focus()
                    notificationFunctions.toastSmall('error', 'CPF inválido');
                }
            }
        })
    },
    cnpj: () => {
        $('[data-mask="cnpj"]').attr('data-verificaNumero', true);
        $('[data-mask="cnpj"]').mask('99.999.999/9999-99').focusout(function (event) {
            let target = (event.currentTarget) ? event.currentTarget : event.srcElement;
            if ($(target).val().length == 18 && $(target).val() != '') {
                if (!validFunctions.cnpj($(target).val())) {
                    $(this).focus()
                    notificationFunctions.toastSmall('error', 'CNPJ inválido');
                }
            }
        })
    },
    cep: () => {
        $('[data-mask="cep"]').attr('data-verificaNumero', true);
        $('[data-mask="cep"]').mask('99999-999')
    },
    percentual: () => {
        $('[data-mask="percentual"]').attr('data-verificaNumero', true);
        $('[data-mask="percentual"]').mask('##0,00%', { reverse: true })
    },
    dinheiro: () => $('[data-mask="dinheiro"]').maskMoney({ allowZero: true, showSymbol: true, symbol: "R$ ", decimal: ",", thousands: "." }),
    numerico: () => $('[data-mask="numerico"]').maskMoney({ allowZero: true, precision: 4, showSymbol: true, symbol: "", decimal: ",", thousands: "." }),
    numericoPercentual: () => $('[data-mask="numericoPercentual"]').maskMoney({ allowZero: true, precision: 2, showSymbol: true, symbol: "", decimal: ".", thousands: "" }),
    telefoneCelular: () => {
        $('[data-mask="telefoneCelular"]').attr('data-verificaNumero', true);
        $('[data-mask="telefoneCelular"]').mask("(00) 0000-0000#").focusout(function (event) {
            let target, phone, element;
            target = (event.currentTarget) ? event.currentTarget : event.srcElement;
            phone = target.value.replace(/\D/g, '');
            element = $(target);
            element.unmask();
            if (phone.length > 10) {
                element.mask('(00) 00000-000#');
            } else {
                element.mask('(00) 0000-0000#');
            }
        });
    },
    cnpjCpf: () => {
        $('[data-mask="cnpjCpf"]').attr('data-verificaNumero', true);
        $('[data-mask="cnpjCpf"]').mask("999.999.999-99#").keydown(function (event) {
            let value = $(this).val();
            value = value.replace(/\D/g, "");
            $(this).unmask();
            if (value.length > 11) {
                $(this).mask('99.999.999/9999-99');
            } else {
                $(this).mask('999.999.999-99#');
            }

            // ajustando foco
            var elem = this;
            setTimeout(function () {
                // mudo a posição do seletor
                elem.selectionStart = elem.selectionEnd = 10000;
            }, 0);
            // reaplico o valor para mudar o foco
            var currentValue = $(this).val();
            $(this).val('');
            $(this).val(currentValue);
        });
    },
    cpfCnpj: (value) => {
        //Remove tudo o que não é dígito
        value = value.replace(/\D/g, "")
        if (value.length <= 11) { //CPF
            //Coloca um ponto entre o terceiro e o quarto dígitos
            value = value.replace(/(\d{3})(\d)/, "$1.$2")
            //Coloca um ponto entre o terceiro e o quarto dígitos
            //de novo (para o segundo bloco de números)
            value = value.replace(/(\d{3})(\d)/, "$1.$2")
            //Coloca um hífen entre o terceiro e o quarto dígitos
            value = value.replace(/(\d{3})(\d{1,2})$/, "$1-$2")
        } else { //CNPJ
            //Coloca ponto entre o segundo e o terceiro dígitos
            value = value.replace(/^(\d{2})(\d)/, "$1.$2")
            //Coloca ponto entre o quinto e o sexto dígitos
            value = value.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3")
            //Coloca uma barra entre o oitavo e o nono dígitos
            value = value.replace(/\.(\d{3})(\d)/, ".$1/$2")
            //Coloca um hífen depois do bloco de quatro dígitos
            value = value.replace(/(\d{4})(\d)/, "$1-$2")
        }
        return value
    },
    verificaNumero: () => {
        // Valida O Campo Para Apenas Números
        $(document).on('keypress', '[data-verificaNumero="true"]', function (e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                e.preventDefault();
            }
        });
    },
    verificaNumeroPonto: () => {
        // Troca a Virgula por ponto do campo
        $(document).on('keyup', '[data-verificanumeroponto="true"]', function (e) {
            if (e.which == 46) {
                if ($(this).val().indexOf('.') != -1) {
                    e.preventDefault();
                }
                if ($(this).val().length == 0) {
                    $(this).val(0);
                }
            }
            // troca a virgula por ponto
            if (e.which == 44) {
                if ($(this).val().indexOf('.') != -1) {
                    e.preventDefault();
                } else if ($(this).val().length == 0) {
                    $(this).val(0 + '.');
                } else {
                    $(this).val($(this).val() + '.');
                }
            }
            if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
                e.preventDefault();
                return false;
            }
            if (typeof $(this).attr('maxlength') != 'undefined') {
                if (parseInt($(this).val().length) >= parseInt($(this).attr('maxlength'))) {
                    e.preventDefault();
                    return false;
                }
            }
        });
    },
}
