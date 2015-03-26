<?php
include('recursos/libs/moodle/v2_2/moodle_config22.php');
include("recursos/libs/moodle/v2_2/dao/UserDaov22.php");
include("recursos/libs/moodle/v2_2/dao/RoleDaov22.php");
include("recursos/libs/moodle/v2_2/dao/UserEnrollmentDaov22.php");
include('recursos/libs/moodle/v2_2/dao/UserPreferenceDaov22.php');
include('recursos/libs/moodle/v2_2/dao/ContextDaov22.php');
include('recursos/libs/moodle/v2_2/dao/CourseDaov22.php');
include('recursos/libs/moodle/v2_2/dao/EnrolDaov22.php');

class UserBov22{
	function registrar($username, $password, $Nombre, $Apaterno, $Amaterno, $email, $Lugar_Residencia, $Id_Pais, $role, $Id, &$dbh, $shortnames){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[UserBov22:11:registrar]--------->");
	
		$dbhm22 = Sdx_ConectaBasev22();
	
		$userDao = new UserDaov22();
		$user=$userDao->save($username, $password, $Nombre, $Apaterno.' '.$Amaterno, $email, $Lugar_Residencia, $Id_Pais, $dbhm22);
		if(is_numeric($user)){
			mysqli_rollback($dbhm22);
			return false;
		}
		//registra sus preferencias
		$upDao = new UserPreferenceDaov22();
		
		$upDao->save($user->id, 'email_send_count', 1, $dbhm22);
		$upDao->save($user->id, 'email_bounce_count', 1, $dbhm22);
		$upDao->save($user->id, 'auth_forcepasswordchange', 0, $dbhm22);
		
		//registra su contexto
		$contextDao = new ContextDaov22();
		$log->LogInfo("[UserBov22:33:registrar]Creando contexto");
		$c = $contextDao->save(CONTEXT_USER, $user->id, null, $dbhm22);
		if(is_numeric($c)){
			mysqli_rollback($dbhm22);
			return false;
		}
		$user->context = $c;
		$log->LogInfo("[UserBov22:36:registrar]Creando rol");
	
		//registrar el rol de sistema del usuario
		if($role==TEACHER_ROLE || $role==STUDENT_ROLE ){
			$log->LogInfo("Al alumno o maestro no se le debe asiganr el rol en contexto del sistema.");
// 			$roleDao = new RoleDaov22();
// 			$contextSystem = 1;
// 			$roleData=$roleDao->save($role, $contextSystem, $user->id, $dbhm22);
// 			if(is_numeric($roleData)){
// 				$log->LogError("[UserBov22:44:registrar]ROLLBACK ".$roleData."");
// 				mysqli_rollback($dbhm22);
// 				return false;
// 			}
		}
		if($role==TEACHER_ROLE){
			$instructorDao = new InstructorDao();
			$instructorDao->actualizarInstructorId($user->id, $Id, MOODLE_V2_2, $dbh);
		}else if($role==STUDENT_ROLE){
			$alumnoDao = new AlumnoDao();
			//$alumnoDao->actualizarAlumnoId($user->id, $Id, MOODLE_V2_2, $dbh);
		}
		//Registro a un cuso de prueba
		//TODO:Eliminar este codigo ya que va en la pantalla de cursos
// 		$contextDao = new ContextDaov22();
// 		$CONTEXT_COURSE = 50;
// 		//$log->LogInfo("[UserBov22:88:registrar]buscando el context del curso");
// 		$contextCurso = $contextDao->find($CONTEXT_COURSE, 2, $dbhm22);
// 		$role=$roleDao->save(TEACHER_ROLE, $contextCurso->id, $user->id, $dbhm22);
// 		if(is_numeric($role)){
// 			mysqli_rollback($dbhm19);
// 			return false;
// 		}
// 		$userEnrollmentDaov22 =  new UserEnrollmentDaov22();
// 		$ueVo = $userEnrollmentDaov22->save($user->id, $dbhm22);
// 		if(is_numeric($ueVo)){
// 			mysqli_rollback($dbhm22);
// 			return false;
// 		}
		
		if($shortnames) {
			// Se obtienen ID de mdl_course
			foreach($shortnames as $curso) {
				$courseDaov22 = new CourseDaov22();
				$log->LogInfo("[UserBov22:83:registrar]buscando id del curso");
				$idCourse = $courseDaov22->getByShortname($curso, $dbhm22);
				
				$contextDao = new ContextDaov22();
				$CONTEXT_COURSE = 50;
				$log->LogInfo("[UserBov22:88:registrar]buscando el context del curso");
				$contextCurso = $contextDao->find($CONTEXT_COURSE, $idCourse, $dbhm22);
				
				$enrolDao = new EnrolDaov22();
				$log->LogInfo("[UserBov22:94:registrar]buscando enrol del curso");
				$enrolId = $enrolDao->findByCourseEnrole($idCourse, $dbhm22);

				$userEnrollmentDaov22 = new UserEnrollmentDaov22();
				$log->LogInfo("[UserBov22:97:registrar]inserta en user_enrolments");
				$ueVo = $userEnrollmentDaov22->save($enrolId, $user->id, $dbhm22);
				if(is_numeric($ueVo)){
					mysqli_rollback($dbhm22);
					return false;
				}
				
				$roleDaov22 = new RoleDaov22();
				$ROLE_ID = 5;
				$log->LogInfo("[UserBov22:94:registrar]inserta en role_assignments");
				$rVo = $roleDaov22->save($ROLE_ID, $contextCurso->id, $user->id, $dbhm22);
				if(is_numeric($rVo)){
					mysqli_rollback($dbhm22);
					return false;
				}
			}
		}
		

		
		mysqli_commit($dbhm22);
		$log->LogInfo("[UserBov22:79:registrar]---------<");
		return true;
	}

