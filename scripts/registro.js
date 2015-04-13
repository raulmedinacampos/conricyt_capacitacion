// Muestra u oculta los campos para capturar el perfil o institución diferentes a los que aparecen las opciones
function visualizarCampos() {
	$(".entidad, .otro-perfil, .otra-institucion").css("display", "none");
	
	$("#pais").change(function() {
		var seleccion = $(this).children("option:selected");
		
		if(seleccion.text() == "México") {
			$(".entidad").css("display", "block");
		} else {
			$(".entidad").css("display", "none");
		}
	});
	
	$("#perfil").change(function() {
		var seleccion = $(this).children("option:selected");
		
		if(seleccion.text() == "Otro") {
			$(".otro-perfil").css("display", "block");
		} else {
			$(".otro-perfil").css("display", "none");
		}
	});
	
	$("#institucion").change(function() {
		var seleccion = $(this).children("option:selected");
		
		if(seleccion.text() == "Otra") {
			$(".otra-institucion").css("display", "block");
		} else {
			$(".otra-institucion").css("display", "none");
		}
	});
}

// Inhabilita la opción de copiar y pegar en la confirmación del correo
function evitarCopyPaste() {
	$("#correo_conf").bind("cut copy paste",function(e) {
		e.preventDefault();
	});
}

function cambiarCaptcha() {
	$("#btn_captcha").click(function(e) {
		obtenerImagen();
	});
}

// Validación de los apellidos. Puede no tener paterno o materno, el otro es obligatorio.
function validarApellidos() {
	$("#chkApPaterno, #chkApMaterno").click(function() {
		if($(this).is(":checked")) {
			$(this).parents("div").children(".form-control").attr("disabled", "disabled");
			$("#chkApPaterno, #chkApMaterno").not($(this)).attr("disabled", "disabled");
			
			if($(this).closest("div").hasClass("has-error")) {
				$(this).closest("div").removeClass("has-error").addClass("has-success");
			}
		} else {
			$(this).parents("div").children(".form-control").removeAttr("disabled");
			$("#chkApPaterno, #chkApMaterno").not($(this)).removeAttr("disabled");
			
			if($(this).closest("div").hasClass("has-success") && !$(this).parents("div").children(".form-control").val()) {
				$(this).closest("div").removeClass("has-success").addClass("has-error");
			}
		}
	});
}

