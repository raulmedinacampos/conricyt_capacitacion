<?php
class GroupMemberDaov22{
	function save( &$dbhm22, $groupid, $userid){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[GroupMemberDaov22:9:save]--------->");
	
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
		
		$time = time();
		
		//registra al usuario
		$INSERT_COURSE = 'INSERT INTO '.$CFG_v22->prefix.'groups_members'.
							'(groupid, userid, timeadded) VALUES (?, ?, ?)';
		
		$log->LogInfo("[GroupMemberDaov22:21:save]".$INSERT_COURSE);
		$stmt = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt, $INSERT_COURSE);
		
		mysqli_stmt_bind_param($stmt, 'iii', $groupid, $userid, $time);
		$log->LogInfo("[GroupMemberDaov22:26:save]hasta aqui");
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogInfo("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
	
		$id_curso=mysqli_insert_id($dbhm22);
		$groupMember = new stdClass();
		$groupMember->id = $id_curso;
		$log->LogInfo("[GroupMemberDaov22:37:save]".$groupMember->id."");
		mysqli_stmt_close($stmt);
		
		if($localConn ){
			mysqli_close($dbhm22);
		}
	
		$log->LogInfo("[GroupMemberDaov22:44:save]---------<");
		return $groupMember;
	}
	
	function delete(&$dbhm22, $obj){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[GroupMemberDaov22:51:delete]--------->");
	
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
		$obj = (array)$obj;
		$DELETE_COURSE = 'DELETE FROM '.$CFG_v22->prefix.'groups_members WHERE ';
		
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
		
		$stmt2 = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt2, $DELETE_COURSE);
		
		call_user_func_array('mysqli_stmt_bind_param', array_merge (array($stmt2, $types), $this->refValues($obj)));
		
		mysqli_stmt_execute($stmt2);
		
		$errorNum=mysqli_stmt_errno($stmt2);
		if($errorNum){
			$log->LogInfo("[GroupMemberDaov22:83:delete]Error ".$errorNum.": ".mysqli_stmt_error($stmt2).".");
			return false;
		}
		mysqli_stmt_close($stmt2);
	
		if($localConn ){
			mysqli_close($dbhm22);
		}
		$log->LogInfo("[GroupMemberDaov22:91:delete]---------<");
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