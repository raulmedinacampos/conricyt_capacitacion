<?php
if(!class_exists("MoodleConstants") ){
	require_once("recursos/libs/moodle/moodle_constants.php");
}
if(!class_exists("KLogger") ){
	require_once 'recursos/libs/KLogger/KLogger.php';
}
if(!class_exists("CursoDao") ){
	include("recursos/libs/moodle/dao/CursoDao.php");
}
if(!class_exists("GrupoDao") ){
	include("recursos/libs/moodle/dao/GrupoDao.php");
}
if(!class_exists("AlumnoDao") ){
	include("recursos/libs/moodle/dao/AlumnoDao.php");
}
if(!class_exists("GroupBov19") ){
	include("recursos/libs/moodle/v1_9/bo/GroupBov19.php");
}
if(!class_exists("GroupBov22") ){
	include("recursos/libs/moodle/v2_2/bo/GroupBov22.php");
}
class GrupoMoodleBo{
	
	function registrar(&$dbh, $Id_Curso, 
			$Id_Grupo, $Nombre_Grupo){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[GrupoMoodleBo:registrar:21]-------->");
		
		$cursoDao = new CursoDao();
		$moodleData=$cursoDao->getMoodleId($Id_Curso, $dbh);
		$id_moodle19 =$moodleData['id_moodle19'];
		$id_moodle22 =$moodleData['id_moodle22'];
		if( !isset($id_moodle19) ){
			$id_moodle19=0;
		}
		if( !isset($id_moodle22) ){
			$id_moodle22=0;
		}
		
		if(MOODLE_ACTIVE==MOODLE_V1_9){
			$log->LogInfo("[GrupoMoodleBo:registrar:35]".MOODLE_V1_9."");
			$groupBo = new GroupBov19();
			$result= $groupBo->registrar($dbh, $id_moodle19, $Id_Grupo, $Nombre_Grupo);
			if( !$result ){
				return $result;
			}
		}else if(MOODLE_ACTIVE==MOODLE_V2_2){
			$log->LogInfo("[GrupoMoodleBo:registrar:42]".MOODLE_V2_2."");
			$groupBo = new GroupBov22();
			$result=$groupBo->registrar($dbh, $id_moodle22, $Id_Grupo, $Nombre_Grupo, $id_moodle22);
			if( !$result ){
				return $result;
			}
		}else if(MOODLE_ACTIVE==MOODLE_BOTH){
			$log->LogInfo("[GrupoMoodleBo:registrar:49]".MOODLE_BOTH."");
			$groupBo = new GroupBov19();
			$result= $groupBo->registrar($dbh, $id_moodle19, $Id_Grupo, $Nombre_Grupo, $id_moodle19);
			$log->LogInfo("[GrupoMoodleBo:registrar:52]result=".$result."");
			if( !$result ){
				return false;
			}
			$groupBo = new GroupBov22();
			$result=$groupBo->registrar($dbh, $id_moodle22, $Id_Grupo, $Nombre_Grupo, $id_moodle22);
			$log->LogInfo("[GrupoMoodleBo:registrar:58]result=".$result."");
			if( !$result ){
				return false;
			}
		}
		$log->LogInfo("[GrupoMoodleBo:registrar:63]--------<");
		return true;
	}
	function modificar(&$dbh, $Id_Grupo, $name){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("modificar-------->");
	
		//Consultando los id de moodle
		$grupoDao = new GrupoDao();
		$moodleData=$grupoDao->getMoodleId($Id_Grupo, $dbh);
		$id_moodle19 =$moodleData['id_moodle19'];
		$id_moodle22 =$moodleData['id_moodle22'];
		$log->LogInfo("[modificar:101]ids ".$id_moodle19.": ".$id_moodle22.".");
	
		if(MOODLE_ACTIVE==MOODLE_V1_9){
			$log->LogInfo("[GrupoMoodleBo:104]".MOODLE_V1_9."");
			$groupBo = new GroupBov19();
			$result=$groupBo->modificar($dbh, $id_moodle19, $name);
			if( !$result ){
				return $result;
			}
		}else if(MOODLE_ACTIVE==MOODLE_V2_2){
			$log->LogInfo("[GrupoMoodleBo:111]".MOODLE_V2_2."");
			$groupBo = new GroupBov22();
			$result=$groupBo->modificar($dbh, $id_moodle22, $name);
			if( !$result ){
				return $result;
			}
		}else if(MOODLE_ACTIVE==MOODLE_BOTH){
			$log->LogInfo("[GrupoMoodleBo:104]".MOODLE_V1_9."");
			$groupBo = new GroupBov19();
			$result=$groupBo->modificar($dbh, $id_moodle19, $name);
			if( !$result ){
				return $result;
			}
			$groupBo = new GroupBov22();
			$result=$groupBo->modificar($dbh, $id_moodle22, $name);
			if( !$result ){
				return $result;
			}
		}
		$log->LogInfo("modificar--------<");
		return true;
	}
	
