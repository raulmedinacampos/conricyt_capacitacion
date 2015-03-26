<?php

if(!class_exists("Configv19")){
	include('recursos/libs/moodle/v1_9/moodle_config19.php');
}
if(!class_exists("GrupoDao")){
	include('recursos/libs/moodle/dao/GrupoDao.php');
}
if(!class_exists("AlumnoGrupoDao")){
	include('recursos/libs/moodle/dao/AlumnoGrupoDao.php');
}
if(!class_exists("GroupMemberDaov19")){
	include('recursos/libs/moodle/v1_9/dao/GroupMemberDaov19.php');
}
if(!class_exists("GroupDaov19")){
	include('recursos/libs/moodle/v1_9/dao/GroupDaov19.php');
}

class GroupBov19{
	function registrar($dbh, $Id_Curso, $Id_Grupo, $name){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[GroupBov19:18:registrar]--------->");
		
		$dbhm19=Sdx_ConectaBasev19();
		
		//registro del curso
		$log->LogInfo("[GroupBov19:23:registrar]registro del curso");
				
		$groupDao = new GroupDaov19();
		$group=$groupDao->save($dbhm19, $Id_Curso, $name );
		$log->LogInfo("[GroupBov19:29:save]".$group->id);
		if(is_numeric($group)){
			$log->LogError("registrar:31-ROLLBACK");
			mysqli_rollback($dbhm19);
			return false;
		}
		
		//guardando el id del curso de moodle
		$log->LogInfo("guardando el id del curso de moodle");
		$grupoDao = new GrupoDao();
		$result=$grupoDao->actualizarGruposId($group->id, $Id_Grupo, MOODLE_V1_9, $dbh);
		if(is_numeric($result)){
			$log->LogError("registrar:38-ROLLBACK");
			mysqli_rollback($dbhm19);
			return false;
		}
		
		mysqli_commit($dbhm19);
		$log->LogInfo("[GroupBov19:44:registrar]---------<");
		return true;
	}
	
	function modificar(&$dbh, $groupid, $name){
		//consultar el curso para saber si tiene la misma cantidad de secciones
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("modificar-------->");
	
		$dbhm19=Sdx_ConectaBasev19();
	
		$groupDao = new GroupDaov19();
		$group = $groupDao->update($dbhm19, $groupid, $name);
		
		if(is_numeric($group)){
			$log->LogInfo("231:modificar-ROLLBACK");
			mysqli_rollback($dbhm19);
			return false;
		}
	
		mysqli_commit($dbhm19);
		$log->LogInfo("modificar--------<");
		return true;
	}
	
	function eliminarGrupo($groupid){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[GroupBov19:eliminarGrupo]-------->");
		
		$dbhm19=Sdx_ConectaBasev19();
	
		//borrar alumnos
		$log->LogInfo("[GroupBov19:eliminarGrupo]borrar secciones");
		$groupMemberDaov19 = new GroupMemberDaov19();
		$obj = new stdClass();
		$obj->groupid = $groupid;
		$result=$groupMemberDaov19->delete($dbhm19, $obj);
		if(!$result){
			$log->LogError("[GroupBov19:eliminar]85-ROLLBACK");
			mysqli_rollback($dbhm19);
			return false;
		}
		
		$groupDao = new GroupDaov19();
		$result=$groupDao->delete($groupid, $dbhm19);
		if(!$result){
			$log->LogError("[GroupBov19:eliminar]93-ROLLBACK");
			mysqli_rollback($dbhm19);
			return false;
		}
		
		mysqli_commit($dbhm19);
		$log->LogInfo("[GroupBov19:eliminar]--------<");
		return true;
	}
	
	function registrarAlumnos($dbh, $alumnos){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[GroupBov19:105:registrarAlumno]--------->");
	
		$dbhm19=Sdx_ConectaBasev19();
	
		if( !is_array($alumnos) ){
			$alumnos[]=$alumnos;
		}
		$groupMemberDaov19 = new GroupMemberDaov19();
		$alumnoGrupoDao = new AlumnoGrupoDao();
		for($i=0; $i<count($alumnos); $i++){
			//registro del curso
			$log->LogInfo("[GroupBov19:114:registrarAlumno]registro del alumno");
			
			
			$groupStudent = $groupMemberDaov19->save($dbhm19, $alumnos[$i]->groupidv19 , $alumnos[$i]->useridv19 );
			//$log->LogInfo("[GroupBov19:118:save]".$groupStudent->id);
			if(is_numeric($groupStudent)){
				$log->LogError("registrarAlumno:116-ROLLBACK");
				mysqli_rollback($dbhm19);
				return false;
			}
			
			$result=$alumnoGrupoDao->actualizarGruposId($groupStudent->id, $alumnos[$i]->id_alumno, $alumnos[$i]->id_grupo, MOODLE_V1_9, $dbh);
			if(is_numeric($result)){
				$log->LogError("registrar:38-ROLLBACK");
				mysqli_rollback($dbhm19);
				return false;
			}
			
		}
		mysqli_commit($dbhm19);
		$log->LogInfo("[GroupBov19:126:registrar]---------<");
		return true;
	}
	
	function borrarAlumnos($dbh, $alumnos){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[GroupBov19:132:borrarAlumnos]--------->");
	
		$dbhm19=Sdx_ConectaBasev19();
	
		if( !is_array($alumnos) ){
			$alumnos[]=$alumnos;
		}
		for($i=0; $i<count($alumnos); $i++){
			//registro del curso
			$log->LogInfo("[GroupBov19:153:borrarAlumnos]registro del alumno");
			$obj = new stdClass();
			$obj->id=$alumnos[$i]->idv19;
			
			$groupMemberDaov19 = new GroupMemberDaov19();
			$groupStudent = $groupMemberDaov19->delete($dbhm19, $obj);
			$log->LogInfo("[GroupBov19:159:borrarAlumnos]".$groupStudent);
			if(is_numeric($groupStudent)){
				$log->LogError("borrarAlumnos:147-ROLLBACK");
				mysqli_rollback($dbhm19);
				return false;
			}
			
		}
		mysqli_commit($dbhm19);
		$log->LogInfo("[GroupBov19:168:borrarAlumnos]---------<");
		return true;
	}
}
?>