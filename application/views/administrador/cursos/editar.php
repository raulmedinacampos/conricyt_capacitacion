<?php
	function listDirImages($dir) {
		$files = scandir($dir);
		$preg = "/.(jpg|gif|png|jpeg)/i";
		
		$images = array();
		
		foreach($files as $img) {
			if(substr($img, 0, 1) != '.') {
				echo '<img src="'.base_url().$dir.$img.'" data-ruta="'.$dir.$img.'" />';
			}
		}
	}
?>
<style type="text/css">
	#modal_window {
		min-width: 454px;
		width: 454px;
		max-height: 290px;
		margin-left: -225px;
		border-radius: 8px 8px 0 0;
	}
	
	#modal_window img {
		width: 80px;
		margin: 10px;
		border: 1px #CCCCCC solid;
	}
	
	#modal_content {
		height: 210px;
		overflow: auto;
	}
	
	#modal_footer {
		border-radius: 0 0 8px 8px;
		bottom: -31px;
		width: 440px;
	}
</style>
<script type="text/javascript" src="<?php echo base_url(); ?>scripts/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
	tinymce.init({
		selector: "textarea#texto",
		menubar: false,
		plugins: "code",
		toolbar: "undo redo | styleselect | cut copy paste | bold italic | link image | bullist numlist | code"
	});

	$.fn.modal = function() {
		var th = $(this);
		$('#btn_img').on('click', function(e) {
			e.preventDefault();
		
			$('body').append('<div class="lbox" ></div>');
			th.toggle();
			
			$('.modal_closeX').click(function() {
				th.css("display", "none");
				if($("#modal_window").is(":visible") == 0) {
					$('.lbox').remove();
				}
			});
		});
	};
	
	$(function(){
		var valImagen = "";
		$("#modal_window").modal();
		
		$("#modal_content img").click(function() {
			$("#modal_content img").css("border", "1px #CCCCCC solid");
			$(this).css("border", "2px #006699 solid");
			valImagen = $(this).data('ruta');
		});
		
		$("#btn_aceptar").click(function() {
			$("#modal_window").css("display", "none");
			if($("#modal_window").is(":visible") == 0) {
				$('.lbox').remove();
			}
			$("#imagen").val(valImagen);
			$("#img_actual").attr('src', "<?php echo base_url(); ?>"+valImagen);
		});
	});
</script>

Editar curso
<div id="modal_window">
	<button class="modal_closeX" href="#modal_window" ></button>
    <div id="modal_heading">
        <h2>Selecciona una imagen</h2>
    </div>
    <div id="modal_content">
		<?php listDirImages("images/cursos/"); ?>
    </div>
	<div id="modal_footer"><input type="button" id="btn_aceptar" value="Aceptar" /></div>
</div>
<?php
	echo form_open();
	echo form_label('Título: ');
	echo form_input('titulo', $curso->curso);
	echo "<br />";
	echo form_label('Imagen: ');
	if($curso->ruta_imagen) {
		echo '<img id="img_actual" src="'.base_url().$curso->ruta_imagen.'" />';
	}
	$attr = array(
				  'id'		=>	'btn_img',
				  'name'	=>	'btn_img',
				  'content'	=>	'Cambiar imagen'
				  );
	echo form_button($attr);
	$attr = array(
				  'id'		=>	'imagen',
				  'name'	=>	'imagen',
				  );
	echo form_input($attr, 'aaaa');
	echo "<br />";
	$attr = array(
				  'id'		=>	'texto',
				  'name'	=>	'texto'
				  );
	echo form_textarea($attr, $curso->descripcion);
	echo "<br />";
	echo form_label('Estado: ');
	$options = array(
					 0	=>	'Inactivo',
					 1	=>	'Activo'
					 );
	echo form_dropdown('estatus', $options, $curso->estatus);
	echo "<br />";
	$attr = array(
				  'id'		=>	'boton1',
				  'name'	=>	'boton1',
				  'content'	=>	'Actualizar'
				  );
	echo form_button($attr);
	echo form_close();
?>