	function eliminarGrupo( &$dbh, $Id_Grupo){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[GrupoMoodleBo:eliminar]-------->");
		$grupoDao = new GrupoDao();
		$moodleData=$grupoDao->getMoodleId($Id_Grupo, $dbh);
		$id_moodle19 =$moodleData['id_moodle19'];
		$id_moodle22 =$moodleData['id_moodle22'];
		$log->LogInfo("[modificar:101]ids ".$id_moodle19.": ".$id_moodle22.".");
		
		if(MOODLE_ACTIVE==MOODLE_V1_9){
			$log->LogInfo("[GrupoMoodleBo:77]".MOODLE_V1_9."");
			$groupBo = new GroupBov19();
			$result=$groupBo->eliminarGrupo($id_moodle19);
			if( !$result ){
				return false;
			}
			$log->LogInfo("[GrupoMoodleBo:83]".$result."");
		}else if(MOODLE_ACTIVE==MOODLE_V2_2){
			$log->LogInfo("[GrupoMoodleBo:73]".MOODLE_V2_2."");
			$groupBo = new GroupBov22();
			$result=$groupBo->eliminarGrupo($id_moodle22);
			if( !$result ){
				return $result;
			}
			$log->LogInfo("[GrupoMoodleBo:91]".$result."");
		}else if(MOODLE_ACTIVE==MOODLE_BOTH){
			$log->LogInfo("[GrupoMoodleBo:93]".MOODLE_BOTH."");
			$groupBo = new GroupBov19();
			$result=$groupBo->eliminarGrupo($id_moodle19);
			if( !$result ){
				return $result;
			}
			$log->LogInfo("[GrupoMoodleBo:99]".$result."");
			$groupBo = new GroupBov22();
			$result=$groupBo->eliminarGrupo($id_moodle22);
			if( !$result ){
				return $result;
			}
			$log->LogInfo("[GrupoMoodleBo:105]".$result."");
		}
		$log->LogInfo("[GrupoMoodleBo:eliminar]--------<");
		return true;
	}
	
