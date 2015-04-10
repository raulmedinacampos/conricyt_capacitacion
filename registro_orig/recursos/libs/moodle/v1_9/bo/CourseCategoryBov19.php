<?php

if(!class_exists("Configv19")){
	include('recursos/libs/moodle/v1_9/moodle_config19.php');
}
if(!class_exists("CourseCategoryDaov19")){
	include('recursos/libs/moodle/v1_9/dao/CourseCategoryDaov19.php');
}
if(!class_exists("ContextDaov19")){
	include('recursos/libs/moodle/v1_9/dao/ContextDaov19.php');
}
if(!class_exists("GeneralDaov19")){
	include('recursos/libs/moodle/v1_9/dao/GeneralDaov19.php');
}
if(!class_exists("CourseBov19") ){
	include("recursos/libs/moodle/v1_9/bo/CourseBov19.php");
}
class CourseCategoryBov19{

	function registrar(&$dbh, $Id_CatCurso, $name, $parent, $description){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CourseCategoryBov19:18:registrar]--------->");
		
		$dbhm19=Sdx_ConectaBasev19();
		
		//registro del curso
		$log->LogInfo("[CourseCategoryBov19:23:registrar]registro de la categoria del curso");
		if($parent==1){
			$parent=0;
		}
		$courseCategoryDao = new CourseCategoryDaov19();
		$courseCategory=$courseCategoryDao->save($dbhm19, $name, $parent, $description);
		if(is_numeric($courseCategory)){
			$log->LogError("registrar:39-ROLLBACK");
			mysqli_rollback($dbhm19);
			return false;
		}
		$log->LogInfo("[CourseCategoryBov19:35:save]".$courseCategory->id."");

		//registrando el contexto del curso
		$log->LogInfo("[CourseCategoryBov19:38:registrar]registrando el contexto del curso");
		$contextDao = new ContextDaov19();
		//obtener la categoria del curso
		$log->LogInfo("[CourseCategoryBov19:41:registrar]obtener la categoria del curso");
		$path='';
		if($parent==0){
			$path='/1';
		}else{
			$contextCategory=$contextDao->find(CONTEXT_COURSECAT, $parent, $dbhm19);
			$path=$contextCategory->path;
		}
		$context = $contextDao->save(CONTEXT_COURSECAT, $courseCategory->id, $path,$dbhm19);
		if(is_numeric($context)){
			$log->LogError("registrar:79-ROLLBACK");
			mysqli_rollback($dbhm19);
			return false;
		}
		$courseCategory->context = $context;
		$log->LogInfo("[CourseCategoryBov19:56:registrar]".$courseCategory->context->id.",".$courseCategory->id."");
		
		//guardando el id del curso de moodle
		$log->LogInfo("guardando el id del curso de moodle");
		$catCursoDao = new CatCursoDao();
		$result=$catCursoDao->actualizarCatCursoId($courseCategory->id, $Id_CatCurso, MOODLE_V1_9, $dbh);
		if(is_numeric($result)){
			$log->LogError("registrar:113-ROLLBACK");
			mysqli_rollback($dbhm19);
			return false;
		}
		
		mysqli_commit($dbhm19);
		$log->LogInfo("[CourseCategoryBov19:119:registrar]---------<");
		return true;
	}
	/**
	 * 
	 * @param unknown_type $idCourse
	 * @param unknown_type $dbh
	 * @return boolean
	 */
	function eliminar($idCatCourse){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CourseCategoryBov19:eliminar]-------->");
		
		$dbhm19=Sdx_ConectaBasev19();
	
		$courseBo = new CourseBov19();
		$result=$courseBo->buscarCursosPorCategoria($dbhm19, $idCatCourse);
		foreach($result as $entry){
			$log->LogInfo("[CourseCategoryBov19:eliminar]".$entry->id."-".$entry->fullname);
			$courseBo->eliminar($entry->id, null);
		}
		$log->LogInfo("[CourseCategoryBov19:eliminar:89]cursos borrados");
		//context
		$contextDao = new ContextDaov19();
		$result=$contextDao->delete(CONTEXT_COURSECAT, $idCatCourse, $dbhm19);
		if(!$result){
			$log->LogInfo("[CourseCategoryBov19:eliminar:94]-ROLLBACK");
			mysqli_rollback($dbhm19);
			return false;
		}
		$log->LogInfo("[CourseCategoryBov19:eliminar:89]contexto borrado");
		
		//curso
		$courseCategoryDao = new CourseCategoryDaov19();
		$result=$courseCategoryDao->delete($idCatCourse, $dbhm19);
		if(!$result){
			$log->LogInfo("[CourseCategoryBov19:eliminar:103]-ROLLBACK");
			mysqli_rollback($dbhm19);
			return false;
		}
		$log->LogInfo("[CourseCategoryBov19:eliminar:89]categoria borrada");
		
		mysqli_commit($dbhm19);
		$log->LogInfo("[CourseCategoryBov19:eliminar]--------<");
		return true;
	}
	function modificar( $id, $name, $parent, $description){
		//consultar el curso para saber si tiene la misma cantidad de secciones
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CourseCategoryBov19:modificar]-------->");
		
		$dbhm19=Sdx_ConectaBasev19();
		
		$courseCategory = new stdClass();
		$courseCategory->id=$id;
		$courseCategory->name=$name;
		$courseCategory->description=$description;
		
		$courseCategoryDao = new CourseCategoryDaov19();
		$result=$courseCategoryDao->update($dbhm19, $courseCategory);
		if(!$result){
			$log->LogError("[CourseCategoryBov19:modificar]:129-ROLLBACK");
			mysqli_rollback($dbhm19);
			return false;
		}
		mysqli_commit($dbhm19);
		$log->LogInfo("[CourseCategoryBov19:modificar]--------<");
		return true;
	}
}
?>