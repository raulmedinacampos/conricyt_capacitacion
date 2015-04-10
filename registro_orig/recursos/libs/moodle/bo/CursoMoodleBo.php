<?php
if(!class_exists("MoodleConstants") ){
	require_once("recursos/libs/moodle/moodle_constants.php");
}

include("recursos/libs/moodle/v1_9/bo/CourseBov19.php");
include("recursos/libs/moodle/v2_2/bo/CourseBov22.php");
if(!class_exists("CatCursoDao") ){
	include("recursos/libs/moodle/dao/CatCursoDao.php");
}
if(!class_exists("KLogger") ){
	require_once 'recursos/libs/KLogger/KLogger.php';
}
class CursoMoodleBo{
	

	function registrar(&$dbh, $idCurso, 
			$fullname, $startdate, $enddate,
			$cost, $shortname, $category=1, $idNumber='', $summary='', 
			$password='', $currency=''){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CursoMoodleBo:registrar]-------->");
		
		$catCursoDao = new CatCursoDao();
		$moodleData=$catCursoDao->getMoodleId($category, $dbh);
		$idCourseCatv19 =$moodleData['id_moodle19'];
		$idCourseCatv22 =$moodleData['id_moodle22'];
		$log->LogInfo("[CursoMoodleBo:registrar]category=$category, 1.9=$idCourseCatv19, 2.2=$idCourseCatv22");
		
