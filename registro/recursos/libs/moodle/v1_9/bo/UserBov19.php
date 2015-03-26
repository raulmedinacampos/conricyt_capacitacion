<?php

include('recursos/libs/moodle/v1_9/moodle_config19.php');
include("recursos/libs/moodle/v1_9/dao/UserDaov19.php");
include("recursos/libs/moodle/v1_9/dao/RoleDaov19.php");
include('recursos/libs/moodle/v1_9/dao/UserPreferenceDaov19.php');
include('recursos/libs/moodle/v1_9/dao/ContextDaov19.php');

class UserBov19 {
	
	
	function registrar($username, $password, $Nombre, $Apaterno, $Amaterno, $email, $Lugar_Residencia, $Id_Pais, $role, $Id, &$dbh){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[UserBov19:10:registrar]--------->");
		
		$dbhm19=Sdx_ConectaBasev19();
		//registro usuarios
		$userDao = new UserDaov19();
		$user=$userDao->save($username, $password, $Nombre, $Apaterno.' '.$Amaterno, $email, $Lugar_Residencia, $Id_Pais, $dbhm19);
		if(is_numeric($user)){
			$log->LogError("registrar:17-ROLLBACK");
			mysqli_rollback($dbhm19);
			return false;
		}
		//registra sus preferencias
		$upDao = new UserPreferenceDaov19();
		
		$result=$upDao->save($user->id, 'email_send_count', 1, $dbhm19);
		if(is_numeric($result)){
			$log->LogError("registrar:26-ROLLBACK");
			mysqli_rollback($dbhm19);
			return false;
		}
		$result=$upDao->save($user->id, 'email_bounce_count', 1, $dbhm19);
		if(is_numeric($result)){
			$log->LogError("registrar:31-ROLLBACK");
			mysqli_rollback($dbhm19);
			return false;
		}
		$result=$upDao->save($user->id, 'auth_forcepasswordchange', 1, $dbhm19);
		if(is_numeric($result)){
			$log->LogError("registrar:38-ROLLBACK");
			mysqli_rollback($dbhm19);
			return false;
		}
		//registra su contexto
		$contextDao = new ContextDaov19();
		$c = $contextDao->save(CONTEXT_USER, $user->id, null, $dbhm19);
		if(is_numeric($c)){
			$log->LogError("registrar:46-ROLLBACK");
			mysqli_rollback($dbhm19);
			return false;
		}
		$user->context = $c;
		$log->LogInfo("[UserBov19:51:registrar]".$user->context->id.",".$user->id.",".TEACHER_ROLE."");
		
		
		if($role==TEACHER_ROLE || $role==STUDENT_ROLE ){
			//registrar el rol de sistema
			$log->LogInfo("[UserBov19:40:registrar]Creando rol");
			$roleDao = new RoleDaov19();
			$roleData = $roleDao->save($role, $user->context->id, $user->id, $dbhm19);
			if(is_numeric($roleData)){
				$log->LogError("registrar:46-ROLLBACK");
				mysqli_rollback($dbhm19);
				return false;
			}	
		}
		//guarda los ids
		if($role==TEACHER_ROLE){
			$instructorDao = new InstructorDao();
			$instructorDao->actualizarInstructorId($user->id, $Id, MOODLE_V1_9, $dbh);
		}else if($role==STUDENT_ROLE){
			$alumnoDao = new AlumnoDao();
			$alumnoDao->actualizarAlumnoId($user->id, $Id, MOODLE_V1_9, $dbh);
		}
		//Registro a un cuso de prueba
// 		$contextBo = new Contextv19();
// 		//$log->LogInfo("[UserBov19:31:registrar]buscando el context del curso");
// 		$CONTEXT_COURSE = 50;
// 		$contextCurso = $contextBo->find($CONTEXT_COURSE, 2, $dbhm19);
// 		//$log->LogInfo("[UserBov19:25:registrar]".$contextCurso->id."");
// 		$role = $roleBo->save(TEACHER_ROLE, $contextCurso->id, $user->id, $dbhm19);
// 		if(is_numeric($role)){
// 			mysqli_rollback($dbhm19);
// 			return false;
// 		}
		
		mysqli_commit($dbhm19);
		$log->LogInfo("[UserBov19:74:registrar]---------<");
		return true;
	}
	
	/**
	* Eliminar
	*/
	function eliminar($idUserv19){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("eliminar-------->");
		$dbhm19=Sdx_ConectaBasev19();
	
		//user preference
		$userPreferenceDao = new UserPreferenceDaov19();
		$result=$userPreferenceDao->delete($idUserv19, $dbhm19);
		if(!$result){
			$log->LogError("eliminar-ROLLBACK");
			mysqli_rollback($dbhm19);
			return false;
		}
	
		//role
		$roleDao = new RoleDaov19();
		$result=$roleDao->deleteUserRoles($idUserv19, $dbhm19);
		if(!$result){
			$log->LogError("eliminar-ROLLBACK");
			mysqli_rollback($dbhm19);
			return false;
		}
	
		//context
		$contextDao = new ContextDaov19();
		$result=$contextDao->delete(CONTEXT_USER, $idUserv19, $dbhm19);
		if(!$result){
			$log->LogError("eliminar-ROLLBACK");
			mysqli_rollback($dbhm19);
			return false;
		}
	
		//usuario
		$userDao = new UserDaov19();
		$result=$userDao->delete($idUserv19, $dbhm19);
		if(!$result){
			$log->LogError("eliminar-ROLLBACK");
			mysqli_rollback($dbhm19);
			return false;
		}
		mysqli_commit($dbhm19);
		$log->LogInfo("eliminar--------<");
		return true;
	}
	
	/**
	* Modificar
	*/
	function modificar($email, $password, $Nombre, $Apaterno, $Amaterno, $Lugar_Residencia, $Id_Pais, $idUser, &$dbh){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[UserBov19:129:modificar]--------->");
		//obtener los id asociados a moodle
		$dbhm19=Sdx_ConectaBasev19();
	
		$userDao = new UserDaov19();
		$user=$userDao->update($password, $Nombre, $Apaterno.' '.$Amaterno, $email, $Lugar_Residencia, $Id_Pais, $idUser, $dbhm19);
	
		if(is_numeric($user)){
			mysqli_rollback($dbhm19);
			return false;
		}
	
		mysqli_commit($dbhm19);
		$log->LogInfo("[UserBov19:142:modificar]---------<");
		return true;
	}
	function getUsers($ids){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[UserBov19:129:modificar]--------->");
		//obtener los id asociados a moodle
		$dbhm19=Sdx_ConectaBasev19();
		
		$userDao = new UserDaov19();
		$users=$userDao->getLatesUsers($dbhm19, $ids);
		
		mysqli_commit($dbhm19);
		$log->LogInfo("[UserBov19:142:modificar]---------<");
		return $users;
	}
}