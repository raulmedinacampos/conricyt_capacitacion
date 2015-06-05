<link href="<?php echo base_url('css/owl.carousel.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('css/owl.theme.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('css/gray.min.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('css/jquery.raty.css'); ?>" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="<?php echo base_url('scripts/owl.carousel.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('scripts/jquery-ui.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('scripts/jquery.gray.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('scripts/jquery.raty.js'); ?>"></script>

<?php
if ($query && sizeof($query) < 4) {
?>
<style type="text/css">
#curso-editoriales {
	width: <?php echo(sizeof($query) * 25)?>%;
}
</style>
<?php
}
?>

<script type="text/javascript">
	$(function() {
		$("#curso-editoriales").owlCarousel({
			<?php
			if (sizeof($query) < 4) {
			?>
			items: <?php echo (int)sizeof($query); ?>,
			<?php
			} else {
			?>
			items: 4,
			<?php
			}
			?>
			itemsTablet: [768,3],
			itemsMobile: [479,2],
			autoPlay: 3000,
			stopOnHover: true
		});
		
		$(".curso-bloque > img").click(function(e) {
			var id = $(this).data('curso');
			$("#modalLogin .modal-body > p").removeClass("show").addClass("hide");
			<?php
			if ($this->session->userdata ( 'usuario' )) {
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
	});
</script>


<div id="contenido-titulo">
	<h1>Presentación</h1>
</div>

<div id="contenido-linea">
	<div id="contenido-mundo">
		<img src="<?php echo base_url('images/mundo.png'); ?>">
	</div>
</div>

<br>
<br>
<div id="contenido-texto">
	<p>El Centro de Capacitación Virtual del Consorcio Nacional de Recursos
		de Información Científica y Tecnológica (CONRICYT), es un espacio
		donde se puede conocer de manera fácil y eficiente la estructura,
		navegación y tipo de contenido editorial con que cuenta las diferentes
		plataformas, mediante los contenidos que están a tu disposición.</p>

	<h4>Objetivo</h4>

	<p>Desarrollar habilidades en la búsqueda, recuperación y uso de la
		información científica y tecnológica en formatos digitales.</p>

	<h4>¿A quién está dirigido?</h4>

	<p>A la comunidad académica en general de las Instituciones de
		Educación Superior, Centros de Investigación o cualquier sector que
		requiera que sus usuarios aprendan a emplear información científica y
		tecnológica para la generación de nuevo conocimiento.</p>

	<h4>Acerca de los Cursos</h4>

	<p>Todos los cursos disponibles en el Centro de Capacitación Virtual
		son autogestivos. Cada curso requiere, en promedio 20 horas (no 
		continuas) para su conclusión.</p>

	<p>Los cursos se encuentran estructurados por módulos, con unidades de
		aprendizaje, que incluyen además de los contenidos, ejercicios de
		autoevaluación, evaluaciones y material de consulta adicional,
		asimismo se podrá obtener una constancia de terminación del Curso, al
		cumplir con un mínimo del 80% de los reactivos correctos en la
		evaluación de cada módulo y evaluación final.</p>

	<p class="importante">
		<strong>Importante:</strong> Antes de realiza el registro sugerimos
		revisar el <a href="#" data-toggle="modal" data-target="#modal_video">videotutorial</a>
		que explica este procedimiento.
	</p>

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
				class="grayscale grayscale-fade" alt="<?php echo $curso->curso; ?>"
				data-curso="<?php echo $curso->id_curso; ?>" />
			<div class="calificacion" data-calif="<?php echo $curso->calif; ?>"></div>
		</div>
    <?php
	}
	?>
  </div>
	<!-- Fin carrousel -->

	<!-- Video -->
	<div class="modal fade modal_video" id="modal_video" tabindex="-1"
		role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div align="center" class="embed-responsive embed-responsive-16by9">
					<iframe width="100%" height="100%"
						src="https://www.youtube.com/embed/KcbqEEqmcTw?list=PL6e5g66BBEbyulmocw5Jo9f1MmAx9RwT3"
						frameborder="0" allowfullscreen></iframe>
				</div>
			</div>
		</div>
	</div>
	<!-- Fin video -->
	<br style="clear: both;" />
</div>

