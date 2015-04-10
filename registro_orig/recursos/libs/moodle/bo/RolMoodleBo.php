<?php

if(!class_exists("KLogger") ){
	require_once 'recursos/libs/KLogger/KLogger.php';
}
if(!class_exists("RolBov19") ){
	include("recursos/libs/moodle/v1_9/bo/RolBov19.php");
}
if(!class_exists("RolBov22") ){
	include("recursos/libs/moodle/v2_2/bo/RolBov22.php");
}
if(!class_exists("InstructorDao") ){
	include('recursos/libs/moodle/dao/InstructorDao.php');
}
if(!class_exists("AlumnoDao") ){
	include('recursos/libs/moodle/dao/AlumnoDao.php');
}
if(!class_exists("CursoDao") ){
	include('recursos/libs/moodle/dao/CursoDao.php');
}
if(!class_exists("MoodleConstants") ){
	require_once("recursos/libs/moodle/moodle_constants.php");
}
if(!class_exists("GrupoMoodleBo") ){
	require_once 'recursos/libs/moodle/bo/GrupoMoodleBo.php';
}
if(!class_exists("AlumnoGrupoDao") ){
	include("recursos/libs/moodle/dao/AlumnoGrupoDao.php");
}
/*
 * consulta el id del curso y del instructor
* envia los id al bo de moodle
*/

class RolMoodleBo{

	function registrarUsuarioCurso(&$dbh, $Id_Curso, $Id_Usuario, $role){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogDebug("registrarUsuarioCurso-------->");
		$log->LogDebug("[registrarUsuarioCurso]role=".$role);
		if($role == TEACHER_ROLE){
			$instructorDao = new InstructorDao();
			$moodleData=$instructorDao->getMoodleId($Id_Usuario, $dbh);
			$idUserv19 =$moodleData['id_moodle19'];
			$idUserv22 =$moodleData['id_moodle22'];
			
		}else if($role == STUDENT_ROLE){
			$alumnoDao = new AlumnoDao();
			$moodleData=$alumnoDao->getMoodleId($Id_Usuario, $dbh);
			$idUserv19 =$moodleData['id_moodle19'];
			$idUserv22 =$moodleData['id_moodle22'];
		}
		
		$cursoDao = new CursoDao();
		$moodleData=$cursoDao->getMoodleId($Id_Curso, $dbh);
		$idCoursev19 =$moodleData['id_moodle19'];
		$idCoursev22 =$moodleData['id_moodle22'];
		
		$log->LogDebug("[registrarUsuarioCurso:52]idUserv19=".$idUserv19." idUserv22=".$idUserv22);
		$log->LogDebug("[registrarUsuarioCurso:53]idCoursev19=".$idCoursev19." idCoursev22=".$idCoursev22);
		
		if(MOODLE_ACTIVE==MOODLE_V1_9){
			$log->LogDebug("[RolMoodleBo:12]".MOODLE_V1_9."");
			$rolBo = new RolBov19();
			$result= $rolBo->registrar($idCoursev19, $idUserv19, $role);
			if( !$result ){
				return $result;
			}
		}else if(MOODLE_ACTIVE==MOODLE_V2_2){
			$log->LogDebug("[RolMoodleBo:19]".MOODLE_V2_2."");
			$rolBo = new RolBov22();
			$result=$rolBo->registrar0($idCoursev22, $idUserv22, $role);
			if( !$result ){
				return $result;
			}
		}else if(MOODLE_ACTIVE==MOODLE_BOTH){
			$log->LogDebug("[RolMoodleBo:26]".MOODLE_BOTH."");
			$rolBo = new RolBov19();
			$result= $rolBo->registrar($idCoursev19, $idUserv19, $role);
			if( !$result ){
				return $result;
			}
			$rolBo = new RolBov22();
			$result=$rolBo->registrar($idCoursev22, $idUserv22, $role);
			if( !$result ){
				return $result;
			}
		}
		$log->LogDebug("registrarUsuarioCurso--------<");
		return true;
	}


