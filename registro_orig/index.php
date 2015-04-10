<?php
include_once('recursos/conf/conf.php');
include_once('recursos/general.php');
include_once('recursos/sessionHandlerDB.php');

$sess = new SessionManager();
session_start();

$dbh=Sdx_ConectaBase();
mysqli_set_charset($dbh, "utf8");

$salt='#UdN23aN$%G89n';
$hash = md5(mt_rand(1,1000000) . $salt);
$_SESSION['sdx_token'] = $hash;
$raiz = 'http://capacitacion.dev';

$cursos = array();
if(isset($_POST['cursos'])) {
	$cursos = $_POST['cursos'];
} else {
	if(isset($_COOKIE['ci_session']) && !isset($_POST['btn_registrarme'])){
		$cookies = unserialize($_COOKIE['ci_session']);
		if(isset($cookies['cursos_seleccionados'])) {
			$cursos = explode("<br/>", $cookies['cursos_seleccionados']);
			for($i=0; $i<count($cursos); $i++) {
				$cursos[$i] = strip_tags($cursos[$i]);
			}
		}
	}
}

$id_entidad = "";
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        
        <title>Centro de capacitación - Formulario de registro</title>
        
        <link href='http://fonts.googleapis.com/css?family=Scada:400,700' rel='stylesheet' type='text/css'>
        
        <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/dojo/1.9.2/dijit/themes/tundra/tundra.css" media="screen">
        
        <link href="<?php echo $raiz;?>/css/estilos.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript">
          <!--  
            dojoConfig = {async: true, parseOnLoad: true, locale: 'es'};
          //-->  
        </script>
        <script src="http://ajax.googleapis.com/ajax/libs/dojo/1.9.2/dojo/dojo.js"></script>
        <script src="recursos/js/dynamic.php"></script>
		<style media="screen" type="text/css">
            <!--
            p label{color:#206080;display:inline-block;font-size:1.5em;margin-bottom:0.15em;}
            .sdxCampoTexto{border-radius:6px;vertical-align:middle;}
            .sdxClearBoth{clear:both;}
            .sdxLista{float:left;margin:0 auto;overflow:hidden;}
            .sdxLista input{float:left;line-height:1.25em;margin-top:0.5em;}
            .sdxLista label{float:right;line-height:1.25em;padding:0 6px;white-space:nowrap;min-width:310px;}
            -->
        </style>
    </head>
    <body class="tundra">

 <div id="header-pleca-superior" ></div>
   
  <!-- contenedor 1--> 
   <div id="contenedor"  >
   
 
   
   
<header>
        

       
       
    <!--logos superiores-->    
  <div id="logos-conricyt" >
  
    <div id="header-logo1" onClick='window.open("http://www.conricyt.mx/","_blank","toolbar=yes, scrollbars=yes, resizable=yes, top=10, left=50")' >
     
        <img src="<?php echo $raiz;?>/images/header-conricyt.png" style=" cursor:pointer;" >
        </div>
        
        <div id="header-logo2" onClick='window.open("http://www.conricyt.mx/","_blank","toolbar=yes, scrollbars=yes, resizable=yes, top=10, left=50")'> 
        <img src="<?php echo $raiz;?>/images/header-conricyt-text.png" style=" width:100%; cursor:pointer;" >
        </div>
        
        <div id="header-logo3" onClick='window.open("http://www.conacyt.gob.mx","_blank","toolbar=yes, scrollbars=yes, resizable=yes, top=10, left=50")' > 
        <img src="<?php echo $raiz;?>/images/header-conacyt.png" style=" cursor:pointer;">
        </div>
        
        
        </div>
        <!-- Fin logos superiores-->   
        
        
        
        
          <!--banner login--> 
        <div id="banner" >
        
         <div id="banner-fondo"  >
        <img src="<?php echo $raiz;?>/images/header-banner2.png" style=" width:100%; cursor:pointer;">
        </div>
        
         <div id="banner-redes" >
         
         <div  class="banner-redes-img"  onClick='window.open("http://blog.conricyt.mx/","_blank","toolbar=yes, scrollbars=yes, resizable=yes, top=10, left=50")'>
        <img src="<?php echo $raiz;?>/images/nav-redes.png" style=" cursor:pointer;">
        </div>
        
        <div  class="banner-redes-img" onClick='window.open("http://www.youtube.com/user/CONRICYT","_blank","toolbar=yes, scrollbars=yes, resizable=yes, top=10, left=50")' >
        <img src="<?php echo $raiz;?>/images/nav-redes.png" style="left:-46px; cursor:pointer;" >
        </div>
        
        <div  class="banner-redes-img" onClick='window.open("https://twitter.com/CONRICYT","_blank","toolbar=yes, scrollbars=yes, resizable=yes, top=10, left=50")' ‎>
        <img src="<?php echo $raiz;?>/images/nav-redes.png" style="left:-94px; cursor:pointer;" >
        </div>
        
        <div  class="banner-redes-img" onClick='window.open("https://es-es.facebook.com/CONRICYT","_blank","toolbar=yes, scrollbars=yes, resizable=yes, top=10, left=50")'>
        <img src="<?php echo $raiz;?>/images/nav-redes.png" style="left:-140px; cursor:pointer;" >
        </div>
        
        
         </div>
        
        <div id="banner-login">
        <form action="http://localhost/moodle/login/index.php" method="post" id="logeo" target="_blank">
        
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
         <div class="boton" onClick="window.location.href='<?php echo $raiz; ?>/inicio'">
         
         <div class="boton-icono" >
         <img src="<?php echo $raiz;?>/images/nav-iconos.png" class="boton-icono-img1" >
         </div>
         <div class="boton-capa-texto" ><span class="boton-texto1">Inicio</span><br><span class="boton-texto2" >Acerca de</span> </div>
         
         </div>
         
         
           <!--boton 2-->
         <div class="boton" onClick="window.location.href='<?php echo $raiz; ?>/cursos'"> 
         
         <div class="boton-icono" >
          <img src="<?php echo $raiz;?>/images/nav-iconos.png" class="boton-icono-img2" >
         </div>
         <div class="boton-capa-texto" ><span class="boton-texto1">Cursos</span><br><span class="boton-texto2" >  Virtuales</span> </div>
         
         </div>
         
         <!--boton 3-->
         <div class="boton"> 
         
         <div class="boton-icono" >
         <img src="<?php echo $raiz;?>/images/nav-iconos.png" class="boton-icono-img3" >
         </div>
         <div class="boton-capa-texto"><span class="boton-texto1">Regístrate</span><br><span class="boton-texto2" >y participa</span> </div>
         
         </div>
         
         <!--boton 4-->
         <div class="boton" onClick="window.location.href='<?php echo $raiz; ?>/guias-de-uso'"> 
         
         <div class="boton-icono" >
          <img src="<?php echo $raiz;?>/images/nav-iconos.png" class="boton-icono-img4" >
         </div>
         <div class="boton-capa-texto"><span class="boton-texto1">Guías</span><br><span class="boton-texto2" >de uso</span> </div>
                  
         </div>
         
         <!--boton 6-->
         <div class="boton" onClick="window.location.href='<?php echo $raiz; ?>/preguntas-frecuentes'">
         
         <div class="boton-icono" >
        <img src="<?php echo $raiz;?>/images/nav-iconos.png" class="boton-icono-img6" >
         </div>
         <div class="boton-capa-texto"><span class="boton-texto1">Preguntas</span><br><span class="boton-texto2" >frecuentes</span> </div>
         
          </div>
         
         <!--boton 7-->
         <div class="boton-ultimo" onClick="window.location.href='<?php echo $raiz; ?>/contacto'"> 
         
         <div class="boton-icono">
         <img src="<?php echo $raiz;?>/images/nav-iconos.png" class="boton-icono-img7" >
         </div>
         <div class="boton-capa-texto"><span class="boton-texto1">Contacto</span><br><span class="boton-texto2" ></span> </div>
         
         </div>
         
         
         
         </div>
          <!-- Fin menu -->
         
         
         
        </header>


<div id="contenido" >


        <div id="contenido-titulo" >
         <h1>Registro de Capacitación CONRICyT</h1>
         </div>
         
         <div id="contenido-linea" >
         <div id="contenido-mundo" ><img src="<?php echo $raiz;?>/images/mundo.png"></div>
         </div>
         
         <br><br>
         
         
         <div id="contenido-texto" >

         
         
        <form name="Forma1" action="" id="Forma1" method="post">
            <input type="hidden" id="token" name="token" value="<?php echo $hash; ?>">
             <?php if (isset($_SESSION['SdxMsgCapta']) ) 
                        { ?>
                    <p style="color:red;">
                        <?php echo $_SESSION['SdxMsgCapta']; ?>
                    </p><br>
                    <?php } ?>
            <p>
                <label for="nombre">Nombre(s):</label>
                <br><input id="nombre" name="nombre" type="text" data-dojo-type="dijit/form/ValidationTextBox" data-dojo-props="placeHolder:'Escriba su(s) nombre(s)',required:true,trim:true,maxLength:50,invalidMessage:'Este campo es requerido',missingMessage:'Este campo es requerido',promptMessage:'Escriba su(s) nombre(s) como desee que aparezca en<br />su Constancia (obligatorio). No registre su grado académico'" style="width:300px;" class="sdxCampoTexto">
            </p>
            <p>
                <label for="ap_paterno">Apellido paterno:</label>
                <span id="span_ap_paterno">
                <br/><input id="ap_paterno" name="ap_paterno" type="text" data-dojo-type="dijit/form/ValidationTextBox" data-dojo-props="placeHolder:'Escriba su apellido paterno',required:true,trim:true,maxLength:50,invalidMessage:'Escriba su apellido paterno, en caso de no tener<br />seleccione la casilla',missingMessage:'Escriba su apellido paterno, en caso de no tener<br />seleccione la casilla',promptMessage:'Escriba su apellido paterno, en caso de no tener<br />seleccione la casilla'" style="width:300px;" class="sdxCampoTexto"></span>
                <br/>
                <input id="hide_ap_paterno" name="hide_ap_paterno" data-dojo-type="dijit/form/CheckBox" data-dojo-props="value:'hide'" style="margin-right:5px;" onclick="OcultarCampo(this, span_ap_paterno);" ><span>Sin apellido paterno</span>
            </p>
            <p>
                <label for="ap_materno">Apellido materno:</label>
                <span id="span_ap_materno">
                <br><input id="ap_materno" name="ap_materno" type="text" data-dojo-type="dijit/form/ValidationTextBox" data-dojo-props="placeHolder:'Escriba su apellido materno',required:true,trim:true,maxLength:50,invalidMessage:'Escriba su apellido materno, en caso de no tener<br />seleccione la casilla',missingMessage:'Escriba su apellido materno, en caso de no tener<br />seleccione la casilla',promptMessage:'Escriba su apellido materno, en caso de no tener<br />seleccione la casilla'" style="width:300px;" class="sdxCampoTexto"></span>
                <br class="hideClass"/>
                <input id="hide_ap_materno" name="hide_ap_materno" data-dojo-type="dijit/form/CheckBox" data-dojo-props="value:'hide'" style="margin-right:5px;" onclick="OcultarCampo(this, span_ap_materno);" ><span>Sin apellido materno</span>
            </p>
            <p>
                <label for="correo">Correo:</label>
                <br><input id="correo" name="correo" type="email" data-dojo-type="dijit/form/ValidationTextBox" data-dojo-props="placeHolder:'Escriba su correo electrónico',required:true,trim:true,maxLength:150,invalidMessage:'Este campo es requerido',missingMessage:'Este campo es requerido',promptMessage:'Escriba su Correo electronico (obligatorio)'" style="width:300px;" class="sdxCampoTexto">
            </p>
            <p>
                <label for="sexo">Sexo:</label>
                <br><select id="sexo" name="sexo" data-dojo-type="dijit/form/ComboBox" data-dojo-props="placeHolder:'- Elija su sexo -',require:true" style="width:300px;">
                    <option value=""></option>
                    <option value="femenino">Femenino</option>
                    <option value="masculino">Masculino</option>
                </select>
            </p>            
            <p>
               <label for="id_pais">País:</label>                
               <br><select id="id_pais" name="id_pais" data-dojo-type="dijit/form/FilteringSelect" data-dojo-props="placeHolder:'- Elija su estado -',require:true,title:'Institucion',tooltip:'Seleccione el nombre de su Estado (obligatorio)'" onChange="verificapais();" style="width:300px;">
                   <option value="" selected="selected"></option>
                   <?php
                    $comando0 = 'SELECT id_pais, pais FROM cat_paises ORDER BY pais';
                    $result0 = mysqli_query ($dbh, $comando0);
                    while($row0 = mysqli_fetch_array($result0, MYSQLI_ASSOC)){
                        $id_paisX=$row0['id_pais'];
                        $paisX=$row0['pais'];
                        ?>
                        <option value="<?php echo $id_paisX; ?>" <?php //if ($id_pais==$id_paisX) { echo 'selected="selected"'; } ?>><?php echo $paisX; ?></option>
                        <?php } mysqli_free_result($result0); ?>
               </select>
            </p>
            <p id="estado" style="display:<?php if ($id_paisX=='156') { ?>;<?php } else { ?>none;<?php } ?>">
               <label for="id_entidad">Estado:</label>                
               <br><select id="id_entidad" name="id_entidad" data-dojo-type="dijit/form/FilteringSelect" data-dojo-props="placeHolder:'- Elija su estado -',require:true,title:'Institucion',tooltip:'Seleccione el nombre de su Estado (obligatorio)'" style="width:300px;">
                   <option value="" selected="selected"></option>
                   <?php
                    $comando = 'SELECT id_entidad, entidad FROM entidad ORDER BY entidad';
                    $result = mysqli_query ($dbh, $comando);
                    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                        $id_entidadX=$row['id_entidad'];
                        $entidadX=$row['entidad'];
                        ?>
                        <option value="<?php echo $id_entidadX; ?>" <?php //if ($id_entidad==$id_entidadX) { echo 'selected="selected"'; } ?>><?php echo $entidadX; ?></option>
                        <?php } mysqli_free_result($result); ?>
               </select>
            </p>
            <p>
               <label for="id_perfil">Perfil:</label> 
               <br>
               <select id="id_perfil" name="id_perfil" data-dojo-type="dijit/form/FilteringSelect" data-dojo-props="placeHolder:'- Elija su perfil -',require:true,title:'Institucion',tooltip:'Seleccione el nombre de su Estado (obligatorio)'" style="width:300px;" onChange="Revisaperfil();">
                   <option value="" selected="selected"></option>
                   <?php
                    $comando1 = 'SELECT id_perfil, perfil FROM cat_perfil ORDER BY perfil';
                    $result1 = mysqli_query ($dbh, $comando1);
                    while($row1 = mysqli_fetch_array($result1, MYSQLI_ASSOC)){
                        $id_perfilX=$row1['id_perfil'];
                        $perfilX=$row1['perfil'];
                        ?>
                        <option value="<?php echo $id_perfilX; ?>" <?php //if ($id_perfil==$id_perfilX) { echo 'selected="selected"'; } ?>><?php echo $perfilX; ?></option>
                        <?php } mysqli_free_result($result1); ?>
               </select>
            </p>
            <p id="operfil" style="display:<?php if ($id_perfilX == '10') { ?>;<?php } else { ?>none;<?php } ?>">
                <label for="perfil">Especifique otro perfil:</label>
                <br><input id="perfil" name="perfil" type="text" data-dojo-type="dijit/form/ValidationTextBox" data-dojo-props="placeHolder:'Escriba su otro perfil',trim:true,maxLength:50" style="width:300px;" class="sdxCampoTexto">
            </p>
            <p>
               <label for="id_institucion">Institución:</label> 
               <br><select id="id_institucion" name="id_institucion" data-dojo-type="dijit/form/FilteringSelect" data-dojo-props="placeHolder:'- Elija su institución -',require:true,title:'Institucion',tooltip:'Seleccione el nombre de su Institución de procedencia (obligatorio)'" style="width:500px;" onChange="Revisainstitucion();">
                   <option value="" selected="selected"></option>                   
                    <?php
                    $comando2 = 'SELECT id_institucion, institucion FROM institucion ORDER BY institucion';
                    $result2 = mysqli_query ($dbh, $comando2);
                    while($row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC)){
                        $id_institucionX=$row2['id_institucion'];
                        $institucionX=$row2['institucion'];
                        ?>
                        <option value="<?php echo $id_institucionX; ?>" <?php //if ($id_institucion==$id_institucionX) { echo 'selected="selected"'; } ?>><?php echo $institucionX; ?></option>
                        <?php } mysqli_free_result($result2); ?>
               </select>    
            </p>
            <p id="oinstitucion" style="display:<?php if ( $id_institucionX == '484') { ?>;<?php } else { ?>none;<?php } ?>">
                <label for="institucion">Especifique otra Institución:</label>
                <br><input id="institucion" name="institucion" type="text" data-dojo-type="dijit/form/ValidationTextBox" data-dojo-props="placeHolder:'Escriba la institución',trim:true,maxLength:50" style="width:300px;" class="sdxCampoTexto">
            </p>
            <?php
				if($cursos) {
            ?>
            <p style="padding-top:2em;">Elija los cursos a los que quiere inscribirse</p>
            <?php
				}
			?>
                <?php
				$filtro=" AND (nombre_corto=''";
				foreach($cursos as $curso) {
					$filtro.=" OR nombre_corto='".$curso."'";
				}
				$filtro.=")";
                $a=0;
                $comando3 = 'SELECT id_curso, curso FROM curso WHERE estatus = 1'.$filtro.' ORDER BY curso';
				//$comando3 = 'SELECT id_curso, curso FROM curso ORDER BY curso';
                $result3 = mysqli_query ($dbh, $comando3);
                while($row3 = mysqli_fetch_array($result3, MYSQLI_ASSOC)){
                    $a++;
                    $id_curso=$row3['id_curso'];
                    $curso=$row3['curso'];
                    ?>            
                <p class="sdxLista">
                    <label for="id_curso_<?php echo $id_curso; ?>"><?php echo $curso; ?></label>
                    <input id="id_curso_<?php echo $id_curso; ?>" name="id_curso_<?php echo $id_curso; ?>" data-dojo-type="dijit/form/CheckBox" data-dojo-props="value:'agreed', checked:true, etiqueta:'<?php echo $curso; ?>'">
                </p>
                <?php } mysqli_free_result($result3); ?>
            
            <p style="clear:both;padding-top:2em;">
                <span>Acepto los términos y condiciones de uso</span>
                <input type="hidden" id="filtro_campos" name="filtro_campos" data-dojo-type="dijit/form/TextBox" value="<?php echo $filtro; ?>"/>
                <input id="terminos" name="terminos" data-dojo-type="dijit/form/CheckBox" data-dojo-props="value:'agreed',required:true" style="float:left;margin-right:5px;" >
            </p>

                <!--Captcha -->          
            <p style="clear:both;padding-top:2em;">
                <label for="usuarioh">Ingrese los caracteres mostrados en la imagen:</label>
                <br>
                <input type="text" id="capta" name="capta" data-dojo-type="dijit/form/ValidationTextBox" data-dojo-props="size:34,maxlength:6,required:true,placeholder:'Los caracteres mostrados en la imagen'">
                <br>
                <a href="#" onclick="document.getElementById('captcha').src = 'recursos/securimage/securimage_show.php?' + Math.random(); return false;">[ Obtener imagen diferente ]</a>
            </p>
            <!-- *** COMIENZA CAPTCHA *** -->
            <img id="captcha" src="recursos/securimage/securimage_show.php" alt="Imagen Captcha">
            <!-- *** TERMINA CAPTCHA *** -->           
            <br>    
            <p>
                <button data-dojo-type="dijit/form/Button" data-dojo-props="label:'Enviar'" onClick="Confirma();"></button>
            </p>
        </form>
        <div data-dojo-type="dijit/Dialog" data-dojo-id="myDialog" title="Formulario de registro">
              <p id="TextoDialogo"></p><br>
              
              <div dojoType="dijit/ProgressBar" data-dojo-props="indeterminate:true" style="width:100%"></div>
              
              <div id="boton">
                  <button data-dojo-type="dijit/form/Button" data-dojo-props="label:'Aceptar'" onClick="Registra();" id="btn_aceptar"></button>
                  <button data-dojo-type="dijit/form/Button" data-dojo-props="label:'Cerrar'" onClick="Cerrar();" id="btn_cancelar"></button>
              </div>
        </div>
        
        </div>



	<!-- Ventana modal con aviso -->
	<?php
		if(!$cursos) {
    ?>
		<script type="text/javascript" src="../scripts/jquery-1.11.0.min.js" ></script>
        <script type="text/javascript">
            $.fn.modal = function() {
                var th = $(this);
                $('body').append('<div class="lbox" ></div>');
                $("#modal_window").toggle();
            };
            
            $(function(){
                $("#modal_window").modal();
                $("#btn_cursos").click(function() {
                    document.location.href = '../cursos/';
                });
            });
        </script>
        <div id="modal_window">
			<div id="modal_heading">
				<h2>Registrarse</h2>
</div>
            <div id="modal_content">
                Favor de primero seleccionar los cursos a los que se quiere inscribir.
            </div>
            <div id="modal_footer"><input type="button" id="btn_cursos" value="Ir a los cursos" /></div>
        </div>
    <?php
		}
	?>
    

 </div>



  <footer class="foot" >
      <br>
      <div id="foot-logos">
      <a href="http://www.conacyt.gob.mx/" target="_blank"><img class="footer-img" src="<?php echo $raiz;?>/images/conacyt.png" ></a>
     <a href="http://www.sep.gob.mx/" target="_blank"> <img class="footer-img" src="<?php echo $raiz;?>/images/sep.png" ></a>
      <a href="http://www.anuies.mx/" target="_blank"><img class="footer-img" src="<?php echo $raiz;?>/images/anuies.png" ></a>
      <a href="http://www.unam.mx/" target="_blank"><img class="footer-img" src="<?php echo $raiz;?>/images/unam.png" ></a>
      <a href="http://www.cinvestav.mx/" target="_blank"><img class="footer-img" src="<?php echo $raiz;?>/images/cinvestav.png" ></a>
      <a href="http://www.ipn.mx/" target="_blank"><img class="footer-img" src="<?php echo $raiz;?>/images/ipn.png" ></a>
      <a href="http://www.uam.mx/" target="_blank"><img class="footer-img" src="<?php echo $raiz;?>/images/uam.png" ></a>
      <a href="http://www.udg.mx/" target="_blank"><img class="footer-img" src="<?php echo $raiz;?>/images/guadalajara.png" ></a>
      <a href="http://www.cudi.edu.mx/" target="_blank"><img class="footer-img" src="<?php echo $raiz;?>/images/cudi.png" ></a>
      </div>
            
      <br clear="all">
      <div id="foot-texto" >
      CONSORCIO NACIONAL DE RECURSOS DE INFORMACIÓN CIENTÍFICA Y TECNOLÓGICA (CONRICyT)
      <br>
Copyright © 2011 Derechos Reservados<br><br>

Av. Insurgentes Sur 1582, Col. Crédito Constructor, Del. Benito Juárez
<br>
C.P.: 03940, México, D.F. Tel: (55) 5322-7700. Ext. 4020 a la 4026
<br><br><br>
       </div>
      
      </footer>
      
      
         </div>
   <!-- Fin contenedor 1-->
      


</body>
</html>