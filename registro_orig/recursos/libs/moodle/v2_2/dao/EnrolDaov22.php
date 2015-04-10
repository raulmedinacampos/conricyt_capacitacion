<?php
class EnrolDaov22{
	function save(&$dbhm22, $enrol, $status, $courseid, $sortorder, 
			$enrolstartdate, $enrolenddate,				
			$notifyall, $password, $cost, $currency, 
			$roleid, $customint1, $customint2, $customint3, $customint4 ){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[EnrolDaov22:26:save]--------->");
		
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
	
		//registra al usuario
		$INSERT_ENROL = 'INSERT INTO  '.$CFG_v22->prefix.'enrol ('.
				'enrol, status, courseid, sortorder, '.
				'name, enrolperiod, enrolstartdate, enrolenddate, expirynotify, expirythreshold, '.
				'notifyall, password, cost, currency, roleid, '.
				'customint1, customint2, customint3, customint4, '.
				'customchar1, customchar2, customdec1, customdec2, '.
				'customtext1, customtext2, timecreated, timemodified) VALUES '.
				'(?,?,?,?,'.
				'null,0,?,?,0,0,'.
				'?,?,?,?,?,'.
				'?,?,?,?,'.
				'null,null,null,null,'.
				'null,null,?,?)';
		$time=time();
	
		$stmt = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt, $INSERT_ENROL);

		mysqli_stmt_bind_param($stmt, 'siii'.'ii'.'isssi'.'iiii'.'ii', $enrol, $status, $courseid, $sortorder, 
								$enrolstartdate, $enrolenddate,				
								$notifyall, $password, $cost, $currency,$roleid, 
								$customint1, $customint2, $customint3, $customint4, $time , $time );
		mysqli_stmt_execute($stmt);
	
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
	
	
		$id_enrol=mysqli_insert_id($dbhm22);
		$log->LogInfo("[EnrolDaov22:58:save]".$id_enrol."");
		mysqli_stmt_close($stmt);
		$log->LogInfo("[EnrolDaov22:58:save]closed statement");
	
	
		$enrol = new stdClass();
		$enrol->id = $id_enrol;
	
		if($localConn ){
			mysqli_close($dbhm22);
		}
	
		$log->LogInfo("[EnrolDaov22:66:save]---------<");
		return $enrol;
	}
	
	function findByCourse($idCourse, &$dbhm22){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[EnrolDaov22:52:findByCourse]--------->");
		
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
		$SELECT_ENROL = 'SELECT id,enrol FROM '.$CFG_v22->prefix.'enrol WHERE courseid=?';
		
		$stmt2 = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt2, $SELECT_ENROL);
		mysqli_stmt_bind_param($stmt2, 'i', $idCourse);
		mysqli_stmt_execute($stmt2);
		mysqli_stmt_bind_result($stmt2, $id, $enrol);
		
		mysqli_stmt_store_result($stmt2);
		$nr=mysqli_stmt_num_rows($stmt2);
		//$log->LogInfo("[EnrolDaov22:72:findByCourse]numero de registros=".$nr);
		$errorNum=mysqli_stmt_errno($stmt2);
		if($errorNum){
			$log->LogError("[EnrolDaov22:72:findByCourse]Error ".$errorNum.": ".mysqli_stmt_error($stmt2).".");
			return $errorNum;
		}
		$arrayData = array();
		$i=0;
		while (mysqli_stmt_fetch($stmt2)) {
			$bi=new stdClass();
			$bi->id=$id;
			$bi->enrol=$enrol;
			$arrayData[$i++]=$bi;
		}
		
		mysqli_stmt_free_result($stmt2);
		mysqli_stmt_close($stmt2);
		
		$log->LogInfo("[EnrolDaov22:92:findByCourse]".count($arrayData));
		
		if($localConn ){
			mysqli_close($dbhm22);
		}
		$log->LogInfo("[EnrolDaov22:111:findByCourse]---------<");
		return $arrayData;
	}


	function findByCourseEnrole($idCourse, &$dbhm22){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[EnrolDaov22:114:findByCourseEnrole]--------->");
		
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
		$SELECT_ENROL2 = 'SELECT id FROM '.$CFG_v22->prefix.'enrol WHERE courseid=? AND enrol=\'manual\'';
		
		$stmt2 = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt2, $SELECT_ENROL2);
		mysqli_stmt_bind_param($stmt2, 'i', $idCourse);
		mysqli_stmt_execute($stmt2);
		mysqli_stmt_bind_result($stmt2, $idEnrole);
		mysqli_stmt_store_result($stmt2);
		$nr=mysqli_stmt_num_rows($stmt2);

		$errorNum=mysqli_stmt_errno($stmt2);
		if($errorNum){
			$log->LogError("[EnrolDaov22:135:findByCourseEnrole]Error ".$errorNum.": ".mysqli_stmt_error($stmt2).".");
			return $errorNum;
		}
		
		mysqli_stmt_fetch($stmt2);
				$log->LogInfo("[EnrolDaov22:156:findByCourseEnrole]--------- ".$nr . " ". $idEnrole);
		mysqli_stmt_close($stmt2);
		
		$log->LogInfo("[EnrolDaov22:156:findByCourseEnrole]---------<");
		return $idEnrole;
	}

	
	function deleteAllEnrolCourse($idCourse, &$dbhm22){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[EnrolDaov22:153:delete]--------->");
		
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
		
		$DELETE_ENROL = 'DELETE FROM '.$CFG_v22->prefix.'enrol WHERE courseid=? ';
		
		$stmt = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt, $DELETE_ENROL);
		mysqli_stmt_bind_param($stmt, 'i', $idCourse );
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("[EnrolDaov22:136:delete]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
		mysqli_stmt_close($stmt);
		
		if($localConn ){
			mysqli_close($dbhm22);
		}
		$log->LogInfo("[EnrolDaov22:144:delete]---------<");
		return true;
	}
	function update(&$dbhm22, $notifyall, $status, $courseid, $sortorder, 
			$enrolstartdate, $enrolenddate,				
			$password, $cost, $currency, 
			$roleid, $customint1, $customint2, $customint3, $customint4, $id ){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CourseDaov22:186:update]--------->");
		
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
		
		$time = time();
		
		// 		//registra al usuario
		$UPDATE_ENROL = 'UPDATE '.$CFG_v22->prefix.'enrol SET '.
				'notifyall=?, status=?, courseid=?, sortorder=?,'.
				'enrolstartdate=?,enrolenddate=?,password=?,cost=?,'.
				'currency=?,roleid=?,customint1=?,customint2=?,'.
				'customint3=?,customint4=?,timemodified=? '.
				'WHERE id = ?';
		$log->LogInfo("[CourseDaov22:174:update]UPDATE_COURSE=".$UPDATE_ENROL."");
		$stmt = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt, $UPDATE_ENROL);
		mysqli_stmt_bind_param($stmt, 	'iiii'.'iiss'.
										'siii'.'iiii', $notifyall, $status, $courseid, $sortorder, 
									$enrolstartdate, $enrolenddate,				
									$password, $cost, $currency, 
									$roleid, $customint1, $customint2, $customint3, $customint4, $time, $id );
		mysqli_stmt_execute($stmt);
		
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogInfo("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
		
		mysqli_stmt_close($stmt);
		
		$log->LogInfo("[CourseDaov22:225:update]---------<");
		return true;
	}
}
?>