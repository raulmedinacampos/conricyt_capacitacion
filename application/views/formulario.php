<div id="contenido-titulo">
	<h1>Registro de Capacitación CONRICyT</h1>
</div>

<div id="contenido-linea">
	<div id="contenido-mundo">
		<img src="<?php echo base_url(); ?>images/mundo.png">
	</div>
</div>

<div id="contenido-texto">
<?php
$attr = array(
	'id'	=>	'formRegistro',
	'name'	=>	'formRegistro'
);
echo form_open(base_url(''), $attr);

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
echo form_label('Sexo:', '', array('class' => 'col-xs-8'));

$opt = array(
		''	=>	'Seleccione',
		'm'	=>	'Masculino',
		'f'	=>	'Femenino'
);
echo '<div class="col-xs-8">';
echo form_dropdown('sexo', $opt, '', 'id="sexo" class="form-control"');
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo form_label('País:', '', array('class' => 'col-xs-8'));

$opt = array('' => 'Seleccione');

foreach($paises as $pais) {
	$opt[$pais->id_pais] = $pais->pais;
}

echo '<div class="col-xs-8">';
echo form_dropdown('pais', $opt, '', 'id="pais" class="form-control"');
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo form_label('Perfil:', '', array('class' => 'col-xs-8'));

$opt = array('' => 'Seleccione');

foreach($perfiles as $perfil) {
	$opt[$perfil->id_perfil] = $perfil->perfil;
}

echo '<div class="col-xs-8">';
echo form_dropdown('perfil', $opt, '', 'id="perfil" class="form-control"');
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo form_label('Institución:', '', array('class' => 'col-xs-8'));

$opt = array('' => 'Seleccione');

foreach($instituciones as $institucion) {
	$opt[$institucion->id_institucion] = $institucion->institucion;
}

echo '<div class="col-xs-8">';
echo form_dropdown('institucion', $opt, '', 'id="institucion" class="form-control"');
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
$attr = array(
		'id'	=>	'btnEnviar',
		'name'	=>	'btnEnviar',
		'content'=>	'Enviar',
		'class'	=>	'btn btn-primary'
);
echo '<div class="col-xs-8">';
echo form_button($attr);
echo '</div>';
echo '</div>';

echo form_close();
?>
</div>

<br>
<br style="clear:both;">