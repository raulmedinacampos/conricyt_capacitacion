<?php

if(!class_exists("MoodleConstants") ){
	require_once("recursos/libs/moodle/moodle_constants.php");
}

if(!class_exists("KLogger") ){
	require_once 'recursos/libs/KLogger/KLogger.php';
}
if(!class_exists("CatCursoDao") ){
	include("recursos/libs/moodle/dao/CatCursoDao.php");
}

if(!class_exists("CourseCategoryBov19") ){
	include("recursos/libs/moodle/v1_9/bo/CourseCategoryBov19.php");
}
if(!class_exists("CourseCategoryBov22") ){
	include("recursos/libs/moodle/v2_2/bo/CourseCategoryBov22.php");
}
class CourseCategoryMoodleBo{
	
	function registrar(&$dbh, $Id_CatCurso, 
			$name, $parent, $description){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("registrarConRol-------->");
		
		$catCursoDao = new CatCursoDao();
		$moodleData=$catCursoDao->getMoodleId($parent, $dbh);
		$id_moodle19 =$moodleData['id_moodle19'];
		$id_moodle22 =$moodleData['id_moodle22'];
		if( !isset($id_moodle19) ){
			$id_moodle19=0;
		}
		if( !isset($id_moodle22) ){
			$id_moodle22=0;
		}
		
		if(MOODLE_ACTIVE==MOODLE_V1_9){
			$log->LogInfo("[CategoriaCursoMoodleBo:12]".MOODLE_V1_9."");
			$courseCategoryBo = new CourseCategoryBov19();
			$result= $courseCategoryBo->registrar($dbh, $Id_CatCurso, $name, $id_moodle19, $description);
			if( !$result ){
				return $result;
			}
		}else if(MOODLE_ACTIVE==MOODLE_V2_2){
			$log->LogInfo("[CategoriaCursoMoodleBo:19]".MOODLE_V2_2."");
			$courseCategoryBo = new CourseCategoryBov22();
			$result=$courseCategoryBo->registrar($dbh, $Id_CatCurso, $name, $id_moodle22, $description);
			if( !$result ){
				return $result;
			}
		}else if(MOODLE_ACTIVE==MOODLE_BOTH){
			$log->LogInfo("[CategoriaCursoMoodleBo:26]".MOODLE_BOTH."");
			$courseCategoryBo = new CourseCategoryBov19();
			$result= $courseCategoryBo->registrar($dbh, $Id_CatCurso, $name, $id_moodle19, $description);
			$log->LogInfo("[CategoriaCursoMoodleBo:34]result=".$result."");
			if( !$result ){
				return false;
			}
			$courseCategoryBo = new CourseCategoryBov22();
			$result=$courseCategoryBo->registrar($dbh, $Id_CatCurso, $name, $id_moodle22, $description);
			$log->LogInfo("[CategoriaCursoMoodleBo:40]result=".$result."");
			if( !$result ){
				return false;
			}
		}
		$log->LogInfo("registrarConRol--------<");
		return true;
	}
	

	function eliminar( $Id_CatCurso, &$dbh){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CategoriaCursoMoodleBo:eliminar]-------->");
		$catCursoDao = new CatCursoDao();
		$moodleData=$catCursoDao->getMoodleId($Id_CatCurso, $dbh);
		$idCoursev19 =$moodleData['id_moodle19'];
		$idCoursev22 =$moodleData['id_moodle22'];
		$log->LogInfo("[CategoriaCursoMoodleBo:eliminar] Id_CatCurso=$Id_CatCurso, v1.9=$idCoursev19, v2.2=$idCoursev22");
		
		if(MOODLE_ACTIVE==MOODLE_V1_9){
			$log->LogInfo("[CategoriaCursoMoodleBo:77]".MOODLE_V1_9."");
			$courseCategoryBo = new CourseCategoryBov19();
			$result=$courseCategoryBo->eliminar($idCoursev19);
			if( !$result ){
				return false;
			}
			$log->LogInfo("[CategoriaCursoMoodleBo:83]".$result."");
		}else if(MOODLE_ACTIVE==MOODLE_V2_2){
			$log->LogInfo("[CategoriaCursoMoodleBo:73]".MOODLE_V2_2."");
			$courseCategoryBo = new CourseCategoryBov22();
			$result=$courseCategoryBo->eliminar($idCoursev22);
			if( !$result ){
				return $result;
			}
			$log->LogInfo("[CategoriaCursoMoodleBo:91]".$result."");
		}else if(MOODLE_ACTIVE==MOODLE_BOTH){
			$log->LogInfo("[CategoriaCursoMoodleBo:93]".MOODLE_BOTH."");
			$courseCategoryBo = new CourseCategoryBov19();
			$result=$courseCategoryBo->eliminar($idCoursev19);
			if( !$result ){
				return $result;
			}
			$log->LogInfo("[CategoriaCursoMoodleBo:99]".$result."");
			$courseCategoryBo = new CourseCategoryBov22();
			$result=$courseCategoryBo->eliminar($idCoursev22);
			if( !$result ){
				return $result;
			}
			$log->LogInfo("[CategoriaCursoMoodleBo:105]".$result."");
		}
		$log->LogInfo("[CategoriaCursoMoodleBo:eliminar]--------<");
		return true;
	}
	
	function modificar(&$dbh, $Id_CatCurso , 
			$name, $parent, $description){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("modificar-------->");

		//Consultando los id de moodle
		$catCursoDao = new CatCursoDao();
		$moodleData=$catCursoDao->getMoodleId($Id_CatCurso, $dbh);
		$id_moodle19 =$moodleData['id_moodle19'];
		$id_moodle22 =$moodleData['id_moodle22'];
		$log->LogInfo("[modificar:101]ids ".$id_moodle19.": ".$id_moodle22.".");

		if(MOODLE_ACTIVE==MOODLE_V1_9){
			$log->LogInfo("[CategoriaCursoMoodleBo:104]".MOODLE_V1_9."");
			$courseCategoryBo = new CourseCategoryBov19();
			$result=$courseCategoryBo->modificar($id_moodle19, $name, $parent, $description);
			if( !$result ){
				return $result;
			}
		}else if(MOODLE_ACTIVE==MOODLE_V2_2){
			$log->LogInfo("[CategoriaCursoMoodleBo:111]".MOODLE_V2_2."");
			$courseCategoryBo = new CourseCategoryBov22();
			$result=$courseCategoryBo->modificar($id_moodle22, $name, $parent, $description);
			if( !$result ){
				return $result;
			}
		}else if(MOODLE_ACTIVE==MOODLE_BOTH){
			$log->LogInfo("[CategoriaCursoMoodleBo:118]".MOODLE_BOTH."");
			$courseCategoryBo = new CourseCategoryBov19();
			$result=$courseCategoryBo->modificar($id_moodle19, $name, $parent, $description);
			if( !$result ){
				return $result;
			}
			$courseCategoryBo = new CourseCategoryBov22();
			$result=$courseCategoryBo->modificar($id_moodle22, $name, $parent, $description);
			if( !$result ){
				return $result;
			}
		}
		$log->LogInfo("modificar--------<");
		return true;
	}
	
}
?>