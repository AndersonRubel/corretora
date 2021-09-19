const notificationFunctions = {
    init: () => {
        notificationFunctions.alertDefaultModal();
    },
    alertDefaultModal: () => {
        /////////////////////////// Inicio :: Altera o Alert Padrão por Modal Bootstrap ///////////////////////////
        $(function () {
            (function (w) {
                var alert = w.alert;
                var $template = $(`<div class="modal" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Aviso</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p></p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary nodisable" data-bs-dismiss="modal">Fechar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                        `);
                var $body = $template.find('.modal-body p');
                var $buttons = $template.find('.modal-dialog button[data-type]');

                var init = false;
                var actions = [];

                var currentAction = null;

                $buttons.on('click', function (e) {
                    $template.data('type', $(this).data('type')).modal('hide');
                });

                $template.on('show.bs.modal', function (e) {
                    currentAction = actions.shift();

                    if (currentAction) {
                        if (currentAction.text) {
                            $body.text(currentAction.text);
                        }
                    }
                });

                $template.on('shown.bs.modal', function (e) {
                    $template.find('.btn-primary').focus();
                });

                $template.on('hidden.bs.modal', function (e) {
                    init = false;

                    if (currentAction) {
                        if (currentAction.fn && $.isFunction(currentAction.fn)) {
                            currentAction.fn.call(null, {
                                type: $(this).data('type')
                            });
                        }
                    }

                    if (actions.length > 0) {
                        w.alert(null, null, true);
                    }
                });

                w.alert = function (text, fn, multiple) {
                    if (multiple == undefined) {
                        actions.push({
                            text: text,
                            fn: fn
                        });
                    }

                    if (init == false) {
                        $body.empty();

                        $template.data('type', undefined).modal('show');
                        init = true;
                    }
                };
            })(window);
        });
    },
    toastSmall: (icone, msg, timer = null) => {
        if (msg) {
            const Toast = swal.mixin({
                toast: true,
                position: 'top-end',
                width: '32rem',
                showConfirmButton: false,
                timer: timer ? timer : 5000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', swal.stopTimer)
                    toast.addEventListener('mouseleave', swal.resumeTimer)
                    toast.addEventListener('click', () => {
                        //Quando Clicado no Toast, é Copiado a Mensagem dele para a Area de Transferência
                        appFunctions.copyToClipboard(msg); //Header.php
                    })
                }
            })

            Toast.fire({icon: icone ? icone : 'info',title: msg ? msg : ''})
        }
    },
    alertPopup: (icone, msg, title, footer = '') => {
        return new Promise((resolve, reject) => {
            swal.fire({
                icon: icone ? icone : 'info',
                title: title ? title : 'Oops...',
                text: msg ? msg : 'Algo deu errado!',
                footer: footer ? footer : ''
            }).then((result) => {
                resolve(result);
            }).catch((error) => {
                reject(error);
            });
        });
    },
    popupConfirm: (title, text, icon) => {
        return new Promise((resolve, reject) => {
            swal.fire({
                title: title ? title : '',
                text: text ? text : '',
                icon: icon ? icon : 'info',
                showCancelButton: true,
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não',
                confirmButtonColor: '#2B9B59',
                cancelButtonColor: '#EF3D6D',
            }).then((result) => {
                resolve(result);
            }).catch((error) => {
                reject(error);
            });
        });
	},
	feedbackCustom: (icone = '', text = '', buttonText = 'CLIQUE AQUI PARA CONTINUAR') => {
		return new Promise((resolve, reject) => {
			let nowTime = new Date().getTime();
			let htmlModal = `
				<!-- Inicio :: Modal Feedback -->
				<div class="modal fade modal-feedback" id="modalFeedbackCustom_${nowTime}" tabindex="-1" role="dialog" aria-labelledby="modalFeedbackCustom_${nowTime}">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-body text-center">
								<h2>${text}</h2>
								${icone !== '' ? `<figure><object id="svg-object_${nowTime}" data="${BASEURL}assets/svg/${icone}" type="image/svg+xml"></object></figure>` : ''}
								<button type="button" class="primary-button success" data-dismiss="modal">${buttonText}</button>
							</div>
						</div>
					</div>
				</div>
				<!-- Fim :: Modal Feedback -->
			`;

			// Insere a Modal no Body
			let elModal = document.createElement("div");
			elModal.innerHTML = htmlModal;
			document.body.appendChild(elModal);


			// Devolve o Botão que aciona a Modal
			let btnAbreModal = `$("#modalFeedbackCustom_${nowTime}").modal('show');`;
			resolve(btnAbreModal);
		});
	}
}
