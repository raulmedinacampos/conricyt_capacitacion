<?php

if(!class_exists("KLogger") ){
	require_once 'recursos/libs/KLogger/KLogger.php';
}

if(!class_exists("MoodleConstants") ){
	require_once("recursos/libs/moodle/moodle_constants.php");
}

include("recursos/libs/moodle/v1_9/bo/UserBov19.php");
include("recursos/libs/moodle/v2_2/bo/UserBov22.php");
include('recursos/libs/moodle/dao/InstructorDao.php');
if(!class_exists("AlumnoDao") ){
	include('recursos/libs/moodle/dao/AlumnoDao.php');
}

class UsuarioMoodleBo{

	function registrarConRol($username, $password, $Nombre, $Apaterno, $Amaterno, $email, $Lugar_Residencia, $Id_Pais, $role, $Id, &$dbh, $shortnames){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("registrarConRol-------->");
		if(MOODLE_ACTIVE==MOODLE_V1_9){
			$log->LogInfo("[UsuarioMoodleBo:12]".MOODLE_V1_9."");
			$usuarioBo = new UserBov19();
			$result= $usuarioBo->registrar($username, $password, $Nombre, $Apaterno, $Amaterno, $email, $Lugar_Residencia, $Id_Pais, $role, $Id, $dbh);
			if( !$result ){
				return $result;
			}
		}else if(MOODLE_ACTIVE==MOODLE_V2_2){
			$log->LogInfo("[UsuarioMoodleBo:19]".MOODLE_V2_2."");
			$usuarioBo = new UserBov22();
			$result=$usuarioBo->registrar($username, $password, $Nombre, $Apaterno, $Amaterno, $email, $Lugar_Residencia, $Id_Pais, $role, $Id, $dbh, $shortnames);
			if( !$result ){
				return $result;
			}
		}else if(MOODLE_ACTIVE==MOODLE_BOTH){
			$log->LogInfo("[UsuarioMoodleBo:26]".MOODLE_BOTH."");
			$usuarioBo = new UserBov19();
			$result= $usuarioBo->registrar($username, $password, $Nombre, $Apaterno, $Amaterno, $email, $Lugar_Residencia, $Id_Pais, $role, $Id, $dbh);
			if( !$result ){
				return $result;
			}
			$usuarioBo = new UserBov22();
			$result=$usuarioBo->registrar($username, $password, $Nombre, $Apaterno, $Amaterno, $email, $Lugar_Residencia, $Id_Pais, $role, $Id, $dbh);
			if( !$result ){
				return $result;
			}
		}
		$log->LogInfo("registrarConRol--------<");
		return true;
	}


	function eliminar($id, $role, &$dbh ){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("eliminar-------->");
		
		if($role == TEACHER_ROLE){
			$instructorDao = new InstructorDao();
			$moodleData=$instructorDao->getMoodleId($id, $dbh);
			$idUserv19 =$moodleData['id_moodle19'];
			$idUserv22 =$moodleData['id_moodle22'];
		}else if($role == STUDENT_ROLE){
			$alumnoDao = new AlumnoDao();
			$moodleData=$alumnoDao->getMoodleId($id, $dbh);
			$idUserv19 =$moodleData['id_moodle19'];
			$idUserv22 =$moodleData['id_moodle22'];
		}
			
		if(MOODLE_ACTIVE==MOODLE_V1_9){
			$log->LogInfo("[UsuarioMoodleBo:46]".MOODLE_V1_9."");
			$usuarioBo = new UserBov19();
			$result=$usuarioBo->eliminar($idUserv19);
			if( !$result ){
				return $result;
			}
			$log->LogInfo("[UsuarioMoodleBo:245]".$result."");
		}else if(MOODLE_ACTIVE==MOODLE_V2_2){
			$log->LogInfo("[UsuarioMoodleBo:54]".MOODLE_V2_2."");
			$usuarioBo = new UserBov22();
			$result=$usuarioBo->eliminar($idUserv22);
			if( !$result ){
				return $result;
			}
			$log->LogInfo("[UsuarioMoodleBo:60]".$result."");
		}else if(MOODLE_ACTIVE==MOODLE_BOTH){
			$log->LogInfo("[UsuarioMoodleBo:62]".MOODLE_BOTH."");
			$usuarioBo = new UserBov19();
			$result=$usuarioBo->eliminar($idUserv19);
			if( !$result ){
				return $result;
			}
			$log->LogInfo("[UsuarioMoodleBo:68]".$result."");
			$usuarioBo = new UserBov22();
			$result=$usuarioBo->eliminar($idUserv22);
			if( !$result ){
				return $result;
			}
			$log->LogInfo("[UsuarioMoodleBo:74]".$result."");
		}
		$log->LogInfo("eliminar--------<");
		return true;
	}

