<?php

if(!class_exists("KLogger")){
	require_once 'recursos/libs/KLogger/KLogger.php';
}
class UserPreferenceDaov19 {
	
	
	function save($userId, $name, $value, &$dbhm19){
		global $CFG_v19;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		//$log->LogInfo("[UserPreferencev19:14:save]--------->");
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
		
		$INSERT_USER_PREFERENCE = 'INSERT INTO  '.$CFG_v19->prefix.'user_preferences ('.
		'userid, name, value) VALUES '.
		'(?,?,?)';
		
		$dbhm19=Sdx_ConectaBasev19();
		$stmt = mysqli_stmt_init($dbhm19);
		mysqli_stmt_prepare($stmt, $INSERT_USER_PREFERENCE);
		
		mysqli_stmt_bind_param($stmt, 'isi', $userId, $name, $value);
		mysqli_stmt_execute($stmt);
		
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogInfo("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
		
		$id=mysqli_insert_id($dbhm19);
		mysqli_stmt_close($stmt);
		//$log->LogInfo("[UserPreferencev19:14:save]id=".$id."");
		$user_preference = new stdClass();
		$user_preference->id = $id;
		$localConn = false;
		if($localConn ){
			mysqli_close($dbhm19);
		}
		//$log->LogInfo("[UserPreferencev19:37:save]---------<");
		return $user_preference;
	}
	
	function find($userId, &$dbhm19){
		global $CFG_v19;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$SELECT_USER_PREFERENCE = 'SELECT id, userid, name, value FROM '.$CFG_v19->prefix.'user_preferences '.
				'WHERE userid=?';
		
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
		
		$stmt = mysqli_stmt_init($dbhm19);
		
		mysqli_stmt_prepare($stmt, $SELECT_USER_PREFERENCE);
		mysqli_stmt_bind_param($stmt, 'i', $userId);
		mysqli_stmt_execute($stmt);
		
		$user_preference = new stdClass();
		
		mysqli_stmt_bind_result($stmt, $context->id, $user_preference->userid, $user_preference->name, $user_preference->value);
		mysqli_stmt_store_result($stmt);
		mysqli_stmt_close($stmt);
		
		if($localConn ){
			mysqli_close($dbhm19);
		}
		
		return $user_preference;
	}
	
	function delete($id, &$dbhm19){
		global $CFG_v19;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		//$log->LogInfo("[UserPreferencev19:78:delete]--------->");
	
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
	
		$DELETE_USER_PREFERENCE = 'DELETE FROM '.$CFG_v19->prefix.'user_preferences WHERE userid=? ';
	
		$stmt = mysqli_stmt_init($dbhm19);
		mysqli_stmt_prepare($stmt, $DELETE_USER_PREFERENCE);
		mysqli_stmt_bind_param($stmt, 'i', $id );
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			//$log->LogInfo("[UserPreferencev19:95:delete]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".", 3, "C:/Tata/moodle.log");
			return false;
		}
		mysqli_stmt_close($stmt);
	
		if($localConn ){
			mysqli_close($dbhm19);
		}
		//$log->LogInfo("[UserPreferencev19:102:delete]---------<");
		return true;
	}
	
	
}
?>