	function registrarAlumnos(&$dbh, $Id_Grupo, $alumnos){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[GrupoMoodleBo:registrarAlumnos:159]-------->");
		if(count($alumnos)==0){
			$log->LogInfo("[GrupoMoodleBo:registrarAlumnos:161]--------<");
			return true;
		}
		$grupoDao = new GrupoDao();
		$alumnoDao = new AlumnoDao();
		$moodleData=$grupoDao->getMoodleId($Id_Grupo, $dbh);
		$id_moodle19 =$moodleData['id_moodle19'];
		$id_moodle22 =$moodleData['id_moodle22'];
		if( !isset($id_moodle19) ){
			$id_moodle19=0;
		}
		if( !isset($id_moodle22) ){
			$id_moodle22=0;
		}
		  
		for($i=0; $i<count($alumnos);$i++){
			$alumnos[$i]->groupidv19 = $id_moodle19;
			$alumnos[$i]->groupidv22 = $id_moodle22;
			$moodleData = $alumnoDao->getMoodleId($alumnos[$i]->id_alumno, $dbh) ;
			$alumnos[$i]->useridv19 = $moodleData['id_moodle19'];
			$alumnos[$i]->useridv22 = $moodleData['id_moodle22'];
			$alumnos[$i]->id_grupo = $Id_Grupo;
		}
		
		
		if(MOODLE_ACTIVE==MOODLE_V1_9){
			$log->LogInfo("[GrupoMoodleBo:registrar:35]".MOODLE_V1_9."");
			$groupBo = new GroupBov19();
			$result= $groupBo->registrarAlumnos($dbh, $alumnos);
			if( !$result ){
				return $result;
			}
		}else if(MOODLE_ACTIVE==MOODLE_V2_2){
			$log->LogInfo("[GrupoMoodleBo:registrar:42]".MOODLE_V2_2."");
			$groupBo = new GroupBov22();
			$result=$groupBo->registrarAlumnos($dbh, $alumnos);
			if( !$result ){
				return $result;
			}
		}else if(MOODLE_ACTIVE==MOODLE_BOTH){
			$log->LogInfo("[GrupoMoodleBo:registrar:49]".MOODLE_BOTH."");
			$groupBo = new GroupBov19();
			$result= $groupBo->registrarAlumnos($dbh, $alumnos);
			$log->LogInfo("[GrupoMoodleBo:registrar:52]result=".$result."");
			if( !$result ){
				return false;
			}
			$groupBo = new GroupBov22();
			$result=$groupBo->registrarAlumnos($dbh, $alumnos);
			$log->LogInfo("[GrupoMoodleBo:registrar:58]result=".$result."");
			if( !$result ){
				return false;
			}
		}
		$log->LogInfo("[GrupoMoodleBo:registrarAlumnos:215]--------<");
		return true;
	}
	
	function borrarAlumnos(&$dbh, $Id_Grupo, $alumnos){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[GrupoMoodleBo:borrarAlumnos:223]-------->");
		
		if(!is_numeric($Id_Grupo) || !is_array($alumnos)){
			$log->LogInfo("[GrupoMoodleBo:borrarAlumnos:226]ERROR--------<");
			return false;
		}
		
		$alumnoGrupoDao = new AlumnoGrupoDao();
		
		for($i=0; $i<count($alumnos);$i++){
			$moodleData = $alumnoGrupoDao->getMoodleId($Id_Grupo, $alumnos[$i]->id_alumno, $dbh) ;
			$alumnos[$i]->idv19 = $moodleData['id_moodle19'];
			$alumnos[$i]->idv22 = $moodleData['id_moodle22'];
			unset( $alumnos[$i]->id_alumno );
		}
		if(MOODLE_ACTIVE==MOODLE_V1_9){
			$log->LogInfo("[GrupoMoodleBo:borrarAlumnos:228]".MOODLE_V1_9."");
			$groupBo = new GroupBov19();
			$result= $groupBo->borrarAlumnos($dbh, $alumnos);
			if( !$result ){
				return $result;
			}
		}else if(MOODLE_ACTIVE==MOODLE_V2_2){
			$log->LogInfo("[GrupoMoodleBo:borrarAlumnos:234]".MOODLE_V2_2."");
			$groupBo = new GroupBov22();
			$result=$groupBo->borrarAlumnos($dbh, $alumnos);
			if( !$result ){
				return $result;
			}
		}else if(MOODLE_ACTIVE==MOODLE_BOTH){
			$log->LogInfo("[GrupoMoodleBo:borrarAlumnos:242]".MOODLE_BOTH."");
			$groupBo = new GroupBov19();
			$result= $groupBo->borrarAlumnos($dbh, $alumnos);
			$log->LogInfo("[GrupoMoodleBo:registrar:52]result=".$result."");
			if( !$result ){
				return false;
			}
			$groupBo = new GroupBov22();
			$result=$groupBo->borrarAlumnos($dbh, $alumnos);
			$log->LogInfo("[GrupoMoodleBo:borrarAlumnos:251]result=".$result."");
			if( !$result ){
				return false;
			}
		}
		$log->LogInfo("[GrupoMoodleBo:borrarAlumnos:256]--------<");
		return true;
	}
}
?>