// Parámetros y reglas para la validación del formulario
function validarFormulario() {
	$("#formRegistro").validate({
		errorLabelContainer: "#mensajesModal .modal-body ul",
		errorElement: 'li',
		onkeyup: false,
        highlight: function(element) {
            $(element).closest('div').addClass('has-error');
            $(element).closest('div').removeClass('has-success');
		},
        unhighlight: function(element) {
            $(element).closest('div').addClass('has-success ');
        	$(element).closest('div').removeClass('has-error');
		},
		rules: {
			nombre: {
				required: true
			},
			ap_paterno: {
				required: "#chkApPaterno:unchecked",
			},
			ap_materno: {
				required: "#chkApMaterno:unchecked",
			},
			correo: {
				required: true,
				email: true
			},
			correo_conf: {
				required: true,
				equalTo: "#correo"
			},
			sexo: {
				required: true
			},
			pais: {
				required: true
			},
			entidad: {
				required: true
			},
			perfil: {
				required: true
			},
			otro_perfil: {
				required: true
			},
			institucion: {
				required: true
			},
			otra_institucion: {
				required: true
			},
			"cursos[]": {
				required: true,
				minlength: 1
			},
			chk_terminos: {
				required: true
			},
			captcha: {
				required: true,
				equalTo: "#oculto"
			}
		},
		messages: {
			nombre: "El nombre es obligatorio",
			ap_paterno: "El apellido paterno es obligatorio",
			ap_materno: "El apellido materno es obligatorio",
			correo: {
				required: "El correo es obligatorio",
				email: "El formato del correo es incorrecto"
			},
			correo_conf: {
				required: "Falta confirmar el correo electrónico",
				equalTo: "El correo electrónico no coincide"
			},
			sexo: "Falta seleccionar el sexo",
			pais: "Falta seleccionar el país",
			entidad: "Falta seleccionar la entidad",
			perfil: "Falta seleccionar el perfil",
			otro_perfil: "Escribe un perfil que te describa",
			institucion: "Falta seleccionar la institución",
			otra_institucion: "Escribe tu institución de procedencia",
			"cursos[]": "Selecciona por lo menos un curso",
			chk_terminos: "Debes aceptar los términos y condiciones de uso",
			captcha: {
				required: "Falta escribir el texto de la imagen",
				equalTo: "El código escrito es incorrecto"
			}
		}
	});
	
	$("#btnEnviar").click(function() {
		if($("#formRegistro").valid()) {
			$("#mensajesModal .modal-footer .btn-primary").removeAttr("disabled");
			
			var texto = "";
			var ap = "Sin apellido paterno";
			var am = "Sin apellido materno";
			var entidad = "Fuera de México";
			var perfil = "";
			var institucion = "";
			
			if($("#ap_paterno").val().length > 0) {
				ap = $("#ap_paterno").val();
			}
			
			if($("#ap_materno").val().length > 0) {
				am = $("#ap_materno").val();
			}
			
			if($("#pais option:selected").text() == "México") {
				entidad = $("#entidad option:selected").text();
			}
			
			if($("#perfil option:selected").text() == "Otro") {
				perfil = $("#otro_perfil").val();
			} else {
				perfil = $("#perfil option:selected").text();
			}
			
			if($("#institucion option:selected").text() == "Otra") {
				institucion = $("#otra_institucion").val();
			} else {
				institucion = $("#institucion option:selected").text();
			}
			
			texto += '<li>Nombre: '+$("#nombre").val()+'</li>';
			texto += '<li>Apellido paterno: '+ap+'</li>';
			texto += '<li>Apellido materno: '+am+'</li>';
			texto += '<li>Correo: '+$("#correo").val()+'</li>';
			texto += '<li>Sexo: '+$("#sexo option:selected").text()+'</li>';
			texto += '<li>País: '+$("#pais option:selected").text()+'</li>';
			texto += '<li>Entidad: '+$("#entidad option:selected").text()+'</li>'
			texto += '<li>Perfil: '+perfil+'</li>';
			texto += '<li>Institución: '+institucion+'</li>';
			texto += '<li>Cursos seleccionados:<ul>';
			
			$(".cursos label.col-xs-6").each(function() {
				if($(this).children("input").is(":checked")) {
					texto += '<li>'+$(this).text()+'</li>';
				}
			});
			
			texto += '</ul></li>';
			$('#mensajesModal h4.modal-title').html("Verifica la información registrada");
			$('#mensajesModal .modal-body ul').html(texto);
			$('#mensajesModal .modal-body ul').css("display", "block");
			$('#mensajesModal .modal-footer .btn-default').html("Regresar");
			$('#mensajesModal .modal-footer .btn-default').css("display", "inline");
			$('#mensajesModal .modal-footer .btn-primary').css("display", "inline");
			$('#mensajesModal').modal();
			
			$("#mensajesModal .modal-footer .btn-primary").click(function() {
				$('#mensajesModal h4.modal-title').html("Guardando...");
				texto = '<p>Espera un momento mientras guardamos tu información</p>';
				texto += '<div class="text-center"><img src="images/loading.gif" /></div>';
				$('#mensajesModal .modal-body ul').html(texto);
				$('#mensajesModal .modal-footer').css("display", "none");
				
				//$("#formRegistro").submit();
				
				$.post('registro/guardarRegistro',
						$("#formRegistro").serialize(),
						function( data ) {
							var usr = data;
							if ( usr == 0 ) {
								$('#mensajesModal h4.modal-title').html("Error");
								texto = '<p>Ha ocurrido un error con tu registro. Te pedimos intentarlo nuevamente, ';
								texto += 'si continuas teniendo problemas puedes comunicarte al teléfono (55) 5322 7700 ext. 4026</p>';
								$('#mensajesModal .modal-body ul').html(texto);
								$('#mensajesModal .modal-footer').css("display", "block");
								$('#mensajesModal .modal-footer .btn-primary').css("display", "none");
								$('#mensajesModal .modal-footer .btn-default').html("Aceptar");
								
								$("#mensajesModal .modal-footer .btn-default").click(function(e) {
									e.preventDefault();
									window.location = "/";
								});
							} else {
								$('#mensajesModal h4.modal-title').html("Registro realizado");
								texto = '<p>Tu registro se ha realizado correctamente.</p>';
								texto += '<p>En <a href="registro/comprobante/'+usr+'" download="comprobante.pdf">este enlace</a> puedes descargar tu comprobante.</p>';
								$('#mensajesModal .modal-body ul').html(texto);
								$('#mensajesModal .modal-footer').css("display", "block");
								$('#mensajesModal .modal-footer .btn-primary').css("display", "none");
								$('#mensajesModal .modal-footer .btn-default').html("Aceptar");
								
								$("#mensajesModal .modal-footer .btn-default").click(function(e) {
									e.preventDefault();
									window.location = "/";
								});
							}
						}
				);
				
				$(this).attr("disabled", "disabled");
			});
		} else {
			$('#mensajesModal .modal-footer .btn-default').css("display", "inline");
			$('#mensajesModal .modal-footer .btn-primary').css("display", "none");
			$('#mensajesModal').modal();
		}
	});
}

$(function() {
	evitarCopyPaste();
	visualizarCampos();
	cambiarCaptcha();
	validarApellidos();
	validarFormulario();
});