		if(MOODLE_ACTIVE==MOODLE_V1_9){
			$log->LogInfo("[CursoMoodleBo:12]".MOODLE_V1_9."");
			$courseBo = new CourseBov19();
			$result= $courseBo->registrar($dbh, $idCurso, $fullname, $startdate, $enddate,$enddate, $idCourseCatv19, $shortname, $idNumber, $summary, $password, $cost, $currency);
			if( !$result ){
				return $result;
			}
		}else if(MOODLE_ACTIVE==MOODLE_V2_2){
			$log->LogInfo("[CursoMoodleBo:19]".MOODLE_V2_2."");
			$courseBo = new CourseBov22();
			$result=$courseBo->registrar($dbh, $idCurso, $fullname, $startdate, $enddate,$idCourseCatv22, $shortname, $idNumber, $summary, $password, $cost, $currency);
			if( !$result ){
				return $result;
			}
		}else if(MOODLE_ACTIVE==MOODLE_BOTH){
			$log->LogInfo("[CursoMoodleBo:26]".MOODLE_BOTH."");
			$courseBo = new CourseBov19();
			$result= $courseBo->registrar($dbh, $idCurso, $fullname, $startdate, $enddate,$idCourseCatv19, $shortname, $idNumber, $summary, $password, $cost, $currency);
			$log->LogInfo("[CursoMoodleBo:34]result=".$result."");
			if( !$result ){
				return false;
			}
			$courseBo = new CourseBov22();
			$result=$courseBo->registrar($dbh, $idCurso, $fullname, $startdate, $enddate,$idCourseCatv22, $shortname, $idNumber, $summary, $password, $cost, $currency);
			$log->LogInfo("[CursoMoodleBo:40]result=".$result."");
			if( !$result ){
				return false;
			}
		}
		$log->LogInfo("registrarConRol--------<");
		return true;
	}
	

	function eliminar( $Id_Curso, &$dbh){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("eliminar-------->");
		$cursoDao = new CursoDao();
		$cursoDao = new CursoDao();
		$curso=$cursoDao->findById($dbh, $Id_Curso);
		$idCoursev19 =$curso->id_moodle19;
		$idCoursev22 =$curso->id_moodle22;
		$log->LogInfo("[modificar:101]ids ".$idCoursev19.": ".$idCoursev22.".");
		$catCursoDao = new CatCursoDao();
		$moodleData=$catCursoDao->getMoodleId($curso->Id_CatCurso, $dbh);
		$idCourseCatv19 =$moodleData['id_moodle19'];
		$idCourseCatv22 =$moodleData['id_moodle22'];
		
		if(MOODLE_ACTIVE==MOODLE_V1_9){
			$log->LogInfo("[CursoMoodleBo:46]".MOODLE_V1_9."");
			$courseBo = new CourseBov19();
			$result=$courseBo->eliminar($idCoursev19, $idCourseCatv19);
			if( !$result ){
				return false;
			}
			$log->LogInfo("[CursoMoodleBo:245]".$result."");
		}else if(MOODLE_ACTIVE==MOODLE_V2_2){
			$log->LogInfo("[CursoMoodleBo:54]".MOODLE_V2_2."");
			$courseBo = new CourseBov22();
			$result=$courseBo->eliminar($idCoursev22, $idCourseCatv22);
			if( !$result ){
				return $result;
			}
			$log->LogInfo("[CursoMoodleBo:60]".$result."");
		}else if(MOODLE_ACTIVE==MOODLE_BOTH){
			$log->LogInfo("[CursoMoodleBo:62]".MOODLE_BOTH."");
			$courseBo = new CourseBov19();
			$result=$courseBo->eliminar($idCoursev19, $idCourseCatv19);
			if( !$result ){
				return $result;
			}
			$log->LogInfo("[CursoMoodleBo:68]".$result."");
			$courseBo = new CourseBov22();
			$result=$courseBo->eliminar($idCoursev22, $idCourseCatv22);
			if( !$result ){
				return $result;
			}
			$log->LogInfo("[CursoMoodleBo:74]".$result."");
		}
		$log->LogInfo("eliminar--------<");
		return true;
	}
	
	function modificar(&$dbh, $idCurso , 
			$fullname, $startdate, $enddate,
			$cost, $shortname, $category=1, $idNumber='', $summary='', 
			$password='', $currency=''){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("modificar-------->");

		//Consultando los id de moodle
		$cursoDao = new CursoDao();
		$curso=$cursoDao->findById($dbh, $idCurso);
		$id_moodle19 =$curso->id_moodle19;
		$id_moodle22 =$curso->id_moodle22;
		$log->LogInfo("[modificar:101]ids ".$id_moodle19.": ".$id_moodle22.".");
		$catCursoDao = new CatCursoDao();
		$moodleData=$catCursoDao->getMoodleId($curso->Id_CatCurso, $dbh);
		$idCourseCatv19 =$moodleData['id_moodle19'];
		$idCourseCatv22 =$moodleData['id_moodle22'];
		
		if(MOODLE_ACTIVE==MOODLE_V1_9){
			$log->LogInfo("[CursoMoodleBo:104]".MOODLE_V1_9."");
			$courseBo = new CourseBov19();
			$result=$courseBo->modificar($dbh, $id_moodle19, $fullname, $startdate, $enddate,$idCourseCatv19, $shortname, $idNumber, $summary, $password, $cost, $currency);
			if( !$result ){
				return $result;
			}
		}else if(MOODLE_ACTIVE==MOODLE_V2_2){
			$log->LogInfo("[CursoMoodleBo:111]".MOODLE_V2_2."");
			$courseBo = new CourseBov22();
			$result=$courseBo->modificar($dbh, $id_moodle22, $fullname, $startdate, $enddate,$idCourseCatv22, $shortname, $idNumber, $summary, $password, $cost, $currency);
			if( !$result ){
				return $result;
			}
		}else if(MOODLE_ACTIVE==MOODLE_BOTH){
			$log->LogInfo("[CursoMoodleBo:118]".MOODLE_BOTH."");
			$courseBo = new CourseBov19();
			$result=$courseBo->modificar($dbh, $id_moodle19, $fullname, $startdate, $enddate,$idCourseCatv19, $shortname, $idNumber, $summary, $password, $cost, $currency);
			if( !$result ){
				return $result;
			}
			$courseBo = new CourseBov22();
			$result=$courseBo->modificar($dbh, $id_moodle22, $fullname, $startdate, $enddate,$idCourseCatv22, $shortname, $idNumber, $summary, $password, $cost, $currency);
			if( !$result ){
				return $result;
			}
		}
		$log->LogInfo("modificar--------<");
		return true;
	}
}
?>