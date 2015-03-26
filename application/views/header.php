<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Centro de capacitación</title>

<link rel="shortcut icon" href="<?php echo base_url('favicon.ico'); ?>" >

<link href='http://fonts.googleapis.com/css?family=Scada:400,700' rel='stylesheet' type='text/css'>

<link href="<?php echo base_url('css/estilos.css'); ?>" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="<?php echo base_url('scripts/jquery-1.11.0.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('scripts/modernizr-latest.js'); ?>"></script>





</head>

<body>

 <div id="header-pleca-superior" ></div>
   
  <!-- contenedor 1--> 
   <div id="contenedor"  >
   
 
   
   
<header>
        

       
       
    <!--logos superiores-->    
  <div id="logos-conricyt" >
  
    <div id="header-logo1" onClick='window.open("http://www.conricyt.mx/","_blank","toolbar=yes, scrollbars=yes, resizable=yes, top=10, left=50")' >
     
        <img src="<?php echo base_url('images/header-conricyt.png'); ?>" style=" cursor:pointer;" >
        </div>
        
        <div id="header-logo2" onClick='window.open("http://www.conricyt.mx/","_blank","toolbar=yes, scrollbars=yes, resizable=yes, top=10, left=50")'> 
        <img src="<?php echo base_url('images/header-conricyt-text.png'); ?>" style=" width:100%; cursor:pointer;" >
        </div>
        
        <div id="header-logo3" onClick='window.open("http://www.conacyt.gob.mx","_blank","toolbar=yes, scrollbars=yes, resizable=yes, top=10, left=50")' > 
        <img src="<?php echo base_url('images/header-conacyt.png'); ?>" style=" cursor:pointer;">
        </div>
        
        
        </div>
        <!-- Fin logos superiores-->   
        
        
        
        
          <!--banner login--> 
        <div id="banner" >
        
         <div id="banner-fondo"  >
        <img src="<?php echo base_url('images/header-banner2.png'); ?>" style=" width:100%; cursor:pointer;">
        </div>
        
<!--        <div id="banner-tablet" > <img src="<?php echo base_url(); ?>images/banner-tablet.png" style="width:100%;">
        </div>-->
        