	function eliminar(&$dbh, $Id_Curso, $Id_Usuario, $role ){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogDebug("RolMoodleBo.eliminar-------->");
		
		if($role == TEACHER_ROLE){
			$instructorDao = new InstructorDao();
			$moodleData=$instructorDao->getMoodleId($Id_Usuario, $dbh);
			$idUserv19 =$moodleData['id_moodle19'];
			$idUserv22 =$moodleData['id_moodle22'];
		}else if($role == STUDENT_ROLE){
			$alumnoDao = new AlumnoDao();
			$moodleData=$alumnoDao->getMoodleId($Id_Usuario, $dbh);
			$idUserv19 =$moodleData['id_moodle19'];
			$idUserv22 =$moodleData['id_moodle22'];
		}
		$cursoDao = new CursoDao();
		$moodleData=$cursoDao->getMoodleId($Id_Curso, $dbh);
		$idCoursev19 =$moodleData['id_moodle19'];
		$idCoursev22 =$moodleData['id_moodle22'];
		
		$log->LogDebug("[RolMoodleBo.eliminar:107]idUserv19=".$idUserv19." idUserv22=".$idUserv22);
		$log->LogDebug("[RolMoodleBo.eliminar:108]idCoursev19=".$idCoursev19." idCoursev22=".$idCoursev22);
		
		if(MOODLE_ACTIVE==MOODLE_V1_9){
			$log->LogDebug("[RolMoodleBo:118]".MOODLE_V1_9."");
			$rolBo = new RolBov19();
			
			if($role == STUDENT_ROLE){
				$grupoMoodleBo = new GrupoMoodleBo();
				$alumnoGrupoDao = new AlumnoGrupoDao();
				$Id_Grupo = $alumnoGrupoDao->findGrupo($dbh, $Id_Curso, $Id_Usuario);
				$log->LogDebug("[RolMoodleBo:125:eliminar]Id_Grupo=".$Id_Grupo."");
				$alumnosEliminados = array();
				$o=new stdClass();
				$o->id_alumno =  $Id_Usuario;
				$alumnosEliminados[] = $o;
				$result=$grupoMoodleBo->borrarAlumnos($dbh, $Id_Grupo, $alumnosEliminados);
				if( !$result ){
					return false;
				}
			}
			
			$result=$rolBo->eliminar($idCoursev19, $idUserv19, $role);
			if( !$result ){
				return $result;
			}
			$log->LogDebug("[RolMoodleBo:245]".$result."");
		}else if(MOODLE_ACTIVE==MOODLE_V2_2){
			$log->LogDebug("[RolMoodleBo:54]".MOODLE_V2_2."");
			$rolBo = new RolBov22();
			if($role == STUDENT_ROLE){
				$grupoMoodleBo = new GrupoMoodleBo();
				$alumnoGrupoDao = new AlumnoGrupoDao();
				$Id_Grupo = $alumnoGrupoDao->findGrupo($dbh, $Id_Curso, $Id_Usuario);
			
				$alumnosEliminados = array();
				$o=new stdClass();
				$o->id_alumno =  $Id_Usuario;
				$alumnosEliminados[] = $o;
				$result=$grupoMoodleBo->borrarAlumnos($dbh, $Id_Grupo, $alumnosEliminados);
				if( !$result ){
					return false;
				}
			}
			$result=$rolBo->eliminar($idCoursev22, $idUserv22, $role);
			if( !$result ){
				return $result;
			}
			$log->LogDebug("[RolMoodleBo:154]".$result."");
		}else if(MOODLE_ACTIVE==MOODLE_BOTH){
			$log->LogDebug("[RolMoodleBo.eliminar:156]".MOODLE_BOTH."");
			
			if($role == STUDENT_ROLE){
				$grupoMoodleBo = new GrupoMoodleBo();
				$alumnoGrupoDao = new AlumnoGrupoDao();
				$Id_Grupo = $alumnoGrupoDao->findGrupo($dbh, $Id_Curso, $Id_Usuario);
					
				$alumnosEliminados = array();
				$o=new stdClass();
				$o->id_alumno =  $Id_Usuario;
				$alumnosEliminados[] = $o;
				$result=$grupoMoodleBo->borrarAlumnos($dbh, $Id_Grupo, $alumnosEliminados);
				if( !$result ){
					return false;
				}
			}
			
			$rolBo = new RolBov19();
			$result=$rolBo->eliminar($idCoursev19, $idUserv19, $role);
			if( !$result ){
				return $result;
			}
			$log->LogDebug("[RolMoodleBo.eliminar:175]".$result."");
			$rolBo = new RolBov22();
			$result=$rolBo->eliminar($idCoursev22, $idUserv22, $role);
			if( !$result ){
				return $result;
			}
			$log->LogDebug("[RolMoodleBo.eliminar:181]".$result."");
		}
		$log->LogDebug("RolMoodleBo.eliminar--------<");
		return true;
	}	
}
?>