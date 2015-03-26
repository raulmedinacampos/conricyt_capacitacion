<?php
class BlockInstanceDaov19{
	function save( $blockid, $pageid, $position, $weight, $visible, &$dbhm19){
		global $CFG_v19;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[BlockInstanceDaov19:6:save]--------->");
	
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
	
		//registra al usuario
		$INSERT_BLOCK_INSTANCE = 'INSERT INTO '.$CFG_v19->prefix.'block_instance ('.
				'blockid, pageid, pagetype, position, weight, visible, configdata) VALUES '.
				'(?,?,\'course-view\',?,?,?,null)';
	
		$stmt = mysqli_stmt_init($dbhm19);
		mysqli_stmt_prepare($stmt, $INSERT_BLOCK_INSTANCE);
	
		mysqli_stmt_bind_param($stmt, 'iisii', $blockid, $pageid, $position, $weight, $visible );
		mysqli_stmt_execute($stmt);
	
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
	
	
		$id_block_instance=mysqli_insert_id($dbhm19);
		$log->LogInfo("[BlockInstanceDaov19:33:save]".$id_block_instance."");
		mysqli_stmt_close($stmt);
		$log->LogInfo("[BlockInstanceDaov19:35:save]closed statement");
	
	
		$blockInstance = new stdClass();
		$blockInstance->id = $id_block_instance;
	
		if($localConn ){
			mysqli_close($dbhm19);
		}
	
		$log->LogInfo("[BlockInstanceDaov19:45:save]---------<");
		return $blockInstance;
	}
	
	function findBlocksPerCourse($idCourse, &$dbhm19){
		global $CFG_v19;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[BlockInstanceDaov19:52:findBlocksPerCourse]--------->");
		
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
		$SELECT_BLOCK_INSTANCE = 'SELECT id, blockid, pageid FROM '.$CFG_v19->prefix.'block_instance WHERE pageid=?';
		
		$log->LogInfo("[BlockInstanceDaov19:61:findBlocksPerCourse]".$SELECT_BLOCK_INSTANCE);
		
		$stmt2 = mysqli_stmt_init($dbhm19);
		mysqli_stmt_prepare($stmt2, $SELECT_BLOCK_INSTANCE);
		mysqli_stmt_bind_param($stmt2, 'i', $idCourse);
		mysqli_stmt_execute($stmt2);
		mysqli_stmt_bind_result($stmt2, $id, $blockid, $pageid);
		
		mysqli_stmt_store_result($stmt2);
		$nr=mysqli_stmt_num_rows($stmt2);
		$log->LogInfo("[BlockInstanceDaov19:72:findBlocksPerCourse]numero de registros=".$nr);
		$errorNum=mysqli_stmt_errno($stmt2);
		if($errorNum){
			$log->LogError("[BlockInstanceDaov19:72:findBlocksPerCourse]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
		$arrayData = array();
		$i=0;
		while (mysqli_stmt_fetch($stmt2)) {
			$bi=new stdClass();
			$bi->id=$id;
			$bi->blockid=$blockid;
			$bi->pageid=$pageid;
			$arrayData[$i++]=$bi;
		}
		
		mysqli_stmt_free_result($stmt2);
		mysqli_stmt_close($stmt2);
		
		$log->LogInfo("[BlockInstanceDaov19:92:findBlocksPerCourse]".count($arrayData));
		
		if($localConn ){
			mysqli_close($dbhm19);
		}
		$log->LogInfo("[BlockInstanceDaov19:103:findBlocksPerCourse]---------<");
		return $arrayData;
	}
	
	function delete($id, &$dbhm19){
		global $CFG_v19;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[BlockInstanceDaov19:102:delete]--------->");
	
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
	
		$DELETE_BLOCK_INSTANCE = 'DELETE FROM '.$CFG_v19->prefix.'block_instance WHERE id=? ';
	
		$stmt = mysqli_stmt_init($dbhm19);
		mysqli_stmt_prepare($stmt, $DELETE_BLOCK_INSTANCE);
		mysqli_stmt_bind_param($stmt, 'i', $id );
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("[BlockInstanceDaov19:171:delete]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
		mysqli_stmt_close($stmt);
	
		if($localConn ){
			mysqli_close($dbhm19);
		}
		$log->LogInfo("[BlockInstanceDaov19:179:delete]---------<");
		return true;
	}
}
?>