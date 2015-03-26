<?php

class RoleDaov19 {
		
	function save($roleid, $contextid, $userid, &$dbhm19){
		global $CFG_v19;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		//$log->LogInfo("[Rolev19:16:save]--------->");
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
		
		$basedepth=1;
		
		$INSERT_ROLE = 'INSERT INTO  '.$CFG_v19->prefix.'role_assignments ('.
		'roleid, contextid, userid, hidden, timestart, timeend, timemodified, modifierid, enrol, sortorder) VALUES '.
		'(?,?,?,0,?,0,?,2,\'manual\',0)';
		
		
		$stmt = mysqli_stmt_init($dbhm19);
		mysqli_stmt_prepare($stmt, $INSERT_ROLE);
		$time=time();
		mysqli_stmt_bind_param($stmt, 'iiiii', $roleid, $contextid, $userid,$time , $time);
		mysqli_stmt_execute($stmt);
		
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogInfo("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
		
		$id_role=mysqli_insert_id($dbhm19);
		$role = new stdClass();
		$role->id = $id_role;
		mysqli_stmt_close($stmt);
		
		if($localConn ){
			mysqli_close($dbhm19);
		}
		//$log->LogInfo("[Rolev19:45:save]---------<");
		return $role;
	}
	
	function find($userid, &$dbhm19){
		global $CFG_v19;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
		
		$SELECT_ROLE = 'SELECT id, roleid, contextid, userid FROM '.$CFG_v19->prefix.'role_assignments '.
				'WHERE userid=?';
		
		
		$stmt = mysqli_stmt_init($dbhm19);
		mysqli_stmt_prepare($stmt, $SELECT_ROLE);
		
		mysqli_stmt_bind_param($stmt, 'i', $userid);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $id, $roleid, $contextid, $userid );
		mysqli_stmt_store_result($stmt);
		$nr=mysqli_stmt_num_rows($stmt);
		$log->LogInfo("[RoleDaov19:67:findByCourse]numero de registros=".$nr);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("[RoleDaov19:70:findByCourse]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
		
		$arrayData = array();
		$i=0;
		while (mysqli_stmt_fetch($stmt)) {
			$role = new stdClass();
			$role->id=$id;
			$role->roleid=$roleid;
			$role->contextid=$contextid;
			$role->userid=$userid;
			$arrayData[$i++]=$role;
		}

		mysqli_stmt_close($stmt);
		
		if($localConn ){
			mysqli_close($dbhm19);
		}
		
		return $arrayData;
	}
	
	function deleteUserRoles(  $userid, &$dbhm19){
		global $CFG_v19;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		//$log->LogInfo("[Rolev19:79:deleteAllRoles]--------->");
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
		
		$stmt = mysqli_stmt_init($dbhm19);
		mysqli_stmt_prepare($stmt, 'DELETE FROM '.$CFG_v19->prefix.'role_assignments WHERE userid=? ');
		mysqli_stmt_bind_param($stmt, 'i', $userid);
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			//$log->LogInfo("[Contextv19:169:delete]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
		mysqli_stmt_close($stmt);
		
		if($localConn ){
			mysqli_close($dbhm19);
		}
		//$log->LogInfo("[Rolev19:100:deleteAllRoles]--------->");
		return true;
	}
	
	function deleteContextRoles( $context, &$dbhm19){
		global $CFG_v19;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[Rolev19:79:deleteAllRoles]--------->");
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
	
		$stmt = mysqli_stmt_init($dbhm19);
		mysqli_stmt_prepare($stmt, 'DELETE FROM '.$CFG_v19->prefix.'role_assignments WHERE contextid=? ');
		mysqli_stmt_bind_param($stmt, 'i', $context);
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("[Contextv19:169:delete]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
		mysqli_stmt_close($stmt);
	
		if($localConn ){
			mysqli_close($dbhm19);
		}
		$log->LogInfo("[Rolev19:100:deleteAllRoles]--------->");
		return true;
	}
	
	function delete( $roleid,  $userid, &$dbhm19){
		global $CFG_v19;
	
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
	
		$stmt = mysqli_stmt_init($dbh);
		mysqli_stmt_prepare($stmt, 'DELETE FROM '.$CFG_v19->prefix.'role_assignments WHERE userid=? AND roleid=?');
		mysqli_stmt_bind_param($stmt, 'ii', $userid, $roleid);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	
		if($localConn ){
			mysqli_close($dbhm19);
		}
	}
	
	function deleteByContextAndUser(&$dbhm19, $contextid, $userid, $roleid){
		global $CFG_v19;
	
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
	
		$stmt = mysqli_stmt_init($dbhm19);
		mysqli_stmt_prepare($stmt, 'DELETE FROM '.$CFG_v19->prefix.'role_assignments WHERE userid=? AND roleid=? AND contextid=?');
		mysqli_stmt_bind_param($stmt, 'iii', $userid, $roleid, $contextid);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	
		if($localConn ){
			mysqli_close($dbhm19);
			return false;
		}
		return true;
	}
}
?>