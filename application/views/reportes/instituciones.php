<link rel="stylesheet" href="<?php echo base_url('css/reporte.css'); ?>" />

<div id="contenido-titulo">
	<h1>Reporte por instituciones</h1>
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
		<div class="panel-heading">Reporte por instituciones</div>
		<table class="table table-striped table-condensed">
			<tr>
				<th>No.</th>
				<th>Institución</th>
				<th>Registrados</th>
			</tr>
	<?php
	foreach ( $instituciones as $institucion ) {
	?>
			<tr>
				<td><?php echo $institucion->num; ?></td>
				<?php
				if ( $institucion->institucion == "Otra" ) {
				?>
					<td><a href="<?php echo base_url('administrador/reporte/por-otra-institucion'); ?>"><?php echo $institucion->institucion; ?><a></a></td>
				<?php
				} else {
				?>
					<td><?php echo $institucion->institucion; ?></td>
				<?php
				}
				?>
				<td><?php echo $institucion->total; ?></td>
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