<?php

include('recursos/libs/moodle/v1_9/moodle_config19.php');
include("recursos/libs/moodle/v1_9/dao/RoleDaov19.php");
include('recursos/libs/moodle/v1_9/dao/ContextDaov19.php');

class RolBov19 {
	
	
	function registrar($idCourse, $idUser, $role){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[RolBov19:12:registrar]--------->");
		
		if($role!=TEACHER_ROLE && $role!=STUDENT_ROLE ){
			$log->LogInfo("[RolBov19:15:registrar]rol inadecuado");
			return false;
		}
		$dbhm19=Sdx_ConectaBasev19();
		//buscando el context del curso
		$contextDao = new ContextDaov19();
		$log->LogInfo("[RolBov19:31:registrar]buscando el context del curso");
		$CONTEXT_COURSE = 50;
		$contextCurso = $contextDao->find($CONTEXT_COURSE, $idCourse, $dbhm19);
		$log->LogInfo("[RolBov19:25:registrar]".$contextCurso->id."");
		
		//si ya esta enrolado, no lo vuelvas a hacer
		$roleDao = new RoleDaov19();
		$arrData=$roleDao->find($idUser, $dbhm19);
		for($i=0;$i<count($arrData);$i++){
			if($arrData[$i]->contextid==$contextCurso->id){
				//ya estaba registrado
				return true;
			}
		}
		
		$log->LogInfo("[RolBov19:40:registrar]Creando rol");
		$roleData = $roleDao->save($role, $contextCurso->id, $idUser, $dbhm19);
		if(is_numeric($roleData)){
			$log->LogError("registrar:30-ROLLBACK");
			mysqli_rollback($dbhm19);
			return false;
		}
				
		mysqli_commit($dbhm19);
		$log->LogInfo("[RolBov19:74:registrar]---------<");
		return true;
	}
	
	/**
	* Eliminar
	*/
	function eliminar($idCourse, $idUser, $role){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("eliminar-------->");
		$dbhm19=Sdx_ConectaBasev19();
	
		$contextDao = new ContextDaov19();
		$log->LogInfo("[RolBov19:49:eliminar]buscando el context del curso");
		$CONTEXT_COURSE = 50;
		$contextCurso = $contextDao->find($CONTEXT_COURSE, $idCourse, $dbhm19);
		$log->LogInfo("[RolBov19:52:eliminar]".$contextCurso->id."");
		
		//role
		$roleDao = new RoleDaov19();
		$result=$roleDao->deleteByContextAndUser($dbhm19, $contextCurso->id, $idUser, $role);
		if(!$result){
			$log->LogError("eliminar-ROLLBACK");
			mysqli_rollback($dbhm19);
			return false;
		}
	
		mysqli_commit($dbhm19);
		$log->LogInfo("eliminar--------<");
		return true;
	}
	
}
?>