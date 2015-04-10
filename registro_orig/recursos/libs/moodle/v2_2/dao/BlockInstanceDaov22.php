<?php
class BlockInstanceDaov22{
	function save( $blockname, $parentcontextid, $pagetypepattern, $defaultregion, $defaultweight, &$dbhm22){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[BlockInstanceDaov22:26:save]--------->");
		
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
	
		//registra al usuario
		$INSERT_BLOCK_INSTANCE = 'INSERT INTO  '.$CFG_v22->prefix.'block_instances ('.
				'blockname, parentcontextid, showinsubcontexts, pagetypepattern,'.
				'subpagepattern, defaultregion, defaultweight, configdata) VALUES '.
				'(?, ?, 0, ?,'.
				'null,?,?,\'\')';
	
		$stmt = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt, $INSERT_BLOCK_INSTANCE);
	
		mysqli_stmt_bind_param($stmt, 'sissi', $blockname, $parentcontextid, $pagetypepattern, $defaultregion, $defaultweight );
		mysqli_stmt_execute($stmt);
	
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
	
	
		$id_block_instance=mysqli_insert_id($dbhm22);
		$log->LogInfo("[BlockInstanceDaov22:58:save]".$id_block_instance."");
		mysqli_stmt_close($stmt);
		$log->LogInfo("[BlockInstanceDaov22:58:save]closed statement");
	
	
		$blockInstance = new stdClass();
		$blockInstance->id = $id_block_instance;
	
		if($localConn ){
			mysqli_close($dbhm22);
		}
	
		$log->LogInfo("[BlockInstanceDaov22:66:save]---------<");
		return $blockInstance;
	}
	
	function findBlocksPerCourse($idContextCourse, &$dbhm22){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[BlockInstanceDaov22:52:findBlocksPerCourse]--------->");
		
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
		$SELECT_BLOCK_INSTANCE = 'SELECT id, blockname FROM '.$CFG_v22->prefix.'block_instances WHERE parentcontextid=?';
		
		$log->LogInfo("[BlockInstanceDaov22:61:findBlocksPerCourse]".$SELECT_BLOCK_INSTANCE);
		
		$stmt = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt, $SELECT_BLOCK_INSTANCE);
		mysqli_stmt_bind_param($stmt, 'i', $idContextCourse);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $id, $blockname);
		
		mysqli_stmt_store_result($stmt);
		$nr=mysqli_stmt_num_rows($stmt);
		$log->LogInfo("[BlockInstanceDaov22:72:findBlocksPerCourse]numero de registros=".$nr);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("[BlockInstanceDaov22:72:findBlocksPerCourse]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
		$arrayData = array();
		$i=0;
		while (mysqli_stmt_fetch($stmt)) {
			$bi=new stdClass();
			$bi->id=$id;
			$bi->blockname=$blockname;
			$arrayData[$i++]=$bi;
		}
		
		mysqli_stmt_free_result($stmt);
		mysqli_stmt_close($stmt);
		
		$log->LogInfo("[BlockInstanceDaov22:92:findBlocksPerCourse]".count($arrayData));
		
		if($localConn ){
			mysqli_close($dbhm22);
		}
		$log->LogInfo("[BlockInstanceDaov22:103:findBlocksPerCourse]---------<");
		return $arrayData;
	}
	
	function delete($id, &$dbhm22){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[BlockInstanceDaov22:103:delete]--------->");
	
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
	
		$DELETE_BLOCK_INSTANCE = 'DELETE FROM '.$CFG_v22->prefix.'block_instances WHERE id=? ';
	
		$stmt = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt, $DELETE_BLOCK_INSTANCE);
		mysqli_stmt_bind_param($stmt, 'i', $id );
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("[BlockInstanceDaov22:171:delete]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
		mysqli_stmt_close($stmt);
	
		if($localConn ){
			mysqli_close($dbhm22);
		}
		$log->LogInfo("[BlockInstanceDaov22:127:delete]---------<");
		return true;
	}
}
?>