<?php

if(!class_exists("KLogger")){
	require_once 'recursos/libs/KLogger/KLogger.php';
}
class UserPreferenceDaov22 {
	
	
	function save($userId, $name, $value, &$dbhm22){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		//$log->LogInfo("[UserPreferencev22:14:save]--------->");
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
		
		$INSERT_USER_PREFERENCE = 'INSERT INTO  '.$CFG_v22->prefix.'user_preferences ('.
		'userid, name, value) VALUES '.
		'(?,?,?)';
		
		$dbhm22=Sdx_ConectaBasev22();
		$stmt = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt, $INSERT_USER_PREFERENCE);
		
		mysqli_stmt_bind_param($stmt, 'iss', $userId, $name, $value);
		mysqli_stmt_execute($stmt);
		
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			//$log->LogInfo("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
		
		$id=mysqli_insert_id($dbhm22);
		mysqli_stmt_close($stmt);
		//$log->LogInfo("[UserPreferencev22:14:save]id=".$id."");
		$user_preference = new stdClass();
		$user_preference->id = $id;
		$localConn = false;
		if($localConn ){
			mysqli_close($dbhm22);
		}
		//$log->LogInfo("[UserPreferencev22:37:save]---------<");
		return $user_preference;
	}
	
	function find($userId, &$dbhm22){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$SELECT_USER_PREFERENCE = 'SELECT id, userid, name, value FROM '.$CFG_v22->prefix.'user_preferences '.
				'WHERE userid=?';
		
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
		
		$stmt = mysqli_stmt_init($dbhm22);
		
		mysqli_stmt_prepare($stmt, $SELECT_USER_PREFERENCE);
		mysqli_stmt_bind_param($stmt, 'i', $userId);
		mysqli_stmt_execute($stmt);
		
		$user_preference = new stdClass();
		
		mysqli_stmt_bind_result($stmt, $context->id, $user_preference->userid, $user_preference->name, $user_preference->value);
		mysqli_stmt_store_result($stmt);
		mysqli_stmt_close($stmt);
		
		if($localConn ){
			mysqli_close($dbhm22);
		}
		
		return $user_preference;
	}
	
	function update($id, &$dbhm22){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		//$log->LogInfo("[UserPreferencev22:78:delete]--------->");
	
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
	
		$UPDATE_USER_PREFERENCE = 'UPDATE '.$CFG_v22->prefix.'user_preferences SET value=\'0\' WHERE userid=? AND name=\'auth_forcepasswordchange\' ';
	
		$stmt = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt, $UPDATE_USER_PREFERENCE);
		mysqli_stmt_bind_param($stmt, 'i', $id );
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			//$log->LogInfo("[Contextv22:94:update]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
		mysqli_stmt_close($stmt);
	
		if($localConn ){
			mysqli_close($dbhm22);
		}
		//$log->LogInfo("[UserPreferencev22:102:delete]---------<");
		return true;
	}
	
	function delete($id, &$dbhm22){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		//$log->LogInfo("[UserPreferencev22:78:delete]--------->");
	
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
	
		$DELETE_USER_PREFERENCE = 'DELETE FROM '.$CFG_v22->prefix.'user_preferences WHERE userid=? ';
	
		$stmt = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt, $DELETE_USER_PREFERENCE);
		mysqli_stmt_bind_param($stmt, 'i', $id );
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			//$log->LogInfo("[Contextv22:94:delete]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
		mysqli_stmt_close($stmt);
	
		if($localConn ){
			mysqli_close($dbhm22);
		}
		//$log->LogInfo("[UserPreferencev22:102:delete]---------<");
		return true;
	}
}
?>