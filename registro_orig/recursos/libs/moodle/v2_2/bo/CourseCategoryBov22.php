<?php
if(!class_exists("Configv22")){
	include('recursos/libs/moodle/v2_2/moodle_config22.php');
}
if(!class_exists("GeneralDaov22") ){
	include('recursos/libs/moodle/v2_2/dao/GeneralDaov22.php');
}
if(!class_exists("ContextDaov22")){
	include('recursos/libs/moodle/v2_2/dao/ContextDaov22.php');
}
if(!class_exists("CourseBov22") ){
	include("recursos/libs/moodle/v2_2/bo/CourseBov22.php");
}
if(!class_exists("CourseCategoryDaov22")){
	include("recursos/libs/moodle/v2_2/dao/CourseCategoryDaov22.php");
}

class CourseCategoryBov22{


	function registrar(&$dbh,  $Id_CatCurso, $name, $parent, $description){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CourseBov22:10:registrar]--------->");

		$dbhm22=Sdx_ConectaBasev22();

		//registro del curso
		$log->LogInfo("[CourseCategoryBov22:23:registrar]registro de la categoria del curso");
		//generando el nombre corto
		if($parent==1){
			$parent=0;
		}
		$shortname=$this->createShortname($name);
		$courseCategoryDao = new CourseCategoryDaov22();
		$courseCategory=$courseCategoryDao->save($dbhm22, $name, $parent, $description, $shortname);
		if(is_numeric($courseCategory)){
			$log->LogError("registrar:39-ROLLBACK");
			mysqli_rollback($dbhm22);
			return false;
		}
		$log->LogInfo("[CourseCategoryBov22:37:save]".$courseCategory->id."");

		//registrando el contexto del curso
		$log->LogInfo("[CourseCategoryBov22:42:registrar]registrando el contexto del curso");
		$contextDao = new ContextDaov22();
		//obtener la categoria del curso
		$log->LogInfo("[CourseCategoryBov22:45:registrar]obtener la categoria del curso");
		$path='';
		if($parent==0){
			$path='/1';
		}else{
			$contextCategory=$contextDao->find(CONTEXT_COURSECAT, $parent, $dbhm22);
			$path=$contextCategory->path;
		}
		$context = $contextDao->save(CONTEXT_COURSECAT, $courseCategory->id, $path,$dbhm22);
		if(is_numeric($context)){
			$log->LogError("[CourseCategoryBov22:55:registrar]-ROLLBACK");
			mysqli_rollback($dbhm22);
			return false;
		}
		$courseCategory->context = $context;
		$log->LogInfo("[CourseCategoryBov22:60:registrar]".$courseCategory->context->id.",".$courseCategory->id."");
		
		//guardando el id del curso de moodle
		$log->LogInfo("[CourseCategoryBov22:63:registrar]-guardando el id del curso de moodle");
		$catCursoDao = new CatCursoDao();
		$result=$catCursoDao->actualizarCatCursoId($courseCategory->id, $Id_CatCurso, MOODLE_V2_2, $dbh);
		if(is_numeric($result)){
			$log->LogError("[CourseCategoryBov22:67:registrar]-ROLLBACK");
			mysqli_rollback($dbhm22);
			return false;
		}
		
		mysqli_commit($dbhm22);
		$log->LogInfo("[CourseBov22:73:registrar]---------<");
		return true;
	}

	function eliminar($idCatCourse){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CourseCategoryBov22:eliminar]-------->");
		$dbhm22=Sdx_ConectaBasev22();
		
		$courseBo = new CourseBov22();
		$result=$courseBo->buscarCursosPorCategoria($dbhm22, $idCatCourse);
		foreach($result as $entry){
			$log->LogInfo("[CourseCategoryBov22:eliminar:85]".$entry->id."-".$entry->fullname);
			$courseBo->eliminar($entry->id, null);
		}
		$log->LogInfo("[CourseCategoryBov22:eliminar:88]cursos eliminados");
		//preguntas
		$contextDao = new ContextDaov22();
		$cc=$contextDao->find(CONTEXT_COURSECAT, $idCatCourse, $dbhm22);
		$generalDaov22 = new GeneralDaov22();
		$generalDaov22->deleteByField($dbhm22, 'question_categories', 'contextid', $cc->id);
		$log->LogInfo("[CourseCategoryBov22:eliminar:88]preguntas");
		
		//context
		$result=$contextDao->delete(CONTEXT_COURSECAT, $idCatCourse, $dbhm22);
		if(!$result){
			$log->LogInfo("[CourseCategoryBov22:eliminar:96]-ROLLBACK");
			mysqli_rollback($dbhm22);
			return false;
		}
		$log->LogInfo("[CourseCategoryBov22:eliminar:88]context");

		//categoria
		$courseCategoryDao = new CourseCategoryDaov22();
		$result=$courseCategoryDao->delete($idCatCourse, $dbhm22);
		if(!$result){
			$log->LogInfo("[CourseCategoryBov22:eliminar:105]-ROLLBACK");
			mysqli_rollback($dbhm22);
			return false;
		}
		$log->LogInfo("[CourseCategoryBov22:eliminar:88]categoria");
		
		mysqli_commit($dbhm22);
		$log->LogInfo("[CourseCategoryBov22:eliminar]--------<");
		return true;
	}

	function modificar($id,
			 $name, $parent, $description){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );

		$log->LogInfo("[CourseCategoryBov22:modificar]-------->");

		$dbhm22=Sdx_ConectaBasev22();

		$courseCategory = new stdClass();
		$courseCategory->id=$id;
		$courseCategory->name=$name;
		$courseCategory->description=$description;
		$courseCategory->idnumber=$this->createShortname($name);
		
		$courseCategoryDao = new CourseCategoryDaov22();
		$result=$courseCategoryDao->update($dbhm22, $courseCategory);
		if(!$result){
			$log->LogError("[CourseCategoryBov22:modificar]:137-ROLLBACK");
			mysqli_rollback($dbhm22);
			return false;
		}

		mysqli_commit($dbhm22);
		$log->LogInfo("[CourseCategoryBov22:modificar]--------<");
		return true;
	}
	
	function createShortname($name){
		$shortname='';
		$pieces = explode(" ", $name);
		for ($i=0;$i<count($pieces) ;$i++){
			//haz todo minusculas
			$pieces[$i]=strtolower($pieces[$i]);
			if(strlen($pieces[$i])>1){
				//luego la primera en mayuscula
				$pieces[$i]=strtoupper(substr($pieces[$i], 0, 1 ) ).substr($pieces[$i], 1, strlen($pieces[$i])-1 );
				//luego obtene las 3 primeras letras y juntalas
				$shortname=$shortname.substr($pieces[$i], 0, 3 );
			}else{
				$shortname=$shortname.$pieces[$i];
			}
		}
		return $shortname;
	}
	
}
?>