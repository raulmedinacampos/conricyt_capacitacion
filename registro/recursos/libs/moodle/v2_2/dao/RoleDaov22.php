<?php



class RoleDaov22 {
		
	function save($roleid, $contextid, $userid, &$dbhm22){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		//$log->LogInfo("[Rolev22:16:save]--------->");
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
		
		$basedepth=1;
		
		$INSERT_ROLE = 'INSERT INTO  '.$CFG_v22->prefix.'role_assignments ('.
		'roleid, contextid, userid, timemodified, modifierid, component, itemid, sortorder) VALUES '.
		'(?,?,?,?,2,\'\',0,0)';
		
		
		$stmt = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt, $INSERT_ROLE);
		$time=time();
		mysqli_stmt_bind_param($stmt, 'iiii', $roleid, $contextid, $userid, $time);
		mysqli_stmt_execute($stmt);
		
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			//$log->LogInfo("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
		
		$id_role=mysqli_insert_id($dbhm22);
		$role = new stdClass();
		$role->id = $id_role;
		mysqli_stmt_close($stmt);
		
		if($localConn ){
			mysqli_close($dbhm22);
		}
		//$log->LogInfo("[Rolev22:45:save]---------<");
		return $role;
	}
	
	function find($userid, &$dbhm22){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
		
		$SELECT_ROLE = 'SELECT id, roleid, contextid, userid FROM '.$CFG_v22->prefix.'role_assignments '.
				'WHERE userid=?';
		
		
		$stmt = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt, $SELECT_ROLE);
		
		mysqli_stmt_bind_param($stmt, 'i', $userid);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $id, $roleid, $contextid, $userid );
		mysqli_stmt_store_result($stmt);
		$nr=mysqli_stmt_num_rows($stmt);
		$log->LogInfo("[Rolev22:72:findByCourse]numero de registros=".$nr);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("[Rolev22:72:findByCourse]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
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
			mysqli_close($dbhm22);
		}
		
		return $arrayData;
	}
	
	function deleteAllRoles(  $userid, &$dbhm22){
		global $CFG_v22;
		//$log->LogInfo("[Rolev22:79:deleteAllRoles]--------->");
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
		
		$stmt = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt, 'DELETE FROM '.$CFG_v22->prefix.'role_assignments WHERE userid=? ');
		mysqli_stmt_bind_param($stmt, 'i', $userid);
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			//$log->LogInfo("[Contextv22:169:delete]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
		mysqli_stmt_close($stmt);
		
		if($localConn ){
			mysqli_close($dbhm22);
		}
		//$log->LogInfo("[Rolev22:100:deleteAllRoles]--------->");
		return true;
	}
	
	function delete( $roleid,  $userid, &$dbhm22){
		global $CFG_v22;
	
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
	
		$stmt = mysqli_stmt_init($dbh);
		mysqli_stmt_prepare($stmt, 'DELETE FROM '.$CFG_v22->prefix.'role_assignments WHERE userid=? AND roleid=?');
		mysqli_stmt_bind_param($stmt, 'ii', $userid, $roleid);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	
		if($localConn ){
			mysqli_close($dbhm22);
		}
	}
	function deleteByContextAndUser(&$dbhm22, $contextid, $userid, $roleid){
		global $CFG_v22;
	
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
	
		$stmt = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt, 'DELETE FROM '.$CFG_v22->prefix.'role_assignments WHERE userid=? AND roleid=? AND contextid=?');
		mysqli_stmt_bind_param($stmt, 'iii', $userid, $roleid, $contextid);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	
		if($localConn ){
			mysqli_close($dbhm22);
			return false;
		}
		return true;
	}
}
?>