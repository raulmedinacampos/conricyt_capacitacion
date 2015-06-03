<script type="text/javascript">
$(function() {
	$("#cursos a").click(function(e) {
		e.preventDefault();

		var id = $(this).data("id");
		window.location = "<?php echo base_url('acceso/verificarAcceso'); ?>/"+id;
	});
	
	$("#disponibles a").click(function() {
		var id = $(this).data("id");
		var curso = $(this).data("curso");
		var texto = '¿Estás seguro de que deseas inscribirte al curso <strong>"'+curso+'"</strong>?';

		$("#curso").val(id);

		$("#confirmacion .modal-body").html(texto);
		$("#confirmacion").modal('show');

		$("#confirmacion .modal-footer .btn-primary").click(function() {
			$("#formMatricular").submit();
		});
	});
});
</script>
<link href="<?php echo base_url('css/usuario.css'); ?>" rel="stylesheet" type="text/css" /> 
<?php
$saludo = ($usuario->sexo == 'm') ? "Bienvenido" : "Bienvenida";
?>
<div class="bienvenida">
	<h3><?php echo "¡$saludo!"; ?></h3>
	<!-- <p class="nombre"><?php echo trim($usuario->nombre." ".$usuario->ap_paterno." ".$usuario->ap_materno); ?></p>
	<span class="salir"><a href="<?php echo base_url('login/salir'); ?>">Salir</a></span> -->
</div>

<div id="contenido-texto" >
	<h4 class="cursos">Mis cursos</h4>
	<div id="cursos">
	<?php
	if ( $cursos ) {
		echo '<ul>';
		foreach ( $cursos->result() as $curso ) {
	?>
			<li><span class="col-sm-5"><?php echo $curso->curso; ?></span><a data-id="<?php echo $curso->id_curso; ?>" href="#"><span class="btn btn-xs btn-primary">Ingresar al curso</span></a>
	<?php
		}
		echo '</ul>';
	} else {
		echo '<p>No te has inscrito a ningún curso</p>';
	}
	?>
	</div>
	
	<h4 class="cursos">Cursos disponibles</h4>
	<div id="disponibles">
	<?php
	if ( $cursos_disponibles ) {
		echo '<ul>';
		foreach ( $cursos_disponibles->result() as $curso ) {
			// Revisamos si es un curso nuevo para colocarle la imagen correspondiente
			$clase_estatus = "";
			if ( $curso->estatus == 2) {
				$clase_estatus = "nuevo";
			}
	?>
			<li><span class="col-sm-5 <?php echo $clase_estatus; ?>"><?php echo $curso->curso; ?></span><a data-id="<?php echo $curso->id_curso; ?>" data-curso="<?php echo $curso->curso; ?>"><span class="btn btn-xs btn-primary">Incribirme a este curso</span></a>
	<?php
		}
		echo '</ul>';
	} else {
		echo '<p>No hay cursos disponibles</p>';
	}
	
	$attr = array(
			'id'	=>	'formMatricular',
			'name'	=>	'formMatricular'
	);
	
	echo form_open(base_url('registro/matricular'), $attr);
	$attr = array(
			'id'	=>	'curso',
			'name'	=>	'curso',
			'type'	=>	'hidden',
			'value'	=>	''
	);
	echo form_input($attr);
	echo form_close();
	?>
	</div>
</div>

<!-- Ventana modal de confirmación -->
<div class="modal fade bs-example-modal-sm" id="confirmacion" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Inscribirse a curso</h4>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary">Inscribirme</button>
      </div>
    </div>
  </div>
</div>