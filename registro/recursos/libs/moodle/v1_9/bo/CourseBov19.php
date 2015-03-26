<?php

if(!class_exists("Configv19")){
	include('recursos/libs/moodle/v1_9/moodle_config19.php');
}

include("recursos/libs/moodle/v1_9/dao/CourseDaov19.php");
if(!class_exists("ContextDaov19")){
	include('recursos/libs/moodle/v1_9/dao/ContextDaov19.php');
}
include('recursos/libs/moodle/v1_9/dao/BlockInstanceDaov19.php');
include('recursos/libs/moodle/v1_9/dao/CourseSectionDaov19.php');
include('recursos/libs/moodle/v1_9/dao/RoleDaov19.php');
if(!class_exists("GeneralDaov19")){
	include('recursos/libs/moodle/v1_9/dao/GeneralDaov19.php');
}
if(!class_exists("CourseCategoryDaov19")){
	include('recursos/libs/moodle/v1_9/dao/CourseCategoryDaov19.php');
}
include('recursos/libs/moodle/dao/CursoDao.php');

class CourseBov19{
	/**
	 * Bugs:
	 * Course start date no es el correcto
	 * Start date
	 * End date
	 * Number of weeks/topics
	 * 
	 * 
	 * @param unknown_type $dbh
	 * @param unknown_type $idCurso
	 * @param unknown_type $fullname
	 * @param unknown_type $startdate
	 * @param unknown_type $enddate
	 * @param unknown_type $category
	 * @param unknown_type $shortname
	 * @param unknown_type $idNumber
	 * @param unknown_type $summary
	 * @param unknown_type $password
	 * @param unknown_type $cost
	 * @param unknown_type $currency
	 * @return boolean
	 */
	function registrar(&$dbh, $idCurso, 
			$fullname, $startdate, $enddate, 
			$category, $shortname, $idNumber, $summary, 
			$password, $cost, $currency){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CourseBov19:18:registrar]--------->");
		
		$dbhm19=Sdx_ConectaBasev19();
		
		//registro del curso
		$log->LogInfo("[CourseBov19:23:registrar]registro del curso");
		$startdatePieces=explode("-", $startdate);
		$startDatetime=mktime(0,0,0,$startdatePieces[1],$startdatePieces[2],$startdatePieces[0]);
// 		$startDatetime=date('Y-m-d', $startDatetime);
		$enrolstartdate=time();
		$enrolenddate=strtotime( '-5 day',$startDatetime);
		$enddatePieces=explode("-", $enddate);
		$endDatetime=mktime(0,0,0,$enddatePieces[1],$enddatePieces[2],$enddatePieces[0]);
		$numsections=((int)date('W',$endDatetime)-(int)date('W',$startDatetime));
		
		$courseDao = new CourseDaov19();
		$course=$courseDao->save($dbhm19, $fullname, $startDatetime, 
			$category, $shortname, $idNumber, $summary, 
			$password, $cost, $currency, $numsections, $enrolstartdate, $enrolenddate);
		$log->LogInfo("[CourseBov19:69:save]".$course->id);
		if(is_numeric($course)){
			$log->LogError("registrar:39-ROLLBACK");
			mysqli_rollback($dbhm19);
			return false;
		}
		
		//registra sus secciones
		$log->LogInfo("[CourseBov19:74:registrar]registra sus secciones");
		$courseSectionDao = new CourseSectionDaov19();
		
		//registrando seccion inicial
		$log->LogInfo("[CourseBov19:78:registrar]registrando seccion inicial");
		$sequence=$courseSectionDao->getMaxSequence($dbhm19);
		$sequence+=1;
		
		$courseSection=$courseSectionDao->save($course->id, 0, null, $sequence, 1, $dbhm19);
		if(is_numeric($courseSection)){
			$log->LogInfo("registrar:84-ROLLBACK");
			mysqli_rollback($dbhm19);
			return false;
		}
		//registrando las secciones del curso
		$log->LogInfo("[CourseBov19:89:registrar]registrando las secciones del curso");
		for ($size=1;$size<=$numsections;$size++)
		{
			$courseSection=$courseSectionDao->save($course->id, $size, '', null, 1, $dbhm19);
			if(is_numeric($courseSection)){
				$log->LogInfo("registrar:94-ROLLBACK");
				mysqli_rollback($dbhm19);
				return false;
			}
		}
		
