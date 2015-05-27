<link href="<?php echo base_url('css/owl.carousel.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('css/owl.theme.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('css/gray.min.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('css/jquery.raty.css'); ?>" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="<?php echo base_url('scripts/owl.carousel.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('scripts/jquery-ui.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('scripts/jquery.gray.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('scripts/jquery.raty.js'); ?>"></script>

<?php
if ( $query && sizeof($query) < 4 ) {
?>
<style type="text/css">
	#curso-editoriales { width: <?php echo (sizeof($query) * 25)?>%; }
</style>
<?php
}
?>

<script type="text/javascript">
	$(function() {
		$("#curso-editoriales").owlCarousel({
			<?php
			if ( sizeof($query) < 4 ) {
			?>
			items: <?php echo (int)sizeof($query); ?>,
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

		$('div.calificacion').raty({
			readOnly: true,
			score: function() { return $(this).data('calif'); },
			hints: ['Muy malo', 'Malo', 'Regular', 'Bueno', 'Excelente'],
			path: "<?php echo base_url('images'); ?>",
			noRatedMsg: "Este curso aún no ha sido calificado"
		});

		// Bloque para poner la imagen en escala de grises
		
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
	<p>El Centro de Capacitación Virtual del Consorcio Nacional de Recursos
		de Información Científica y Tecnológica (CONRICYT) es un espacio en
		donde vas a conocer de manera fácil y eficiente la estructura,
		navegación y tipo de información con que cuenta cada una de las
		plataformas de las editoriales mediante los contenidos que están a tu
		disposición.</p>

	<h4>Objetivo</h4>

	<p>Desarrollar habilidades en la búsqueda, recuperación y uso de la
		información científica y tecnológica de manera remota.</p>

	<h4>¿A quién está dirigido?</h4>

	<p>A la comunidad académica de las Instituciones de Educación Superior,
		Centros de Investigación o cualquier sector que requiera emplear
		información científica y tecnológica para la generación de nuevo
		conocimiento por medio de trabajos monográficos, tesis, diseño de
		temáticas para cátedras e investigaciones formales o simple consulta.</p>

	<h4>Acerca de los Cursos</h4>

	<p>Todos los cursos disponibles en el Centro de Capacitación Virtual
		los puedes trabajar a tu propio ritmo. Considera que cada curso
		requiere en promedio 20 horas (no continuas) para su conclusión.</p>

	<p>Cada curso está estructurado por módulos con unidades de
		aprendizaje, donde se incluyen además de los contenidos, ejercicios de
		retroalimentación, evaluaciones y material adicional.</p>

	<p>Asimismo se cuenta con una Constancia de Terminación del Curso.</p>

	<p class="importante"><strong>IMPORTANTE:</strong> Antes de ingresar a cualquier curso es necesario que
		revises la <a href="<?php echo base_url('guias-de-uso'); ?>">Guía de uso</a> a los cursos para identificar cada una de
		las secciones que lo integran.</p>

	<!-- Carrousel -->
	<div id="curso-editoriales" class="owl-carousel">
    <?php
	foreach ( $query as $curso ) {
	?>
	    <div class="curso-bloque"
				data-idcurso="<?php echo $curso->id_curso; ?>"
				data-curso="<?php echo $curso->nombre_corto; ?>"
				data-nombre="<?php echo $curso->curso; ?>">
			<img src="<?php echo base_url().$curso->ruta_imagen; ?>"
					class="grayscale grayscale-fade"
					alt="<?php echo $curso->curso; ?>"
					data-curso="<?php echo $curso->id_curso; ?>" />
			<div class="calificacion" data-calif="<?php echo $curso->calif; ?>"></div>
		</div>
    <?php
    }
	?>
  </div>
	<!-- Fin carrousel -->
	<br style="clear: both;" />
</div>

