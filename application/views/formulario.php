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
	<p>Debes llenar cuidadosamente cada campo con la información que se solicita, ya que si deseas obtener la Constancia de Terminación del Curso, tu nombre aparecerá tal como lo ingreses en el formulario.</p>
	
	<p>Una vez que hayas concluido tu registro, se te enviará por correo electrónico tu clave de usuario y contraseña. Para ingresar a los cursos seleccionados, debes colocar las contraseñas en la sección que lo solicita.</p>
	
	<p>Recuerda que cada curso requiere hasta de 20 horas (no continuas) para su realización, después de 30 días continuos sin actividad se te desmatriculará de manera automática; si deseas recuperar tu curso deberás registrarte e iniciar nuevamente.</p>
	
	<p>Se recomienda contar con un mínimo de 80% de reactivos correctos en cada una de las evaluaciones por módulo y en la evaluación final, para poder obtener la Constancia de Terminación del Curso.</p> 
	
	<p><b><i>Los datos personales proporcionados se encuentran protegidos conforme a lo dispuesto por la Ley Federal de Transparencia y Acceso a la Información Pública Gubernamental. La información recabada en este sistema tiene la finalidad de contar con datos estadísticos, para la realización de encuestas de calidad en el servicio y de contacto para enviar invitaciones a presentaciones de materiales de divulgación y eventos que organiza el Consorcio.</i></b></p>
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

if ( $cursos ) {
	foreach ($cursos as $curso) {
		$attr = array(
				'id'	=>	'chk_curso_'.$curso->id_curso,
				'name'	=>	'cursos[]',
				'value'	=>	$curso->id_curso,
				'checked'=>	'checked'
		);
		
		echo form_label(form_checkbox($attr).' '.$curso->curso, '', array('class' => 'col-xs-6'));
	}
}

$opt = array('' => 'Selecciona');

foreach($cursos as $c) {
	$opt[$c->id_curso] = $c->curso;
}

echo '<div class="col-xs-8">';
echo form_dropdown('curso', $opt, '', 'id="curso" class="form-control"');
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
$attr = array(
		'id'	=>	'chk_terminos',
		'name'	=>	'chk_terminos',
		'value'	=>	1
);
echo form_label(form_checkbox($attr).' Acepto los términos y condiciones de uso:', '', array('class' => 'col-xs-8 checkbox-inline'));
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