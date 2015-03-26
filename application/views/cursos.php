<link href="<?php echo base_url(); ?>css/owl.carousel.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>css/owl.theme.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url(); ?>scripts/owl.carousel.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-ui.min.js"></script>
<script type="text/javascript">
	$(function() {
		function mostrarBoton() {
			var chk = $("#listado input[type=checkbox]").length;
			if(chk) {
				$("#btn_registrarme").css("display", "inline");
			} else {
				$("#btn_registrarme").css("display", "none");
			}
		}
		
		mostrarBoton();
		
		$("#curso-editoriales").owlCarousel({
			items: 4,
			itemsMobile: [479,2],
			autoPlay: 3000,
			stopOnHover: true
		});
		
		$.each($(".curso-bloque"), function() {
			$(this).append('<img src="<?php echo base_url()."images/boton1-texto.png"; ?>" class="btnInfoCurso" id="btn'+$(this).data('curso')+'" data-curso="'+$(this).data('idcurso')+'" />');
			$(this).append('<img src="<?php echo base_url()."images/boton2-texto.png"; ?>" class="seleccionarCurso" id="btn'+$(this).data('curso')+'" data-curso="'+$(this).data('curso')+'" />');
		});
		
		$(".curso-bloque .btnInfoCurso").click(function() {
			var id = $(this).data('curso');
			$("#curso-info").load('<?php echo base_url()."cursos/"; ?>'+id);
			$("html, body").animate({ scrollTop: 480 }, 600);
		});
		
		$(".seleccionarCurso").click(function() {
			var id = $(this).data('curso');
			$.post("<?php echo base_url(); ?>cursos/agregarCurso",
				{ seleccion: id },
				function(data) {
					$("#listado").html(data);
					mostrarBoton();
				}
			);
		});
		
		
		var starting_position = $('#cursos-seleccionados').offset();
		var top_padding = 100;
		var bottom_limit = $('#curso-editoriales').offset();
		var box_height = $('#cursos-seleccionados').height() + 30;
		
		$(window).scroll(function() {
			var top_window = $(window).scrollTop();
			
			if (top_window > starting_position.top && top_window < bottom_limit.top - box_height) {
				$('#cursos-seleccionados').stop().animate({top: top_window - starting_position.top + top_padding}, 400);
			} else if (top_window > bottom_limit.top - starting_position.top - box_height) {
				$('#cursos-seleccionados').stop().animate({top: bottom_limit.top - starting_position.top - box_height }, 400);
			} else {
				$('#cursos-seleccionados').stop().animate({top: 15}, 400);
			}
		});
	});
</script>
<div id="contenido-titulo" >
  <h1 >Cursos Virtuales</h1>
</div>
<div id="contenido-linea" >
  <div id="contenido-mundo" ><img src="<?php echo base_url(); ?>images/mundo.png"></div>
</div>
<br>
<br>
<div id="contenido-texto"  >
  <!--%%%%%-->
  <div id="curso-info" style=" position:relative; float:left; width:100%; min-height:100px; border:red 0px solid; margin-right:15px;  ">
    <p> Los cursos del Centro de Capacitación Virtual están elaborados con el objetivo de desarrollar habilidades informativas para una fácil y eficiente búsqueda, recuperación y uso de la información científica y tecnológica de manera remota en los investigadores, docentes, estudiantes, bibliotecarios y referencistas de Instituciones de Educación Superior y Centro de Investigación. </p>
    <p> Cada curso está constituido por cuatro módulos temáticos, estructurados de lo general a lo particular, lo que garantiza una mejor compresión en la información presentada. Cuentan con materia adicional como ejercicios de retroalimentación, apoyo audiovisual y una autoevaluación al final de cada uno. En cada módulo podrás encontrar la siguiente información, de acuerdo a la plataforma seleccionada. </p>
    <p>
    <ul>
      <li>MÓDULO 1. RECUPERACIÓN DE LA INFORMACIÓN.</li>
      <li>MÓDULO 2. CARACTERÍSTICAS DE LA PLATAFORMA DEL EDITOR </li>
      <li>MÓDULO 3. CONTENIDOS TEMÁTICOS DEL EDITOR</li>
      <li>MÓDULO 4. ÁREAS DE CONOCIMIENTO Y CONTENIDO DEL EDITOR</li>
    </ul>
    </p>
    <p>Al finalizar cada módulo encontrarás una evaluación, la cual te permitirá conocer si alcanzaste los objetivos de aprendizaje, y al mismo tiempo, avanzar en el curso hasta llegar a la evaluación final que incluye los temas de los cuatro módulos. </p>
    <p> Se recomienda obtener 80% de reactivos correctos en cada evaluación realizada, así como en la final, para poder obtener la Constancia de Terminación del Curso. </p>
    <p> Los cursos presentados en el Centro de Capacitación Virtual son e-learning y autogestivos, con una duración promedio de 20 horas y desarrollados en español. Cubren una o diversas temáticas de las diferentes áreas del conocimiento. </p>
    <p> Puedes matricularte a los cursos que desees, pero debes tomar en cuenta que después de 30 días continuos sin actividad, se te desmatriculará de manera automática; si deseas recuperar tu curso deberás registrarte e iniciar nuevamente. </p>
    <p> Todos los cursos están abiertos desde el _______ al público interesado, y no cuentan con una fecha de finalización, es decir, te puedes matricular durante todo el año. </p>
    <p> Es importante que los usuarios de los cursos cuenten con conocimientos básicos de computación y motivación para desarrollar habilidades informativas. </p>
    <p> Da clic sobre el curso de tu interés y encuentra más información sobre la plataforma seleccionada. </p>
    <br/>
  </div>
  <!--%%%%%%%%%-->
  <div id="cursos-seleccionados" style="position:absolute; top:15px; right:-130px; max-width:120px; min-height:100px; border:#666 2px solid; text-align:center; -webkit-border-radius:6px ; -moz-border-radius:6px; border-radius:6px; width:100%; background-color:#FFFFFF;">
    <div class="div-titulo-cursos" style="position:relative; float:left; color:#FFF; background-color:#033C81; padding:5px;">Cursos que has elegido</div>
    <br />
    <form id="form1" name="form1" method="post" action="<?php echo base_url()."registro/"; ?>">
      <!-- -->
      <div id="listado" style=" position:relative; float:left; width:100%; text-align: left; color:#333; padding:5px; "> <?php echo $this->session->userdata('cursos_seleccionados'); ?> </div>
      <!-- -->
      <input type="submit" value="Registrarme" id="btn_registrarme" name="btn_registrarme">
      &nbsp;&nbsp;
    </form>
    <br>
    <br >
  </div>
  <!--%%%%%%%%%-->
  <!-- Carrousel -->
  <div id="curso-editoriales" class="owl-carousel">
    <?php
		 	foreach($query->result() as $curso) {
		 ?>
    <div class="curso-bloque" data-idcurso="<?php echo $curso->id_curso; ?>" data-curso="<?php echo $curso->nombre_corto; ?>"> <img src="<?php echo base_url().$curso->ruta_imagen; ?>" alt="<?php echo $curso->curso; ?>" data-curso="<?php echo $curso->id_curso; ?>" /> </div>
    <?php
			}
		 ?>
  </div>
  <!-- Fin carrousel -->
</div>
