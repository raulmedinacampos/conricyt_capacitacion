<script type="text/javascript" src="<?php echo base_url("scripts/jquery.validate.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("scripts/registro.js"); ?>"></script>
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
	<h1>Registro de Capacitación CONRICyT</h1>
</div>

<div id="contenido-linea">
	<div id="contenido-mundo">
		<img src="<?php echo base_url(); ?>images/mundo.png">
	</div>
</div>

<div id="contenido-texto">
	<p class="indicaciones">Indicaciones.</p>
	<p>Debes llenar cuidadosamente cada campo con la información que se solicita, tu nombre aparecerá en el portal y en la constancia tal como lo ingreses en el formulario.</p>
	<ol>
		<li>En los campos los datos personales escribe tu nombre y en caso de no contar con alguno de los apellidos habilita las opciones "Sin apellido paterno" o "Sin apellido materno" según sea el caso.</li>
		<li>Escribir correctamente el correo electrónico al cual llegará tu usuario y contraseña, y confirmarlo.</li>
		<li>De los campos sexo, país, perfil e institución selecciona la opción correspondiente.<br />
		<strong>Nota: Si en las opciones no aparece tú perfil ni tú institución, selecciona la opción "otro" y escribe en el campo "Otro perfil" (tu actividad en la institución) y en el campo "Otra institución" (El nombre de la institución a la que perteneces).</strong></li>
		<li>Selecciona el o los cursos a los que deseas registrarte y  da clic en "Acepto los términos y condiciones de uso".</li>
		<li>Por último escribe el código que se muestra en el recuadro gris y da clic en enviar.</li>
	</ol>
	<p>Aparece una ventana emergente que muestra tus datos para que verifiques si estos son correctos y puedas modificarlos dando clic en el botón Regresar.</p>
	<p>Si tus datos son correctos da clic en el botón Enviar registro y aparece otra ventana con el nombre Registro realizado, en el que aparece un enlace donde se puede descargar el comprobante de inscripción con tus claves.</p>
	<p>Una vez que hayas concluido tu registro, en tu correo electrónico llegará tu comprobante que incluye tu usuario y contraseña. Para ingresar al Centro de Capacitación debes colocar estos datos en la sección que lo solicita.</p>
	<div class="panel panel-default">
		<div class="panel-body">
			<span>Los datos personales proporcionados se encuentran protegidos conforme a lo dispuesto por la Ley Federal de Transparencia y Acceso a la Información Pública Gubernamental. La información recabada en este sistema tiene la finalidad de contar con datos estadísticos, para la realización de encuestas de calidad en el servicio y de contacto para enviar invitaciones a presentaciones de materiales de divulgación y eventos que organiza el Consorcio.</span>
		</div>
	</div>
<?php
$attr = array(
	'id'	=>	'formRegistro',
	'name'	=>	'formRegistro'
);
echo form_open(base_url('registro/guardarRegistro'), $attr);

echo '<div class="form-group">';
echo form_label('Nombre:', '', array('class' => 'col-xs-8'));

$attr = array(
	'id'	=>	'nombre',
	'name'	=>	'nombre',
	'class'	=>	'form-control'
);
echo '<div class="col-xs-8">';
echo form_input($attr);
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo form_label('Apellido paterno:', '', array('class' => 'col-xs-8'));

$attr = array(
		'id'	=>	'ap_paterno',
		'name'	=>	'ap_paterno',
		'class'	=>	'form-control'
);
echo '<div class="col-xs-8">';
echo form_input($attr);

$attr = array(
		'id'	=>	'chkApPaterno',
		'name'	=>	'chkApPaterno',
		'value'	=>	'1'
);
echo '<span class="help-block">';
echo form_checkbox($attr);
echo ' Sin apellido paterno</span>';
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo form_label('Apellido materno:', '', array('class' => 'col-xs-8'));

$attr = array(
		'id'	=>	'ap_materno',
		'name'	=>	'ap_materno',
		'class'	=>	'form-control'
);
echo '<div class="col-xs-8">';
echo form_input($attr);

$attr = array(
		'id'	=>	'chkApMaterno',
		'name'	=>	'chkApMaterno',
		'value'	=>	'1'
);
echo '<span class="help-block">';
echo form_checkbox($attr);
echo ' Sin apellido materno</span>';
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo form_label('Correo:', '', array('class' => 'col-xs-8'));

$attr = array(
		'id'	=>	'correo',
		'name'	=>	'correo',
		'class'	=>	'form-control'
);
echo '<div class="col-xs-8">';
echo form_input($attr);
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo form_label('Confirmar correo:', '', array('class' => 'col-xs-8'));

$attr = array(
		'id'	=>	'correo_conf',
		'name'	=>	'correo_conf',
		'class'	=>	'form-control'
);
echo '<div class="col-xs-8">';
echo form_input($attr);
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo form_label('Sexo:', '', array('class' => 'col-xs-8'));

$opt = array(
		''	=>	'Selecciona',
		'm'	=>	'Masculino',
		'f'	=>	'Femenino'
);
echo '<div class="col-xs-8">';
echo form_dropdown('sexo', $opt, '', 'id="sexo" class="form-control"');
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo form_label('País:', '', array('class' => 'col-xs-8'));

