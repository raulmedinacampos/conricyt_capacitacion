<link rel="stylesheet" href="<?php echo base_url('css/reporte.css'); ?>" />

<div id="contenido-titulo">
	<h1>Reporte por cursos</h1>
</div>

<div id="contenido-linea">
	<div id="contenido-mundo">
		<img src="<?php echo base_url('images/mundo.png'); ?>">
	</div>
</div>

<br>
<br>
<div id="contenido-texto">
	<div class="panel panel-default">
		<div class="panel-heading">Reporte por cursos</div>
		<table class="table table-striped table-condensed">
			<tr>
				<th>No.</th>
				<th>Curso</th>
				<th>Registrados</th>
			</tr>
	<?php
	foreach ( $cursos as $curso ) {
	?>
			<tr>
				<td><?php echo $curso->num; ?></td>
				<td><?php echo $curso->curso; ?></td>
				<td><?php echo $curso->total; ?></td>
			</tr>
	<?php
	}
	?>
		</table>
	</div>
</div>