		//registrando el contexto del curso
		$log->LogInfo("[CourseBov19:101:registrar]registrando el contexto del curso");
		$contextDao = new ContextDaov19();
		//obtener la categoria del curso
		$log->LogInfo("[CourseBov19:104:registrar]obtener la categoria del curso");
		$contextCategory=$contextDao->find(CONTEXT_COURSECAT, $category, $dbhm19);
		$c = $contextDao->save(CONTEXT_COURSE, $course->id, $contextCategory->path,$dbhm19);
		if(is_numeric($c)){
			$log->LogError("[CourseBov19:registrar]:108-ROLLBACK");
			mysqli_rollback($dbhm19);
			return false;
		}
		$course->context = $c;
		$log->LogInfo("[CourseBov19:113:registrar]".$course->context->id.",".$course->id."");
		
		//registrando los bloques del curso
		$biDao = new BlockInstanceDaov19();
		$biData=array(20,1,25,2,9,18,8,19);
		$position='l';
		For ($size=0;$size<count($biData) ;$size++)
		{
			if($size>4){
				$position='r';
			}
			$blockInstance=$biDao->save($biData[$size], $course->id, $position, ($size>4?$size-5:$size), 1, $dbhm19);
			if(is_numeric($blockInstance)){
				$log->LogError("registrar:96-ROLLBACK");
				mysqli_rollback($dbhm19);
				return false;
			}
			$cb=$contextDao->save(CONTEXT_BLOCK, $blockInstance->id, $course->context->path,$dbhm19);
			if(is_numeric($cb)){
				$log->LogError("registrar:102-ROLLBACK");
				mysqli_rollback($dbhm19);
				return false;
			}
		}
		
		$courseCategoryDao = new CourseCategoryDaov19();
		$courseCategoryDao->updateCourseCount($dbhm19, $category, true);
		
		//guardando el id del curso de moodle
		$log->LogInfo("guardando el id del curso de moodle");
		$cursoDao = new CursoDao();
		$result=$cursoDao->actualizarCursoId($course->id, $idCurso, MOODLE_V1_9, $dbh);
		if(is_numeric($result)){
			$log->LogError("registrar:113-ROLLBACK");
			mysqli_rollback($dbhm19);
			return false;
		}
		