	function modificar($email, $password, $Nombre, $Apaterno, $Amaterno, $Lugar_Residencia, $Id_Pais, $Id, $role, &$dbh){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("modificar-------->");

		//Consultando los id de moodle
		if($role == TEACHER_ROLE){
			$instructorDao = new InstructorDao();
			$moodleData=$instructorDao->getMoodleId($Id, $dbh);
			$id_moodle19 =$moodleData['id_moodle19'];
			$id_moodle22 =$moodleData['id_moodle22'];
		}else if($role == STUDENT_ROLE){
			$alumnoDao = new AlumnoDao();
			$moodleData=$alumnoDao->getMoodleId($Id, $dbh);
			$id_moodle19 =$moodleData['id_moodle19'];
			$id_moodle22 =$moodleData['id_moodle22'];
		}		
		
		$log->LogInfo("[modificar:101]ids ".$id_moodle19.": ".$id_moodle22.".");

		if(MOODLE_ACTIVE==MOODLE_V1_9){
			$log->LogInfo("[UsuarioMoodleBo:104]".MOODLE_V1_9."");
			$usuarioBo = new UserBov19();
			$result=$usuarioBo->modificar($email, $password, $Nombre, $Apaterno, $Amaterno, $Lugar_Residencia, $Id_Pais, $id_moodle19, $dbh);
			if( !$result ){
				return $result;
			}
		}else if(MOODLE_ACTIVE==MOODLE_V2_2){
			$log->LogInfo("[UsuarioMoodleBo:111]".MOODLE_V2_2."");
			$usuarioBo = new UserBov22();
			$result=$usuarioBo->modificar($email, $password, $Nombre, $Apaterno, $Amaterno, $Lugar_Residencia, $Id_Pais, $id_moodle22, $dbh);
			if( !$result ){
				return $result;
			}
		}else if(MOODLE_ACTIVE==MOODLE_BOTH){
			$log->LogInfo("[UsuarioMoodleBo:118]".MOODLE_BOTH."");
			$usuarioBo = new UserBov19();
			$result=$usuarioBo->modificar($email, $password, $Nombre, $Apaterno, $Amaterno, $Lugar_Residencia, $Id_Pais, $id_moodle19, $dbh);
			if( !$result ){
				return $result;
			}
			$usuarioBo = new UserBov22();
			$result=$usuarioBo->modificar($email, $password, $Nombre, $Apaterno, $Amaterno, $Lugar_Residencia, $Id_Pais, $id_moodle22, $dbh);
			if( !$result ){
				return $result;
			}
		}
		$log->LogInfo("modificar--------<");
		return true;
	}
	
	function importar($dbh, $role ){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("importar-------->");
		
		//buscar todos los alumnos cargados en moodle que no estan en nuestra bd
		//consultar todos los ids de los alumnos
		$alumnoDao = new AlumnoDao();
		$arrAlumnos=$alumnoDao->findMoodleIds($dbh);
		if(MOODLE_ACTIVE==MOODLE_V1_9){
			$ids='';
			for($i=0;$i<count($arrAlumnos);$i++){
				$ids.= ($arrAlumnos[$i]->id_moodle19==null?'':$arrAlumnos[$i]->id_moodle19.',');
			}
			if(strlen($ids)==0){
				return;
			}
			$ids= substr($ids, 0, strlen($ids)-1);
			$log->LogInfo("[importar:171]ids=".$ids);
			$userBov19 = new UserBov19();
			$users = $userBov19->getUsers($ids);
			for($i=0;$i<count($users);$i++){
				$id=$alumnoDao->insertFromMoodle($dbh, $users[$i]->firstname, $users[$i]->lastname, $users[$i]->country, $users[$i]->email, $users[$i]->username, $users[$i]->password, $users[$i]->id, true);
				$log->LogInfo("[importar:171]id=".$id);
			}
		}else if(MOODLE_ACTIVE==MOODLE_V2_2 || MOODLE_ACTIVE==MOODLE_BOTH){
			$ids='';
			for($i=0;$i<count($arrAlumnos);$i++){
				$ids.=($arrAlumnos[$i]->id_moodle22==null?'':$arrAlumnos[$i]->id_moodle22.',');
			}
			if(strlen($ids)==0){
				return;
			}
			$ids= substr($ids, 0, strlen($ids)-1);
			$log->LogInfo("[importar:187]ids=".$ids);
			$userBov22 = new UserBov22();
			$users = $userBov22->getUsers($ids);
			for($i=0;$i<count($users);$i++){
				$id=$alumnoDao->insertFromMoodle($dbh, $users[$i]->firstname, $users[$i]->lastname, $users[$i]->country,  $users[$i]->email, $users[$i]->username, $users[$i]->password, $users[$i]->id, false);
				$log->LogInfo("[importar:171]id=".$id);
			}
		}
		$log->LogInfo("importar--------<");
	}
}
?>