	function eliminar($idUserv22){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("eliminar-------->");
		$dbhm22=Sdx_ConectaBasev22();
	
		//user preference
		$userPreferenceDao = new UserPreferenceDaov22();
		$result=$userPreferenceDao->delete($idUserv22, $dbhm22);
		if(!$result){
			$log->LogError("eliminar-ROLLBACK");
			mysqli_rollback($dbhm22);
			return false;
		}
	
		//role
		$roleDao = new RoleDaov22();
		$result=$roleDao->deleteAllRoles($idUserv22, $dbhm22);
		if(!$result){
			$log->LogError("eliminar-ROLLBACK");
			mysqli_rollback($dbhm22);
			return false;
		}
	
		//context
		$contextDao = new ContextDaov22();
		$result=$contextDao->delete(CONTEXT_USER, $idUserv22, $dbhm22);
		if(!$result){
			$log->LogError("eliminar-ROLLBACK");
			mysqli_rollback($dbhm22);
			return false;
		}
	
		//enrol
		$userEnrollmentDao = new UserEnrollmentDaov22();
		$result=$userEnrollmentDao->deleteByUser($idUserv22, $dbhm22);
		if(!$result){
			$log->LogError("eliminar-ROLLBACK");
			mysqli_rollback($dbhm22);
			return false;
		}
	
		//usuario
		$userDao = new UserDaov22();
		$result=$userDao->delete($idUserv22, $dbhm22);
		if(!$result){
			$log->LogError("eliminar-ROLLBACK");
			mysqli_rollback($dbhm22);
			return false;
		}
		mysqli_commit($dbhm22);
		$log->LogInfo("eliminar--------<");
		return true;
	}
	
	function modificar($email, $password, $Nombre, $Apaterno, $Amaterno, $Lugar_Residencia, $Id_Pais, $idUser, &$dbh){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[UserBov22:137:modificar]--------->");
	
		$dbhm22 = Sdx_ConectaBasev22();
	
		$userDao = new UserDaov22();
		$user=$userDao->update($password, $Nombre, $Apaterno.' '.$Amaterno, $email, $Lugar_Residencia, $Id_Pais,$idUser, $dbhm22);
	
		if(is_numeric($user)){
			mysqli_rollback($dbhm22);
			return false;
		}
	
		mysqli_commit($dbhm22);
		$log->LogInfo("[UserBov22:150:modificar]---------<");
		return true;
	}
	function getUsers($ids){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[UserBov19:129:modificar]--------->");
		//obtener los id asociados a moodle
		$dbhm22=Sdx_ConectaBasev22();
	
		$userDao = new UserDaov22();
		$users=$userDao->getLatesUsers($dbhm22, $ids);
	
		mysqli_commit($dbhm22);
		$log->LogInfo("[UserBov19:142:modificar]---------<");
		return $users;
	}
}