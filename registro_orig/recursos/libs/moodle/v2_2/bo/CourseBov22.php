<?php
if(!class_exists("Configv22")){
	include('recursos/libs/moodle/v2_2/moodle_config22.php');
}
// include('recursos/libs/moodle/v2_2/moodle_config22.php');
include("recursos/libs/moodle/v2_2/dao/CourseDaov22.php");
if(!class_exists("ContextDaov22")){
	include('recursos/libs/moodle/v2_2/dao/ContextDaov22.php');
}
include('recursos/libs/moodle/v2_2/dao/BlockInstanceDaov22.php');
include('recursos/libs/moodle/v2_2/dao/CourseSectionDaov22.php');
include('recursos/libs/moodle/v2_2/dao/EnrolDaov22.php');
include('recursos/libs/moodle/v2_2/dao/UserEnrollmentDaov22.php');
if(!class_exists("GeneralDaov22")){
	include('recursos/libs/moodle/v2_2/dao/GeneralDaov22.php');
}
if(!class_exists("CourseCategoryDaov22")){
	include('recursos/libs/moodle/v2_2/dao/CourseCategoryDaov22.php');
}

// include('recursos/libs/moodle/dao/CursoDao.php');

class CourseBov22{


	/**
	 * No pudo visualizarse en moodle
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
	function registrar(&$dbh, $idCurso, $fullname, $startdate, $enddate,
			$category, $shortname, $idNumber, $summary,
			$password, $cost, $currency){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CourseBov22:10:registrar]--------->");

		$dbhm22=Sdx_ConectaBasev22();

		//registro del curso
		$log->LogInfo("[CourseBov22:24:registrar]registro del curso");
		$startdatePieces=explode("-", $startdate);
		$startDatetime=mktime(0,0,0,$startdatePieces[1],$startdatePieces[2],$startdatePieces[0]);

		$enddatePieces=explode("-", $enddate);
		$endDatetime=mktime(0,0,0,$enddatePieces[1],$enddatePieces[2],$enddatePieces[0]);
		$numsections=((int)date('W',$endDatetime)-(int)date('W',$startDatetime));

		$courseDao = new CourseDaov22();
		$course=$courseDao->save($dbhm22, $fullname, $startDatetime, $category, $shortname, $idNumber, $summary, $numsections);
		if(is_numeric($course)){
			$log->LogError("registrar:35-ROLLBACK");
			mysqli_rollback($dbhm22);
			return false;
		}

		//registrando el contexto del curso
		$log->LogInfo("[CourseBov22:57:registrar]registrando el contexto del curso");
		$contextDao = new ContextDaov22();
		$contextCategory=$contextDao->find(CONTEXT_COURSECAT, $category, $dbhm22);
		$c = $contextDao->save(CONTEXT_COURSE, $course->id, $contextCategory->path, $dbhm22);
		if(is_numeric($c)){
			$log->LogError("registrar:62-ROLLBACK");
			mysqli_rollback($dbhm22);
			return false;
		}
		$course->context = $c;
		$log->LogInfo("[CourseBov22:67:registrar]".$course->context->id.",".$course->id."");


		//registrando los bloques del curso
		$log->LogInfo("[CourseBov22:71:registrar]registrando los bloques del curso");
		$biDao = new BlockInstanceDaov22();
		$biData=array('search_forums','news_items','calendar_upcoming','recent_activity');
		For ($size=0;$size<count($biData) ;$size++)
		{
			$blockInstance=$biDao->save($biData[$size], $course->context->id, 'course-view-*', 'side-post', $size, $dbhm22);
			if(is_numeric($blockInstance)){
				$log->LogError("registrar:78-ROLLBACK");
				mysqli_rollback($dbhm22);
				return false;
			}
			$cb=$contextDao->save(CONTEXT_BLOCK, $blockInstance->id, $course->context->path, $dbhm22);
			if(is_numeric($cb)){
				$log->LogInfo("registrar:84-ROLLBACK");
				mysqli_rollback($dbhm22);
				return false;
			}
		}

		//registra sus secciones
		$log->LogInfo("[CourseBov22:91:registrar]registra sus secciones");
		$courseSectionDao = new CourseSectionDaov22();
		$log->LogInfo("[CourseBov22:93:registrar]registrando seccion inicial");
		//registrando seccion inicial
		$sequence=$courseSectionDao->getMaxSequence($dbhm22);
		$sequence+=1;
		$courseSection=$courseSectionDao->save($course->id, 0, null,null,1,$sequence, 1, $dbhm22);
		if(is_numeric($courseSection)){
			$log->LogError("registrar:99-ROLLBACK");
			mysqli_rollback($dbhm22);
			return false;
		}


		//guardando el enrol
		$log->LogInfo("[CourseBov22:106:registrar]guardando el enrol");
		$enrolDao=new EnrolDaov22();
		$enrolData=array('manual','guest','self');
		For ($size=0;$size<count($enrolData) ;$size++)
		{
			$notifyall=0;
			$enrolstartdate=time();
			$enrolenddate=strtotime( '-5 day',$startDatetime);
			$customint1=null;
			$customint2=null;
			$customint3=null;
			$customint4=null;
			switch($size){
				case 0:
					$roleid=5;
					$status=0;
					break;
				case 1:
					$status=0;
					$roleid=0;
					break;
				case 2:
					$status=0;
					$roleid=5;
					$customint1=0;
					$customint2=0;
					$customint3=0;
					$customint4=1;
					break;
			}


			$enrol=$enrolDao->save($dbhm22, $enrolData[$size],  $status, $course->id, $size,
					$enrolstartdate, $enrolenddate,
					$notifyall, $password, $cost, $currency,
					$roleid, $customint1, $customint2, $customint3, $customint4);
			if(is_numeric($enrol)){
				$log->LogError("registrar:133-ROLLBACK");
				mysqli_rollback($dbhm22);
				return false;
			}
		}
		$courseCategoryDao = new CourseCategoryDaov22();
		$courseCategoryDao->updateCourseCount($dbhm22, $category, true);

		//guardando el id el curso de moodle
		$log->LogInfo("[CourseBov22:140:registrar]guardando el id el curso de moodle");
		$cursoDao = new CursoDao();
		$result=$cursoDao->actualizarCursoId($course->id, $idCurso, MOODLE_V2_2, $dbh);
		if(is_numeric($result)){
			$log->LogInfo("registrar:144-ROLLBACK");
			mysqli_rollback($dbhm22);
			return false;
		}

		mysqli_commit($dbhm22);
		$log->LogInfo("[CourseBov22:150:registrar]---------<");
		return true;
	}

	function eliminar($idCourse, $idCourseCategory){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("eliminar-------->");
		$dbhm22=Sdx_ConectaBasev22();

		//borrar secciones
		$courseSectionDao = new CourseSectionDaov22();
		$result=$courseSectionDao->deleteAllCourseSections($idCourse, $dbhm22);
		if(!$result){
			$log->LogInfo("eliminar-ROLLBACK");
			mysqli_rollback($dbhm22);
			return false;
		}

		//borrando bloques
		$contextDao = new ContextDaov22();
		$context=$contextDao->find(CONTEXT_COURSE, $idCourse, $dbhm22);
		$biDao = new BlockInstanceDaov22();
		$biData = $biDao->findBlocksPerCourse($context->id, $dbhm22);
		for($i=0;$i<count($biData);$i++){
			$biTemp=$biData[$i];
			$result=$biDao->delete($biTemp->id, $dbhm22);
			if(!$result){
				$log->LogInfo("eliminar-ROLLBACK");
				mysqli_rollback($dbhm22);
				return false;
			}
			$result=$contextDao->delete(CONTEXT_BLOCK, $biTemp->id, $dbhm22);
			if(!$result){
				$log->LogInfo("eliminar-ROLLBACK");
				mysqli_rollback($dbhm22);
				return false;
			}
		}


		//borrando enrolamientos de cursos
		$enrolDao=new EnrolDaov22();
		$userEnrolmentDao=new UserEnrollmentDaov22();
		$enrols=$enrolDao->findByCourse($idCourse, $dbhm22);
		for ($i=0;$i<count($enrols);$i++){
			$userEnrolmentDao->deleteByEnrol($enrols[$i]->id, $dbhm22);
		}

		//enrol
		$result=$enrolDao->deleteAllEnrolCourse($idCourse, $dbhm22);
		if(!$result){
			$log->LogInfo("eliminar-ROLLBACK");
			mysqli_rollback($dbhm22);
			return false;
		}

		//context
		$result=$contextDao->delete(CONTEXT_COURSE, $idCourse, $dbhm22);
		if(!$result){
			$log->LogInfo("eliminar-ROLLBACK");
			mysqli_rollback($dbhm22);
			return false;
		}
		
		//borrando otras tablas
		$generalDaov22 = new GeneralDaov22();
		$this->deleteSeveralCourseTables($generalDaov22, $dbhm22, $idCourse);
		
		//curso
		$courseDao = new CourseDaov22();
		$result=$courseDao->delete($idCourse, $dbhm22);
		if(!$result){
			$log->LogInfo("eliminar-ROLLBACK");
			mysqli_rollback($dbhm22);
			return false;
		}
		if($idCourseCategory){
			$courseCategoryDao = new CourseCategoryDaov22();
			$courseCategoryDao->updateCourseCount($dbhm22, $idCourseCategory, false);
		}
		
		mysqli_commit($dbhm22);
		$log->LogInfo("eliminar--------<");
		return true;
	}
	
	function deleteSeveralCourseTables(&$generalDaov22, &$dbhm22, $id){
		$generalDaov22->deleteByField($dbhm22,'assignment','course',$id);
		$generalDaov22->deleteByField($dbhm22,'chat','course',$id);
		$generalDaov22->deleteByField($dbhm22,'chat_users','course',$id);
		$generalDaov22->deleteByField($dbhm22,'choice','course',$id);
		$generalDaov22->deleteByField($dbhm22,'course_allowed_modules','course',$id);
		$generalDaov22->deleteByField($dbhm22,'course_completion_aggr_methd','course',$id);
		$generalDaov22->deleteByField($dbhm22,'course_completion_crit_compl','course',$id);
		$generalDaov22->deleteByField($dbhm22,'course_completion_criteria','course',$id);
		$generalDaov22->deleteByField($dbhm22,'course_completion_notify','course',$id);
		$generalDaov22->deleteByField($dbhm22,'course_completions','course',$id);
		$generalDaov22->deleteByField($dbhm22,'course_display','course',$id);
		$generalDaov22->deleteByField($dbhm22,'course_modules','course',$id);
		$generalDaov22->deleteByField($dbhm22,'course_sections','course',$id);
		$generalDaov22->deleteByField($dbhm22,'data','course',$id);
		$generalDaov22->deleteByField($dbhm22,'feedback','course',$id);
		$generalDaov22->deleteByField($dbhm22,'feedback_template','course',$id);
		$generalDaov22->deleteByField($dbhm22,'folder','course',$id);
		$generalDaov22->deleteByField($dbhm22,'forum','course',$id);
		$generalDaov22->deleteByField($dbhm22,'forum_discussions','course',$id);
		$generalDaov22->deleteByField($dbhm22,'glossary','course',$id);
		$generalDaov22->deleteByField($dbhm22,'imscp','course',$id);
		$generalDaov22->deleteByField($dbhm22,'label','course',$id);
		$generalDaov22->deleteByField($dbhm22,'lesson','course',$id);
		$generalDaov22->deleteByField($dbhm22,'log','course',$id);
		$generalDaov22->deleteByField($dbhm22,'lti','course',$id);
		$generalDaov22->deleteByField($dbhm22,'lti_types','course',$id);
		$generalDaov22->deleteByField($dbhm22,'mnet_log','course',$id);
		$generalDaov22->deleteByField($dbhm22,'page','course',$id);
		$generalDaov22->deleteByField($dbhm22,'quiz','course',$id);
		$generalDaov22->deleteByField($dbhm22,'resource','course',$id);
		$generalDaov22->deleteByField($dbhm22,'resource_old','course',$id);
		$generalDaov22->deleteByField($dbhm22,'scorm','course',$id);
		$generalDaov22->deleteByField($dbhm22,'survey','course',$id);
		$generalDaov22->deleteByField($dbhm22,'url','course',$id);
		$generalDaov22->deleteByField($dbhm22,'wiki','course',$id);
		$generalDaov22->deleteByField($dbhm22,'workshop','course',$id);
		$generalDaov22->deleteByField($dbhm22,'workshop_old','course',$id);
		
		
		
		$generalDaov22->deleteByField($dbhm22,'backup_courses','courseid',$id);
		$generalDaov22->deleteByField($dbhm22,'course_published','courseid',$id);
		$generalDaov22->deleteByField($dbhm22,'enrol','courseid',$id);
		$generalDaov22->deleteByField($dbhm22,'enrol_authorize','courseid',$id);
		$generalDaov22->deleteByField($dbhm22,'enrol_flatfile','courseid',$id);
		$generalDaov22->deleteByField($dbhm22,'enrol_paypal','courseid',$id);
		$generalDaov22->deleteByField($dbhm22,'event','courseid',$id);
		$generalDaov22->deleteByField($dbhm22,'feedback_sitecourse_map','courseid',$id);
		$generalDaov22->deleteByField($dbhm22,'grade_categories','courseid',$id);
		$generalDaov22->deleteByField($dbhm22,'grade_categories_history','courseid',$id);
		$generalDaov22->deleteByField($dbhm22,'grade_items','courseid',$id);
		$generalDaov22->deleteByField($dbhm22,'grade_items_history','courseid',$id);
		$generalDaov22->deleteByField($dbhm22,'grade_outcomes','courseid',$id);
		$generalDaov22->deleteByField($dbhm22,'grade_outcomes_courses','courseid',$id);
		$generalDaov22->deleteByField($dbhm22,'grade_outcomes_history','courseid',$id);
		$generalDaov22->deleteByField($dbhm22,'grade_settings','courseid',$id);
		$generalDaov22->deleteByField($dbhm22,'groupings','courseid',$id);
		$generalDaov22->deleteByField($dbhm22,'groups','courseid',$id);
		$generalDaov22->deleteByField($dbhm22,'post','courseid',$id);
		$generalDaov22->deleteByField($dbhm22,'scale','courseid',$id);
		$generalDaov22->deleteByField($dbhm22,'scale_history','courseid',$id);
		$generalDaov22->deleteByField($dbhm22,'stats_daily','courseid',$id);
		$generalDaov22->deleteByField($dbhm22,'stats_monthly','courseid',$id);
		$generalDaov22->deleteByField($dbhm22,'stats_user_daily','courseid',$id);
		$generalDaov22->deleteByField($dbhm22,'stats_user_monthly','courseid',$id);
		$generalDaov22->deleteByField($dbhm22,'stats_user_weekly','courseid',$id);
		$generalDaov22->deleteByField($dbhm22,'stats_weekly','courseid',$id);
		$generalDaov22->deleteByField($dbhm22,'user_lastaccess','courseid',$id);
		
	}

	function modificar(&$dbh, $idCourse,
			$fullname, $startdate, $enddate,
			$category, $shortname, $idNumber, $summary,
			$password, $cost, $currency){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );

		$log->LogInfo("modificar-------->");

		$dbhm22=Sdx_ConectaBasev22();

		$startdatePieces=explode("-", $startdate);
		$startDatetime=mktime(0,0,0,$startdatePieces[1],$startdatePieces[2],$startdatePieces[0]);

		$enddatePieces=explode("-", $enddate);
		$endDatetime=mktime(0,0,0,$enddatePieces[1],$enddatePieces[2],$enddatePieces[0]);
		$numsections=((int)date('W',$endDatetime)-(int)date('W',$startDatetime));


		$courseDao = new CourseDaov22();
		$result=$courseDao->update($dbhm22, $idCourse, $fullname, $startDatetime, $category, $shortname, $idNumber, $summary, $numsections);
		if(!$result){
			$log->LogError("modificar:258-ROLLBACK");
			mysqli_rollback($dbhm22);
			return false;
		}

		//obtener enrols
		$enrolDao = new EnrolDaov22();
		$enrols=$enrolDao->findByCourse($idCourse, $dbhm22);
		For ($size=0;$size<count($enrols) ;$size++){
			$notifyall=0;
			$enrolstartdate=time();
			$enrolenddate=strtotime( '-5 day',$startDatetime);
			$customint1=null;
			$customint2=null;
			$customint3=null;
			$customint4=null;
			switch($size){
				case 0:
					$roleid=5;
					$status=0;
					break;
				case 1:
					$status=0;
					$roleid=0;
					break;
				case 2:
					$status=0;
					$roleid=5;
					$customint1=0;
					$customint2=0;
					$customint3=0;
					$customint4=1;
					break;
			}


			$enrol=$enrolDao->update($dbhm22, $notifyall, $status, $idCourse, $size, 
									$enrolstartdate, $enrolenddate, 
									$password, $cost, $currency,
									 $roleid, $customint1, $customint2, $customint3, $customint4, $enrols[$size]->id);
			if(!$enrol){
				$log->LogError("modificar:296-ROLLBACK");
				mysqli_rollback($dbhm22);
				return false;
			}
		}


		mysqli_commit($dbhm22);
		$log->LogInfo("modificar--------<");
		return true;
	}
	
	function buscarCursosPorCategoria(&$dbhm22, $idCatCurso){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CourseCategoryBov22:buscarCursosPorCategoria]-------->");
		
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
			$log->LogInfo("[ContextDaov22:64:buscarCursosPorCategoria]localConn=".$localConn."");
		}
		
		$course = new stdClass();
		$course->category=$idCatCurso;
		
		$courseDao = new CourseDaov22();
		$result=$courseDao->findByCategory($dbhm22, $course);
		
		if($localConn ){
			mysqli_close($dbhm22);
		}
		
		$log->LogInfo("[CourseCategoryBov22:buscarCursosPorCategoria]--------<");
		return $result;
	}
}
?>