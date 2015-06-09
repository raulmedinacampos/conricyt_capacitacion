<link rel="stylesheet" href="<?php echo base_url('css/reporte.css'); ?>" />

<div id="contenido-titulo">
	<h1>Reporte por regiones</h1>
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
		<div class="panel-heading">Reporte por regiones</div>
		<table class="table table-striped table-condensed">
			<tr>
				<th>No.</th>
				<th>Región</th>
				<th>Registrados</th>
			</tr>
	<?php
	foreach ( $regiones as $region ) {
	?>
			<tr>
				<td><?php echo $region->num; ?></td>
				<td><?php echo $region->region; ?></td>
				<td><?php echo $region->total; ?></td>
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