<?php
$ctrl = $this->router->fetch_class();
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Centro de capacitación</title>

<link rel="shortcut icon" href="<?php echo base_url('favicon.ico'); ?>">

<!-- Hojas de estilos -->
<link href='http://fonts.googleapis.com/css?family=Scada:400,700' rel='stylesheet' type='text/css' />
<link href="<?php echo base_url('css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('css/estilos.css'); ?>" rel="stylesheet" type="text/css" />

<!-- Scripts (jQuery y Bootstrap) -->
<script type="text/javascript" src="<?php echo base_url('scripts/jquery-1.11.0.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('scripts/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('scripts/login.js'); ?>"></script>

<script type="text/javascript">
$(function() {
	$(".cursos-menu a").click(function(e) {
		var id = $(this).data("id");
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

	<?php
	if ( $this->uri->segment(2) == "error" ) {
	?>
		$("#modalErrorLogin").modal('show');
	<?php
	}
	?>
});
</script>
</head>

<body>
	<div id="header-pleca-superior"></div>

	<!-- contenedor 1-->
	<div id="contenedor" class="container">
		<header>
			<!--logos superiores-->
			<div id="logos-conricyt">

				<div id="header-logo1"
					onClick='window.open("http://www.conricyt.mx/","_blank","toolbar=yes, scrollbars=yes, resizable=yes, top=10, left=50")'>

					<img src="<?php echo base_url('images/header-conricyt.png'); ?>"
						style="cursor: pointer;">
				</div>

				<div id="header-logo2"
					onClick='window.open("http://www.conricyt.mx/","_blank","toolbar=yes, scrollbars=yes, resizable=yes, top=10, left=50")'>
					<img
						src="<?php echo base_url('images/header-conricyt-text.png'); ?>"
						style="width: 100%; cursor: pointer;">
				</div>

				<div id="header-logo3"
					onClick='window.open("http://www.conacyt.gob.mx","_blank","toolbar=yes, scrollbars=yes, resizable=yes, top=10, left=50")'>
					<img src="<?php echo base_url('images/header-conacyt.png'); ?>"
						style="cursor: pointer;">
				</div>


			</div>
			<!-- Fin logos superiores-->




			<!--banner login-->
			<div id="banner">

				<div id="banner-fondo">
					<img src="<?php echo base_url('images/header-banner.jpg'); ?>"
						style="width: 100%; cursor: pointer;">
				</div>

				<div id="banner-redes">

					<div class="banner-redes-img"
						onClick='window.open("http://blog.conricyt.mx/","_blank","toolbar=yes, scrollbars=yes, resizable=yes, top=10, left=50")'>
						<img src="<?php echo base_url('images/nav-redes.png'); ?>"
							style="cursor: pointer;">
					</div>

					<div class="banner-redes-img"
						onClick='window.open("http://www.youtube.com/user/CONRICYT","_blank","toolbar=yes, scrollbars=yes, resizable=yes, top=10, left=50")'>
						<img src="<?php echo base_url('images/nav-redes.png'); ?>"
							style="left: -46px; cursor: pointer;">
					</div>

					<div class="banner-redes-img"
						onClick='window.open("https://twitter.com/CONRICYT","_blank","toolbar=yes, scrollbars=yes, resizable=yes, top=10, left=50")'‎>
						<img src="<?php echo base_url('images/nav-redes.png'); ?>"
							style="left: -94px; cursor: pointer;">
					</div>

					<div class="banner-redes-img"
						onClick='window.open("https://es-es.facebook.com/CONRICYT","_blank","toolbar=yes, scrollbars=yes, resizable=yes, top=10, left=50")'>
						<img src="<?php echo base_url('images/nav-redes.png'); ?>"
							style="left: -140px; cursor: pointer;">
					</div>


				</div>

				<div id="banner-login">
					<form action="<?php echo base_url('login'); ?>" method="post" id="logeo">
						<div id="banner-login-2">
							<?php
							if ( !$this->session->userdata('usuario') ) {
							?>
							<label>usuario </label> <input type="text" id="usuario" name="usuario" />
							<label>clave </label> <input type="password" id="password" name="password" />
							<label>&nbsp;</label> <input type="submit" value="Enviar" id="enviar">&nbsp;&nbsp;
							<?php
							} else {
							?>
							<p class="nombre"><?php echo trim($usuario->nombre." ".$usuario->ap_paterno." ".$usuario->ap_materno); ?></p>
							<span class="salir"><a href="<?php echo base_url('login/salir'); ?>">Salir</a></span>
							<?php
							}
							?>
						</div>

					</form>
				</div>





			</div>
			<!-- Fin banner y login-->






			<!-- menu -->
			<div id="menu">
			<nav class="navbar navbar-default">
				<div class="container-fluid">
			    <!-- Menú colapsado para resoluciones pequeñas -->
			    <div class="navbar-header">
			      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
			        <span class="sr-only">Mostrar/ocultar menú</span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			      </button>
			      <a class="navbar-brand visible-xs-block" href="#">Menú principal</a>
			    </div>
			
			    <!-- Elmentos del menú -->
			    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			      <ul class="nav navbar-nav">
			        <li <?php if($ctrl == "inicio") {echo 'class="active"'; } ?>><a href="<?php echo base_url('inicio'); ?>">Presentación <span class="sr-only">(current)</span></a></li>
			        <?php
					if ( $this->session->userdata('usuario') ) {
					?>
					<li <?php if($ctrl == "usuario") {echo 'class="active"'; } ?>><a href="<?php echo base_url('usuario'); ?>">Mis Cursos</a></li>
					<?php
					}
					?>
					<li <?php if($ctrl == "guias_de_uso") {echo 'class="active"'; } ?>><a href="<?php echo base_url('guias-de-uso'); ?>">Guías de uso</a></li>
			        <li class="dropdown">
			          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Cursos <span class="caret"></span></a>
			          <ul class="dropdown-menu" role="menu">
			          	<?php
			          	if ( $menu ) {
				          	foreach ( $menu->result() as $val ) {
				          	?>
				            <li class="cursos-menu"><a href="#" data-id="<?php echo $val->id_curso; ?>"><?php echo $val->curso; ?></a></li>
				            <?php
				          	}
			          	}
			          	?>
			            <?php
						if ( $this->session->userdata('usuario') ) {
						?>
			            <li class="divider"></li>
			            <li><a href="<?php echo base_url('usuario'); ?>">Cursos disponibles</a></li>
			            <?php
						}
			            ?>
			          </ul>
			        </li>
			        <li <?php if($ctrl == "preguntas_frecuentes") {echo 'class="active"'; } ?>><a href="<?php echo base_url('preguntas-frecuentes'); ?>">Preguntas frecuentes</a></li>
			        <li <?php if($ctrl == "contacto") {echo 'class="active"'; } ?>><a href="<?php echo base_url('contacto'); ?>">Contacto</a></li>
			      </ul>
			    </div><!-- /.navbar-collapse -->
			  </div><!-- /.container-fluid -->
			</nav>
			</div>
		</header>


		<div id="contenido">