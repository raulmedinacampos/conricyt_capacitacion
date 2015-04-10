<?php
class GeneralDaov22{
	function deleteByField(&$dbhm22, $table,$field,$id){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[GeneralDaov22:6:delete]--------->");
	
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
	
		$DELETE_COURSE = 'DELETE FROM '.$CFG_v22->prefix.$table.' WHERE '.$field.'=? ';
	
		$stmt = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt, $DELETE_COURSE);
		mysqli_stmt_bind_param($stmt, (is_numeric($id)?'i':'s'), $id );
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("[GeneralDaov22:171:delete]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
		mysqli_stmt_close($stmt);
	
		if($localConn ){
			mysqli_close($dbhm22);
		}
		$log->LogInfo("[GeneralDaov22:30:delete]---------<");
		return true;
	}
	
	
}
?>