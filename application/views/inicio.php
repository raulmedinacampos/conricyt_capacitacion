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
	//Grayscale w canvas method
	function grayscale(src){
	    var canvas = document.createElement('canvas');
		var ctx = canvas.getContext('2d');
	    var imgObj = new Image();
		imgObj.src = src;
		canvas.width = imgObj.width;
		canvas.height = imgObj.height; 
		ctx.drawImage(imgObj, 0, 0); 
		var imgPixels = ctx.getImageData(0, 0, canvas.width, canvas.height);
		for(var y = 0; y < imgPixels.height; y++){
			for(var x = 0; x < imgPixels.width; x++){
				var i = (y * 4) * imgPixels.width + x * 4;
				var avg = (imgPixels.data[i] + imgPixels.data[i + 1] + imgPixels.data[i + 2]) / 3;
				imgPixels.data[i] = avg; 
				imgPixels.data[i + 1] = avg; 
				imgPixels.data[i + 2] = avg;
			}
		}
		ctx.putImageData(imgPixels, 0, 0, 0, 0, imgPixels.width, imgPixels.height);
		return canvas.toDataURL();
	}
	
	$(function() {
		$("#curso-editoriales").owlCarousel({
			<?php
			if ( $query->num_rows() < 4 ) {
			?>
			items: <?php echo (int)$query->num_rows(); ?>,
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

		// Bloque para poner la imagen en escala de grises
		$(".curso-bloque img").animate({opacity:1}, 200);
		
		// clone image
		$('.curso-bloque img').each(function(){
			var el = $(this);
			el.css({"position":"absolute"}).wrap("<div class='img_wrapper' style='display: inline-block'>").clone().addClass('img_grayscale').css({"position":"absolute","z-index":"998","opacity":"0"}).insertBefore(el).queue(function(){
				var el = $(this);
				el.parent().css({"width":this.width,"height":this.height});
				el.dequeue();
			});
			this.src = grayscale(this.src);
		});
		
		// Fade image 
		$('.curso-bloque img').mouseover(function(){
			$(this).parent().find('img:first').stop().animate({opacity:1}, 200);
		})
		$('.img_grayscale').mouseout(function(){
			$(this).stop().animate({opacity:0}, 400);
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

