<?php
	echo form_open(base_url()."administrador/login");
	echo form_label('Nombre de usuario: ');
	$attr = array(
				  'id'		=>	'usuario',
				  'name'	=>	'usuario'
				  );
	echo form_input($attr);
	echo form_label('Contraseña: ');
	$attr = array(
				  'id'		=>	'contrasenia',
				  'name'	=>	'contrasenia'
				  );
	echo form_password($attr);
	$attr = array(
				  'id'		=>	'botonEnviar',
				  'name'	=>	'botonEnviar',
				  'class'	=>	'',
				  'value'	=>	'Entrar'
				  );
	echo form_submit($attr);
	echo form_close();
?>