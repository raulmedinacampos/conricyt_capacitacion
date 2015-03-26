<?php

class ContextDaov22 {

	function save($contextlevel, $instanceid, $basepath, &$dbhm22){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		//$log->LogInfo("[ContextDaov22:13:save]--------->");
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
		
		$basedepth=1;
		
		$INSERT_CONTEXT = 'INSERT INTO  '.$CFG_v22->prefix.'context ('.
		'contextlevel, instanceid, depth) VALUES '.
		'(?,?,?)';
		
		$stmt = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt, $INSERT_CONTEXT);
		
		switch($contextlevel){
			case CONTEXT_USER:
				$basedepth+=1;
				break;
			case CONTEXT_COURSE:
				$basedepth+=2;
				break;
			case CONTEXT_BLOCK:
				$basedepth+=3;
				break;
			case CONTEXT_COURSECAT:
				$basedepth= substr_count( $basepath, '/'  ) + 1 ;
				break;
		}
		
		mysqli_stmt_bind_param($stmt, 'iii', $contextlevel, $instanceid, $basedepth);
		mysqli_stmt_execute($stmt);
		
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogInfo("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
		
		$id_context=mysqli_insert_id($dbhm22);
		mysqli_stmt_close($stmt);
		
		//$log->LogInfo("[ContextDaov22:47:save]id=".$id_context."");
		
		$context = $this->find($contextlevel, $instanceid, $dbhm22);
		if(is_numeric($context)){
			$log->LogError("[ContextDaov22:52:save]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
		switch($contextlevel){
			case CONTEXT_USER:
				$context->path = '/1/'.$context->id;
				$this->update($context, $dbhm22);
				break;
			case CONTEXT_COURSE:
				$context->path = $basepath.'/'.$context->id;
				$this->update($context, $dbhm22);
				break;
			case CONTEXT_BLOCK:
				$context->path = $basepath.'/'.$context->id;
				$this->update($context, $dbhm22);
				break;
			case CONTEXT_COURSECAT:
				$context->path = $basepath.'/'.$context->id;
				$this->update($context, $dbhm22);
				break;
		}
		
		$this->update($context, $dbhm22);
		
		if($localConn ){
			mysqli_close($dbhm22);
		}
		//$log->LogInfo("[ContextDaov22:47:save]---------<");
		return $context;
	}
	
	function find($contextlevel, $instanceid, &$dbhm22){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[ContextDaov22:64:find]--------->");
		
		$context = new stdClass();
		$context->idContext=null;
		$context->contextlevel=null;
		$context->instanceid=null;
		$context->path=null;
		$context->depth=null;
		
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
			$log->LogInfo("[ContextDaov22:64:find]localConn=".$localConn."");
		}
		
		$SELECT_CONTEXT = 'SELECT id, contextlevel, instanceid, path, depth FROM '.$CFG_v22->prefix.'context '.
				'WHERE contextlevel=? AND instanceid=?';
		
// 		$log->LogInfo("[ContextDaov22:81:find]contextlevel=".$contextlevel."-instanceid=".$instanceid."");
		$stmt2 = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt2, $SELECT_CONTEXT);		
		mysqli_stmt_bind_param($stmt2, 'ii', $contextlevel, $instanceid);
		mysqli_stmt_execute($stmt2);
		mysqli_stmt_bind_result($stmt2, $idContext, $contextLev, $instance, $path, $depth);
		mysqli_stmt_store_result($stmt2);
		$nr=mysqli_stmt_num_rows($stmt2);
// 		$log->LogInfo("[ContextDaov22:88:find]num rows".$nr."");
		
		$errorNum=mysqli_stmt_errno($stmt2);
		if($errorNum){
			$log->LogError("[ContextDaov22:92:find]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
		mysqli_stmt_fetch($stmt2);
		mysqli_stmt_free_result($stmt2);
		mysqli_stmt_close($stmt2);
// 		$log->LogInfo("[ContextDaov22:95:find]".$idContext.",".$contextLev.",".$instance.",".$path.",".$depth."");
		
		$context->id= 0 + $idContext;
// 		$log->LogInfo("[ContextDaov22:104:find]context->idContext=".$context->id.",idContext=".$idContext."");
		$context->contextlevel=$contextLev;
		$context->instanceid=$instance;
		$context->path=$path;
		$context->depth=$depth;
		
		$log->LogInfo("[ContextDaov22:133:find]id=".$context->id.",contextlevel=".$context->contextlevel.",instanceid=".$context->instanceid."");
		
		if($localConn ){
			mysqli_close($dbhm22);
		}
		$log->LogInfo("[ContextDaov22:111:find]---------<");
		return $context;
	}
	
	function update( $context, &$dbhm22){
		global $CFG_v22;
		//$log->LogInfo("[ContextDaov22:126:update]--------->");
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
		
		$UPDATE_CONTEXT = 'UPDATE '.$CFG_v22->prefix.'context SET path=? WHERE id=?';
		//$log->LogInfo("[ContextDaov22:126:update]".$UPDATE_CONTEXT."");
		$stmt3 = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt3, $UPDATE_CONTEXT);
		mysqli_stmt_bind_param($stmt3, 'si', $context->path , $context->id);
		mysqli_stmt_execute($stmt3);
		
		//$log->LogInfo("[ContextDaov22:126:update]".$context->path.",".$context->id."");
		$errorNum=mysqli_stmt_errno($stmt3);
		if($errorNum){
			//$log->LogInfo("Error ".$errorNum.": ".mysqli_stmt_error($stmt3).".");
			return $errorNum;
		}
		
		mysqli_stmt_close($stmt3);
		if($localConn ){
			mysqli_close($dbhm22);
		}
		//$log->LogInfo("[ContextDaov22:149:update]--------->");
	}
	function delete($context, $id, &$dbhm22){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[ContextDaov22:176:delete]--------->");
		
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
	
		$DELETE_CONTEXT = 'DELETE FROM '.$CFG_v22->prefix.'context WHERE contextlevel = ? AND instanceid=? ';
	
		$stmt = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt, $DELETE_CONTEXT);
		mysqli_stmt_bind_param($stmt, 'ii', $context, $id );
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("[ContextDaov22:169:delete]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
		mysqli_stmt_close($stmt);
	
		if($localConn ){
			mysqli_close($dbhm22);
		}
		$log->LogInfo("[ContextDaov22:177:delete]---------<");
		return true;
	}
}
?>