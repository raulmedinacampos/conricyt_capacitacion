<link rel="stylesheet" href="<?php echo base_url('css/administrador.css'); ?>" />

<h3 class="text-center">Administración del Centro de Capacitación</h3>
<?php
$attr = array(
		'id'	=> 'formLoginAdmin',
		'name'	=> 'formLoginAdmin',
		'class'	=> 'col-sm-4 col-xs-12'
);
echo form_open(base_url('administrador/login'), $attr);
echo '<div class="form-group">';
echo form_label('Nombre de usuario: ');
$attr = array(
		'id'	=> 'admin_usuario',
		'name'	=> 'admin_usuario',
		'class'	=> 'form-control'			
);
echo form_input($attr);
echo form_label('Contraseña: ');
$attr = array(
		'id'	=>	'admin_password',
		'name'	=>	'admin_password',
		'class'	=> 'form-control'
);
echo form_password($attr);
echo '</div>';
echo '<div class="botonLogin">';
$attr = array(
		'id'	=>	'botonEnviar',
		'name'	=>	'botonEnviar',
		'class'	=>	'btn btn-primary',
		'value'	=>	'Entrar'
);
echo form_submit($attr);
echo '</div>';
echo form_close();
?>