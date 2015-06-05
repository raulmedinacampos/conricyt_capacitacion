<script type="text/javascript"
	src="<?php echo base_url("scripts/jquery.validate.min.js"); ?>"></script>
<script type="text/javascript"
	src="<?php echo base_url("scripts/registro.js"); ?>"></script>
<script type="text/javascript">
function obtenerImagen() {
	$.post("<?php echo base_url('captcha'); ?>", '', function(data) {
		$("#img-captcha").attr("src", "<?php echo base_url('captcha/getImage').'/'; ?>"+data+"/"+Math.random());
		$("#oculto").val(data);
	});
}

$(function() {
	obtenerImagen();
});
</script>

<div id="contenido-titulo">
	<h1>Registro</h1>
</div>

<div id="contenido-linea">
	<div id="contenido-mundo">
		<img src="<?php echo base_url(); ?>images/mundo.png">
	</div>
</div>

<div id="contenido-texto">
	<p class="indicaciones">Indicaciones.</p>
	<p>Se debe llenar cuidadosamente cada campo del formulario con la
		información que se solicita, el nombre aparecerá en el portal y en la
		constancia tal como se haya escrito.</p>
	<p>En la sección de datos personales se escribe el nombre(s), en
		caso de no contar con alguno de los apellidos, es necesario habilitar
		cualquiera de las opciones "Sin apellido paterno" o "Sin apellido
		materno" según sea el caso.</p>
	<p>Escribir correctamente el correo electrónico al cual llegará el
		comprobante de registro con el usuario y la contraseña. 
		Confirmar el correo en el campo requerido.</p>
	<p>De las opciones sexo, país, perfil e institución selecciona la
		opción correspondiente.</p>
	<p>
		<em><strong>Nota: Si en las secciones de perfil o institución no localiza 
				la opción adecuada, es necesario dirigirse al final del listado 
				y seleccionar la opción "otro" para habilitar el campo "Otro perfil" 
				y escribir la actividad que realiza.
				Para la sección de Institución, en caso de no localizar la opción 
				deseada se sigue el mismo procedimiento de la sección anterior.</strong></em>
	</p>
	<p>Se pueden seleccionar o deseleccionar los cursos en los que se desea
		trabajar.</p>
	<p>Se debe habilitar la casilla de "Acepto los términos y condiciones de
		uso" o bien dar clic al enlace "términos y condiciones" para consultar 
		el documento.</p>
	<p>Por último, es importante escribir el código que se muestra en el
		recuadro gris y dar clic en enviar.</p>
	<p>Aparece una ventana emergente que muestra los datos para que sean
		verificados y en caso de alguna modificación, es necesario dar clic en
		el botón "Regresar", si la información es correcta, basta con dar clic
		en el botón "Enviar registro".</p>
	<p><strong>Registro realizado.</strong> Muestra el mensaje que indica que 
		el registro se generó correctamente, desde ahí se puede descargar el 
		comprobante del registro al dar clic sobre el enlace correspondiente, 
		el documento descargado es el mismo que llegará al correo electrónico 
		que se registró. Para cerrar el mensaje solo se debe dar clic en el 
		botón "Aceptar".</p>
	<p>Una vez concluido el registro. En el correo electrónico registrado 
		llegará el comprobante que incluye: el usuario y la contraseña. 
		Para ingresar al Centro de Capacitación se deben colocar estos datos 
		en la sección que lo solicita.</p>
	<div class="panel panel-default">
		<div class="panel-body">
			<span>Los datos personales proporcionados se encuentran protegidos
				conforme a lo dispuesto por la Ley Federal de Transparencia y Acceso
				a la Información Pública Gubernamental. La información recabada en
				este sistema tiene la finalidad de contar con datos estadísticos,
				para la realización de encuestas de calidad en el servicio y de
				contacto para enviar invitaciones a presentaciones de materiales de
				divulgación y eventos que organiza el Consorcio.</span>
		</div>
	</div>
<?php
$attr = array (
		'id' => 'formRegistro',
		'name' => 'formRegistro' 
);
echo form_open ( base_url ( 'registro/guardarRegistro' ), $attr );

