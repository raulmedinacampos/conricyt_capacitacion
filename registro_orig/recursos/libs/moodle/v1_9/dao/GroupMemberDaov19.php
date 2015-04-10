<?php
class GroupMemberDaov19{
	function save( &$dbhm19, $groupid, $userid){
		global $CFG_v19;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[GroupMemberDaov19:9:save]--------->");
	
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
		
		$time = time();
		
		//registra al usuario
		$INSERT_COURSE = 'INSERT INTO '.$CFG_v19->prefix.'groups_members'.
							'(groupid, userid, timeadded) VALUES (?, ?, ?)';
		
		$log->LogInfo("[GroupMemberDaov19:21:save]".$INSERT_COURSE);
		$stmt = mysqli_stmt_init($dbhm19);
		mysqli_stmt_prepare($stmt, $INSERT_COURSE);
		
		mysqli_stmt_bind_param($stmt, 'iii', $groupid, $userid, $time);
		$log->LogInfo("[GroupMemberDaov19:26:save]hasta aqui");
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogInfo("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
	
		$id_curso=mysqli_insert_id($dbhm19);
		$groupMember = new stdClass();
		$groupMember->id = $id_curso;
		$log->LogInfo("[GroupMemberDaov19:37:save]".$groupMember->id."");
		mysqli_stmt_close($stmt);
		
		if($localConn ){
			mysqli_close($dbhm19);
		}
	
		$log->LogInfo("[GroupMemberDaov19:44:save]---------<");
		return $groupMember;
	}
	
	function delete(&$dbhm19, $obj){
		global $CFG_v19;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[GroupMemberDaov19:51:delete]--------->");
	
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
		$obj = (array)$obj;
		$DELETE_COURSE = 'DELETE FROM '.$CFG_v19->prefix.'groups_members WHERE ';
		
		$where=array();
		$types=array();
		$values=array();
		
		foreach ($obj as $field=>$value) {
			$where[] = "$field = ?";
			$types[] = (is_numeric($value)?'i':'s');
		}
		
		$where=implode(' AND ', $where);
		$types=implode('', $types);
		
		$DELETE_COURSE.=$where;
		
		$stmt2 = mysqli_stmt_init($dbhm19);
		mysqli_stmt_prepare($stmt2, $DELETE_COURSE);
		
		call_user_func_array('mysqli_stmt_bind_param', array_merge (array($stmt2, $types), $this->refValues($obj)));
		
		mysqli_stmt_execute($stmt2);
		
		$errorNum=mysqli_stmt_errno($stmt2);
		if($errorNum){
			$log->LogInfo("[GroupMemberDaov19:83:delete]Error ".$errorNum.": ".mysqli_stmt_error($stmt2).".");
			return false;
		}
		mysqli_stmt_close($stmt2);
	
		if($localConn ){
			mysqli_close($dbhm19);
		}
		$log->LogInfo("[GroupMemberDaov19:91:delete]---------<");
		return true;
	}
	
	
	function refValues($arr)
	{
		if (strnatcmp(phpversion(),'5.3') >= 0) //Reference is required for PHP 5.3+
		{
			$refs = array();
			foreach($arr as $key => $value)
				$refs[$key] = &$arr[$key];
			return $refs;
		}
		return $arr;
	}
}
?>