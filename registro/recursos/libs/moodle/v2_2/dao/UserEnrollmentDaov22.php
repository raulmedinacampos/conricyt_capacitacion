<?php

class UserEnrollmentDaov22 {
	
	function save($enrolid, $userid, &$dbhm22){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[UserEnrollmentv22:7:save]--------->");
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
		
		$basedepth=1;
		
		$INSERT_UE = 'INSERT INTO  '.$CFG_v22->prefix.'user_enrolments ('.
		'status, enrolid, userid, timestart, timeend, modifierid, timecreated, timemodified) VALUES '.
		'(0,?,?,?,0,2,?,?)';
		
		$stmt = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt, $INSERT_UE);
		$time = time();
		mysqli_stmt_bind_param($stmt, 'iiiii', $enrolid, $userid, $time, $time, $time);
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogInfo("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
		
		$id_enrollment = mysqli_insert_id($dbhm22);
		mysqli_stmt_close($stmt);
		
		$user_enrolments = new stdClass();
		$user_enrolments->id = $id_enrollment;
		
		if($localConn ){
			mysqli_close($dbhm22);
		}
		$log->LogInfo("[UserEnrollmentv22:35:save]---------<");
		return $user_enrolments;
	}
		
	function deleteByUser($id, &$dbhm22){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[UserEnrollmentv22:153:delete]--------->");
	
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
	
		$DELETE_USER_ENROLLMENT = 'DELETE FROM '.$CFG_v22->prefix.'user_enrolments WHERE userid=? ';
	
		$stmt = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt, $DELETE_USER_ENROLLMENT);
		mysqli_stmt_bind_param($stmt, 'i', $id );
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("[UserEnrollmentv22:169:delete]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
		mysqli_stmt_close($stmt);
	
		if($localConn ){
			mysqli_close($dbhm22);
		}
		$log->LogInfo("[UserEnrollmentv22:177:delete]---------<");
		return true;
	}
	
	function deleteByEnrol($id, &$dbhm22){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[UserEnrollmentDaov22:77:deleteByEnrol]--------->");
	
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
	
		$DELETE_USER_ENROLMENT = 'DELETE FROM '.$CFG_v22->prefix.'user_enrolments WHERE enrolid=? ';
	
		$stmt = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt, $DELETE_USER_ENROLMENT);
		mysqli_stmt_bind_param($stmt, 'i', $id );
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("[UserEnrollmentv22:169:delete]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
		mysqli_stmt_close($stmt);
	
		if($localConn ){
			mysqli_close($dbhm22);
		}
		$log->LogInfo("[UserEnrollmentv22:177:delete]---------<");
		return true;
	}
	
	function deleteByEnrolAndUser(&$dbhm22, $id, $userid){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[UserEnrollmentDaov22:109:deleteByEnrolAndUser]--------->");
	
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
	
		$DELETE_USER_ENROLMENT = 'DELETE FROM '.$CFG_v22->prefix.'user_enrolments WHERE enrolid=? AND userid=?';
	
		$stmt = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt, $DELETE_USER_ENROLMENT);
		mysqli_stmt_bind_param($stmt, 'ii', $id, $userid );
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("[UserEnrollmentDaov22:125:deleteByEnrolAndUser]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
		mysqli_stmt_close($stmt);
	
		if($localConn ){
			mysqli_close($dbhm22);
		}
		$log->LogInfo("[UserEnrollmentDaov22:133:deleteByEnrolAndUser]---------<");
		return true;
	}
}
?>