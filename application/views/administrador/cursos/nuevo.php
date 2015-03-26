<script type="text/javascript" src="<?php echo base_url(); ?>scripts/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
	tinymce.init({
		selector: "textarea#texto",
		menubar: false,
		plugins: "code",
		toolbar: "undo redo | styleselect | cut copy paste | bold italic | link image | bullist numlist | code"
	});
</script>

Nuevo curso
<?php
	echo form_open();
	echo form_label('Título: ');
	echo form_input('titulo');
	echo "<br />";
	echo form_label('Imagen: ');
	echo form_upload('imagen');
	echo "<br />";
	$attr = array(
				  'id'		=>	'texto',
				  'name'	=>	'texto'
				  );
	echo form_textarea($attr);
	echo "<br />";
	echo form_label('Estado: ');
	$options = array(
					 0	=>	'Inactivo',
					 1	=>	'Activo'
					 );
	echo form_dropdown('estatus', $options, '0');
	echo "<br />";
	$attr = array(
				  'id'		=>	'boton1',
				  'name'	=>	'boton1',
				  'content'	=>	'Guardar'
				  );
	echo form_button($attr);
	echo form_close();
?>