$opt = array('' => 'Selecciona');

foreach($paises as $pais) {
	$opt[$pais->id_pais] = $pais->pais;
}

echo '<div class="col-xs-8">';
echo form_dropdown('pais', $opt, '', 'id="pais" class="form-control"');
echo '</div>';
echo '</div>';

echo '<div class="form-group entidad">';
echo form_label('Entidad:', '', array('class' => 'col-xs-8'));

$opt = array('' => 'Selecciona');

foreach($entidades as $entidad) {
	$opt[$entidad->id_entidad] = $entidad->entidad;
}

echo '<div class="col-xs-8">';
echo form_dropdown('entidad', $opt, '', 'id="entidad" class="form-control"');
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo form_label('Perfil:', '', array('class' => 'col-xs-8'));

$opt = array('' => 'Selecciona');

foreach($perfiles as $perfil) {
	$opt[$perfil->id_perfil] = $perfil->perfil;
}

echo '<div class="col-xs-8">';
echo form_dropdown('perfil', $opt, '', 'id="perfil" class="form-control"');
echo '</div>';
echo '</div>';

echo '<div class="form-group otro-perfil">';
echo form_label('Otro perfil:', '', array('class' => 'col-xs-8'));

$attr = array(
		'id'	=>	'otro_perfil',
		'name'	=>	'otro_perfil',
		'class'	=>	'form-control'
);
echo '<div class="col-xs-8">';
echo form_input($attr);
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo form_label('Institución:', '', array('class' => 'col-xs-8'));

$opt = array('' => 'Selecciona');

foreach($instituciones as $institucion) {
	$opt[$institucion->id_institucion] = $institucion->institucion;
}

echo '<div class="col-xs-8">';
echo form_dropdown('institucion', $opt, '', 'id="institucion" class="form-control"');
echo '</div>';
echo '</div>';

echo '<div class="form-group otra-institucion">';
echo form_label('Otra institución:', '', array('class' => 'col-xs-8'));

$attr = array(
		'id'	=>	'otra_institucion',
		'name'	=>	'otra_institucion',
		'class'	=>	'form-control'
);
echo '<div class="col-xs-8">';
echo form_input($attr);
echo '</div>';
echo '</div>';

echo '<div class="form-group cursos">';
echo form_label('Elije los cursos a los que quieres inscribirte:', '', array('class' => 'col-xs-8'));
echo '<div id="div-cursos">';

/*if ( $cursos ) {
	foreach ($cursos as $curso) {
		$attr = array(
				'id'	=>	'chk_curso_'.$curso->id_curso,
				'name'	=>	'cursos[]',
				'value'	=>	$curso->id_curso,
				'checked'=>	'checked'
		);
		
		echo form_label(form_checkbox($attr).' '.$curso->curso, '', array('class' => 'col-xs-6'));
	}
}*/

echo '</div>';

$opt = array('' => 'Selecciona');

foreach($cursos as $c) {
	$opt[$c->id_curso] = $c->curso;
}

echo '<div class="col-xs-8">';
echo form_dropdown('cmb_curso', $opt, '', 'id="cmb_curso" class="form-control"');
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
$attr = array(
		'id'	=>	'chk_terminos',
		'name'	=>	'chk_terminos',
		'value'	=>	1
);
echo form_label(form_checkbox($attr).' Acepto los <a href="'.base_url('pdf/terminos_y_condiciones.pdf').'" target="_blank">términos y condiciones de uso</a>:', '', array('class' => 'col-xs-8 checkbox-inline'));
echo '</div>';

echo '<div class="form-group">';
echo form_label('Escribe el texto de la imagen', '', array('class' => 'col-xs-10'));

$attr = array(
		'id'	=>	'captcha',
		'name'	=>	'captcha',
		'class'	=>	'form-control'
);
echo '<div class="col-sm-3">';
echo form_input($attr);

$attr = array(
		'id'	=>	'oculto',
		'name'	=>	'oculto',
		'type'	=>	'hidden'
);
echo form_input($attr);
echo '</div>';

echo '<div class="col-sm-3">';
echo '<img id="img-captcha" src="'.base_url("captcha").'" />';
echo '</div>';

echo '<div class="col-sm-2 text-right">';
$attr = array(
		'id'	=>	'btn_captcha',
		'name'	=>	'btn_captcha',
		'class'	=>	'btn btn-primary',
		'content' => '<span class="glyphicon glyphicon-refresh"></span>'
);
echo form_button($attr);
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
$attr = array(
		'id'	=>	'btnEnviar',
		'name'	=>	'btnEnviar',
		'content'=>	'Enviar',
		'class'	=>	'btn btn-primary'
);
echo '<div class="col-xs-8" style="margin-top:10px;">';
echo form_button($attr);
echo '</div>';
echo '</div>';

echo form_close();
?>
<br style="clear: both;" />
</div>

<!-- Ventana modal donde se muestran errores y confirmación de información para el registro -->
<div class="modal fade" id="mensajesModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Hay errores con el llenado del formulario</h4>
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
<br style="clear:both;">