echo '<div class="form-group">';
echo form_label ( 'Nombre:', '', array (
		'class' => 'col-sm-8 col-xs-12' 
) );

$attr = array (
		'id' => 'nombre',
		'name' => 'nombre',
		'class' => 'form-control' 
);
echo '<div class="col-sm-8 col-xs-12">';
echo form_input ( $attr );
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo form_label ( 'Apellido paterno:', '', array (
		'class' => 'col-sm-8 col-xs-12' 
) );

$attr = array (
		'id' => 'ap_paterno',
		'name' => 'ap_paterno',
		'class' => 'form-control' 
);
echo '<div class="col-sm-8 col-xs-12">';
echo form_input ( $attr );

$attr = array (
		'id' => 'chkApPaterno',
		'name' => 'chkApPaterno',
		'value' => '1' 
);
echo '<span class="help-block">';
echo form_checkbox ( $attr );
echo ' Sin apellido paterno</span>';
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo form_label ( 'Apellido materno:', '', array (
		'class' => 'col-sm-8 col-xs-12' 
) );

$attr = array (
		'id' => 'ap_materno',
		'name' => 'ap_materno',
		'class' => 'form-control' 
);
echo '<div class="col-sm-8 col-xs-12">';
echo form_input ( $attr );

$attr = array (
		'id' => 'chkApMaterno',
		'name' => 'chkApMaterno',
		'value' => '1' 
);
echo '<span class="help-block">';
echo form_checkbox ( $attr );
echo ' Sin apellido materno</span>';
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo form_label ( 'Correo:', '', array (
		'class' => 'col-sm-8 col-xs-12' 
) );

$attr = array (
		'id' => 'correo',
		'name' => 'correo',
		'type' => 'email',
		'class' => 'form-control' 
);
echo '<div class="col-sm-8 col-xs-12">';
echo form_input ( $attr );
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo form_label ( 'Confirmar correo:', '', array (
		'class' => 'col-sm-8 col-xs-12' 
) );

$attr = array (
		'id' => 'correo_conf',
		'name' => 'correo_conf',
		'type' => 'email',
		'class' => 'form-control' 
);
echo '<div class="col-sm-8 col-xs-12">';
echo form_input ( $attr );
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo form_label ( 'Sexo:', '', array (
		'class' => 'col-sm-8 col-xs-12' 
) );

$opt = array (
		'' => 'Selecciona',
		'm' => 'Masculino',
		'f' => 'Femenino' 
);
echo '<div class="col-sm-8 col-xs-12">';
echo form_dropdown ( 'sexo', $opt, '', 'id="sexo" class="form-control"' );
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo form_label ( 'País:', '', array (
		'class' => 'col-sm-8 col-xs-12' 
) );

$opt = array (
		'' => 'Selecciona' 
);

foreach ( $paises as $pais ) {
	$opt [$pais->id_pais] = $pais->pais;
}

echo '<div class="col-sm-8 col-xs-12">';
echo form_dropdown ( 'pais', $opt, '', 'id="pais" class="form-control"' );
echo '</div>';
echo '</div>';

echo '<div class="form-group entidad">';
echo form_label ( 'Entidad:', '', array (
		'class' => 'col-sm-8 col-xs-12' 
) );

$opt = array (
		'' => 'Selecciona' 
);

foreach ( $entidades as $entidad ) {
	$opt [$entidad->id_entidad] = $entidad->entidad;
}

echo '<div class="col-sm-8 col-xs-12">';
echo form_dropdown ( 'entidad', $opt, '', 'id="entidad" class="form-control"' );
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo form_label ( 'Perfil:', '', array (
		'class' => 'col-sm-8 col-xs-12' 
) );

$opt = array (
		'' => 'Selecciona' 
);

foreach ( $perfiles as $perfil ) {
	$opt [$perfil->id_perfil] = $perfil->perfil;
}

echo '<div class="col-sm-8 col-xs-12">';
echo form_dropdown ( 'perfil', $opt, '', 'id="perfil" class="form-control"' );
echo '</div>';
echo '</div>';

