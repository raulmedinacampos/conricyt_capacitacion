/**
 * Script para login por medio de ventana modal
 */

function login() {
	$("#formLogin #btn_modal").click(function() {
		$.post('login/entrar',
				$("#formLogin").serialize(),
				function(data) {
					if ( data == 'ok' ) {
						window.location = "/acceso/verificarAcceso/"+$("#curso_modal").val();
					}
					
					if ( data == 'reg' ) {
						window.location = "/usuario";
					}
					
					if ( data == 'error' ) {
						$("#modalLogin .modal-body > p").removeClass("hide").addClass("show");
					}
				}
		);
	});
}

$(function() {
	login();
});