<!--        <div id="banner-libros" > 
        <img src="<?php echo base_url(); ?>images/header-libros.png" style=" width:100%;">
        </div>-->
        
        
         <div id="banner-redes" >
         
         <div  class="banner-redes-img"  onClick='window.open("http://blog.conricyt.mx/","_blank","toolbar=yes, scrollbars=yes, resizable=yes, top=10, left=50")'>
        <img src="<?php echo base_url('images/nav-redes.png'); ?>" style=" cursor:pointer;">
        </div>
        
        <div  class="banner-redes-img" onClick='window.open("http://www.youtube.com/user/CONRICYT","_blank","toolbar=yes, scrollbars=yes, resizable=yes, top=10, left=50")' >
        <img src="<?php echo base_url('images/nav-redes.png'); ?>" style="left:-46px; cursor:pointer;" >
        </div>
        
        <div  class="banner-redes-img" onClick='window.open("https://twitter.com/CONRICYT","_blank","toolbar=yes, scrollbars=yes, resizable=yes, top=10, left=50")' ‎>
        <img src="<?php echo base_url('images/nav-redes.png'); ?>" style="left:-94px; cursor:pointer;" >
        </div>
        
        <div  class="banner-redes-img" onClick='window.open("https://es-es.facebook.com/CONRICYT","_blank","toolbar=yes, scrollbars=yes, resizable=yes, top=10, left=50")'>
        <img src="<?php echo base_url('images/nav-redes.png'); ?>" style="left:-140px; cursor:pointer;" >
        </div>
        
        
         </div>
        
        <div id="banner-login">
        <form action="http://localhost/moodle/login/index.php" method="post" id="logeo" target="_blank" >
        
        <div id="banner-login-2">
        
         <label for="username">usuario: </label>
         <input type="text" id="username" name="username" height="25" width="100" value="" >
        
        <label for="password">clave: </label>
         <input type="password" id="password" name="password" height="25" width="100" value="">
         
         
        <input type="submit" value="Enviar"  id="enviar">&nbsp;&nbsp;
        
        </div>
        
        </form>
        </div>
        
        
       
        
        
        </div>
         <!-- Fin banner y login-->
         
         
        
         
         
         
         <!-- menu -->
         <div id="menu" >
         
         <!--boton 1-->
         <div id="boton1" class="boton" onClick="window.location.href='<?php echo base_url(); ?>inicio'">
         
         <div class="boton-icono" >
         <img src="<?php echo base_url('images/nav-iconos.png'); ?>" class="boton-icono-img1" >
         </div>
         <div class="boton-capa-texto" ><span class="boton-texto1">Inicio</span><br><span class="boton-texto2" >Acerca de</span> </div>
         
         </div>
         
         
           <!--boton 2-->
         <div id="boton2" class="boton" onClick="window.location.href='<?php echo base_url(); ?>cursos'"> 
         
         <div class="boton-icono" >
          <img src="<?php echo base_url(); ?>images/nav-iconos.png" class="boton-icono-img2" >
         </div>
         <div class="boton-capa-texto" ><span class="boton-texto1">Información </span><br><span class="boton-texto2" >de los cursos </span> </div>
         
         </div>
         
         <!--boton 3-->
         <div id="boton3" class="boton" onClick="window.location.href='<?php echo base_url(); ?>registro'"> 
         
         <div class="boton-icono" >
         <img src="<?php echo base_url(); ?>images/nav-iconos.png" class="boton-icono-img3" >
         </div>
         <div class="boton-capa-texto"><span class="boton-texto1">Regístrate</span><br><span class="boton-texto2" >y participa</span> </div>
         
         </div>
         
         <!--boton 4-->
         <div id="boton4" class="boton" onClick="window.location.href='<?php echo base_url(); ?>guias-de-uso'"> 
         
         <div class="boton-icono" >
          <img src="<?php echo base_url(); ?>images/nav-iconos.png" class="boton-icono-img4" >
         </div>
         <div class="boton-capa-texto"><span class="boton-texto1">Guías</span><br><span class="boton-texto2" >de uso</span> </div>
                  
         </div>
         
         <!--boton 5-->
         <!--<div class="boton" onClick="window.location.href='<?php echo base_url(); ?>instituciones'">
         
         <div class="boton-icono" >
         <img src="<?php //echo base_url(); ?>images/nav-iconos.png" class="boton-icono-img5" >
         </div>
         <div class="boton-capa-texto"><span class="boton-texto1">Instituciones</span><br><span class="boton-texto2" >que participan</span> </div>
         
          </div>-->
         
         <!--boton 6-->
         <div id="boton5" class="boton" onClick="window.location.href='<?php echo base_url(); ?>preguntas-frecuentes'">
         
         <div class="boton-icono" >
        <img src="<?php echo base_url(); ?>images/nav-iconos.png" class="boton-icono-img6" >
         </div>
         <div class="boton-capa-texto"><span class="boton-texto1">Preguntas</span><br><span class="boton-texto2" >frecuentes</span> </div>
         
          </div>
         
         <!--boton 7-->
         <div id="boton6" class="boton-ultimo" onClick="window.location.href='<?php echo base_url(); ?>contacto'"> 
         
         <div class="boton-icono">
         <img src="<?php echo base_url(); ?>images/nav-iconos.png" class="boton-icono-img7" >
         </div>
         <div class="boton-capa-texto"><span class="boton-texto1">Contacto</span><br><span class="boton-texto2" ></span> </div>
         
         </div>
         
         
         
         </div>
          <!-- Fin menu -->
         
         
         
        </header>


<div id="contenido" >