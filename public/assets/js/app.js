const appFunctions = {
    init: () => {
        appFunctions.enableSelect2();
        appFunctions.tooltip();
        // appFunctions.initStorageGetTabs();
        appFunctions.btnSubmitDisable();
        appFunctions.fixTooltipSelect2();
        appFunctions.addInputLabelRequired();
        moment.locale('pt-BR'); // Define Idioma do MomentJs
    },
    enableSelect2: () => $("[data-selectTwo='true']").select2(),
    showLoader: () => $('.loader').show(),
    hideLoader: () => $('.loader').hide(300),
    tinyInstance: () => {
		tinymce.init({
			selector: '.selector-text',
			language: 'pt_BR',
			menubar: false,
			statusbar: false,
			height: '30vh',
			plugins: "advlist lists wordcount",
			toolbar: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | hr | numlist bullist",
		});
	},
    tooltip: () => {
		tippy('[data-tippy-content]', { animation: 'fade', allowHTML: true });
    },
    addInputLabelRequired: () => {
        $("[required]").each(function (index) {
            let labelElement = $(this).parent().find('label');
            let labelText = '';

            // Se o Label contiver a palavra * e for diferente de Vazio
            if (labelElement.text().indexOf("*") != -1 && labelElement.text() != '') { } else {
                labelText = labelElement.text() + ' *';
                labelElement.text(labelText);
				labelElement.attr("data-tippy-content", "Campo Obrigatório");
				appFunctions.tooltip();
			}

        });
    },
    fixTooltipSelect2: () => {
        $(".select2-container").tooltip({
            trigger: 'hover',
            title: function () {
                return $(this).siblings('input').attr("data-original-title");
            }
		});
    },
    validaNavegador: () => {
		var navegadorValido = false;

        if (navigator.userAgent.toLowerCase().indexOf('firefox') > 0)
            navegadorValido = false;

		if (navigator.userAgent.toLowerCase().indexOf('safari') > 0)
            navegadorValido = false;

		if (navigator.userAgent.toLowerCase().indexOf('chrome') > 0)
            navegadorValido = true;

        if (navigator.userAgent.toLowerCase().indexOf('mobile') > 0)
            navegadorValido = true;

        if (!navegadorValido) {
            notificationFunctions.alertPopup('warning', 'Seu navegador não é homologado para este sistema, por favor utilize o Google Chrome.', 'Atenção').then(
                (res) => {
                    if (res.value) {
                        window.location = 'https://www.google.com/chrome';
                    }
                }
            )
            return;
        }
    },
    initStorageGetTabs: () => {
        // localStorage.setItem('tab_<?= $this->router->fetch_class().$this->router->fetch_method(); ?>', $(this).attr('href'));

        // if (localStorage.getItem('tab_<?= $this->router->fetch_class().$this->router->fetch_method(); ?>')) {
        //     $('.nav-tabs').find('[href="' + localStorage.getItem('tab_<?= $this->router->fetch_class().$this->router->fetch_method(); ?>') + '"]').click();
        // }
    },
    btnSubmitDisable: () => {
        // Bloqueia o Botão de Submit por 3 segundos ao Enviar o Form
        $('form').on('submit', function () {
            if ($(this).hasClass('is-loading')) {
                var button = $(this).find('[type="submit"]');
                var old_val = button.val() || button.text();
                var time = 3; // segundos
                var tmpTime = time;
                button.prop('disabled', true);
                button.html("Aguarde... " + tmpTime);
                button.text("Aguarde... " + tmpTime);
                button.val("Aguarde... " + tmpTime);
                tmpTime--;
                var interval = setInterval(function () {
                    button.html("Aguarde... " + tmpTime);
                    button.text("Aguarde... " + tmpTime);
                    button.val("Aguarde... " + tmpTime);
                    if (tmpTime <= 0) {
                        clearInterval(interval);
                        button.html(old_val);
                        button.text(old_val);
                        button.val(old_val);
                        button.prop('disabled', false);
                    }
                    tmpTime--;
                }, 1000);
            }
        });
    },
    copyToClipboard: (text) => {
        var textArea = document.createElement("textarea");
        textArea.value = text ? text : ''; // Atribui a Mensagem

        // Esconde o Elemento para não aparecer ao Usuário
        textArea.style.top = "0";
        textArea.style.left = "0";
        textArea.style.position = "fixed";

        document.body.appendChild(textArea); // Atribui o Elemento ao Documento
        textArea.focus();
        textArea.select();
        document.execCommand('copy'); // Comando Copiar
        document.body.removeChild(textArea); //Remove o Elemento do Documento
        notificationFunctions.toastSmall('info', 'Copiado!');
    },
    backendCall: (metodo = 'GET', path = '', params = {}, showLoading = true, options = { processData: true, contentType: "application/x-www-form-urlencoded; charset=UTF-8", cache: false }) => {
        return new Promise((resolve, reject) => {
            if (path != '') {

                if (metodo == 'POST') {
                    params.csrf_test_name = getCookie('csrf_cookie_name');
                }

                $.ajax({
                    url: `${BASEURL}/${path}`,
                    method: metodo,
                    dataType: 'json',
					data: params,
					processData: options.processData,
					contentType: options.contentType,
					cache: options.cache,
					mimeType: "multipart/form-data",
                    beforeSend: () => showLoading ? appFunctions.showLoader() : ''
                }).done((result, textStatus, xhr) => {
                    result.textStatus = textStatus;
                    result.statusCode = xhr.status;
                    resolve(result);
                    showLoading ? appFunctions.hideLoader() : '';
                }).fail((error) => {
                    error.textStatus = 'error';
                    error.statusCodigo = error.status;
                    error.mensagem = error.responseJSON.mensagem;
                    showLoading ? appFunctions.hideLoader() : '';
                    reject(error);
                });
            } else {
                showLoading ? appFunctions.hideLoader() : '';
                reject('A Url passada não é válida!');
            }
        });
	},
	createShortUrl: async (longUrl) => {
		const res = await appFunctions.backendCall("POST", `${CONTROLLER}/createShortUrl/`, { "longUrl": longUrl });
		return res;
    },
    calculaValor: (valor, valor_juros, valor_desconto, valor_acrescimo = 0) => {
        return (parseFloat(valor) - parseFloat(valor_desconto)) + parseFloat(valor_juros) + parseFloat(valor_acrescimo);
    },
    numberFormat: (number, decimals, dec_point, thousands_sep) => {
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function (n, prec) {
                var k = Math.pow(10, prec);
                return '' + (Math.round(n * k) / k).toFixed(prec);
            };
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    },
    imageToPrint: (source) => {
        return "<html><head><scri" + "pt>function step1(){\n" +
            "setTimeout('step2()', 10);}\n" +
            "function step2(){window.print();window.close()}\n" +
            "</scri" + "pt></head><body style='padding:5px;width:100%' onload='step1()'>\n" +
            "<img style='width:100%' src='" + source + "' /></body></html>";
	},
	viewToPrint: (urlFunction) => {
		let iframe = document.createElement('iframe');
		document.body.appendChild(iframe);
		iframe.style.width = '0px';
		iframe.style.height = '0px';
		iframe.onload = function() {
			setTimeout(function() {
				iframe.focus();
				iframe.contentWindow.print();
			}, 1);
		};
		iframe.src = `${urlFunction}`;
	},
    disabledFields: (condition) => {
        $("input, select, textarea").prop(condition, true);
	},
	orderBy: (listArray) => {
		return listArray.sort((a,b) => (a.trim().toUpperCase() < b.trim().toUpperCase()) ? -1 : ((a.trim().toUpperCase() > b.trim().toUpperCase()) ? 1 : 0));
	},
	orderByField: (listArray, fieldName = '') => {
		if (fieldName == '') return [];
		return listArray.sort((a,b) => (a[fieldName].trim().toUpperCase() < b[fieldName].trim().toUpperCase()) ? -1 : ((a[fieldName].trim().toUpperCase() > b[fieldName].trim().toUpperCase()) ? 1 : 0));
	},
    buscarCep: (cep) => {
        let baseUrl = 'https://brasilapi.com.br/api/cep/v1/';
        return new Promise((resolve, reject) => {
            $.ajax({
                url: `${baseUrl}${cep}`,
                method: 'GET',
                dataType: 'json',
                data: {},
                processData: true,
                contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                cache: false,
                beforeSend: () => appFunctions.showLoader(),
            }).done((result) => {
                resolve(result);
                appFunctions.hideLoader();
            }).fail((error) => {
                appFunctions.hideLoader();
                if (error.responseJSON.name == "CepPromiseError") {
                    notificationFunctions.toastSmall("error", "CEP inválido!");
                }
            });
        });
    },
    formatName: (name, onlyFirstName = false) => {
        if(!name) return name;

        // Deixa apenas Letras
        name = name.replace(/[0-9]/g,'');

        // Quebra a string nos espaços
        let nomes = name.split(" ");

        // Remove os espaços vazio
        nomes = nomes.filter(el => el !== '');
        // Quantidade de nomes que a pessoa tem
        let qtdeNomes = nomes.length;

        // Primeiro Nome da Pessoa
        let nome = nomes[0];

        // Se for solicitado apenas o primeiro nome, já retorna
        if (onlyFirstName) {
            return nome;
        }

        // Percorre os sobrenomes
        let sobrenomes = '';
        for (let i = 1; i < (qtdeNomes - 1); i++) {
            if (qtdeNomes > 2) {
                sobrenomes = sobrenomes += nomes[i][0] + '. ';
            } else {
                sobrenomes = sobrenomes += nomes[i] + ' ';
            }
        }

        let ultimoNome =nomes[qtdeNomes - 1];
        return `${nome} ${sobrenomes} ${ultimoNome}`
	},
    getImageBase64: (diretorio) => {
        return $.ajax({
            url: `${BASEURL}/getImagem/${encodeURIComponent(diretorio)}`,
            method: 'GET',
            cache: false,
        }).done(res => res);
    },
};


// função .replaceAll() para trocar todas as ocorrencias
String.prototype.replaceAll = function (search, replace) {
    return this.replace(new RegExp(`[${search}]`, 'g'), replace);
};

// função .replaceArray() para trocar todas as ocorrencias
String.prototype.replaceArray = function (find, replace) {
    var replaceString = this;
    var regex;
    for (var i = 0; i < find.length; i++) {
        regex = new RegExp(find[i], "g");
        replaceString = replaceString.replace(regex, replace[i]);
    }
    return replaceString;
};

window.getCookie = function(name) {
    var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    if (match) return match[2];
  }
