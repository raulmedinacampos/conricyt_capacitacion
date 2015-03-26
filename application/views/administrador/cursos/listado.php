Listado de cursos
<?php
	foreach($query->result() as $curso) {
?>
<a href="<?php echo base_url()."administrador/cursos/editar/".$curso->id_curso; ?>"><?php echo $curso->curso; ?></a>
<?php
	}
?>