		mysqli_commit($dbhm19);
		$log->LogInfo("[CourseBov19:119:registrar]---------<");
		return true;
	}
	/**
	 * 
	 * @param unknown_type $idCourse
	 * @param unknown_type $dbh
	 * @return boolean
	 */
	function eliminar($idCourse, $idCourseCategory){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CourseBov19:eliminar]-------->");
		
		$dbhm19=Sdx_ConectaBasev19();
	
		//borrar secciones
		$log->LogInfo("[CourseBov19:eliminar]borrar secciones");
		$courseSectionDao = new CourseSectionDaov19();
		$result=$courseSectionDao->deleteAllCourseSections($idCourse, $dbhm19);
		if(!$result){
			$log->LogError("[CourseBov19:eliminar]168-ROLLBACK");
			mysqli_rollback($dbhm19);
			return false;
		}
	
		//borrando bloques
		$contextDao = new ContextDaov19();
		$biDao = new BlockInstanceDaov19();
		$biData = $biDao->findBlocksPerCourse($idCourse, $dbhm19);
		for($i=0;$i<count($biData);$i++){
			$biTemp=$biData[$i];
			$result=$biDao->delete($biTemp->id, $dbhm19);
			if(!$result){
				$log->LogInfo("[CourseBov19:eliminar]181-ROLLBACK");
				mysqli_rollback($dbhm19);
				return false;
			}
			$result=$contextDao->delete(CONTEXT_BLOCK, $biTemp->id, $dbhm19);
			if(!$result){
				$log->LogInfo("[CourseBov19:eliminar]187-ROLLBACK");
				mysqli_rollback($dbhm19);
				return false;
			}
		}
		
		//borrar enrolamientos
		$c=$contextDao->find(CONTEXT_COURSE, $idCourse, $dbhm19);
		$roleDao = new RoleDaov19();
		$result=$roleDao->deleteContextRoles($c->id, $dbhm19);
		if(!$result){
			$log->LogError("[CourseBov19:eliminar]:198-ROLLBACK");
			mysqli_rollback($dbhm19);
			return false;
		}
	
		//context
		$result=$contextDao->delete(CONTEXT_COURSE, $idCourse, $dbhm19);
		if(!$result){
			$log->LogError("[CourseBov19:eliminar]:206-ROLLBACK");
			mysqli_rollback($dbhm19);
			return false;
		}
		
		//borrando otras tablas
		$generalDaov19 = new GeneralDaov19();
		$this->deleteSeveralCourseTables($generalDaov19, $dbhm19, $idCourse);
	
		//curso
		$courseDao = new CourseDaov19();
		$result=$courseDao->delete($idCourse, $dbhm19);
		if(!$result){
			$log->LogError("[CourseBov19:eliminar]:219-ROLLBACK");
			mysqli_rollback($dbhm19);
			return false;
		}
		if(isset($idCourseCategory)){
			$courseCategoryDao = new CourseCategoryDaov19();
			$courseCategoryDao->updateCourseCount($dbhm19, $idCourseCategory, false);
		}
		
		mysqli_commit($dbhm19);
		$log->LogInfo("[CourseBov19:eliminar]--------<");
		return true;
	}
	function deleteSeveralCourseTables(&$generalDaov19, &$dbhm19, $id){
		$generalDaov19->deleteByField($dbhm19,'assignment','course',$id);
		$generalDaov19->deleteByField($dbhm19,'chat','course',$id);
		$generalDaov19->deleteByField($dbhm19,'chat_users','course',$id);
		$generalDaov19->deleteByField($dbhm19,'choice','course',$id);
		$generalDaov19->deleteByField($dbhm19,'course_allowed_modules','course',$id);
		$generalDaov19->deleteByField($dbhm19,'course_display','course',$id);
		$generalDaov19->deleteByField($dbhm19,'course_modules','course',$id);
		$generalDaov19->deleteByField($dbhm19,'course_sections','course',$id);
		$generalDaov19->deleteByField($dbhm19,'data','course',$id);
		$generalDaov19->deleteByField($dbhm19,'forum','course',$id);
		$generalDaov19->deleteByField($dbhm19,'forum_discussions','course',$id);
		$generalDaov19->deleteByField($dbhm19,'glossary','course',$id);
		$generalDaov19->deleteByField($dbhm19,'hotpot','course',$id);
		$generalDaov19->deleteByField($dbhm19,'journal','course',$id);
		$generalDaov19->deleteByField($dbhm19,'label','course',$id);
		$generalDaov19->deleteByField($dbhm19,'lams','course',$id);
		$generalDaov19->deleteByField($dbhm19,'lesson','course',$id);
		$generalDaov19->deleteByField($dbhm19,'lesson_default','course',$id);
		$generalDaov19->deleteByField($dbhm19,'log','course',$id);
		$generalDaov19->deleteByField($dbhm19,'mnet_log','course',$id);
		$generalDaov19->deleteByField($dbhm19,'quiz','course',$id);
		$generalDaov19->deleteByField($dbhm19,'resource','course',$id);
		$generalDaov19->deleteByField($dbhm19,'scorm','course',$id);
		$generalDaov19->deleteByField($dbhm19,'survey','course',$id);
		$generalDaov19->deleteByField($dbhm19,'wiki','course',$id);
		$generalDaov19->deleteByField($dbhm19,'wiki_entries','course',$id);
		$generalDaov19->deleteByField($dbhm19,'workshop','course',$id);
		
		$generalDaov19->deleteByField($dbhm19,'backup_courses','courseid',$id);
		$generalDaov19->deleteByField($dbhm19,'backup_log','courseid',$id);
		$generalDaov19->deleteByField($dbhm19,'block_search_documents','courseid',$id);
		$generalDaov19->deleteByField($dbhm19,'enrol_authorize','courseid',$id);
		$generalDaov19->deleteByField($dbhm19,'enrol_paypal','courseid',$id);
		$generalDaov19->deleteByField($dbhm19,'event','courseid',$id);
		$generalDaov19->deleteByField($dbhm19,'grade_categories','courseid',$id);
		$generalDaov19->deleteByField($dbhm19,'grade_categories_history','courseid',$id);
		$generalDaov19->deleteByField($dbhm19,'grade_items','courseid',$id);
		$generalDaov19->deleteByField($dbhm19,'grade_items_history','courseid',$id);
		$generalDaov19->deleteByField($dbhm19,'grade_outcomes','courseid',$id);
		$generalDaov19->deleteByField($dbhm19,'grade_outcomes_courses','courseid',$id);
		$generalDaov19->deleteByField($dbhm19,'grade_outcomes_history','courseid',$id);
		$generalDaov19->deleteByField($dbhm19,'grade_settings','courseid',$id);
		$generalDaov19->deleteByField($dbhm19,'groupings','courseid',$id);
		$generalDaov19->deleteByField($dbhm19,'groups','courseid',$id);
		$generalDaov19->deleteByField($dbhm19,'mnet_enrol_assignments','courseid',$id);
		$generalDaov19->deleteByField($dbhm19,'post','courseid',$id);
		$generalDaov19->deleteByField($dbhm19,'scale','courseid',$id);
		$generalDaov19->deleteByField($dbhm19,'scale_history','courseid',$id);
		$generalDaov19->deleteByField($dbhm19,'stats_daily','courseid',$id);
		$generalDaov19->deleteByField($dbhm19,'stats_monthly','courseid',$id);
		$generalDaov19->deleteByField($dbhm19,'stats_user_daily','courseid',$id);
		$generalDaov19->deleteByField($dbhm19,'stats_user_monthly','courseid',$id);
		$generalDaov19->deleteByField($dbhm19,'stats_user_weekly','courseid',$id);
		$generalDaov19->deleteByField($dbhm19,'stats_weekly','courseid',$id);
		$generalDaov19->deleteByField($dbhm19,'user_lastaccess','courseid',$id);
		
		
	}
	
	function modificar(&$dbh, $idCourse, 
			$fullname, $startdate, $enddate,
			$category, $shortname, $idNumber, $summary, 
			$password, $cost, $currency){
		//consultar el curso para saber si tiene la misma cantidad de secciones
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("modificar-------->");
		
		$dbhm19=Sdx_ConectaBasev19();
		
		$courseDao = new CourseDaov19();
		$course = $courseDao->find($idCourse, $dbhm19);
		
		if(is_numeric($course)){
			$log->LogInfo("231:modificar-ROLLBACK");
			mysqli_rollback($dbhm19);
			return false;
		}
		
		$startdatePieces=explode("-", $startdate);
		$startDatetime=mktime(0,0,0,$startdatePieces[1],$startdatePieces[2],$startdatePieces[0]);
		// 		$startDatetime=date('Y-m-d', $startDatetime);
		$enrolstartdate=time();
		$enrolenddate=strtotime( '-5 day',$startDatetime);
		$enddatePieces=explode("-", $enddate);
		$endDatetime=mktime(0,0,0,$enddatePieces[1],$enddatePieces[2],$enddatePieces[0]);
		$numsections=((int)date('W',$endDatetime)-(int)date('W',$startDatetime));
		
		if($course->numsections != $numsections){
			$courseSectionDao = new CourseSectionDaov19();
			//borrar las secciones
			$result=$courseSectionDao->deleteAllCourseSections($idCourse, $dbhm19);
			if(!$result){
				$log->LogInfo("modificar:241-ROLLBACK");
				mysqli_rollback($dbhm19);
				return false;
			}
			//agregar las secciones
			$sequence=$courseSectionDao->getMaxSequence($dbhm19);
			$sequence+=1;
			$courseSection=$courseSectionDao->save($course->id, 0, null, $sequence, 1, $dbhm19);
			if(!$courseSection){
				$log->LogInfo("modificar:250-ROLLBACK");
				mysqli_rollback($dbhm19);
				return false;
			}
			for ($size=1;$size<=$numsections;$size++)
			{
				$courseSection=$courseSectionDao->save($course->id, $size, '', null, 1, $dbhm19);
				if(!$courseSection){
					$log->LogInfo("modificar:258-ROLLBACK");
					mysqli_rollback($dbhm19);
					return false;
				}
			}
		}
		
		
		$result=$courseDao->update($dbhm19, $idCourse, $fullname, $startDatetime, 
			$category, $shortname, $idNumber, $summary, 
			$password, $cost, $currency, $numsections, $enrolstartdate, $enrolenddate);
		if(!$result){
			$log->LogError("modificar:279-ROLLBACK");
			mysqli_rollback($dbhm19);
			return false;
		}
		mysqli_commit($dbhm19);
		$log->LogInfo("modificar--------<");
		return true;
	}
	
	function buscarCursosPorCategoria(&$dbhm19, $idCatCurso){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CourseBov19:buscarCursosPorCategoria]-------->");
	
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
			$log->LogInfo("[CourseBov19:367:buscarCursosPorCategoria]localConn=".$localConn."");
		}
	
		$course = new stdClass();
		$course->category=$idCatCurso;
	
		$courseDao = new CourseDaov19();
		$result=$courseDao->findByCategory($dbhm19, $course);
	
		if($localConn ){
			mysqli_close($dbhm19);
		}
	
		$log->LogInfo("[CourseBov19:buscarCursosPorCategoria]--------<");
		return $result;
	}
}
?>