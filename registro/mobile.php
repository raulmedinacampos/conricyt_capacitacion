<?php
include_once('recursos/conf/conf.php');
include_once('recursos/general.php');
include_once('recursos/sessionHandlerDB.php');

$sess = new SessionManager();
session_start();

$dbh = Sdx_ConectaBase();
mysqli_set_charset($dbh, "utf8");

$salt = '#UdN23aN$%G89n';
$hash = md5(mt_rand(1, 1000000) . $salt);
$_SESSION['sdx_token'] = $hash;



$id_entidad = "";
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">

        <title>Formulario para Registro de Capacitación</title>
        <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/dojo/1.9.2/dijit/themes/tundra/tundra.css" media="screen">
        <link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css">
        <script type="text/javascript">
            <!--
            /*
                 require([
                    'dojo/parser',
                    'dojo/ready'
                ]);
            */
            function verificapais() {
                var pais = document.getElementById('id_pais').get('value');
                if (pais == '156') {
                    document.getElementById('estado').style.display = 'block';
                } else {
                    document.getElementById('estado').style.display = 'none';
                }
            }
//-->
        </script>
        <style media="screen" type="text/css">
            <!--
            *{font-family:'Open Sans',sans-serif;font-size:100%;}
            body{background-color:#a0b0c0;}
            #sdxMobile{margin:0 auto;padding:1.5em;}
            #sdxMobile form{margin:0 auto;padding:1.5em;border-radius:20px;}
            #sdxMobile h1{background-color:#f0f0f0;border:1px solid #c0c0c0;color:#204060;font-size:4em;font-weight:normal;padding:0.25em;border-radius:12px;text-shadow:2px 2px 0 #ffffff;}
            #sdxMobile label{color:#ffffff;display:inline-block;font-size:2.6em;margin-bottom:0.15em;white-space:nowrap;}
            #sdxMobile .sdxClearBoth{clear:both;}
            #sdxMobile .sdxLista{float:left;margin:0 auto;overflow:hidden;}
            #sdxMobile .sdxLista input{float:left;line-height:1.25em;margin-top:0.5em;}
            #sdxMobile .sdxLista label{float:right;line-height:1.25em;padding:0 6px;white-space:nowrap;min-width:325px;}
            #sdxMobile input[type=text],#sdxMobile input[type=email],#sdxMobile input[type=password],#sdxMobile input[type=tel]{-webkit-appearance:none;-moz-appearance:none;background-color:#f8f8f8;border:1px solid #c0c0c0;display:block;font-size:2.6em;line-height:60px;margin:0;width:100%;height:60px;border-radius:12px;}
            #sdxMobile input[type=checkbox]{-webkit-border-radius:22px;-moz-border-radius:22px;border:1px solid #c0c0c0;width:44px;height:44px;border-radius:22px;}
            #sdxMobile button{-webkit-appearance:none;-moz-appearance:none;background:#f8f8f8;background:-moz-linear-gradient(top,#f8f8f8 0%,#c0c0c0 100%);background:-webkit-gradient(linear,left top,left bottom,color-stop(0%,#f8f8f8),color-stop(100%,#c0c0c0));background:-webkit-linear-gradient(top,#f8f8f8 0%,#c0c0c0 100%);background:-o-linear-gradient(top,#f8f8f8 0%,#c0c0c0 100%);background:-ms-linear-gradient(top,#f8f8f8 0%,#c0c0c0 100%);background:linear-gradient(to bottom,#f8f8f8 0%,#c0c0c0 100%);-webkit-border-radius:10px;-moz-border-radius:10px;border:1px solid #c0c0c0;color:#404040;display:block;font-size:2.6em;line-height:2.5em;margin:1.5em 0;width:100%;height:2.5em;border-radius:12px;text-shadow:-1px -1px 2px #ffffff;}
            #sdxMobile .sdxContenedorSelect{background-image:linear-gradient(bottom,#c0c0c0 0%,#c0c0c0 2%,#f8f8f8 100%);background-clip:padding-box;background-color:#f8f8f8;border:1px solid #c0c0c0;display:inline-block;color:0 1px 3px rgba(0,0,0,0.13),inset 0 1px 0 #ffffff;position:relative;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.13),inset 0 1px 0 #ffffff;}
            #sdxMobile .sdxContenedorSelect select{-moz-appearance:window;-webkit-appearance:none;appearance:none;-moz-padding-end:19px;background:transparent;background-color:transparent;border:none;display:block;float:left;font-size:2.6em;line-height:60px;margin: 0;padding:5px 25px 4px 5px;position:relative;z-index:2;min-width:640px;max-width:780px;height:70px;}
            -->
        </style>
    </head>
    <body>
        <div id="sdxMobile">
            <h1>Registro de Capacitación CONRICyT</h1>
            <form id="Forma1" name="Forma1" action="#" method="post" onsubmit="return Valida();">
                <p>
                    <label for="nombre">Nombre(s):</label>
                    <br><input id="nombre" name="nombre" type="text" maxlength="50" placeholder="El nombre como aparecerá en su constancia" required="required">
                </p>
                <p>
                    <label for="ap_paterno">Apellido paterno:</label>
                    <br><input id="ap_paterno" name="ap_paterno" type="text" maxlength="50" placeholder="Su apellido paterno" required="required">
                </p>
                <p>
                    <label for="ap_materno">Apellido materno:</label>
                    <br><input id="ap_materno" name="ap_materno" type="text" maxlength="50" placeholder="Su apellido materno">
                </p>
                <p>
                    <label for="sexo">Sexo:</label>
                    <br>
                <span class="sdxContenedorSelect">
                    <select id="sexo" name="sexo" required="required">
                        <option value="">- Indique su sexo -</option>
                        <option value="masculino">Masculino</option>
                        <option value="femenino">Femenino</option>
                    </select>
                </span><!-- class_sdxContenedorSelect -->
                </p>
                <!--
                <p>
                   <label for="telefono">Teléfono:</label>
                   <br><input id="telefono" name="telefono" type="tel" maxlength="10" placeholder="+52 55 1234-5678">
                </p>
                -->
                <p>
                    <label for="correo">Correo:</label>
                    <br><input id="correo" name="correo" type="email" maxlength="50" placeholder="usuario@institucion.ext.mx" required="required">
                </p>
                <p>
                    <label for="id_pais">País:</label>
                    <br>
                <span class="sdxContenedorSelect">
                    <select id="id_pais" name="id_pais" required="required" onChange="verificapais();">
                        <option value="" selected="selected">- Elija su país -</option>
                        <?php
                        $comando0 = 'SELECT id_pais, pais FROM cat_paises ORDER BY pais';
                        $result0 = mysqli_query($dbh, $comando0);
                        while ($row0 = mysqli_fetch_array($result0, MYSQLI_ASSOC)) {
                            $id_paisX = $row0['id_pais'];
                            $paisX = $row0['pais'];
                            ?>
                            <option value="<?php echo $id_paisX; ?>" <?php //if ($id_pais==$id_paisX) { echo 'selected="selected"'; }  ?>><?php echo $paisX; ?></option>
                        <?php } mysqli_free_result($result0); ?>
                    </select>
                </span><!-- class_sdxContenedorSelect -->
                </p>
                <p id="estado" style="display:<?php if ($id_paisX == '156') { ?>;<?php } else { ?>none;<?php } ?>">
                    <label for="id_entidad">Estado:</label>                
                    <br>
                <span class="sdxContenedorSelect">
                    <select id="id_entidad" name="id_entidad" required="required">
                        <option value="" selected="selected">- Elija su estado -</option>
                        <?php
                        $comando = 'SELECT id_entidad, entidad FROM entidad ORDER BY entidad';
                        $result = mysqli_query($dbh, $comando);
                        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                            $id_entidadX = $row['id_entidad'];
                            $entidadX = $row['entidad'];
                            ?>
                            <option value="<?php echo $id_entidadX; ?>" <?php //if ($id_entidad==$id_entidadX) { echo 'selected="selected"'; }  ?>><?php echo $entidadX; ?></option>
                        <?php } mysqli_free_result($result); ?>
                    </select>
                </span><!-- class_sdxContenedorSelect -->
                </p>
                <p>
                    <label for="id_perfil">Perfil:</label> 
                    <br>
                <span class="sdxContenedorSelect">
                    <select id="id_perfil" name="id_perfil" required="required">
                        <option value="" selected="selected">- Elija su perfil -</option>
                        <?php
                        $comando1 = 'SELECT id_perfil, perfil FROM cat_perfil ORDER BY perfil';
                        $result1 = mysqli_query($dbh, $comando1);
                        while ($row1 = mysqli_fetch_array($result1, MYSQLI_ASSOC)) {
                            $id_perfilX = $row1['id_perfil'];
                            $perfilX = $row1['perfil'];
                            ?>
                            <option value="<?php echo $id_perfilX; ?>" <?php //if ($id_perfil==$id_perfilX) { echo 'selected="selected"'; }  ?>><?php echo $perfilX; ?></option>
                        <?php } mysqli_free_result($result1); ?>
                    </select>
                </span><!-- class_sdxContenedorSelect -->
                </p>
                <p>
                    <label for="id_institucion">Institución:</label> 
                    <br>
                <span class="sdxContenedorSelect">
                    <select id="id_institucion" name="id_institucion" required="required">
                        <option value="" selected="selected">- Elija su institución -</option>
                        <?php
                        $comando2 = 'SELECT id_institucion, institucion FROM institucion ORDER BY institucion';
                        $result2 = mysqli_query($dbh, $comando2);
                        while ($row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC)) {
                            $id_institucionX = $row2['id_institucion'];
                            $institucionX = $row2['institucion'];
                            ?>
                            <option value="<?php echo $id_institucionX; ?>" <?php //if ($id_institucion==$id_institucionX) { echo 'selected="selected"'; }  ?>><?php echo $institucionX; ?></option>
                        <?php } mysqli_free_result($result2); ?>
                    </select>    
                </span><!-- class_sdxContenedorSelect -->
                </p>
                <?php
                $comando3 = 'SELECT id_curso, curso FROM curso ORDER BY curso';
                $result3 = mysqli_query($dbh, $comando3);
                while ($row3 = mysqli_fetch_array($result3, MYSQLI_ASSOC)) {
                    $id_curso = $row3['id_curso'];
                    $curso = $row3['curso'];
                    ?>            
                    <div class="sdxContenedorLista">
                        <p class="sdxLista">
                            <label for="id_curso<?php echo $id_curso; ?>"><?php echo $curso; ?></label>
                            <input id="id_curso<?php echo $id_curso; ?>" name="id_curso<?php echo $id_curso; ?>" type="checkbox" value="agreed" <?php //if(!empty($curso)) { echo 'checked="checked"'; }  ?> onchange="Revisa();">
                        </p>
                    <?php } mysqli_free_result($result3); ?>
                </div><!-- class_sdxContenedorLista -->
                <p class="sdxClearBoth">
                    <label for="login">Usuario:</label> 
                    <br><input id="login" name="login" type="text" maxlength="20" placeholder="Su nombre de usuario" required="required">
                </p>
                <p>
                    <label for="password">Contraseña:</label> 
                    <br><input id="password" name="password" type="password" placeholder="Su contraseña" required="required">
                </p>            
                <p>
                    <label for="usuarioh">Ingrese los caracteres mostrados en la imagen:</label>
                    <br>
                    <input type="text" id="capta" name="capta" size="10" maxlength="6" required="required" placeholder="Los caracteres mostrados en la imagen">
                    <a href="#" onclick="document.getElementById('captcha').src = 'securimage/securimage_show.php?' + Math.random(); return false">[ Obtener imagén diferente ]</a>
                </p>
                <!-- *** COMIENZA CAPTCHA *** -->
                <img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image">
                <!-- *** TERMINA CAPTCHA *** -->
                <br>
                <p>
                    <button onClick="Registra();">Enviar</button>
                </p>
            </form>
        </div><!-- id_sdxMobile -->
    </body>    
</html>