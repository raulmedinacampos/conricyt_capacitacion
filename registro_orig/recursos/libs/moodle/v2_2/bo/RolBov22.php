<?php
include('recursos/libs/moodle/v2_2/moodle_config22.php');
include("recursos/libs/moodle/v2_2/dao/RoleDaov22.php");
include("recursos/libs/moodle/v2_2/dao/UserEnrollmentDaov22.php");
include('recursos/libs/moodle/v2_2/dao/EnrolDaov22.php');
include('recursos/libs/moodle/v2_2/dao/ContextDaov22.php');

class RolBov22{
	function registrar( $idCourse, $idUser, $role){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[RolBov22:11:registrar]--------->");
	
		if($role!=TEACHER_ROLE && $role!=STUDENT_ROLE ){
			$log->LogInfo("[RolBov22:88:registrar]rol inadecuado");
			return false;
		}
		$dbhm22 = Sdx_ConectaBasev22();
		
		//Buscando el contexto del curso
		$contextDao = new ContextDaov22();
		$CONTEXT_COURSE = 50;
		$log->LogInfo("[RolBov22:23:registrar]buscando el context del curso");
		$contextCurso = $contextDao->find($CONTEXT_COURSE, $idCourse, $dbhm22);
		
		//si ya esta enrolado, no lo vuelvas a hacer
		$roleDao = new RoleDaov22();
		$arrData=$roleDao->find($idUser, $dbhm22);
		for($i=0;$i<count($arrData);$i++){
			if($arrData[$i]->contextid==$contextCurso->id){
				//ya estaba registrado
				return true;
			}
		}
		
		//registrando el rol para el curso
		$role=$roleDao->save($role, $contextCurso->id, $idUser, $dbhm22);
		if(is_numeric($role)){
			mysqli_rollback($dbhm22);
			return false;
		}
		
		//buscar los tipos de enrolamiento del curso
		$log->LogInfo("[RolBov22:34:registrar]buscar los tipos de enrolamiento del curso");
		$enrol=null;
		$enrolDao=new EnrolDaov22();
		$enrols=$enrolDao->findByCourse($idCourse, $dbhm22);
		for ($i=0;$i<count($enrols);$i++){
			if($enrols[$i]->enrol=='manual'){
				$enrol=$enrols[$i];
				break;
			}
		}
		//agregando la informacion del como el usuario se registro en el curso
		$log->LogInfo("[RolBov22:45:registrar]agregando la informacion del como el usuario se registro en el curso");
		$userEnrollmentDaov22 =  new UserEnrollmentDaov22();
		$ueVo = $userEnrollmentDaov22->save($enrol->id, $idUser, $dbhm22);
		if(is_numeric($ueVo)){
			mysqli_rollback($dbhm22);
			return false;
		}
		
		
		mysqli_commit($dbhm22);
		$log->LogInfo("[RolBov22:55:registrar]---------<");
		return true;
	}

	function eliminar($idCourse, $idUser, $role){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("RolBov22.eliminar-------->");
		
		if($role!=TEACHER_ROLE && $role!=STUDENT_ROLE ){
			$log->LogInfo("[RolBov22.eliminar73:]rol inadecuado");
			return false;
		}
		
		$dbhm22=Sdx_ConectaBasev22();
	
		//Buscando el contexto del curso
		$contextDao = new ContextDaov22();
		$CONTEXT_COURSE = 50;
		$log->LogInfo("[RolBov22.eliminar:82]buscando el context del curso");
		$contextCurso = $contextDao->find($CONTEXT_COURSE, $idCourse, $dbhm22);
		
		//role
		$log->LogInfo("[RolBov22.eliminar:86]eliminando el rol");
		$roleDao = new RoleDaov22();
		$result=$roleDao->deleteByContextAndUser($dbhm22, $contextCurso->id, $idUser, $role);
		if(!$result){
			$log->LogError("eliminar-ROLLBACK");
			mysqli_rollback($dbhm22);
			return false;
		}
		
		//enrol
		$enrol=null;
		$enrolDao=new EnrolDaov22();
		$enrols=$enrolDao->findByCourse($idCourse, $dbhm22);
		for ($i=0;$i<count($enrols);$i++){
			if($enrols[$i]->enrol=='manual'){
				$enrol=$enrols[$i];
				break;
			}
		}
		$log->LogInfo("[RolBov22.eliminar:105]tipo de enrolamiento:".$enrols[$i]->enrol);
		$userEnrollmentDao = new UserEnrollmentDaov22();
		$result=$userEnrollmentDao->deleteByEnrolAndUser($dbhm22, $enrol->id, $idUser);
		if(!$result){
			$log->LogError("eliminar-ROLLBACK");
			mysqli_rollback($dbhm22);
			return false;
		}
		$log->LogInfo("[RolBov22.eliminar:113]enrolamiento terminado");
		
		mysqli_commit($dbhm22);
		$log->LogInfo("RolBov22.eliminar--------<");
		return true;
	}
	
}
?>