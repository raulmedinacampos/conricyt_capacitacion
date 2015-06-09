<link rel="stylesheet" href="<?php echo base_url('css/reporte.css'); ?>" />

<div id="contenido-titulo">
	<h1>Reporte por entidades</h1>
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
		<div class="panel-heading">Reporte por entidades</div>
		<table class="table table-striped table-condensed">
			<tr>
				<th>No.</th>
				<th>Entidad</th>
				<th>Registrados</th>
			</tr>
	<?php
	foreach ( $entidades as $entidad ) {
	?>
			<tr>
				<td><?php echo $entidad->num; ?></td>
				<td><?php echo $entidad->entidad; ?></td>
				<td><?php echo $entidad->total; ?></td>
			</tr>
	<?php
	}
	?>
			<tr class="total">
				<td colspan="2">Total</td>
				<td><?php echo $total; ?></td>
			</tr>
		</table>
	</div>
</div>