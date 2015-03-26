<?php
class GroupDaov22{
	function save( &$dbhm22, $Id_Curso, $name, $description='', $enrolmentkey=''){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[GroupDaov22:9:save]--------->");
	
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
		
		$time = time();
		
		//registra al usuario
		$INSERT_COURSE = 'INSERT INTO '.$CFG_v22->prefix.'groups'.
							'(courseid, name, description, descriptionformat, enrolmentkey,'.
							'picture, hidepicture, timecreated, timemodified) '.
							'VALUES (?, ?, ?, 0, ?,'.
							'0, 0, ?, ?)';
		
		$log->LogInfo("[GroupDaov22:50:save]".$INSERT_COURSE);
		$stmt = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt, $INSERT_COURSE);
		
		mysqli_stmt_bind_param($stmt, 'isss'.'ii', $Id_Curso, $name,$description, 
								$enrolmentkey, $time,$time);
		$log->LogInfo("[GroupDaov22:31:save]hasta aqui");
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogInfo("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
	
		$id_curso=mysqli_insert_id($dbhm22);
		$group = new stdClass();
		$group->id = $id_curso;
		$log->LogInfo("[GroupDaov22:42:save]".$group->id."");
		mysqli_stmt_close($stmt);
		
		if($localConn ){
			mysqli_close($dbhm22);
		}
	
		$log->LogInfo("[GroupDaov22:77:save]---------<");
		return $group;
	}
	function delete($id, &$dbhm22){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[GroupDaov22:55:delete]--------->");
	
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
	
		$DELETE_COURSE = 'DELETE FROM '.$CFG_v22->prefix.'groups WHERE id=? ';
	
		$stmt = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt, $DELETE_COURSE);
		mysqli_stmt_bind_param($stmt, 'i', $id );
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogInfo("[GroupDaov22:71:delete]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
		mysqli_stmt_close($stmt);
	
		if($localConn ){
			mysqli_close($dbhm22);
		}
		$log->LogInfo("[GroupDaov22:79:delete]---------<");
		return true;
	}
	
	function update(&$dbhm22, $groupid, $name, $description=''){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[GroupDaov22:84:update]--------->");
	
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
		$time = time();
		
// 		//registra al usuario
		$UPDATE_COURSE = 'UPDATE '.$CFG_v22->prefix.'groups SET '.
				'name=?,'.
				'description=? '.
				'WHERE id = ?';
		$log->LogInfo("[GroupDaov22:98:update]UPDATE_COURSE=".$UPDATE_COURSE);
		$log->LogInfo("[GroupDaov22:98:update]name=".$name.",description=".$description.",groupid=".$groupid);
		$stmt = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt, $UPDATE_COURSE);
		mysqli_stmt_bind_param($stmt, 'ssi', $name, $description,$groupid);
		
		mysqli_stmt_execute($stmt);
	
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogInfo("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
	
		mysqli_stmt_close($stmt);
	
		$log->LogInfo("[GroupDaov22:115:update]---------<");
		return true;
	}
}
?>