echo '<div class="form-group otro-perfil">';
echo form_label ( 'Otro perfil:', '', array (
		'class' => 'col-sm-8 col-xs-12' 
) );

$attr = array (
		'id' => 'otro_perfil',
		'name' => 'otro_perfil',
		'class' => 'form-control' 
);
echo '<div class="col-sm-8 col-xs-12">';
echo form_input ( $attr );
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo form_label ( 'Institución:', '', array (
		'class' => 'col-sm-8 col-xs-12' 
) );

$opt = array (
		'' => 'Selecciona' 
);

foreach ( $instituciones as $institucion ) {
	$opt [$institucion->id_institucion] = $institucion->institucion;
}

echo '<div class="col-sm-8 col-xs-12">';
echo form_dropdown ( 'institucion', $opt, '', 'id="institucion" class="form-control"' );
echo '</div>';
echo '</div>';

echo '<div class="form-group otra-institucion">';
echo form_label ( 'Otra institución:', '', array (
		'class' => 'col-sm-8 col-xs-12' 
) );

$attr = array (
		'id' => 'otra_institucion',
		'name' => 'otra_institucion',
		'class' => 'form-control' 
);
echo '<div class="col-sm-8 col-xs-12">';
echo form_input ( $attr );
echo '</div>';
echo '</div>';

echo '<div class="form-group cursos">';
echo form_label ( 'Elije los cursos a los que quieres inscribirte:', '', array (
		'class' => 'col-sm-8 col-xs-12' 
) );
echo '<div id="div-cursos">';
echo '</div>';

$opt = array (
		'' => 'Selecciona' 
);

foreach ( $cursos as $c ) {
	$opt [$c->id_curso] = $c->curso;
}

echo '<div class="col-sm-8 col-xs-12">';
echo form_dropdown ( 'cmb_curso', $opt, '', 'id="cmb_curso" class="form-control"' );
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
$attr = array (
		'id' => 'chk_terminos',
		'name' => 'chk_terminos',
		'value' => 1 
);
echo form_label ( form_checkbox ( $attr ) . ' Acepto los <a href="' . base_url ( 'pdf/terminos_y_condiciones.pdf' ) . '" target="_blank">términos y condiciones de uso</a>:', '', array (
		'class' => 'col-sm-8 col-xs-12 checkbox-inline' 
) );
echo '</div>';

echo '<div class="form-group">';
echo form_label ( 'Escribe el texto de la imagen', '', array (
		'class' => 'col-sm-10 col-xs-12' 
) );

$attr = array (
		'id' => 'captcha',
		'name' => 'captcha',
		'class' => 'form-control' 
);
echo '<div class="col-sm-3 col-xs-9">';
echo form_input ( $attr );

$attr = array (
		'id' => 'oculto',
		'name' => 'oculto',
		'type' => 'hidden' 
);
echo form_input ( $attr );
echo '</div>';

echo '<div class="col-sm-3 col-xs-6">';
echo '<img id="img-captcha" src="' . base_url ( "captcha" ) . '" />';
echo '</div>';

echo '<div class="col-sm-2 col-xs-3 text-right">';
$attr = array (
		'id' => 'btn_captcha',
		'name' => 'btn_captcha',
		'class' => 'btn btn-primary',
		'content' => '<span class="glyphicon glyphicon-refresh"></span>' 
);
echo form_button ( $attr );
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
$attr = array (
		'id' => 'btnEnviar',
		'name' => 'btnEnviar',
		'content' => 'Enviar',
		'class' => 'btn btn-primary' 
);
echo '<div class="col-sm-8 col-xs-12" style="margin-top:10px;">';
echo form_button ( $attr );
echo '</div>';
echo '</div>';

echo form_close ();
?>
<br style="clear: both;" />
</div>

<!-- Ventana modal donde se muestran errores y confirmación de información para el registro -->
<div class="modal fade" id="mensajesModal" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">Hay errores con el llenado
					del formulario</h4>
			</div>
			<div class="modal-body">
				<ul></ul>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary">Enviar registro</button>
			</div>
		</div>
	</div>
</div>

<br>
<br style="clear: both;">