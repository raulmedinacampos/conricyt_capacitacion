<link href="<?php echo base_url(); ?>css/owl.carousel.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>css/owl.theme.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="<?php echo base_url(); ?>scripts/owl.carousel.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-ui.min.js"></script>

<?php
if ( $query && $query->num_rows() < 4 ) {
?>
<style type="text/css">
	#curso-editoriales { width: <?php echo ($query->num_rows() * 25)?>%; }
</style>
<?php
}
?>

<script type="text/javascript">
	$(function() {
		$("#curso-editoriales").owlCarousel({
			<?php
			if ( $query && $query->num_rows() < 4 ) {
			?>
			items: <?php echo $query->num_rows(); ?>,
			<?php
			} else {
			?>
			items: 4,
			<?php
			}
			?>
			itemsMobile: [479,2],
			autoPlay: 3000,
			stopOnHover: true
		});
		
		$(".curso-bloque > img").click(function(e) {
			var id = $(this).data('curso');
			$("#modalLogin .modal-body > p").removeClass("show").addClass("hide");
			<?php
			if( $this->session->userdata('usuario') ) {
			?>
				e.preventDefault();
				
				window.location = "<?php echo base_url('acceso/verificarAcceso'); ?>/"+id;
			<?php
			} else {
			?>
				$("#modalLogin #curso_modal").val(id);
				$("#modalLogin").modal('show');
			<?php
			}
			?>
		});
	});
</script>

<div id="contenido-titulo">
	<h1>Inicio</h1>
</div>

<div id="contenido-linea">
	<div id="contenido-mundo">
		<img src="<?php echo base_url(); ?>images/mundo.png">
	</div>
</div>

<br>
<br>
<div id="contenido-texto">
	<p>Como un referente en el uso de recursos de información científica y
		tecnológica, se relaciona a las habilidades de búsqueda y recuperación
		de información, es por ello que, en el Centro de Capacitación Virtual
		del Consorcio Nacional de Recursos de Información Científica y
		Tecnológica (CONRICYT) desarrollarás estas habilidades que te
		permitirán conocer de manera una fácil y eficiente la estructura,
		navegación y tipo de información con que cuenta cada una de las
		plataformas de las editoriales mediante los contenidos de cada curso
		que se ponen a tu disposición.</p>

	<p>El Centro de Capacitación Virtual está dirigido a investigadores,
		docentes, estudiantes, bibliotecarios y referencistas de Instituciones
		de Educación Superior, Centros de Investigación, o cualquier
		institución que en su quehacer cotidiano haga uso de información
		científica y tecnológica más actual, así como estar al día en la
		generación de nuevo conocimiento para la realización de trabajos
		monográficos, tesis, diseño de temáticas para cátedras e
		investigaciones formales y que requieran comprender sobre el uso de
		las Plataformas de información que en este Centro se ofrecen.</p>

	<!-- Carrousel -->
	<div id="curso-editoriales" class="owl-carousel">
    <?php
		foreach ( $query->result () as $curso ) {
	?>
	    <div class="curso-bloque"
				data-idcurso="<?php echo $curso->id_curso; ?>"
				data-curso="<?php echo $curso->nombre_corto; ?>"
				data-nombre="<?php echo $curso->curso; ?>">
			<img src="<?php echo base_url().$curso->ruta_imagen; ?>"
					alt="<?php echo $curso->curso; ?>"
					data-curso="<?php echo $curso->id_curso; ?>" />
		</div>
    <?php
		}
	?>
  </div>
	<!-- Fin carrousel -->
	<br style="clear: both;" />
</div>

