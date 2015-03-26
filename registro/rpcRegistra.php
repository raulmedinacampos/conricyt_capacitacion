<?php
include_once('recursos/conf/conf.php');
include_once('recursos/general.php');
include_once('recursos/sessionHandlerDB.php');
include_once('recursos/securimage/securimagen.php');
include_once('recursos/libs/moodle/bo/UsuarioMoodleBo.php');
include_once('mailer.php');
if (!class_exists("KLogger")) {
    require_once 'recursos/libs/KLogger/KLogger.php';
}

$sess = new SessionManager();
session_start();

$dbh=Sdx_ConectaBase();
mysqli_set_charset($dbh, "utf8");

   $flagErrValid = 0;
    
    if (filter_has_var(INPUT_POST, 'nombre')) { $nombre = Sdx_NormalizaCampos($_POST['nombre']); } else { $flagErrValid = 1;}
    if (filter_has_var(INPUT_POST, 'ap_paterno')) { $ap_paterno = Sdx_NormalizaCampos($_POST['ap_paterno']); } else { $flagErrValid = 1;}
    if (filter_has_var(INPUT_POST, 'ap_materno')) { $ap_materno = Sdx_NormalizaCampos($_POST['ap_materno']); } else { $flagErrValid = 1;}
    if (filter_has_var(INPUT_POST, 'sexo')) { $sexo = $_POST['sexo']; } else { $flagErrValid = 1;}
    if (filter_has_var(INPUT_POST, 'correo')) { $correo = trim($_POST['correo']); } else { $flagErrValid = 1;}
    if (filter_has_var(INPUT_POST, 'id_pais')) { $id_pais = $_POST['id_pais']; } else { $flagErrValid = 1;}
    if (filter_has_var(INPUT_POST, 'id_entidad')) { $id_entidad = $_POST['id_entidad']; } else { $flagErrValid = 1;}
    if (filter_has_var(INPUT_POST, 'id_perfil')) { $id_perfil = $_POST['id_perfil']; } else { $flagErrValid = 1;}
    if (filter_has_var(INPUT_POST, 'perfil')) { $perfil = $_POST['perfil']; } else { $flagErrValid = 1;}
    if (filter_has_var(INPUT_POST, 'id_institucion')) { $id_institucion = $_POST['id_institucion']; } else { $flagErrValid = 1;}
    if (filter_has_var(INPUT_POST, 'institucion')) { $institucion = $_POST['institucion']; } else { $flagErrValid = 1;}
	
	$comando3 = 'SELECT id_curso, nombre_corto FROM curso';
    $result3 = mysqli_query ($dbh, $comando3);
    $cursos = array();
	$shortnames = array();
    $a=0;
    while($row3 = mysqli_fetch_array($result3, MYSQLI_ASSOC)){
        $id_curson=$row3['id_curso'];
        if (filter_has_var(INPUT_POST, 'id_curso_'.$id_curson)) 
        {
            if ( $_POST['id_curso_'.$id_curson]==false || $_POST['id_curso_'.$id_curson]=="false" ) {
                $valor = 0;
            } else {
                $valor = 1;
            }
            if($valor==1) {
                $cursos[$id_curson] = $valor;
				array_push($shortnames, $row3['nombre_corto']);
            }
        }
    }
	
    if (filter_has_var(INPUT_POST, 'capta')) { $capta = Sdx_NormalizaCampos($_POST['capta']);} else {$flagErrValid = 1;}
   
    $securimage = new Securimage();
        if ($securimage->check($capta) == false) {
            exit('{"Msg":"Ingrese el texto de la imagen de forma correcta"}');
        }
   
    if($flagErrValid==0){
      if(strlen($nombre)<1){$flagErrValid=1;}
      if(strlen($ap_paterno)<1){$flagErrValid=1;}
      if(strlen($correo)<1){$flagErrValid=1;}
    }
    
    
    if ($flagErrValid == 1) {
            exit('{"Msg":"ERROR: Por favor llene bien los campos requeridos"}');
        }  
        
   if ($id_entidad ==""){ 
       $id_entidad='33';
   }
        
    
	$Contra = createPassword(10);
	
	// Verificamos que el usuario no esté registrado	
	$stmt = mysqli_stmt_init($dbh);
    mysqli_stmt_prepare($stmt, 'SELECT correo FROM usuario WHERE correo =  ?');
    mysqli_stmt_bind_param($stmt, 's', $correo);
    mysqli_stmt_execute($stmt);
	mysqli_stmt_store_result($stmt);
	if(mysqli_stmt_num_rows($stmt) > 0) {
		exit('{"Msg":"ERROR: Este usuario ya se encuentra registrado"}');
	}
    mysqli_stmt_close($stmt);
	
	
    $stmt = mysqli_stmt_init($dbh);
    mysqli_stmt_prepare($stmt, 'INSERT INTO usuario (fecha_alta, nombre, ap_paterno, ap_materno, sexo, correo, password, id_perfil, id_pais, id_entidad, id_institucion,institucion,perfil) VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    mysqli_stmt_bind_param($stmt, 'ssssssiiiiss', $nombre, $ap_paterno, $ap_materno, $sexo, $correo, $Contra, $id_perfil, $id_pais, $id_entidad, $id_institucion, $institucion, $perfil);
    mysqli_stmt_execute($stmt);
    $id_usuario = mysqli_insert_id($dbh);
    mysqli_stmt_close($stmt);
    
    foreach ($cursos as $curso => $valor) {
        $stmt = mysqli_stmt_init($dbh);
        mysqli_stmt_prepare($stmt, 'INSERT INTO usuario_curso (usuario, curso, fecha_inscripcion) VALUES (?, ?, NOW())');
        mysqli_stmt_bind_param($stmt, 'ii', $id_usuario, $curso);
        mysqli_stmt_execute($stmt);        
        mysqli_stmt_close($stmt);
    }
    
//Registra a moodle
$Region = 'Metropolitana';
$Procedencia = 'UNAM';
$Procedencia_Esp = 'ABC';
$log = new KLogger(LOGGER_FILE, LOGGER_LEVEL);
$log->LogInfo("registrar usuario de alumno");
$usuarioMoodleBo = new UsuarioMoodleBo();
$result = $usuarioMoodleBo->registrarConRol($correo, $Contra, $nombre, $ap_paterno, $ap_materno, $correo, $Region . ', ' . $Procedencia . ', ' . $Procedencia_Esp, $id_pais, STUDENT_ROLE, $id_usuario, $dbh, $shortnames);
if (!$result) {
    exit('{"Msg":"ERROR al registrar en moodle"}');
}
$log->LogInfo("Registro del usuario del alumno");


    $CadJSON = '{"Msg":"OK",';
    $CadJSON.='"id_usuario":"' . Sdx_TextFld2JSON($id_usuario) . '",';
    $CadJSON.='"nombre":"' . Sdx_TextFld2JSON($nombre) . '",';
    $CadJSON.='"ap_paterno":"' . Sdx_TextFld2JSON($ap_paterno) . '",';
    $CadJSON.='"ap_materno":"' . Sdx_TextFld2JSON($ap_materno) . '",';
    $CadJSON.='"sexo":"' . Sdx_TextFld2JSON($sexo) . '",';
    $CadJSON.='"correo":"' . Sdx_TextFld2JSON($correo) . '",';
    $CadJSON.='"id_pais":"' . Sdx_TextFld2JSON($id_pais) . '",';
    $CadJSON.='"id_entidad":"' . Sdx_TextFld2JSON($id_entidad) . '",';
    $CadJSON.='"id_perfil":"' . Sdx_TextFld2JSON($id_perfil) . '",';
    $CadJSON.='"id_institucion":"' . Sdx_TextFld2JSON($id_institucion) . '",';   
    $CadJSON.='"capta":"' . Sdx_TextFld2JSON($capta) . '"';    
    $CadJSON.='}';

    echo $CadJSON;
	
	$datos_correo = array();
	$datos_correo['nombre'] = trim($nombre." ".$ap_paterno." ".$ap_materno);
	$datos_correo['correo'] = $correo;
	$datos_correo['pass'] = $Contra;
	$mailer = new Mailer();
	//$mailer->enviaRegistro($datos_correo);
?>
