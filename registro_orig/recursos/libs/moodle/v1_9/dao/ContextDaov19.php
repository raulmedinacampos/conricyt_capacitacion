<?php
if(!class_exists("KLogger") ){
	require_once 'recursos/libs/KLogger/KLogger.php';
}
class ContextDaov19 {
	
	function ContextDaov19(){
		//TODO: By now nothing to do but coud be useful.
	}
	function save($contextlevel, $instanceid, $basepath, &$dbhm19){
		global $CFG_v19;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		//$log->LogInfo("[ContextDaov19:13:save]--------->");
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
		
		$basedepth=1;
		
		$INSERT_CONTEXT = 'INSERT INTO  '.$CFG_v19->prefix.'context ('.
		'contextlevel, instanceid, depth) VALUES '.
		'(?,?,?)';
		
		$stmt = mysqli_stmt_init($dbhm19);
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
				$basedepth= substr_count( $basepath, '/' ) + 1 ;
				break;
		}
		
		mysqli_stmt_bind_param($stmt, 'iii', $contextlevel, $instanceid, $basedepth);
		mysqli_stmt_execute($stmt);
		
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogInfo("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
		
		$id_context=mysqli_insert_id($dbhm19);

		mysqli_stmt_close($stmt);
		
		$log->LogInfo("[ContextDaov19:47:save]id=".$id_context."");
		
		$context = $this->find($contextlevel, $instanceid, $dbhm19);
		if(is_numeric($context)){
			$log->LogInfo("[ContextDaov19:52:save]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
		
		switch($contextlevel){
			case CONTEXT_USER:
				$context->path = '/1/'.$context->id;
				$this->update($context, $dbhm19);
				break;
			case CONTEXT_COURSE:
				$context->path = $basepath.'/'.$context->id;
				$this->update($context, $dbhm19);
				break;
			case CONTEXT_BLOCK:
				$context->path = $basepath.'/'.$context->id;
				$this->update($context, $dbhm19);
				break;
			case CONTEXT_COURSECAT:
				$context->path = $basepath.'/'.$context->id;
				$this->update($context, $dbhm19);
				break;
		}
		
		if($localConn ){
			mysqli_close($dbhm19);
		}
		//$log->LogInfo("[ContextDaov19:47:save]---------<");
		return $context;
	}
	
	function find($contextlevel, $instanceid, &$dbhm19){
		global $CFG_v19;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
// 		$log->LogInfo("[ContextDaov19:88:find]--------->");
		
		$context = new stdClass();
		$context->idContext=null;
		$context->contextlevel=null;
		$context->instanceid=null;
		$context->path=null;
		$context->depth=null;
		
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
			$log->LogError("[ContextDaov19:101:find]localConn=".$localConn."");
		}
		$SELECT_CONTEXT = 'SELECT id, contextlevel, instanceid, path, depth FROM '.$CFG_v19->prefix.'context WHERE contextlevel=? AND instanceid=?';
		
// 		$log->LogInfo("[ContextDaov19:81:find]contextlevel=".$contextlevel."-instanceid=".$instanceid."");
		$stmt2 = mysqli_stmt_init($dbhm19);
		mysqli_stmt_prepare($stmt2, $SELECT_CONTEXT);		
		mysqli_stmt_bind_param($stmt2, 'ii', $contextlevel, $instanceid);
		mysqli_stmt_execute($stmt2);
		mysqli_stmt_bind_result($stmt2, $idContext, $contextLev, $instance, $path, $depth);
		mysqli_stmt_store_result($stmt2);
		$nr=mysqli_stmt_num_rows($stmt2);
// 		$log->LogInfo("[ContextDaov19:88:find]num rows".$nr."");
		
		$errorNum=mysqli_stmt_errno($stmt2);
		if($errorNum){
// 			$log->LogError("[ContextDaov19:92:find]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
		mysqli_stmt_fetch($stmt2);
		mysqli_stmt_free_result($stmt2);
		mysqli_stmt_close($stmt2);
// 		$log->LogInfo("[ContextDaov19:95:find]".$idContext.",".$contextLev.",".$instance.",".$path.",".$depth."");
		
		$context->id= 0 + $idContext;
// 		$log->LogInfo("[ContextDaov19:104:find]context->idContext=".$context->id.",idContext=".$idContext."");
		$context->contextlevel=$contextLev;
		$context->instanceid=$instance;
		$context->path=$path;
		$context->depth=$depth;
		
// 		$log->LogInfo("[ContextDaov19:104:find]".$context->id.",".$context->contextlevel.",".$context->instanceid."");
		
		if($localConn ){
			mysqli_close($dbhm19);
		}
// 		$log->LogInfo("[ContextDaov19:111:find]---------<");
		return $context;
	}
	
	function update( $context, &$dbhm19){
		global $CFG_v19;
		//$log->LogInfo("[ContextDaov19:126:update]--------->");
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
		
		$UPDATE_CONTEXT = 'UPDATE '.$CFG_v19->prefix.'context SET path=? WHERE id=?';
		//$log->LogInfo("[ContextDaov19:126:update]".$UPDATE_CONTEXT."");
		$stmt3 = mysqli_stmt_init($dbhm19);
		mysqli_stmt_prepare($stmt3, $UPDATE_CONTEXT);
		mysqli_stmt_bind_param($stmt3, 'si', $context->path , $context->id);
		mysqli_stmt_execute($stmt3);
		
		//$log->LogInfo("[ContextDaov19:137:update]".$context->path.",".$context->id."");
		
		$errorNum=mysqli_stmt_errno($stmt3);
		if($errorNum){
			//$log->LogInfo("Error ".$errorNum.": ".mysqli_stmt_error($stmt3).".");
			return $errorNum;
		}
		
		mysqli_stmt_close($stmt3);
		if($localConn ){
			mysqli_close($dbhm19);
		}
		//$log->LogInfo("[ContextDaov19:149:update]--------->");
	}
	function delete($context, $id, &$dbhm19){
		global $CFG_v19;
		//$log->LogInfo("[ContextDaov19:153:delete]--------->");
	
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
	
		$DELETE_CONTEXT = 'DELETE FROM '.$CFG_v19->prefix.'context WHERE contextlevel = ? AND instanceid=? ';
	
		$stmt = mysqli_stmt_init($dbhm19);
		mysqli_stmt_prepare($stmt, $DELETE_CONTEXT);
		mysqli_stmt_bind_param($stmt, 'ii', $context, $id );
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			//$log->LogInfo("[ContextDaov19:169:delete]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
		mysqli_stmt_close($stmt);
	
		if($localConn ){
			mysqli_close($dbhm19);
		}
		//$log->LogInfo("[ContextDaov19:177:delete]---------<");
		return true;
	}
}
?>