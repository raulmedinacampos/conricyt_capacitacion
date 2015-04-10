<?php
class GroupDaov19{
	function save( &$dbhm19, $Id_Curso, $name, $description='', $enrolmentkey=''){
		global $CFG_v19;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[GroupDaov19:9:save]--------->");
	
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
		
		$time = time();
		
		
		
		//registra al usuario
		$INSERT_COURSE = 'INSERT INTO '.$CFG_v19->prefix.'groups'.
							'(courseid, name, description, enrolmentkey,'.
							'picture, hidepicture, timecreated, timemodified) '.
							'VALUES (?, ?, ?, ?,'.
							'0, 0, ?, ?)';
		
		$log->LogInfo("[GroupDaov19:50:save]".$INSERT_COURSE);
		$stmt = mysqli_stmt_init($dbhm19);
		mysqli_stmt_prepare($stmt, $INSERT_COURSE);
		
		mysqli_stmt_bind_param($stmt, 'isss'.'ii', $Id_Curso, $name,$description, 
								$enrolmentkey, $time,$time);
		$log->LogInfo("[GroupDaov19:31:save]hasta aqui");
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogInfo("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
	
		$id_curso=mysqli_insert_id($dbhm19);
		$group = new stdClass();
		$group->id = $id_curso;
		$log->LogInfo("[GroupDaov19:42:save]".$group->id."");
		mysqli_stmt_close($stmt);
		
		if($localConn ){
			mysqli_close($dbhm19);
		}
	
		$log->LogInfo("[GroupDaov19:77:save]---------<");
		return $group;
	}
	function delete($id, &$dbhm19){
		global $CFG_v19;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[GroupDaov19:55:delete]--------->");
	
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
	
		$DELETE_COURSE = 'DELETE FROM '.$CFG_v19->prefix.'groups WHERE id=? ';
	
		$stmt = mysqli_stmt_init($dbhm19);
		mysqli_stmt_prepare($stmt, $DELETE_COURSE);
		mysqli_stmt_bind_param($stmt, 'i', $id );
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogInfo("[GroupDaov19:71:delete]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
		mysqli_stmt_close($stmt);
	
		if($localConn ){
			mysqli_close($dbhm19);
		}
		$log->LogInfo("[GroupDaov19:79:delete]---------<");
		return true;
	}
	
	function update(&$dbhm19, $groupid, $name, $description=''){
		global $CFG_v19;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[GroupDaov19:88:update]--------->");
	
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
		$time = time();
		
// 		//registra al usuario
		$UPDATE_COURSE = 'UPDATE '.$CFG_v19->prefix.'groups SET '.
				'name=?,'.
				'description=? '.
				'WHERE id = ?';
		//$log->LogInfo("[GroupDaov19:204:update]UPDATE_COURSE=".$UPDATE_COURSE."");
		$stmt = mysqli_stmt_init($dbhm19);
		mysqli_stmt_prepare($stmt, $UPDATE_COURSE);
		mysqli_stmt_bind_param($stmt, 'ssi', $name, $description,$groupid);
		
		mysqli_stmt_execute($stmt);
	
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogInfo("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
	
		mysqli_stmt_close($stmt);
	
		$log->LogInfo("[GroupDaov19:115:update]---------<");
		return true;
	}
}
?>